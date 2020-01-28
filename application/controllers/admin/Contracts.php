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

    public function contract($id = '')
    {
        if ($this->input->post()) {
            // new post
            if ($id == '') {
                if (!has_permission('contracts', '', 'create')) {
                    access_denied('contracts');
                }
                $id = $this->contracts_model->add($this->input->post());

                if ($id) {
                    set_alert('success', _l('added_successfully', _l('contract')));
                    redirect(admin_url('contracts/contract/' . $id));
                }
            } 
            // update post
            else {
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
        // new
        if ($id == '') {
            $title = _l('add_new', _l('contract_lowercase'));
        } 
        // edit
        else {
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
        $data['title']         = $title;
        $data['bodyclass']     = 'contract';
        $data['subscriptions'] = $this->subscriptions_model->get_subscriptions();
        $data['blocks'] = $this->subscriptions_model->get_set_information();
        $data['title']         = $title;
        $data['bodyclass']     = 'contract';
        $data['customer'] = $this->clients_model->get_customer_with_country();
        // $data['customer'] = $this->clients_model->get();
        $staffid = get_staff_user_id();
        $data['staff'] = $this->staff_model->get_staff_with_country($staffid);
        // print_r($data); exit();
        $this->load->view('admin/contracts/contract', $data);
    }




    /* Edit contract or add new contract */
    // public function contract($id = '')
    // {
    //     // $this->session->sess_destroy();
        
    //     if($_POST && ($_POST['btn_type'] == "save")){
    //         print_r("expression");
    //         if ($this->input->get('customer_id')) {
    //             $data['customer_id'] = $this->input->get('customer_id');
    //         }

    //         $this->load->model('currencies_model');
    //         $data['base_currency'] = $this->currencies_model->get_base_currency();
    //         $data['types']         = $this->contracts_model->get_contract_types();
    //         $data['contract_detail'] = $this->contracts_model->get_contract_types($_POST['contract_type'])->details;
    //         // print_r($data['types']); exit();
    //         $data['subscriptions'] = $this->subscriptions_model->get_subscriptions();
    //         // print_r($data['subscriptions']); exit();
    //         $data['blocks'] = $this->subscriptions_model->get_set_information();
    //         $data['bodyclass']     = 'contract';
    //         $data['customer'] = $this->clients_model->get_customer_with_country();
    //         $staffid = get_staff_user_id();
    //         $data['staff'] = $this->staff_model->get_staff_with_country($staffid);
    //         $data['btn_type'] = $_POST['btn_type'];
            

    //         $this->session->sess_destroy();
    //         $session_data = [];
    //         // print_r($_POST); exit();
    //         if (isset($_POST['trash'])) $session_data['session']['trash'] = $_POST['trash'];
    //         if (isset($_POST['not_visible_to_client'])) $session_data['session']['not_visible_to_client'] = $_POST['not_visible_to_client'];
    //         $session_data['session']['staff_name'] = $_POST['staff_name'];
    //         $session_data['session']['staff_info'] = $_POST['staff_info'];
    //         $session_data['session']['subject'] = $_POST['subject'];
    //         $session_data['session']['client'] = $_POST['client'];
    //         $session_data['session']['cus_value'] = $_POST['cus_value'];
    //         $session_data['session']['cus_addr_value'] = $_POST['cus_addr_value'];
    //         $session_data['session']['contract_type'] = $_POST['contract_type'];
    //         $session_data['session']['datestart'] = $_POST['datestart'];
    //         $session_data['session']['dateend'] = $_POST['dateend'];
    //         $session_data['session']['datestart'] = $_POST['datestart'];
    //         $session_data['session']['description'] = $_POST['description'];
    //         $session_data['session']['btn_type'] = $_POST['btn_type'];

    //         if ($_POST['contract_type'] == 2) {

    //             $session_data['session']['subscription'] = $_POST['subscription'];
    //             $session_data['session']['contract_value'] = $_POST['contract_value'];
    //             $session_data['session']['sub_arr'] = $_POST['sub_arr'];
    //             $session_data['session']['sub_tax'] = $_POST['sub_tax'];
    //             $session_data['session']['custom_fields']['contracts_ser'][12] = $_POST['custom_fields']['contracts_ser'][12];
    //             $session_data['session']['custom_fields']['contracts_ser'][13] = $_POST['custom_fields']['contracts_ser'][13];

    //         }

    //         elseif ($_POST['contract_type'] == 3) {
                
    //             $session_data['session']['consulting_client_point'] = $_POST['consulting_client_point'];
    //             $session_data['session']['hourly_rate'] = $data['staff'][0]['hourly_rate'];
    //             $session_data['session']['custom_fields']['contracts_beratung'][12] = $_POST['custom_fields']['contracts_beratung'][12];
    //             $session_data['session']['custom_fields']['contracts_beratung'][13] = $_POST['custom_fields']['contracts_beratung'][13];

    //         }

    //         elseif ($_POST['contract_type'] == 1) {
                
    //             $session_data['session']['consulting_client_point'] = $_POST['consulting_client_point'];

    //             if ($_POST['custom_fields']['contracts_produkt'][13] == 'One Time Payment'){
    //                 $session_data['session']['one_time_payment_value'] = $_POST['one_time_payment_value'];
    //                 $session_data['session']['savings_amount_per_month_value'] = null;
    //                 $session_data['session']['term_value'] = null;
    //                 $session_data['session']['amount_value'] = null;
    //                 $session_data['session']['opening_payment_value'] = null;
    //                 $session_data['session']['dynamic_percentage_per_year_value'] = null;
    //                 $session_data['session']['total_amount_value'] = null;
    //                 $session_data['session']['agent_remuneration_percent_value'] = null;
    //                 $session_data['session']['agent_remuneration_price_value'] = null;
    //             }
    //             else {
    //                 $session_data['session']['one_time_payment_value'] = null;
    //                 $session_data['session']['savings_amount_per_month_value'] = $_POST['savings_amount_per_month_value'];
    //                 $session_data['session']['term_value'] = $_POST['term_value'];
    //                 $session_data['session']['amount_value'] = $_POST['amount_value'];
    //                 $session_data['session']['opening_payment_value'] = $_POST['opening_payment_value'];
    //                 $session_data['session']['dynamic_percentage_per_year_value'] = $_POST['dynamic_percentage_per_year_value'];
    //                 $session_data['session']['total_amount_value'] = $_POST['total_amount_value'];
    //                 $session_data['session']['agent_remuneration_percent_value'] = $_POST['agent_remuneration_percent_value'];
    //                 $session_data['session']['agent_remuneration_price_value'] = $_POST['agent_remuneration_price_value'];
    //             }
                    
    //             $session_data['session']['custom_fields']['contracts_produkt'][12] = $_POST['custom_fields']['contracts_produkt'][12];
    //             $session_data['session']['custom_fields']['contracts_produkt'][13] = $_POST['custom_fields']['contracts_produkt'][13];
    //             $session_data['session']['custom_fields']['contracts_produkt'][14] = $_POST['custom_fields']['contracts_produkt'][14];

    //         }

    //         /*one time payment and open time payment*/
    //         // $last_session = $this->session->all_userdata();
    //         // if($last_session['session']['custom_fields']['contracts_produks'][13] == 'One Time Payment'){
    //         //     // echo "one time payment already exist"; exit();
    //         //     $session_data['session'] = $_POST;
    //         //     $session_data['session']['one_time_payment_value'] = null;
    //         // }
    //         // if($last_session['session']['custom_fields']['contracts_produks'][13] != 'One Time Payment'){
    //         //     // echo "opening payment already exist"; exit();
    //         //     $session_data['session'] = $_POST;
    //         //     $session_data['session']['savings_amount_per_month_value'] = null;
    //         //     // $session_data['session']['term_value'] = null;
    //         //     $session_data['session']['amount_value'] = null;
    //         //     $session_data['session']['opening_payment_value'] = null;
    //         //     $session_data['session']['dynamic_percentage_per_year_value'] = null;
    //         //     $session_data['session']['total_amount_value'] = null;
    //         //     $session_data['session']['agent_remuneration_percent_value'] = null;
    //         //     $session_data['session']['agent_remuneration_price_value'] = null;
    //         // }
    //         // if (($last_session['session']['one_time_payment_value'] == null) && ($last_session['session']['opening_payment_value'] == null))
    //         // {
    //         //     $session_data['session'] = $_POST;
    //         // }

    //         // if ($_POST['contract_type'] == 2) {

    //         //     $session_data['session']['custom_fields']['contracts_beratung'] = array_fill(12, 13, null);
    //         //     $session_data['session']['custom_fields']['contracts_produkt'] = array_fill(12, 14, null);

    //         // } else if ($_POST['contract_type'] == 3){
    //         //     $session_data['session']['custom_fields']['contracts_ser'] = array_fill(12, 13, null);
    //         //     $session_data['session']['custom_fields']['contracts_produkt'] = array_fill(12, 14, null);

    //         // } else if ($_POST['contract_type'] == 1){
    //         //     $session_data['session']['custom_fields']['contracts_ser'] = array_fill(12, 13, null);
    //         //     $session_data['session']['custom_fields']['contracts_beratung'] = array_fill(12, 13, null);
    //         // }


            
    //         // print_r($session_data);exit;
    //         $this->session->set_userdata($session_data);
            
    //     }


    //     else 
    //     {

    //         if ($this->input->post()) {
    //             // echo "initial state";
    //             // print_r($_POST); exit();
    //             if ($id == '') {
    //                 if (!has_permission('contracts', '', 'create')) {
    //                     access_denied('contracts');
    //                 }
    //                 $id = $this->contracts_model->add($this->input->post());
    //                 $this->session->sess_destroy();
    //                 $invoice_number = $this->invoices_model->get_last_invoice_num();
    //                  if ($id) {
    //                     // set_alert('success', _l('added_successfully', _l('contract')));
    //                     // redirect(admin_url('contracts/contract/' . $id));
    //                     // automatic invoice creating
    //                     $invoice_data = [];
    //                     $invoice_data['clientid'] = $_POST['client'];
    //                     $invoice_data['project_id'] = null;
    //                     $invoice_data['billing_street'] = null;
    //                     $invoice_data['billing_city'] = null;
    //                     $invoice_data['billing_state'] = null;
    //                     $invoice_data['billing_zip'] = null;
    //                     $invoice_data['show_shipping_on_invoice'] = 1;
    //                     $invoice_data['shipping_street'] = null;
    //                     $invoice_data['shipping_city'] = null;
    //                     $invoice_data['shipping_state'] = null;
    //                     $invoice_data['shipping_zip'] = null;
    //                     $invoice_data['number'] = $invoice_number->number + 1;
    //                     $invoice_data['date'] = $_POST['datestart'];
    //                     $invoice_data['duedate'] = null;
    //                     $invoice_data['allowed_payment_modes'] = array(
    //                             5, 'stripe'
    //                         );

    //                     $invoice_data['currency'] = 2;
    //                     $invoice_data['sale_agent'] = 0;

    //                     // if($_POST['custom_fields']['contracts_ser'][13] == 'Daily')
    //                     // {
    //                     //     $invoice_data['recurring'] = 'custom';
    //                     //     $invoice_data['repeat_every_custom'] = 1;
    //                     //     $invoice_data['repeat_type_custom'] = 'day';
    //                     // }
    //                     // print_r($_POST); exit();
    //                     if($_POST['custom_fields']['contracts_ser'][13] == 'Quaterly')
    //                     {
    //                         $invoice_data['recurring'] = 'custom';
    //                         $invoice_data['repeat_every_custom'] = 15;
    //                         $invoice_data['repeat_type_custom'] = 'day';
    //                     }
    //                     elseif ($_POST['custom_fields']['contracts_ser'][13] == 'Monthly') {
    //                         $invoice_data['recurring'] = 'custom';
    //                         $invoice_data['repeat_every_custom'] = 1;
    //                         $invoice_data['repeat_type_custom'] = 'month';
    //                     }
    //                     elseif ($_POST['custom_fields']['contracts_ser'][13] == 'Half-Yearly') {
    //                         $invoice_data['recurring'] = 'custom';
    //                         $invoice_data['repeat_every_custom'] = 6;
    //                         $invoice_data['repeat_type_custom'] = 'month';
    //                     }
    //                     elseif ($_POST['custom_fields']['contracts_ser'][13] == 'Annually') {
    //                         $invoice_data['recurring'] = 'custom';
    //                         $invoice_data['repeat_every_custom'] = 1;
    //                         $invoice_data['repeat_type_custom'] = 'year';
    //                     }
    //                     $invoice_data['discount_type'] = null;
                        
    //                     $invoice_data['adminnote'] = null;
    //                     $invoice_data['show_quantity_as'] = 1;

    //                     $invoice_data['quantity'] = 1;

    //                     $invoice_data['subtotal'] = $_POST['contract_value'];
    //                     $invoice_data['discount_percent'] = 0;
    //                     $invoice_data['discount_total'] = 0.00;
    //                     $invoice_data['adjustment'] = 0;

    //                     if($_POST['contract_type'] == 2)
    //                         $invoice_data['total'] = $_POST['contract_value'] * ( 1 + $_POST['sub_tax']/100);

    //                     else if($_POST['contract_type'] == 3)
    //                         $invoice_data['total'] = 0;

    //                     else if($_POST['contract_type'] == 1)
    //                         $invoice_data['total'] = $_POST['agent_remuneration_price_value'];

    //                     $invoice_data['sub_tax'] = $_POST['sub_tax'];
    //                     $invoice_data['clientnote'] = null;
    //                     $invoice_data['terms'] = null;
    //                     $invoice_data['subscription_id'] = $_POST['subscription'];
    //                     $invoice_data['accordingContract'] = $id;
    //                     // print_r($invoice_data); exit();
    //                     $invoice_id = $this->invoices_model->add($invoice_data);
    //                     if ($invoice_id) {
    //                         set_alert('success', _l('added_successfully', _l('contract_and_invoice')));
    //                         $this->session->unset_userdata('session');
    //                         if (isset($invoice_data['save_and_record_payment'])) {
    //                             $this->session->set_userdata('record_payment', true);
    //                         }
    //                         redirect(admin_url('contracts/contract/' . $id));

    //                     }
    //                 }
                    
    //             } 
    //         }
    //         if ($id == '') {
    //             $title = _l('add_new', _l('contract_lowercase'));
    //         } else {
    //             $data['contract']                 = $this->contracts_model->get($id, [], true); 
    //             $data['contract_renewal_history'] = $this->contracts_model->get_contract_renewal_history($id);
    //             $data['totalNotes']               = total_rows(db_prefix().'notes', ['rel_id' => $id, 'rel_type' => 'contract']);
    //             if (!$data['contract'] || (!has_permission('contracts', '', 'view') && $data['contract']->addedfrom != get_staff_user_id())) {
    //                 blank_page(_l('contract_not_found'));
    //             }

    //             $data['contract_merge_fields'] = $this->app_merge_fields->get_flat('contract', ['other', 'client'], '{email_signature}');

    //             $title = $data['contract']->subject;

    //             $data = array_merge($data, prepare_mail_preview_data('contract_send_to_customer', $data['contract']->client));

    //         }

    //         if ($this->input->get('customer_id')) {
    //             $data['customer_id'] = $this->input->get('customer_id');
    //         }

    //         $this->load->model('currencies_model');
    //         $data['base_currency'] = $this->currencies_model->get_base_currency();
    //         $data['types']         = $this->contracts_model->get_contract_types();
    //         $data['subscriptions'] = $this->subscriptions_model->get_subscriptions();
    //         $data['blocks'] = $this->subscriptions_model->get_set_information();
    //         $data['title']         = $title;
    //         $data['bodyclass']     = 'contract';
    //         $data['customer'] = $this->clients_model->get_customer_with_country();
    //         // $data['customer'] = $this->clients_model->get();
    //         $staffid = get_staff_user_id();
    //         $data['staff'] = $this->staff_model->get_staff_with_country($staffid);
    //         // $this->load->view('admin/contracts/contract', $data);
    //     }

    //     $data['contract0'] = $this->session->all_userdata();
    //     $this->load->view('admin/contracts/contract', $data); 
    // }

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

    /* Delete contract from database (and according invoice) */
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
            set_alert('success', _l('deleted', _l('contract and its invoice')));
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
