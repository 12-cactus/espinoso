<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelegramChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // https://core.telegram.org/bots/api#chat
        Schema::create('telegram_chats', function (Blueprint $table) {
            $table->integer('id');
            $table->string('type');
            $table->string('title')->nullable();
            $table->string('username')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->boolean('all_members_are_administrators')->nullable();
            $table->string('photo')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telegram_chats');
    }
}
