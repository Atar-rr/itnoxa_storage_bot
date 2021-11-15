<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSelectStoragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_select_storages', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('bot_user_id')
                ->index()
                ->nullable(false)
                ->comment('Идентификатор пользователя бота')->constrained('bot_users');
            $table->foreignId('storage_id')
                ->index()
                ->nullable(false)
                ->comment('Идентификатор склада')->constrained('storages');
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
        Schema::dropIfExists('user_select_storages');
    }
}
