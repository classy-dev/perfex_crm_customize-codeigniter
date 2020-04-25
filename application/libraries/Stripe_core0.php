<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Stripe_core
{
    protected $ci;

    protected $secretKey;

    protected $publishableKey;

    protected $apiVersion = '2019-02-19';

    public function __construct()
    {
        $this->ci             = &get_instance();
        $this->secretKey      = $this->ci->stripe_gateway->decryptSetting('api_secret_key');
        $this->publishableKey = $this->ci->stripe_gateway->getSetting('api_publishable_key');

        \Stripe\Stripe::setApiVersion($this->apiVersion);
        \Stripe\Stripe::setApiKey($this->secretKey);
    }

    public function create_customer($data)
    {
        return \Stripe\Customer::create($data);
    }

    public function get_customer($id)
    {
        return \Stripe\Customer::retrieve($id);
    }

    public function update_customer_source($customer_id, $token)
    {
        \Stripe\Customer::update($customer_id, [
            'source' => $token,
        ]);
    }

    public function get_customer_with_default_source($id)
    {
        return \Stripe\Customer::retrieve(['id' => $id, 'expand' => ['default_source']]);
    }

    public function create_charge($data)
    {
        return \Stripe\Charge::create($data);
    }

    public function create_source($data)
    {
        return \Stripe\Source::create($data);
    }

    public function get_source($source)
    {
        return \Stripe\Source::retrieve($source);
    }

    public function get_publishable_key()
    {
        return $this->publishableKey;
    }

    public function retrieve_token($token_id)
    {
        return \Stripe\Token::retrieve($token_id);
    }

    public function has_api_key()
    {
        return $this->secretKey != '';
    }
}
