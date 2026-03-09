<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('maintenances', function (Blueprint $table) {
            // Tambahkan kolom user_id setelah comment_id
            $table->foreignId('user_id')->constrained('users')->after('comment_id');
        });
    }

    public function down()
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
