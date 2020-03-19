<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<style>
   [aria-controls="custom_fields"], [aria-controls="billing_and_shipping"]{
      display:none!important;
   }
   .input-group-select{
      display:none!important;
   }
</style>

<h4 class="customer-profile-group-heading"><?php echo _l('client_add_edit_profile'); ?></h4>
<div class="row">
   <?php echo form_open($this->uri->uri_string(),array('class'=>'client-form','autocomplete'=>'off')); ?>
   <div class="additional"></div>
   <div class="col-md-12">
      <div class="tab-content">
         <?php hooks()->do_action('after_custom_profile_tab_content',isset($client) ? $client : false); ?>
         <?php if($customer_custom_fields) { ?>
         <div role="tabpanel" class="tab-pane <?php if($this->input->get('tab') == 'custom_fields'){echo ' active';}; ?>" id="custom_fields">
            <?php $rel_id=( isset($client) ? $client->userid : false); ?>
            <?php echo render_custom_fields( 'customers',$rel_id); ?>
         </div>
         <?php } ?>
         <div role="tabpanel" class="tab-pane<?php if(!$this->input->get('tab')){echo ' active';}; ?>" id="contact_info">
            <div class="row">
               
               <div class="col-md-6">
                  <div class="form-group"><label for="" class="control-label"><?php echo _l('profile_title');?></label>
                    <?php //print_r($client) ?>
                    <div class="dropdown bootstrap-select form-control bs3">
                      <select data-fieldto="profile_title" data-fieldid="profile_title" name="profile_title" id="profile_title" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                        <option value=""></option>
                        <option value="company" <?php if(isset($client)&&$client->profile_title == 'company') echo "selected"?>><?php echo _l('client_profile_company')?></option>
                        <option value="mr" <?php if(isset($client)&&$client->profile_title == 'mr') echo "selected"?> ><?php echo _l('client_profile_mr')?></option>
                        <option value="miss" <?php if(isset($client)&&$client->profile_title == 'miss') echo "selected"?>><?php echo _l('client_profile_miss')?></option>
                        <option value="other" <?php if(isset($client)&&$client->profile_title == 'other') echo "selected"?>><?php echo _l('client_profile_other')?></option>
                        
                      </select>
                    </div>
                  </div>
                </div>
                <?php if(!isset($client)|| $client->profile_title != 'company') {?>
                  <div class="col-md-6" id="company"  style="display: none;">
                <?php }?>
                <?php if(isset($client)&& $client->profile_title == 'company') {?>
                  <div class="col-md-6" id="company">
                <?php }?>
                    <?php $value=( isset($client) ? $client->company : ''); ?>
                  <?php $attrs = (isset($client) ? array() : array('autofocus'=>true)); ?>
                  <?php echo render_input( 'company', 'client_company',$value,'text',$attrs); ?>

                  <?php $value=( isset($client) ? $client->company_form : ''); ?>
                  <?php echo render_input( 'company_form', 'client_company_form',$value); ?>

                  <?php $value=( isset($client) ? $client->company_address : ''); ?>
                  <?php echo render_textarea( 'company_address', 'client_company_address',$value); ?>

                  <?php $value=( isset($client) ? $client->company_email : ''); ?>
                  <?php echo render_input( 'company_email', 'client_company_email',$value,'email'); ?>
                  
                  <?php $value=( isset($client) ? $client->company_phonenumber : ''); ?>

                  <?php 
                   echo render_input( 'company_phonenumber', 'client_company_phonenumber',$value); ?>

                  <?php $value=( isset($client) ? $client->company_commercial_register_number : ''); ?>
                  <?php echo render_input( 'company_commercial_register_number', 'client_company_commercial_register_number',$value); ?>
               </div>
            
                <?php if(!isset($client)||$client->profile_title == 'company') {?>
                  <div class="col-md-6" id="person"  style="display: none;">
               <?php }?>
                <?php if(isset($client) && !empty($client->profile_title) && $client->profile_title != 'company') {?>
                  <div class="col-md-6" id="person">
                <?php }?>
                  <?php $value=( isset($client) ? $client->person_firstname : ''); ?>
                  <?php echo render_input( 'person_firstname', 'client_person_firstname',$value); ?>

                  <?php $value=( isset($client) ? $client->person_lastname : ''); ?>
                  <?php echo render_input( 'person_lastname', 'client_person_lastname',$value); ?>

                  <?php $value=( isset($client) ? $client->person_street : ''); ?>
                  <?php echo render_input( 'person_street', 'client_person_street',$value); ?>

                  <?php $value=( isset($client) ? $client->person_city : ''); ?>
                  <?php echo render_input( 'person_city', 'client_person_city',$value); ?>

                  <?php $countries= get_all_countries();
                      // print_r($countries);
                     $customer_default_country = get_option('customer_default_country');
                     // print_r($customer_default_country);
                     $selected =( isset($client) ? $client->country : $customer_default_country);
                     echo render_select( 'country',$countries,array( 'country_id',array( 'short_name')), 'clients_country',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                     ?>
                  <?php $value=( isset($client) ? $client->person_email : ''); ?>
                  <?php echo render_input( 'person_email', 'client_person_email',$value); ?>  
               </div>
               
            </div>

         </div>
      </div>
   </div>
   <?php echo form_close(); ?>
</div>
<?php if(isset($client)){ ?>
<?php if (has_permission('customers', '', 'create') || has_permission('customers', '', 'edit')) { ?>
<div class="modal fade" id="customer_admins_assign" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <?php echo form_open(admin_url('clients/assign_admins/'.$client->userid)); ?>
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title"><?php echo _l('assign_admin'); ?></h4>
         </div>
         <div class="modal-body">
            <?php
               $selected = array();
               foreach($customer_admins as $c_admin){
                  array_push($selected,$c_admin['staff_id']);
               }
               echo render_select('customer_admins[]',$staff,array('staffid',array('firstname','lastname')),'',$selected,array('multiple'=>true),array(),'','',false); ?>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
            <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
         </div>
      </div>
      <!-- /.modal-content -->
      <?php echo form_close(); ?>
   </div>
   <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php } ?>
<?php } ?>
<?php $this->load->view('admin/clients/client_group'); ?>