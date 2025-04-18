<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\TagController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

/***************************Administracion *************************/
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/**
 * Routes for Categories
 */
Route::middleware(['auth', 'verified'])->controller(CategoryController::class)->group(function () {
    Route::get('/categories','index')->name('admin.categories.index');
    Route::get('/categories/create', 'create')->name('admin.categories.create');
    Route::post('/categories', 'store')->name('admin.categories.store');
    Route::get('/categories/{category}/edit', 'edit')->name('admin.categories.edit');
    Route::put('/categories/{category}','update')->name('admin.categories.update');
    Route::delete('/categories/{category}', 'destroy')->name('admin.categories.destroy');
});


/**
 * Routes for Posts
 */
Route::middleware(['auth', 'verified'])->controller(PostController::class)->group(function () {
    Route::get('/posts', 'index')->name('admin.posts.index');
    Route::get('/posts/create', 'create')->name('admin.posts.create');
    Route::post('/posts', 'store')->name('admin.posts.store');
    Route::get('/posts/{post}/edit', 'edit')->name('admin.posts.edit');
    Route::put('/posts/{post}', 'update')->name('admin.posts.update');
    Route::delete('/posts/{post}', 'destroy')->name('admin.posts.destroy');
});

/**
 * Routes for Tags
 */
Route::middleware(['auth', 'verified'])->controller(TagController::class)->group(function () {
    Route::get('/tags','index')->name('admin.tags.index');
    Route::get('/tags/create', 'create')->name('admin.tags.create');
    Route::post('/tags', 'store')->name('admin.tags.store');
    Route::get('/tags/{tag}/edit', 'edit')->name('admin.tags.edit');
    Route::put('/tags/{tag}','update')->name('admin.tags.update');
    Route::delete('/tags/{tag}', 'destroy')->name('admin.tags.destroy');
});



require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
