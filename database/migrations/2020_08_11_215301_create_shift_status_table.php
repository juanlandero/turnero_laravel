<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_status', function (Blueprint $table) {
            $table->id();
            $table->string('shift_status', 45);
            $table->timestamps();
        });

        DB::statement("INSERT INTO  shift_status
            (
                id, shift_status, created_at, updated_at
            )
            VALUES
                ( 1, 'En espera', NOW(), NOW()),
                ( 2, 'Reasignado', NOW(), NOW()),
                ( 3, 'Atendido', NOW(), NOW()),
                ( 4, 'Abandonado', NOW(), NOW())
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_status');
    }
}
