<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurifierModelsTable extends Migration
{
    public function up()
    {
        Schema::create('purifier_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('purifier_brands')->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('model_code', 100)->nullable()->index();
            $table->string('purifier_type', 30)->default('ro')->index();
            $table->unsignedInteger('number_of_filters')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purifier_models');
    }
}
