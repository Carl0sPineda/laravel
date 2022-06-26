<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\TelefonosController;
use App\Http\Controllers\DireccionesController;
use App\Http\Controllers\MetodosPagoController;
use App\Http\Controllers\SeguimientoCompraController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DetalleOrdenController;


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
    Route::post('/usuario/login',[UsuarioController::class,'login']);
    Route::get('/usuario/getidentity',[UsuarioController::class,'getIdentity']);
    Route::post('/usuario/upload',[UsuarioController::class,'upload']);
    Route::get('/usuario/getimage/{filename}',[UsuarioController::class,'getImage']);
    Route::get('/productos/image/{filename}',[ProductosController::class,'getImage']);
    Route::post('/productos/upload',[ProductosController::class,'upload']);//.

    //RUTAS AUTOMATICAS
    Route::resource('/usuario', UsuarioController::class,['except'=>['create','edit']]);
    Route::resource('/telefonos', TelefonosController::class,['except'=>['create','edit']]);
    Route::resource('/direcciones', DireccionesController::class,['except'=>['create','edit']]);
    Route::resource('/metodospago', MetodosPagoController::class,['except'=>['create','edit']]);
    Route::resource('/seguimientocompra', SeguimientoCompraController::class,['except'=>['create','edit']]);
    Route::resource('/productos', ProductosController::class,['except'=>['create','edit']]);
    Route::resource('/empleado', EmpleadoController::class,['except'=>['create','edit']]);
    Route::resource('/ordencompra', OrdenCompraController::class,['except'=>['create','edit']]);
    Route::resource('/categoria', CategoriaController::class,['except'=>['create','edit']]);
    Route::resource('/detalleorden', DetalleOrdenController::class,['except'=>['create','edit']]);
 
});
