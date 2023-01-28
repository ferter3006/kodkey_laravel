<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Mineros;
use App\Models\Player;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function gameNextTurn($id)
    {
        $game = Game::with('players')->find($id);

        $player1 = Player::find($game->players->first()->id);
        $player1->puntos_accion = $player1->puntos_accion + 2;
        $player1->save();

        $player2 = Player::find($game->players->last()->id);
        $player2->puntos_accion = $player2->puntos_accion + 2;
        $player2->save();

        $game->turno++;
        $game->save();

        $game->turno % 2 === 0 ? $player = $player2 : $player = $player1;

        $mineros = Mineros::where('player_id', $player->id)->first();

        $helper = ['Tierra', 'Aire', 'Agua', 'Fuego'];
        $arrayResults = [];

        for ($i = 0; $i < 4; $i++) {
            $lvl_minerox = 'lvl_minero' . $i + 1;
            $recursox = 'recurso' . $i + 1;
            $lvlMina = $mineros->$lvl_minerox;
            $chance = $lvlMina * 5 - 5 + 10;
            $randomNum = rand(0, 100);

            if ($randomNum <= $chance) {
                $player->$recursox = $player->$recursox + 1;
                array_push($arrayResults, $helper[$i]);
            }
        }
        $player->save();


        return [
            'status' => 1,
            'turno' => $game->turno,
            'arrayresults' => $arrayResults
        ];
    }

    public function gameStatus($id)
    {
        $game = Game::with('players')->find($id);

        if ($game->turno % 2 === 0) {
            $haveToPlay = 2;
        } else {
            $haveToPlay = 1;
        }

        $user1 = User::find($game->players->first()->user_id);
        $user2 = User::find($game->players->last()->user_id);

        $playerPlayingThisTurn = $game->players->where('player', $haveToPlay)->first();
        $playerWaitingThisTurn = $game->players->where('player', '!=', $haveToPlay)->first();

        $player1 = $game->players->first();
        $player2 = $game->players->last();

        return [
            'status' => 1,
            'gameId' => $game->id,
            'turno' => $game->turno,
            'idPlayerPlaying' => $playerPlayingThisTurn->id,
            'user_idPlayerPlaying' => $playerPlayingThisTurn->user_id,
            'idPlayerWaiting' => $playerWaitingThisTurn->id,
            'user_idPlayerWaiting' => $playerWaitingThisTurn->user_id,
            'player1Status' => [
                'user_id' => $user1->id,
                'user_name' => $user1->name,
                'PA' => $player1->puntos_accion,
                'recursos' => [
                    'recurso1' => $player1->recurso1,
                    'recurso2' => $player1->recurso2,
                    'recurso3' => $player1->recurso3,
                    'recurso4' => $player1->recurso4,
                ],
                'soldadosInventario' => $player1->soldados->where('ubicacion', 0)->count(),
                'soldadosEnDefensa' => $player1->soldados->where('ubicacion', 2)->count(),
                'soldados' => $player1->soldados,
                'mineros' => $player1->mineros->only('id', 'lvl_minero1', 'lvl_minero2', 'lvl_minero3', 'lvl_minero4'),
                'codigo' => $player1->codigo->only('id', 'c1', 'c2', 'c3', 'c4', 'c5', 'c6', 'c7', 'c8', 'c9'),
            ],
            'player2Status' => [
                'user_id' => $user2->id,
                'user_name' => $user2->name,
                'PA' => $player2->puntos_accion,
                'recursos' => [
                    'recurso1' => $player2->recurso1,
                    'recurso2' => $player2->recurso2,
                    'recurso3' => $player2->recurso3,
                    'recurso4' => $player2->recurso4,
                ],
                'soldadosInventario' => $player2->soldados->where('ubicacion', 0)->count(),
                'soldadosEnDefensa' => $player2->soldados->where('ubicacion', 2)->count(),
                'soldados' => $player2->soldados,
                'mineros' => $player2->mineros->only('id', 'lvl_minero1', 'lvl_minero2', 'lvl_minero3', 'lvl_minero4'),
                'codigo' => $player2->codigo->only('id', 'c1', 'c2', 'c3', 'c4', 'c5', 'c6', 'c7', 'c8', 'c9'),
            ]


        ];
    }
}
