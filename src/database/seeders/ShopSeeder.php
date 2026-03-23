<?php

namespace Database\Seeders;

use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    public function run()
    {
        // 店舗オーナー（仮）
        $owner = User::firstOrCreate(
            ['email' => 'owner@example.com'],
            ['name' => 'Owner', 'password' => bcrypt('password')]
        );

        $path = database_path('seeders/data/shops.csv');

        $file = fopen($path, 'r');
        $header = fgetcsv($file); // ヘッダ行

        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($header, $row);

            $area  = Area::where('name', $data['area'])->firstOrFail();
            $genre = Genre::where('name', $data['genre'])->firstOrFail();

            Shop::updateOrCreate(
                ['name' => $data['name']],
                [
                    'area_id' => $area->id,
                    'genre_id' => $genre->id,
                    'owner_id' => $owner->id,
                    'description' => $data['description'],
                    'address' => '未設定',
                    'open_time' => '10:00:00',
                    'close_time' => '22:00:00',
                    'image_url' => $data['image_url'],
                ]
            );
        }

        fclose($file);
    }
}
