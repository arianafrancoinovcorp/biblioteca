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
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

// Home
Route::get('/', [WelcomeController::class, 'index'])->name('home');

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

// Public Routes
Route::resource('books', BookController::class)->only(['index', 'show']);
Route::resource('authors', AuthorController::class)->only(['index', 'show']);
Route::resource('publishers', PublisherController::class)->only(['index', 'show']);


// Auth Routes
Route::middleware(['auth'])->group(function () {

    // Settings
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    // Authors e Publishers - except index and show
    Route::resource('authors', AuthorController::class)->except(['index', 'show']);
    Route::resource('publishers', PublisherController::class)->except(['index', 'show']);

    // Users
    Route::resource('users', UserController::class);

    // Books - except index and show
    Route::resource('books', BookController::class)->except(['index', 'show']);

    // Export
    Route::get('/books/export', [BookController::class, 'export'])->name('books.export');

    // Book Request
    Route::resource('requests', RequestsController::class);
    Route::get('requests/create/{book}', [RequestsController::class, 'create'])->name('requests.create');
    Route::post('requests', [RequestsController::class, 'store'])->name('requests.store');
    Route::get('requests', [RequestsController::class, 'index'])->name('requests.index');
    Route::post('requests/{bookRequest}/return', [RequestsController::class, 'returnBook'])->name('requests.return');
    Route::post('/requests/{bookRequest}/confirm', [RequestsController::class, 'confirm'])->name('requests.confirm');

    // Admin - Books
    Route::get('/admin/books', [BookController::class, 'adminIndex'])->name('admin.books.index');

    Route::post('/requests/{id}/review', [App\Http\Controllers\ReviewsController::class, 'store'])
    ->name('reviews.store');

    Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
        Route::get('/reviews', [ReviewsController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/{id}/edit', [ReviewsController::class, 'edit'])->name('reviews.edit');
        Route::get('/reviews/{id}', [ReviewsController::class, 'show'])->name('reviews.show');
        Route::put('/reviews/{id}', [ReviewsController::class, 'update'])->name('reviews.update');
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    });

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{book}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{cartItem}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');

    Route::get('/checkout', [CheckoutController::class, 'showForm'])->name('checkout.form');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    
});


require __DIR__ . '/auth.php';


// --------------------
// Test
// --------------------
Route::get('/test-google-books', function () {
    $response = Http::get('https://www.googleapis.com/books/v1/volumes?q=harry+potter');
    return $response->json();
});
