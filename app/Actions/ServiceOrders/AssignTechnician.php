<?php

namespace App\Actions\ServiceOrders;

use App\Domain\ServiceOrders\ServiceOrderStatusTransition;
use App\Enums\ServiceOrderStatus;
use App\Enums\TechnicianAssignmentStatus;
use App\Enums\TechnicianWorkingStatus;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderStatusHistory;
use App\Models\Technician;
use App\Models\User;
use DomainException;
use Illuminate\Support\Facades\DB;

class AssignTechnician
{
    public function execute(
        ServiceOrder $order,
        Technician $technician,
        ?User $assignedBy = null,
        ?string $note = null
    ) {
        if ($technician->trashed() || (string) $technician->working_status === TechnicianWorkingStatus::OFF) {
            throw new DomainException('Kỹ thuật viên hiện không sẵn sàng.');
        }

        return DB::transaction(function () use ($order, $technician, $assignedBy, $note) {
            $lockedOrder = ServiceOrder::query()->lockForUpdate()->findOrFail($order->id);
            $oldStatus = (string) $lockedOrder->status;

            if (in_array($oldStatus, [ServiceOrderStatus::COMPLETED, ServiceOrderStatus::CANCELLED], true)) {
                throw new DomainException('Không thể phân công kỹ thuật viên cho đơn đã đóng.');
            }

            $activeStatuses = [
                TechnicianAssignmentStatus::ASSIGNED,
                TechnicianAssignmentStatus::ACCEPTED,
                TechnicianAssignmentStatus::IN_PROGRESS,
            ];
            $current = $lockedOrder->assignments()
                ->whereIn('status', $activeStatuses)
                ->lockForUpdate()
                ->latest('assigned_at')
                ->first();

            if ($current && $current->technician_id === $technician->id) {
                if ($note !== null) {
                    $current->update(['note' => $note]);
                }

                return $current->fresh();
            }

            $isInProgress = $oldStatus === ServiceOrderStatus::IN_PROGRESS;

            if (!$isInProgress && $oldStatus !== ServiceOrderStatus::ASSIGNED) {
                app(ServiceOrderStatusTransition::class)
                    ->assertAllowed($oldStatus, ServiceOrderStatus::ASSIGNED);
            }

            if ($current) {
                $current->update(['status' => TechnicianAssignmentStatus::CANCELLED]);
                $this->releasePreviousTechnicianIfIdle($current->technician_id, $current->id);
            }

            $assignment = $lockedOrder->assignments()->create([
                'technician_id' => $technician->id,
                'assigned_at' => now(),
                'started_at' => $isInProgress ? ($lockedOrder->started_at ?: now()) : null,
                'status' => $isInProgress
                    ? TechnicianAssignmentStatus::IN_PROGRESS
                    : TechnicianAssignmentStatus::ASSIGNED,
                'assigned_by' => $assignedBy ? $assignedBy->id : null,
                'note' => $note,
            ]);

            if ($isInProgress) {
                $technician->update(['working_status' => TechnicianWorkingStatus::BUSY]);
                return $assignment;
            }

            if ($oldStatus !== ServiceOrderStatus::ASSIGNED) {
                $lockedOrder->update(['status' => ServiceOrderStatus::ASSIGNED]);
                ServiceOrderStatusHistory::create([
                    'service_order_id' => $lockedOrder->id,
                    'old_status' => $oldStatus,
                    'new_status' => ServiceOrderStatus::ASSIGNED,
                    'changed_by' => $assignedBy ? $assignedBy->id : null,
                ]);
            }

            return $assignment;
        });
    }

    private function releasePreviousTechnicianIfIdle(int $technicianId, int $excludedAssignmentId): void
    {
        $hasOtherActiveWork = \App\Models\TechnicianAssignment::query()
            ->where('technician_id', $technicianId)
            ->where('id', '!=', $excludedAssignmentId)
            ->where('status', TechnicianAssignmentStatus::IN_PROGRESS)
            ->exists();

        if (!$hasOtherActiveWork) {
            Technician::whereKey($technicianId)->update([
                'working_status' => TechnicianWorkingStatus::AVAILABLE,
            ]);
        }
    }
}
