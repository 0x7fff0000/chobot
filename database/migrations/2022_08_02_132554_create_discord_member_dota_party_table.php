<?php

use App\Models\DiscordMember;
use App\Models\DotaParty;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscordMemberDotaPartyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discord_member_dota_party', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(DiscordMember::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(DotaParty::class)->constrained()->cascadeOnDelete();

            $table->unsignedTinyInteger('current_role')->nullable();
            $table->json('roles');
            $table->boolean('is_leader')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('discord_user_dota_party');
    }
}
