<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilterReplacementHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('filter_replacement_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purifier_filter_id')->nullable()->constrained('purifier_filters')->nullOnDelete();
            $table->foreignId('purifier_id')->constrained('water_purifiers')->cascadeOnDelete();
            $table->foreignId('old_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->foreignId('new_product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->unsignedBigInteger('service_order_id')->nullable()->index();
            $table->unsignedBigInteger('technician_id')->nullable()->index();
            $table->dateTime('replaced_at')->index();
            $table->unsignedInteger('replacement_months')->nullable();
            $table->date('next_replace_at')->nullable()->index();
            $table->unsignedInteger('input_tds')->nullable();
            $table->unsignedInteger('output_tds')->nullable();
            $table->string('old_filter_name', 150)->nullable();
            $table->string('new_filter_name', 150)->nullable();
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('unit_cost', 15, 2)->default(0);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->text('note')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->index(['purifier_id', 'replaced_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('filter_replacement_histories');
    }
}
