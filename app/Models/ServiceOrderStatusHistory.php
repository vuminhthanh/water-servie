<?php
namespace App\Models;use Illuminate\Database\Eloquent\Factories\HasFactory;use Illuminate\Database\Eloquent\Model;
class ServiceOrderStatusHistory extends Model{use HasFactory;const UPDATED_AT=null;protected $fillable=['service_order_id','old_status','new_status','changed_by','note'];public function serviceOrder(){return $this->belongsTo(ServiceOrder::class);}public function changedBy(){return $this->belongsTo(User::class,'changed_by');}}
