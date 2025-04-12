<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('cvs', function (Blueprint $table) {
            $table->text('objective')->nullable()->after('phone');
            $table->text('certifications')->nullable()->after('education');
            $table->text('languages')->nullable()->after('certifications');
            $table->text('references')->nullable()->after('languages');
            $table->string('github_link')->nullable()->after('references');
            $table->string('linkedin_link')->nullable()->after('github_link');
        });
    }

    public function down(): void
    {
        Schema::table('cvs', function (Blueprint $table) {
            $table->dropColumn([
                'objective', 
                'certifications', 
                'languages', 
                'references', 
                'github_link', 
                'linkedin_link'
            ]);
        });
    }
};
