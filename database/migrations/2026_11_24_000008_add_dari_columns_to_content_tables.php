<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Dari versions of the public content. Empty means the Dari site shows the English text.
     */
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('name_fa')->nullable()->after('name');
            $table->string('title_fa')->nullable()->after('title');
            $table->longText('body_fa')->nullable()->after('body');
        });

        Schema::table('galeries', function (Blueprint $table) {
            $table->string('name_fa')->nullable()->after('name');
            $table->longText('details_fa')->nullable()->after('details');
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->string('name_fa')->nullable()->after('name');
            $table->longText('details_fa')->nullable()->after('details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn(['name_fa', 'title_fa', 'body_fa']);
        });

        Schema::table('galeries', function (Blueprint $table) {
            $table->dropColumn(['name_fa', 'details_fa']);
        });

        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn(['name_fa', 'details_fa']);
        });
    }
};
