<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Verification extends Model
{
    public const DEFAULT_ROLE = 'Member';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_EXPIRED = 'expired';

    public const STATUS_REVOKED = 'revoked';

    protected $fillable = [
        'first_name',
        'last_name',
        'role',
        'dob',
        'location',
        'joined_at',
        'expirs_at',
        'note',
        'picture',
        'member_id',
    ];

    protected $casts = [
        'dob' => 'date',
        'joined_at' => 'date',
        'expirs_at' => 'date',
        'revoked_at' => 'datetime',
        'replaced_card_tokens' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function (Verification $verification) {
            $verification->card_token ??= static::newCardToken();
        });

        static::saving(function (Verification $verification) {
            $role = trim((string) ($verification->role ?? ''));
            $verification->role = $role !== '' ? $role : self::DEFAULT_ROLE;
        });
    }

    public static function newCardToken(): string
    {
        return Str::random(32);
    }

    public function status(): string
    {
        return match (true) {
            $this->revoked_at !== null => self::STATUS_REVOKED,
            $this->expirs_at !== null && $this->expirs_at->endOfDay()->isPast() => self::STATUS_EXPIRED,
            default => self::STATUS_ACTIVE,
        };
    }

    // Invalidates the printed card (its QR stops verifying) and gives the member a new one.
    public function replaceCard(): void
    {
        $this->replaced_card_tokens = [...($this->replaced_card_tokens ?? []), $this->card_token];
        $this->card_token = static::newCardToken();
        $this->save();
    }

    public function scopeWithReplacedCardToken(Builder $query, string $token): Builder
    {
        return $query->whereJsonContains('replaced_card_tokens', $token);
    }

    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        $today = now()->startOfDay();

        return match ($status) {
            self::STATUS_REVOKED => $query->whereNotNull('revoked_at'),
            self::STATUS_EXPIRED => $query->whereNull('revoked_at')->whereNotNull('expirs_at')->where('expirs_at', '<', $today),
            self::STATUS_ACTIVE => $query->whereNull('revoked_at')->where(fn (Builder $query) => $query->whereNull('expirs_at')->orWhere('expirs_at', '>=', $today)),
            default => $query,
        };
    }
}
