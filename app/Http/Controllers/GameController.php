<?php

namespace App\Http\Controllers;

use App\Models\Codigo;
use App\Models\Game;
use App\Models\Mineros;
use App\Models\Player;
use App\Models\Rule;
use App\Models\Soldado;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class GameController extends Controller
{
    public function createGame(Request $request)
    {

        //                                                      cal validar? es una perdua de recursos? XD
        $validated = $request->validate([
            'player1' => 'required|exists:users,id',
            'player2' => 'required|exists:users,id'
        ]);

        $newGame = Game::create([
            'user_id' => $validated['player1'],
            'game_type' => 1
        ]);

        // Creating both players
        $newPlayer1 = Player::create([
            'game_id' => $newGame->id,
            'user_id' => $validated['player1'],
            'player' => 1,
        ]);
        $newPlayer2 = Player::create([
            'game_id' => $newGame->id,
            'user_id' => $validated['player2'],
            'player' => 2,
        ]);

        // Creating both mineros
        $newMineros1 = Mineros::create([
            'game_id' => $newGame->id,
            'player_id' => $newPlayer1->id,
        ]);
        $newMineros2 = Mineros::create([
            'game_id' => $newGame->id,
            'player_id' => $newPlayer2->id,
        ]);

        // Creating both Codigos
        $posibleValues = ['recurso1', 'recurso2', 'recurso3', 'recurso4', 'recurso1', 'recurso2', 'recurso3', 'recurso4', 'recurso1', 'recurso2', 'recurso3', 'recurso4'];
        $newCodigo1 = Codigo::create([
            'player_id' => $newPlayer1->id,
            'c1' => Arr::random($posibleValues),
            'c2' => Arr::random($posibleValues),
            'c3' => Arr::random($posibleValues),
            'c4' => Arr::random($posibleValues),
            'c5' => Arr::random($posibleValues),
            'c6' => Arr::random($posibleValues),
            'c7' => Arr::random($posibleValues),
            'c8' => Arr::random($posibleValues),
            'c9' => Arr::random($posibleValues),
        ]);
        $newCodigo2 = Codigo::create([
            'player_id' => $newPlayer2->id,
            'c1' => Arr::random($posibleValues),
            'c2' => Arr::random($posibleValues),
            'c3' => Arr::random($posibleValues),
            'c4' => Arr::random($posibleValues),
            'c5' => Arr::random($posibleValues),
            'c6' => Arr::random($posibleValues),
            'c7' => Arr::random($posibleValues),
            'c8' => Arr::random($posibleValues),
            'c9' => Arr::random($posibleValues),
        ]);

        // Creating first soldier for each player
        $maxAtt = Rule::find(1)->soldierMaxAtt;
        $maxDeff = Rule::find(1)->soldierMaxDeff;

        $soldadoPlayer1 = Soldado::create([
            'player_id' => $newPlayer1->id,
            'atack' => rand(0, $maxAtt),
            'defense' => rand(0, $maxDeff),
            'special' => rand(0, 9)
        ]);
        $soldadoPlayer2 = Soldado::create([
            'player_id' => $newPlayer2->id,
            'atack' => rand(0, $maxAtt),
            'defense' => rand(0, $maxDeff),
            'special' => rand(0, 9)
        ]);


        return [
            'status' => 1,
            'game' => $newGame,
            'newPlayer1' => $newPlayer1,
            'newPlayer2' => $newPlayer2,
            'newCodigo1' => $newCodigo1,
            'newCodigo2' => $newCodigo2,
            'newMineros1' => $newMineros1,
            'newMineros2' => $newMineros2,
            'soldadoPlayer1' => $soldadoPlayer1,
            'soldadoPlaer2' => $soldadoPlayer2

        ];
    }

    public function indexGames(Request $request)
    {

        $allGames = Game::with('players')->get();

        return [
            'status' => 1,
            'games' => $allGames
        ];
    }
}
