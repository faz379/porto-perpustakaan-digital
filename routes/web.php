<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Exports\BooksExport;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register');
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('/books', BookController::class)->except(['index']);
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::get('books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::resource('/categories', CategoryController::class);
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::get('/export', [BookController::class, 'export'])->name('books.export');
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::group(['middleware' => 'check.ownership'], function () {
    Route::resource('/books', BookController::class);
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::get('books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::resource('/categories', CategoryController::class);
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::get('/export', [BookController::class, 'export'])->name('books.export');
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
});
