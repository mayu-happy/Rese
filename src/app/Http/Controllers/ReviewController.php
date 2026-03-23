<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $shop_id)
    {
        $shop = Shop::findOrFail($shop_id);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:400'],
        ]);

        $hasReservation = Auth::user()->reservations()
            ->where('shop_id', $shop->id)
            ->where('reserved_at', '<', now())
            ->exists();
            
        if (! $hasReservation) {
            return back()->with('error', '来店済みの店舗のみレビューを投稿できます。');
        }

        Review::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'shop_id' => $shop->id,
            ],
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]
        );

        return back()->with('success', 'レビューを投稿しました。');
    }
}
