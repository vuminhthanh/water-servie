<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagePathToProductsAndWaterPurifiers extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('description');
        });

        Schema::table('water_purifiers', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('custom_name');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('water_purifiers', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
    }
}
