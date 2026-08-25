<?php

namespace App\Filament\Resources\MarketingSourceResource\Pages;

use App\Filament\Resources\MarketingSourceResource;
use Filament\Resources\Pages\ListRecords;

class ListMarketingSources extends ListRecords
{
    public static $resource = MarketingSourceResource::class;

    public static function getQuery()
    {
        return parent::getQuery()->withCount('leads');
    }
}
