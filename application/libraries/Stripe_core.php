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
        #$this->secretKey      = $this->ci->stripe_gateway->decryptSetting('api_secret_key');
        #$this->publishableKey = $this->ci->stripe_gateway->getSetting('api_publishable_key');
        #$this->secretKey = "sk_test_UzrHS9xZEqOpS2TVJhyM4AO6006NFWacep";
       # $this->publishableKey = "pk_test_haHv6NQ9pF07I8T4c0An4kHP00ZFfx32YV";
        $this->publishableKey = "pk_test_KyRF1oNZFuhCEagkRJcaHYvN00cGjARMsR";
        $this->secretKey = "sk_test_I4rpv5ZlRQuhY1v85TQRFgy4000yvYS0H9";


        


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
    public function get_account_id($data)
    {
        return \Stripe\Account::create($data);
    }
    public function create_token($data)
    {
        return \Stripe\Token::create($data);
    }
    public function create_external_account($account_id,$external_account)
    {
        return \Stripe\Account::createExternalAccount($account_id,$external_account);
    }
    public function create_transfer($data)
    {
        return \Stripe\Transfer::create($data);
    }
    public function account_update($account_id,$data)
    {
        return \Stripe\Account::update($account_id,$data);
    }
    public function create_file_id($data){
        
        $file = \Stripe\File::create([
          'purpose' => 'identity_document',
          'file' => $data
        ]);
        return $file->id;

    }
}
