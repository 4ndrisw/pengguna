<?php defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'kelompok_pegawai')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "kelompok_pegawai` (
      `id` int(11) NOT NULL,
      `name` varchar(50) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'kelompok_pegawai`
      ADD PRIMARY KEY (`id`)
      ;');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'kelompok_pegawai`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}



  $CI->db->query("
        INSERT INTO `tblkelompok_pegawai` VALUES (1,'Tenaga Ahli PAA'),(2,'Tenaga Ahli PUBT'),(3,'Tenaga Ahli PTP'),(4,'Tenaga Ahli IPK'),(5,'Tenaga Ahli LIE'),(6,'Tenaga Ahli IIL'),(7,'NDT PT II ');
    ");

