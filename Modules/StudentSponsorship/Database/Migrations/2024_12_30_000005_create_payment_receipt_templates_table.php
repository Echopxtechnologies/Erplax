<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentReceiptTemplatesTable extends Migration
{
    public function up(): void
    {
        Schema::create('payment_receipt_templates', function (Blueprint $table) {
            $table->id();
            $table->string('currency', 3)->unique()->comment('Currency code (LKR, USD, GBP, etc.)');
            $table->string('currency_name')->nullable()->comment('Full currency name');
            $table->string('currency_symbol', 10)->nullable()->comment('Currency symbol');
            
            // Organization details
            $table->string('organization_name')->default('Student Sponsorship Program');
            $table->text('organization_address')->nullable();
            $table->string('organization_phone')->nullable();
            $table->string('organization_email')->nullable();
            $table->string('organization_website')->nullable();
            $table->string('organization_logo')->nullable()->comment('Path to logo image');
            
            // Receipt content
            $table->string('receipt_title')->default('Payment Receipt');
            $table->text('header_text')->nullable()->comment('Text shown below title');
            $table->text('footer_text')->nullable()->comment('Text shown at bottom');
            $table->text('thank_you_message')->nullable();
            
            // Bank details (for reference)
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('bank_swift_code')->nullable();
            
            // Styling
            $table->string('primary_color', 7)->default('#2563eb')->comment('Hex color');
            $table->string('secondary_color', 7)->default('#1e40af');
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Insert default templates for common currencies
        $currencies = [
            ['currency' => 'LKR', 'currency_name' => 'Sri Lankan Rupees', 'currency_symbol' => 'Rs.'],
            ['currency' => 'USD', 'currency_name' => 'US Dollars', 'currency_symbol' => '$'],
            ['currency' => 'GBP', 'currency_name' => 'British Pounds', 'currency_symbol' => '£'],
            ['currency' => 'EUR', 'currency_name' => 'Euros', 'currency_symbol' => '€'],
            ['currency' => 'AUD', 'currency_name' => 'Australian Dollars', 'currency_symbol' => 'A$'],
            ['currency' => 'CAD', 'currency_name' => 'Canadian Dollars', 'currency_symbol' => 'C$'],
        ];
        
        foreach ($currencies as $c) {
            \DB::table('payment_receipt_templates')->insert([
                'currency' => $c['currency'],
                'currency_name' => $c['currency_name'],
                'currency_symbol' => $c['currency_symbol'],
                'organization_name' => 'Student Sponsorship Program',
                'receipt_title' => 'Payment Receipt',
                'thank_you_message' => 'Thank you for your generous contribution to support student education.',
                'footer_text' => 'This is a computer-generated receipt and does not require a signature.',
                'primary_color' => '#2563eb',
                'secondary_color' => '#1e40af',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_receipt_templates');
    }
}
