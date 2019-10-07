<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Stripe_ideal extends App_Controller
{
    public function response($id, $hash)
    {
        $this->load->model('invoices_model');
        check_invoice_restrictions($id, $hash);

        $invoice = $this->invoices_model->get($id);
        load_client_language($invoice->clientid);

        $this->load->library('stripe_core');

        try {

            $source_id = $this->input->get('source');
            $source    = $this->stripe_core->get_source($source_id);

            if ($source->status == 'chargeable') {
                try {
                    try {
                        $charge = $this->stripe_ideal_gateway->charge($source->id, $source->amount, $source->metadata->invoice_id);

                        if ($charge->status == 'succeeded') {
                            $charge->invoice_id = $source->metadata->invoice_id;
                            $success            = $this->stripe_ideal_gateway->finish_payment($charge);

                            set_alert('success', $success ? _l('online_payment_recorded_success') : _l('online_payment_recorded_success_fail_database'));
                        } elseif ($charge->status == 'pending') {
                            set_alert('success', _l('payment_received_awaiting_confirmation'));
                        } else {
                            // In the mean time the webhook probably got the source
                            $source = $this->stripe_core->get_source($source_id);
                            if ($source->status == 'consumed') {
                                set_alert('success', _l('online_payment_recorded_success'));
                            } else {
                                $errMsg = _l('invoice_payment_record_failed');
                                if ($charge->failure_message) {
                                    $errMsg .= ' - ' . $charge->failure_message;
                                }
                                set_alert('warning', $errMsg);
                            }
                        }
                    } catch (Exception $e) {
                        // In the mean time the webhook probably got the source
                        $source = $this->stripe_core->get_source($source_id);
                        if ($source->status == 'consumed') {
                            set_alert('success', _l('online_payment_recorded_success'));
                        } else {
                            set_alert('warning', $e->getMessage());
                        }
                    }
                } catch (Exception $e) {
                    set_alert('warning', $e->getMessage());
                }
            } else {
                set_alert('warning', _l('invoice_payment_record_failed'));
            }
        } catch (Exception $e) {
            set_alert('warning', $e->getMessage());
        }

        redirect(site_url('invoice/' . $id . '/' . $hash));
    }

    public function webhook($key)
    {
        $saved_key = $this->stripe_ideal_gateway->getSetting('webhook_key');

        if ($saved_key == $key) {
            $input = json_decode(file_get_contents('php://input'), true);
            $data  = $input['data']['object'];

            $pcrm_gateway = isset($data['metadata']['pcrm-stripe-ideal'])
            ? $data['metadata']['pcrm-stripe-ideal']
            : false;

            if ($pcrm_gateway == true
                && (isset($data['type']) && $data['type'] == 'ideal')
                && $data['status'] == 'chargeable'
             ) {
                $invoice_id = intval($data['metadata']['invoice_id']);
                $charge     = $this->stripe_ideal_gateway->charge($data['id'], $data['amount'], $invoice_id);
                if ($charge->status == 'succeeded') {
                    $this->stripe_ideal_gateway->finish_payment($charge);
                }
            }
        } else {
            header('HTTP/1.0 403 Not Authorized');
            echo 'Webhook key is not matching.';
        }
    }
}
