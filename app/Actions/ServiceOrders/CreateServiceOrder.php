<?php

namespace App\Actions\ServiceOrders;

use App\Enums\PaymentStatus;
use App\Enums\ServiceOrderStatus;
use App\Models\Product;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderStatusHistory;
use App\Services\OrderCodeGenerator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class CreateServiceOrder
{
    public function execute(array $data): ServiceOrder
    {
        return DB::transaction(function () use ($data) {
            $items = $data['items'] ?? [];
            unset($data['items']);
            $data['status'] = ServiceOrderStatus::NEW;
            $data['payment_status'] = $data['payment_status'] ?? PaymentStatus::UNPAID;

            $order = $this->createWithUniqueCode($data);

            foreach ($items as $item) {
                $product = !empty($item['product_id'])
                    ? Product::findOrFail($item['product_id'])
                    : null;
                app(AddServiceOrderItem::class)->execute($order, $product, $item, false);
            }

            app(RecalculateServiceOrderTotals::class)->execute($order);
            ServiceOrderStatusHistory::create([
                'service_order_id' => $order->id,
                'old_status' => null,
                'new_status' => ServiceOrderStatus::NEW,
                'changed_by' => $order->created_by,
            ]);

            return $order->fresh(['items', 'statusHistories']);
        });
    }

    private function createWithUniqueCode(array $data): ServiceOrder
    {
        $providedCode = $data['order_code'] ?? null;

        for ($attempt = 1; $attempt <= 5; $attempt++) {
            $data['order_code'] = $providedCode ?: app(OrderCodeGenerator::class)->generate();
            try {
                return ServiceOrder::create($data);
            } catch (QueryException $exception) {
                if ($providedCode || $exception->getCode() !== '23000' || $attempt === 5) {
                    throw $exception;
                }
            }
        }
    }
}
