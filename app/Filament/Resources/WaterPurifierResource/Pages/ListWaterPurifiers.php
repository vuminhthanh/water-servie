<?php

namespace App\Filament\Resources\WaterPurifierResource\Pages;

use App\Filament\Resources\WaterPurifierResource;
use Filament\Resources\Pages\ListRecords;

class ListWaterPurifiers extends ListRecords
{
    public static $resource = WaterPurifierResource::class;

    public static function getQuery()
    {
        return parent::getQuery()->with(['customer', 'product', 'address']);
    }
}
