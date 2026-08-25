<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Actions\Customers\CreateCustomer as CreateCustomerAction;
use App\Filament\Resources\CustomerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    public static $resource = CustomerResource::class;

    public function create($another = false)
    {
        $this->callHook('beforeValidate');
        $this->validate();
        $this->callHook('afterValidate');

        $this->record = app(CreateCustomerAction::class)->execute($this->record);

        if ($another) {
            $this->fillRecord();
            $this->notify(__(static::$createdMessage));
            return;
        }

        $this->redirect($this->getRedirectUrl($this->record));
    }
}
