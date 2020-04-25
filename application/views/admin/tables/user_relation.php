<?php
defined('BASEPATH') or exit('No direct script access allowed');

$has_permission_delete = has_permission('user_relation', '', 'delete');

$custom_fields = get_custom_fields('user_relation', [
    'show_on_table' => 1,
    ]);

$aColumns = [
    'created_by',
    'create_id',
    'percentage'
    ];
// 
$sIndexColumn = 'id';
$sTable       = db_prefix().'user_relation';
// $join         = ['LEFT JOIN '.db_prefix().'roles ON '.db_prefix().'roles.roleid = '.db_prefix().'staff.role'];
$i            = 0;
// foreach ($custom_fields as $field) {
//     $select_as = 'cvalue_' . $i;
//     if ($field['type'] == 'date_picker' || $field['type'] == 'date_picker_time') {
//         $select_as = 'date_picker_cvalue_' . $i;
//     }
//     array_push($aColumns, 'ctable_' . $i . '.value as ' . $select_as);
//     array_push($join, 'LEFT JOIN '.db_prefix().'customfieldsvalues as ctable_' . $i . ' ON '.db_prefix().'staff.staffid = ctable_' . $i . '.relid AND ctable_' . $i . '.fieldto="' . $field['fieldto'] . '" AND ctable_' . $i . '.fieldid=' . $field['id']);
//     $i++;
    
// }

            // Fix for big queries. Some hosting have max_join_limit
if (count($custom_fields) > 4) {
    @$this->ci->db->query('SET SQL_BIG_SELECTS=1');
}

$where = hooks()->apply_filters('staff_table_sql_where', []);
$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $where, [
    
    ]);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {

    // $staff_role_name = $this->ci->staff_model->get_staff_role_name($aRow['role_type']);
    // $aRow['role_type'] = $staff_role_name;

    

    if ($aRow['created_by'] == 0 || $aRow['created_by'] == null){
        $aRow['created_by'] = '';
    }
    else
    {
        //$user_group_name = $this->ci->staff_model->get_plans_name($aRow['created_by']);
        $aRow['created_by'] = get_staff_full_name($aRow['created_by']);
    }

    if ($aRow['create_id'] == 0 || $aRow['create_id'] == null){
        $aRow['create_id'] = '';
    }
    else
    {
        //$user_group_name = $this->ci->staff_model->get_plans_name($aRow['created_by']);
        $aRow['create_id'] = get_staff_full_name($aRow['create_id']);
    }


    
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        if (strpos($aColumns[$i], 'as') !== false && !isset($aRow[$aColumns[$i]])) {
            $_data = $aRow[strafter($aColumns[$i], 'as ')];
        } else {
            $_data = $aRow[$aColumns[$i]];
        }
        
        $row[] = $_data;
        // print_r($row); exit();
    }
    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
