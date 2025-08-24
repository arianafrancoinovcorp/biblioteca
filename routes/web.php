<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RequestsController;
use App\Http\Controllers\AdminController;

// Home
Route::get('/', function () {
    return view('welcome');
})->name('home');

// User Dashboard
Route::get('/users/dashboard', function () {
    return view('users.dashboard');
})->name('users.dashboard');


// Admin Dashboard
Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])
    ->middleware('auth')
    ->name('admin.dashboard');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user && $user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('users.dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    // Settings
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    // Authors e Publishers
    Route::resource('authors', AuthorController::class);
    Route::resource('publishers', PublisherController::class);

    // Users
    Route::resource('users', UserController::class);

    // Books
    Route::resource('books', BookController::class);

    // Export
    Route::get('/books/export', [BookController::class, 'export'])->name('books.export');

    // Book Request
    Route::resource('requests', RequestsController::class);
    Route::get('requests/create/{book}', [RequestsController::class, 'create'])->name('requests.create');
    Route::post('requests', [RequestsController::class, 'store'])->name('requests.store');
    Route::get('requests', [RequestsController::class, 'index'])->name('requests.index');

    Route::post('requests/{request}/return', [RequestsController::class, 'returnBook'])->name('requests.return');
    Route::post('/requests/{request}/confirm', [RequestsController::class, 'confirm'])->name('requests.confirm');




    // Admin - Books
    Route::get('/admin/books', [BookController::class, 'adminIndex'])->name('admin.books.index');
});

require __DIR__ . '/auth.php';
