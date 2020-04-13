<template>
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-md-8">
                <h2>New Game</h2>
                <form @submit.prevent="playGame()" >
                    <ul style="color: red">
                        <li v-for="error in errors" v-bind:key="error"> {{ error }} </li>
                    </ul>
                    <div class="form-group">
                        <label for="">Player Name</label>
                        <input type="text" name="player_name" class="form-control" v-model="newGame.player_name" id="player_name" required>
                    </div>
                    <div class="form-group">
                        <label for="">Enter Cards</label>
                        <input type="text" name="player_cards" class="form-control" v-model="newGame.player_cards" placeholder="Valid cards are: 2,3,4,5,6,7,8,9,10,J,Q,K,A" id="player_cards" required>
                    </div>
                    <div class="form-group" v-if="result.generated_cards !== ''">
                        <label for="">Computer Cards: {{ result.generated_cards }} </label>
                    </div>
                    <div class="form-group" v-if="result.player_score >= 0">
                        <label for="">Your Score: {{ result.player_score }} </label>
                    </div>
                    <div class="form-group" v-if="result.computer_score >= 0">
                        <label for="">Computer Score: {{ result.computer_score }} </label>
                    </div>
                    <div class="form-group" v-if="result.winner !== ''">
                        <label for="">Winner: {{ result.winner }} </label>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Play</button>
                    </div>

                </form>
            </div>
        </div>
        <div class="row justify-content-center">
            <h2>Leader Board</h2>
            <table class="table table-stripped">
                <thead>
                    <tr>
                        <th>Player names</th>
                        <th>Total Games Played</th>
                        <th>Total Games Won</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="game in games" v-bind:key="game.player_name">
                        <td>{{ game.player.player_name }}</td>
                        <td>{{ game.total_played }}</td>
                        <td>{{ game.total_won }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                errors: [],
                newGame: {
                    player_name: '',
                    player_cards: ''
                },
                result: {
                    winner: '',
                    generated_cards: '',
                    player_score: -1,
                    computer_score: -1
                },
                games: [],
                game: {
                    player_name: '',
                    total_game_played: 0,
                    total_game_won: 0
                }
            }
        },
        created() {
            this.fetchLeaderBoard();
        },
        methods: {
            playGame() {
                this.errors = [];
                this.result.generated_cards = '';
                this.result.winner = '';
                this.result.computer_score = -1;
                this.result.player_score = -1;
                fetch('api/game/play', {
                    method: "POST",
                    body: JSON.stringify(this.newGame),
                    headers: {
                        'content-type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(res => {
                    if(res.success === false) {
                        console.log(res.errors);
                        let errors = Object.values(res.errors);
                        errors = errors.flat();
                        this.errors = errors;
                    } else {
                        this.errors = [];
                        this.result.generated_cards = res.generated_cards;
                        this.result.player_score = res.player_score;
                        this.result.computer_score = res.computer_score;
                        this.result.winner = res.winner;
                        this.fetchLeaderBoard();
                    }
                })
                .catch(err => {
                    console.log(err);
                    alert("Something went wrong")
                });
            },
            fetchLeaderBoard() {
                fetch('api/game/leaderboard')
                .then(res => res.json())
                .then(res => {
                    if(res.success !== true) {

                    } else {
                        this.games = res.leaderboard;
                    }
                })
                .catch(err => {
                    alert("Some error occurred. Please try later");
                });
            }
        }

    }
</script>
