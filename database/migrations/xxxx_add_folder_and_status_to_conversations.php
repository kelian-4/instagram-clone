<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conversation_user', function (Blueprint $table) {
            // primary | general | request | blocked
            $table->string('folder')->default('primary')->after('user_id');
            // pending | accepted | declined | blocked
            $table->string('status')->default('accepted')->after('folder');
        });
    }

    public function down(): void
    {
        Schema::table('conversation_user', function (Blueprint $table) {
            $table->dropColumn(['folder', 'status']);
        });
    }
};
