<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RestructureEselonCategoriesInProfiles extends Migration
{
    public function up()
    {
        // Perluas ENUM agar tipe lama dan baru dapat berdampingan selama pemindahan data
        $this->db->query("ALTER TABLE `profiles` MODIFY COLUMN `type` ENUM('bupati', 'wakil-bupati', 'sekda', 'forkopimda', 'eselon-ii', 'eselon-iii', 'asisten-staf-ahli', 'kepala-opd', 'kabag-setda', 'camat', 'lurah', 'kepala-desa') NOT NULL");

        // Pindahkan data eselon-ii: Asisten dan Staf Ahli -> asisten-staf-ahli, sisanya -> kepala-opd
        $this->db->query("UPDATE `profiles` SET `type` = 'asisten-staf-ahli' WHERE `type` = 'eselon-ii' AND (TRIM(`position`) LIKE 'Asisten%' OR TRIM(`position`) LIKE 'Staf Ahli%')");
        $this->db->query("UPDATE `profiles` SET `type` = 'kepala-opd' WHERE `type` = 'eselon-ii'");

        // Pindahkan data eselon-iii: Camat -> camat, Kepala Bagian -> kabag-setda, sisanya (misal Direktur RSUD) -> kepala-opd
        $this->db->query("UPDATE `profiles` SET `type` = 'camat' WHERE `type` = 'eselon-iii' AND TRIM(`position`) LIKE 'Camat%'");
        $this->db->query("UPDATE `profiles` SET `type` = 'kabag-setda' WHERE `type` = 'eselon-iii' AND TRIM(`position`) LIKE 'Kepala%Bagian%'");
        $this->db->query("UPDATE `profiles` SET `type` = 'kepala-opd' WHERE `type` = 'eselon-iii'");

        // Hapus tipe lama dari ENUM setelah seluruh data berpindah
        $this->db->query("ALTER TABLE `profiles` MODIFY COLUMN `type` ENUM('bupati', 'wakil-bupati', 'sekda', 'forkopimda', 'asisten-staf-ahli', 'kepala-opd', 'kabag-setda', 'camat', 'lurah', 'kepala-desa') NOT NULL");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE `profiles` MODIFY COLUMN `type` ENUM('bupati', 'wakil-bupati', 'sekda', 'forkopimda', 'eselon-ii', 'eselon-iii', 'asisten-staf-ahli', 'kepala-opd', 'kabag-setda', 'camat', 'lurah', 'kepala-desa') NOT NULL");

        $this->db->query("UPDATE `profiles` SET `type` = 'eselon-iii' WHERE `type` IN ('kabag-setda', 'camat')");
        $this->db->query("UPDATE `profiles` SET `type` = 'eselon-iii' WHERE `type` = 'kepala-opd' AND TRIM(`position`) LIKE 'Direktur%'");
        $this->db->query("UPDATE `profiles` SET `type` = 'eselon-ii' WHERE `type` IN ('asisten-staf-ahli', 'kepala-opd')");

        $this->db->query("ALTER TABLE `profiles` MODIFY COLUMN `type` ENUM('bupati', 'wakil-bupati', 'sekda', 'forkopimda', 'eselon-ii', 'eselon-iii', 'lurah', 'kepala-desa') NOT NULL");
    }
}
