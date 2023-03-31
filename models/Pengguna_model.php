<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pengguna_model extends App_Model
{

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Get Kelompok pegawai
     * @param  mixed $id
     * @return array
     */
    public function get_kelompok_pegawai($id = '')
    {
        if ($id) {
            $this->db->where('id', $id);
        }
        return $this->db->get(db_prefix() . 'kelompok_pegawai')->result_array();
    }

    public function get_groups()
    {
        $this->db->order_by('name', 'asc');

        return $this->db->get(db_prefix() . 'kelompok_pegawai')->result_array();
    }

    public function add_group($data)
    {
        $this->db->insert(db_prefix() . 'kelompok_pegawai', $data);
        log_activity('Items Group Created [Name: ' . $data['name'] . ']');

        return $this->db->insert_id();
    }

    public function edit_group($data, $id)
    {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'kelompok_pegawai', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Kelompok pegawai diperbaharui [Nama: ' . $data['name'] . ']');

            return true;
        }

        return false;
    }

    public function delete_group($id)
    {
        $this->db->where('id', $id);
        $group = $this->db->get(db_prefix() . 'kelompok_pegawai')->row();

        if ($group) {
            $this->db->where('group_id', $id);
            $this->db->update(db_prefix() . 'jenis_pesawat', [
                'group_id' => 0,
            ]);

            $this->db->where('id', $id);
            $this->db->delete(db_prefix() . 'kelompok_pegawai');

            log_activity('Kelompok pegawai Deleted [Name: ' . $group->name . ']');

            return true;
        }

        return false;
    }

}