<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RecalculateExistingServiceOrderTotals extends Migration
{
    public function up()
    {
        DB::table('service_orders')
            ->select(['id', 'discount_amount', 'shipping_fee'])
            ->orderBy('id')
            ->chunkById(100, function ($orders) {
                foreach ($orders as $order) {
                    $items = DB::table('service_order_items')
                        ->where('service_order_id', $order->id)
                        ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) AS subtotal')
                        ->selectRaw('COALESCE(SUM(discount_amount), 0) AS item_discount')
                        ->first();

                    $subtotal = (float) $items->subtotal;
                    $total = max(
                        0,
                        $subtotal
                        - (float) $items->item_discount
                        - (float) $order->discount_amount
                        + (float) $order->shipping_fee
                    );

                    DB::table('service_orders')
                        ->where('id', $order->id)
                        ->update([
                            'subtotal' => $subtotal,
                            'total_amount' => $total,
                        ]);
                }
            });
    }

    public function down()
    {
        // Giá trị tổng cũ không thể khôi phục một cách an toàn.
    }
}
