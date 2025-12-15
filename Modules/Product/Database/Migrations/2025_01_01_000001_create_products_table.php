<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('bank_details')) {
            Schema::create('bank_details', function (Blueprint $table) {
                $table->id();
                $table->string('holder_type', 50)->default('vendor'); // vendor, customer, employee, company
                $table->unsignedBigInteger('holder_id');
                $table->string('account_holder_name', 191);
                $table->string('bank_name', 191);
                $table->string('account_number', 50);
                $table->string('ifsc_code', 20)->nullable();
                $table->string('branch_name', 191)->nullable();
                $table->string('upi_id', 100)->nullable();
                $table->enum('account_type', ['SAVINGS', 'CURRENT', 'OTHER'])->default('CURRENT');
                $table->boolean('is_primary')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                
                $table->index(['holder_type', 'holder_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_details');
    }
};