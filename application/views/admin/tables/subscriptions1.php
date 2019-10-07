<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    db_prefix() . 'subscriptions.id as id',
    db_prefix() . 'subscriptions.name as name',
    db_prefix() . 'subscriptions.monthly_costs as monthly_costs',
    db_prefix() . 'subscriptions.status as status',
    'date_subscribed',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'subscriptions';

$filter = [];
$where  = [];


if (!has_permission('subscriptions', '', 'view')) {
    array_push($where, 'AND ' . db_prefix() . 'subscriptions.created_from=' . get_staff_user_id());
}

$statusIds = [];

foreach (get_subscriptions_statuses() as $status) {
    if ($this->ci->input->post('subscription_status_' . $status['id'])) {
        array_push($statusIds, $status['id']);
    }
}

if (count($statusIds) > 0) {
    $whereStatus = '';
    foreach ($statusIds as $key => $status) {
        $whereStatus .= db_prefix() . 'subscriptions.status="' . $status . '" OR ';
    }
    $whereStatus = rtrim($whereStatus, ' OR ');

    if ($this->ci->input->post('not_subscribed')) {
        $whereStatus .= ' OR stripe_subscription_id IS NULL OR stripe_subscription_id = ""';
    }
    array_push($where, 'AND (' . $whereStatus . ')');
} else {
    if ($this->ci->input->post('not_subscribed')) {
        array_push($where, 'AND ( stripe_subscription_id IS NULL OR stripe_subscription_id = "" )');
    }
}

$join = [
    
];

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, [
    db_prefix() . 'subscriptions.id',
    db_prefix() . 'subscriptions.monthly_costs as monthly_costs',
    'stripe_subscription_id',
    'hash',
]);

// print_r($result); exit();

$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];

    $row[] = $aRow['id'];

    $link       = admin_url('subscriptions/edit/' . $aRow['id']);
    $outputName = '<a href="' . $link . '">' . $aRow['name'] . '</a>';

    $outputName .= '<div class="row-options">';

    // $outputName .= '<a href="' . site_url('subscription/' . $aRow['hash']) . '" target="_blank">' . _l('view_subscription') . '</a>';

    if (has_permission('subscriptions', '', 'edit')) {
        $outputName .= '<a href="' . admin_url('subscriptions/edit/' . $aRow['id']) . '">' . _l('edit') . '</a>';
    }
    if (empty($aRow['stripe_subscription_id']) && has_permission('subscriptions', '', 'delete')) {
        $outputName .= ' | <a href="' . admin_url('subscriptions/delete/' . $aRow['id']) . '" class="text-danger _delete">' . _l('delete') . '</a>';
    }
    $outputName .= '</div>';
    // print_r($outputName); exit();
    $row[] = $outputName;

    $row[] = '<div>' . $aRow['monthly_costs'] . '</div>';

    if (empty($aRow['status'])) {
        $row[] = _l('subscription_not_subscribed');
    } else {
        $row[] = _l('subscription_' . $aRow['status'], '', false);
    }


    if ($aRow['date_subscribed']) {
        $row[] = _dt($aRow['date_subscribed']);
    } else {
        $row[] = '-';
    }


    $row['DT_RowClass'] = 'has-row-options';
    $output['aaData'][] = $row;
}
