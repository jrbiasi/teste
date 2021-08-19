<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

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

Auth::routes();

Route::get('/', function () {
    return view('auth.login');
});

Route::prefix('usuarios')->middleware('auth')->group(function () {
    Route::get('novo', [UsersController::class, 'novo'])->name('novo');
    Route::post('save', [UsersController::class, 'save'])->name('save.user');
    Route::get('delete-user/{id}', [UsersController::class, 'deleteUser'])->name('delete.user');
    Route::get('listar', [UsersController::class, 'listar'])->name('listar');
    Route::get('ajax-listar', [UsersController::class, 'listarAjax'])->name('ajax.listar');
});

Route::prefix('ajax')->middleware('auth')->group(function () {
    Route::post('modal-user', [UsersController::class, 'modalUser'])->name('ajax.modal.user');
});
