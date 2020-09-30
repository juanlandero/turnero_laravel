<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersPrivileges extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_privileges', function (Blueprint $table) {
            $table->primary(['user_id', 'privilege_id']);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('privilege_id')->constrained('privileges');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_privileges');
    }
}
