<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'shop_id' => ['required', 'exists:shops,id'],
            'date'    => ['required', 'date'],
            'time'    => ['required', 'date_format:H:i'],
            'number'  => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $reservedAt = Carbon::parse($validated['date'] . ' ' . $validated['time']);

        if ($reservedAt < now()) {
            return redirect()->back()->withInput()->with('error', '現在より前の日時は予約できません。');
        }

        Reservation::create([
            'user_id'     => Auth::id(),
            'shop_id'     => $validated['shop_id'],
            'reserved_at' => $reservedAt,
            'people'      => $validated['number'],
            'status'      => 'booked',
        ]);

        return redirect()->route('reservations.done');
    }

    public function update(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }

        if ($reservation->reserved_at < now()) {
            return redirect()->route('mypage')->with('error', '過去の予約は変更できません。');
        }

        $validated = $request->validate([
            'date'   => ['required', 'date'],
            'time'   => ['required', 'date_format:H:i'],
            'number' => ['required', 'integer', 'min:1', 'max:20'],
        ]);

        $reservedAt = Carbon::parse($validated['date'] . ' ' . $validated['time']);

        if ($reservedAt < now()) {
            return redirect()->route('mypage')->with('error', '現在より前の日時には変更できません。');
        }

        $reservation->update([
            'reserved_at' => $reservedAt,
            'people'      => $validated['number'],
        ]);

        return redirect()->route('mypage')->with('success', '予約内容を変更しました。');
    }

    public function destroy(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }

        if ($reservation->reserved_at < now()) {
            return redirect()->route('mypage')->with('error', '過去の予約はキャンセルできません。');
        }

        $reservation->canceled_at = now();
        $reservation->status = 'canceled';
        $reservation->save();

        return redirect()->route('mypage')->with('success', '予約をキャンセルしました。');
    }
}
