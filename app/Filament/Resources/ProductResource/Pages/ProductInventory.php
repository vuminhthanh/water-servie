<?php
namespace App\Filament\Resources\ProductResource\Pages;
use App\Actions\Inventory\AdjustProductStock;use App\Filament\Resources\ProductResource;use DomainException;use Filament\Resources\Pages\Page;use Illuminate\Database\Eloquent\ModelNotFoundException;use Illuminate\Validation\ValidationException;
class ProductInventory extends Page
{
 public static $resource=ProductResource::class;public static $view='filament.resources.product-resource.pages.inventory';public $record;public $movement_type='receipt';public $quantity;public $note;
 protected $rules=['movement_type'=>'required|in:receipt,issue','quantity'=>'required|numeric|min:0.01','note'=>'nullable|string|max:1000'];
 public function mount($record){$this->record=static::getQuery()->find($record);if(!$this->record)throw(new ModelNotFoundException())->setModel(static::getModel(),[$record]);$this->abortIfForbidden();}
 public function adjustStock(){$this->validate();$change=(float)$this->quantity*($this->movement_type==='issue'?-1:1);try{app(AdjustProductStock::class)->execute($this->record,$change,$this->movement_type,$this->note);}catch(DomainException $e){throw ValidationException::withMessages(['quantity'=>$e->getMessage()]);}$this->record->refresh();$this->quantity=null;$this->note=null;$this->notify('Đã cập nhật tồn kho');}
 public static function getTitle(){return'Quản lý tồn kho';}public static function getBreadcrumbs(){return[static::getResource()::generateUrl()=>'Sản phẩm'];}
 protected function viewData(){return['movements'=>$this->record->inventoryMovements()->with('serviceOrder')->latest()->limit(100)->get()];}
}
