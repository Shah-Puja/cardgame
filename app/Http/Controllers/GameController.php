<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GameRequest;
use App\Services\PlayService;
use App\Game;
use App\Player;
use Validator;
use DB;

class GameController extends Controller
{
    public function score_board(){
        $score_board = $this->scores();
        return view('welcome', compact('score_board'));
    }

    public function scores(){
        $score = Game::with([
            'player'
        ])
        ->select(DB::raw("player_id,count(*) as total_played, sum(user_won) as total_won, players.player_name"))
        ->join('players', 'players.id', '=', 'games.player_id')
        ->groupBy('player_id')
        ->orderBy('total_won', 'desc')
        ->limit(25)
        ->get();
        return $score;
    }

    public function play(GameRequest $request){
        if(isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                "errors" => $request->validator->errors(), 
                "success" =>  false
            ], 200);
        }
        $playerCards = explode(" ", $request->player_cards);
       // echo "<pre>";print_r($playerCards);die;
        $playService = new PlayService();
        $generatedCards = $playService->generateCards($playerCards);
        $result = $playService->play($playerCards, $generatedCards);
        $playerScore = $result['player_score'];
        $generatedScore = $result['generated_score'];
       // echo "<pre>";print_r($generatedScore);die;
        $player = Player::firstOrCreate(['player_name' => $request->player_name]);
        // Store game play in database
        Game::create([
            'player_id'   => $player->id,
            'player_score'  => $playerScore,
            'computer_score'=> $generatedScore,
            'user_won'      => ($generatedScore <= $playerScore)
        ]);
        $score_board = $this->scores();
        return response()->json([
            "message" => ($generatedScore <= $playerScore) ? "Congratulations! You are the winner." : "Sorry! You lost this game.", 
            "success" =>  true, 
            "generated_cards" => implode(",", $generatedCards),
            "player_score"      => $playerScore,
            "computer_score"    => $generatedScore,
            "winner" => ($generatedScore <= $playerScore) ? $request->player_name : "Computer",
            "score_board" =>$score_board
        ], 200);
    }
}
