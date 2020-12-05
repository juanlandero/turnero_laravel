<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boxes', function (Blueprint $table) {
            $table->id();
            $table->string('box_name', '30');
            $table->boolean('is_active', true);
            $table->timestamps();
        });

        DB::statement("INSERT INTO  boxes
            (
                id, box_name, is_active, created_at, updated_at
            )
            VALUES
                ( 1, 'No aplica', false, NOW(), NOW()),
                ( 2, 'Caja 1', true, NOW(), NOW()),
                ( 3, 'Caja 2', true, NOW(), NOW()),
                ( 4, 'Caja 3', true, NOW(), NOW())
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boxes');
    }
}
