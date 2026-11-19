<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('verifications', function (Blueprint $table) {
            $table->string('card_token', 32)->nullable()->unique()->after('member_id');
            $table->json('replaced_card_tokens')->nullable()->after('card_token');
            $table->timestamp('revoked_at')->nullable()->after('expirs_at');
            $table->string('revoked_reason')->nullable()->after('revoked_at');
        });

        DB::table('verifications')->whereNull('card_token')->orderBy('id')->each(function (object $verification) {
            DB::table('verifications')->where('id', $verification->id)->update(['card_token' => Str::random(32)]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verifications', function (Blueprint $table) {
            $table->dropColumn(['card_token', 'replaced_card_tokens', 'revoked_at', 'revoked_reason']);
        });
    }
};
