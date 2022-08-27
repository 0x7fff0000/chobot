<?php

namespace App\Models;

use App\Pivots\DiscordMemberDotaParty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DotaParty extends Model
{
    const DEFAULT_ROLES = [1, 2, 3, 4, 5];
    const MAX_ROLES = 5;

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(DiscordMember::class)->using(DiscordMemberDotaParty::class)->withPivot('current_role', 'roles', 'is_leader');
    }

    public function hasMember(DiscordMember $discordMember): bool
    {
        return $this->members()->where($discordMember->getForeignKey(), $discordMember->id)->limit(1)->exists();
    }
}
