<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Genre;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    private function createShop(): Shop
    {
        $owner = User::factory()->create();

        $area = Area::create([
            'name' => '東京都',
        ]);

        $genre = Genre::create([
            'name' => '寿司',
        ]);

        return Shop::create([
            'area_id' => $area->id,
            'genre_id' => $genre->id,
            'owner_id' => $owner->id,
            'name' => 'テスト店舗',
            'description' => 'テスト用の店舗です',
            'address' => '東京都渋谷区1-1-1',
            'open_time' => '10:00:00',
            'close_time' => '22:00:00',
            'image_url' => 'shops/sushi.jpg',
        ]);
    }

    public function test_user_can_create_reservation(): void
    {
        $user = User::factory()->create();
        $shop = $this->createShop();

        $response = $this->actingAs($user)->post(route('reservations.store'), [
            'shop_id' => $shop->id,
            'date' => now()->addDay()->format('Y-m-d'),
            'time' => '17:00',
            'number' => 2,
        ]);

        $response->assertRedirect(route('reservations.done'));

        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'people' => 2,
            'status' => 'booked',
        ]);
    }

    public function test_user_can_update_own_reservation(): void
    {
        $user = User::factory()->create();
        $shop = $this->createShop();

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'reserved_at' => now()->addDay()->setTime(17, 0, 0),
            'people' => 2,
            'status' => 'booked',
        ]);

        $response = $this->actingAs($user)->patch(route('reservations.update', $reservation->id), [
            'date' => now()->addDays(2)->format('Y-m-d'),
            'time' => '18:00',
            'number' => 4,
        ]);

        $response->assertRedirect(route('mypage'));

        $reservation->refresh();

        $this->assertEquals(4, $reservation->people);
        $this->assertEquals(
            now()->addDays(2)->format('Y-m-d') . ' 18:00:00',
            Carbon::parse($reservation->reserved_at)->format('Y-m-d H:i:s')
        );
    }

    public function test_user_can_delete_own_reservation(): void
    {
        $user = User::factory()->create();
        $shop = $this->createShop();

        $reservation = Reservation::create([
            'user_id' => $user->id,
            'shop_id' => $shop->id,
            'reserved_at' => now()->addDay()->setTime(17, 0, 0),
            'people' => 2,
            'status' => 'booked',
        ]);

        $response = $this->actingAs($user)->delete(route('reservations.destroy', $reservation->id));

        $response->assertRedirect(route('mypage'));

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'canceled',
        ]);

        $reservation->refresh();
        $this->assertNotNull($reservation->canceled_at);
    }
}
