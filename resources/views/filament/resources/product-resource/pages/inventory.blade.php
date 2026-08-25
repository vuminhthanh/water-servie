<div>
 <x-filament::app-header :breadcrumbs="static::getBreadcrumbs()" :title="$title" />
 <x-filament::app-content class="space-y-6">
  <x-filament::card class="space-y-5">
   <div class="flex items-center justify-between"><div><div class="text-lg font-medium">{{ $record->name }}</div><div class="text-sm text-gray-500">{{ $record->sku }}</div></div><div class="text-right"><div class="text-sm text-gray-500">Tồn hiện tại</div><div class="text-2xl font-bold {{ (float)$record->stock_quantity <= (float)$record->low_stock_threshold ? 'text-danger-600' : 'text-primary-600' }}">{{ number_format((float)$record->stock_quantity,2,',','.') }} {{ $record->unit }}</div></div></div>
   <form wire:submit.prevent="adjustStock" class="grid gap-4 md:grid-cols-4">
    <div><label class="block text-sm font-medium">Loại</label><select wire:model="movement_type" class="block w-full mt-1 rounded border-gray-300"><option value="receipt">Nhập kho</option><option value="issue">Xuất kho</option></select></div>
    <div><label class="block text-sm font-medium">Số lượng</label><input wire:model="quantity" type="number" min="0.01" step="0.01" class="block w-full mt-1 rounded border-gray-300">@error('quantity')<div class="text-sm text-danger-600">{{ $message }}</div>@enderror</div>
    <div><label class="block text-sm font-medium">Ghi chú</label><input wire:model="note" class="block w-full mt-1 rounded border-gray-300"></div>
    <div class="flex items-end"><x-filament::button type="submit" color="primary">Cập nhật kho</x-filament::button></div>
   </form>
  </x-filament::card>
  <x-filament::card><div class="mb-4 text-lg font-medium">Lịch sử kho</div><div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr class="text-left border-b"><th class="py-3">Thời gian</th><th>Loại</th><th>Thay đổi</th><th>Tồn sau</th><th>Đơn</th><th>Ghi chú</th></tr></thead><tbody>@forelse($movements as $movement)<tr class="border-b"><td class="py-3">{{ $movement->created_at->format('d/m/Y H:i') }}</td><td>{{ ['receipt'=>'Nhập kho','issue'=>'Xuất kho','service_order_completed'=>'Xuất theo đơn'][$movement->type] ?? $movement->type }}</td><td class="{{ (float)$movement->quantity_change < 0 ? 'text-danger-600' : 'text-green-600' }}">{{ (float)$movement->quantity_change > 0 ? '+' : '' }}{{ number_format((float)$movement->quantity_change,2,',','.') }}</td><td>{{ number_format((float)$movement->balance_after,2,',','.') }}</td><td>@if($movement->serviceOrder)<a class="text-primary-600 hover:underline" href="{{ \App\Filament\Resources\ServiceOrderResource::generateUrl('edit',['record'=>$movement->serviceOrder]) }}">{{ $movement->serviceOrder->order_code }}</a>@endif</td><td>{{ $movement->note }}</td></tr>@empty<tr><td colspan="6" class="py-6 text-center text-gray-500">Chưa có biến động kho.</td></tr>@endforelse</tbody></table></div></x-filament::card>
 </x-filament::app-content>
</div>
