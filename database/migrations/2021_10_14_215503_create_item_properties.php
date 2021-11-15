<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemProperties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_properties', function (Blueprint $table) {
            $table->id()
                ->comment('Идентификатор');
            $table
                ->foreignId('item_id')
                ->index()
                ->nullable(false)
                ->comment('Идентификатор товара')
                ->constrained('items');
            $table
                ->string('guid', 100)
                ->nullable(false)
                ->unique('item_property_guid_index')
                ->comment('Уникальный guid характеристика товара в 1С');
            $table
                ->string('name', 255)
                ->nullable(false)
                ->index('item_property_name_index')
                ->comment('Название товара');
            $table
                ->string('size', 50)
                ->nullable(false)
                ->default('')
                ->comment('Размер товара');
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
        Schema::dropIfExists('item_properties');
    }
}
