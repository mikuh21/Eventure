<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table): void {
            if (Schema::hasColumn('participants', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('participants', 'approved_at')) {
                $table->dropColumn('approved_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table): void {
            if (! Schema::hasColumn('participants', 'status')) {
                $table->string('status')->default('pending')->after('digital_id_token');
            }

            if (! Schema::hasColumn('participants', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('status');
            }
        });
    }
};
