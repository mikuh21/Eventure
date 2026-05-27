<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            if (! Schema::hasColumn('participants', 'digital_id_verified_at')) {
                $table->timestamp('digital_id_verified_at')->nullable()->after('digital_id_token');
            }
        });

        Schema::table('guests', function (Blueprint $table) {
            if (! Schema::hasColumn('guests', 'digital_id_verified_at')) {
                $table->timestamp('digital_id_verified_at')->nullable()->after('digital_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            if (Schema::hasColumn('participants', 'digital_id_verified_at')) {
                $table->dropColumn('digital_id_verified_at');
            }
        });

        Schema::table('guests', function (Blueprint $table) {
            if (Schema::hasColumn('guests', 'digital_id_verified_at')) {
                $table->dropColumn('digital_id_verified_at');
            }
        });
    }
};
