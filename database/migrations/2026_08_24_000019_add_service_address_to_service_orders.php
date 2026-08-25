<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddServiceAddressToServiceOrders extends Migration
{
    public function up()
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->string('service_address', 500)->nullable()->after('address_id');
        });

        DB::table('service_orders')
            ->whereNotNull('address_id')
            ->orderBy('id')
            ->chunkById(200, function ($orders) {
                $addresses = DB::table('customer_addresses')
                    ->whereIn('id', $orders->pluck('address_id')->filter())
                    ->pluck('address_line', 'id');

                foreach ($orders as $order) {
                    if (isset($addresses[$order->address_id])) {
                        DB::table('service_orders')->where('id', $order->id)->update([
                            'service_address' => $addresses[$order->address_id],
                        ]);
                    }
                }
            });

        DB::table('service_orders')
            ->whereNull('service_address')
            ->whereNotNull('purifier_id')
            ->orderBy('id')
            ->chunkById(200, function ($orders) {
                $purifiers = DB::table('water_purifiers')
                    ->whereIn('id', $orders->pluck('purifier_id')->filter())
                    ->pluck('address_id', 'id');
                $addresses = DB::table('customer_addresses')
                    ->whereIn('id', $purifiers->values()->filter())
                    ->pluck('address_line', 'id');

                foreach ($orders as $order) {
                    $addressId = isset($purifiers[$order->purifier_id])
                        ? $purifiers[$order->purifier_id]
                        : null;

                    if ($addressId && isset($addresses[$addressId])) {
                        DB::table('service_orders')->where('id', $order->id)->update([
                            'service_address' => $addresses[$addressId],
                        ]);
                    }
                }
            });
    }

    public function down()
    {
        Schema::table('service_orders', function (Blueprint $table) {
            $table->dropColumn('service_address');
        });
    }
}
