<?php

namespace App\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DiscordMemberDotaParty extends Pivot
{
    protected $fillable = [
        'current_role',
        'roles',
        'is_leader'
    ];

    protected $casts = [
        'roles' => 'array',
        'is_leader' => 'boolean'
    ];
}
