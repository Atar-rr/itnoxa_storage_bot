<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table
                ->id()
                ->comment('Идентификатор');
            $table
                ->string('guid', 100)
                ->nullable(false)
                ->unique('item_guid_index')
                ->comment('Уникальный guid товара в 1С');
            $table
                ->string('article', 50)
                ->nullable(false)
                ->index('item_article_index')
                ->comment('Артикул товара');
            $table
                ->string('name', 255)
                ->nullable(false)
                ->index('item_name_index')
                ->comment('Название товара');
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
        Schema::dropIfExists('items');
    }
}
