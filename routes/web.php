<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DeudorController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropuestaController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/agenda',[AgendaController::class, 'index'])->middleware(['auth', 'verified'])->name('agenda');
Route::get('/usuario',[UsuarioController::class, 'index'])->middleware(['auth', 'verified'])->name('usuario');

//Modulo de cliente
Route::get('/clientes',[ClienteController::class, 'index'])->middleware(['auth', 'verified'])->name('clientes');
Route::get('/crear-cliente',[ClienteController::class, 'create'])->middleware(['auth', 'verified'])->name('crear.cliente');
Route::get('/actualizar-cliente/{cliente}',[ClienteController::class, 'edit'])->middleware(['auth', 'verified'])->name('actualizar.cliente');
Route::get('/perfil-cliente/{cliente}',[ClienteController::class, 'show'])->middleware(['auth', 'verified'])->name('perfil.cliente');
Route::get('/importar-cartera-cliente/{cliente}',[ClienteController::class, 'importar'])->middleware(['auth', 'verified'])->name('importar.cartera.cliente');
Route::post('/importar-cartera-cliente/{cliente}',[ClienteController::class, 'almacenar'])->middleware(['auth', 'verified'])->name('almacenar.cartera.cliente');

//Productos
Route::get('/productos',[ProductoController::class, 'index'])->middleware(['auth', 'verified'])->name('productos');
Route::get('/crear-producto',[ProductoController::class, 'create'])->middleware(['auth', 'verified'])->name('crear.producto');
Route::get('/actualizar-producto/{producto}',[ProductoController::class, 'update'])->middleware(['auth', 'verified'])->name('actualizar.producto');

//Gestion Deudores 
Route::get('/cartera',[DeudorController::class, 'index'])->middleware(['auth', 'verified'])->name('cartera');
Route::get('/deudor-perfil/{deudor}',[DeudorController::class, 'show'])->middleware(['auth', 'verified'])->name('deudor.perfil');
Route::get('/deudor-nueva-gestion/{deudor}',[DeudorController::class, 'edit'])->middleware(['auth', 'verified'])->name('deudor.nueva.gestion');
Route::get('/deudor-historial/{deudor}',[DeudorController::class, 'historial'])->middleware(['auth', 'verified'])->name('deudor.historial');
Route::get('/deudor-actualizar-gestion/{gestionesDeudores}',[DeudorController::class, 'actualizarGestion'])->middleware(['auth', 'verified'])->name('deudor.actualizar.gestion');
Route::get('/deudor-nuevo-telefono/{deudor}',[DeudorController::class, 'nuevoTelefono'])->middleware(['auth', 'verified'])->name('deudor.nuevo.telefono');
Route::get('/deudor-actualizar-telefono/{telefono}',[DeudorController::class, 'actualizarTelefono'])->middleware(['auth', 'verified'])->name('deudor.actualizar.telefono');
Route::get('/nueva-propuesta/{operacion}',[DeudorController::class, 'propuesta'])->middleware(['auth', 'verified'])->name('propuesta');
Route::get('/propuesta-incobrable/{operacion}',[DeudorController::class, 'propuestaIncobrable'])->middleware(['auth', 'verified'])->name('propuesta.incobrable');
Route::get('/historial-propuesta/{operacion}',[DeudorController::class, 'historialPropuesta'])->middleware(['auth', 'verified'])->name('historial.propuesta');

//Gestión de propuestas
Route::get('/propuestas',[PropuestaController::class, 'index'])->middleware(['auth', 'verified'])->name('propuestas');
Route::post('/propuestas',[PropuestaController::class, 'exportar'])->middleware(['auth', 'verified'])->name('exportar.propuestas');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
