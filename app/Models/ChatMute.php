<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMute extends Model
{
    protected $fillable = ['user_id', 'muted_by', 'expires_at'];

    protected $casts = ['expires_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Is this mute still active? */
    public function isActive(): bool
    {
        return is_null($this->expires_at) || $this->expires_at->isFuture();
    }

    /** Check if a given user is currently muted */
    public static function isMuted(int $userId): bool
    {
        return static::where('user_id', $userId)
            ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->exists();
    }
}
