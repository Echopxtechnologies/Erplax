<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            // Primary Key
            $table->id()->comment('Primary key');
            
            // Basic Information
            $table->string('name', 191)->comment('Contact person name');
            $table->enum('customer_type', ['individual', 'company'])->default('individual')->comment('Customer type');
            $table->boolean('active')->default(true)->comment('Active status');
            $table->string('email', 100)->unique('unique_email')->comment('Email address');
            $table->string('phone', 100)->nullable()->comment('Phone number');
            
            // Company Information
            $table->string('company', 191)->nullable()->comment('Company name (for company type)');
            $table->string('vat', 50)->nullable()->comment('VAT/GST number');
            $table->string('website', 150)->nullable()->comment('Website URL');
            $table->string('group_name', 100)->nullable()->comment('Customer group');
            $table->integer('currency')->default(0)->comment('Currency ID');
            $table->string('designation', 100)->nullable()->comment('Job title/position');
            
            // Primary Address
            $table->string('address', 200)->nullable()->comment('Street address');
            $table->string('city', 100)->nullable()->comment('City');
            $table->string('state', 100)->nullable()->comment('State/Province');
            $table->string('zip_code', 15)->nullable()->comment('Postal/ZIP code');
            $table->string('country', 100)->nullable()->comment('Country');
            $table->string('latitude', 191)->nullable()->comment('GPS latitude');
            $table->string('longitude', 191)->nullable()->comment('GPS longitude');
            
            // Billing Address
            $table->string('billing_street', 200)->nullable()->comment('Billing street address');
            $table->string('billing_city', 100)->nullable()->comment('Billing city');
            $table->string('billing_state', 100)->nullable()->comment('Billing state');
            $table->string('billing_zip_code', 100)->nullable()->comment('Billing postal code');
            $table->string('billing_country', 100)->nullable()->comment('Billing country');
            
            // Shipping Address
            $table->string('shipping_address', 200)->nullable()->comment('Shipping street address');
            $table->string('shipping_city', 100)->nullable()->comment('Shipping city');
            $table->string('shipping_state', 100)->nullable()->comment('Shipping state');
            $table->string('shipping_zip_code', 100)->nullable()->comment('Shipping postal code');
            $table->string('shipping_country', 100)->nullable()->comment('Shipping country');
            
            // Preferences
            $table->string('default_language', 40)->nullable()->comment('Preferred language');
            $table->string('profile_image', 191)->nullable()->comment('Profile image path');
            
            // Email Notification Preferences
            $table->boolean('invoice_emails')->default(true)->comment('Receive invoice emails');
            $table->boolean('estimate_emails')->default(true)->comment('Receive estimate emails');
            $table->boolean('credit_note_emails')->default(true)->comment('Receive credit note emails');
            $table->boolean('contract_emails')->default(true)->comment('Receive contract emails');
            $table->boolean('task_emails')->default(true)->comment('Receive task emails');
            $table->boolean('project_emails')->default(true)->comment('Receive project emails');
            $table->boolean('ticket_emails')->default(true)->comment('Receive ticket emails');
            
            // Flags
            $table->boolean('is_supplier')->default(false)->comment('Is also a supplier');
            
            // References
            $table->integer('leadid')->nullable()->comment('Source lead ID');
            $table->integer('added_by')->default(0)->comment('Staff ID who added');
            
            // Notes
            $table->text('notes')->nullable()->comment('Additional notes');
            
            // Timestamps
            $table->timestamp('created_at')->useCurrent()->comment('Created timestamp');
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate()->comment('Updated timestamp');
            
            // Indexes
            $table->index('customer_type', 'idx_customer_type');
            $table->index('company', 'idx_company');
            $table->index('active', 'idx_active');
            $table->index('group_name', 'idx_group_name');
            $table->index('is_supplier', 'idx_is_supplier');
            $table->index('leadid', 'idx_leadid');
            $table->index('added_by', 'idx_added_by');



            $table->foreign('group_name', 'fk_customers_group_name')
      ->references('name')
      ->on('customer_groups')
      ->onUpdate('cascade')
      ->onDelete('set null');
        });


        
        
        DB::statement("ALTER TABLE `customers` COMMENT = 'Customers - individuals and companies'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};