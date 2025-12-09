<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tblschool_report_card', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_school_id');
            $table->string('filename', 255);
            $table->enum('term', ['Term1','Term2','Term3']);
            $table->date('upload_date');
            $table->string('report_card_file', 255)->nullable();
            $table->binary('file_blob')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->unsignedInteger('file_size')->nullable();
            $table->char('sha256', 64)->nullable();
            $table->dateTime('created_on')->nullable();
            
            $table->index('student_school_id', 'student_school_id');
        });

        // Change file_blob to MEDIUMBLOB
        DB::statement('ALTER TABLE tblschool_report_card MODIFY file_blob MEDIUMBLOB NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('tblschool_report_card');
    }
};
