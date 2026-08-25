<?php

namespace App\Actions\Customers;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class UpdateCustomer
{
    public function execute(Customer $customer, string $addressLine): Customer
    {
        return DB::transaction(function () use ($customer, $addressLine) {
            $customer->save();

            $address = $customer->addresses()
                ->orderByDesc('is_default')
                ->orderBy('id')
                ->first();

            if ($address) {
                $address->update([
                    'address_line' => trim($addressLine),
                    'is_default' => true,
                ]);
            } else {
                $customer->addresses()->create([
                    'address_line' => trim($addressLine),
                    'is_default' => true,
                ]);
            }

            return $customer->fresh(['addresses']);
        });
    }
}
