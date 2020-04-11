<?php

namespace App\Services;

class PlayService {

    protected $availableCards = ['2','3','4','5','6','7','8','9','10','J','Q','K','A'];

    public function generateCards($playerCards) {
        $generatedCards = [];
        foreach(array_rand($this->availableCards, count($playerCards)) as $index) {
            $generatedCards[] = $this->availableCards[$index];
        }
        return $generatedCards;
    }

    public function play($playerCards, $generatedCards) {
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

        return [
            'player_score'      => $playerScore,
            'generated_score'   => $generatedScore
        ];
    }
}