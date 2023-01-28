<?php

use App\Http\Controllers\CodigoController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GameController;
use App\Http\Controllers\MinerosController;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\SoldadoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/indexgames', [GameController::class, 'indexGames']);
Route::post('/creategame', [GameController::class, 'createGame']);

Route::get('/indexplayer/{id}', [PlayerController::class, 'indexPlayer']);
Route::post('/updateplayer/{id}', [PlayerController::class, 'updatePlayer']);

Route::get('/indexmineros/{id}', [MinerosController::class, 'indexMineros']);
Route::get('/upgrademinero/{id}/{minero}/{x}', [MinerosController::class, 'upgradeMinero']);
Route::get('/picamina/{id}/{recurso}', [MinerosController::class, 'picaMina']);
Route::post('/updatemineros/{id}', [MinerosController::class, 'updateMineros']);

Route::get('/indexcodigo/{id}', [CodigoController::class, 'indexCodigo']);
Route::get('/probarcodigo/{playerId}/{codigo}', [CodigoController::class, 'probarCodigo']);
Route::get('/indexcodigointentos/{playerId}', [CodigoController::class, 'indexCodigoIntentos']);

Route::get('/indexsoldado/{id}', [SoldadoController::class, 'indexSoldado']);
Route::get('/robarsoldado/{playerId}', [SoldadoController::class, 'robarSoldado']);
Route::get('/forjarsoldado/{id}', [SoldadoController::class, 'forjarSoldado']);
Route::get('/ponersoldadoendefensa/{id}', [SoldadoController::class, 'ponerSoldadoEnDefensa']);
Route::get('/atacarconsoldado/{id}', [SoldadoController::class, 'atacarConSoldado']);

Route::get('gamenextturn/{id}', [Controller::class, 'gameNextTurn']);
Route::get('gamestatus/{id}', [Controller::class, 'gameStatus']);
