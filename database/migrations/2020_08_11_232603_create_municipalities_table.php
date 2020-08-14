<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMunicipalitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipalities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->timestamps();
        });

        DB::statement("INSERT INTO  municipalities
            (
                id, name, created_at, updated_at
            )
            VALUES
                ( 1, 'Mérida', NOW(), NOW()),
                ( 2, 'Umán', NOW(), NOW()),
                ( 3, 'Izamal', NOW(), NOW()),
                ( 4, 'Calotmul', NOW(), NOW())
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('municipalities');
    }
}
