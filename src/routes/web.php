<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ReviewController;

Route::get('/', [ShopController::class, 'index'])->name('shops.index');
Route::get('/detail/{shop}', [ShopController::class, 'show'])->name('shops.show');
Route::get('/menu', fn() => view('partials.menu'))->name('menu');

// 予約はログイン必須（auth ルートがBreezeで作られてる前提）
Route::middleware('auth')->group(function () {
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/done', fn() => view('reservations.done'))->name('reservations.done');
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');
    Route::post('/favorites/{shop}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])
        ->name('reservations.destroy');
    Route::patch('/reservations/{reservation}', [ReservationController::class, 'update'])->name('reservations.update');
});

require __DIR__ . '/auth.php';
Route::get('/thanks', function () {
    return view('auth.thanks');
})->name('thanks');

Route::post('/shops/{shop}/reviews', [ReviewController::class, 'store'])
    ->middleware('auth')
    ->name('reviews.store');