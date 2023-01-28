<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function updatePlayer(Request $request, $id)
    {
        // FALTA com a minim comprobar que el token de usuari que fa la request pertanyi al joc en questió
        $player = Player::find($id);
        $player->update($request->all());

        return [
            'status' => 1,
            'player' => $player
        ];
    }

    // Mostrar player
    public function indexPlayer(Request $request, $id)
    {
        $player = Player::with('mineros', 'soldados')->find($id);

        return [
            'status' => 1,
            'player' => $player
        ];
    }
}
