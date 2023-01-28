<?php

namespace App\Http\Controllers;

use App\Models\Mineros;
use App\Models\Player;
use Illuminate\Http\Request;

class MinerosController extends Controller
{

    // A lo bruto sense tindre en compte antic estat xD
    public function updateMineros(Request $request, $id)
    {
        // FALTA com a minim comprobar que el token de usuari que fa la request pertanyi al joc en questió
        $mineros = Mineros::where('id', $id)->first();
        $mineros->update($request->all());

        return [
            'status' => 1,
            'player' => $mineros
        ];
    }

    public function upgradeMinero($id, $minero, $x)
    {
        $myMinero = Mineros::find($id);
        $player = Player::find($myMinero->player_id);

        if ($myMinero->$minero >= 19) {
            return [
                'status' => 0,
                'message' => 'No se puede mejorar más la mina! Tu entiendes lo que significa un 100% ?'
            ];
        }

        if ($myMinero->$minero === 0) {
            return [
                'status' => 0,
                'message' => 'Jugada Inválida, Minero explotado no puede ser upgradeado',
            ];
        }

        if ($player->puntos_accion <= 0) {
            return [
                'status' => 0,
                'message' => 'No tienes P.A. suficientes para realizar esta acción'
            ];
        } else {
            $player->puntos_accion = $player->puntos_accion - 1;
            $player->save();
        }

        if ($x !== 'explote') {
            $myMinero->$minero = $myMinero->$minero + $x;
            $myMinero->save();
        } else {
            $myMinero->$minero = 0;
            $myMinero->save();
            $stringRecurso = 'recurso' . $minero[10];
            $player->$stringRecurso = $player->$stringRecurso + 2;
            $player->save();
        }

        return [
            'status' => 1,
            'mineros' => $myMinero,
            'PA' => $player->puntos_accion,
        ];
    }

    public function picaMina($id, $recurso) 
    {

        $recursox = 'recurso' . $recurso;
        $lvl_minerox = 'lvl_minero' . $recurso;
        $helper = ['Tierra', 'Aire', 'Agua', 'Fuego'];

        $minero = Mineros::find($id);
        $player = Player::find($minero->player_id);

        $lvlMina = $minero->$lvl_minerox;
        $chance = $lvlMina * 5 - 5 + 10;
        $randomNum = rand(0, 100);


        if ($player->puntos_accion === 0) {
            return [
                'status' => 0,
                'message' => 'No tienes puntos de acción para realizar esta acción'
            ];
        }

        $player->puntos_accion = $player->puntos_accion - 1;

        if ($randomNum <= $chance) {
            $player->$recursox = $player->$recursox + 1;
            $player->save();
            $message = 'Has encontrado una piedra de ' . $helper[$recurso - 1] . '!';
        } else {
            $player->save();
            $message = 'No has encontrado nada esta vez. ¿Tendrás mejor suerte la próxima?';
        }

        return [
            'status' => '1',
            'message' => $message
        ];
    }

    public function indexMineros(Request $request, $id)
    {
        $mineros = Mineros::find($id);

        return [
            'status' => 1,
            'player' => $mineros

        ];
    }
}
