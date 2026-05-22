<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Rien à ajouter — post_media.type = 'video' suffit
        // On marque les posts is_reel = true
    }
    public function down(): void {}
};
