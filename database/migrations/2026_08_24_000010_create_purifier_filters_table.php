<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurifierFiltersTable extends Migration
{
    public function up()
    {
        Schema::create('purifier_filters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purifier_id')->constrained('water_purifiers')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->unsignedInteger('filter_position')->nullable();
            $table->string('filter_name', 150);
            $table->date('installed_at')->nullable();
            $table->date('last_replace_at')->nullable();
            $table->unsignedInteger('replacement_months')->nullable();
            $table->date('next_replace_at')->nullable()->index();
            $table->string('status', 30)->default('active')->index();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['purifier_id', 'status']);
            $table->index(['next_replace_at', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('purifier_filters');
    }
}
