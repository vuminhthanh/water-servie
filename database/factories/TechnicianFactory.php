<?php
namespace Database\Factories;use App\Enums\TechnicianWorkingStatus;use App\Models\{Technician,User};use Illuminate\Database\Eloquent\Factories\Factory;
class TechnicianFactory extends Factory{protected $model=Technician::class;public function definition(){return['user_id'=>User::factory(),'technician_code'=>strtoupper($this->faker->unique()->bothify('TECH-####')),'phone'=>$this->faker->numerify('09########'),'working_status'=>TechnicianWorkingStatus::AVAILABLE];}}
