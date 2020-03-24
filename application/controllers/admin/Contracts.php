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
        $this->load->model('projects_model');
        $this->load->model('tasks_model');
        $this->load->model('taxes_model');
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
              // print_r($_POST); exit();
            // new post
            if ($id == '') {
                if (!has_permission('contracts', '', 'create')) {
                    access_denied('contracts');
                }
                if (!has_permission('projects', '', 'create')) {
                    access_denied('Projects');
                }
                if (!has_permission('tasks', '', 'create')) {
                    header('HTTP/1.0 400 Bad error');
                    echo json_encode([
                        'success' => false,
                        'message' => _l('access_denied'),
                    ]);
                    die;
                }
                
                
                $data = $this->input->post();
                $id = $this->contracts_model->add($data);
                if($id){
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
            $data['project_title'] =  _l('add_new', _l('time_tracking_lowercase'));
            $data['auto_select_billing_type'] = $this->projects_model->get_most_used_billing_type();

            $data['task_title'] =  _l('add_new', _l('task_lowercase'));
        } 
        // edit
        else {
            $data['contract']                 = $this->contracts_model->get($id, [], true);
            $data['project']                  = $this->projects_model->get($data['contract']->timetracking_id);
            $data['project']->settings->available_features = unserialize($data['project']->settings->available_features);

            $rel_type = 'project';
            $rel_id = $data['project']->id;

            $rel_data = get_relation_data($rel_type,$rel_id);
            $rel_val = get_relation_values($rel_data,$rel_type);

            $data['timetracking_rel'] = json_encode($rel_val);
            // print_r($data['timetracking_rel_name']); exit();
            if($data['contract']->timetracking_id == null)
                $data['project_title'] =  _l('add_new', _l('time_tracking_lowercase'));
            else
                $data['project_title'] =  _l('edit', _l('time_tracking_lowercase'));

            $tasks_ids_string = $data['contract']->tasks_ids;

            $tasks_ids_arr = explode(",", $tasks_ids_string);
            
            $data['tasks'] = [];
            foreach ($tasks_ids_arr as $key => $task_id) {
                $task = $this->tasks_model->get($task_id);
                array_push($data['tasks'], $task);
            }


            if($data['contract']->tasks_ids == null)
                $data['task_title'] =  _l('add_new', _l('task_lowercase'));
            else
                $data['task_title'] =  _l('edit', _l('task_lowercase'));

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
        $data['products']      = $this->contracts_model->get_contract_products();
        $data['taxes']      = $this->taxes_model->get();
        // print_r($data['products']); exit();
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

        //timetracking part
        $data['statuses'] = $this->projects_model->get_project_statuses();
        // print_r($data); exit();
        $this->load->view('admin/contracts/contract', $data);
    }

    public function timetracking_on_contract()
    {
        
        // print_r($_POST); exit();
        if($_POST['timetracking_action'] == 'add'){

            if (!has_permission('projects', '', 'create')) {
                    header('HTTP/1.0 400 Bad error');
                    echo json_encode([
                        'success' => false,
                        'message' => _l('access_denied'),
                    ]);
                    die;
                }

            $project_data = $_POST['timetracking'];
            $project_data['clientid'] = $_POST['timetracking_client'];
            $project_data['start_date'] = $_POST['timetracking_start_date'];
            // $project_data['deadline'] = $_POST['timetracking_due_date'];

            if($project_data['clientid']!=''){
                $timetracking_id = $this->projects_model->add($project_data);
                if($timetracking_id){

                    $rel_type = 'project';
                    $rel_id = $timetracking_id;

                    $rel_data = get_relation_data($rel_type,$rel_id);
                    $rel_val = get_relation_values($rel_data,$rel_type);

                    $res['id'] = $timetracking_id;
                    $res['msg'] = _l('added_successfully', _l('timetracking'));
                    $res['rel_val'] = $rel_val;
                    $res['status'] = 'add';

                    echo json_encode($res);
                }
                
            }

            else {
                $res['msg'] = "Please Confirm Customer Selection";
                echo json_encode($res);
            }
        }

        else if($_POST['timetracking_action'] == 'edit'){
            
            if (!has_permission('projects', '', 'edit')) {
                    header('HTTP/1.0 400 Bad error');
                    echo json_encode([
                        'success' => false,
                        'message' => _l('access_denied'),
                    ]);
                    die;
                }
            $project_data = $_POST['timetracking'];
            $project_data['clientid'] = $_POST['timetracking_client'];
            $project_data['start_date'] = $_POST['timetracking_start_date'];
            // $project_data['deadline'] = $_POST['timetracking_due_date'];

            $id = $_POST['time_id'];
            
            $success = $this->projects_model->update($project_data, $id);

            if($success)
            {   
                $message = _l('updated_successfully', _l('timetracking'));
                echo json_encode([
                    'success' => $success,
                    'msg' => $message,
                    'id'      => $id,
                    'status' => 'edit'
                ]);
            }

        }
        
        
    }

    // public function get_current_timetracking_rel_value()
    // {
    //     $timetracking_id = $_POST['time_id'];
    //     if($timetracking_id){

    //         $rel_type = 'project';
    //         $rel_id = $timetracking_id;

    //         $rel_data = get_relation_data($rel_type,$rel_id);
    //         $rel_val = get_relation_values($rel_data,$rel_type);

    //         $res['id'] = $timetracking_id;
    //         $res['msg'] = _l('added_successfully', _l('timetracking'));
    //         $res['rel_val'] = $rel_val;
    //         $res['status'] = 'add';

    //         echo json_encode($res);
    //     }
    // }

    public function tasks_on_contract()
    {
        
        if($_POST['tasks_action'] == 'add'){

            if (!has_permission('tasks', '', 'create')) {
                    header('HTTP/1.0 400 Bad error');
                    echo json_encode([
                        'success' => false,
                        'message' => _l('access_denied'),
                    ]);
                    die;
                }

            $task_datas = $_POST['task'];
            // print_r($task_datas);exit;
            $task_ids = [];
            foreach ($task_datas as $task_data) {
                $task_data['startdate'] = $_POST['tasks_start_date'];
                $task_data['rel_type'] = 'project';
                // $task_data['duedate'] = $_POST['tasks_due_date'];
                // print_r($task_data);exit();
                if($task_data['name']!=''){
                    $task_id = $this->tasks_model->add($task_data);
                    array_push($task_ids, $task_id);
                }

            }

            if(count($task_ids)>0){
                $res['msg'] = _l('added_successfully', _l('task'));
                $res['ids'] = $task_ids;
                $res['status'] = 'add';
                echo json_encode($res);    
            }
        }
        else if($_POST['tasks_action'] == 'edit'){

            if (!has_permission('tasks', '', 'edit')) {
                    header('HTTP/1.0 400 Bad error');
                    echo json_encode([
                        'success' => false,
                        'message' => _l('access_denied'),
                    ]);
                    die;
                }

            $last_ids = explode(",", $this->contracts_model->get($_POST['contract_id_on_task'])->tasks_ids);
            $task_datas = $_POST['task'];
            $task_ids = explode(",", $_POST['t_ids']);
            // print_r($task_datas); exit;
            if(count($last_ids) == count($task_datas)){
                $index = 0;

                foreach ($task_datas as $task_data) {
                    $task_data['startdate'] = $_POST['tasks_start_date'];
                    // $task_data['duedate'] = $_POST['tasks_due_date'];
                    $task_data['rel_type'] = 'project';

                    $id = $task_ids[$index];
                    $success = $this->tasks_model->update($task_data, $id);

                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfully', _l('task'));
                    }

                    $index = $index + 1;
                }
                
                echo json_encode([
                    'msg' => $message,
                    'flag'=> $success,
                    'ids' => $task_ids

                ]);
            }

            else if(count($last_ids)!=count($task_datas)){
                foreach ($last_ids as $key => $id) {
                    $this->tasks_model->delete_task($id);
                }
                $task_ids=[];
                // print_r($task_datas); exit();
                foreach ($task_datas as $key => $task_data) {
                    $task_data['startdate'] = $_POST['tasks_start_date'];
                    // $task_data['duedate'] = $_POST['tasks_due_date'];
                    $task_data['rel_type'] = 'project';

                    if($task_data['name']!=''){
                        $task_id = $this->tasks_model->add($task_data);
                        array_push($task_ids, $task_id);
                    }
                }
                $this->db->where('id',$_POST['contract_id_on_task']);
                $task_ids_string = implode(",", $task_ids);
                $success = $this->db->update(db_prefix().'contracts',['tasks_ids' => $task_ids_string]);
                // print_r($success);
                echo json_encode([
                    'msg' => _l('updated_successfully', _l('task')),
                    'flag' => $success,
                    'ids' => $task_ids
                ]);
            }
            

        }
        
        
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

        // $this->db->where('accordingContract', $id);
        // $this->db->delete(db_prefix() . 'invoices');

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

    public function product(){
        $data = $this->contracts_model->add_product($_POST);
        // print_r($data); exit();
        echo json_encode($data);
    }
    public function delete_product(){
        // print_r($_POST);
        foreach ($_POST['selectedProduct'] as $key => $product_del) {
            $success = $this->contracts_model->remove_products($product_del);
        }
        $result = $this->contracts_model->get_contract_products();
        echo json_encode($result);
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
