<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Filament\Resources\LeadResource;
use Filament\Resources\Pages\ListRecords;

class ListLeads extends ListRecords
{
    public static $resource = LeadResource::class;

    public static function getQuery()
    {
        return parent::getQuery()->with(['marketingSource', 'assignedTo']);
    }
}
