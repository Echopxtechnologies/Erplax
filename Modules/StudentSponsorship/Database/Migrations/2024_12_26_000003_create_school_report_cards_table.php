<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_report_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_school_id');
            $table->string('filename', 255);
            $table->enum('term', ['Term1', 'Term2', 'Term3']);
            $table->date('upload_date');
            $table->string('report_card_file', 255)->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedInteger('file_size')->nullable();
            $table->string('sha256', 64)->nullable();
            $table->timestamp('created_on')->useCurrent();
            
            $table->index('student_school_id');
            $table->index('term');
            $table->index('upload_date');
        });
        
        // Add LONGBLOB column for binary file storage (up to 4GB)
        DB::statement('ALTER TABLE school_report_cards ADD COLUMN file_blob LONGBLOB AFTER report_card_file');
    }

    public function down(): void
    {
        Schema::dropIfExists('school_report_cards');
    }
};
