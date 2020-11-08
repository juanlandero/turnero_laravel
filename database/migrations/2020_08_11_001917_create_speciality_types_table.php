<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialityTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('speciality_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 20);
            $table->string('description', 100)->nullable();
            $table->string('class_icon', 40);
            $table->boolean('is_active', true);
            $table->timestamps();
        });

        DB::statement("INSERT INTO  speciality_types
            (
                id, name, description, class_icon, is_active, created_at, updated_at
            )
            VALUES
                ( 1, 'Socio Mecánico', '', 'fas fa-wrench fa-3x', 1, NOW(), NOW()),
                ( 2, 'Socio Comprador', '', 'fas fa-shopping-basket fa-3x', 1, NOW(), NOW()),
                ( 3, 'Devoluciones', '', 'fas fa-reply fa-3x', 1, NOW(), NOW()),
                ( 4, 'Damas', '', 'fas fa-female fa-3x', 1, NOW(), NOW()),
                ( 5, 'Pedido Listo', '', 'fas fa-dolly fa-3x', 1, NOW(), NOW())
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('speciality_types');
    }
}
