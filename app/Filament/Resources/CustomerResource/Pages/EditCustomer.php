<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Actions\Customers\UpdateCustomer;
use App\Filament\Resources\CustomerResource;
use Filament\Resources\Pages\EditRecord;

class EditCustomer extends EditRecord
{
    public static $resource = CustomerResource::class;

    public function save()
    {
        $this->callHook('beforeValidate');
        $this->validate();
        $this->callHook('afterValidate');

        $addressLine = $this->record->initial_address;
        $this->record->clearInitialAddressInput();
        $this->record = app(UpdateCustomer::class)->execute($this->record, $addressLine);

        $this->notify(__(static::$savedMessage));
    }
}
