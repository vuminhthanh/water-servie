<?php

namespace App\Filament\Resources\TechnicianResource\Pages;

use App\Actions\Technicians\UpdateTechnicianAccount;
use App\Filament\Resources\TechnicianResource;
use Filament\Resources\Forms\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTechnician extends EditRecord
{
    public static $resource = TechnicianResource::class;

    public function save()
    {
        $this->callHook('beforeValidate');
        $this->validate();
        $this->callHook('afterValidate');

        $accountData = [
            'name' => $this->record->account_name,
            'email' => $this->record->account_email,
            'password' => $this->record->account_password,
        ];
        $this->record->clearAccountInputs();
        $this->record = app(UpdateTechnicianAccount::class)->execute($this->record, $accountData);

        $this->notify(__(static::$savedMessage));
    }

    protected function actions()
    {
        return array_merge([
            Actions\Button::make('Xem lịch sử kỹ thuật viên')
                ->url(TechnicianResource::generateUrl('history', ['record' => $this->record])),
        ], parent::actions());
    }
}
