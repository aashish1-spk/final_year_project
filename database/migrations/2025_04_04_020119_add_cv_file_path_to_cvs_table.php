<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCvFilePathToCvsTable extends Migration
{
    public function up()
    {
        Schema::table('cvs', function (Blueprint $table) {
            $table->string('cv_file_path')->nullable(); // Add a column to store the file path
        });
    }

    public function down()
    {
        Schema::table('cvs', function (Blueprint $table) {
            $table->dropColumn('cv_file_path');
        });
    }
}
