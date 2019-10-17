<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Contracts extends Admin_controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('contracts_model');
        $this->load->model('subscriptions_model');
        $this->load->model('clients_model');
        $this->load->model('staff_model');
        $this->load->model('invoices_model');
    }

    /* List all contracts */
    public function index()
    {
        close_setup_menu();

        if (!has_permission('contracts', '', 'view') && !has_permission('contracts', '', 'view_own')) {
            access_denied('contracts');
        }

        $data['chart_types']        = json_encode($this->contracts_model->get_contracts_types_chart_data());
        $data['chart_types_values'] = json_encode($this->contracts_model->get_contracts_types_values_chart_data());
        $data['contract_types']     = $this->contracts_model->get_contract_types();
        $data['years']              = $this->contracts_model->get_contracts_years();
        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['title']         = _l('contracts');

        $where_include = '';
        if(!is_admin())
            if (has_permission('contracts', '', 'view_own')) {
                $role = $this->staff_model->get_role(get_staff_user_id());
                $staffid_arr = $this->staff_model->get_customer_with_role($role[0]['role_type'],$role[0]['role']);
                foreach ($staffid_arr as  $value) {
                    $res[] = $value['staffid'];
                }
              $data['where_include'] = $res;  
            }
        

        $this->load->view('admin/contracts/manage', $data);
    }

    public function table($clientid = '')
    {
        if (!has_permission('contracts', '', 'view') && !has_permission('contracts', '', 'view_own')) {
            ajax_access_denied();
        }

        if (has_permission( 'contracts', '', 'view_own') and !is_admin()){
           $role = $this->staff_model->get_role(get_staff_user_id());
            $staffid_arr = $this->staff_model->get_customer_with_role($role[0]['role_type'],$role[0]['role']);

            foreach ($staffid_arr as  $value) {
                $res[] = $value['staffid'];
            }
            
            $this->app->get_table_data('contracts',[
            'clientid' => $clientid,
            'staff_arr' => $res
            ]); 
        }

        elseif (has_permission('contracts', '', 'view'))
        {
            $this->app->get_table_data('contracts', [
            'clientid' => $clientid,
            ]);
        }

        
    }

    /* Edit contract or add new contract */
    public function contract($id = '')
    {
        if ($this->input->post()) {
            if ($id == '') {
                if (!has_permission('contracts', '', 'create')) {
                    access_denied('contracts');
                }
                $id = $this->contracts_model->add($this->input->post());
                $invoice_number = $this->invoices_model->get_last_invoice_num();
                 if ($id) {
                    // set_alert('success', _l('added_successfully', _l('contract')));
                    // redirect(admin_url('contracts/contract/' . $id));
                    // automatic invoice creating
                    $invoice_data = [];
                    $invoice_data['clientid'] = $_POST['client'];
                    $invoice_data['project_id'] = null;
                    $invoice_data['billing_street'] = null;
                    $invoice_data['billing_city'] = null;
                    $invoice_data['billing_state'] = null;
                    $invoice_data['billing_zip'] = null;
                    $invoice_data['show_shipping_on_invoice'] = 1;
                    $invoice_data['shipping_street'] = null;
                    $invoice_data['shipping_city'] = null;
                    $invoice_data['shipping_state'] = null;
                    $invoice_data['shipping_zip'] = null;
                    $invoice_data['number'] = $invoice_number->number + 1;
                    $invoice_data['date'] = $_POST['datestart'];
                    $invoice_data['duedate'] = null;
                    $invoice_data['allowed_payment_modes'] = array(
                            5, 'stripe'
                        );

                    $invoice_data['currency'] = 2;
                    $invoice_data['sale_agent'] = 0;

                    if($_POST['custom_fields']['contracts_ser'][13] == 'Daily')
                    {
                        $invoice_data['recurring'] = 'custom';
                        $invoice_data['repeat_every_custom'] = 1;
                        $invoice_data['repeat_type_custom'] = 'day';
                    }

                    if($_POST['custom_fields']['contracts_ser'][13] == 'Quaterly')
                    {
                        $invoice_data['recurring'] = 'custom';
                        $invoice_data['repeat_every_custom'] = 15;
                        $invoice_data['repeat_type_custom'] = 'day';
                    }
                    elseif ($_POST['custom_fields']['contracts_ser'][13] == 'Monthly') {
                        $invoice_data['recurring'] = 'custom';
                        $invoice_data['repeat_every_custom'] = 1;
                        $invoice_data['repeat_type_custom'] = 'month';
                    }
                    elseif ($_POST['custom_fields']['contracts_ser'][13] == 'Half-Yearly') {
                        $invoice_data['recurring'] = 'custom';
                        $invoice_data['repeat_every_custom'] = 6;
                        $invoice_data['repeat_type_custom'] = 'month';
                    }
                    elseif ($_POST['custom_fields']['contracts_ser'][13] == 'Annually') {
                        $invoice_data['recurring'] = 'custom';
                        $invoice_data['repeat_every_custom'] = 1;
                        $invoice_data['repeat_type_custom'] = 'year';
                    }
                    $invoice_data['discount_type'] = null;
                    
                    $invoice_data['adminnote'] = null;
                    $invoice_data['show_quantity_as'] = 1;

                    $invoice_data['quantity'] = 1;

                    $invoice_data['subtotal'] = $_POST['contract_value'];
                    $invoice_data['discount_percent'] = 0;
                    $invoice_data['discount_total'] = 0.00;
                    $invoice_data['adjustment'] = 0;
                    $invoice_data['total'] = $_POST['contract_value'] * ( 1 + $_POST['sub_tax']/100);
                    $invoice_data['sub_tax'] = $_POST['sub_tax'];
                    $invoice_data['clientnote'] = null;
                    $invoice_data['terms'] = null;
                    $invoice_data['subscription_id'] = $_POST['subscription'];
                    $invoice_data['accordingContract'] = $id;

                    /*
                    $invoice_data = array(
                        'clientid'=> $_POST['client'],
                        'project_id' => 0,
                        'billing_street'=> null,
                        'billing_city' => null,
                        'billing_state' => null,
                        'billing_zip' => null,

                        'show_shipping_on_invoice' => 1,
                        'shipping_street' => null,
                        'shipping_city' => null,
                        'shipping_state' => null,
                        'shipping_zip' => null,

                        'number' => $id,
                        'date' => $_POST['datestart'],
                        'duedate' => $_POST['dateend'],

                        'allowed_payment_modes' => array(
                            5, 'stripe'
                        ),
                        'currency' => 2,
                        // 'tags' => null,
                        'sale_agent' => null,
                        // 'recurring' => null,
                        'recurring' => 15,
                        'discount_type' => null,
                        'repeat_every_custom' => 1,
                        'repeat_type_custom' => 'day',
                        'adminnote' => null,
                        // 'item_select' => null,
                        // 'task_select' => null,
                        'show_quantity_as' => 1,
                        // 'description' => null,
                        // 'long_description' => null,
                        // 'quantity' => 1,
                        // 'unit' => null,
                        // 'rate' => null,
                        // 'taxname' => 'Umsatzsteuer 19%|19.00',
                        
                        'subtotal' => $_POST['contract_value'],
                        'discount_percent' => 0,
                        'discount_total' => 0.00,
                        'adjustment' => 0,
                        'total' => $_POST['contract_value'] * ( 1 + $_POST['sub_tax']/100),
                        'sub_tax' => $_POST['sub_tax'],
                        // 'task_id' => null,
                        // 'expense_id' => null,
                        'clientnote' => null,
                        'terms' => null,
                        'subscription_id' => $_POST['subscription']

                    );
                    */
                    $invoice_id = $this->invoices_model->add($invoice_data);
                    if ($invoice_id) {
                        set_alert('success', _l('added_successfully', _l('contract and invoice')));
                        if (isset($invoice_data['save_and_record_payment'])) {
                            $this->session->set_userdata('record_payment', true);
                        }
                        redirect(admin_url('contracts/contract/' . $id));
                    }
                }
                

            } else {
                if (!has_permission('contracts', '', 'edit')) {
                    access_denied('contracts');
                }
                $success = $this->contracts_model->update($this->input->post(), $id);
                if ($success) {
                    set_alert('success', _l('updated_successfully', _l('contract')));
                }
                redirect(admin_url('contracts/contract/' . $id));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('contract_lowercase'));
        } else {
            $data['contract']                 = $this->contracts_model->get($id, [], true);

            $data['contract_renewal_history'] = $this->contracts_model->get_contract_renewal_history($id);
            $data['totalNotes']               = total_rows(db_prefix().'notes', ['rel_id' => $id, 'rel_type' => 'contract']);
            if (!$data['contract'] || (!has_permission('contracts', '', 'view') && $data['contract']->addedfrom != get_staff_user_id())) {
                blank_page(_l('contract_not_found'));
            }

            $data['contract_merge_fields'] = $this->app_merge_fields->get_flat('contract', ['other', 'client'], '{email_signature}');

            $title = $data['contract']->subject;

            $data = array_merge($data, prepare_mail_preview_data('contract_send_to_customer', $data['contract']->client));
        }

        if ($this->input->get('customer_id')) {
            $data['customer_id'] = $this->input->get('customer_id');
        }

        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['types']         = $this->contracts_model->get_contract_types();
        $data['subscriptions'] = $this->subscriptions_model->get_subscriptions();
        $data['blocks'] = $this->subscriptions_model->get_set_information();
        $data['title']         = $title;
        $data['bodyclass']     = 'contract';
        $data['customer'] = $this->clients_model->get_customer_with_country();
        // $data['customer'] = $this->clients_model->get();
        $staffid = get_staff_user_id();
        $data['staff'] = $this->staff_model->get_staff_with_country($staffid);
        $this->load->view('admin/contracts/contract', $data);
    }

    public function get_template()
    {
        $name = $this->input->get('name');
        echo $this->load->view('admin/contracts/templates/' . $name, [], true);
    }

    public function pdf($id)
    {
        if (!has_permission('contracts', '', 'view') && !has_permission('contracts', '', 'view_own')) {
            access_denied('contracts');
        }

        if (!$id) {
            redirect(admin_url('contracts'));
        }

        $contract = $this->contracts_model->get($id);

        try {
            $pdf = contract_pdf($contract);
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }

        $type = 'D';

        if ($this->input->get('output_type')) {
            $type = $this->input->get('output_type');
        }

        if ($this->input->get('print')) {
            $type = 'I';
        }

        $pdf->Output(slug_it($contract->subject) . '.pdf', $type);
    }

    public function send_to_email($id)
    {
        if (!has_permission('contracts', '', 'view') && !has_permission('contracts', '', 'view_own')) {
            access_denied('contracts');
        }
        $success = $this->contracts_model->send_contract_to_client($id, $this->input->post('attach_pdf'), $this->input->post('cc'));
        if ($success) {
            set_alert('success', _l('contract_sent_to_client_success'));
        } else {
            set_alert('danger', _l('contract_sent_to_client_fail'));
        }
        redirect(admin_url('contracts/contract/' . $id));
    }

    public function add_note($rel_id)
    {
        if ($this->input->post() && (has_permission('contracts', '', 'view') || has_permission('contracts', '', 'view_own'))) {
            $this->misc_model->add_note($this->input->post(), 'contract', $rel_id);
            echo $rel_id;
        }
    }

    public function get_notes($id)
    {
        if ((has_permission('contracts', '', 'view') || has_permission('contracts', '', 'view_own'))) {
            $data['notes'] = $this->misc_model->get_notes($id, 'contract');
            $this->load->view('admin/includes/sales_notes_template', $data);
        }
    }

    public function clear_signature($id)
    {
        if (has_permission('contracts', '', 'delete')) {
            $this->contracts_model->clear_signature($id);
        }

        redirect(admin_url('contracts/contract/' . $id));
    }

    public function save_contract_data()
    {
        if (!has_permission('contracts', '', 'edit') && !has_permission('contracts', '', 'create')) {
            header('HTTP/1.0 400 Bad error');
            echo json_encode([
                'success' => false,
                'message' => _l('access_denied'),
            ]);
            die;
        }

        $success = false;
        $message = '';

        $this->db->where('id', $this->input->post('contract_id'));
        $this->db->update(db_prefix().'contracts', [
                'content' => $this->input->post('content', false),
        ]);

        $success = $this->db->affected_rows() > 0;
        $message = _l('updated_successfully', _l('contract'));

        echo json_encode([
            'success' => $success,
            'message' => $message,
        ]);
    }

    public function add_comment()
    {
        if ($this->input->post()) {
            echo json_encode([
                'success' => $this->contracts_model->add_comment($this->input->post()),
            ]);
        }
    }

    public function edit_comment($id)
    {
        if ($this->input->post()) {
            echo json_encode([
                'success' => $this->contracts_model->edit_comment($this->input->post(), $id),
                'message' => _l('comment_updated_successfully'),
            ]);
        }
    }

    public function get_comments($id)
    {
        $data['comments'] = $this->contracts_model->get_comments($id);
        $this->load->view('admin/contracts/comments_template', $data);
    }

    public function remove_comment($id)
    {
        $this->db->where('id', $id);
        $comment = $this->db->get(db_prefix().'contract_comments')->row();
        if ($comment) {
            if ($comment->staffid != get_staff_user_id() && !is_admin()) {
                echo json_encode([
                    'success' => false,
                ]);
                die;
            }
            echo json_encode([
                'success' => $this->contracts_model->remove_comment($id),
            ]);
        } else {
            echo json_encode([
                'success' => false,
            ]);
        }
    }

    public function renew()
    {
        if (!has_permission('contracts', '', 'create') && !has_permission('contracts', '', 'edit')) {
            access_denied('contracts');
        }
        if ($this->input->post()) {
            $data    = $this->input->post();
            $success = $this->contracts_model->renew($data);
            if ($success) {
                set_alert('success', _l('contract_renewed_successfully'));
            } else {
                set_alert('warning', _l('contract_renewed_fail'));
            }
            redirect(admin_url('contracts/contract/' . $data['contractid'] . '?tab=renewals'));
        }
    }

    public function delete_renewal($renewal_id, $contractid)
    {
        $success = $this->contracts_model->delete_renewal($renewal_id, $contractid);
        if ($success) {
            set_alert('success', _l('contract_renewal_deleted'));
        } else {
            set_alert('warning', _l('contract_renewal_delete_fail'));
        }
        redirect(admin_url('contracts/contract/' . $contractid . '?tab=renewals'));
    }

    public function copy($id)
    {
        if (!has_permission('contracts', '', 'create')) {
            access_denied('contracts');
        }
        if (!$id) {
            redirect(admin_url('contracts'));
        }
        $newId = $this->contracts_model->copy($id);
        if ($newId) {
            set_alert('success', _l('contract_copied_successfully'));
        } else {
            set_alert('warning', _l('contract_copied_fail'));
        }
        redirect(admin_url('contracts/contract/' . $newId));
    }

    /* Delete contract from database */
    public function delete($id)
    {
        if (!has_permission('contracts', '', 'delete')) {
            access_denied('contracts');
        }
        if (!$id) {
            redirect(admin_url('contracts'));
        }
        $response = $this->contracts_model->delete($id);
        if ($response == true) {
            set_alert('success', _l('deleted', _l('contract')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('contract_lowercase')));
        }
        if (strpos($_SERVER['HTTP_REFERER'], 'clients/') !== false) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(admin_url('contracts'));
        }
    }

    /* Manage contract types Since Version 1.0.3 */
    public function type($id = '')
    {
        if (!is_admin() && get_option('staff_members_create_inline_contract_types') == '0') {
            access_denied('contracts');
        }
        if ($this->input->post()) {
            if (!$this->input->post('id')) {
                $id = $this->contracts_model->add_contract_type($this->input->post());
                if ($id) {
                    $success = true;
                    $message = _l('added_successfully', _l('contract_type'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                    'id'      => $id,
                    'name'    => $this->input->post('name'),
                ]);
            } else {
                $data = $this->input->post();
                $id   = $data['id'];
                unset($data['id']);

                $success = $this->contracts_model->update_contract_type($data, $id);
                $message = '';
                if ($success) {
                    $message = _l('updated_successfully', _l('contract_type'));
                }
                echo json_encode([
                    'success' => $success,
                    'message' => $message,
                ]);
            }
        }
    }

    public function contract_custom_type_values(){
        $id =  $this->input->get('id');
        $result = $this->db->select('*')->where('id', $id)->get('tblcontracts_types')->row_array();
        echo json_encode($result);
        exit();
    }

    public function types()
    {
        if (!is_admin()) {
            access_denied('contracts');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('contract_types');
        }
        $data['title'] = _l('contract_types');
        $this->load->view('admin/contracts/manage_types', $data);
    }

    /* Delete announcement from database */
    public function delete_contract_type($id)
    {
        if (!$id) {
            redirect(admin_url('contracts/types'));
        }
        if (!is_admin()) {
            access_denied('contracts');
        }
        $response = $this->contracts_model->delete_contract_type($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('contract_type_lowercase')));
        } elseif ($response == true) {
            set_alert('success', _l('deleted', _l('contract_type')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('contract_type_lowercase')));
        }
        redirect(admin_url('contracts/types'));
    }

    public function add_contract_attachment($id)
    {
        handle_contract_attachment($id);
    }

    public function add_external_attachment()
    {
        if ($this->input->post()) {
            $this->misc_model->add_attachment_to_database(
                $this->input->post('contract_id'),
                'contract',
                $this->input->post('files'),
                $this->input->post('external')
            );
        }
    }

    public function delete_contract_attachment($attachment_id)
    {
        $file = $this->misc_model->get_file($attachment_id);
        if ($file->staffid == get_staff_user_id() || is_admin()) {
            echo json_encode([
                'success' => $this->contracts_model->delete_contract_attachment($attachment_id),
            ]);
        }
    }
    // public function recurring_test(){
    //     $this->invoices_model->recurring_invoices();
    // }
}
