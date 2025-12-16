<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add PDF settings if they don't exist
        $settings = [
            ['key' => 'pdf_primary_color', 'value' => '#1e40af', 'group' => 'pdf'],
            ['key' => 'pdf_secondary_color', 'value' => '#f3f4f6', 'group' => 'pdf'],
            ['key' => 'pdf_show_logo', 'value' => '1', 'group' => 'pdf'],
            ['key' => 'pdf_show_gst', 'value' => '1', 'group' => 'pdf'],
            ['key' => 'pdf_show_terms', 'value' => '1', 'group' => 'pdf'],
            ['key' => 'pdf_show_signature', 'value' => '1', 'group' => 'pdf'],
            ['key' => 'pdf_show_notes', 'value' => '1', 'group' => 'pdf'],
            ['key' => 'pdf_compact_mode', 'value' => '1', 'group' => 'pdf'],
            ['key' => 'pdf_font_size', 'value' => '9', 'group' => 'pdf'],
        ];

        foreach ($settings as $setting) {
            $exists = DB::table('purchase_settings')->where('key', $setting['key'])->exists();
            if (!$exists) {
                DB::table('purchase_settings')->insert(array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }
    }

    public function down(): void
    {
        DB::table('purchase_settings')->whereIn('key', [
            'pdf_primary_color', 'pdf_secondary_color', 'pdf_show_logo',
            'pdf_show_gst', 'pdf_show_terms', 'pdf_show_signature',
            'pdf_show_notes', 'pdf_compact_mode', 'pdf_font_size',
        ])->delete();
    }
};
