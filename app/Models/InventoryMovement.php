<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class InventoryMovement extends Model{protected $fillable=['product_id','type','quantity_change','balance_after','service_order_id','created_by','note'];protected $casts=['quantity_change'=>'decimal:2','balance_after'=>'decimal:2'];public function product(){return $this->belongsTo(Product::class)->withTrashed();}public function serviceOrder(){return $this->belongsTo(ServiceOrder::class);}public function creator(){return $this->belongsTo(User::class,'created_by');}}
