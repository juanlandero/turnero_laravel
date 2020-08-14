<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_types', function (Blueprint $table) {
            $table->id();
            $table->string('user_type', 30);
            $table->timestamps();
        });

        DB::statement("INSERT INTO  user_types
            (
                id, user_type, created_at, updated_at
            )
            VALUES
                ( 1, 'Administrador', NOW(), NOW()),
                ( 2, 'Supervisor', NOW(), NOW()),
                ( 3, 'Caja/Especialista', NOW(), NOW())
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_types');
    }
}
