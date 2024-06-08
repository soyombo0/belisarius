<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth
Route::get('/register', [AuthController::class, 'index'])->name('register');
Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
Route::post('/signup', [AuthController::class, 'register'])->name('signup');
Route::post('/signin', [AuthController::class, 'login'])->name('signin');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Note
Route::middleware('auth')->group(function ()  {
    Route::get('/notes', [\App\Http\Controllers\NoteController::class, 'index'])->name('notes');
    Route::get('/note/{id}', [\App\Http\Controllers\NoteController::class, 'show'])->name('note');
    Route::post('/notes', [\App\Http\Controllers\NoteController::class, 'store'])->name('notes.store');
    Route::put('/note/{id}', [\App\Http\Controllers\NoteController::class, 'update'])->name('note.update');
    Route::delete('/note/{id}', [\App\Http\Controllers\NoteController::class, 'destroy'])->name('note.delete');
});

// Task
Route::middleware('auth')->group(function ()  {
    Route::get('/tasks', [\App\Http\Controllers\TaskController::class, 'index'])->name('tasks');
    Route::get('/task/{id}', [\App\Http\Controllers\TaskController::class, 'show'])->name('task');
    Route::post('/tasks', [\App\Http\Controllers\TaskController::class, 'store'])->name('tasks.store');
    Route::post('/tasks/search', [\App\Http\Controllers\TaskController::class, 'indexByTags'])->name('tasks.search.tags');
    Route::put('/task/{id}', [\App\Http\Controllers\TaskController::class, 'update'])->name('task.update');
    Route::post('/task/{id}/image', [\App\Http\Controllers\TaskController::class, 'uploadImage'])->name('task.image');
    Route::delete('/task/{id}/image', [\App\Http\Controllers\TaskController::class, 'deleteImage'])->name('task.image.delete');
    Route::delete('/task/{id}', [\App\Http\Controllers\TaskController::class, 'destroy'])->name('task.delete');
});

// Tag
Route::middleware('auth')->group(function ()  {
    Route::get('/tags', [\App\Http\Controllers\TagController::class, 'index'])->name('tags');
    Route::get('/tag/{id}', [\App\Http\Controllers\TagController::class, 'show'])->name('tag');
    Route::post('/tags', [\App\Http\Controllers\TagController::class, 'store'])->name('tags.store');
    Route::put('/tag/{id}', [\App\Http\Controllers\TagController::class, 'update'])->name('tag.update');
    Route::delete('/tag/{id}', [\App\Http\Controllers\TagController::class, 'destroy'])->name('tag.delete');
});
