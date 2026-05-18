<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table): void {
            $table->enum('status', ['pending', 'approved'])->default('pending')->after('digital_id_token');
            $table->timestamp('approved_at')->nullable()->after('status');
            $table->boolean('attended')->default(false)->after('approved_at');
        });
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table): void {
            $table->dropColumn(['status', 'approved_at', 'attended']);
        });
    }
};
