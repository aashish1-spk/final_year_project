<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // This will drop the 'tidx' column from the 'payments' table
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('tidx');
        });
    }

    public function down()
    {
        // This will add back the 'tidx' column if you ever want to roll back this migration
        Schema::table('payments', function (Blueprint $table) {
            $table->string('tidx')->nullable(); // Adjust the column type if needed
        });
    }
};
