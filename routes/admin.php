<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\TagController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/***************************Administration *************************/

Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function (){

    Route::get('dashboard', function () { // Now will be /admin/dashboard
        return Inertia::render('Dashboard');
    })->name('dashboard'); // The name will be 'admin.dashboard'

    /**
     * Routes for Categories
     */
    Route::controller(CategoryController::class)->prefix('categories')->name('categories.')->group(function () {
        Route::get('/','index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{category}/edit', 'edit')->name('edit');
        Route::put('/{category}','update')->name('update');
        Route::delete('/{category}', 'destroy')->name('destroy');
    });

    /**
     * Routes for Posts
     */
    Route::controller(PostController::class)->prefix('posts')->name('posts.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{post}/edit', 'edit')->name('edit');
        Route::put('/{post}', 'update')->name('update');
        Route::delete('/{post}', 'destroy')->name('destroy');
    });

    /**
     * Routes for Tags
     */
    Route::controller(TagController::class)->prefix('tags')->name('tags.')->group(function () {
        Route::get('/','index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{tag}/edit', 'edit')->name('edit');
        Route::put('/{tag}','update')->name('update');
        Route::delete('/{tag}', 'destroy')->name('destroy');
    });

    /**
     * Routes for Permissions
     */
    Route::controller(PermissionController::class)->prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/','index')->name('index');
        Route::get('/create','create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{permission}/edit', 'edit')->name('edit');
        Route::put('/{permission}','update')->name('update');
        Route::delete('/{permission}', 'destroy')->name('destroy');
    });
});

