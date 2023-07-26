<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string("avatar")->nullable();

                $table->unsignedBigInteger('role_id')->nullable();
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            });
        } else {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->integer('role_id');
                $table->string('avatar');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        //command for migrate
        //php artisan migrate --path=database/migrations/create_or_supplement_users_table.php
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('users')) {

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn("avatar");

                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            });

        }

    }
};
