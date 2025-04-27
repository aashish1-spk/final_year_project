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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade'); // Job seeker
            $table->foreignId('company_id')->constrained('users')->onDelete('cascade'); // Company
            $table->foreignId('job_id')->nullable()->constrained()->onDelete('set null');
            $table->tinyInteger('rating')->comment('1 to 5');
            $table->text('comment');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
