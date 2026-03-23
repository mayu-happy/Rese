<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained()->cascadeOnDelete();

            $table->dateTime('reserved_at')->comment('予約日時');
            $table->unsignedTinyInteger('people')->comment('予約人数');

            $table->string('status')->default('booked')
                ->comment('予約状態(booked/canceled/visited/no_show)');

            $table->text('note')->nullable()->comment('予約時の要望');
            $table->dateTime('canceled_at')->nullable()->comment('キャンセル日時');
            $table->dateTime('checked_in_at')->nullable()->comment('来店確認日時');

            $table->timestamps();

            $table->index(['shop_id', 'reserved_at']);
            $table->index(['user_id', 'reserved_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
