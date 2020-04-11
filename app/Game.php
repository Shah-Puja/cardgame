<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $table = "games";
    protected $fillable = ['player_id', 'player_score', 'computer_score', 'user_won'];

    /**
     * Get the player that played the game.
     */
    public function player() {
        return $this->belongsTo('App\Player');
    }
}
