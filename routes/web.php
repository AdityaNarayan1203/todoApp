<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToDoController;
use App\Models\Task;

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



Route::get('/', [ToDoController::class, 'index']);

Route::post('/tasks', [ToDoController::class, 'store'])->name('tasks.store');

Route::post('/tasks/fetch', [\App\Http\Controllers\ToDoController::class, 'fetchTasks'])->name('tasks.fetch');
Route::delete('/tasks/{id}', [\App\Http\Controllers\ToDoController::class, 'destroy'])->name('tasks.destroy');
Route::patch('/tasks/{id}/toggle', [\App\Http\Controllers\ToDoController::class, 'toggleComplete'])->name('tasks.toggle');



