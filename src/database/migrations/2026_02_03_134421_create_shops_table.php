<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();

            $table->foreignId('area_id')
                ->constrained()
                ->cascadeOnDelete()
                ->comment('エリアID');

            $table->foreignId('genre_id')
                ->constrained()
                ->cascadeOnDelete()
                ->comment('ジャンルID');

            $table->foreignId('owner_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('店舗代表ユーザーID');

            $table->string('name')->comment('店舗名');
            $table->text('description')->comment('店舗説明');
            $table->string('address')->comment('住所');

            $table->time('open_time')->comment('開店時間');
            $table->time('close_time')->comment('閉店時間');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
