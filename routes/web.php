<?php

use App\Controllers\Controller;
use Lib\Route;
use App\Controllers\HomeController;
use App\Controllers\UsuarioController;
use App\Controllers\LoginController;

// Indicaremos la clase del controlador y el método a ejecutar. Solo algunas rutas están implementadas
// Tendremos rutas para get y pst, así como parámetros opcionales indicados con : que podrán
// ser recuperados por un mismo controlador. Por ejemplo, /curso/:variable y /curso/ruta1 usan el mismo controlador
// y :variable se trata como un parámetro ajeno a la ruta
Route::get('/', [HomeController::class, 'index']);
Route::get('/crear_bd/crear_bbdd',[UsuarioController::class,'crear_bd']);
//Route::get('/usuario', [UsuarioController::class, 'index']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/login', [LoginController::class, 'showLogin']);
Route::get('/registro',[HomeController::class, 'showRegistro']);
Route::post('/registro',[UsuarioController::class, 'alta']);
Route::get('/usuario/:username', [UsuarioController::class, 'show']);
Route::get('/logout',[LoginController::class,'logout']);
Route::post('/usuario/:username/actualizar', [UsuarioController::class, 'update']);
Route::post('/usuario/:username/enviar_puntos', [UsuarioController::class, 'enviarPuntos']);
Route::get('/list',[UsuarioController::class,'showlist']);
Route::post('/usuario/:id/borrar_usuario',[UsuarioController::class,'destroy']);
//Route::get('/usuario/nuevo', [UsuarioController::class, 'create']);
//Route::get('/usuario/pruebas', [UsuarioController::class, 'pruebasSQLQueryBuilder']);
//Route::post('/usuario', [UsuarioController::class, 'store']);
//Route::get('/contacto', [ContactoController::class, 'index']);
//Route::get('/formulario', [FormularioController::class, 'index']);
//Route::get('/curso', [CursoController::class, 'index']);
//Route::get('/curso/ruta1', [CursoController::class, 'index']);
//Route::get('/curso/:variable', [CursoController::class, 'index']);
//Route::get('/otro/:variable1/:variable2/:variable3', [OtroController::class, 'index']);
 
Route::dispatch();