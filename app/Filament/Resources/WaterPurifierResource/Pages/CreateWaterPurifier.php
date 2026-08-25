<?php

namespace App\Filament\Resources\WaterPurifierResource\Pages;

use App\Filament\Resources\WaterPurifierResource;
use App\Models\CustomerAddress;
use Filament\Resources\Pages\CreateRecord;

class CreateWaterPurifier extends CreateRecord
{
    public static $resource = WaterPurifierResource::class;

    public function updatedRecordCustomerId($customerId): void
    {
        $this->record['installation_address'] = CustomerAddress::query()
            ->where('customer_id', $customerId)
            ->orderByDesc('is_default')
            ->orderBy('id')
            ->value('address_line');
    }

}
