<?php

namespace App\Http\Controllers;

use App\Models\Codigo;
use App\Models\CodigoIntento;
use App\Models\Player;
use Illuminate\Http\Request;

class CodigoController extends Controller
{

    // Mostrar player
    public function indexCodigo(Request $request, $id)
    {
        $codigo = Codigo::find($id);

        return [
            'status' => 1,
            'player' => $codigo
        ];
    }

    public function probarCodigo($player_id, $codigo)
    {
        if (strlen($codigo) != 9) {
            return [
                'status' => 0,
                'message' => 'Solo puede ser un codigo de 9 cifras'
            ];
        }

        $player = Player::find($player_id);
        $codigoCorrecto = Codigo::where('player_id', $player_id)->first();

        $trueOrFalse = []; // por si en algun momento queremos mostrar esto (habilidad especial?)
        $cuantosTrues = 0;
        $intento = []; // para guardar el registro de intentos

        // Este for comprueba si el codigo es correcto.
        // Cuenta cuantos y cuales y lo guarda en las 2 variables anteriores
        for ($i = 0; $i < strlen($codigo); $i++) {
            $codeSent = 'recurso' . $codigo[$i];
            $codeHave = $codigoCorrecto['c' . $i + 1];
            $intento['c' . $i + 1] = $codigo[$i];
            error_log($codeSent);
            // Quitamos un recurso del player
            $player->$codeSent = $player->$codeSent - 1;
            if ($player->$codeSent < 0) {
                return ['status' => 0, 'message' => 'No se como pero has usado piedras que no tenías'];
            }
            if ($codeHave === $codeSent) {
                $trueOrFalse[$i] = true;
                $cuantosTrues++;
                $intento['result_c' . $i + 1] = true;
            } else {
                $trueOrFalse[$i] = false;
                $intento['result_c' . $i + 1] = false;
            }
        }

        $intento['result_count'] = $cuantosTrues;
        $intento['player_id'] = $player_id;

        CodigoIntento::create($intento);
        $player->save();
        return [
            'status' => 1,
            'cuantosTrue' => $cuantosTrues,
            'trueorfalse' => $trueOrFalse,
            'code Sent' => $codigo,
            'codigo correcto' => $codigoCorrecto,
            'plauer' => $player,
        ];
    }

    public function indexCodigoIntentos($player_id)
    {
        $intentos = CodigoIntento::select('c1', 'c2', 'c3', 'c4', 'c5', 'c6', 'c7', 'c8', 'c9', 'result_count')->orderBy('id', 'desc')->where('player_id', $player_id)->get();

        $mejorasi = [];

        for ($i = 0; $i < $intentos->count(); $i++) {
            $helper = [];
            for ($j = 0; $j < 9; $j++) {
                array_push($helper, $intentos[$i]['c' . $j + 1]);
            }
            array_push($mejorasi, ['codigo' => $helper, 'result' => $intentos[$i]->result_count]);
        }
        return [
            'status' => 1,
            'intentos' => $mejorasi
        ];
    }
}
