<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('first_name', 50);
            $table->string('second_name', 50);

            $table->foreignId('user_type_id')->constrained();

            $table->string('email', 150)->unique();
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 200);
            $table->rememberToken();

            $table->foreignId('office_id')->constrained();
            $table->foreignId('speciality_type_id')->constrained();

            $table->string('is_active', true);
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
        Schema::dropIfExists('users');
    }
}
