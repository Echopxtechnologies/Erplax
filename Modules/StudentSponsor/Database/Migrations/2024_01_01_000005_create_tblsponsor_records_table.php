<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblsponsor_records', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('entity_type', ['sponsor'])->default('sponsor');
            $table->string('name', 255)->nullable();
            $table->string('sponsor_type', 20)->nullable();
            $table->string('sponsor_occupation', 255)->nullable();
            $table->string('contact_no', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('zip', 20)->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('bank_id')->nullable();
            $table->string('sponsor_bank_branch_info', 255)->nullable();
            $table->string('sponsor_bank_branch_number', 100)->nullable();
            $table->string('sponsor_bank_account_no', 100)->nullable();
            $table->string('sponsor_frequency', 20)->nullable();
            $table->integer('product_id')->nullable();
            $table->date('membership_start_date')->nullable();
            $table->date('membership_end_date')->nullable();
            $table->longText('school_internal_ids')->nullable();
            $table->longText('university_internal_ids')->nullable();
            $table->dateTime('created_on')->nullable()->useCurrent();
            $table->tinyInteger('active')->default(0);
            
            $table->unique('email', 'email');
            $table->unique('sponsor_bank_account_no', 'sponsor_bank_account_no');
            $table->index('bank_id', 'bank_id');
            $table->index('country_id', 'country_id');
            $table->index('product_id', 'product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tblsponsor_records');
    }
};
