<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stripe extends App_Controller
{
    public function complete_purchase($id, $hash)
    {
        check_invoice_restrictions($id, $hash);

        if ($this->input->post()) {
            $data = $this->input->post();
            $total = $data['total'];
            $this->load->model('invoices_model');
            $invoice = $this->invoices_model->get($id);

            load_client_language($invoice->clientid);

            $data['amount'] = $total;

            $data['description'] = str_replace('{invoice_number}', format_invoice_number($invoice->id), $this->stripe_gateway->getSetting('description_dashboard'));

            $data['currency'] = $invoice->currency_name;
            $data['clientid'] = $invoice->clientid;
            $data['email']    = $this->input->post('stripeEmail');

            try {
                $charge        = $this->stripe_gateway->finish_payment($data);
                $transactionid = $charge->id;

                $success         = $this->stripe_gateway->addPayment(
                    [
                      'amount'        => (strcasecmp($data['currency'], 'JPY') == 0 ? $charge->amount : $charge->amount / 100),
                      'invoiceid'     => $invoice->id,
                      'transactionid' => $transactionid,
                      ]
                    );

                set_alert('success', _l($success ? 'online_payment_recorded_success' : 'online_payment_recorded_success_fail_database'));

                redirect(site_url('invoice/' . $invoice->id . '/' . $invoice->hash));
            } catch (Exception $e) {
                set_alert('danger', $e->getMessage());
                redirect(site_url('invoice/' . $invoice->id . '/' . $invoice->hash));
            }
        }
    }

    public function make_payment()
    {
        check_invoice_restrictions($this->input->get('invoiceid'), $this->input->get('hash'));
        $this->load->model('invoices_model');
        $invoice = $this->invoices_model->get($this->input->get('invoiceid'));
        $language = load_client_language($invoice->clientid);
        $data['locale'] = get_locale_key($language);

        $data['invoice'] = $invoice;
        if (is_client_logged_in()) {
            $data['contact'] = $this->clients_model->get_contact(get_contact_user_id());
        }

        if(!empty($invoice->client->stripe_id)){
            $this->load->library('stripe_core');
            $data['stripe_customer'] = $this->stripe_core->get_customer_with_default_source($invoice->client->stripe_id);
        }

        $data['total'] = $this->input->get('total');
        echo $this->get_view($data);
    }

    public function get_view($data = [])
    {
        ?>
        <?php echo payment_gateway_head(_l('payment_for_invoice') . ' ' . format_invoice_number($data['invoice']->id)); ?>
        <body class="gateway-stripe">
            <div class="container">
                <div class="col-md-8 col-md-offset-2 mtop30">
                  <div class="mbot30 text-center">
                      <?php echo payment_gateway_logo(); ?>
                    </div>
                    <div class="row">
                        <div class="panel_s">
                            <div class="panel-body">
                               <h3 class="no-margin">
                                  <b><?php echo _l('payment_for_invoice'); ?></b>
                                  <a href="<?php echo site_url('invoice/' . $data['invoice']->id . '/' . $data['invoice']->hash); ?>">
                                    <b>
                                        <?php echo format_invoice_number($data['invoice']->id); ?>
                                    </b>
                                  </a>
                              </h3>
                              <h4><?php echo _l('payment_total', app_format_money($data['total'], $data['invoice']->currency_name)); ?></h4>
                              <hr />
                              <?php
                             if(isset($data['stripe_customer']) && !empty($data['stripe_customer']->default_source)) {
                                    echo form_open(site_url('gateways/stripe/complete_purchase/'.$data['invoice']->id.'/'. $data['invoice']->hash));
                                    echo '<button type="submit" name="pay_with_card" value="true" class="btn btn-success mbot15">';
                                    echo form_hidden('total', $data['total']);
                                    echo _l('view_invoice_pdf_link_pay') . ' ('.$data['stripe_customer']->default_source->brand . ' ' . $data['stripe_customer']->default_source->last4.')';
                                    echo '</button>';
                                    echo form_close();
                              }
                              $form = '<form action="' . site_url('gateways/stripe/complete_purchase/'.$data['invoice']->id.'/'. $data['invoice']->hash) . '" method="POST">
                                <script
                                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                data-label="'.(isset($data['stripe_customer']) && !empty($data['stripe_customer']->default_source) ? _l('enter_new_card') : _l('pay_with_card')).'"
                                data-key="' . $this->stripe_gateway->getSetting('api_publishable_key') . '"
                                data-amount="' . (strcasecmp($data['invoice']->currency_name, 'JPY') == 0 ? $data['total'] : $data['total'] * 100) . '"
                                data-name="' . html_escape(get_option('companyname')) . '"
                                data-billing-address="true"
                                data-description=" ' . _l('payment_for_invoice') . ' ' . format_invoice_number($data['invoice']->id) . '";
                                data-locale="'.$data['locale'].'"
                                ' . (is_client_logged_in() ? 'data-email="' . $data['contact']->email . '"' : '') . '
                                data-currency="' . $data['invoice']->currency_name . '"
                                >
                            </script>
                            ' . form_hidden('total', $data['total']) . '
                        </form>';
                        echo $form;
                    ?>
                    </div>
                </div>
            </div>
        </div>
        <?php echo payment_gateway_scripts(); ?>
        <?php if(!isset($data['stripe_customer']) || (isset($data['stripe_customer']) && empty($data['stripe_customer']->default_source))) { ?>
            <script>
                $(function(){
                    $('.stripe-button-el').click();
                });
            </script>
         <?php } ?>
        <?php echo payment_gateway_footer(); ?>
    <?php
    }

    public function webhook($key)
    {
        $saved_key = $this->stripe_gateway->getSetting('webhook_key');

        if ($saved_key == $key) {
            $input = json_decode(file_get_contents('php://input'), true);

            if (isset($input['type'])) {
                if (isset($input['data']['object']['lines']['data'][0]['metadata']['pcrm-subscription-hash'])) {
                    // Invoice event
                    $meta = $input['data']['object']['lines']['data'][0]['metadata'];
                } elseif (isset($input['data']['object']['metadata']['pcrm-subscription-hash'])) {
                    // Customer event
                    $meta = $input['data']['object']['metadata'];
                }

                if (!isset($meta['pcrm-subscription-hash'])) {
                    return false;
                }

                $this->load->model('subscriptions_model');
                $subscription = $this->subscriptions_model->get_by_hash($meta['pcrm-subscription-hash']);

                if (!$subscription) {
                    return false;
                }

                $this->db->select('email')
                ->from(db_prefix().'staff')
                ->where('staffid', $subscription->created_from);
                $staff = $this->db->get()->row();
                $cc    = '';
                if ($staff) {
                    $cc = $staff->email;
                }

                if ($input['type'] == 'invoice.payment_succeeded') {
                    $stripeSubtotal   = $input['data']['object']['subtotal'];
                    $stripeTotal      = $input['data']['object']['total'];
                    $stripeTaxPercent = $input['data']['object']['tax_percent'];

                    if ($subscription) {
                        $new_invoice_data = create_subscription_invoice_data($subscription, $input['data']['object']);

                        $this->subscriptions_model->update($subscription->id, ['next_billing_cycle' => $input['data']['object']['lines']['data'][0]['period']['end']]);

                        $this->load->model('invoices_model');

                        if(!defined('STRIPE_SUBSCRIPTION_INVOICE')) {
                            define('STRIPE_SUBSCRIPTION_INVOICE', true);
                        }

                        $id = $this->invoices_model->add($new_invoice_data);

                        if ($id) {
                            $this->db->where('id', $id);
                            $this->db->update(db_prefix().'invoices', [
                                'addedfrom' => $subscription->created_from,
                            ]);

                            $payment_data['paymentmode']   = 'stripe';
                            $payment_data['amount']        = $new_invoice_data['total'];
                            $payment_data['invoiceid']     = $id;
                            $payment_data['transactionid'] = $input['data']['object']['charge'];

                            $this->load->model('payments_model');
                            $this->payments_model->add($payment_data, $subscription->id);

                            $this->subscriptions_model->update($subscription->id, ['status' => 'active']);
                        }
                    }
                } elseif ($input['type'] == 'invoice.payment_failed') {
                    $this->subscriptions_model->send_email_template($subscription->id, $cc, 'subscription_payment_failed_to_customer');
                } elseif ($input['type'] == 'customer.subscription.deleted') {
                    $this->subscriptions_model->send_email_template($subscription->id, $cc, 'subscription_cancelled_to_customer');
                    $this->subscriptions_model->update($subscription->id, ['status' => $input['data']['object']['status'], 'next_billing_cycle' => null]);
                } elseif ($input['type'] == 'customer.subscription.updated') {

                    $update = [
                        'status'             => $input['data']['object']['status'],
                        'next_billing_cycle' => $input['data']['object']['current_period_end'],
                        'quantity'           => $input['data']['object']['items']['data'][0]['quantity'],
                        'ends_at'            => $input['data']['object']['cancel_at_period_end'] ? $input['data']['object']['current_period_end'] : null,
                    ];

                    if($input['data']['object']['tax_percent'] === null) {
                        $update['tax_id'] = 0;
                    } else if($subscription->tax_percent != $input['data']['object']['tax_percent']) {
                        $this->db->where('taxrate', number_format($input['data']['object']['tax_percent'], get_decimal_places(), '.', ''));
                        $dbTax = $this->db->get(db_prefix().'taxes')->row();
                        if($dbTax) {
                            $update['tax_id'] = $dbTax->id;
                        }
                    }

                    if($subscription->status == 'future') {
                        unset($update['status']);
                        unset($update['next_billing_cycle']);
                    }
                    $this->subscriptions_model->update($subscription->id, $update);
                }
            }
        } else {
            header('HTTP/1.0 403 Not Authorized');
            echo 'Webhook key is not matching';
        }
    }
}
