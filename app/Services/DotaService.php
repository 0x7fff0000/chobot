<?php

namespace App\Services;

use App\Models\DiscordMember;
use App\Models\DotaParty;
use Exception;

class DotaService
{
    public static function joinParty(DiscordMember $member, DotaParty $party, bool $bLeader = false): void
    {
        $member->dotaParties->each(fn (DotaParty $dotaParty) => static::leaveParty($member, $dotaParty));

        if ($party->members()->count() > 4) {
            throw new Exception('Party is full');
        }

        $party->members()->attach($member, [
            'roles' => DotaParty::DEFAULT_ROLES,
            'is_leader' => $bLeader && !$party->members()->wherePivot('is_leader', true)->exists()
        ]);
    }

    public static function leaveParty(DiscordMember $member, DotaParty $party): void
    {
        $party->members()->detach($member);

        if (!$party->members()->count()) {
            $party->delete();
        }

        if ($member->is($party->members()->wherePivot('is_leader', true)->first())) {
            $party->members()->updateExistingPivot($party->members()->random(), [
                'is_leader' => true
            ]);
        }
    }

    public static function getParty(int $partyId): ?DotaParty
    {
        return DotaParty::find($partyId);
    }

    public static function getMemberParty(DiscordMember $member): ?DotaParty
    {
        return $member->dotaParties()->latest()->first();
    }

    public static function hasParty(DiscordMember $member): bool
    {
        return $member->dotaParties()->exists();
    }

    public static function getPartyInfo(DotaParty $party): string
    {
        $info = "Party number: {$party->id}\n";

        $party->members->each(function (DiscordMember $member) use (&$info) {
            $info .= "{$member->mention} {$member->discord_leader_emoji} " . implode(', ', $member->pivot->roles) . "\n";
        });

        return $info;
    }

    public static function rollMember(DiscordMember $member, array $roles): int
    {
        $nRoles = count($member->pivot->roles);

        if (!$nRoles) {
            return 0;
        }

        $memberRoles = $member->pivot->roles;

        $diffedRoles = array_diff($roles, $memberRoles);

        $filteredRoles = array_filter($roles, fn (int $role) => !in_array($role, $diffedRoles));

        $role = $filteredRoles[array_rand($filteredRoles)];

        $memberRoleKey = array_search($role, $memberRoles);

        unset($memberRoles[$memberRoleKey]);

        $member->pivot->roles = array_values($memberRoles);

        $member->pivot->current_role = $role;
        $member->pivot->save();

        return $role;
    }

    public static function rollParty(DotaParty $party): string
    {
        $rollInfo = "Party {$party->id} roll\n";

        $availableRoles = DotaParty::DEFAULT_ROLES;

        $party->members->sort(fn (DiscordMember $member) => count($member->pivot->roles))->each(function (DiscordMember $member) use (&$availableRoles, &$rollInfo) {
            $role = static::rollMember($member, $availableRoles);

            $roleKey = array_search($role, $availableRoles);

            if ($roleKey !== false) {
                unset($availableRoles[$roleKey]);
            }

            $rollInfo .= "{$member->mention} {$member->discord_leader_emoji}" . " $role\n";
        });

        return $rollInfo;
    }

    public static function isValidRoleNumber(int $role): bool
    {
        return in_array($role, DotaParty::DEFAULT_ROLES);
    }

    public static function setPartyMemberRoles(DiscordMember $member, array $roles): array
    {
        $party = static::getMemberParty($member);

        if (!$party) {
            throw new Exception("You are not in a party");
        }

        $validRoles = [];

        foreach ($roles as $role) {
            $role = intval($role);

            if (!static::isValidRoleNumber($role)) {
                continue;
            }

            $validRoles[] = $role;
        }

        $validRoles = array_unique($validRoles);

        if (count($validRoles) > DotaParty::MAX_ROLES) {
            $validRoles = array_slice($validRoles, 0, DotaParty::MAX_ROLES);
        }

        $party->members()->updateExistingPivot($member, [
            'roles' => $validRoles
        ]);

        return $validRoles;
    }

    public static function addPartyMemberRole(DiscordMember $member, $role): void
    {
        $party = static::getMemberParty($member);

        if (!$party) {
            throw new Exception("You are not in a party");
        }

        $role = intval($role);

        if (!static::isValidRoleNumber($role)) {
            throw new Exception('Invalid role number');
        }

        $partyMember = $party->members()->wherePivot($member->getForeignKey(), $member->id)->first();

        $memberRoles = $partyMember->pivot->roles;
        $memberRoles[] = $role;

        $partyMember->pivot->roles = array_unique($memberRoles);
        $partyMember->pivot->save();
    }

    public static function removePartyMemberRole(DiscordMember $member, $role): void
    {
        $party = static::getMemberParty($member);

        if (!$party) {
            throw new Exception("You are not in a party");
        }

        $role = intval($role);

        if (!static::isValidRoleNumber($role)) {
            throw new Exception('Invalid role number');
        }

        $partyMember = $party->members()->wherePivot($member->getForeignKey(), $member->id)->first();

        if (!in_array($role, $partyMember->pivot->roles)) {
            throw new Exception('You do not have this role');
        }

        $memberRoles = $partyMember->pivot->roles;

        $roleKey = array_search($role, $memberRoles);

        unset($memberRoles[$roleKey]);

        $partyMember->pivot->roles = array_values($memberRoles);
        $partyMember->pivot->save();
    }

    public static function refreshPartyRoles(DotaParty $party): void
    {
        $party->members->each(fn (DiscordMember $member) => static::setPartyMemberRoles($member, DotaParty::DEFAULT_ROLES));
    }
}
