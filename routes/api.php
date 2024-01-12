<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('projects', \App\Http\Controllers\ProjectController::class)
    ->only(['index', 'store']);
Route::get('projects/{id}/tasks', [\App\Http\Controllers\TaskController::class, 'index'])
    ->name('projects.tasks');


Route::resource('tasks', \App\Http\Controllers\TaskController::class)
    ->only(['index', 'store', 'update', 'destroy']);

Route::post('/tasks/{task}/toggle', [\App\Http\Controllers\TaskController::class, 'toggle'])
    ->name('tasks.toggle');

Route::post('/tasks/upsert', [\App\Http\Controllers\TaskController::class, 'upsert'])
    ->name('tasks.upsert');
