<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Pengguna 
Description: Module for pengguna 
Version: 2.3.4
Requires at least: 2.3.*
*/

if (!defined('MODULE_PENGGUNA')) {
    define('MODULE_PENGGUNA', basename(__DIR__));
}

hooks()->add_action('admin_init', 'pengguna_module_init_menu_items');
hooks()->add_action('admin_init', 'pengguna_permissions');
hooks()->add_filter('customers_table_sql_where', 'pengguna_client_sql_where',10,1);

hooks()->add_action('staff_member_created', 'pengguna_staff_member_created');
hooks()->add_filter('get_contact_permissions', 'pengguna_contact_permission',10,1);
hooks()->add_filter('invoices_table_row_data', 'pengguna_invoices_table_row_data',10,2);

function pengguna_invoices_table_row_data( $row, $aRow){
    $client = get_client($aRow['clientid']);
   
    if(!empty($client)){
        if($client->is_staff !=0){

            $row[5] =  '<a href="'.admin_url().'pengguna/client/'.$aRow['clientid'].'">'.$aRow['company'].'</a>';
        }
    }
    return $row;
}



function pengguna_contact_permission($permissions){
        $item = array(
            'id'         => 9,
            'name'       => _l('pengguna'),
            'short_name' => 'pengguna',
        );
        $permissions[] = $item;
      return $permissions;

}
function pengguna_client_sql_where($where){
    array_push($where, 'AND '.db_prefix().'clients.is_staff =0');
    return $where;
}

function pengguna_permissions() {
    $capabilities = [];

    $capabilities['capabilities'] = [
            'view'              => _l('permission_view') . '(' . _l('permission_global') . ')',
            'view_in_inspectors' => _l('view_pengguna_in_inspectors'),
            'view_in_institutions' => _l('view_pengguna_in_inspectors'),
            'create'            => _l('permission_create'),
            'edit'              => _l('permission_edit'),
            'delete'            => _l('permission_delete'),
            'manage'            => _l('manage_user'),
    ];
    if (function_exists('register_staff_capabilities')) {
        register_staff_capabilities('pengguna', $capabilities, _l('pengguna'));
    }
}

/**
* Register activation module hook
*/
register_activation_hook(MODULE_PENGGUNA, 'pengguna_module_activation_hook');

function pengguna_module_activation_hook() {
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}
/**
* Load the module helper
*/
get_instance()->load->helper(MODULE_PENGGUNA . '/pengguna');

// print_r(MODULE_PENGGUNA );exit;
/**
* Register language files, must be registered if the module is using languages
*/

register_language_files(MODULE_PENGGUNA, [MODULE_PENGGUNA]);

/**
 * Init pengguna module menu items in setup in admin_init hook
 * @return null
 */

function pengguna_module_init_menu_items() {
    $CI = &get_instance();

    $CI->app->add_quick_actions_link([
        'name'       => _l('pengguna'),
        'url'        => 'pengguna',
        'permission' => 'pengguna',
        'icon'     => 'fa-regular fa-user menu-icon',
        'position'   => 57,
    ]);

    if (has_permission('pengguna', '', 'manage')) {
        $CI->app_menu->add_sidebar_menu_item('pengguna', [
            'slug'     => 'pengguna',
            'name'     => _l('pengguna'),
            'position' => 5,
            'icon'     => 'fa-regular fa-user menu-icon',
            'href'     => admin_url('pengguna')
        ]);
        /*
        $CI->app_tabs->add_customer_profile_tab('pengguna', [
        'name'     => _l('pengguna'),
        'icon'     => 'fa-regular fa-user menu-icon',
        'visible'  => TRUE,
        'view'     => 'admin/clients/groups/pengguna',
        'position' => 95,
        ]);
        */
    }
    
    // auto create custom js file
    if (!file_exists(APP_MODULES_PATH.MODULE_PENGGUNA.'/assets/js')) {
        mkdir(APP_MODULES_PATH.MODULE_PENGGUNA.'/assets/js',0755,true);
        file_put_contents(APP_MODULES_PATH.MODULE_PENGGUNA.'/assets/js/'.MODULE_PENGGUNA.'.js', '');
    }
    //  auto create custom css file
    if (!file_exists(APP_MODULES_PATH.MODULE_PENGGUNA.'/assets/css')) {
        mkdir(APP_MODULES_PATH.MODULE_PENGGUNA.'/assets/css',0755,true);
        file_put_contents(APP_MODULES_PATH.MODULE_PENGGUNA.'/assets/css/'.MODULE_PENGGUNA.'.css', '');
    }
    
    if(($CI->uri->segment(1)=='admin' && $CI->uri->segment(2)=='pengguna') || $CI->uri->segment(1)=='pengguna'){
        $CI->app_css->add(MODULE_PENGGUNA.'-css', base_url('modules/'.MODULE_PENGGUNA.'/assets/css/'.MODULE_PENGGUNA.'.css'));
        $CI->app_scripts->add(MODULE_PENGGUNA.'-js', base_url('modules/'.MODULE_PENGGUNA.'/assets/js/'.MODULE_PENGGUNA.'.js'));
    }
}
