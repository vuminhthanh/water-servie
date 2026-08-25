<?php
namespace App\Filament\Resources\ServiceOrderResource\Pages;
use App\Actions\ServiceOrders\{AddServiceOrderItem,AssignTechnician,CancelServiceOrder,CompleteServiceOrder,ConfirmServiceOrder,MarkTechnicianOnTheWay,RecalculateServiceOrderTotals,StartServiceOrder};use App\Enums\ServiceOrderStatus;use App\Filament\Resources\ServiceOrderResource;use App\Models\{CustomerAddress,Product,Technician};use DomainException;use Filament\Resources\Forms\Actions;use Filament\Resources\Forms\Form;use Filament\Resources\Pages\EditRecord;use Illuminate\Validation\ValidationException;
class EditServiceOrder extends EditRecord
{
 public static $resource=ServiceOrderResource::class;
 public function canDelete(){return false;}
 public function delete(){abort(403,'Đơn dịch vụ đã khởi tạo không được phép xóa.');}
 public function mount($record){parent::mount($record);$this->fillAssignmentInputs();}
 protected function form(Form $form){$form=parent::form($form);if($this->isClosed()){foreach($form->getFlatSchema() as $field){if(method_exists($field,'disabled'))$field->disabled();}}return$form;}
 public function updatedRecordCustomerId($customerId){$this->record->service_address=CustomerAddress::query()->where('customer_id',$customerId)->orderByDesc('is_default')->orderBy('id')->value('address_line');}
 public function updatedRecordAdminItemProductId($productId){$product=$productId?Product::find($productId):null;$this->record->admin_item_type='product';$this->record->admin_item_name=$product?$product->name:null;$this->record->admin_item_unit_price=$product?$product->selling_price:null;if(!$this->record->admin_item_quantity)$this->record->admin_item_quantity=1;}
 public function save(){if($this->isClosed())throw new DomainException('Đơn đã đóng, không thể cập nhật.');$technicianId=$this->record->admin_technician_id;$note=$this->record->admin_assignment_note;$current=$this->record->assignments()->whereIn('status',['assigned','accepted','in_progress'])->latest('assigned_at')->first();$item=$this->pendingItem();if($item)$this->validateItem($item['product_id'],$item['data']);if((float)$this->record->paid_amount<0)throw ValidationException::withMessages(['record.paid_amount'=>'Số tiền đã thanh toán không được âm.']);$this->record->clearAdminInputs();parent::save();if($technicianId&&(!$current||$current->technician_id!=(int)$technicianId)){app(AssignTechnician::class)->execute($this->record,Technician::findOrFail($technicianId),null,$note);$this->record->refresh();}if($item)$this->storeItem($item['product_id'],$item['data']);app(RecalculateServiceOrderTotals::class)->execute($this->record);$this->fillAssignmentInputs();$this->reloadPage();}
 public function confirmOrder(){app(ConfirmServiceOrder::class)->execute($this->record);$this->record->refresh();$this->notify('Đã xác nhận đơn');}
 public function assignTechnician(){if(!$this->record->admin_technician_id)throw new DomainException('Vui lòng chọn kỹ thuật viên');app(AssignTechnician::class)->execute($this->record,Technician::findOrFail($this->record->admin_technician_id),null,$this->record->admin_assignment_note);$this->record->refresh();$this->fillAssignmentInputs();$this->notify('Đã phân công kỹ thuật viên');}
 public function onTheWay(){app(MarkTechnicianOnTheWay::class)->execute($this->record);$this->record->refresh();$this->notify('Đã chuyển trạng thái đang di chuyển');}
 public function startOrder(){$a=$this->record->assignments()->whereIn('status',['assigned','accepted'])->latest()->firstOrFail();app(StartServiceOrder::class)->execute($this->record,$a->technician);$this->record->refresh();$this->notify('Đã bắt đầu thực hiện');}
 public function completeOrder(){try{app(CompleteServiceOrder::class)->execute($this->record);}catch(DomainException $e){throw ValidationException::withMessages(['record.admin_item_product_id'=>$e->getMessage()]);}$this->record->refresh();$this->notify('Đã hoàn thành đơn');}
 public function cancelOrder(){app(CancelServiceOrder::class)->execute($this->record);$this->record->refresh();$this->notify('Đã hủy đơn');}
 public function addItem(){
  if($this->isClosed())throw new DomainException('Đơn đã đóng, không thể thêm dòng hàng.');
  $productId=$this->record->admin_item_product_id;
  $itemData=['item_type'=>$this->record->admin_item_type,'name'=>$this->record->admin_item_name,'quantity'=>$this->record->admin_item_quantity,'unit_price'=>$this->record->admin_item_unit_price,'discount_amount'=>$this->record->admin_item_discount,'note'=>$this->record->admin_item_note];
  $this->validateItem($productId,$itemData);
  $this->record->clearAdminInputs();
  $this->storeItem($productId,$itemData);
  $this->fillAssignmentInputs();
  $this->notify('Đã thêm dòng đơn hàng');
  $this->reloadPage();
 }
 private function pendingItem(){
  $productId=$this->record->admin_item_product_id;
  $data=['item_type'=>$this->record->admin_item_type,'name'=>$this->record->admin_item_name,'quantity'=>$this->record->admin_item_quantity,'unit_price'=>$this->record->admin_item_unit_price,'discount_amount'=>$this->record->admin_item_discount,'note'=>$this->record->admin_item_note];
  return $productId||$data['item_type']==='service'?['product_id'=>$productId,'data'=>$data]:null;
 }
 private function validateItem($productId,array $itemData){
  if($itemData['item_type']==='product'&&!$productId){throw ValidationException::withMessages(['record.admin_item_product_id'=>'Vui lòng chọn sản phẩm.']);}
  if($itemData['item_type']==='service'&&!trim((string)$itemData['name'])){throw ValidationException::withMessages(['record.admin_item_name'=>'Vui lòng nhập tên dịch vụ.']);}
  if((float)$itemData['quantity']<=0){throw ValidationException::withMessages(['record.admin_item_quantity'=>'Số lượng phải lớn hơn 0.']);}
 }
 private function storeItem($productId,array $itemData){$p=$productId?Product::findOrFail($productId):null;app(AddServiceOrderItem::class)->execute($this->record,$p,$itemData);$this->record->refresh();}
 private function reloadPage(){return $this->redirect(route('filament.resources.service-orders.edit',$this->record));}
 private function fillAssignmentInputs(){ $assignment=$this->record->assignments()->latest('assigned_at')->first();$this->record->admin_technician_id=$assignment?$assignment->technician_id:null;$this->record->admin_assignment_note=$assignment?$assignment->note:null; }
 private function isClosed(){return isset($this->record)&&in_array((string)$this->record->status,[ServiceOrderStatus::COMPLETED,ServiceOrderStatus::CANCELLED],true);}
 protected function actions(){ $s=(string)$this->record->status;if(in_array($s,[ServiceOrderStatus::COMPLETED,ServiceOrderStatus::CANCELLED],true))return[];$a=[Actions\Button::make('Lưu thông tin')->primary()->submit(),Actions\Button::make('Thêm dòng hàng')->action('addItem')];if($s===ServiceOrderStatus::NEW)$a[]=Actions\Button::make('Xác nhận')->action('confirmOrder');if(in_array($s,[ServiceOrderStatus::NEW,ServiceOrderStatus::CONFIRMED,ServiceOrderStatus::ASSIGNED,ServiceOrderStatus::IN_PROGRESS],true))$a[]=Actions\Button::make('Phân công')->action('assignTechnician');if($s===ServiceOrderStatus::ASSIGNED)$a[]=Actions\Button::make('Đang di chuyển')->action('onTheWay');if(in_array($s,[ServiceOrderStatus::ASSIGNED,ServiceOrderStatus::ON_THE_WAY],true))$a[]=Actions\Button::make('Bắt đầu')->action('startOrder');if($s===ServiceOrderStatus::IN_PROGRESS)$a[]=Actions\Button::make('Hoàn thành')->action('completeOrder')->primary();if(!in_array($s,[ServiceOrderStatus::COMPLETED,ServiceOrderStatus::CANCELLED],true))$a[]=Actions\Button::make('Hủy đơn')->action('cancelOrder');return$a;}
}
