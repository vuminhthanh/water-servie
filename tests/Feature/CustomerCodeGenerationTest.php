<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CustomerCodeGenerationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_customer_code_is_generated_from_creation_date_and_id()
    {
        $customer = Customer::create([
            'full_name' => 'Khách tự sinh mã',
            'phone' => '0999999999',
            'customer_type' => 'individual',
            'status' => 'active',
        ]);

        $expected = 'KH_' . $customer->created_at->format('Ymd') . '_' . $customer->id;

        $this->assertSame($expected, $customer->fresh()->customer_code);
    }
}
