<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('university_report_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('university_student_id');
            $table->string('filename', 255);
            $table->date('upload_date');
            $table->enum('report_card_term', [
                '1Y1S', '1Y2S', '2Y1S', '2Y2S', '3Y1S', '3Y2S', '4Y1S', '4Y2S', '5Y1S', '5Y2S'
            ]);
            $table->enum('current_term', [
                '1Y1S', '1Y2S', '2Y1S', '2Y2S', '3Y1S', '3Y2S', '4Y1S', '4Y2S', '5Y1S', '5Y2S'
            ])->nullable();
            $table->unsignedTinyInteger('semester_end_month');
            $table->year('semester_end_year');
            $table->string('file_path', 500)->nullable();
            $table->longText('file_data')->nullable()->comment('Base64 encoded file or BLOB');
            $table->string('mime_type', 100)->nullable();
            $table->unsignedInteger('file_size')->nullable();
            $table->timestamps();
            
            $table->index('university_student_id', 'urc_student_idx');
            $table->index('report_card_term', 'urc_term_idx');
            $table->index(['semester_end_year', 'semester_end_month'], 'urc_semester_idx');
            
            $table->foreign('university_student_id', 'urc_student_fk')
                  ->references('id')->on('university_students')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('university_report_cards');
    }
};
