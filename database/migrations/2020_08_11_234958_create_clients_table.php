<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('client_number', 45);
            $table->string('name', 45);
            $table->string('first_name', 45);
            $table->string('second_name', 45);
            $table->string('sex', 5);

            $table->foreignId('office_register_id')->constrained('offices');

            $table->boolean('is_active', true);
            $table->timestamps();
        });

        DB::statement("INSERT INTO  clients
        (
            id, client_number, name, first_name, second_name, sex, office_register_id, is_active, created_at, updated_at
        )
        VALUES
            ( 1, '1234', 'José', 'Hernández', 'Diaz', 'M', 1, true, NOW(), NOW()),
            ( 2, '4321', 'Ana', 'López', 'Sanchez', 'F', 2, true, NOW(), NOW()),
            ( 3, '1243', 'Diana', 'Pérez', 'Montejo', 'F', 3, true, NOW(), NOW())
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
