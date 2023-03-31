<?php

defined('BASEPATH') or exit('No direct script access allowed');

function is_admin_upt(){
	$staff_id = get_staff_user_id();
	$admin_upt = has_role_permission(6,'','') ? '1' : '0';
	return $admin_upt;
}

function get_kelompok_pegawai($id=''){
	
    $CI = &get_instance();
	if ($id) {
	    $CI->db->where('id', $id);
	}
	return $CI->db->get(db_prefix() . 'kelompok_pegawai')->result_array();

}

function get_staff_nip($staff_id){
	$CI = &get_instance();
	$CI->db->select('nip');
	$CI->db->where('staffid', $staff_id);
	return $CI->db->get(db_prefix() . 'staff')->row('nip');

}

function get_role_permissions($roleid){
	$CI = &get_instance();
	$CI->db->select('permissions');
	$CI->db->where('roleid', $roleid);
	return $CI->db->get(db_prefix() . 'roles')->row('permissions');

}


 function pengguna_staff_member_created($staffid){

    $CI      = & get_instance();
    /*
    if(is_admin()){
        return;
    }
    */

    $inspectors_staff = $CI->staff_model->get($staffid);
    $features = unserialize(get_role_permissions($inspectors_staff->role));
    $permissions = [];
    foreach ($features as $feature => $capabilities) {
        foreach ($capabilities as $capability) {
        $permissions['staff_id'] = $staffid; 
        $permissions['feature'] = $feature; 
        $permissions['capability'] = $capability; 
        $CI->db->insert(db_prefix().'staff_permissions', $permissions);
        }
    }

 }