<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TelefonosController;
use App\Http\Controllers\DireccionesController;
use App\Http\Controllers\MetodosPagoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\OrdenCompraController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('api')->group(function () {
    
    //RUTAS ESPECIFICAS
    

    //RUTAS AUTOMATICAS
    Route::resource('/ordencompra', OrdenCompraController::class,['except'=>['create','edit']]);
    Route::resource('/usuario', UsuarioController::class,['except'=>['create','edit']]);
    Route::resource('/empleado', EmpleadoController::class,['except'=>['create','edit']]);
    Route::resource('/telefonos', TelefonosController::class,['except'=>['create','edit']]);
    Route::resource('/direcciones', DireccionesController::class,['except'=>['create','edit']]);
    Route::resource('/metodospago', MetodosPagoController::class,['except'=>['create','edit']]);

});
