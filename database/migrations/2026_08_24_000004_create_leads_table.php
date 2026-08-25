<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeadsTable extends Migration
{
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('phone');
            $table->string('phone_normalized')->nullable();
            $table->string('email')->nullable();
            $table->foreignId('source_id')->nullable()->constrained('marketing_sources')->nullOnDelete();
            $table->string('campaign')->nullable();
            $table->string('medium')->nullable();
            $table->string('keyword')->nullable();
            $table->text('requirement')->nullable();
            $table->string('status');
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('contacted_at')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leads');
    }
}
