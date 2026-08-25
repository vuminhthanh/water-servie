<?php
namespace Database\Factories;use App\Models\{ServiceOrder,ServiceOrderItem,Product};use Illuminate\Database\Eloquent\Factories\Factory;
class ServiceOrderItemFactory extends Factory{protected $model=ServiceOrderItem::class;public function definition(){return['service_order_id'=>ServiceOrder::factory(),'product_id'=>Product::factory(),'item_type'=>'product','name'=>$this->faker->words(2,true),'quantity'=>1,'unit_price'=>100000,'cost_price'=>50000,'discount_amount'=>0,'total_amount'=>100000];}}
