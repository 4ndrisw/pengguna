<?php

defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('staff', '', 'delete');

$staff_id = get_staff_user_id();
$current_user = get_client_type($staff_id);
$company_id = $current_user->client_id;

$custom_fields = get_custom_fields('staff', [
    'show_on_table' => 1,
    ]);
$aColumns = [
    'firstname',
    'email',
    db_prefix() . 'roles.name',
    'last_login',
    'active',
    ];
$sIndexColumn = 'staffid';
$sTable       = db_prefix() . 'staff';
$join         = ['LEFT JOIN ' . db_prefix() . 'roles ON ' . db_prefix() . 'roles.roleid = ' . db_prefix() . 'staff.role'];
$i            = 0;
foreach ($custom_fields as $field) {
    $select_as = 'cvalue_' . $i;
    if ($field['type'] == 'date_picker' || $field['type'] == 'date_picker_time') {
        $select_as = 'date_picker_cvalue_' . $i;
    }
    array_push($aColumns, 'ctable_' . $i . '.value as ' . $select_as);
    array_push($join, 'LEFT JOIN ' . db_prefix() . 'customfieldsvalues as ctable_' . $i . ' ON ' . db_prefix() . 'staff.staffid = ctable_' . $i . '.relid AND ctable_' . $i . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $i . '.fieldid=' . $field['id']);
    $i++;
}


//$where = hooks()->apply_filters('pengguna_staff_table_sql_where', []);
$where = [];
if(!is_admin()){
    array_push($where, 'AND ' . db_prefix() . 'staff.client_type = "' . $this->ci->db->escape_str($current_user->client_type) .'"');
    array_push($where, 'AND ' . db_prefix() . 'staff.client_id = "' . $this->ci->db->escape_str($current_user->client_id) .'"');
}


$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    'profile_image',
    'lastname',
    'staffid',
    'client_type',
    'client_id',
    ]);


$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        if ($aColumns[$i] == 'last_login') {
            if ($_data != null) {
                $_data = '<span class="text-has-action is-date" data-toggle="tooltip" data-title="' . _dt($_data) . '">' . time_ago($_data) . '</span>';
            } else {
                $_data = 'Never';
            }
        } elseif ($aColumns[$i] == 'active') {
            $checked = '';
            if ($aRow['active'] == 1) {
                $checked = 'checked';
            }

            $_data = '<div class="onoffswitch">
                <input type="checkbox" ' . (($aRow['staffid'] == get_staff_user_id() || (is_admin($aRow['staffid']) || !has_permission('staff', '', 'edit')) && !is_admin()) ? 'disabled' : '') . ' data-switch-url="' . admin_url() . 'staff/change_staff_status" name="onoffswitch" class="onoffswitch-checkbox" id="c_' . $aRow['staffid'] . '" data-id="' . $aRow['staffid'] . '" ' . $checked . '>
                <label class="onoffswitch-label" for="c_' . $aRow['staffid'] . '"></label>
            </div>';

            // For exporting
            $_data .= '<span class="hide">' . ($checked == 'checked' ? _l('is_active_export') : _l('is_not_active_export')) . '</span>';
        } elseif ($aColumns[$i] == 'firstname') {
            /*
             * ganti link ke modules
            $_data = '<a href="' . admin_url('pengguna/staff/profile/' . $aRow['staffid']) . '">' . staff_profile_image($aRow['staffid'], [
                'staff-profile-image-small',
                ]) . '</a>';
            $_data .= ' <a href="' . admin_url('pengguna/staff/member/' . $aRow['staffid']) . '">' . $aRow['firstname'] . ' ' . $aRow['lastname'] . '</a>';

            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . admin_url('pengguna/staff/member/' . $aRow['staffid']) . '">' . _l('view') . '</a>';
            */

            $_data = '<a href="' . 'pengguna/staff/member/' . $aRow['staffid'] . '">' . staff_profile_image($aRow['staffid'], [
                'staff-profile-image-small',
                ]) . '</a>';
            $_data .= ' <a href="' . 'pengguna/staff/member/' . $aRow['staffid'] . '">' . $aRow['firstname'] . ' ' . $aRow['lastname'] . '</a>';

            $_data .= '<div class="row-options">';
            $_data .= '<a href="' . 'pengguna/staff/member/' . $aRow['staffid'] . '">' . _l('view') . '</a>';

            if (($has_permission_delete && ($has_permission_delete && !is_admin($aRow['staffid']))) || is_admin()) {
                if ($has_permission_delete && $output['iTotalRecords'] > 1 && $aRow['staffid'] != get_staff_user_id()) {
                    $_data .= ' | <a href="#" onclick="delete_staff_member(' . $aRow['staffid'] . '); return false;" class="text-danger">' . _l('delete') . '</a>';
                }
            }

            $_data .= '</div>';
        } elseif ($aColumns[$i] == 'email') {
            $_data = '<a href="mailto:' . $_data . '">' . $_data . '</a>';
        } else {
            if (strpos($aColumns[$i], 'date_picker_') !== false) {
                $_data = (strpos($_data, ' ') !== false ? _dt($_data) : _d($_data));
            }
        }
        $row[] = $_data;
    }

    $row['DT_RowClass'] = 'has-row-options';

    $row = hooks()->apply_filters('pengguna_staff_table_row', $row, $aRow);

    $output['aaData'][] = $row;
}
