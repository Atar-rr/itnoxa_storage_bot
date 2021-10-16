<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemStorage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_storage', function (Blueprint $table) {
            $table->id()
                ->comment('Идентификатор');
            $table
                ->foreignId('item_property_id')
                ->index()
                ->nullable(false)
                ->comment('Идентификатор характеристики товара')->constrained('item_property');
            $table->foreignId('storage_id')
                ->index()
                ->nullable(false)
                ->comment('Идентификатор склада')->constrained('storage');
            $table
                ->integer('quantity')
                ->nullable(false)
                ->comment('Кол-во товаров на складе');
            $table->timestamps();

            $table->unique(['item_property_id', 'storage_id'], 'storage_item_item_property_id_storage_id_uiindex');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_storage');
    }
}
