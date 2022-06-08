<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/files/index', [FileController::class, 'index'])->name('files.list');
Route::get('/files/create', [FileController::class, 'create'])->name('files.create');
Route::get('/files/select', [FileController::class, 'selectTable'])->name('files.select.table');
Route::post('/files/parse', [FileController::class, 'parse'])->name('files.parse');
Route::post('/files/store', [FileController::class, 'store'])->name('files.store');
Route::post('/sql/create', [FileController::class, 'sql'])->name('sql.create');

