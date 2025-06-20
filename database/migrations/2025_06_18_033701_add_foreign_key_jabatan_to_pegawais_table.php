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
        Schema::table('pegawais', function (Blueprint $table) {
            if (!Schema::hasColumn('pegawais', 'jabatan_id')) {
                $table->unsignedBigInteger('jabatan_id')->nullable()->after('id');
            }

            // Tambahkan foreign key secara manual
            $table->foreign('jabatan_id')
                ->references('id') // sesuaikan jika bukan "id"
                ->on('jabatans')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropForeign(['jabatan_id']);
        });
    }
};
