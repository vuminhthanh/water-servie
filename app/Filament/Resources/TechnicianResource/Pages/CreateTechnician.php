<?php

namespace App\Filament\Resources\TechnicianResource\Pages;

use App\Actions\Technicians\CreateTechnicianAccount;
use App\Filament\Resources\TechnicianResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTechnician extends CreateRecord
{
    public static $resource = TechnicianResource::class;

    public function create($another = false)
    {
        $this->callHook('beforeValidate');
        $this->validate();
        $this->callHook('afterValidate');

        $this->record = app(CreateTechnicianAccount::class)->execute($this->record);

        if ($another) {
            $this->fillRecord();
            $this->notify(__(static::$createdMessage));
            return;
        }

        $this->redirect($this->getRedirectUrl($this->record));
    }
}
