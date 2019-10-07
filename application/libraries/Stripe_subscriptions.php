<?php

defined('BASEPATH') or exit('No direct script access allowed');

include_once(APPPATH . 'libraries/Stripe_core.php');

class Stripe_subscriptions extends Stripe_core
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_upcoming_invoice($customer_id, $subscription_id)
    {
        return \Stripe\Invoice::upcoming(['customer' => $customer_id, 'subscription' => $subscription_id]);
    }

    public function get_plans()
    {
        return \Stripe\Plan::all(['limit' => 100]);
    }

    public function get_plan($id)
    {
        return \Stripe\Plan::retrieve($id);
    }

    public function get_product($id)
    {
        return \Stripe\Product::retrieve($id);
    }

    public function get_subscription($id)
    {
        return \Stripe\Subscription::retrieve($id);
    }

    public function cancel($id)
    {
        $sub = $this->get_subscription($id);
        $sub->cancel();
    }

    public function cancel_at_end_of_billing_period($id)
    {
        $sub = $this->get_subscription($id);

        \Stripe\Subscription::update($id, [
            'cancel_at_period_end' => true,
        ]);

        return $sub->current_period_end;
    }

    public function resume($id, $plan_id)
    {
        $stripeSubscription = $this->get_subscription($id);

        \Stripe\Subscription::update($id, [
        'cancel_at_period_end' => false,
        'items'                => [
            [
                'id'   => $stripeSubscription->items->data[0]->id,
                'plan' => $plan_id,
            ],
        ],
    ]);
    }

    public function pause()
    {
        $sub = $this->get_subscription($id);
        $sub->cancel();
    }

    public function update_subscription($subscription_id, $update_values, $db_subscription, $prorate = false)
    {
        if (empty($subscription_id)) {
            return false;
        }

        if ($update_values['tax_id'] != $db_subscription->tax_id
                    || $update_values['quantity'] != $db_subscription->quantity
                    || $update_values['stripe_plan_id'] != $db_subscription->stripe_plan_id
                ) {
            $stripeSubscription = $this->get_subscription($subscription_id);

            if ($update_values['tax_id'] != $db_subscription->tax_id) {
                if (empty($update_values['tax_id'])) {
                    $stripeSubscription->tax_percent = 0.00;
                } else {
                    $this->ci->load->model('taxes_model');
                    $tax                             = $this->ci->taxes_model->get($update_values['tax_id']);
                    $stripeSubscription->tax_percent = $tax->taxrate;
                }
            }

            // Causing issue when changin both plan/items and quantity
            if ($update_values['quantity'] != $db_subscription->quantity && $update_values['stripe_plan_id'] == $db_subscription->stripe_plan_id) {
                $stripeSubscription->quantity = $update_values['quantity'];
            }

            if ($update_values['stripe_plan_id'] != $db_subscription->stripe_plan_id) {
                $items = [
                            [
                                'id'   => $stripeSubscription->items->data[0]->id,
                                'plan' => $update_values['stripe_plan_id'],
                            ],
                         ];
                // If quantity is changed, update quantity too
                if ($update_values['quantity'] != $db_subscription->quantity) {
                    $items[0]['quantity'] = $update_values['quantity'];
                }
                $stripeSubscription->items = $items;
            }

            $stripeSubscription->prorate = $prorate;
            $stripeSubscription->save();
        }
    }

    public function subscribe($customer_id, $params = [])
    {
        if (isset($params['tax_percent']) && empty($params['tax_percent'])) {
            unset($params['tax_percent']);
        }

        return \Stripe\Subscription::create(array_merge([
            'customer' => $customer_id,
        ], $params));
    }

    /**
     * @todo
     * Perhaps when Paypal is added make is as contract? E.q. for preview invoice add new method and use this to insert the invoice in database
     */
    public function create_invoice($data) {

    }
}
