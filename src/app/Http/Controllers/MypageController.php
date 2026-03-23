<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\Favorite;
use Carbon\Carbon;

class MypageController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 予約（例：Reservationモデルがある前提）
        $reservations = Reservation::with('shop')
            ->where('user_id', $user->id)
            ->where('status', 'booked')
            ->where('reserved_at', '>=', Carbon::now())
            ->orderBy('reserved_at', 'asc')
            ->get();
            
        // お気に入り（例：favoritesテーブル or many-to-many の想定）
        // shops() のリレーション名はあなたの実装に合わせて変更してね
        $favorites = $user->favorites()
            ->whereHas('shop')                 // ← shopが存在するものだけ
            ->with('shop.area', 'shop.genre')
            ->get();

        return view('mypage', compact('user', 'reservations', 'favorites'));
    }
}
