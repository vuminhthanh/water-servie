<?php

namespace App\Filament\Resources\WaterPurifierResource\Pages;

use App\Filament\Resources\WaterPurifierResource;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WaterPurifierHistory extends Page
{
    public static $resource = WaterPurifierResource::class;

    public static $view = 'filament.resources.water-purifier-resource.pages.history';

    public $record;

    public function mount($record): void
    {
        $this->record = static::getQuery()->find($record);

        if (! $this->record) {
            throw (new ModelNotFoundException())->setModel(static::getModel(), [$record]);
        }

        $this->abortIfForbidden();
    }

    public static function getTitle()
    {
        return 'Lịch sử sản phẩm của khách hàng';
    }

    public static function getBreadcrumbs()
    {
        return [
            static::getResource()::generateUrl() => 'Sản phẩm của khách hàng',
        ];
    }
}
