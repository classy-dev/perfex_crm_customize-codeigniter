<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Stripe_ideal_gateway extends App_gateway
{
    public function __construct()
    {
        /**
        * Call App_gateway __construct function
        */
        parent::__construct();

        /**
        * REQUIRED
        * Gateway unique id
        * The ID must be alpha/alphanumeric
        */
        $this->setId('stripe_ideal');

        /**
         * REQUIRED
         * Gateway name
         */
        $this->setName('Stripe iDEAL');

        /**
         * Add gateway settings
        */
        $this->setSettings([
            [
                'name'      => 'api_secret_key',
                'encrypted' => true,
                'label'     => 'settings_paymentmethod_stripe_api_secret_key',
            ],
            [
                'name'  => 'api_publishable_key',
                'label' => 'settings_paymentmethod_stripe_api_publishable_key',
            ],
            [
                'name'          => 'description_dashboard',
                'label'         => 'settings_paymentmethod_description',
                'type'          => 'textarea',
                'default_value' => 'Payment for Invoice {invoice_number}',
            ],
            [
                'name'          => 'statement_descriptor',
                'label'         => 'ideal_customer_statement_descriptor',
                'type'          => 'textarea',
                'default_value' => 'Payment for Invoice {invoice_number}',
                'field_attributes'=>['maxlength' => 22],
                'after'=>'<p class="mbot15">Statement descriptors are limited to 22 characters, cannot use the special characters <, >, \', ", or *, and must not consist solely of numbers.</p>'
            ],
            [
                'name'             => 'webhook_key',
                'label'            => 'Stripe Webhook Key',
                'default_value'    => app_generate_hash(),
                'after'            => '<p class="mbot15">Secret key to protect your webhook, webhook URL: ' . site_url('gateways/stripe_ideal/webhook/YOUR_WEBHOOK_KEY</p>'),
                'field_attributes' => ['required' => true],
            ],
            [
                'name'             => 'currencies',
                'label'            => 'settings_paymentmethod_currencies',
                'default_value'    => 'EUR',
                'field_attributes' => ['disabled' => true],
            ],
            [
                'name'          => 'test_mode_enabled',
                'type'          => 'yes_no',
                'default_value' => 1,
                'label'         => 'settings_paymentmethod_testing_mode',
            ],
        ]);
    }

    public function charge($source, $amount, $invoice_id)
    {
        $this->ci->load->library('stripe_core');

        return $this->ci->stripe_core->create_charge([
                'currency'    => 'eur',
                'amount'      => $amount,
                'source'      => $source,
                'description' => str_replace('{invoice_number}', format_invoice_number($invoice_id), $this->getSetting('description_dashboard')),
                'metadata'    => [
                    'invoice_id'        => $invoice_id,
                    'pcrm-stripe-ideal' => true,
                ],
            ]);
    }

    public function finish_payment($charge)
    {
        $success = $this->addPayment(
            [
                'amount'        => ($charge->amount / 100),
                'invoiceid'     => $charge->metadata->invoice_id,
                'transactionid' => $charge->id,
                'paymentmethod' => strtoupper($charge->source->ideal->bank),
            ]
        );

        return (bool) $success;
    }

    public function process_payment($data)
    {
        $name = $data['invoice']->client->company;
        // Address information
        $country = '';

        $db_country = get_country_short_name($data['invoice']->billing_country);
        if ($db_country != '') {
            $country = $db_country;
        }

        $city        = $data['invoice']->billing_city;
        $line1       = $data['invoice']->billing_street;
        $postal_code = $data['invoice']->billing_zip;
        $state       = $data['invoice']->billing_state;

        $address = [
            'city'        => "$city",
            'country'     => "$country",
            'line1'       => "$line1",
            'postal_code' => "$postal_code",
            'state'       => "$state",
        ];

        $stripe_data = [
            'type'     => 'ideal',
            'ideal'=> [
                'statement_descriptor' =>  str_replace('{invoice_number}', format_invoice_number($data['invoice']->id), $this->getSetting('statement_descriptor'))
            ],
            'amount'   => $data['amount'] * 100,
            'currency' => 'eur',

            'owner' => [
                'name'    => $name,
                'address' => $address,
            ],

            'redirect' => [
                'return_url' => site_url('gateways/stripe_ideal/response/' . $data['invoice']->id . '/' . $data['invoice']->hash),
            ],

            'metadata' => [
                'invoice_id'        => $data['invoice']->id,
                'pcrm-stripe-ideal' => true,
            ],
        ];

        try {
            $this->ci->load->library('stripe_core');
            $source = $this->ci->stripe_core->create_source($stripe_data);

            if ($source->created != '') {
                redirect($source->redirect->url);
            } else {
                if (!empty($source->failure_reason)) {
                    set_alert('warning', $source->failure_reason);
                }
            }
        } catch (Exception $e) {
            set_alert('warning', $e->getMessage());
        }

        redirect(site_url('invoice/' . $data['invoice']->id . '/' . $data['invoice']->hash));
    }
}
