<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//creacion de rutas para el foro
//ruta para llamar la funcion de index y mostrar publicacciones o posteos 

Route::get('/post', [App\Http\Controllers\PostController::class,'index'])->name('posts.index');
Route::post('/post', [App\Http\Controllers\PostController::class,'store'])->name('posts.store');
Route::get('/post{post}/edit', [App\Http\Controllers\PostController::class,'edit'])->name('posts.edit');
Route::patch('/post{post}', [App\Http\Controllers\PostController::class,'update'])->name('posts.update');
Route::delete('/post{post}', [App\Http\Controllers\PostController::class,'destroy'])->name('posts.destroy');
require __DIR__.'/auth.php';
