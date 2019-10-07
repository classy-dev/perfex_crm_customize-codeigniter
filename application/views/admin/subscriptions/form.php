<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php if(isset($subscription_error)) { ?>
<div class="alert alert-warning">
   <?php echo $subscription_error; ?>
</div>
<?php } ?>
<?php echo form_open('',array('id'=>'subscriptionForm','class'=>'_transaction_form')); ?>
<div class="row">
   <div class="col-md-12">
         <div class="bg-stripe mbot15" id="block_contain">
          <input type="hidden" name="block_array" id="block_array" value="<?php if(isset($subscription)) echo $subscription->block_array;?>">
      <?php
        if(isset($subscription) && !empty($subscription->stripe_subscription_id) && $subscription->status != 'canceled' && $subscription->status != 'future') { ?>
           <div class="checkbox checkbox-info hide" id="prorateWrapper">
                <input type="checkbox" id="prorate" class="ays-ignore" checked name="prorate">
                <label for="prorate"><a href="https://stripe.com/docs/billing/subscriptions/prorations" target="_blank"><i class="fa fa-link"></i></a> Prorate</label>
            </div>
        <?php } ?>
     </div>
      <?php $value = (isset($subscription) ? $subscription->name : ''); ?>
      <?php echo render_input('name','subscription_name',$value,'text',[],[],'','ays-ignore'); ?>
      <?php $value = (isset($subscription) ? $subscription->description : ''); ?>
      <?php echo render_textarea('description','subscriptions_description',$value,[],[],'','ays-ignore'); ?>
       <div class="form-group">
        <div class="checkbox checkbox-primary">
          <input type="checkbox" id="description_in_item" class="ays-ignore" name="description_in_item"<?php if(isset($subscription) && $subscription->description_in_item == '1'){echo ' checked';} ?>>
          <label for="description_in_item"><i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('description_in_invoice_item_help'); ?>"></i> <?php echo _l('description_in_invoice_item'); ?></label>
        </div>
       </div>
      <div class="form-group" app-field-wrapper="costs">
        <label for="costs" class="control-label">Monthly Subscription Costs</label> 
        <input type="text" id="costs" name="costs" class="form-control" value="<?php if(isset($subscription)) echo $subscription->monthly_costs;?>">
      </div>
        
      <div class="form-group select-placeholder">
         <label class="control-label" for="tax"><?php echo _l('tax'); ?></label>
         <select class="selectpicker" data-width="100%" name="tax_id" data-none-selected-text="<?php echo _l('no_tax'); ?>">
            <option value=""></option>
            <?php foreach($taxes as $tax){ ?>
            <option value="<?php echo $tax['id']; ?>" data-subtext="<?php echo $tax['name']; ?>"<?php if(isset($subscription) && $subscription->tax_id == $tax['id']){echo ' selected';} ?>><?php echo $tax['taxrate']; ?>%</option>
            <?php } ?>
         </select>
      </div>
   </div>
</div>
<?php if((isset($subscription) && has_permission('subscriptions','','edit')) || !isset($subscription)){ ?>
<div class="btn-bottom-toolbar text-right">
   <button type="submit" class="btn btn-info" data-loading-text="<?php echo _l('wait_text'); ?>" data-form="#subscriptionForm">
   <?php echo _l('save'); ?>
   </button>
</div>
<?php } ?>
<?php echo form_close(); ?>
