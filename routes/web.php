<?php

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

Route::get('/', function () {
    return view('tasks.index');
});

//Route::get('api/projects', [\App\Http\Controllers\ProjectController::class, 'index'])
//    ->name('projects');
//Route::post('api/projects', [\App\Http\Controllers\ProjectController::class, 'store'])
//    ->name('projects.store');
