<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWaterPurifiersTable extends Migration
{
    public function up()
    {
        Schema::create('water_purifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('address_id')->nullable()->constrained('customer_addresses')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('purifier_brands')->nullOnDelete();
            $table->foreignId('model_id')->nullable()->constrained('purifier_models')->nullOnDelete();
            $table->string('serial_number', 100)->nullable()->index();
            $table->string('custom_name', 150)->nullable();
            $table->date('installed_at')->nullable();
            $table->date('purchased_at')->nullable();
            $table->dateTime('last_service_at')->nullable();
            $table->dateTime('next_service_at')->nullable()->index();
            $table->unsignedInteger('water_input_tds')->nullable();
            $table->unsignedInteger('water_output_tds')->nullable();
            $table->string('status', 30)->default('active')->index();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('water_purifiers');
    }
}
