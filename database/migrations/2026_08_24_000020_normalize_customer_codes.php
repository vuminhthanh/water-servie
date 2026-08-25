<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class NormalizeCustomerCodes extends Migration
{
    public function up()
    {
        DB::table('customers')->orderBy('id')->chunkById(200, function ($customers) {
            foreach ($customers as $customer) {
                $createdDate = $customer->created_at
                    ? date('Ymd', strtotime($customer->created_at))
                    : now()->format('Ymd');

                DB::table('customers')->where('id', $customer->id)->update([
                    'customer_code' => sprintf('KH_%s_%d', $createdDate, $customer->id),
                ]);
            }
        });
    }

    public function down()
    {
        // Mã cũ không thể khôi phục chính xác sau khi đã chuẩn hóa.
    }
}
