<?php

namespace App\Services;

use App\Models\Customer;
use Illuminate\Support\Str;

class CustomerCodeGenerator
{
    public function temporary(): string
    {
        return 'KH_TMP_' . Str::upper(Str::random(20));
    }

    public function generate(Customer $customer): string
    {
        $createdDate = ($customer->created_at ?: now())->format('Ymd');

        return sprintf('KH_%s_%d', $createdDate, $customer->getKey());
    }
}
