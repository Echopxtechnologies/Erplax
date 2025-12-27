<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_records', function (Blueprint $table) {
            $table->integer('time_taken')->nullable()->after('service_time'); // Time in minutes
            $table->boolean('is_paid')->default(false)->after('total_cost');
            $table->decimal('service_charge', 10, 2)->default(0)->after('is_paid');
            $table->foreignId('invoice_id')->nullable()->after('service_charge'); // Link to invoice
            $table->string('service_reference')->nullable()->unique()->after('invoice_id'); // Unique reference for invoice tracking
        });
    }

    public function down(): void
    {
        Schema::table('service_records', function (Blueprint $table) {
            $table->dropColumn(['time_taken', 'is_paid', 'service_charge', 'invoice_id', 'service_reference']);
        });
    }
};
