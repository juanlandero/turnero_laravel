<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('shift', 10);

            $table->foreignId('shift_type_id')->constrained();
            $table->foreignId('speciality_type_id')->constrained();
            $table->foreignId('shift_status_id')->constrained('shift_status');
            $table->foreignId('office_id')->constrained();
            $table->foreignId('user_advisor_id')->constrained('users');

            $table->string('sex_client', 5)->nullable();
            $table->string('number_client', 25)->nullable();

            $table->time('start_shift')->nullable();
            $table->time('end_shift')->nullable();
            $table->boolean('is_reassigned', false);
            $table->boolean('is_active', true);
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
        Schema::dropIfExists('shifts');
    }
}
