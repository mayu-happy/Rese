<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request, Shop $shop)
    {
        $userId = $request->user()->id;

        $fav = Favorite::where('user_id', $userId)
            ->where('shop_id', $shop->id)
            ->first();

        if ($fav) {
            $fav->delete();
        } else {
            Favorite::create([
                'user_id' => $userId,
                'shop_id' => $shop->id,
            ]);
        }

        return back()->withInput();
    }
}
