<?php

use Inertia\Inertia;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\PostingController;
use App\Http\Controllers\ProductController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('products', ProductController::class);
Route::resource('postings', PostingController::class);

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    
     Route::prefix('upload')->group(function () {
        Route::get('/', [UploadController::class, 'index'])->name('upload.index');
        Route::post('/', [UploadController::class, 'store'])->name('upload.store');
    });


    // Route::prefix('task')->group(function () {
    //     Route::get('/', [UploadController::class, 'index'])->name('task.index');
    //     // Route::get('/create', [TaskController::class, 'create'])->name('task.create');
    //     // Route::get('/{task}', [TaskController::class, 'show'])->name('task.show');
    //     // Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('task.edit');

    // });
});



require __DIR__ . '/settings.php';
