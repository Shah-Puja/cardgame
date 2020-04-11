<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

        <!-- Optional theme -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">


        <title>ACTO Card Game</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
       
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    </head>
    <body>
        <div id="app">
            <div class="container">
                <h1>ACTO Card Game</h1>
                <hr>
            
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
                <h2>New Game</h2>
                <form id="play_game" name="play_game">
                    <div class="form-group">
                        <label for="">Player Name</label>
                        <input type="text" name="player_name" class="form-control"id="player_name" placeholder="Enter your name" required>
                    </div>
                    <div class="form-group">
                        <label for="">Enter Cards</label>
                        <input type="text" name="player_cards" class="form-control"  placeholder="Valid cards are: 2,3,4,5,6,7,8,9,10,J,Q,K,A" id="player_cards" required>
                    </div>
                    
                   <div id="winner_results"></div> 
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Play</button>
                    </div>

                </form>
            </div>
        </div>
        
    </div>

    <div class="row justify-content-center">
            <h2>Score Board</h2>
            <div class="scoreboardajax">
            <table class="table table-stripped">
                <thead>
                    <tr>
                        <th>Player names</th>
                        <th>Total games played</th>
                        <th>Total games won</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($score_board as $score)
                    <tr>
                        <td>{{ $score->player_name }}</td>
                        <td>{{ $score->total_played }}</td>
                        <td>{{ $score->total_won }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
      <script type="text/javascript">
$(document).ready(function () {
    $("#play_game").submit(function (e) {
            e.preventDefault();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "/play",
            method: "POST",
            data: {player_name:$('#player_name').val(), player_cards:$('#player_cards').val()},
            success: function (result) {
                if(result.generated_cards!=""){
                    console.log(result);
                    $('.scoreboardajax').html('');
                   var str = '';
                   str+='<div>Computer Cards:'+ result.generated_cards +'</div>';
                   str+='<div>Your Score:'+ result.player_score +'</div>';
                   str+='<div>Computer Score:'+ result.computer_score +'</div>';
                   str+='<div>Winner:'+ result.winner +'</div>';
                   $('#winner_results').html(str);

                    
                   var scoreboard = '';
                   scoreboard +='<table class="table table-stripped"><thead><tr><th>Player names</th><th>Total games played</th><th>Total games won</th></tr></thead><tbody>';
                   $.each(result.score_board, function(k, v) {
                        scoreboard += '<tr><td>'+v.player_name+'</td>';
                        scoreboard += '<td>'+v.total_played+'</td>';
                        scoreboard += '<td>'+v.total_won+'</td></tr>';
                    });
                    scoreboard +='</tbody></table>';
                    $('.scoreboardajax').html(scoreboard);
                    //$('.scoreboardajax').css('display','block');
                   
                }
            },
        });
    });
});
      </script>
    </body>
</html>
