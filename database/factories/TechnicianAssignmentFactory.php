<?php
namespace Database\Factories;use App\Enums\TechnicianAssignmentStatus;use App\Models\{ServiceOrder,Technician,TechnicianAssignment};use Illuminate\Database\Eloquent\Factories\Factory;
class TechnicianAssignmentFactory extends Factory{protected $model=TechnicianAssignment::class;public function definition(){return['service_order_id'=>ServiceOrder::factory(),'technician_id'=>Technician::factory(),'assigned_at'=>now(),'status'=>TechnicianAssignmentStatus::ASSIGNED];}}
