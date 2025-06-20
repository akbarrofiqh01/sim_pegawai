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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'pegawai_id')) {
                $table->unsignedBigInteger('pegawai_id')->nullable()->after('id');
            }

            // Tambahkan foreign key secara manual
            $table->foreign('pegawai_id')
                ->references('id') // sesuaikan jika bukan "id"
                ->on('pegawais')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['pegawai_id']);
        });
    }
};
