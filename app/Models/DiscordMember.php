<?php

namespace App\Models;

use App\Pivots\DiscordMemberDotaParty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DiscordMember extends Model
{
    protected $fillable = [
        'member_id',
        'username',
        'discriminator'
    ];

    public function dotaParties(): BelongsToMany
    {
        return $this->belongsToMany(DotaParty::class)->using(DiscordMemberDotaParty::class)->withPivot('current_role', 'roles', 'is_leader');
    }

    public function getMentionAttribute(): string
    {
        return "<@{$this->member_id}>";
    }

    public function getDiscordLeaderEmojiAttribute(): string
    {
        return ($this->pivot->is_leader ?? false) ? ':crown:' : '';
    }
}
