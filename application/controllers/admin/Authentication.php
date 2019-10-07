<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Authentication extends App_Controller
{
    public function __construct()
    {
        parent::__construct();

        if ($this->app->is_db_upgrade_required()) {
            redirect(admin_url());
        }

        load_admin_language();
        $this->load->model('Authentication_model');
        $this->load->library('form_validation');

        $this->form_validation->set_message('required', _l('form_validation_required'));
        $this->form_validation->set_message('valid_email', _l('form_validation_valid_email'));
        $this->form_validation->set_message('matches', _l('form_validation_matches'));

        hooks()->do_action('admin_auth_init');
    }

    public function index()
    {   
        $this->admin();
        
    }

    public function admin()
    {
        if (is_staff_logged_in()) {
            redirect(admin_url());
        }

        $this->form_validation->set_rules('password', _l('admin_auth_login_password'), 'required');
        $this->form_validation->set_rules('email', _l('admin_auth_login_email'), 'trim|required|valid_email');
        if (get_option('recaptcha_secret_key') != '' && get_option('recaptcha_site_key') != '') {
            $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
        }
        if ($this->input->post()) 
        {
            $trial_date = $this->get_created_date();
            $role_status = $this->get_role_status();
            $today = date('Y-m-d');
            if($today < $trial_date || $role_status['admin'] == 1 || $role_status['status'] == "paid")
            {
                if ($this->form_validation->run() !== false) 
                {
                    $email    = $this->input->post('email');
                    $password = $this->input->post('password', false);
                    $remember = $this->input->post('remember');

                    $data = $this->Authentication_model->login($email, $password, $remember, true);
                   
                    if (is_array($data) && isset($data['memberinactive'])) {
                        set_alert('danger', _l('admin_auth_inactive_account'));
                        redirect(admin_url('authentication'));
                    } elseif (is_array($data) && isset($data['two_factor_auth'])) {
                        $this->Authentication_model->set_two_factor_auth_code($data['user']->staffid);

                        $sent = send_mail_template('staff_two_factor_auth_key', $data['user']);

                        if (!$sent) {
                            set_alert('danger', _l('two_factor_auth_failed_to_send_code'));
                            redirect(admin_url('authentication'));
                        } else {
                            set_alert('success', _l('two_factor_auth_code_sent_successfully', $email));
                        }
                        redirect(admin_url('authentication/two_factor'));
                    } elseif ($data == false) {
                        set_alert('danger', _l('admin_auth_invalid_email_or_password'));
                        redirect(admin_url('authentication'));
                    }

                    $this->load->model('announcements_model');
                    $this->announcements_model->set_announcements_as_read_except_last_one(get_staff_user_id(), true);

                    // is logged in
                    
                        maybe_redirect_to_previous_url();
                        hooks()->do_action('after_staff_login');
                        $staff_user_id = get_staff_user_id();
                        // print_r($staff_user_id); exit();
                        redirect(admin_url());

                }
            }
            else{
                $data['email'] = $_POST['email'];
                $data['password'] = $_POST['password'];
                $data['warning'] = '<h4 style="text-align:center">You are out of date.</h4>
                                    <p style="text-align:center">If you want to continue, you should pay to Administrator</p>';
                $data['buy'] = '<button type="submit" class="btn btn-info" style="width:100%;">Buy</button>';
            }
        }

        $data['title'] = _l('admin_auth_login_heading');
        $this->load->view('authentication/login_admin', $data);
        
    }

    public function two_factor()
    {
        $this->form_validation->set_rules('code', _l('two_factor_authentication_code'), 'required');

        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                $code = $this->input->post('code');
                $code = trim($code);
                if ($this->Authentication_model->is_two_factor_code_valid($code)) {
                    $user = $this->Authentication_model->get_user_by_two_factor_auth_code($code);
                    $this->Authentication_model->clear_two_factor_auth_code($user->staffid);
                    $this->Authentication_model->two_factor_auth_login($user);

                    $this->load->model('announcements_model');
                    $this->announcements_model->set_announcements_as_read_except_last_one(get_staff_user_id(), true);

                    maybe_redirect_to_previous_url();

                    hooks()->do_action('after_staff_login');
                    redirect(admin_url());
                } else {
                    set_alert('danger', _l('two_factor_code_not_valid'));
                    redirect(admin_url('authentication/two_factor'));
                }
            }
        }
        $this->load->view('authentication/set_two_factor_auth_code');
    }

    public function forgot_password()
    {
        if (is_staff_logged_in()) {
            redirect(admin_url());
        }
        $this->form_validation->set_rules('email', _l('admin_auth_login_email'), 'trim|required|valid_email|callback_email_exists');
        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                $success = $this->Authentication_model->forgot_password($this->input->post('email'), true);
                if (is_array($success) && isset($success['memberinactive'])) {
                    set_alert('danger', _l('inactive_account'));
                    redirect(admin_url('authentication/forgot_password'));
                } elseif ($success == true) {
                    set_alert('success', _l('check_email_for_resetting_password'));
                    redirect(admin_url('authentication'));
                } else {
                    set_alert('danger', _l('error_setting_new_password_key'));
                    redirect(admin_url('authentication/forgot_password'));
                }
            }
        }
        $this->load->view('authentication/forgot_password');
    }

    public function reset_password($staff, $userid, $new_pass_key)
    {
        if (!$this->Authentication_model->can_reset_password($staff, $userid, $new_pass_key)) {
            set_alert('danger', _l('password_reset_key_expired'));
            redirect(admin_url('authentication'));
        }
        $this->form_validation->set_rules('password', _l('admin_auth_reset_password'), 'required');
        $this->form_validation->set_rules('passwordr', _l('admin_auth_reset_password_repeat'), 'required|matches[password]');
        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                hooks()->do_action('before_user_reset_password', [
                    'staff'  => $staff,
                    'userid' => $userid,
                ]);
                $success = $this->Authentication_model->reset_password($staff, $userid, $new_pass_key, $this->input->post('passwordr', false));
                if (is_array($success) && $success['expired'] == true) {
                    set_alert('danger', _l('password_reset_key_expired'));
                } elseif ($success == true) {
                    hooks()->do_action('after_user_reset_password', [
                        'staff'  => $staff,
                        'userid' => $userid,
                    ]);
                    set_alert('success', _l('password_reset_message'));
                } else {
                    set_alert('danger', _l('password_reset_message_fail'));
                }
                redirect(admin_url('authentication'));
            }
        }
        $this->load->view('authentication/reset_password');
    }

    public function set_password($staff, $userid, $new_pass_key)
    {
        if (!$this->Authentication_model->can_set_password($staff, $userid, $new_pass_key)) {
            set_alert('danger', _l('password_reset_key_expired'));
            redirect(admin_url('authentication'));
            if ($staff == 1) {
                redirect(admin_url('authentication'));
            } else {
                redirect(site_url());
            }
        }
        $this->form_validation->set_rules('password', _l('admin_auth_set_password'), 'required');
        $this->form_validation->set_rules('passwordr', _l('admin_auth_set_password_repeat'), 'required|matches[password]');
        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                $success = $this->Authentication_model->set_password($staff, $userid, $new_pass_key, $this->input->post('passwordr', false));
                if (is_array($success) && $success['expired'] == true) {
                    set_alert('danger', _l('password_reset_key_expired'));
                } elseif ($success == true) {
                    set_alert('success', _l('password_reset_message'));
                } else {
                    set_alert('danger', _l('password_reset_message_fail'));
                }
                if ($staff == 1) {
                    redirect(admin_url('authentication'));
                } else {
                    redirect(site_url());
                }
            }
        }
        $this->load->view('authentication/set_password');
    }

    public function logout()
    {
        $this->Authentication_model->logout();
        hooks()->do_action('after_user_logout');
        redirect(admin_url('authentication'));
    }

    public function email_exists($email)
    {
        $total_rows = total_rows(db_prefix().'staff', [
            'email' => $email,
        ]);
        if ($total_rows == 0) {
            $this->form_validation->set_message('email_exists', _l('auth_reset_pass_email_not_found'));

            return false;
        }

        return true;
    }

    public function recaptcha($str = '')
    {
        return do_recaptcha_validation($str);
    }

    public function register($id = '')
    {
        
        if ($this->input->post()) {
            $data = $this->input->post();
            // Don't do XSS clean here.
            $data['email_signature'] = $this->input->post('email_signature', false);
            $data['email_signature'] = html_entity_decode($data['email_signature']);

            $data['password'] = $this->input->post('password', false);
            $data['role'] = 1;
            

            if ($id == '') {
            
                $id = $this->staff_model->add_staff($data);
               
                $role_permission = [
                    "marketplace" => ["view", "edit"],
                    "bulk_pdf_exporter" => ["view"],
                    "contracts" => ["view_own", "create","edit","delete"],
                    "customers" => ["create","edit","delete"],
                    "invoices" => ["view_own", "create", "edit"],
                    "projects" => ["view", "create","edit","delete"],
                    "reports" => ["view"],
                    "checklist_templates" => ["create", "delete"],
                    "leads" => ["view", "delete"]

                ];
                    
                $success = $this->staff_model->insert_register_permissions($role_permission,$id);
            
            }

                $email    = $this->input->post('email');
                $password = $this->input->post('password', false);
                $remember = $this->input->post('remember');

                $data = $this->Authentication_model->login($email, $password, $remember, true);
                // is logged in
                
                    maybe_redirect_to_previous_url();
                    hooks()->do_action('after_staff_login');
                    redirect(admin_url());
        }
        if ($id == '') {
            $title = _l('add_new', _l('staff_member_lowercase'));
        } 
        $this->load->view('authentication/register');
    }
    public function get_created_date(){

       $email = $_POST['email'];
       $date = $this->load->Authentication_model->get_created_date_db($email);
       $date1 = $date[0]['datecreated'];
       $new_date = DateTime::createFromFormat("Y-m-d H:i:s",$date1)->format("Y-m-d");
       $trial_date = date('Y-m-d', strtotime($new_date.' + 14 days'));
       return $trial_date;
        
    }
    public function get_role_status()
    {
       $email = $_POST['email'];
       $data = $this->load->Authentication_model->get_role_status_db($email);
    // print_r($data); exit();
       $admin = $data[0]['admin'];
       $status = $data[0]['pay_status'];
       $data1['admin'] = $admin;
       $data1['status'] = $status;
       return $data1;
    }
    public function trial_pay()
    {
        
        $email = $_POST['email'];
        $psw = $_POST['password'];
        $data = $this->load->Authentication_model->get_name($email);
        // print_r($data);
        $res['first_name'] = $data[0]['firstname'];
        $res['last_name'] = $data[0]['lastname'];
        $res['email'] = $email;
        $res['password'] = $psw;
        // print_r($res); exit();
        $this->load->view('authentication/trial_payments', $res);
    }
    public function charge()
    {
        // $path = 
        // echo $path;
        // require_once('../../vendor/');
        \Stripe\Stripe::setApiKey('sk_test_oFjOOLGDirGCOrKYOgAepFsU00eGnk7m0K');

        //Sanitize POST Array
        $POST =  filter_var_array($_POST,FILTER_SANITIZE_STRING);

        $first_name = $POST['first_name'];
        $last_name = $POST['last_name'];
        $email = $POST['email'];
        $password = $POST['password'];
        $token = $POST['stripeToken'];
        
        $predata['email'] = $email;
        $predata['password'] = $password;

        // Create Customer In Stripe

        $customer = \Stripe\Customer::create(array(
            "email" => $email,
            "source" => $token,  
        ));

        //Charge Customer

        $charge = \Stripe\Charge::create(array(
            "amount" => 5000,
            "currency" => "usd",
            "description" => "trial pay",
            "customer" => $customer->id
        ));

        if($charge['status'] == "succeeded") 
        {
            $this->load->view('authentication/trial_payments_success',$predata);
            $data = [];
            $data['email'] = $email;
            $data['password'] = $password;
            $data['pay_status'] = $this->load->Authentication_model->set_pay_status($email);

            
        }

    }
    public function after_pay_before_login(){

        $data['email'] = $_POST['email'];
        $data['password'] = $_POST['password'];

        // $transaction = [];
        // $transaction['amount'] = $charge['amount']/100;
        // $transaction['transaction_id'] = $charge['id'];
        // $transaction['currency'] = $charge['currency'];
        // $transaction['customer'] = $charge['customer'];
        // $transaction['description'] = $charge['description'];
        // $transaction['status'] = $charge['status'];
        // $staffid = $this->load->Authentication_model->get_staffid($email);
        // $transaction['staffid'] = $staffid[0]['staffid'];
        // $transaction['staff_name'] = $first_name.''.$last_name;
        // $transaction['created_at'] = date("Y-m-d H:i:s");
        // $this->load->Authentication_model->set_transaction($transaction);

        $this->load->view('authentication/login_admin_after_pay',$data);
    }
}
