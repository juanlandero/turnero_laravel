<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shift_types', function (Blueprint $table) {
            $table->id();
            $table->string('shift_type', 45);
            $table->timestamps();
        });

        DB::statement("INSERT INTO  shift_types
            (
                id, shift_type, created_at, updated_at
            )
            VALUES
                ( 1, 'Normal', NOW(), NOW()),
                ( 2, 'Premium', NOW(), NOW())
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_types');
    }
}
