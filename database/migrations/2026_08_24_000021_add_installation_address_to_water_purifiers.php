<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddInstallationAddressToWaterPurifiers extends Migration
{
    public function up()
    {
        Schema::table('water_purifiers', function (Blueprint $table) {
            $table->string('installation_address', 500)->nullable()->after('address_id');
        });

        DB::table('water_purifiers')
            ->whereNotNull('address_id')
            ->orderBy('id')
            ->chunkById(100, function ($purifiers) {
                $addresses = DB::table('customer_addresses')
                    ->whereIn('id', $purifiers->pluck('address_id')->filter())
                    ->pluck('address_line', 'id');

                foreach ($purifiers as $purifier) {
                    if (isset($addresses[$purifier->address_id])) {
                        DB::table('water_purifiers')->where('id', $purifier->id)->update([
                            'installation_address' => $addresses[$purifier->address_id],
                        ]);
                    }
                }
            });
    }

    public function down()
    {
        Schema::table('water_purifiers', function (Blueprint $table) {
            $table->dropColumn('installation_address');
        });
    }
}
