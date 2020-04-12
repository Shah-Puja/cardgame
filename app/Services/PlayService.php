<?php

namespace App\Services;
use App\Game;
use App\Player;
use DB;

class PlayService {

    protected $availableCards = ['2','3','4','5','6','7','8','9','10','J','Q','K','A'];

    public function generateCards($playerCards) {
        $generatedCards = [];
        foreach(array_rand($this->availableCards, count($playerCards)) as $index) {
            $generatedCards[] = $this->availableCards[$index];
        }
        return $generatedCards;
    }

    public function play($playerCards, $generatedCards,$player_name) {
        $generatedScore = 0;
        $playerScore = 0;
        // Compare Player's and Generated Cards and calculate scores
        foreach($playerCards as $index => $card) {
            $playerCardValue = array_search($card, $this->availableCards);
            $generatedCardValue = array_search($generatedCards[$index], $this->availableCards);
            if($playerCardValue >= $generatedCardValue) {
                $playerScore++;
            } else {
                $generatedScore++;
            }
        }
        $player = Player::firstOrCreate(['player_name' => $player_name]);
        // Store game play in database
        Game::create([
            'player_id'   => $player->id,
            'player_score'  => $playerScore,
            'computer_score'=> $generatedScore,
            'user_won'      => ($generatedScore <= $playerScore)
        ]);

        return [
            'player_score'      => $playerScore,
            'generated_score'   => $generatedScore
        ];
    }

    public function getScores(){
        return Game::with([
            'player'
        ])
        ->select(DB::raw("player_id,count(*) as total_played, sum(user_won) as total_won, players.player_name"))
        ->join('players', 'players.id', '=', 'games.player_id')
        ->groupBy('player_id')
        ->orderBy('total_won', 'desc')
        ->limit(25)
        ->get();
    }
}