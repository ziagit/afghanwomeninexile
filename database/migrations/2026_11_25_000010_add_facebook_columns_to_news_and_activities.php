<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The Facebook Page post made for each item, or why posting failed.
     */
    public function up(): void
    {
        foreach (['news', 'activities'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('facebook_post_id')->nullable();
                $table->text('facebook_error')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['news', 'activities'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn(['facebook_post_id', 'facebook_error']);
            });
        }
    }
};
