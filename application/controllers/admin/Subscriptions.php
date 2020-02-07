<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Subscriptions extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('stripe_subscriptions');
        $this->load->model('subscriptions_model');
        $this->load->model('currencies_model');
        $this->load->model('taxes_model');
    }

    public function index()
    {
        if (!has_permission('subscriptions', '', 'view') && !has_permission('subscriptions', '', 'view_own')) {
            access_denied('Subscriptions View');
        }
        // print_r("expression"); exit();
        close_setup_menu();

        $data['title'] = _l('subscriptions');
        $this->load->view('admin/subscriptions/manage', $data);
    }

    public function table()
    {
        if (!has_permission('subscriptions', '', 'view') && !has_permission('subscriptions', '', 'view_own')) {
            ajax_access_denied();
        }
         // $this->app->get_table_data('subscriptions');
        $this->app->get_table_data('subscriptions1');
    }

    public function create()
    {
        if (!has_permission('subscriptions', '', 'create')) {
            access_denied('Subscriptions Create');
        }

        if ($this->input->post()) {
            print_r($_POST); exit();
            $insert_id = $this->subscriptions_model->create([
                'block_array'         => $this->input->post('block_array'),
                'name'                => $this->input->post('name'),
                'description'         => nl2br($this->input->post('description')),
                'description_in_item' => $this->input->post('description_in_item') ? 1 : 0,
                'monthly_costs'       => $this->input->post('costs'),
                'tax_id'              => $this->input->post('tax_id') ? $this->input->post('tax_id') : 0,

            ]);
            set_alert('success', _l('added_successfully', _l('subscription')));
            redirect(admin_url('subscriptions/edit/' . $insert_id));
        }

        $data['plans'] = [];

        try {
            $data['plans'] = $this->stripe_subscriptions->get_plans();
        } catch (Exception $e) {
            if ($this->stripe_subscriptions->has_api_key()) {
                $data['subscription_error'] = $e->getMessage();
            } else {
                $data['subscription_error'] = _l('api_key_not_set_error_message', '<a href="'.admin_url('settings?group=payment_gateways&tab=online_payments_stripe_tab').'">Stripe Checkout</a>');
            }
        }

        if ($this->input->get('customer_id')) {
            $data['customer_id'] = $this->input->get('customer_id');
        }

        $data['title'] = _l('add_new', _l('subscription_lowercase'));

        $data['taxes']      = $this->taxes_model->get();
        $data['currencies'] = $this->currencies_model->get();
        $data['bodyclass']  = 'subscription';

        // blocks data getting
        $data['blocks'] = $this->subscriptions_model->get_set_information();
        $this->load->view('admin/subscriptions/subscription', $data);
    }

    public function edit($id)
    {
        
        if (!has_permission('subscriptions', '', 'view') && !has_permission('subscriptions', '', 'view_own')) {
            access_denied('Subscriptions View');
        }

        $subscription = $this->subscriptions_model->get_by_id($id);

        if (!$subscription || (!has_permission('subscriptions', '', 'view') && $subscription->created_from != get_staff_user_id())) {
            show_404();
        }
        $data = [];

        $stripeSubscriptionId = $subscription->stripe_subscription_id;

        if ($this->input->post()) {
            if (!has_permission('subscriptions', '', 'edit')) {
                access_denied('Subscriptions Edit');
            }
            $update = [
                'block_array'         => $this->input->post('block_array'),
                'name'                => $this->input->post('name'),
                'description'         => nl2br($this->input->post('description')),
                'description_in_item' => $this->input->post('description_in_item') ? 1 : 0,
                // 'stripe_plan_id'      => $this->input->post('stripe_plan_id'),
                'monthly_costs'       => $this->input->post('costs'),
                'tax_id'              => $this->input->post('tax_id') ? $this->input->post('tax_id') : 0,
             ];
            
            // if (!empty($stripeSubscriptionId)) {
            //     unset($update['clientid']);
            //     unset($update['date']);
            // }

            try {
                $prorate = $this->input->post('prorate') ? true : false;
                $this->stripe_subscriptions->update_subscription($stripeSubscriptionId, $update, $subscription, $prorate);
            } catch (Exception $e) {
                set_alert('warning', $e->getMessage());
                redirect(admin_url('subscriptions/edit/' . $id));
            }

            $updated = $this->subscriptions_model->update($id, $update);

            if ($updated) {
                set_alert('success', _l('updated_successfully', _l('subscription')));
            }
            redirect(admin_url('subscriptions/edit/' . $id));
        }

        try {
            $data['plans'] = [];
            $data['plans'] = $this->stripe_subscriptions->get_plans();

            if (!empty($subscription->stripe_subscription_id)) {
                $data['stripeSubscription'] = $this->stripe_subscriptions->get_subscription($subscription->stripe_subscription_id);
                

                /*       $data['stripeSubscription']->billing_cycle_anchor = 'now';
                       $data['stripeSubscription']->save();
                       die;*/

                if ($subscription->status != 'canceled') {
                    $data['upcoming_invoice'] = $this->stripe_subscriptions->get_upcoming_invoice($subscription->stripe_customer_id, $subscription->stripe_subscription_id);

                    $data['upcoming_invoice'] = subscription_invoice_preview_data($subscription, $data['upcoming_invoice'], $data['stripeSubscription']);
                    // Throwing errors when not set in the invoice preview area
                    if (!isset($data['upcoming_invoice']->include_shipping)) {
                        $data['upcoming_invoice']->include_shipping = 0;
                    }
                }
            }
        } 
       
        catch (Exception $e) {
            if ($this->stripe_subscriptions->has_api_key()) {
                $data['subscription_error'] = $e->getMessage();
            } else {
                $data['subscription_error'] = check_for_links(_l('api_key_not_set_error_message', admin_url('settings?group=payment_gateways&tab=online_payments_stripe_tab')));

            }
        }

        // $data = array_merge($data, prepare_mail_preview_data('subscription_send_to_customer', $subscription->clientid));

        $data['child_invoices'] = $this->subscriptions_model->get_child_invoices($id);
        $data['subscription']   = $subscription;
        $data['title']          = $subscription->name;
        $data['taxes']          = $this->taxes_model->get();
        $data['currencies']     = $this->currencies_model->get();
        $data['bodyclass']      = 'subscription no-calculate-total';
        
        $data['blocks'] = $this->subscriptions_model->get_set_information();
        // print_r($data['subscription']);
        $this->load->view('admin/subscriptions/subscription', $data);
    }

    public function send_to_email($id)
    {
        if (!has_permission('subscriptions', '', 'view')) {
            access_denied('Subscription Send To Email');
        }

        if ($this->input->post()) {
            $success = $this->subscriptions_model->send_email_template($id, $this->input->post('cc'));
            if ($success) {
                set_alert('success', _l('subscription_sent_to_email_success'));
            } else {
                set_alert('danger', _l('subscription_sent_to_email_fail'));
            }
        }
        redirect(admin_url('subscriptions/edit/' . $id));
    }

    public function cancel($id)
    {
        if (!has_permission('subscriptions', '', 'edit')) {
            access_denied('Cancel Subscription');
        }

        $subscription = $this->subscriptions_model->get_by_id($id);

        if (!$subscription) {
            show_404();
        }

        try {
            $type    = $this->input->get('type');
            $ends_at = time();
            if ($type == 'immediately') {
                $this->stripe_subscriptions->cancel($subscription->stripe_subscription_id);
                // The mail sent via the webhook
                // $this->subscriptions_model->send_email_template($subscription->id, '', 'subscription_cancelled_to_customer');
            } elseif ($type == 'at_period_end') {
                $ends_at = $this->stripe_subscriptions->cancel_at_end_of_billing_period($subscription->stripe_subscription_id);
            } else {
                throw new Exception('Invalid Cancelation Type', 1);
            }

            $update = ['ends_at' => $ends_at];

            // Hook may be delayed and the status won't be cancelled upon refresh
            // This is used to prevent confusions till the webhook is invoked
            if ($type == 'immediately') {
                $update['status'] = 'canceled';
            }
            $this->subscriptions_model->update($id, $update);

            set_alert('success', _l('subscription_canceled'));
        } catch (Exception $e) {
            set_alert('danger', $e->getMessage());
        }

        redirect(admin_url('subscriptions/edit/' . $id));
    }

    public function resume($id)
    {
        if (!has_permission('subscriptions', '', 'edit')) {
            access_denied('Resume Subscription');
        }

        $subscription = $this->subscriptions_model->get_by_id($id);
        if (!$subscription) {
            show_404();
        }

        try {
            $this->stripe_subscriptions->resume($subscription->stripe_subscription_id, $subscription->stripe_plan_id);
            $this->subscriptions_model->update($id, ['ends_at' => null]);
            set_alert('success', _l('subscription_resumed'));
        } catch (Exception $e) {
            set_alert('danger', $e->getMessage());
        }
        redirect(admin_url('subscriptions/edit/' . $id));
    }

    public function delete($id)
    {
        if (!has_permission('subscriptions', '', 'delete')) {
            access_denied('Subscriptions Delete');
        }

        if ($this->subscriptions_model->delete($id)) {
            set_alert('success', _l('deleted', _l('subscription')));
        } else {
            set_alert('warning', _l('problem_deleting', _l('subscription')));
        }
        if (strpos($_SERVER['HTTP_REFERER'], 'clients/') !== false) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            redirect(admin_url('subscriptions'));
        }
    }



    public function settings(){

        $data['info'] = $this->subscriptions_model->get_set_information();
        $this->load->view('admin/subscriptions/settings',$data);
    }

    public function blocks_load()
    {
        $fetch_data = $this->subscriptions_model->blocks_make_datatables();
        $data =  array();
            foreach ($fetch_data as $rows) {
                $sub_data = array();
                $index = $rows->index;
                if($index == "Child") {
                   $sub_data['content'] = '&nbsp;&nbsp;&nbsp;&nbsp;• &nbsp;&nbsp;'.$rows->content; 
                }
                else
                    $sub_data['content'] = $rows->content;
                $sub_data['price'] = $rows->price;
                $sub_data['currency'] = $rows->currency;
                 $sub_data['edit'] = '<button type="button" name="Edit" id="'.$rows->id.'" class="btn btn-success btn-xs edit">'._l('edit').'</button>';
                $sub_data['delete'] = '<button type="button" name="delete" id="'.$rows->id.'" class="btn btn-danger btn-xs delete">'._l('delete').'</button>'; 
                $data[] = $sub_data;
            }

        $output = array(
                "draw"                  =>    intval($_POST["draw"]),
                "recordsTotal"          =>    $this->subscriptions_model->blocks_get_all_data(),
                "recordsFiltered"       =>    $this->subscriptions_model->blocks_get_filtered_data(),
                "data"                  =>    $data
            ); 
        echo json_encode($output);  
    }

    public function blocks_single_remove()
   {
        $removed_data = $this->subscriptions_model->blocks_single_remove($_POST['block_id']);
        echo ("Block Deleted Successfully");
   }

   public function blocks_action(){

        if($_POST['action'] == 'add')
        {
            $insert_data['content'] = $_POST['content'];
            $insert_data['price'] = $_POST['price'];
            $insert_data['currency'] = $_POST['currency'];
            $insert_data['index'] = $_POST['index'];
            $this->subscriptions_model->insert_block($insert_data);
            echo "Blocks Data Added Successfully";
        }
        if ($_POST['action'] == 'edit') {

            $update_data['content'] = $_POST['content'];
            $update_data['price'] = $_POST['price'];
            $update_data['currency'] = $_POST['currency'];
            $update_data['index'] = $_POST['index'];

            $this->subscriptions_model->updata_block($update_data,$_POST['block_id']);
            echo "Blocks Data Updated Successfully";
        }
   }

   public function blocks_single_get(){

        $getted_data = $this->subscriptions_model->get_single_block($_POST['block_id']);
        foreach ($getted_data as $key => $value) {
            $output['content'] = $value->content;
            $output['price'] = $value->price;
            $output['currency'] = $value->currency;
            $output['index'] = $value->index;
        }
        echo json_encode($output);
   }
}
