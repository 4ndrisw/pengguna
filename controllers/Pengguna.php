<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pengguna extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('pengguna_model');

    }

    /* List all staff members */
    public function index()
    {
        if (!has_permission('pengguna', '', 'view')) {
            access_denied('staff');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('staff');
        }
        $data['kelompok_pegawai'] = $this->pengguna_model->get_kelompok_pegawai();
        $data['staff_members'] = $this->staff_model->get('', ['active' => 1]);
        $data['title']         = _l('staff_members');
        $this->load->view('admin/pengguna/manage', $data);
    }

    public function staff_projects()
    {
        $this->app->get_table_data(module_views_path('pengguna', 'tables/staff_projects'));
    }



    public function add_group()
    {
        if ($this->input->post() && has_permission('items', '', 'create')) {
            $this->pengguna_model->add_group($this->input->post());
            set_alert('success', _l('added_successfully', _l('kelompok_pegawai')));
        }
    }

    public function update_group($id)
    {
        if ($this->input->post() && has_permission('items', '', 'edit')) {
            $this->pengguna_model->edit_group($this->input->post(), $id);
            set_alert('success', _l('updated_successfully', _l('kelompok_pegawai')));
        }
    }

    public function delete_group($id)
    {
        if (has_permission('items', '', 'delete')) {
            if ($this->pengguna_model->delete_group($id)) {
                set_alert('success', _l('deleted', _l('kelompok_pegawai')));
            }
        }
        redirect(admin_url('pengguna?groups_modal=true'));
    }



}
