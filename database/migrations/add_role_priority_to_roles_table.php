<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('roles', 'role_priority')) {

            Schema::table('roles', function (Blueprint $table) {

                Schema::table('roles', function (Blueprint $table) {
                    $table->json('role_priority')->nullable()->after('guard_name');
                });

            });

        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('role_priority');
        });
    }
};
