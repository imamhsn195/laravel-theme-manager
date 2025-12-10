<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('theme_id')->constrained('themes');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('license_key')->unique();
            $table->string('domain');
            $table->enum('status', ['active', 'expired', 'revoked'])->default('active');
            $table->timestamp('purchased_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
