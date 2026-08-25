<?php

namespace App\Filament\Resources\TechnicianResource\Pages;

use App\Filament\Resources\TechnicianResource;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TechnicianHistory extends Page
{
    public static $resource = TechnicianResource::class;
    public static $view = 'filament.resources.technician-resource.pages.history';
    public $record;

    public function mount($record): void
    {
        $this->record = static::getQuery()->with('user')->find($record);

        if (! $this->record) {
            throw (new ModelNotFoundException())->setModel(static::getModel(), [$record]);
        }

        $this->abortIfForbidden();
    }

    public static function getTitle()
    {
        return 'Lịch sử kỹ thuật viên';
    }

    public static function getBreadcrumbs()
    {
        return [static::getResource()::generateUrl() => 'Kỹ thuật viên'];
    }
}
