<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Copy menu Magang Siswa dari HUMAS (id_modul=3) ke Tendik (id_modul=155)
        DB::statement("
            INSERT INTO menu (id_modul, nm_menu, page, urutan, akses, link_youtube, created_at, created_by, updated_at, updated_by)
            SELECT 155, nm_menu, page, urutan, akses, link_youtube, NOW(), created_by, NOW(), updated_by
            FROM menu
            WHERE id_modul = 3
                AND nm_menu NOT IN (
                SELECT nm_menu FROM menu WHERE id_modul = 155
                )
        ");
    }

    public function down(): void
    {
        // Hapus menu Magang Siswa yang baru kita copy ke Tendik
        DB::table('menu')
            ->where('id_modul', 155)
            ->whereIn('nm_menu', function ($query) {
                $query->select('nm_menu')
                    ->from('menu')
                    ->where('id_modul', 3);
            })
            ->delete();
    }
};
