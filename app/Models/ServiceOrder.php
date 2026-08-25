<?php
namespace App\Models;
use App\Enums\{ServiceOrderType,ServiceOrderStatus,PaymentStatus};use Illuminate\Database\Eloquent\Factories\HasFactory;use Illuminate\Database\Eloquent\Model;use Illuminate\Database\Eloquent\SoftDeletes;
class ServiceOrder extends Model
{
    use HasFactory,SoftDeletes;
    public $adminInputs=[];
    protected $fillable=['order_code','customer_id','purifier_id','address_id','service_address','lead_id','order_type','status','scheduled_at','started_at','completed_at','cancelled_at','issue_description','technician_note','internal_note','input_tds','output_tds','subtotal','discount_amount','shipping_fee','total_amount','paid_amount','payment_status','created_by'];
    protected $casts=['order_type'=>ServiceOrderType::class,'status'=>ServiceOrderStatus::class,'payment_status'=>PaymentStatus::class,'scheduled_at'=>'datetime','started_at'=>'datetime','completed_at'=>'datetime','cancelled_at'=>'datetime','subtotal'=>'decimal:2','discount_amount'=>'decimal:2','shipping_fee'=>'decimal:2','total_amount'=>'decimal:2','paid_amount'=>'decimal:2'];
    public function customer(){return $this->belongsTo(Customer::class);}public function purifier(){return $this->belongsTo(WaterPurifier::class,'purifier_id');}public function address(){return $this->belongsTo(CustomerAddress::class);}public function lead(){return $this->belongsTo(Lead::class);}public function creator(){return $this->belongsTo(User::class,'created_by');}public function items(){return $this->hasMany(ServiceOrderItem::class);}public function assignments(){return $this->hasMany(TechnicianAssignment::class);}public function statusHistories(){return $this->hasMany(ServiceOrderStatusHistory::class);}public function filterReplacementHistories(){return $this->hasMany(FilterReplacementHistory::class);}
    public function setAdminTechnicianIdAttribute($v){$this->attributes['admin_technician_id']=$v;}public function getAdminTechnicianIdAttribute(){return $this->attributes['admin_technician_id']??null;}public function setAdminAssignmentNoteAttribute($v){$this->attributes['admin_assignment_note']=$v;}public function getAdminAssignmentNoteAttribute(){return $this->attributes['admin_assignment_note']??null;}
    public function setAdminItemProductIdAttribute($v){$this->attributes['admin_item_product_id']=$v;}public function getAdminItemProductIdAttribute(){return $this->attributes['admin_item_product_id']??null;}public function setAdminItemTypeAttribute($v){$this->attributes['admin_item_type']=$v;}public function getAdminItemTypeAttribute(){return $this->attributes['admin_item_type']??'product';}public function setAdminItemNameAttribute($v){$this->attributes['admin_item_name']=$v;}public function getAdminItemNameAttribute(){return $this->attributes['admin_item_name']??null;}public function setAdminItemQuantityAttribute($v){$this->attributes['admin_item_quantity']=$v;}public function getAdminItemQuantityAttribute(){return $this->attributes['admin_item_quantity']??1;}public function setAdminItemUnitPriceAttribute($v){$this->attributes['admin_item_unit_price']=$v;}public function getAdminItemUnitPriceAttribute(){return $this->attributes['admin_item_unit_price']??null;}public function setAdminItemDiscountAttribute($v){$this->attributes['admin_item_discount']=$v;}public function getAdminItemDiscountAttribute(){return $this->attributes['admin_item_discount']??0;}public function setAdminItemNoteAttribute($v){$this->attributes['admin_item_note']=$v;}public function getAdminItemNoteAttribute(){return $this->attributes['admin_item_note']??null;}
    public function clearAdminInputs(): void
    {
        foreach (array_keys($this->attributes) as $key) {
            if (strpos($key, 'admin_') === 0) unset($this->attributes[$key]);
        }
    }
}
