<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_taxes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('tax_id')->nullable();
            $table->string('name', 100);
            $table->decimal('rate', 8, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->timestamps();

            $table->index('proposal_id');
            $table->index('tax_id');
        });

        // Add tax_amount column to proposals table if it doesn't exist
        if (!Schema::hasColumn('proposals', 'tax_amount')) {
            Schema::table('proposals', function (Blueprint $table) {
                $table->decimal('tax_amount', 15, 2)->default(0)->after('subtotal');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_taxes');

        if (Schema::hasColumn('proposals', 'tax_amount')) {
            Schema::table('proposals', function (Blueprint $table) {
                $table->dropColumn('tax_amount');
            });
        }
    }
};