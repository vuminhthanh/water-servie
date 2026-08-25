<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_code')->unique();
            $table->string('full_name');
            $table->string('phone')->index();
            $table->string('phone_normalized')->nullable()->index();
            $table->string('email')->nullable();
            $table->string('customer_type');
            $table->string('company_name')->nullable();
            $table->string('tax_code')->nullable();
            $table->foreignId('source_id')->nullable()->constrained('marketing_sources')->nullOnDelete();
            $table->text('note')->nullable();
            $table->string('status');
            $table->timestamp('last_service_at')->nullable()->index();
            $table->timestamp('next_service_at')->nullable()->index();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
