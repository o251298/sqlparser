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

Route::middleware(['auth'])->group(function (){
    Route::get('/', [FileController::class, 'index'])->name('files.list');
    Route::get('/files/create', [FileController::class, 'create'])->name('files.create');
    Route::post('/files/parse', [FileController::class, 'parse'])->name('files.parse');
    Route::post('/files/store', [FileController::class, 'store'])->name('files.store');
    Route::post('/sql/create', [FileController::class, 'sql'])->name('sql.create');
    Route::post('/download/', [FileController::class, 'download'])->name('download');
    Route::get('/files/destroy/{file}', [FileController::class, 'destroy'])->name('destroy');
    Route::get('/files/truncate', [FileController::class, 'truncate'])->name('truncate');
});
require __DIR__.'/auth.php';
