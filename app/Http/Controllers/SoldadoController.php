<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Player;
use App\Models\Rule;
use App\Models\Soldado;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SoldadoController extends Controller
{
    public function indexSoldado(Request $request, $id)
    {

        $soldado = Soldado::find($id);

        return [
            'status' => 1,
            'soldado' => $soldado
        ];
    }

    public function robarSoldado($playerId)
    {
        // Contamos los soldados que estan en el inventario ( ubicacion = 0 )
        $player = Player::with('soldados')->find($playerId);
        $soldados = $player->soldados->where('ubicacion', 0);
        $soldadosInventario = $soldados->count();

        // Si son menos de 3, podemos robar otro

        // Siempre y cuando tengamos puntos de acción disponibles

        if ($player->puntos_accion <= 0) {
            return [
                'status' => 0,
                'message' => 'No tienes puntos de acción suficientes para esta acción'
            ];
        }

        if ($soldadosInventario < 3) {

            $maxAtt = Rule::find(1)->soldierMaxAtt;
            $maxDeff = Rule::find(1)->soldierMaxDeff;

            Soldado::create([
                'player_id' => $playerId,
                'atack' => rand(1, $maxAtt),
                'defense' => rand(1, $maxDeff),
                'special' => rand(0, 9)
            ]);

            $player = Player::with('soldados')->find($playerId);
            $soldados = $player->soldados->where('ubicacion', 0);

            $player->puntos_accion = $player->puntos_accion - 1;
            $player->save();

            return [
                'status' => 1,
                'PA' => $player->puntos_accion,
                'soldado' => $soldados,
            ];
        }

        // Ya tiene 3 no puede robar más

        return [
            'status' => 0,
            'message' => 'Ya tienes 3 soldados en el inventario, no puedes tener más.'

        ];
    }

    public function forjarSoldado($id)
    {
        $soldado = Soldado::find($id);

        if ($soldado->ubicacion !== 0) {
            return [
                'status' => 0,
                'message' => 'Solo se puede forjar un soldado que esté en el inventario'
            ];
        }

        $suma = $soldado->atack + $soldado->defense;
        $randomSuma = rand(0, $suma);

        $player = Player::where('id', $soldado->player_id)->first();
        $player->puntos_accion = $player->puntos_accion + $randomSuma;
        $player->save();

        $soldado->ubicacion = 3;
        $soldado->save();

        return [
            'status' => 1,
            'message' => 'Se han forjado ' . $randomSuma . ' PA!',
            'PA' => $player->puntos_accion,

        ];
    }

    public function ponerSoldadoEnDefensa($id)
    {

        $soldado = Soldado::find($id);

        if ($soldado->ubicacion !== 0) {
            return [
                'status' => 0,
                'message' => 'Solo se puede poner a defender un soldado desde el Inventario'
            ];
        }

        $soldadoEnDefensa = Player::with('soldados')->find($soldado->player_id)->soldados->where('ubicacion', 2)->first();

        if ($soldadoEnDefensa) {
            $soldadoDef = Soldado::find($soldadoEnDefensa->id);
            $soldadoDef->ubicacion = 0;
            $soldadoDef->save();
        }

        $soldado->ubicacion = 2;
        $soldado->save();


        return [
            'status' => 1,
            'soldado' => $soldado,
        ];
    }

    // #### HELPER FUNCTION ####
    // ##### para ordenar ######

    function pillaBotinYDescartaSoldados($atacker, $defender, $soldadoAtacante, $soldadoDefensor)
    {
        //Hay que entender que y cuántos recursos tiene el defensor
        $arrayRecursos = [];
        $helper = ['recurso1', 'recurso2', 'recurso3', 'recurso4'];
        // Preparamos un Array con todas las probabilidades de robo actuales.
        for ($i = 0; $i < 4; $i++) {
            for ($j = 0; $j < $defender[$helper[$i]]; $j++) {
                array_push($arrayRecursos, $helper[$i]);
            }
        }

        // Enviamos los 2 soldados al Cementerio
        $soldadoAtacante->ubicacion = 3;
        $soldadoAtacante->save();
        if ($soldadoDefensor) {
            $soldadoDefensor->ubicacion = 3;
            $soldadoDefensor->save();
        }

        if (!$arrayRecursos) {
            return false;
        }

        $botin = Arr::random($arrayRecursos);

        // Efectuamos el intercambio de recursos
        $atacker->$botin = $atacker->$botin + 1;
        $atacker->save();
        $defender->$botin = $defender->$botin - 1;
        $defender->save();


        $helper = ['Tierra', 'Aire', 'Agua', 'Fuego'];
        error_log($botin);
        error_log($helper[$botin[7] - 1]);
        return 'Una piedra de ' . $helper[$botin[7] - 1];
    }

    public function atacarConSoldado($id)
    {
        // Pillamos la informacion necesaria. 
        // Para saber si el defensor tiene defensas puestas

        $soldadoAtacante = Soldado::with('player')->find($id);
        $gameId = $soldadoAtacante->player->game_id;
        $atacker = Player::where('game_id', $gameId)->where('id', $soldadoAtacante->player_id)->first();
        $defender = Player::where('game_id', $gameId)->where('id', '!=', $soldadoAtacante->player_id)->first();
        $defenderId = $defender->id;
        $soldadoDefensor = Soldado::where('player_id', $defenderId)->where('ubicacion', 2)->first();

        if ($atacker->puntos_accion <= 0) {
            return [
                'status' => 0,
                'message' => 'No tienes puntos de acción suficientes para realizar esta acción'
            ];
        } else {
            $atacker->puntos_accion = $atacker->puntos_accion  - 1;
            $atacker->save();
        }

        // Si resulta que existe un soldado Defensor
        if ($soldadoDefensor) {
            // Luchamos
            $resultado = $soldadoAtacante->atack - $soldadoDefensor->defense;
            // Si el atacante gana...
            if ($resultado > 0) {
                // Calculamos el chance que tiene de Robar segun las normas en la tabla Rules
                $chance = Rule::select('soldierRoboChance', 'soldierRoboUpgrade')->find(1);
                $totalChance = $chance->soldierRoboChance + $chance->soldierRoboUpgrade * $resultado;
                // Random10 para comparar con las probabilidades del atacante
                $random10 = rand(1, 10) * 10;
                // Si finalmente el atacante ha logrado Robar un item...
                if ($totalChance > $random10) {

                    $botin = $this->pillaBotinYDescartaSoldados($atacker, $defender, $soldadoAtacante, $soldadoDefensor);

                    // Si no hay botin es por que no hay nada que robar
                    if (!$botin) {
                        return [
                            'status' => 1,
                            'message' => 'Has ganado la batalla y superado la probabilidad de Robo. Pero no había nada que robar.',
                        ];
                    }

                    // Si hay botín
                    return [
                        'status' => 1,
                        'message' => 'Has ganado la batalla y has conseguido un preciado botín! ' . $botin,
                        'botin' => $botin,
                        'playerAtakante' => $atacker,
                        'playerDefensor' => $defender
                    ];
                } else {

                    return [
                        'status' => 1,
                        'message' => 'Has ganado la batalla pero no has logrado robar nada. No sabes si hay o no hay Items en la camara enemiga.',
                    ];
                }
            } else if ($resultado === 0) {
                // hay batalla y los dos mueren EMPATE
                $soldadoAtacante->ubicacion = 3;
                $soldadoAtacante->save();
                $soldadoDefensor->ubicacion = 3;
                $soldadoDefensor->save();

                return [
                    'status' => 1,
                    'message' => 'Tu soldado a muerto en la batalla, matando al defensor enemigo!'
                ];
            } else {
                //Hay batalla y gana el defensor
                $soldadoAtacante->ubicacion = 3;
                $soldadoAtacante->save();

                return [
                    'status' => 1,
                    'message' => 'Tu soldado ha muerto en la batalla'
                ];
            }
        }

        // NO hay batalla por que no hay soldado defensor.        

        $botin = $this->pillaBotinYDescartaSoldados($atacker, $defender, $soldadoAtacante, false);

        // Si no hay botin es por que no hay nada que robar
        if (!$botin) {
            return [
                'status' => 1,
                'message' => 'No habia nadie defendiendo, lograste entrar! ...Pero no había nada que robar.',
            ];
        }

        // Si hay botín
        return [
            'status' => 1,
            'message' => 'No habia nadie defendiendo, lograste entrar! ...Y te has echo con un preciado botín! ' . $botin,
            'botin' => $botin,
            'playerAtakante' => $atacker,
            'playerDefensor' => $defender
        ];
    }
}
