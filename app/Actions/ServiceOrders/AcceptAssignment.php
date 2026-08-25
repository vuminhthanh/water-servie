<?php
namespace App\Actions\ServiceOrders;use App\Enums\TechnicianAssignmentStatus;use App\Models\TechnicianAssignment;use DomainException;
class AcceptAssignment{public function execute(TechnicianAssignment $a):TechnicianAssignment{if((string)$a->status!==TechnicianAssignmentStatus::ASSIGNED)throw new DomainException('Assignment cannot be accepted');$a->update(['status'=>TechnicianAssignmentStatus::ACCEPTED,'accepted_at'=>now()]);return $a->refresh();}}
