<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Staff extends AdminController
{
    /* List all staff members */
//     function __construct() {
//         parent::__construct();
//         error_reporting(E_ALL);
// ini_set('display_errors', 1);

//     }
    public function index()
    {
        if (!has_permission('staff', '', 'view')) {
            access_denied('staff');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('staff');
        }
        $data['staff_members'] = $this->staff_model->get('', ['active' => 1]);
        $data['title']         = _l('staff_members');
        $this->load->view('admin/staff/manage', $data);
    }

    /* Add new staff member or edit existing */
    public function member($id = '')
    {
        $user_rel = array();
        $created_by = get_staff_user_id();

        if (!has_permission('staff', '', 'view')) {
            access_denied('staff');
        }
        hooks()->do_action('staff_member_edit_view_profile', $id);

        $this->load->model('departments_model');
        if ($this->input->post()) {
            $data = $this->input->post();



            // Don't do XSS clean here.
            $data['email_signature'] = $this->input->post('email_signature', false);
            $data['email_signature'] = html_entity_decode($data['email_signature']);

            $data['password'] = $this->input->post('password', false);

            
            
            if ($id == '') {
                if (!has_permission('staff', '', 'create')) {
                    access_denied('staff');
                }
                $id = $this->staff_model->add($data);
                if ($id) {
                    $created_by = get_staff_user_id();

                    /*check the sub agent have percentage of this agent? */
                    $check_agent = $this->staff_model->check_subagent_percentage($created_by);
                    
                    /*end check sub agent percentage*/

                    if(!empty($check_agent)){
                        $user_rel = array("created_by"=>$created_by,"create_id"=>$id,"date"=>date('Y-m-d'),"percentage"=>$check_agent->percentage);
                    }else{
                        $user_rel = array("created_by"=>$created_by,"create_id"=>$id,"date"=>date('Y-m-d'));
                    }
                    
                    $relation_id = $this->staff_model->add_user_relation($user_rel);
                    


                    handle_staff_profile_image_upload($id);
                    set_alert('success', _l('added_successfully', _l('staff_member')));
                    redirect(admin_url('staff/member/' . $id));
                }
            } else {
                if (!has_permission('staff', '', 'edit')) {
                    access_denied('staff');
                }
                
                handle_staff_profile_image_upload($id);

                $response = $this->staff_model->update($data, $id);
                // print_r($response);exit();
                if (is_array($response)) {
                    if (isset($response['cant_remove_main_admin'])) {
                        set_alert('warning', _l('staff_cant_remove_main_admin'));
                    } elseif (isset($response['cant_remove_yourself_from_admin'])) {
                        set_alert('warning', _l('staff_cant_remove_yourself_from_admin'));
                    }
                } elseif ($response == true) {
                    set_alert('success', _l('updated_successfully', _l('staff_member')));
                }
                redirect(admin_url('staff/member/' . $id));
            }
        }
        if ($id == '') {
            $title = _l('add_new', _l('staff_member_lowercase'));
        } else {
            $member = $this->staff_model->get($id);
            if (!$member) {
                blank_page('Staff Member Not Found', 'danger');
            }
            $data['member']            = $member;
            $title                     = $member->firstname . ' ' . $member->lastname;
            $data['staff_departments'] = $this->departments_model->get_staff_departments($member->staffid);

            $ts_filter_data = [];
            if ($this->input->get('filter')) {
                if ($this->input->get('range') != 'period') {
                    $ts_filter_data[$this->input->get('range')] = true;
                } else {
                    $ts_filter_data['period-from'] = $this->input->get('period-from');
                    $ts_filter_data['period-to']   = $this->input->get('period-to');
                }
            } else {
                $ts_filter_data['this_month'] = true;
            }

            $data['logged_time'] = $this->staff_model->get_logged_time_data($id, $ts_filter_data);
            $data['timesheets']  = $data['logged_time']['timesheets'];
        }
        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['roles']         = $this->roles_model->get();
        $data['role_type']     = $this->roles_model->get_type();
        $data['user_notes']    = $this->misc_model->get_notes($id, 'staff');
        $data['departments']   = $this->departments_model->get();
        $data['title']         = $title;
        $data['check_admin']   = $this->staff_model->get_staff_status(get_staff_user_id());

        $this->load->view('admin/staff/member', $data);
    }

    /* Get role permission for specific role id */
    public function role_changed($id)
    {
        if (!has_permission('staff', '', 'view')) {
            ajax_access_denied('staff');
        }

        echo json_encode($this->roles_model->get($id)->permissions);
    }

    public function save_dashboard_widgets_order()
    {
        hooks()->do_action('before_save_dashboard_widgets_order');

        $post_data = $this->input->post();
        foreach ($post_data as $container => $widgets) {
            if ($widgets == 'empty') {
                $post_data[$container] = [];
            }
        }
        update_staff_meta(get_staff_user_id(), 'dashboard_widgets_order', serialize($post_data));
    }

    public function save_dashboard_widgets_visibility()
    {
        hooks()->do_action('before_save_dashboard_widgets_visibility');

        $post_data = $this->input->post();
        update_staff_meta(get_staff_user_id(), 'dashboard_widgets_visibility', serialize($post_data['widgets']));
    }

    public function reset_dashboard()
    {
        update_staff_meta(get_staff_user_id(), 'dashboard_widgets_visibility', null);
        update_staff_meta(get_staff_user_id(), 'dashboard_widgets_order', null);

        redirect(admin_url());
    }

    public function save_hidden_table_columns()
    {
        hooks()->do_action('before_save_hidden_table_columns');
        $data   = $this->input->post();
        $id     = $data['id'];
        $hidden = isset($data['hidden']) ? $data['hidden'] : [];
        update_staff_meta(get_staff_user_id(), 'hidden-columns-' . $id, json_encode($hidden));
    }

    public function change_language($lang = '')
    {
        hooks()->do_action('before_staff_change_language', $lang);

        $this->db->where('staffid', get_staff_user_id());
        $this->db->update(db_prefix() . 'staff', ['default_language' => $lang]);
        if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(admin_url());
        }
    }

    public function timesheets()
    {
        $data['view_all'] = false;
        if (is_admin() && $this->input->get('view') == 'all') {
            $data['staff_members_with_timesheets'] = $this->db->query('SELECT DISTINCT staff_id FROM ' . db_prefix() . 'taskstimers WHERE staff_id !=' . get_staff_user_id())->result_array();
            $data['view_all']                      = true;
        }

        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('staff_timesheets', ['view_all' => $data['view_all']]);
        }

        if ($data['view_all'] == false) {
            unset($data['view_all']);
        }

        // $staff_id = get_staff_user_id();
        // $stripe = $this->staff_model->get_stripe($staff_id);
        // $data['stripe'] = $stripe;

        $data['logged_time'] = $this->staff_model->get_logged_time_data(get_staff_user_id());
        $data['title']       = '';
        $this->load->view('admin/staff/timesheets', $data);
    }

    public function delete()
    {
        if (!is_admin() && is_admin($this->input->post('id'))) {
            die('Busted, you can\'t delete administrators');
        }
        if (has_permission('staff', '', 'delete')) {
            $success = $this->staff_model->delete($this->input->post('id'), $this->input->post('transfer_data_to'));
            if ($success) {
                set_alert('success', _l('deleted', _l('staff_member')));
            }
        }
        redirect(admin_url('staff'));
    }

    /* When staff edit his profile */
    public function edit_profile()
    {
        if ($this->input->post()) {
            handle_staff_profile_image_upload();
            $data = $this->input->post();
            // Don't do XSS clean here.
            $data['email_signature'] = $this->input->post('email_signature', false);
            $data['email_signature'] = html_entity_decode($data['email_signature']);
            $success                 = $this->staff_model->update_profile($data, get_staff_user_id());
            if ($success) {
                set_alert('success', _l('staff_profile_updated'));
            }
            redirect(admin_url('staff/edit_profile/' . get_staff_user_id()));
        }
        $member = $this->staff_model->get(get_staff_user_id());
        $this->load->model('departments_model');
        $this->load->model('currencies_model');
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['member']            = $member;
        $data['departments']       = $this->departments_model->get();
        $data['staff_departments'] = $this->departments_model->get_staff_departments($member->staffid);

        // //get stripe
        // $staff_id = get_staff_user_id();
        // $this->load->model('staff_model');
        // $stripe = $this->staff_model->get_stripe($staff_id);
        // $data['stripe'] = $stripe;

        // get stripe
        $this->db->select();
        $this->db->where('staff_id',get_staff_user_id());
        $data['stripe_info'] = $this->db->get(db_prefix().'stripe_info')->row();
        $data['title']             = $member->firstname . ' ' . $member->lastname;

        //get stripe info
        // $stripe_info = $this->staff_model->get_stripe_info($staff_id);
        // $data['stripe_info'] = $stripe_info;


        $this->load->view('admin/staff/profile', $data);
    }


    /* Remove staff profile image / ajax */
    public function remove_staff_profile_image($id = '')
    {
        $staff_id = get_staff_user_id();
        if (is_numeric($id) && (has_permission('staff', '', 'create') || has_permission('staff', '', 'edot'))) {
            $staff_id = $id;
        }
        hooks()->do_action('before_remove_staff_profile_image');
        $member = $this->staff_model->get($staff_id);
        if (file_exists(get_upload_path_by_type('staff') . $staff_id)) {
            delete_dir(get_upload_path_by_type('staff') . $staff_id);
        }
        $this->db->where('staffid', $staff_id);
        $this->db->update(db_prefix() . 'staff', [
            'profile_image' => null,
        ]);

        if (!is_numeric($id)) {
            redirect(admin_url('staff/edit_profile/' . $staff_id));
        } else {
            redirect(admin_url('staff/member/' . $staff_id));
        }
    }

    /* When staff change his password */
    public function change_password_profile()
    {
        if ($this->input->post()) {
            $response = $this->staff_model->change_password($this->input->post(null, false), get_staff_user_id());
            if (is_array($response) && isset($response[0]['passwordnotmatch'])) {
                set_alert('danger', _l('staff_old_password_incorrect'));
            } else {
                if ($response == true) {
                    set_alert('success', _l('staff_password_changed'));
                } else {
                    set_alert('warning', _l('staff_problem_changing_password'));
                }
            }
            redirect(admin_url('staff/edit_profile'));
        }
    }

    /* View public profile. If id passed view profile by staff id else current user*/
    public function profile($id = '')
    {
        if ($id == '') {
            $id = get_staff_user_id();
        }

        hooks()->do_action('staff_profile_access', $id);

        $data['logged_time'] = $this->staff_model->get_logged_time_data($id);
        $data['staff_p']     = $this->staff_model->get($id);

        if (!$data['staff_p']) {
            blank_page('Staff Member Not Found', 'danger');
        }

        $this->load->model('departments_model');
        $data['staff_departments'] = $this->departments_model->get_staff_departments($data['staff_p']->staffid);
        $data['departments']       = $this->departments_model->get();
        $data['title']             = _l('staff_profile_string') . ' - ' . $data['staff_p']->firstname . ' ' . $data['staff_p']->lastname;
        // notifications
        $total_notifications = total_rows(db_prefix() . 'notifications', [
            'touserid' => get_staff_user_id(),
        ]);
        $data['total_pages'] = ceil($total_notifications / $this->misc_model->get_notifications_limit());
         //get stripe
        // $staff_id = get_staff_user_id();
        // $this->load->model('staff_model');
        // $stripe = $this->staff_model->get_stripe($staff_id);
        // $data['stripe'] = $stripe;
        
        $this->load->view('admin/staff/myprofile', $data);
    }

    /* Change status to staff active or inactive / ajax */
    public function change_staff_status($id, $status)
    {
        if (has_permission('staff', '', 'edit')) {
            if ($this->input->is_ajax_request()) {
                $this->staff_model->change_staff_status($id, $status);
            }
        }
    }

    /* Logged in staff notifications*/
    public function notifications()
    {
        $this->load->model('misc_model');
        if ($this->input->post()) {
            $page   = $this->input->post('page');
            $offset = ($page * $this->misc_model->get_notifications_limit());
            $this->db->limit($this->misc_model->get_notifications_limit(), $offset);
            $this->db->where('touserid', get_staff_user_id());
            $this->db->order_by('date', 'desc');
            $notifications = $this->db->get(db_prefix() . 'notifications')->result_array();
            $i             = 0;
            foreach ($notifications as $notification) {
                if (($notification['fromcompany'] == null && $notification['fromuserid'] != 0) || ($notification['fromcompany'] == null && $notification['fromclientid'] != 0)) {
                    if ($notification['fromuserid'] != 0) {
                        $notifications[$i]['profile_image'] = '<a href="' . admin_url('staff/profile/' . $notification['fromuserid']) . '">' . staff_profile_image($notification['fromuserid'], [
                        'staff-profile-image-small',
                        'img-circle',
                        'pull-left',
                    ]) . '</a>';
                    } else {
                        $notifications[$i]['profile_image'] = '<a href="' . admin_url('clients/client/' . $notification['fromclientid']) . '">
                    <img class="client-profile-image-small img-circle pull-left" src="' . contact_profile_image_url($notification['fromclientid']) . '"></a>';
                    }
                } else {
                    $notifications[$i]['profile_image'] = '';
                    $notifications[$i]['full_name']     = '';
                }
                $additional_data = '';
                if (!empty($notification['additional_data'])) {
                    $additional_data = unserialize($notification['additional_data']);
                    $x               = 0;
                    foreach ($additional_data as $data) {
                        if (strpos($data, '<lang>') !== false) {
                            $lang = get_string_between($data, '<lang>', '</lang>');
                            $temp = _l($lang);
                            if (strpos($temp, 'project_status_') !== false) {
                                $status = get_project_status_by_id(strafter($temp, 'project_status_'));
                                $temp   = $status['name'];
                            }
                            $additional_data[$x] = $temp;
                        }
                        $x++;
                    }
                }
                $notifications[$i]['description'] = _l($notification['description'], $additional_data);
                $notifications[$i]['date']        = time_ago($notification['date']);
                $notifications[$i]['full_date']   = $notification['date'];
                $i++;
            } //$notifications as $notification
            echo json_encode($notifications);
            die;
        }
    }
    
    //for stripe bank details info
    public function stripe_bank_details()
    {
        $this->load->library('stripe_core');
        if(isset($_POST)){
            
        
            try{
                $id_proof_file_front_id = $this->session->userdata('id_proof_file_front_id');
            }catch (Exception $e) {
                set_alert('danger', 'Id Proof Front Not Exist ');
                redirect(admin_url('staff/edit_profile'));
            }

            try{
                $id_proof_file_back_id = $back = $this->session->userdata('id_proof_file_back_id');
            }catch (Exception $e) {
                set_alert('danger', 'Id Proof Back Not Exist ');
                redirect(admin_url('staff/edit_profile'));
            }

            try{
                $addtional_id_proof_id = $this->session->userdata('addtional_id_proof_id');
            }catch (Exception $e) {
                set_alert('danger', 'Addtional id proof Not Exist ');
                redirect(admin_url('staff/edit_profile'));
            }

            



            $data['stripe_email'] = $_POST['stripe_bank_email'];
            $data['currency'] = $_POST['bank_account_currency'];
            $data['country'] = $_POST['bank_account_country'];
            $data['IBAN'] = $_POST['account_numbers'];
            $data['staff_id'] = get_staff_user_id();
            $datas['staff_p']     = $this->staff_model->get($data['staff_id']);


            /*code for get account_id*/
            $stripe_data = array('type' => 'custom',
                              'country' => $data['country'],
                              'email' => $data['stripe_email'],
                              'requested_capabilities' => [
                                'card_payments',
                                'transfers',
                              ],
                            );
            try{
                $response_account = $this->stripe_core->get_account_id($stripe_data);
                $account_id = $response_account['id'];
            }catch (Exception $e) {
                set_alert('danger', $e->getMessage());
                redirect(admin_url('staff/edit_profile'));
            }

            /*End get account id*/
            /*star for get token id*/
            $stripe_token_data = array(
                                    'bank_account' => [
                                   'country' => $data['country'],
                                    'currency' => $data['currency'],
                                    'account_holder_name' => $datas['staff_p']->firstname.' '.$datas['staff_p']->lastname,
                                    'account_holder_type' => 'individual',
                                    #'routing_number' => '110000000',
                                    'account_number' => $data['IBAN'],
                                  ],
                                );
            try{
                $response_token = $this->stripe_core->create_token($stripe_token_data);
                $token_id = $response_token->id;
            }catch (Exception $e) {
                set_alert('danger', $e->getMessage());
                redirect(admin_url('staff/edit_profile'));
            }
            
            /*end token**/
            /*start create account*/
           
            $external_account = ['external_account' => $token_id];
            try{
                $response_create_account = $this->stripe_core->create_external_account($account_id,$external_account);            
                $data['bank_account_id'] = $response_create_account->id;
            }catch (Exception $e) {
                set_alert('danger', $e->getMessage());
                redirect(admin_url('staff/edit_profile'));
            }
            

            /*end create account*/  
            try{
                if(!empty($response_create_account))
                {
                    $data['bank_account_id'] = $response_create_account->id;
                    $data['stripe_account_id'] = $response_create_account->account;
                    $data['fingerprint'] = $response_create_account->fingerprint;
                    $this->staff_model->add_stripe_bank_details($data);
                    try{

                        $connected_stripe_account_id = $account_id;

        $account_update_arr = [
                                'requested_capabilities' => [
                                'card_payments',
                                'transfers',
                                ],
                                'business_type'=>'individual',
                                'business_profile'=>[
                                  'mcc'=>7011,
                                  'url'=>'http://dipay.de',
                                  'name'=>$datas['staff_p']->firstname.' '.$datas['staff_p']->lastname
                                ],
                                'individual'=> [
                                      'address'=>[
                                        'city'=>'Berlin',
                                        'line1'=>'123 Smith Street Apartment 4B',
                                        'postal_code'=>'20095',
                                      ],
                                      'dob'=>[
                                        'day'=>'09',
                                        'month'=>'08',
                                        'year'=>'1992'
                                      ],
                                      'email'=>$data['stripe_email'],
                                      'first_name'=>$datas['staff_p']->firstname,
                                      'last_name'=>$datas['staff_p']->lastname,
                                      'phone'=>7985689562,
                                      'verification'=>[
                                      'document'=>[
                                        'front'=>$_SESSION["id_proof_file_front_id"],
                                        'back'=>$_SESSION["id_proof_file_back_id"],
                                      ],
                                      'additional_document'=>[
                                        'front'=>$_SESSION["addtional_id_proof_id"],
                                      ],
                                    ],
                                ],
                                
                                'tos_acceptance' => [
                                  'date' => time(),
                                  'ip' => $_SERVER['REMOTE_ADDR'],
                                ],
                                ['metadata' => ['order_id' => '5523']]
                            ];


            //account update start

                        $update = $this->stripe_core->account_update($connected_stripe_account_id,$account_update_arr);
            //end account update           

                    }catch (Exception $e) {
                        set_alert('danger', $e->getMessage());
                        redirect(admin_url('staff/edit_profile'));
                    }

                    set_alert('success', _l('Bank Account Create Successfully', _l('stripe')));
                    redirect(admin_url('staff/edit_profile'));
                }  
            }
            catch (Exception $e) {
                set_alert('danger', $e->getMessage());
                redirect(admin_url('staff/edit_profile'));
            }
            
        }
    }
   

    public function id_proff_front(){
        $data = [];

            $this->load->library('stripe_core');
            $image = $_FILES['identity_proof_front']['name'];

            $size = $_FILES['identity_proof_front']['size'];

            if(!empty($image)){
                //if($size > 50000000){

                     $imageArr=explode('.',$image); 
                     $rand=rand(10000,99999);
                     $newImageName=$imageArr[0].$rand.'.'.$imageArr[1];
                     $uploadPath="uploads/document_details/".$newImageName;
                     $isUploaded=move_uploaded_file($_FILES["identity_proof_front"]["tmp_name"],$uploadPath);
                     if($isUploaded){

                          $id_proof_file_front_id = $this->stripe_core->create_file_id(fopen('/var/www/vhosts/dipay.de/my.dipay.de/uploads/document_details/'.$newImageName, 'r'));
                         if(!empty($id_proof_file_front_id)){
                            $this->session->set_userdata('id_proof_file_front_id', $id_proof_file_front_id);
                            $data['success'] = 1;
                            $data['message'] = "image upload done";
                            $data['token'] = $this->security->get_csrf_hash();
                            $data['imageId'] = $id_proof_file_front_id;
                         }else{
                            $data['success'] = 0;
                            $data['message'] = "Something went wrong";
                            $data['token'] = $this->security->get_csrf_hash();

                         }
                       
                     }else{
                        $data['success'] = 0;
                        $data['message'] = "image not upload";
                        $data['token'] = $this->security->get_csrf_hash();
                     }
                /*}
                else
                {
                        $data['success'] = 0;
                        $data['message'] = "File size must be less than 5 mb";
                        $data['token'] = $this->security->get_csrf_hash();
                }*/
            }
            else
            {
                $data['success'] = 0;
                $data['message'] = "Please select file";
                $data['token'] = $this->security->get_csrf_hash();
            }
        echo json_encode($data);
    }

    public function id_proff_back(){
        $data = [];

            $this->load->library('stripe_core');
            $image = $_FILES['identity_proof_back']['name'];

            $size = $_FILES['identity_proof_back']['size'];

            if(!empty($image)){
                //if($size > 50000000){

                     $imageArr=explode('.',$image); 
                     $rand=rand(10000,99999);
                     $newImageName=$imageArr[0].$rand.'.'.$imageArr[1];
                     $uploadPath="uploads/document_details/".$newImageName;
                     $isUploaded=move_uploaded_file($_FILES["identity_proof_back"]["tmp_name"],$uploadPath);
                     if($isUploaded){

                          $id_proof_file_back_id = $this->stripe_core->create_file_id(fopen('/var/www/vhosts/dipay.de/my.dipay.de/uploads/document_details/'.$newImageName, 'r'));
                         if(!empty($id_proof_file_back_id)){
                             $this->session->set_userdata('id_proof_file_back_id', $id_proof_file_back_id);
                            $data['success'] = 1;
                            $data['message'] = "image upload done";
                            $data['token'] = $this->security->get_csrf_hash();
                            $data['imageId'] = $id_proof_file_back_id;
                         }else{
                            $data['success'] = 0;
                            $data['message'] = "Something went wrong";
                            $data['token'] = $this->security->get_csrf_hash();

                         }
                       
                     }else{
                        $data['success'] = 0;
                        $data['message'] = "image not upload";
                        $data['token'] = $this->security->get_csrf_hash();
                     }
                /*}
                else
                {
                        $data['success'] = 0;
                        $data['message'] = "File size must be less than 5 mb";
                        $data['token'] = $this->security->get_csrf_hash();
                }*/
            }
            else
            {
                $data['success'] = 0;
                $data['message'] = "Please select file";
                $data['token'] = $this->security->get_csrf_hash();
            }
        echo json_encode($data);
    }

    public function addtional_id_proof(){
        $data = [];

            $this->load->library('stripe_core');
            $image = $_FILES['addtional_id_proof']['name'];

            $size = $_FILES['addtional_id_proof']['size'];

            if(!empty($image)){
                //if($size > 50000000){

                     $imageArr=explode('.',$image); 
                     $rand=rand(10000,99999);
                     $newImageName=$imageArr[0].$rand.'.'.$imageArr[1];
                     $uploadPath="uploads/document_details/".$newImageName;
                     $isUploaded=move_uploaded_file($_FILES["addtional_id_proof"]["tmp_name"],$uploadPath);
                     if($isUploaded){

                          $addtional_id_proof_id = $this->stripe_core->create_file_id(fopen('/var/www/vhosts/dipay.de/my.dipay.de/uploads/document_details/'.$newImageName, 'r'));
                         if(!empty($addtional_id_proof_id)){
                            //$_SESSION['id_proof_file_back_id'] = $id_proof_file_back_id;
                            $this->session->set_userdata('addtional_id_proof_id', $addtional_id_proof_id);
                            $data['success'] = 1;
                            $data['message'] = "image upload done";
                            $data['token'] = $this->security->get_csrf_hash();
                            $data['imageId'] = $addtional_id_proof_id;
                         }else{
                            $data['success'] = 0;
                            $data['message'] = "Something went wrong";
                            $data['token'] = $this->security->get_csrf_hash();

                         }
                       
                     }else{
                        $data['success'] = 0;
                        $data['message'] = "image not upload";
                        $data['token'] = $this->security->get_csrf_hash();
                     }
                /*}
                else
                {
                        $data['success'] = 0;
                        $data['message'] = "File size must be less than 5 mb";
                        $data['token'] = $this->security->get_csrf_hash();
                }*/
            }
            else
            {
                $data['success'] = 0;
                $data['message'] = "Please select file";
                $data['token'] = $this->security->get_csrf_hash();
            }
        echo json_encode($data);
    }
    public function user_relation(){
        $data=[];
        if (!has_permission('staff', '', 'view')) {
            access_denied('staff');
        }
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('staff');
        }
        $data['staff_members'] = $this->staff_model->get_user_relation('', ['active' => 1]);
        $data['title']         = _l('staff_members');
        $this->load->view('admin/staff/user_relation', $data);
    }
}
