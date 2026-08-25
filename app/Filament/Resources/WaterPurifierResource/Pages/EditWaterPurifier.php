<?php

namespace App\Filament\Resources\WaterPurifierResource\Pages;

use App\Filament\Resources\WaterPurifierResource;
use App\Models\CustomerAddress;
use Filament\Resources\Forms\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWaterPurifier extends EditRecord
{
    public static $resource = WaterPurifierResource::class;

    public function updatedRecordCustomerId($customerId): void
    {
        $this->record->installation_address = CustomerAddress::query()
            ->where('customer_id', $customerId)
            ->orderByDesc('is_default')
            ->orderBy('id')
            ->value('address_line');
    }

    protected function actions()
    {
        return array_merge([
            Actions\Button::make('Xem lịch sử sản phẩm')
                ->url(WaterPurifierResource::generateUrl('history', ['record' => $this->record])),
        ], parent::actions());
    }
}
