<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
/*-- Import --*/
use App\Http\Controllers\Task\TaskController;



/*-- Datatable --*/
Route::get('/task/datatable',[TaskController::class ,'datatable'])->name('task-datatable');






/*-- Routes --*/
/*------------------- Crud Operation For Task -------------------*/

Route::get('/task',[TaskController::class, 'index'])->name('task-index');
Route::get('/task/create',[TaskController::class, 'create'])->name('task-create');
Route::post('/task/store',[TaskController::class, 'store'])->name('task-store');
Route::get('/task/edit/{id}',[TaskController::class, 'edit'])->name('task-edit');
Route::patch('/task/status',[TaskController::class, 'status'])->name('task-status');
Route::put('/task/update/{id}',[TaskController::class, 'update'])->name('task-update');
Route::delete('/task/delete/{id}',[TaskController::class, 'destroy'])->name('task-delete');





Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
