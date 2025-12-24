<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('website_settings')) {
            Schema::create('website_settings', function (Blueprint $table) {
                $table->id();
                $table->string('site_name')->nullable();
                $table->string('site_url')->nullable();
                $table->string('site_prefix')->nullable()->default('site');
                $table->string('shop_prefix')->nullable()->default('shop');
                $table->string('site_logo')->nullable();
                $table->string('site_favicon')->nullable();
                $table->enum('site_mode', ['website_only', 'ecommerce_only', 'both'])->default('both');
                $table->unsignedBigInteger('homepage_id')->nullable();
                $table->string('contact_email')->nullable();
                $table->string('contact_phone', 50)->nullable();
                $table->string('meta_title')->nullable();
                $table->text('meta_description')->nullable();
                $table->boolean('is_active')->default(1);
                $table->timestamps();
            });

            // Insert default row
            DB::table('website_settings')->insert([
                'site_name' => config('app.name', 'My Website'),
                'site_url' => config('app.url', 'http://localhost'),
                'site_prefix' => 'site',
                'shop_prefix' => 'shop',
                'site_mode' => 'both',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
