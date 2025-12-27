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
        // ========================================
        // TABLE 1: leads_status (CREATE FIRST)
        // ========================================
        Schema::create('leads_status', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('statusorder');
            $table->string('color');
            $table->boolean('isdefault')->default(false);
            $table->timestamps();
        });

        // ========================================
        // TABLE 2: leads_sources (CREATE SECOND)
        // ========================================
        Schema::create('leads_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // ========================================
        // TABLE 3: leads (CREATE LAST)
        // ========================================
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            
            // Primary Information
            $table->string('hash')->unique();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('company')->nullable();
            $table->text('description')->nullable();
            
            // Contact Information
            $table->string('email')->nullable();
            $table->string('phonenumber')->nullable();
            $table->string('website')->nullable();
            
            // Address Information - ALL NULLABLE ✅
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            
            // Relationships - ALL NULLABLE ✅
            $table->unsignedBigInteger('assigned')->nullable();
            $table->unsignedBigInteger('source')->nullable();
            $table->unsignedBigInteger('status')->nullable();
            
            // Dates
            $table->timestamp('dateadded')->useCurrent();
            $table->timestamp('date_converted')->nullable();
            
            // Additional Fields
            $table->unsignedInteger('from_form_id')->default(0);
            $table->unsignedInteger('leadorder')->default(0);
            $table->unsignedInteger('client_id')->default(0);
            
            // Financial
            $table->decimal('lead_value', 10, 2)->default(0.00);
            $table->decimal('vat', 8, 2)->default(0.00);
            
            // Boolean Flags
            $table->boolean('lost')->default(0);
            $table->boolean('junk')->default(0);
            $table->boolean('is_imported_from_email_integration')->default(0);
            $table->boolean('is_public')->default(0);
            
            // Integration
            $table->string('email_integration_uid')->nullable();
            $table->string('default_language')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop in REVERSE order of creation ✅
        Schema::dropIfExists('leads');        
        Schema::dropIfExists('leads_sources'); 
        Schema::dropIfExists('leads_status');  
    }





    
};
