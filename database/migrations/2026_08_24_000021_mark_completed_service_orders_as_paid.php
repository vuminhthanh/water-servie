<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class MarkCompletedServiceOrdersAsPaid extends Migration
{
    public function up()
    {
        DB::table('service_orders')
            ->where('status', 'completed')
            ->where('payment_status', '!=', 'refunded')
            ->update([
                'paid_amount' => DB::raw('total_amount'),
                'payment_status' => 'paid',
            ]);
    }

    public function down()
    {
        // Không thể khôi phục trạng thái thanh toán cũ một cách an toàn.
    }
}
