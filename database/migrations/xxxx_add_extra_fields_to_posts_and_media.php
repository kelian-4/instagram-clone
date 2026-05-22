<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'comments_enabled')) {
                $table->boolean('comments_enabled')->default(true)->after('is_reel');
            }
        });

        Schema::table('post_media', function (Blueprint $table) {
            if (!Schema::hasColumn('post_media', 'alt_text')) {
                $table->string('alt_text')->nullable()->after('type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('comments_enabled');
        });
        Schema::table('post_media', function (Blueprint $table) {
            $table->dropColumn('alt_text');
        });
    }
};
