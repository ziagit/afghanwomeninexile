<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The day an activity happened, so the site lists them in that order rather than by when they were entered.
     * Existing activities start on the day they were entered.
     */
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->date('published_at')->nullable()->after('image');
        });

        DB::table('activities')->update(['published_at' => DB::raw('DATE(created_at)')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('published_at');
        });
    }
};
