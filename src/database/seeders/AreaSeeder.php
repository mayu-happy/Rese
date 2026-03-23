<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Area;

class AreaSeeder extends Seeder
{
    public function run()
    {
        $areas = ['東京都', '大阪府', '福岡県']; // 指示データに合わせて増やす

        foreach ($areas as $name) {
            Area::firstOrCreate(['name' => $name]);
        }
    }
}
