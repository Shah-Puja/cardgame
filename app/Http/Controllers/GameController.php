<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\GameRequest;
use App\Services\PlayService;
use Validator;

class GameController extends Controller
{
    public function index()
    {
        $leaderboard = $this->scores();
        return response()->json([
            'success'   => true,
            'leaderboard'   => $leaderboard
        ], 200);
    }
    
    public function score_board(){
        $score_board = $this->scores();
        return view('welcome', compact('score_board'));
    }

    public function scores(){
        $playService = new PlayService();
        return $playService->getScores();
    }

    public function play(GameRequest $request){
        if(isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                "errors" => $request->validator->errors(), 
                "success" =>  false
            ], 200);
        }
        $playerCards = explode(" ", $request->player_cards);
        $playService = new PlayService();
        $generatedCards = $playService->generateCards($playerCards);
        $result = $playService->play($playerCards, $generatedCards, $request->player_name);
        $playerScore = $result['player_score'];
        $generatedScore = $result['generated_score'];
      
        $score_board = $playService->getScores();
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
