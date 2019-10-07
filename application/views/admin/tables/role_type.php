<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'role_type_name',
    ];

$sIndexColumn = 'id';
$sTable       = db_prefix().'role_type';

$result  = data_tables_init($aColumns, $sIndexColumn, $sTable, [], [], ['id']);

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    for ($i = 0; $i < count($aColumns); $i++) {
        $_data = $aRow[$aColumns[$i]];
        if ($aColumns[$i] == 'role_type_name') {
            $_data            = '<a href="' . admin_url('roles/role_type/' . $aRow['id']) . '" class="mbot10 display-block">' . $_data . '</a>';
            $_data .= '<span class="mtop10 display-block">' . _l('roles_total_users') . ' ' . total_rows(db_prefix().'staff', [
                'role_type' => $aRow['id'],
                ]) . '</span>';
        }
        $row[] = $_data;
    }

    $options = icon_btn('roles/role_type/' . $aRow['id'], 'pencil-square-o');
    $row[]   = $options .= icon_btn('roles/delete_role_type/' . $aRow['id'], 'remove', 'btn-danger _delete');

    $output['aaData'][] = $row;
}
