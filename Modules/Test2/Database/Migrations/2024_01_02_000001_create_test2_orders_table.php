<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test2_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            
            // FOREIGN KEY to test1_items
            $table->unsignedBigInteger('test1_item_id');
            $table->foreign('test1_item_id')
                  ->references('id')
                  ->on('test1_items')
                  ->onDelete('cascade');
            
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test2_orders');
    }
};
