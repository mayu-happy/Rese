<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ShopController extends Controller
{
    public function index(Request $request)
    {
        $areas  = Area::orderBy('id')->get();
        $genres = Genre::orderBy('id')->get();

        $query = Shop::query()->with(['area', 'genre']);

        if ($request->filled('area_id')) {
            $query->where('area_id', $request->area_id);
        }

        if ($request->filled('genre_id')) {
            $query->where('genre_id', $request->genre_id);
        }

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        $shops = $query->orderBy('id')->paginate(12)->withQueryString();

        // ★ここを return の前に置く
        $favoriteShopIds = [];
        if (Auth::check()) {
            $favoriteShopIds = Auth::user()
                ->favorites()
                ->pluck('shop_id')
                ->toArray();
        }

        // ★returnは最後に1回だけ
        return view('shops.index', compact('shops', 'areas', 'genres', 'favoriteShopIds'));
    }

    // 次フェーズ（詳細）用：今は使わなくてもOK
    public function show($shop_id)
    {
        $shop = Shop::with(['area', 'genre', 'reviews.user'])->findOrFail($shop_id);

        $times = [
            '17:00',
            '17:30',
            '18:00',
            '18:30',
            '19:00',
            '19:30',
            '20:00'
        ];

        $userReview = null;

        if (auth()->check()) {
            $userReview = $shop->reviews->where('user_id', auth()->id())->first();
        }

        return view('shops.show', compact('shop', 'times', 'userReview'));
    }
}
