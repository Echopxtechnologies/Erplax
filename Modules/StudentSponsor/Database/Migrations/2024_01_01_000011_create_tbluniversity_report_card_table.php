<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbluniversity_report_card', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_university_id');
            $table->string('filename', 255);
            $table->string('report_card_file', 255)->nullable();
            $table->date('upload_date');
            $table->enum('report_card_term', ['1Y1S','1Y2S','2Y1S','2Y2S','3Y1S','3Y2S','4Y1S','4Y2S','5Y1S','5Y2S']);
            $table->enum('current_term', ['1Y1S','1Y2S','2Y1S','2Y2S','3Y1S','3Y2S','4Y1S','4Y2S','5Y1S','5Y2S']);
            $table->unsignedTinyInteger('semester_end_month');
            $table->year('semester_end_year');
            $table->binary('file_blob')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedInteger('file_size')->nullable();
            $table->char('sha256', 64)->nullable();
            $table->dateTime('created_on')->nullable();
            
            $table->index('student_university_id', 'student_university_id');
        });

        // Change file_blob to MEDIUMBLOB
        DB::statement('ALTER TABLE tbluniversity_report_card MODIFY file_blob MEDIUMBLOB NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('tbluniversity_report_card');
    }
};
