<?php

namespace App\Actions\Customers;

use App\Models\Customer;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CreateCustomer
{
    public function execute(array $data): Customer
    {
        return DB::transaction(function () use ($data) {
            $addressLine = trim((string) Arr::pull($data, 'initial_address'));

            $customer = Customer::create($data);

            if ($addressLine !== '') {
                $customer->addresses()->create([
                    'address_line' => $addressLine,
                    'is_default' => true,
                ]);
            }

            return $customer->fresh(['addresses']);
        });
    }
}
