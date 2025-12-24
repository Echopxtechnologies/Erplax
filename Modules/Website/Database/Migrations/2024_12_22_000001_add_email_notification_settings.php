<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            // Check if columns exist before adding
            if (!Schema::hasColumn('website_settings', 'send_customer_order_email')) {
                $table->boolean('send_customer_order_email')->default(true)->after('order_notification_email');
            }
            if (!Schema::hasColumn('website_settings', 'send_admin_order_alert')) {
                $table->boolean('send_admin_order_alert')->default(true)->after('send_customer_order_email');
            }
        });

        // Remove old column if exists and rename
        if (Schema::hasColumn('website_settings', 'send_order_email') && !Schema::hasColumn('website_settings', 'send_customer_order_email')) {
            Schema::table('website_settings', function (Blueprint $table) {
                $table->renameColumn('send_order_email', 'send_customer_order_email');
            });
        }
    }

    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            if (Schema::hasColumn('website_settings', 'send_admin_order_alert')) {
                $table->dropColumn('send_admin_order_alert');
            }
        });
    }
};
