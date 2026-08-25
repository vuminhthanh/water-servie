<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurifierBrandsTable extends Migration
{
    public function up()
    {
        Schema::create('purifier_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 50)->unique();
            $table->string('status', 30)->default('active')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purifier_brands');
    }
}
