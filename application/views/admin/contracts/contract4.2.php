<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php   //print_r($customer_id); exit();
init_head(); ?>
<style>
   .form-group[app-field-wrapper=subject], #contractmergefields, #tasks, #renewals{
      /*display:none!important;*/
   }
   /*/*slider**/
   .slidecontainer {
     width: 40%;
   }

   .slider {
     -webkit-appearance: none;
     width: 100%;
     height: 25px;
     background: #d3d3d3;
     outline: none;
     opacity: 0.7;
     -webkit-transition: .2s;
     transition: opacity .2s;
   }

   .slider:hover {
     opacity: 1;
   }

   .slider::-webkit-slider-thumb {
     -webkit-appearance: none;
     appearance: none;
     width: 25px;
     height: 25px;
     background: #4CAF50;
     cursor: pointer;
   }

   .slider::-moz-range-thumb {
     width: 25px;
     height: 25px;
     background: #4CAF50;
     cursor: pointer;
   }
   .range-control {
      position: relative;
    }

    input[type=range] {
      display: block;
      width: 100%;
      margin: 0;
      -webkit-appearance: none;
      outline: none;
    }

    input[type=range]::-webkit-slider-runnable-track {
      position: relative;
      height: 12px;
      border: 1px solid #b2b2b2;
      border-radius: 5px;
      background-color: #e2e2e2;
      box-shadow: inset 0 1px 2px 0 rgba(0, 0, 0, 0.1);
    }

    input[type=range]::-webkit-slider-thumb {
      position: relative;
      top: -5px;
      width: 20px;
      height: 20px;
      border: 1px solid #999;
      -webkit-appearance: none;
      background-color: #fff;
      box-shadow: inset 0 -1px 2px 0 rgba(0, 0, 0, 0.25);
      border-radius: 100%;
      cursor: pointer;
    }

    output {
      position: absolute;
      top: -10px;
      display: none;
      width: 50px;
      height: 32px;
      border: 1px solid #e2e2e2;
      background-color: #fff;
      border-radius: 3px;
      color: #777;
      font-size: 1.2em;
      line-height: 24px;
      text-align: center;
    }

    input[type=range]:active + output {
      display: block;
      transform: translateX(-50%);
    }
    p {
      margin-bottom: 0px;
    }
   
</style>

<div id="wrapper">
   <div class="content">
      <div class="row">
         <div class="col-md-5 left-column" id="left-column">
            <div class="panel_s">
               <div class="panel-body">
                  <!-- check box -->
                  <?php echo form_open($this->uri->uri_string(),array('id'=>'contract-form')); ?>
                  <div class="form-group">
                     <div class="checkbox checkbox-primary no-mtop checkbox-inline">
                        <input type="checkbox" id="trash" name="trash"<?php if(isset($contract->trash)){if($contract->trash == 1){echo ' checked';}}; ?>>
                        <label for="trash"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('contract_trash_tooltip'); ?>" ></i> <?php echo _l('contract_trash'); ?></label>
                     </div>
                     <div class="checkbox checkbox-primary checkbox-inline">
                        <input type="checkbox" name="not_visible_to_client" id="not_visible_to_client" <?php if(isset($contract->not_visible_to_client)){if($contract->not_visible_to_client == 1){echo 'checked';}}; ?>>
                        <label for="not_visible_to_client"><?php echo _l('contract_not_visible_to_client'); ?></label>
                     </div>
                  </div>

                  <!-- hidden value -->
                  <input type="hidden" id="staf_name" name="staff_name" value="<?php if(isset($contract->staff_name)) echo $contract->staff_name; else echo "";?>">
                  <input type="hidden" id="staf_info" name="staff_info" value="<?php if(isset($contract->staff_info)) echo $contractcontract->staff_info; else echo "";?>">

                  <!-- subject -->
                   <?php $value = (isset($contract->subject) ? $contract->subject : ''); ?>
                  <i class="fa fa-question-circle pull-left" data-toggle="tooltip" title="<?php echo _l('contract_subject_tooltip'); ?>"></i>
                  <div class="form-group" app-field-wrapper="subject">
                    <label for="subject" class="control-label"><?php echo _l('contract_subject')?></label>
                    <input type="text" id="subject" name="subject" class="form-control" value="<?php echo $value?>" required>
                  </div>

                  <!-- customer -->
                  <!-- div class="form-group select-placeholder">
                     <label for="clientid" class="control-label"><span class="text-danger">* </span><?php echo _l('contract_client_string'); ?></label>
                     <select id="clientid" name="client" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" class="common_for_timetracking" >
                     <?php $selected = (isset($contract->client) ? $contract->client : '');
                
                        if($selected == ''){

                         $selected = (isset($customer_id) ? $customer_id: '');
                        }
                        if($selected != ''){

                        $rel_data = get_relation_data('customer',$selected);
                        $rel_val = get_relation_values($rel_data,'customer');

                        echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['name'].'</option>';
                        } ?>
                     </select>
                     <input type="hidden" id="cus_value" name="cus_value" value="<?php if(isset($contract->cus_value))  print_r($contract->cus_value); else echo "";?>">
                     <input type="hidden" id="cus_addr_value" name="cus_addr_value" value="<?php if(isset($contract->cus_addr_value))  print_r($contract->cus_addr_value); else echo "";?>">
                  </div> -->
                  <!-- customer -->
                  <?php
                    $selected = (isset($contract->client) ? $contract->client : '');
                    if(isset($my_customers)){
                        
                       echo render_select('client',$my_customers,array('userid','fullname'),'contract_client_string',$selected);
                       
                     }
                     ?>
                    <input type="hidden" id="cus_value" name="cus_value" value="<?php if(isset($contract->cus_value))  print_r($contract->cus_value); else echo "";?>">
                    <input type="hidden" id="cus_addr_value" name="cus_addr_value" value="<?php if(isset($contract->cus_addr_value))  print_r($contract->cus_addr_value); else echo "";?>">

                  <!-- contract type -->
                  <?php
      
                     $selected = (isset($contract->contract_type) ? $contract->contract_type : '');
                     if(isset($selected) && !empty($selected)){
                        $contractDetails = $this->db->select('details')->where('id', $selected)->get('tblcontracts_types')->row('details');
                        if(isset($contract->client))
                          $clientDetails = $this->db->select('company, address')->where('userid', $contract->client)->get('tblclients')->row_array();
                        if (isset($contract->addedfrom))
                          $agentDetails = $this->db->select('firstname, lastname')->where('staffid', $contract->addedfrom)->get('tblstaff')->row_array();
                        // if(isset($contract->agentDetails))
                        // {
                        //     $placeholders = array('customer' => $clientDetails['company'], 'customer_address' => $clientDetails['address'], 'agent' => $agentDetails['firstname'].' '.$agentDetails['lastname'], 'agent_address' => '', 'contract_value' => $contract->contract_value );

                        //     foreach($placeholders as $key => $value){
                        //        $contractDetails = str_replace('{'.$key.'}', $value, $contractDetails);
                        //     }
                        // }
                        
                     }
                     if(isset($types)){
                        if(is_admin() || get_option('staff_members_create_inline_contract_types') == '1'){
                        echo render_select_with_input_group('contract_type',$types,array('id','name'),'contract_type',$selected,'<a href="#" onclick="new_type();return false;"><i class="fa fa-plus"></i></a>');
                       } else {
                        // echo $selected;
                       echo render_select('contract_type',$types,array('id','name'),'contract_type',$selected);
                       }
                     }
                     
                  ?>

                  <!-- Date -->
                  <div class="row">
                     <div class="col-md-6">
                        <?php $value = (isset($contract->datestart) ? _d($contract->datestart) : _d(date('Y-m-d'))); ?>
                        <?php echo render_date_input('datestart','contract_start_date',$value); ?>
                        
                     </div>
                     <!-- <div class="col-md-6">
                        <?php $value = (isset($contract->dateend) ? _d($contract->dateend) : ''); ?>
                        <?php echo render_date_input('dateend','contract_end_date',$value); ?>
                        
                     </div> -->
                  </div>

                  <?php
                    $selected = (isset($contract->subscription) ? $contract->subscription : '');
                    
                    if(is_admin() || get_option('staff_members_create_inline_subscriptions') == '1'){
                        if(!isset($contract->subscription)||$contract->contract_type!=2) echo '<div id="subscrip" style="display:none">';
                        if(isset($contract->subscription)&&$contract->contract_type==2) echo '<div id="subscrip">';
                            echo render_select_with_input_group('subscription',$subscriptions,array('id','name'),_l('contract_subscription'),$selected,'<a href="'.admin_url('subscriptions/create').'"><i class="fa fa-plus"></i></a>');
                            echo "</div>";
                        
                      } 
                    else {
                        if(!isset($contract->subscription)||$contract->contract_type!=2) echo '<div id="subscrip" style="display:none">';
                        if(isset($contract->subscription)&&$contract->contract_type==2) echo '<div id="subscrip">';
                        echo render_select('subscription',$subscriptions,array('id','name'),'Subscription',$selected);
                        echo "</div>";
                       }
                  ?>

                  <!-- Consulting  -->
                  <?php if(!isset($contract->contract_type)||($contract->contract_type!=1)) { ?> <div id="consulting" style="display: none"><?php } ?>
                  <?php if(isset($contract->contract_type)&&($contract->contract_type==1)) { ?><div id="consulting"><?php } ?>
                    <?php $value = (isset($contract->consulting_client_point) ? $contract->consulting_client_point : ''); ?>
                   
                    <?php
                    $selected = (isset($contract->consulting_client_point) ? $contract->consulting_client_point : '');
                    if(isset($products)){
                        if(is_admin()){
                        echo render_select_with_input_group('consulting_client_point',$products,array('id','contract_product'),'consulting_client_point',$selected,'<a href="#" onclick="new_product();return false;"><i class="fa fa-plus"></i></a>&nbsp;<a href="#" onclick="delete_product()"><i class="fa fa-minus"></i></a>');
                       } else {
                        // echo $selected;
                       echo render_select('consulting_client_point',$products,array('id','contract_product'),'consulting_client_point',$selected);
                       }
                     }
                     ?>
                     
                    </div> 

                  <!--Servicegebührenvereinbarung Payment -->
                  <?php if(!isset($contract->contract_type)||$contract->contract_type!=2) { ?> <div id="contract_ser" style="display: none;"><?php } ?>
                  <?php if(isset($contract->contract_type)&&$contract->contract_type==2) { ?><div id="contract_ser"><?php } ?>
                    <div class="row custom-fields-form-row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="custom_fields[contracts_ser][method]" class="control-label" style="margin-bottom:9px;"><?php echo _l('payment_method');?></label>
                          <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="contracts_ser" data-fieldid="method" name="custom_fields[contracts_ser][method]" id="custom_fields_contracts_ser_method" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                              <option <?php if($contract->service_p_m == 'Debit') echo 'selected';?> value="Debit"><?php echo _l('debit');?></option>
                              <option <?php if($contract->service_p_m == 'Bank Transfer') echo 'selected';?> value="Bank Transfer"><?php echo _l('bank_transfer');?></option>
                              <option <?php if($contract->service_p_m == 'Immediate Transfer') echo 'selected';?> value="Immediate Transfer"><?php echo _l('immediate_transfer');?></option>
                              
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group"><label for="custom_fields[contracts_ser][timeframe]" class="control-label" style="margin-bottom:9px;"><?php echo _l('payment_timeframe');?></label>
                          <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="contracts_ser" data-fieldid="timeframe" name="custom_fields[contracts_ser][timeframe]" id="custom_fields_contracts_ser_timeframe" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                              
                              <!-- <option <?php //if($contract0['session']['custom_fields']['contracts_ser']['13'] == 'Daily') echo 'selected';?> value="Daily"><?php //echo _l('daily');?></option> -->
                              <option <?php if($contract->service_p_t == 'Annually') echo 'selected';?> value="Annually"><?php echo _l('annually');?></option>
                              <option <?php if($contract->service_p_t == 'Monthly') echo 'selected';?> value="Monthly"><?php echo _l('monthly');?></option>
                              <option <?php if($contract->service_p_t == 'Quarterly') echo 'selected';?> value="Quarterly"><?php echo _l('Quarterly');?></option>
                              <option <?php if($contract->service_p_t == 'Half-Yearly') echo 'selected';?> value="Half-Yearly"><?php echo _l('half_yearly');?></option>
                              
                            </select>
                          </div>
                        </div>
                      </div>
                     </div>
                  </div>
                  <input type="hidden" name="sub_arr" id="sub_arr" value="<?php if(isset($contract->sub_arr))  print_r($contract->sub_arr); else echo "";?>">
                  <!-- <input type="hidden" name="sub_tax" id="sub_tax" value="<?php if(isset($contract->sub_tax))  print_r($contract->sub_tax); else echo "";?>"> -->

                  <!-- Vergütungsvereinbarung Beratung Payment -->
                  <?php if(!isset($contract->contract_type)||$contract->contract_type!=3) { ?> <div id="contracts_beratung" style="display: none;"><?php } ?>
                  <?php if(isset($contract->contract_type)&&$contract->contract_type==3) { ?><div id="contracts_beratung"><?php } ?>
                    <!-- Remuneration -->
                    <div class="row custom-fields-form-row">
                      <div class="col-md-6">
                        <div class="form-group"><label for="custom_fields[contracts_beratung][remuneration]" class="control-label" style="margin-bottom:9px;"><?php echo _l('remuneration');?></label>
                          <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="contracts_beratung" data-fieldid="remuneration" name="custom_fields[contracts_beratung][remuneration]" id="custom_fields_contracts_beratung_remuneration" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                              <option value=""></option>
                              <option <?php if($contract->beratung_remuneration == 'One Time Payment') echo 'selected';?> value="One Time Payment"><?php echo _l('one_time_payment');?></option>
                              <option <?php if($contract->beratung_remuneration == 'Payment According To Time Spent') echo 'selected';?> value="Payment According To Time Spent"><?php echo _l('payment_according_to_time_spent');?></option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="custom_fields[contracts_beratung][method]" class="control-label" style="margin-bottom:9px;"><?php echo _l('payment_method');?></label>
                          <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="contracts_beratung" data-fieldid="method" name="custom_fields[contracts_beratung][method]" id="custom_fields_contracts_beratung_method" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                              <option value=""></option>
                              <option <?php if($contract->beratung_p_m == 'Bank Transfer') echo 'selected';?> value="Bank Transfer"><?php echo _l('bank_transfer');?></option>
                              <option <?php if($contract->beratung_p_m == 'Immediate Transfer') echo 'selected';?> value="Immediate Transfer"><?php echo _l('immediate_transfer');?></option>
                              <option <?php if($contract->beratung_p_m == 'Debit') echo 'selected';?> value="Debit"><?php echo _l('debit');?></option>
                            </select>
                          </div>
                        </div>
                      </div>

                    </div>

                    <!-- Calculation Value -->
                    <?php if (!isset($contract)||($contract->beratung_remuneration != 'One Time Payment') ){?>
                    <div id="beratung_remuneration_one" style="display: none;">
                    <?php }?>
                    <?php if(isset($contract)&&($contract->beratung_remuneration == 'One Time Payment')){?>
                    <div id="beratung_remuneration_one">
                    <?php }?>
                      <div class="row custom-fields-form-row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="beratung_customer_payment_value_excl_tax"><?php echo _l('beratung_customer_payment_value_excl_tax'); ?></label>
                            <div class="input-group" data-toggle="tooltip" title="<?php echo _l('beratung_customer_payment_value_excl_tax'); ?>">
                              <input type="number" class="form-control beratung_calc_values_one" name="beratung_customer_payment_value_excl_tax" id="beratung_customer_payment_value_excl_tax" value="<?php if(isset($contract->beratung_customer_payment_value_excl_tax)){echo $contract->beratung_customer_payment_value_excl_tax; }?>">

                              <div class="input-group-addon">
                                 <?php echo $base_currency->symbol; ?>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">
                          <div class="form-group select-placeholder">
                             <label class="control-label" for="tax"><?php echo _l('tax'); ?></label>
                             <select class="selectpicker" data-width="100%" name="tax_id" data-none-selected-text="<?php echo _l('no_tax'); ?>">
                                <?php if(!isset($contract)) foreach($taxes as $tax) {?>
                                <option value="<?php echo $tax['taxrate']; ?>" data-subtext="<?php echo $tax['name']; ?>"<?php if($tax['id'] == 1){echo ' selected';} ?>><?php echo $tax['taxrate']; ?>%</option>
                                <?php }?>

                                <?php if(isset($contract)) foreach($taxes as $tax){ ?>
                                <option value="<?php echo $tax['taxrate']; ?>" data-subtext="<?php echo $tax['name']; ?>"<?php if(isset($subscription) && $subscription->tax_id == $tax['id']){echo ' selected';} ?>><?php echo $tax['taxrate']; ?>%</option>
                                <?php } ?>

                             </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  
                   <!-- <?php if (!isset($contract->beratung_remuneration)||$contract->beratung_remuneration == 'Payment According To Time Spent'){?>
                    <div class="row custom-fields-form-row" id="beratung_remuneration" style="display: none;"><?php }?>
                    <?php if (isset($contract->beratung_remuneration)&&($contract->beratung_remuneration == 'One Time Payment')){?>
                    <div class="row custom-fields-form-row" id="beratung_remuneration"><?php }?> -->
                    
                    <div class="row custom-fields-form-row" id="beratung_remuneration" style="display: none;">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="beratung_one_time_payment_value"><?php echo _l('beratung_one_time_payment_value'); ?></label>
                          <div class="input-group" data-toggle="tooltip" title="<?php echo _l('beratung_one_time_payment_value'); ?>">
                            <input type="number" class="form-control beratung_calc_values_one" name="beratung_one_time_payment_value" id="beratung_one_time_payment_value" value="<?php if(isset($contract->beratung_one_time_payment_value)){echo $contract->beratung_one_time_payment_value; }?>">

                            <div class="input-group-addon">
                               <?php echo $base_currency->symbol; ?>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="beratung_opening_payment_value"><?php echo _l('beratung_opening_payment_value'); ?></label>
                          <div class="input-group" data-toggle="tooltip" title="<?php echo _l('beratung_opening_payment_value'); ?>">
                            <input type="number" class="form-control beratung_calc_values_one" name="beratung_opening_payment_value" id="beratung_opening_payment_value" value="<?php if(isset($contract->beratung_opening_payment_value)){echo $contract->beratung_opening_payment_value; }?>">

                            <div class="input-group-addon">
                               <?php echo $base_currency->symbol; ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Timeframe and Method -->
                    <!-- <div class="row custom-fields-form-row">

                      <div class="col-md-6">
                        <div class="form-group"><label for="custom_fields[contracts_beratung][timeframe]" class="control-label" style="margin-bottom:9px;"><?php echo _l('payment_timeframe');?></label>
                          <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="contracts_beratung" data-fieldid="timeframe" name="custom_fields[contracts_beratung][timeframe]" id="custom_fields_contracts_beratung_timeframe" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                              <option value=""></option>
                              <option <?php if($contract->beratung_p_t == 'Monthly') echo 'selected';?> value="Monthly"><?php echo _l('monthly');?></option>
                              <option <?php if($contract->beratung_p_t == 'Quarterly') echo 'selected';?> value="Quarterly"><?php echo _l('Quarterly');?></option>
                              <option <?php if($contract->beratung_p_t == 'Half-Yearly') echo 'selected';?> value="Half-Yearly"><?php echo _l('half_yearly');?></option>
                              <option <?php if($contract->beratung_p_t == 'Annually') echo 'selected';?> value="Annually"><?php echo _l('annually');?></option>
                            </select>
                          </div>
                        </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="custom_fields[contracts_beratung][method]" class="control-label" style="margin-bottom:9px;"><?php echo _l('payment_method');?></label>
                          <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="contracts_beratung" data-fieldid="method" name="custom_fields[contracts_beratung][method]" id="custom_fields_contracts_beratung_method" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                              <option value=""></option>
                              <option <?php if($contract->beratung_p_m == 'Bank Transfer') echo 'selected';?> value="Bank Transfer"><?php echo _l('bank_transfer');?></option>
                              <option <?php if($contract->beratung_p_m == 'Immediate Transfer') echo 'selected';?> value="Immediate Transfer"><?php echo _l('immediate_transfer');?></option>
                              <option <?php if($contract->beratung_p_m == 'Debit') echo 'selected';?> value="Debit"><?php echo _l('debit');?></option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div> -->

                  </div>

                  <!-- Nettoprodukt -->
                  <?php if(!isset($contract->contract_type)||$contract->contract_type!=1) { ?> <div id="contracts_produkt" style="display: none;"><?php } ?>
                  <?php if(isset($contract->contract_type)&&$contract->contract_type==1) { ?><div id="contracts_produkt"><?php } ?>
                    <div class="row custom-fields-form-row">
                      <div class="col-md-6" id="remuneration">
                        <div class="form-group"><label for="custom_fields[contracts_produkt][remuneration]" class="control-label" style="margin-bottom:9px;"><?php echo _l('remuneration');?></label>
                          <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="contracts_produkt" data-fieldid="remuneration" name="custom_fields[contracts_produkt][remuneration]" id="custom_fields_contracts_produkt_remuneration" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                              <option value=""></option>
                              <option <?php if($contract->produkt_remuneration == 'One Time Payment') echo 'selected';?> value="One Time Payment"><?php echo _l('one_time_payment');?></option>
                              <option <?php if($contract->produkt_remuneration == 'Partial Payment Of Total Amount') echo 'selected';?> value="Partial Payment Of Total Amount"><?php echo _l('partial_payment_of_total_payment');?></option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row custom-fields-form-row">
                      <!-- one time payment -->
                      <?php if(!isset($contract->produkt_remuneration)||($contract->produkt_remuneration!='One Time Payment')) { ?> <div class="col-md-6" id="one_time_payment" style="display: none;"><?php } ?>
                      <?php if(isset($contract->produkt_remuneration)&&($contract->produkt_remuneration =='One Time Payment')) { ?><div class="col-md-6" id="one_time_payment" ><?php } ?>
                        <div class="form-group">
                          <label for="produkt_one_time_payment_value"><?php echo _l('produkt_one_time_payment_value'); ?></label>
                          <div class="input-group" data-toggle="tooltip" title="<?php echo _l('produkt_one_time_payment_value'); ?>">
                            <input type="number" class="form-control" name="produkt_one_time_payment_value" id="produkt_one_time_payment_value" value="<?php if(isset($contract->produkt_one_time_payment_value)){echo $contract->produkt_one_time_payment_value; }?>">

                            <div class="input-group-addon">
                               <?php echo $base_currency->symbol; ?>
                            </div>
                          </div>
                        </div>
                      </div>

                      <?php if(!isset($contract->produkt_remuneration)||($contract->produkt_remuneration =='One Time Payment'||$contract->produkt_remuneration =='')) { ?> <div class="col-md-6" id="savings_amount_per_month" style="display: none;"><?php } ?>
                      <?php if(isset($contract->produkt_remuneration)&&($contract->produkt_remuneration !='One Time Payment'&&$contract->produkt_remuneration !='')) { ?><div class="col-md-6" id="savings_amount_per_month"><?php } ?>
                        <div class="form-group">
                          <label for="savings_amount_per_month_value"><?php echo _l('savings_amount_per_month_value'); ?></label>
                          <div class="input-group" data-toggle="tooltip" title="<?php echo _l('savings_amount_per_month_value'); ?>">
                            <input type="number" class="form-control produkt_calc_values" name="savings_amount_per_month_value" id="savings_amount_per_month_value" value="<?php if(isset($contract->savings_amount_per_month_value)){echo $contract->savings_amount_per_month_value; }?>">
                            <div class="input-group-addon">
                               <?php echo $base_currency->symbol; ?>
                            </div>
                          </div>
                        </div>
                      </div>

                      <?php if(!isset($contract->produkt_remuneration)||($contract->produkt_remuneration =='One Time Payment'||$contract->produkt_remuneration =='')) { ?> <div class="col-md-6" id="term" style="display: none;"><?php } ?>
                      <?php if(isset($contract->produkt_remuneration)&&($contract->produkt_remuneration !='One Time Payment'&&$contract->produkt_remuneration !='')) { ?><div class="col-md-6" id="term"><?php } ?>
                        <div class="form-group">
                          <label for="term_value"><?php echo _l('term_value'); ?></label>
                          <input type="number" class="form-control produkt_calc_values" name="term_value" id="term_value" value="<?php if(isset($contract->term_value)){echo $contract->term_value; }?>">
                        </div>
                      </div>

                      <?php if(!isset($contract->produkt_remuneration)||($contract->produkt_remuneration =='One Time Payment'||$contract->produkt_remuneration =='')) { ?> <div class="col-md-12" id="amount" style="display: none;"><?php } ?>
                      <?php if(isset($contract->produkt_remuneration)&&($contract->produkt_remuneration !='One Time Payment'&&$contract->produkt_remuneration !='')) { ?><div class="col-md-12" id="amount"><?php } ?>
                        <div class="form-group">
                          <label for="amount_value"><?php echo _l('amount_value'); ?></label>
                          <div class="input-group" data-toggle="tooltip" title="<?php echo _l('amount_value'); ?>">
                            <input type="number" class="form-control produkt_calc_values" name="amount_value" id="amount_value" value="<?php if(isset($contract->amount_value)){echo $contract->amount_value; }?>" readonly>
                            <div class="input-group-addon">
                               <?php echo $base_currency->symbol; ?>
                            </div>
                          </div>
                        </div>
                      </div>

                      <?php if(!isset($contract->produkt_remuneration)||($contract->produkt_remuneration =='One Time Payment'||$contract->produkt_remuneration =='')) { ?> <div class="col-md-6" id="opening_payment" style="display: none;"><?php } ?>
                      <?php if(isset($contract->produkt_remuneration)&&($contract->produkt_remuneration !='One Time Payment'&&$contract->produkt_remuneration !='')) { ?><div class="col-md-6" id="opening_payment" ><?php } ?>
                        <div class="form-group">
                          <div class="checkbox checkbox-primary no-mtop checkbox-inline">
                            <input type="checkbox" id="opening_payment_check" name="opening_payment_check"<?php if(isset($contract->opening_payment_check)){if($contract->opening_payment_check == 1){echo ' checked';}}; ?>>
                            <label for="produkt_opening_payment_value"><?php echo _l('produkt_opening_payment_value'); ?></label>
                          </div>


                          <?php if(!isset($contract->opening_payment_check)||($contract->opening_payment_check ==0)) { ?><div id="opening_hidden" style="display: none;"><?php }?>
                          <?php if(isset($contract->opening_payment_check)&&($contract->opening_payment_check == 1)) { ?><div id="opening_hidden"><?php }?>
                            <div class="input-group" data-toggle="tooltip" title="<?php echo _l('produkt_opening_payment_value'); ?>">
                              <input type="number" class="form-control produkt_calc_values" name="produkt_opening_payment_value" id="produkt_opening_payment_value" value="<?php if(isset($contract->produkt_opening_payment_value)){echo $contract->produkt_opening_payment_value; }?>">
                              <div class="input-group-addon">
                                 <?php echo $base_currency->symbol; ?>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <?php if(!isset($contract->produkt_remuneration)||($contract->produkt_remuneration =='One Time Payment'||$contract->produkt_remuneration =='')) { ?> <div class="col-md-6" id="dynamic_percentage_per_year" style="display: none;"><?php } ?>
                      <?php if(isset($contract->produkt_remuneration)&&($contract->produkt_remuneration !='One Time Payment'&&$contract->produkt_remuneration !='')) { ?><div class="col-md-6" id="dynamic_percentage_per_year"><?php } ?>
                        <div class="range-control ">
                        <!-- <div class="form-group"> -->
                          <div class="checkbox checkbox-primary no-mtop checkbox-inline">
                            <input type="checkbox" id="dynamic_percent_check" name="dynamic_percent_check"<?php if(isset($contract->dynamic_percent_check)){if($contract->dynamic_percent_check == 1){echo ' checked';}}; ?>>
                            <label for="dynamic_percentage_per_year_value"><?php echo _l('dynamic_percentage_per_year_value'); ?>&nbsp;&nbsp;
                              <?php if(!isset($contract->dynamic_percent_check)||($contract->dynamic_percent_check ==0)) { ?><div id="dynamic_value_hidden" style="display: none;"><?php }?>
                              <?php if(isset($contract->dynamic_percent_check)&&($contract->dynamic_percent_check == 1)) { ?><div id="dynamic_value_hidden"><?php }?>
                                <span id="display_percent"><?php if(isset($contract->dynamic_percentage_per_year_value)) echo $contract->dynamic_percentage_per_year_value ?></span><span>%</span>
                              </div>
                            </label>
                          </div>

                          <?php if(!isset($contract->dynamic_percent_check)||($contract->dynamic_percent_check ==0)) { ?><div id="dynamic_hidden" style="display: none;"><?php }?>
                          <?php if(isset($contract->dynamic_percent_check)&&($contract->dynamic_percent_check == 1)) { ?><div id="dynamic_hidden"><?php }?>
                            <input id="dynamic_percentage_per_year_value" class="produkt_calc_values" name="dynamic_percentage_per_year_value" type="range" min="5" max="20" step="1" value="<?php if(isset($contract->dynamic_percentage_per_year_value)) echo $contract->dynamic_percentage_per_year_value; else echo 0;?>" data-thumbwidth="20" style="margin-top: 2%;margin-bottom: 2%">
                            <output name="rangeVal"><?php if(isset($contract->dynamic_percentage_per_year_value)) echo $contract->dynamic_percentage_per_year_value; else echo 0;?></output>
                            <span style="float: left;">5%</span>
                            <span style="float: right;">20%</span>
                          </div>
                        </div>
                      </div>

                      <?php if(!isset($contract->produkt_remuneration)||($contract->produkt_remuneration =='One Time Payment'||$contract->produkt_remuneration =='')) { ?> <div class="col-md-12" id="total_amount" style="display: none;"><?php } ?>
                      <?php if(isset($contract->produkt_remuneration)&&($contract->produkt_remuneration !='One Time Payment'&&$contract->produkt_remuneration !='')) { ?><div class="col-md-12" id="total_amount"><?php } ?>
                        <div class="form-group">
                          <label for="total_amount_value"><?php echo _l('total_amount_value'); ?></label>
                          <div class="input-group" data-toggle="tooltip" title="<?php echo _l('total_amount_value'); ?>">
                            <input type="number" class="form-control" name="total_amount_value" id="total_amount_value" value="<?php if(isset($contract)){echo $contract->total_amount_value; }?>" readonly>
                            <div class="input-group-addon">
                               <?php echo $base_currency->symbol; ?>
                            </div>
                          </div>
                        </div>
                      </div>

                      <?php if(!isset($contract->produkt_remuneration)||($contract->produkt_remuneration =='One Time Payment'||$contract->produkt_remuneration =='')) { ?> <div class="col-md-6" id="agent_remuneration_percent" style="display: none;"><?php } ?>
                      <?php if(isset($contract->produkt_remuneration)&&($contract->produkt_remuneration !='One Time Payment'&&$contract->produkt_remuneration !='')) { ?><div class="col-md-6" id="agent_remuneration_percent"><?php } ?>
                        <div class="form-group">
                          <label for="agent_remuneration_percent_value"><?php echo _l('agent_remuneration_percent_value'); ?></label>
                          <input type="number" class="form-control produkt_calc_values" name="agent_remuneration_percent_value" id="agent_remuneration_percent_value" value="<?php if(isset($contract)){echo $contract->agent_remuneration_percent_value; }?>">
                        </div>
                      </div>

                      <?php if(!isset($contract->produkt_remuneration)||($contract->produkt_remuneration =='One Time Payment'||$contract->produkt_remuneration =='')) { ?> <div class="col-md-6" id="agent_remuneration_price" style="display: none;"><?php } ?>
                      <?php if(isset($contract->produkt_remuneration)&&($contract->produkt_remuneration !='One Time Payment'&&$contract->produkt_remuneration !='')) { ?><div class="col-md-6" id="agent_remuneration_price"><?php } ?>
                        <div class="form-group">
                          <label for="agent_remuneration_price_value"><?php echo _l('agent_remuneration_price_value'); ?></label>
                          <div class="input-group" data-toggle="tooltip" title="<?php echo _l('agent_remuneration_price_value'); ?>">
                            <input type="number" class="form-control" name="agent_remuneration_price_value" id="agent_remuneration_price_value" value="<?php if(isset($contract)){echo $contract->agent_remuneration_price_value; }?>" readonly>
                            <div class="input-group-addon">
                               <?php echo $base_currency->symbol; ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row custom-fields-form-row">
                      <div class="col-md-6" id="payment">
                        <div class="form-group"><label for="custom_fields[contracts_produkt][payment]" class="control-label" style="margin-bottom:9px;"><?php echo _l('payment');?></label>
                          <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="contracts_produkt" data-fieldid="payment" name="custom_fields[contracts_produkt][payment]" id="custom_fields_contracts_produkt_payment" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                              <option value=""></option>
                              <option <?php if($contract->produkt_p == 'One Time Payment') echo 'selected';?> value="One Time Payment"><?php echo _l('one_time_payment');?></option>
                              <option <?php if($contract->produkt_p == 'Partial Payment') echo 'selected';?> value="Partial Payment"><?php echo _l('partial_payment');?></option>
                              <option <?php if($contract->produkt_p == 'Partial Payment With Increased Starting Payment') echo 'selected';?> value="Partial Payment With Increased Starting Payment"><?php echo _l('partial_payment_with_increased_starting_payment');?></option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6" >
                        <div class="form-group">
                          <label for="custom_fields[contracts_produkt][method]" class="control-label" style="margin-bottom:9px;"><?php echo _l('payment_method');?></label>
                          <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="contracts_produkt" data-fieldid="method" name="custom_fields[contracts_produkt][method]" id="custom_fields_contracts_produkt_method" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                              <option value=""></option>
                              <option <?php if($contract->produkt_p_m == 'Bank Transfer') echo 'selected';?> value="Bank Transfer"><?php echo _l('bank_transfer');?></option>
                              <option <?php if($contract->produkt_p_m == 'Immediate Transfer') echo 'selected';?> value="Immediate Transfer"><?php echo _l('immediate_transfer');?></option>
                              <option <?php if($contract->produkt_p_m == 'Debit') echo 'selected';?> value="Debit"><?php echo _l('debit');?></option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <?php if(isset($contract)&&(empty($contract->produkt_p_t))){
                       ?>
                      <div class="col-md-6" id="timeframe" style="display: none;">
                      <?php }?>
                      <?php if(!isset($contract)||(!empty($contract->produkt_p_t))){
                       ?>
                      <div class="col-md-6" id="timeframe"><?php }?>
                        <div class="form-group"><label for="custom_fields[contracts_produkt][timeframe]" class="control-label" style="margin-bottom:9px;"><?php echo _l('payment_timeframe');?></label>
                          <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="contracts_produkt" data-fieldid="timeframe" name="custom_fields[contracts_produkt][timeframe]" id="custom_fields_contracts_produkt_timeframe" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                              <option value=""></option>
                              <option <?php if($contract->produkt_p_t == 'Monthly') echo 'selected';?> value="Monthly"><?php echo _l('monthly');?></option>
                              <option <?php if($contract->produkt_p_t == 'Quarterly') echo 'selected';?> value="Quarterly"><?php echo _l('Quarterly');?></option>
                              <option <?php if($contract->produkt_p_t == 'Half-Yearly') echo 'selected';?> value="Half-Yearly"><?php echo _l('half_yearly');?></option>
                              <option <?php if($contract->produkt_p_t == 'Annually') echo 'selected';?> value="Annually"><?php echo _l('annually');?></option>
                            </select>
                          </div>
                        </div>
                      </div>
                      
                      <?php if(isset($contract)&&($contract->produkt_remuneration == 'One Time Payment') && ($contract->produkt_p == 'Partial Payment With Increased Starting Payment')){ ?>
                      <div class="col-md-6" id="opening_payment_on_one_time"  ><?php }?>
                      <?php if(!isset($contract)||($contract->produkt_remuneration != 'One Time Payment') || ($contract->produkt_p != 'Partial Payment With Increased Starting Payment')){?>
                      <div class="col-md-6" id="opening_payment_on_one_time" style="display: none;" ><?php }?>

                        <div class="form-group">
                          <label for="produkt_opening_payment_on_one_time_value"><?php echo _l('produkt_opening_payment_value'); ?></label>
                          <div class="input-group" data-toggle="tooltip" title="<?php echo _l('produkt_opening_payment_value'); ?>">
                            <input type="number" class="form-control" name="produkt_opening_payment_on_one_time_value" id="produkt_opening_payment_on_one_time_value" value="<?php if(isset($contract->produkt_opening_payment_on_one_time_value)){echo $contract->produkt_opening_payment_on_one_time_value; }?>">
                            <div class="input-group-addon">
                               <?php echo $base_currency->symbol; ?>
                            </div>
                          </div>
                        </div>
                      </div>

                      <!-- last adding for cron job -->
                      <div id="real_payment" style="display: none;">
                        <div class="col-md-6">
                          <?php $value = (isset($contract->dateend) ? _d($contract->dateend) : ''); ?>
                          <?php echo render_date_input('dateend','contract_end_date',$value); ?>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label for="real_payment_term"><?php echo _l('real_payment_term'); ?></label>
                            <input type="number" class="form-control" name="real_payment_term" id="real_payment_term" value="">
                          </div>
                        </div>  
                      </div>
                    </div>   

                  <input type="hidden" name="timetracking_id" id="timetracking_id" value="<?php if(isset($contract->timetracking_id)) echo $contract->timetracking_id; else ''; ?>">
                  <input type="hidden" name="timetracking_rel" id="timetracking_rel" value="<?php if(isset($timetracking_rel)) echo $timetracking_rel; else ''; ?>">
                  <input type="hidden" name="tasks_ids" id="tasks_ids" value="<?php if(isset($contract->tasks_ids)) echo $contract->tasks_ids; else ''; ?>">

                </div>


                <!-- contract value -->
                <?php if(!isset($contract)||$contract->contract_type!=2) { ?> <div id="contract_value_form" style="display: none;"><?php } ?>
                <?php if(isset($contract)&&$contract->contract_type==2) { ?><div  id="contract_value_form" ><?php } ?>
                  <div class="row custom-fields-form-row">
                    <div class="col-md-12">
                      <div class="form-group">
                       <label for="contract_value"><?php echo _l('contract_value'); ?></label>
                       <div class="input-group" data-toggle="tooltip" title="<?php echo _l('contract_value_tooltip'); ?>">
                          <input type="number" class="form-control" name="contract_value" id="contract_value" value="<?php if(isset($contract)){echo $contract->contract_value; }?>" readonly>
                          <div class="input-group-addon">
                             <?php echo $base_currency->symbol; ?>
                          </div>
                       </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- tax -->
                 <?php if(!isset($contract)||$contract->contract_type!=2) { ?> <div id="contract_tax_part" style="display: none;"><?php } ?>
                <?php if(isset($contract)&&$contract->contract_type==2) { ?><div id="contract_tax_part"><?php } ?>
                
                  <div class="row custom-fields-form-row">
                    <div class="col-md-12">
                      <div class="form-group">
                       <label for="contract_tax"><?php echo _l('contract_tax'); ?></label>
                       <div class="input-group" data-toggle="tooltip" title="<?php echo _l('contract_tax_tooltip'); ?>">
                          <input type="number" class="form-control" name="contract_tax" id="contract_tax" value="<?php if(isset($contract)){echo $contract->contract_tax; }?>">
                          <div class="input-group-addon">
                             <?php echo $base_currency->symbol; ?>
                          </div>
                       </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- customer payment value -->
                <!-- <?php if(!isset($contract->id)||$contract->contract_type == null) { ?> <div id="customer_payment_value_form" style="display: none;"><?php } ?>
                <?php if(isset($contract->id)&&$contract->contract_type!=null) { ?><div  id="customer_payment_value_form" ><?php } ?> -->
                  <div  id="customer_payment_value_form" >
                  <div class="row custom-fields-form-row">
                    <div class="col-md-12">
                      <div class="form-group">
                       <label for="customer_payment_value"><?php echo _l('customer_payment_value'); ?></label>
                       <div class="input-group" data-toggle="tooltip" title="<?php echo _l('customer_payment_value_tooltip'); ?>">
                          <input type="number" class="form-control" name="customer_payment_value" id="customer_payment_value" value="<?php if(isset($contract)){echo $contract->customer_payment_value; } else echo 0; ?>" readonly>
                          <div class="input-group-addon">
                             <?php echo $base_currency->symbol; ?>
                          </div>
                       </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- description -->
                <?php $value = (isset($contract) ? $contract->description : ''); ?>
                <?php echo render_textarea('description',_l('notice_for_agent'),$value,array('rows'=>10)); ?>
                <!-- bottom -->
                <div class="btn-bottom-toolbar text-right" >
                    <button type="submit" class="btn btn-info" id="save"><?php echo _l('save');?></button>
                </div>
                
                <?php echo form_close(); ?>



                <?php if(!isset($contract)||($contract->beratung_remuneration !='Payment According To Time Spent' )) {
                  ?>
                  <div id="timetracking_and_task" style="display: none;"><?php }?>
                <?php if(isset($contract->timetracking_id)&&($contract->timetracking_id != '' && $contract->timetracking_id != null )){ 
                  ?>
                  <div id="timetracking_and_task"><?php }?>

                  <!-- Time Tracking Creation -->
                  <?php echo form_open($this->uri->uri_string(),array('id'=>'contract-timetracking-form')); ?>
                    <div id="timetracking_creation">
                      <hr class="hr-panel-heading" style="border-top:1px solid"  />
                      <h4 style="text-align:center">
                          <?php echo $project_title; ?>
                      </h4>
                      
                      <?php
                      $disable_type_edit = '';
                      if(isset($project)){
                          if($project->billing_type != 1){
                              if(total_rows(db_prefix().'tasks',array('rel_id'=>$project->id,'rel_type'=>'project','billable'=>1,'billed'=>1)) > 0){
                                  $disable_type_edit = 'disabled';
                              }
                          }
                      }
                      ?>
                      <?php $value = (isset($project) ? $project->name : ''); ?>
                      <?php echo render_input('timetracking[name]','time_tracking_name',$value); ?>
                     
                      <!-- <div class="form-group">
                          <div class="checkbox checkbox-success">
                              <input type="checkbox" <?php if((isset($project) && $project->progress_from_tasks == 1) || !isset($project)){echo 'checked';} ?> name="timetracking[progress_from_tasks]" id="progress_from_tasks">
                              <label for="timetracking[progress_from_tasks]"><?php echo _l('calculate_progress_through_tasks'); ?></label>
                          </div>
                      </div> -->
                      <!-- <?php
                      if(isset($project) && $project->progress_from_tasks == 1){
                          $value = $this->projects_model->calc_progress_by_tasks($project->id);
                      } else if(isset($project) && $project->progress_from_tasks == 0){
                          $value = $project->progress;
                      } else {
                          $value = 0;
                      }
                      ?>
                      <label for=""><?php echo _l('project_progress'); ?> <span class="label_progress"><?php echo $value; ?>%</span></label>
                      <?php //echo form_hidden('timetracking[progress]',$value); ?>
                      <input type="hidden" name="timetracking[progress]" id="timetracking_progress" value="<?php echo $value; ?>">
                      <div class="project_progress_slider project_progress_slider_horizontal mbot15"></div> -->

                      <div class="row">
                          <!-- <div class="col-md-6">
                              <div class="form-group select-placeholder">
                                  <label for="timetracking[billing_type]"><?php echo _l('time_tracking_billing_type'); ?></label>
                                  <div class="clearfix"></div>
                                  <select name="timetracking[billing_type]" class="selectpicker" id="billing_type" data-width="100%" <?php echo $disable_type_edit ; ?> data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                      
                                      <option value="2" <?php if(isset($project) && $project->billing_type == 2 || !isset($project) && $auto_select_billing_type && $auto_select_billing_type->billing_type == 2){echo 'selected'; } ?>><?php echo _l('project_billing_type_project_hours'); ?></option>
                                      
                                  </select>
                                  <?php if($disable_type_edit != ''){
                                      echo '<p class="text-danger">'._l('cant_change_billing_type_billed_tasks_found').'</p>';
                                  }
                                  ?>
                              </div>
                          </div> -->
                          <div class="col-md-6">
                              <div class="form-group" app-field-wrapper="timetracking[estimated_hours]">
                                <label for="timetracking_estimated_hours" class="control-label"><?php echo _l('estimated_hours')?></label>
                                <input type="number" id="timetracking_estimated_hours" name="timetracking[estimated_hours]" class="form-control " value="<?php echo isset($project) ? $project->estimated_hours : 0;?>" aria-invalid="false"></div>
                          </div>

                          <div class="col-md-6">
                              <div class="form-group select-placeholder">
                                  <label for="timetracking[status]"><?php echo _l('project_status'); ?></label>
                                  <div class="clearfix"></div>
                                  <select name="timetracking[status]" id="status" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                      
                                          <option value="2" <?php if(!isset($project) && $status['id'] == 2 || (isset($project) && $project->status == $status['id'])){echo 'selected';} ?>><?php  echo _l('project_progress_status'); ?></option>
                                      
                                  </select>
                              </div>
                          </div>
                      </div>

                      <div id="project_rate_per_hour" class="<?php echo $input_field_hide_class_rate_per_hour; ?>">
                          <div class="form-group" app-field-wrapper="timetracking[project_rate_per_hour]">
                            <label for="timetracking_project_rate_per_hour" class="control-label"><?php echo _l('rate_per_hour');?></label>
                            <input type="number" id="timetracking_project_rate_per_hour" name="timetracking[project_rate_per_hour]" class="form-control" value="<?php if(isset($project)) echo $project->project_rate_per_hour; else echo 0; ?>" aria-invalid="false">
                          </div>
                      </div>
                      <!-- <div class="row">
                          <div class="col-md-6">
                              <div class="form-group" app-field-wrapper="timetracking[estimated_hours]">
                                <label for="timetracking_estimated_hours" class="control-label"><?php echo _l('estimated_hours')?></label>
                                <input type="number" id="timetracking_estimated_hours" name="timetracking[estimated_hours]" class="form-control " value="<?php echo isset($project) ? $project->estimated_hours : 0;?>" aria-invalid="false"></div>
                          </div>
                          <div class="col-md-6">
                             <?php
                             $selected = array();
                             if(isset($project_members)){
                                foreach($project_members as $member){
                                    array_push($selected,$member['staff_id']);
                                }
                            } else {
                                array_push($selected,get_staff_user_id());
                            }
                            
                            ?>
                          </div>
                      </div> -->

                      <input type="hidden" name="timetracking_action" id="timetracking_action" value="<?php if(isset($project)&&($contract->timetracking_id!=null)) echo "edit"; else echo "add"; ?>">
                      <input type="hidden" name="timetracking_client" id="timetracking_client" value="">
                      <input type="hidden" name="timetracking_start_date" id="timetracking_start_date" value="">
                      <input type="hidden" name="timetracking_due_date" id="timetracking_due_date" value="">
                      <!-- <?php if(isset($contract->id))?><input type="hidden" name="contract_id_on_timetracking" id="contract_id_on_timetracking" value="<?php echo $contract->id;?>"> -->

                      <?php if(isset($project)){ ?>
                        <input type="hidden" name="time_id", id="time_id" value="<?php echo $project->id; ?>">
                      <?php }?>
                      <div class="row" style="margin-left: 70%">
                        <button type="submit" class="btn btn-primary" id="timetracking_save"><?php echo _l('save');?></button>
                      </div>
                      
                    </div>
                  <?php echo form_close(); ?>
                  <!-- Task Creation -->
                  <?php echo form_open($this->uri->uri_string(),array('id'=>'contract-tasks-form')); ?>
                  <div id ="total_tasks_creation" >
                    <?php if(!isset($contract->timetracking_id) || ($contract->timetracking_id ==null) || ($contract->timetracking_id =='')) { ?>
                    <div id="task_heading" style="display: none;"><?php } ?>
                     <?php if(isset($contract->timetracking_id) && ($contract->timetracking_id!=null) && ($contract->timetracking_id!='')) { ?>
                      <div id="task_heading"><?php } ?>
                      <hr class="hr-panel-heading" style="border-top:1px solid" />
                      <h4 style="text-align:center"><?php echo $task_title; ?></h4>
                    </div>

                    <?php if(isset($contract->tasks_ids)&&$contract->tasks_ids!=null) { ?>
                    <?php
                      $count = 0;
                      foreach ($tasks as $key => $task) {
                    ?>

                      <div id="task_creation<?php echo $count;?>">
                        <hr class="hr-panel-heading"/>
                        <div class="row">
                          <div class="col-md-12">
                            <?php
                              $rel_type = '';
                              $rel_id = '';
                              if(isset($task) || ($this->input->get('rel_id') && $this->input->get('rel_type'))){
                                  $rel_id = isset($task) ? $task->rel_id : $this->input->get('rel_id');
                                  $rel_type = isset($task) ? $task->rel_type : $this->input->get('rel_type');
                               }
                               if(isset($task) && $task->billed == 1){
                                 echo '<div class="alert alert-success text-center no-margin">'._l('task_is_billed','<a href="'.admin_url('invoices/list_invoices/'.$task->invoice_id).'" target="_blank">'.format_invoice_number($task->invoice_id)). '</a></div><br />';
                               }
                              ?>
                             <!-- <div class="checkbox checkbox-primary no-mtop checkbox-inline task-add-edit-public">
                                <input type="checkbox" id="task_<?php echo $count;?>_is_public" name="task[<?php echo $count;?>][is_public]" >
                                <label for="task_<?php echo $count;?>_is_public" data-toggle="tooltip" data-placement="bottom" title="<?php echo _l('task_public_help'); ?>"><?php echo _l('task_public'); ?></label>
                             </div>
                             <div class="checkbox checkbox-primary checkbox-inline task-add-edit-billable">
                                <input type="checkbox" id="task_<?php echo $count;?>_is_billable" name="task[<?php echo $count;?>][billable]"
                                   <?php if((isset($task) && $task->billable == 1) || (!isset($task) && get_option('task_biillable_checked_on_creation') == 1)) {echo ' checked'; }?>>
                                <label for="task_<?php echo $count;?>_is_billable"><?php echo _l('task_billable'); ?></label>
                             </div> -->
                             <button type="button" style="float:right" class="btn btn-danger btn-remove" id="remove_task_<?php echo $count;?>"><i class="fa fa-minus"></i></button>
                             <hr />
                             <?php $value = (isset($task) ? $task->name : ''); ?>
                             
                             <div class="form-group" app-field-wrapper="task[<?php echo $count;?>][name]">
                              <label for="task[<?php echo $count;?>][name]" class="control-label"><?php echo _l('task_add_edit_subject') ?></label>
                              <input type="text" id="task[<?php echo $count;?>][name]" name="task[<?php echo $count;?>][name]" class="form-control" value="<?php echo $value;?>" required>
                            </div>

                             <!-- <div class="task-hours">
                                <div class="form-group" app-field-wrapper="task[<?php echo $count;?>][hourly_rate]">
                                  <label for="task_<?php echo $count;?>_hourly_rate" class="control-label "><?php echo _l('task_hourly_rate')?></label>
                                  <input type="number" id="task_<?php echo $count;?>_hourly_rate" name="task[<?php echo $count;?>][hourly_rate]" class="form-control" value="<?php echo $staff[0]['hourly_rate']; ?>" readonly>
                                </div>
                             </div> -->
                             
                             <div class="row">
                                <div class="col-md-6">
                                   <div class="form-group">
                                      <label for="task_<?php echo $count;?>_priority" class="control-label"><?php echo _l('task_add_edit_priority'); ?></label>
                                      <select name="task[<?php echo $count;?>][priority]" class="selectpicker" id="task_<?php echo $count;?>_priority" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                         <?php foreach(get_tasks_priorities() as $priority) { ?>
                                         <option value="<?php echo $priority['id']; ?>"<?php if(isset($task) && $task->priority == $priority['id'] || !isset($task) && get_option('default_task_priority') == $priority['id']){echo ' selected';} ?>><?php echo $priority['name']; ?></option>
                                         <?php } ?>
                                         <?php hooks()->do_action('task_priorities_select', (isset($task) ? $task : 0)); ?>
                                      </select>
                                   </div>
                                </div>
                                <div class="col-md-6">
                                   <div class="form-group">
                                      <label for="task[<?php echo $count;?>][repeat_every]" class="control-label"><?php echo _l('task_repeat_every'); ?></label>
                                      <select name="task[<?php echo $count;?>][repeat_every]" id="task_<?php echo $count;?>_repeat_every" class="selectpicker repeat_every_task" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                         <option value="no-repeat" <?php if(isset($task) && $task->repeat_every == 0 && $task->recurring_type == 'day'){echo 'selected';} ?>><?php echo _l('task_no_repeat'); ?></option>
                                         <option value="1-week" <?php if(isset($task) && $task->repeat_every == 1 && $task->recurring_type == 'week'){echo 'selected';} ?>><?php echo _l('week'); ?></option>
                                         <option value="2-week" <?php if(isset($task) && $task->repeat_every == 2 && $task->recurring_type == 'week'){echo 'selected';} ?>>2 <?php echo _l('weeks'); ?></option>
                                         <option value="1-month" <?php if(isset($task) && $task->repeat_every == 1 && $task->recurring_type == 'month'){echo 'selected';} ?>>1 <?php echo _l('month'); ?></option>
                                         <option value="2-month" <?php if(isset($task) && $task->repeat_every == 2 && $task->recurring_type == 'month'){echo 'selected';} ?>>2 <?php echo _l('months'); ?></option>
                                         <option value="3-month" <?php if(isset($task) && $task->repeat_every == 3 && $task->recurring_type == 'month'){echo 'selected';} ?>>3 <?php echo _l('months'); ?></option>
                                         <option value="6-month" <?php if(isset($task) && $task->repeat_every == 6 && $task->recurring_type == 'month'){echo 'selected';} ?>>6 <?php echo _l('months'); ?></option>
                                         <option value="1-year" <?php if(isset($task) && $task->repeat_every == 1 && $task->recurring_type == 'year'){echo 'selected';} ?>>1 <?php echo _l('year'); ?></option>
                                         <option value="custom" <?php if(isset($task) && $task->custom_recurring == 1){echo 'selected';} ?>><?php echo _l('recurring_custom'); ?></option>
                                      </select>
                                   </div>
                                </div>
                             </div>
                             <div class="recurring_custom_<?php echo $count;?> <?php if((isset($task) && $task->custom_recurring != 1) || (!isset($task))){echo 'hide';} ?>">
                                <div class="row">
                                   <div class="col-md-6">
                                      <?php $value = (isset($task) && $task->custom_recurring == 1 ? $task->repeat_every : 1); ?>
                                      <?php echo render_input('task['.$count.'][repeat_every_custom]','',$value,'number',array('min'=>1)); ?>
                                   </div>
                                   <div class="col-md-6">
                                      <select name="task[<?php echo $count;?>][repeat_type_custom]" id="task_<?php echo $count;?>_repeat_type_custom" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                         <option value="day" <?php if(isset($task) && $task->custom_recurring == 1 && $task->recurring_type == 'day'){echo 'selected';} ?>><?php echo _l('task_recurring_days'); ?></option>
                                         <option value="week" <?php if(isset($task) && $task->custom_recurring == 1 && $task->recurring_type == 'week'){echo 'selected';} ?>><?php echo _l('task_recurring_weeks'); ?></option>
                                         <option value="month" <?php if(isset($task) && $task->custom_recurring == 1 && $task->recurring_type == 'month'){echo 'selected';} ?>><?php echo _l('task_recurring_months'); ?></option>
                                         <option value="year" <?php if(isset($task) && $task->custom_recurring == 1 && $task->recurring_type == 'year'){echo 'selected';} ?>><?php echo _l('task_recurring_years'); ?></option>
                                      </select>
                                   </div>
                                </div>
                             </div>
                             <div id="cycles_wrapper_<?php echo $count;?>" class="<?php if(!isset($task) || (isset($task) && $task->recurring ==0)){echo ' hide';}?>">
                                <?php $value = (isset($task) ? $task->cycles : 0); ?>
                                <div class="form-group recurring-cycles">
                                   <label for="task_<?php echo $count;?>_cycles"><?php echo _l('recurring_total_cycles'); ?>
                                   <?php if(isset($task) && $task->total_cycles > 0){
                                      echo '<small>' . _l('cycles_passed', $task->total_cycles) . '</small>';
                                      }
                                      ?>
                                   </label>
                                   <div class="input-group">
                                      <input type="number" class="form-control"<?php if($value == 0){echo ' disabled'; } ?> name="task[<?php echo $count;?>][cycles]" id="task_<?php echo $count;?>_cycles" value="<?php echo $value; ?>" <?php if(isset($task) && $task->total_cycles > 0){echo 'min="'.($task->total_cycles).'"';} ?>>
                                      <div class="input-group-addon">
                                         <div class="checkbox">
                                            <input type="checkbox"<?php if($value == 0){echo ' checked';} ?> id="task_<?php echo $count;?>_unlimited_cycles">
                                            <label for="task_<?php echo $count;?>_unlimited_cycles"><?php echo _l('cycles_infinity'); ?></label>
                                         </div>
                                      </div>
                                   </div>
                                </div>
                             </div>

                             <div class="row">
                                <!-- <div class="col-md-6">
                                   <div class="form-group">
                                      <label for="task_<?php echo $count;?>_rel_type" class="control-label"><?php echo _l('task_related_to'); ?></label>
                                      <select name="task[<?php echo $count;?>][rel_type]" class="selectpicker" id="task_<?php echo $count;?>_rel_type" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                          <option value="project"
                                            <?php if(isset($task) || $this->input->get('rel_type')){if($rel_type == 'project'){echo 'selected';}} ?>><?php echo _l('timetracking'); ?></option>
                                      </select>
                                   </div>
                                </div> -->
                                <div class="col-md-6">
                                   <div class="form-group" id="task_<?php echo $count;?>_rel_id_wrapper">
                                      <label for="rel_id" class="control-label"><?php echo _l('timetracking')?></label>
                                      <div id="task_<?php echo $count;?>_rel_id_select">
                                         <select name="task[<?php echo $count;?>][rel_id]" id="task_<?php echo $count;?>_rel_id" class="ajax-sesarch" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" required>
                                         <?php if($rel_id != '' && $rel_type != ''){
                                            $rel_data = get_relation_data($rel_type,$rel_id);
                                            $rel_val = get_relation_values($rel_data,$rel_type);
                                            echo '<option value="'.$rel_val['id'].'" selected>'.$rel_val['name'].'</option>';
                                            } ?>
                                         </select>
                                      </div>
                                   </div>
                                </div>
                             </div>

                          </div>
                        </div>
                    </div>
                    <?php 
                      $count = $count + 1;
                      } 
                    ?>
                    <?php }?>
                  </div>
                  <input type="hidden" name="tasks_action" id="tasks_action" value="<?php if(isset($tasks)&&($contract->tasks_ids!=null)) echo "edit"; else echo "add"; ?>">
                  <input type="hidden" name="tasks_start_date" id="tasks_start_date" value="">
                  <input type="hidden" name="tasks_due_date" id="tasks_due_date" value="">
                  <?php if(isset($tasks)) {?>
                    <input type="hidden" name="t_ids" id="t_ids" value="<?php echo $contract->tasks_ids;?>">
                  <?php }?>
                  <?php if(isset($contract->id))?><input type="hidden" name="contract_id_on_task" id="contract_id_on_task" value="<?php echo $contract->id;?>">

                  <?php if(!isset($contract->timetracking_id) || ($contract->timetracking_id ==null) || ($contract->timetracking_id =='')) { ?>
                  <div id="button_group" style="display: none;"><?php } ?>

                  <?php if(isset($contract->timetracking_id) && ($contract->timetracking_id!=null) && ($contract->timetracking_id!='')) { ?>
                  <div id="button_group"><?php } ?>
                    <button type="button" class="btn btn-success" id="add_task"><i class="fa fa-plus"></i></button>
                    <button type="submit" class="btn btn-primary" id="tasks_save" style="margin-left: 70%"><?php echo _l('save');?></button>
                  </div>
                  <?php echo form_close(); ?>
                </div>
                
                
               </div>
             </div>
         </div>


         <?php if(isset($contract->id)) { ?>
         <div class="col-md-7 right-column">
            <div class="panel_s">
               <div class="panel-body">
                  <h4 class="no-margin"><?php echo $contract->subject; ?></h4>
                  <a href="<?php echo site_url('contract/'.$contract->id.'/'.$contract->hash); ?>" target="_blank">
                  <?php echo _l('view_contract'); ?>
                  </a>
                  <hr class="hr-panel-heading" />
                  <?php if($contract->trash > 0){
                     echo '<div class="ribbon default"><span>'._l('contract_trash').'</span></div>';
                     } ?>
                  <div class="horizontal-scrollable-tabs preview-tabs-top">
                     <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
                     <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
                     <div class="horizontal-tabs">
                        <ul class="nav nav-tabs tabs-in-body-no-margin contract-tab nav-tabs-horizontal mbot15" role="tablist">
                           <li role="presentation" class="<?php if(!$this->input->get('tab') || $this->input->get('tab') == 'tab_content'){echo 'active';} ?>">
                              <a href="#tab_content" aria-controls="tab_content" role="tab" data-toggle="tab">
                              <?php echo _l('contract_content'); ?>
                              </a>
                           </li>
                           <li role="presentation" class="<?php if($this->input->get('tab') == 'attachments'){echo 'active';} ?>">
                              <a href="#attachments" aria-controls="attachments" role="tab" data-toggle="tab">
                              <?php echo _l('contract_attachments'); ?>
                              <?php if($totalAttachments = count($contract->attachments)) { ?>
                                  <span class="badge attachments-indicator"><?php echo $totalAttachments; ?></span>
                              <?php } ?>
                              </a>
                           </li>
                           <li role="presentation" id="comments">
                              <a href="#tab_comments" aria-controls="tab_comments" role="tab" data-toggle="tab" onclick="get_contract_comments(); return false;">
                              <?php echo _l('Question from customer'); ?>
                              <?php
                              $totalComments = total_rows(db_prefix().'contract_comments','contract_id='.$contract->id)
                              ?>
                              <span class="badge comments-indicator<?php echo $totalComments == 0 ? ' hide' : ''; ?>"><?php echo $totalComments; ?></span>
                              </a>
                           </li>
                           <li role="presentation" id="renewals" class="<?php if($this->input->get('tab') == 'renewals'){echo 'active';} ?>">
                              <a href="#renewals" aria-controls="renewals" role="tab" data-toggle="tab">
                              <?php echo _l('no_contract_renewals_history_heading'); ?>
                              <?php if($totalRenewals = count($contract_renewal_history)) { ?>
                                 <span class="badge"><?php echo $totalRenewals; ?></span>
                              <?php } ?>
                              </a>
                           </li>
                           <li role="presentation" id="tasks" class="tab-separator">
                              <a href="#tab_tasks" aria-controls="tab_tasks" role="tab" data-toggle="tab" onclick="init_rel_tasks_table(<?php echo $contract->id; ?>,'contract'); return false;">
                              <?php echo _l('tasks'); ?>
                              </a>
                           </li>
                           <li role="presentation" class="tab-separator">
                              <a href="#tab_notes" onclick="get_sales_notes(<?php echo $contract->id; ?>,'contracts'); return false" aria-controls="tab_notes" role="tab" data-toggle="tab">
                                 <?php echo _l('contract_notes'); ?>
                                 <span class="notes-total">
                                    <?php if($totalNotes > 0){ ?>
                                       <span class="badge"><?php echo $totalNotes; ?></span>
                                    <?php } ?>
                                 </span>
                              </a>
                           </li>
                           <li role="presentation" data-toggle="tooltip" title="<?php echo _l('emails_tracking'); ?>" class="tab-separator">
                              <a href="#tab_emails_tracking" aria-controls="tab_emails_tracking" role="tab" data-toggle="tab">
                                 <?php if(!is_mobile()){ ?>
                                 <i class="fa fa-envelope-open-o" aria-hidden="true"></i>
                                 <?php } else { ?>
                                 <?php echo _l('emails_tracking'); ?>
                                 <?php } ?>
                              </a>
                           </li>
                           <li role="presentation" class="tab-separator toggle_view">
                              <a href="#" onclick="contract_full_view(); return false;" data-toggle="tooltip" data-title="<?php echo _l('toggle_full_view'); ?>">
                              <i class="fa fa-expand"></i></a>
                           </li>
                        </ul>
                     </div>
                  </div>
                  <div class="tab-content">
                     <div role="tabpanel" class="tab-pane<?php if(!$this->input->get('tab') || $this->input->get('tab') == 'tab_content'){echo ' active';} ?>" id="tab_content">
                        <div class="row">
                           <?php if($contract->signed == 1){ ?>
                           <div class="col-md-12">
                              <div class="alert alert-success">
                                 <?php echo _l('document_signed_info',array(
                                    '<b>'.$contract->acceptance_firstname . ' ' . $contract->acceptance_lastname . '</b> (<a href="mailto:'.$contract->acceptance_email.'">'.$contract->acceptance_email.'</a>)',
                                    '<b>'. _dt($contract->acceptance_date).'</b>',
                                    '<b>'.$contract->acceptance_ip.'</b>')
                                    ); ?>
                              </div>
                           </div>
                           <?php } ?>
                           <div class="col-md-12 text-right _buttons">
                              <div class="btn-group">
                                 <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-file-pdf-o"></i><?php if(is_mobile()){echo ' PDF';} ?> <span class="caret"></span></a>
                                 <ul class="dropdown-menu dropdown-menu-right">
                                    <li class="hidden-xs"><a href="<?php echo admin_url('contracts/pdf/'.$contract->id.'?output_type=I'); ?>"><?php echo _l('view_pdf'); ?></a></li>
                                    <li class="hidden-xs"><a href="<?php echo admin_url('contracts/pdf/'.$contract->id.'?output_type=I'); ?>" target="_blank"><?php echo _l('view_pdf_in_new_window'); ?></a></li>
                                    <li><a href="<?php echo admin_url('contracts/pdf/'.$contract->id); ?>"><?php echo _l('download'); ?></a></li>
                                    <li>
                                       <a href="<?php echo admin_url('contracts/pdf/'.$contract->id.'?print=true'); ?>" target="_blank">
                                       <?php echo _l('print'); ?>
                                       </a>
                                    </li>
                                 </ul>
                              </div>
                              <a href="#" class="btn btn-default" data-target="#contract_send_to_client_modal" data-toggle="modal"><span class="btn-with-tooltip" data-toggle="tooltip" data-title="<?php echo _l('contract_send_to_email'); ?>" data-placement="bottom">
                              <i class="fa fa-envelope"></i></span>
                              </a>
                              <div class="btn-group">
                                 <button type="button" class="btn btn-default pull-left dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                 <i class="fa fa-eye"></i>
                                 <span class="caret"></span>
                                 </button>
                                 <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                       <a href="<?php echo site_url('contract/'.$contract->id.'/'.$contract->hash); ?>" target="_blank">
                                       <?php echo _l('view_contract'); ?>
                                       </a>
                                    </li>
                                    <?php hooks()->do_action('after_contract_view_as_client_link', $contract); ?>
                                    <?php if(has_permission('contracts','','create')){ ?>
                                    <li>
                                       <a href="<?php echo admin_url('contracts/copy/'.$contract->id); ?>">
                                       <?php echo _l('contract_copy'); ?>
                                       </a>
                                    </li>
                                    <?php } ?>
                                    <?php if($contract->signed == 1 && has_permission('contracts','','delete')){ ?>
                                    <li>
                                       <a href="<?php echo admin_url('contracts/clear_signature/'.$contract->id); ?>" class="_delete">
                                       <?php echo _l('clear_signature'); ?>
                                       </a>
                                    </li>
                                    <?php } ?>
                                    <?php if(has_permission('contracts','','delete')){ ?>
                                    <li>
                                       <a href="<?php echo admin_url('contracts/delete/'.$contract->id); ?>" class="_delete">
                                       <?php echo _l('delete'); ?></a>
                                    </li>
                                    <?php } ?>
                                 </ul>
                              </div>
                           </div>
                           <div class="col-md-12">
                              <?php if(isset($contract_merge_fields)){ ?>
                              <hr class="hr-panel-heading hide" />
                              <p class="bold mtop10 text-right" id="contractmergefields"><a href="#" onclick="slideToggle('.avilable_merge_fields'); return false;"><?php echo _l('available_merge_fields'); ?></a></p>
                              <div class=" avilable_merge_fields mtop15 hide">
                                 <ul class="list-group">
                                    <?php
                                       foreach($contract_merge_fields as $field){
                                           foreach($field as $f){
                                              echo '<li class="list-group-item"><b>'.$f['name'].'</b>  <a href="#" class="pull-right" onclick="insert_merge_field(this); return false">'.$f['key'].'</a></li>';
                                          }
                                       }
                                    ?>
                                 </ul>
                              </div>
                              <?php } ?>
                           </div>
                        </div>
                        <hr class="hr-panel-heading" />
                        <div class="editable tc-content" style="border:1px solid #d2d2d2;min-height:70px; border-radius:4px;">
                           <?php

                              // if( isset($contractDetails)){
                              //    echo $contractDetails;
                              // } 
                              // else 
                              if(empty($contract->content)){
                                 echo hooks()->apply_filters('new_contract_default_content', '<span class="text-danger text-uppercase mtop15 editor-add-content-notice"> ' . _l('click_to_add_content') . '</span>');
                              } else {
                                 echo $contract->content;
                              }
                              ?>
                        </div>
                        <?php if(!empty($contract->signature)) { ?>
                        <div class="row mtop25">
                           <div class="col-md-6 col-md-offset-6 text-right">
                              <p class="bold"><?php echo _l('document_customer_signature_text'); ?>
                                 <?php if($contract->signed == 1 && has_permission('contracts','','delete')){ ?>
                                 <a href="<?php echo admin_url('contracts/clear_signature/'.$contract->id); ?>" data-toggle="tooltip" title="<?php echo _l('clear_signature'); ?>" class="_delete text-danger">
                                 <i class="fa fa-remove"></i>
                                 </a>
                                 <?php } ?>
                              </p>
                              <div class="pull-right">
                                 <img src="<?php echo site_url('download/preview_image?path='.protected_file_url_by_path(get_upload_path_by_type('contract').$contract->id.'/'.$contract->signature)); ?>" class="img-responsive" alt="">
                              </div>
                           </div>
                        </div>
                        <?php } ?>
                     </div>
                     <div role="tabpanel" class="tab-pane" id="tab_notes">
                        <?php echo form_open(admin_url('contracts/add_note/'.$contract->id),array('id'=>'sales-notes','class'=>'contract-notes-form')); ?>
                        <?php echo render_textarea('description'); ?>
                        <div class="text-right">
                           <button type="submit" class="btn btn-info mtop15 mbot15"><?php echo _l('contract_add_note'); ?></button>
                        </div>
                        <?php echo form_close(); ?>
                        <hr />
                        <div class="panel_s mtop20 no-shadow" id="sales_notes_area">
                        </div>
                     </div>
                     <div role="tabpanel" class="tab-pane" id="tab_comments">
                        <div class="row contract-comments mtop15">
                           <div class="col-md-12">
                              <div id="contract-comments"></div>
                              <div class="clearfix"></div>
                              <textarea name="content" id="comment" rows="4" class="form-control mtop15 contract-comment"></textarea>
                              <button type="button" class="btn btn-info mtop10 pull-right" onclick="add_contract_comment();"><?php echo _l('proposal_add_comment'); ?></button>
                           </div>
                        </div>
                     </div>
                     <div role="tabpanel" class="tab-pane<?php if($this->input->get('tab') == 'attachments'){echo ' active';} ?>" id="attachments">
                        <?php echo form_open(admin_url('contracts/add_contract_attachment/'.$contract->id),array('id'=>'contract-attachments-form','class'=>'dropzone')); ?>
                        <?php echo form_close(); ?>
                        <div class="text-right mtop15">
                           <button class="gpicker" data-on-pick="contractGoogleDriveSave">
                              <i class="fa fa-google" aria-hidden="true"></i>
                              <?php echo _l('choose_from_google_drive'); ?>
                           </button>
                           <div id="dropbox-chooser"></div>
                           <div class="clearfix"></div>
                        </div>
                        <!-- <img src="https://drive.google.com/uc?id=14mZI6xBjf-KjZzVuQe8-rjtv_wXEbDTw" /> -->

                        <div id="contract_attachments" class="mtop30">
                           <?php
                              $data = '<div class="row">';
                              foreach($contract->attachments as $attachment) {
                                $href_url = site_url('download/file/contract/'.$attachment['attachment_key']);
                                if(!empty($attachment['external'])){
                                  $href_url = $attachment['external_link'];
                                }
                                $data .= '<div class="display-block contract-attachment-wrapper">';
                                $data .= '<div class="col-md-10">';
                                $data .= '<div class="pull-left"><i class="'.get_mime_class($attachment['filetype']).'"></i></div>';
                                $data .= '<a href="'.$href_url.'"'.(!empty($attachment['external']) ? ' target="_blank"' : '').'>'.$attachment['file_name'].'</a>';
                                $data .= '<p class="text-muted">'.$attachment["filetype"].'</p>';
                                $data .= '</div>';
                                $data .= '<div class="col-md-2 text-right">';
                                if($attachment['staffid'] == get_staff_user_id() || is_admin()){
                                 $data .= '<a href="#" class="text-danger" onclick="delete_contract_attachment(this,'.$attachment['id'].'); return false;"><i class="fa fa fa-times"></i></a>';
                               }
                               $data .= '</div>';
                               $data .= '<div class="clearfix"></div><hr/>';
                               $data .= '</div>';
                              }
                              $data .= '</div>';
                              echo $data;
                              ?>
                        </div>
                     </div>
                     <div role="tabpanel" class="tab-pane<?php if($this->input->get('tab') == 'renewals'){echo ' active';} ?>" id="renewals">
                        <?php if(has_permission('contracts', '', 'create') || has_permission('contracts', '', 'edit')){ ?>
                        <div class="_buttons">
                           <a href="#" class="btn btn-default" data-toggle="modal" data-target="#renew_contract_modal">
                           <i class="fa fa-refresh"></i> <?php echo _l('contract_renew_heading'); ?>
                           </a>
                        </div>
                        <hr />
                        <?php } ?>
                        <div class="clearfix"></div>
                        <?php
                           if(count($contract_renewal_history) == 0){
                            echo _l('no_contract_renewals_found');
                           }
                           foreach($contract_renewal_history as $renewal){ ?>
                        <div class="display-block">
                           <div class="media-body">
                              <div class="display-block">
                                 <b>
                                 <?php
                                    echo _l('contract_renewed_by',$renewal['renewed_by']);
                                    ?>
                                 </b>
                                 <?php if($renewal['renewed_by_staff_id'] == get_staff_user_id() || is_admin()){ ?>
                                 <a href="<?php echo admin_url('contracts/delete_renewal/'.$renewal['id'] . '/'.$renewal['contractid']); ?>" class="pull-right _delete text-danger"><i class="fa fa-remove"></i></a>
                                 <br />
                                 <?php } ?>
                                 <small class="text-muted"><?php echo _dt($renewal['date_renewed']); ?></small>
                                 <hr class="hr-10" />
                                 <span class="text-success bold" data-toggle="tooltip" title="<?php echo _l('contract_renewal_old_start_date',_d($renewal['old_start_date'])); ?>">
                                 <?php echo _l('contract_renewal_new_start_date',_d($renewal['new_start_date'])); ?>
                                 </span>
                                 <br />
                                 <?php if(is_date($renewal['new_end_date'])){
                                    $tooltip = '';
                                    if(is_date($renewal['old_end_date'])){
                                     $tooltip = _l('contract_renewal_old_end_date',_d($renewal['old_end_date']));
                                    }
                                    ?>
                                 <span class="text-success bold" data-toggle="tooltip" title="<?php echo $tooltip; ?>">
                                 <?php echo _l('contract_renewal_new_end_date',_d($renewal['new_end_date'])); ?>
                                 </span>
                                 <br/>
                                 <?php } ?>
                                 <?php if($renewal['new_value'] > 0){
                                    $contract_renewal_value_tooltip = '';
                                    if($renewal['old_value'] > 0){
                                     $contract_renewal_value_tooltip = ' data-toggle="tooltip" data-title="'._l('contract_renewal_old_value',app_format_number($renewal['old_value'])).'"';
                                    } ?>
                                 <span class="text-success bold"<?php echo $contract_renewal_value_tooltip; ?>>
                                 <?php echo _l('contract_renewal_new_value',app_format_number($renewal['new_value'])); ?>
                                 </span>
                                 <br />
                                 <?php } ?>
                              </div>
                           </div>
                           <hr />
                        </div>
                        <?php } ?>
                     </div>
                     <div role="tabpanel" class="tab-pane" id="tab_emails_tracking">
                        <?php
                           $this->load->view('admin/includes/emails_tracking',array(
                             'tracked_emails'=>
                             get_tracked_emails($contract->id, 'contract'))
                           );
                           ?>
                     </div>
                     <div role="tabpanel" class="tab-pane" id="tab_tasks">
                        <?php init_relation_tasks_table(array('data-new-rel-id'=>$contract->id,'data-new-rel-type'=>'contract')); ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <?php };?>
      </div>
   </div>
</div>
<?php init_tail(); ?>
<?php $index = (isset($contract) ? 1 : 0); ?>
<?php if(isset($contract->id)){ ?>
<!-- init table tasks -->
<script>
   var contract_id = '<?php echo $contract->id; ?>';
</script>
<?php $this->load->view('admin/contracts/send_to_client'); ?>
<?php $this->load->view('admin/contracts/renew_contract'); ?>
<?php } ?>
<?php $this->load->view('admin/contracts/contract_type'); ?>
<?php $this->load->view('admin/contracts/contract_product'); ?>

<script>
   Dropzone.autoDiscover = false;
   $(function () {

   if ($('#contract-attachments-form').length > 0) {
      new Dropzone("#contract-attachments-form", $.extend({}, _dropzone_defaults(), {
         success: function (file) {
            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
               var location = window.location.href;
               window.location.href = location.split('?')[0] + '?tab=attachments';
            }
         }
      }));
   }
   
   $('body').on('change', '#contract_type', function (){
      var id = $('#contract_type').val();
      var data;
      $.ajax({
         url: "<?php echo admin_url();?>contracts/contract_custom_type_values",
         type: 'GET',
         async: false,
         data:{
             id: id
         },
         dataType: 'json',
         success: function (json) {
           data = json;
         }
      });
      
      if( id == ''){
         $('input[name="contract_value"]').val('');
      } else if(data.value){
         $('input[name="contract_value"]').val(data.value);
      }
   });

   $('#contract_type').trigger('change');


    // In case user expect the submit btn to save the contract content
   $('#contract-form').on('submit', function () {
       $('#inline-editor-save-btn').click();
       return true;
   });

    if (typeof (Dropbox) != 'undefined' && $('#dropbox-chooser').length > 0) {
       document.getElementById("dropbox-chooser").appendChild(Dropbox.createChooseButton({
          success: function (files) {
             $.post(admin_url + 'contracts/add_external_attachment', {
                files: files,
                contract_id: contract_id,
                external: 'dropbox'
             }).done(function () {
                var location = window.location.href;
                window.location.href = location.split('?')[0] + '?tab=attachments';
             });
          },
          linkType: "preview",
          extensions: app_allowed_files.split(','),
       }));
    }

    _validate_form($('#contract-form'), {
       client: 'required',
       datestart: 'required',
       //subject: 'required'
    });

    _validate_form($('#renew-contract-form'), {
       new_start_date: 'required'
    });

    var _templates = [];
    $.each(contractsTemplates, function (i, template) {
       _templates.push({
          url: admin_url + 'contracts/get_template?name=' + template,
          title: template
       });
    });

    var editor_settings = {
       selector: 'div.editable',
       inline: true,
       theme: 'inlite',
       relative_urls: false,
       remove_script_host: false,
       inline_styles: true,
       verify_html: false,
       cleanup: false,
       apply_source_formatting: false,
       valid_elements: '+*[*]',
       valid_children: "+body[style], +style[type]",
       file_browser_callback: elFinderBrowser,
       table_default_styles: {
          width: '100%'
       },
       fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
       pagebreak_separator: '<p pagebreak="true"></p>',
       plugins: [
          'advlist pagebreak autolink autoresize lists link image charmap hr',
          'searchreplace visualblocks visualchars code',
          'media nonbreaking table contextmenu',
          'paste textcolor colorpicker'
       ],
       autoresize_bottom_margin: 50,
       insert_toolbar: 'image media quicktable | bullist numlist | h2 h3 | hr',
       selection_toolbar: 'save_button bold italic underline superscript | forecolor backcolor link | alignleft aligncenter alignright alignjustify | fontselect fontsizeselect h2 h3',
       contextmenu: "image media inserttable | cell row column deletetable | paste pastetext searchreplace | visualblocks pagebreak charmap | code",
       setup: function (editor) {

          editor.addCommand('mceSave', function () {
             save_contract_content(true);
          });

          editor.addShortcut('Meta+S', '', 'mceSave');

          editor.on('MouseLeave blur', function () {
             if (tinymce.activeEditor.isDirty()) {
                save_contract_content();
             }
          });

          editor.on('MouseDown ContextMenu', function () {
             if (!is_mobile() && !$('.left-column').hasClass('hide')) {
                contract_full_view();
             }
          });

          editor.on('blur', function () {
             $.Shortcuts.start();
          });

          editor.on('focus', function () {
             $.Shortcuts.stop();
          });

       }
    }

    if (_templates.length > 0) {
       editor_settings.templates = _templates;
       editor_settings.plugins[3] = 'template ' + editor_settings.plugins[3];
       editor_settings.contextmenu = editor_settings.contextmenu.replace('inserttable', 'inserttable template');
    }

     if(is_mobile()) {

          editor_settings.theme = 'modern';
          editor_settings.mobile    = {};
          editor_settings.mobile.theme = 'mobile';
          editor_settings.mobile.toolbar = _tinymce_mobile_toolbar();

          editor_settings.inline = false;
          window.addEventListener("beforeunload", function (event) {
            if (tinymce.activeEditor.isDirty()) {
               save_contract_content();
            }
         });
     }

    tinymce.init(editor_settings);

   });

   function save_contract_content(manual) {
    var editor = tinyMCE.activeEditor;
    var data = {};
    data.contract_id = contract_id;
    data.content = editor.getContent();
    $.post(admin_url + 'contracts/save_contract_data', data).done(function (response) {
       response = JSON.parse(response);
       if (typeof (manual) != 'undefined') {
          // Show some message to the user if saved via CTRL + S
          alert_float('success', response.message);
       }
       // Invokes to set dirty to false
       editor.save();
    }).fail(function (error) {
       var response = JSON.parse(error.responseText);
       alert_float('danger', response.message);
    });
   }

   function delete_contract_attachment(wrapper, id) {
    if (confirm_delete()) {
       $.get(admin_url + 'contracts/delete_contract_attachment/' + id, function (response) {
          if (response.success == true) {
             $(wrapper).parents('.contract-attachment-wrapper').remove();

             var totalAttachmentsIndicator = $('.attachments-indicator');
             var totalAttachments = totalAttachmentsIndicator.text().trim();
             if(totalAttachments == 1) {
               totalAttachmentsIndicator.remove();
             } else {
               totalAttachmentsIndicator.text(totalAttachments-1);
             }
          } else {
             alert_float('danger', response.message);
          }
       }, 'json');
    }
    return false;
   }

   function insert_merge_field(field) {
    var key = $(field).text();
    tinymce.activeEditor.execCommand('mceInsertContent', false, key);
   }

   function contract_full_view() {
    $('.left-column').toggleClass('hide');
    $('.right-column').toggleClass('col-md-7');
    $('.right-column').toggleClass('col-md-12');
    $(window).trigger('resize');
   }

   function add_contract_comment() {
    var comment = $('#comment').val();
    if (comment == '') {
       return;
    }
    var data = {};
    data.content = comment;
    data.contract_id = contract_id;
    $('body').append('<div class="dt-loader"></div>');
    $.post(admin_url + 'contracts/add_comment', data).done(function (response) {
       response = JSON.parse(response);
       $('body').find('.dt-loader').remove();
       if (response.success == true) {
          $('#comment').val('');
          get_contract_comments();
       }
    });
   }

   function get_contract_comments() {
    if (typeof (contract_id) == 'undefined') {
       return;
    }
    requestGet('contracts/get_comments/' + contract_id).done(function (response) {
       $('#contract-comments').html(response);
       var totalComments = $('[data-commentid]').length;
       var commentsIndicator = $('.comments-indicator');
       if(totalComments == 0) {
            commentsIndicator.addClass('hide');
       } else {
         commentsIndicator.removeClass('hide');
         commentsIndicator.text(totalComments);
       }
    });
   }

   function remove_contract_comment(commentid) {
    if (confirm_delete()) {
       requestGetJSON('contracts/remove_comment/' + commentid).done(function (response) {
          if (response.success == true) {

            var totalComments = $('[data-commentid]').length;

             $('[data-commentid="' + commentid + '"]').remove();

             var commentsIndicator = $('.comments-indicator');
             if(totalComments-1 == 0) {
               commentsIndicator.addClass('hide');
            } else {
               commentsIndicator.removeClass('hide');
               commentsIndicator.text(totalComments-1);
            }
          }
       });
    }
   }

   function edit_contract_comment(id) {
    var content = $('body').find('[data-contract-comment-edit-textarea="' + id + '"] textarea').val();
    if (content != '') {
       $.post(admin_url + 'contracts/edit_comment/' + id, {
          content: content
       }).done(function (response) {
          response = JSON.parse(response);
          if (response.success == true) {
             alert_float('success', response.message);
             $('body').find('[data-contract-comment="' + id + '"]').html(nl2br(content));
          }
       });
       toggle_contract_comment_edit(id);
    }
   }

   function toggle_contract_comment_edit(id) {
       $('body').find('[data-contract-comment="' + id + '"]').toggleClass('hide');
       $('body').find('[data-contract-comment-edit-textarea="' + id + '"]').toggleClass('hide');
   }

   function contractGoogleDriveSave(pickData) {
      var data = {};
      data.contract_id = contract_id;
      data.external = 'gdrive';
      data.files = pickData;
      $.post(admin_url + 'contracts/add_external_attachment', data).done(function () {
        var location = window.location.href;
        window.location.href = location.split('?')[0] + '?tab=attachments';
     });
   }


   $(document).ready(function(){

          // var r1 = 'One Time Payment';
          // var r2 = 'Partial Payment Of Total Amount';
          // var p1 = 'One Time Payment';
          // var p2 = 'Partial Payment';
          // var p3 = 'Partial Payment With Increased Starting Payment';
        // form validation
          $('#contract_type').attr("required",true);

          var staff0 = '<?php echo json_encode($staff)?>';
          var staff = JSON.parse(staff0);
          $('#staf_name').val(staff[0].firstname+'&nbsp;'+staff[0].lastname);
          $('#staf_info').val(staff[0].address+'</br>'+staff[0].zip+'&nbsp;'+staff[0].city+'</br>'+staff[0].state+'&nbsp;'+staff[0].short_name);
        /////////
        // Customer:
          var customer_array0 = '<?php echo json_encode($all_customers_with_country)?>';
          var customer_array = JSON.parse(customer_array0);
          $('#client').change(function(){
            for (var i = 0; i < customer_array.length; i++)
             {
                if(customer_array[i].userid == $('#client option:selected').val()){
        
                  $('#cus_value').val(customer_array[i].company);
                  $('#cus_addr_value').val(customer_array[i].address + '</br>'+ customer_array[i].zip + '&nbsp;'+customer_array[i].city + '</br>'+customer_array[i].state + '&nbsp;'+customer_array[i].short_name);
                  }

             }
          });
          //fixed customer
          var current_customer_id = $('#client').val();
          if (current_customer_id != ''){
            // console.log("fixed customer")
            for (var p = 0; p < customer_array.length; p++)
            {
              if (customer_array[p].userid == current_customer_id)
              {
                
                $('#cus_value').val(customer_array[p].company);
                $('#cus_addr_value').val(customer_array[p].address + '</br>'+ customer_array[p].zip + '&nbsp;'+customer_array[p].city + '</br>'+customer_array[p].state + '&nbsp;'+customer_array[p].short_name);
              }
              
            }
            
          }
        

        // Contract type:(#contract_type->contract_type select, #contract&&#contract_ser->other field, #subscrip->subscription div, #contract_ser->payment)
          $('#contract_type').change(function(){
              var create_test = $('#contract_type option:selected').val();
              // service abo
              if(create_test==2) {
                  $('#contract').css("display","none");
                  $('#subscrip').css("display","block");
                  $('#contract_opt').css("display","none");
                  $('#contract_ser').css("display","block");
                  $('#contract_value_form').css("display","block");
                  $('#contracts_beratung').css("display","none");
                  $('#contracts_produkt').css("display","none");
                  $('#consulting').css("display","none");
                  $('#customer_payment_value_form').css("display","block");

                  // required fields setting
                  $('#subscription').attr("required",true);
                  $('#contract_value').attr('required',true);
                  $('#custom_fields_contracts_ser_method').attr("required",true);
                  $('#custom_fields_contracts_ser_timeframe').attr("required",true);

                  $('#custom_fields_contracts_beratung_method').attr("required",false);
                  $('#custom_fields_contracts_beratung_remuneration').attr("required",false);

                  $('#consulting_client_point').attr('required',false);
                  $('#custom_fields_contracts_produkt_method').attr("required",false);
                  $('#custom_fields_contracts_produkt_remuneration').attr("required",false);
                  $('#one_time_payment').attr('required', false);
                  $('#opening_payment').attr('required', false);
                  $('#savings_amount_per_month').attr('required', false);

                  // $('#dateend').attr("required",true);
                  // $('#description').attr("required",true);
                  $('#timetracking_and_task').css('display','none');

                }
              // beratung
              else if (create_test == 3){
                  $('#contract').css("display","none");
                  $('#subscrip').css("display","none");
                  // $('#consulting').css("display","block");
                  $('#contract_opt').css("display","none");
                  $('#contract_ser').css("display","none");
                  $('#contracts_beratung').css("display","block");
                  $('#contracts_produkt').css("display","none");
                  $('#contract_value_form').css("display","none");
                  $('#customer_payment_value_form').css("display","block");
                  $('#consulting').css("display","none");
                  
                  $('#subscription').attr("required",false);
                  $('#contract_value').attr('required',false);
                  $('#custom_fields_contracts_ser_method').attr("required",false);
                  $('#custom_fields_contracts_ser_timeframe').attr("required",false);

                  $('#custom_fields_contracts_beratung_method').attr("required",true);
                  $('#custom_fields_contracts_beratung_remuneration').attr("required",true);
                  
                  $('#consulting_client_point').attr('required',false);
                  $('#custom_fields_contracts_produkt_method').attr("required",false);
                  $('#custom_fields_contracts_produkt_remuneration').attr("required",false);
                  $('#one_time_payment').attr('required', false);
                  $('#opening_payment').attr('required', false);
                  $('#savings_amount_per_month').attr('required', false);
                  
                  if($('#custom_fields_contracts_beratung_remuneration option:selected').val() == 'Payment According To Time Spent'){
                    $('#timetracking_and_task').css('display','block');
                  }
                  else{
                    $('#timetracking_and_task').css('display','none');
                  }
                  $('#timetracking_and_task').css('display','none');
              }
              else if (create_test == 1){
                  $('#contract').css("display","none");
                  $('#subscrip').css("display","none");
                  $('#consulting').css("display","block");
                  $('#contract_opt').css("display","none");
                  $('#contract_ser').css("display","none");
                  $('#contracts_beratung').css("display","none");
                  $('#contracts_produkt').css("display","block");
                  $('#contract_value_form').css("display","none");
                  $('#contract_tax_part').css("display","none");
                  $('#customer_payment_value_form').css("display","block");
                  // $('#payment').css("display","none");

                  $('#subscription').attr("required",false);
                  $('#contract_value').attr('required',false);
                  $('#custom_fields_contracts_ser_method').attr("required",false);
                  $('#custom_fields_contracts_ser_timeframe').attr("required",false);

                  $('#custom_fields_contracts_beratung_method').attr("required",false);
                  $('#custom_fields_contracts_beratung_remuneration').attr("required",false);

                  $('#consulting_client_point').attr('required',true);
                  $('#custom_fields_contracts_produkt_method').attr("required",true);
                  $('#custom_fields_contracts_produkt_remuneration').attr("required",true);
                  if($('#custom_fields_contracts_produkt_remuneration option:selected').val() == 'One Time Payment') $('#one_time_payment').attr('required', true);
                  else if ($('#custom_fields_contracts_produkt_remuneration option:selected').val() == 'Partial Payment Of Total Amount') $('#opening_payment').attr('required', true);
                  $('#savings_amount_per_month').attr('required', true);
                  $('#timetracking_and_task').css('display','none');
              }
              else {
                  $('#contract').css("display","block");
                  $('#contract_opt').css("display","block");
                  $('#contract_ser').css("display","none");
                  $('#consulting').css("display","none");
                }
            });

          /* Payment Time Frame Change in Each Case */
          $('#custom_fields_contracts_ser_timeframe').change(function(){
              var seleted_time = $('#custom_fields_contracts_ser_timeframe option:selected').val();
              if (seleted_time == 'Monthly')
                $('#customer_payment_value').val($('#contract_value').val()*12);
              else if (seleted_time == 'Quarterly')
                $('#customer_payment_value').val($('#contract_value').val()*4);
              else if (seleted_time == 'Half-Yearly')
                $('#customer_payment_value').val($('#contract_value').val()*2);
              else if (seleted_time == 'Annually')
                $('#customer_payment_value').val($('#contract_value').val());
              
            });

          $('#custom_fields_contracts_beratung_timeframe').change(function(){
              var remuneration =  $('#custom_fields_contracts_beratung_remuneration option:selected').val();
              var seleted_time = $('#custom_fields_contracts_beratung_timeframe option:selected').val();

              if(remuneration == 'One Time Payment'){
                var one_time = $('#beratung_one_time_payment_value').val();
                var opening = $('#beratung_opening_payment_value').val();

                if (seleted_time == 'Monthly')
                  $('#customer_payment_value').val((one_time-opening)*12);
                else if (seleted_time == 'Quarterly')
                  $('#customer_payment_value').val((one_time-opening)*4);
                else if (seleted_time == 'Half-Yearly')
                  $('#customer_payment_value').val((one_time-opening)*2);
                else if (seleted_time == 'Annually')
                  $('#customer_payment_value').val((one_time-opening));
              }
              else if(remuneration == 'Payment According To Time Spent'){
                var hourly_rate = $('#timetracking_project_rate_per_hour').val();
                var hours = $('#timetracking_estimated_hours').val();
                if (seleted_time == 'Monthly')
                  $('#customer_payment_value').val((hourly_rate*hours)*12);
                else if (seleted_time == 'Quarterly')
                  $('#customer_payment_value').val((hourly_rate*hours)*4);
                else if (seleted_time == 'Half-Yearly')
                  $('#customer_payment_value').val((hourly_rate*hours)*2);
                else if (seleted_time == 'Annually')
                  $('#customer_payment_value').val((hourly_rate*hours));
              }   
            });

          //Produkt-Payment TimeFrame Change Event
          $('#custom_fields_contracts_produkt_timeframe').change(function(){
              var selected_rem = $('#custom_fields_contracts_produkt_remuneration option:selected').val();
              var selected_time = $('#custom_fields_contracts_produkt_timeframe option:selected').val();
              var selected_payment = $('#custom_fields_contracts_produkt_payment option:selected').val();
              
              if (selected_rem == 'One Time Payment'){

                if(selected_payment == 'One Time Payment')
                  $('#customer_payment_value').val($('#produkt_one_time_payment_value').val());

                else if (selected_payment == 'Partial Payment'){
                  if (selected_time == 'Monthly')
                    $('#customer_payment_value').val($('#produkt_one_time_payment_value').val()/12);
                  else if (selected_time == 'Quarterly')
                    $('#customer_payment_value').val($('#produkt_one_time_payment_value').val()/4);
                  else if (selected_time == 'Half-Yearly')
                    $('#customer_payment_value').val($('#produkt_one_time_payment_value').val()/2);
                  else if (selected_time == 'Annually')
                    $('#customer_payment_value').val($('#produkt_one_time_payment_value').val());
                }

                else if (selected_payment == 'Partial Payment With Increased Starting Payment'){
                  // console.log(seleted_payment);
                  var one_time = $('#produkt_one_time_payment_value').val();
                  var opening = $('#produkt_opening_payment_on_one_time_value').val();
                  if (selected_time == 'Monthly')
                    $('#customer_payment_value').val((one_time-opening)/12);
                  else if (selected_time == 'Quarterly')
                    $('#customer_payment_value').val((one_time-opening)/4);
                  else if (selected_time == 'Half-Yearly')
                    $('#customer_payment_value').val((one_time-opening)/2);
                  else if (selected_time == 'Annually')
                    $('#customer_payment_value').val((one_time-opening));
                }

                
              }

              else if(selected_rem == 'Partial Payment Of Total Amount'){
                if (seleted_time == 'Monthly')
                  $('#customer_payment_value').val($('#agent_remuneration_price_value').val()*12);
                else if (seleted_time == 'Quarterly')
                  $('#customer_payment_value').val($('#agent_remuneration_price_value').val()*4);
                else if (seleted_time == 'Half-Yearly')
                  $('#customer_payment_value').val($('#agent_remuneration_price_value').val()*2);
                else if (seleted_time == 'Annually')
                  $('#customer_payment_value').val($('#agent_remuneration_price_value').val());
                }
            });
          /*--------------------------------*/



        // Subscription: (#subscription->only subscription select, #subtax->hidden subscription tax value, #sub_arr->hidden subscription array value)
           var subscription0 = '<?php echo json_encode($subscriptions) ?>';
           var subscription = JSON.parse(subscription0);
           var blocks_array0 = '<?php echo json_encode($blocks)?>';
           var blocks_array = JSON.parse(blocks_array0);
           //fixed subscription
           var fixed_subscription = $('#subscription option:selected').val();
           if(fixed_subscription != ''){
              for (var i = 0; i < subscription.length; i++)
               {
                  if(subscription[i].id == fixed_subscription){
                    $('#current_block').empty();
                    for (var j = 0; j<subscription[i].block_array.split(",").length; j++){
                      for(var k = 0; k<blocks_array.length;k++)
                      {
                        if(blocks_array[k].id == subscription[i].block_array.split(",")[j] ){
                          $('#current_block').append('<p style="margin-left:5%">•&nbsp;'+blocks_array[k].content+'</p>');
                          
                        }
                          
                      }
                    }
                  }
               }
           }
          
          $('#subscription').change(function(){
               var sub = $('#subscription option:selected').val();
               var contract_value;
               var contract_tax;
               for (var i = 0; i < subscription.length; i++)
               {
                  if(subscription[i].id == sub){
                    contract_value = subscription[i].monthly_costs;
                    contract_tax = subscription[i].taxrate;
                    // $('#sub_tax').val(subscription[i].taxrate);
                    $('#sub_arr').val(subscription[i].block_array.split(","));
                    $('#current_block').empty();

                    for (var j = 0; j<subscription[i].block_array.split(",").length; j++){
                      for(var k = 0; k<blocks_array.length;k++)
                      {
                        if(blocks_array[k].id == subscription[i].block_array.split(",")[j] ){
                          $('#current_block').append('<p style="margin-left:5%">•&nbsp;'+blocks_array[k].content+'</p>');
                          
                        }
                          
                      }
                    }
                  }
               }
               $('#contract_value').val(contract_value);
               $('#contract_tax').val(contract_tax);

               if($('#custom_fields_contracts_ser_timeframe').val() == 'Annually')
                $('#customer_payment_value').val(contract_value); 
            });         
          
          // beratung remuneration change
          $('#custom_fields_contracts_beratung_remuneration').change(function(){

            var selected = $('#custom_fields_contracts_beratung_remuneration option:selected').val();
            if(selected == 'One Time Payment'){
              $('#beratung_remuneration_one').css('display','block');
              $('#timetracking_and_task').css('display','none');

              // cutomer value calculation by option
              let customerValueExcl = $('#beratung_customer_payment_value_excl_tax').val();
              let tax = $('select[name="tax_id"] option:selected').val();
              let final = parseInt(customerValueExcl)+parseInt(customerValueExcl)*parseInt(tax)/100;
              $('#customer_payment_value').val(final);
              // console.log(customerValueExcl, tax);
            }
            else if (selected == 'Payment According To Time Spent'){
              $('#beratung_remuneration_one').css('display','none');
              $('input[name="timetracking[name]"]').attr('required',true);
              // $('.multiple_task_name').attr('required', true);
              // $('#beratung_remuneration').css('display','none');
              $('#timetracking_and_task').css('display','block');
              $('#save').prop('disabled',true);
            }

          });
          // cutomer value calculation by excl value
          $('#beratung_customer_payment_value_excl_tax').change(function(){

            if($('#custom_fields_contracts_beratung_remuneration option:selected').val() == 'One Time Payment'){
              let customerValueExcl = $(this).val();
              let tax = $('select[name="tax_id"] option:selected').val();
              let final = parseInt(customerValueExcl)+parseInt(customerValueExcl)*parseInt(tax)/100;
              $('#customer_payment_value').val(final);
            }

          });
          
          $('select[name="tax_id"]').change(function(){
            // console.log("1111")
            if($('#custom_fields_contracts_beratung_remuneration option:selected').val() == 'One Time Payment'){
              let customerValueExcl = $('#beratung_customer_payment_value_excl_tax').val();
              let tax = $('select[name="tax_id"] option:selected').val();
              let final = parseInt(customerValueExcl)+parseInt(customerValueExcl)*parseInt(tax)/100;
              $('#customer_payment_value').val(final);
            }

          });





          // $('.beratung_calc_values_one').change(function(){
          //   var seleted_time = $('#custom_fields_contracts_beratung_timeframe option:selected').val();
          //   var one_time = $('#beratung_one_time_payment_value').val();
          //   var opening = $('#beratung_opening_payment_value').val();
          //   if (seleted_time == 'Monthly')
          //     $('#customer_payment_value').val((one_time-opening)*12);
          //   else if (seleted_time == 'Quarterly')
          //     $('#customer_payment_value').val((one_time-opening)*4);
          //   else if (seleted_time == 'Half-Yearly')
          //     $('#customer_payment_value').val((one_time-opening)*2);
          //   else if (seleted_time == 'Annually')
          //     $('#customer_payment_value').val((one_time-opening));

          // });
          
          // produkt left side change(Remuneration Calculation)
          $('#custom_fields_contracts_produkt_remuneration').change(function(){

            var selected_payment_agent = $('#custom_fields_contracts_produkt_remuneration option:selected').val();

            if(selected_payment_agent == 'One Time Payment'){

              $('#one_time_payment').css('display','block');
              
              $('#savings_amount_per_month').css('display','none');
              $('#term').css('display','none');
              $('#amount').css('display','none');
              $('#opening_payment').css('display','none');
              $('#dynamic_percentage_per_year').css('display','none');
              $('#total_amount').css('display','none');
              $('#agent_remuneration_percent').css('display','none');
              $('#agent_remuneration_price').css('display','none');
              // $('#timeframe').css('display','block');

              //when add
              $('#custom_fields_contracts_produkt_payment').change(function(){
                
                var selected_payment = $('#custom_fields_contracts_produkt_payment').val();
                var selected_time = $('#custom_fields_contracts_produkt_timeframe option:selected').val();
                if(selected_payment == 'One Time Payment')
                {
                  $('#timeframe').css('display','none');
                  $('#real_payment').hide();
                  $('#customer_payment_value').val($('#produkt_one_time_payment_value').val());
                  $('#opening_payment_on_one_time').css('display','none');
                }

                else if (selected_payment == 'Partial Payment With Increased Starting Payment')
                {
                  $('#timeframe').css('display','none');
                  $('#opening_payment_on_one_time').css('display','block');
                  var one_time = $('#produkt_one_time_payment_value').val();
                  var opening = $('#produkt_opening_payment_on_one_time_value').val();
                  // if (selected_time == 'Monthly')
                  //   $('#customer_payment_value').val((one_time-opening)/12);
                  // else if (selected_time == 'Quarterly')
                  //   $('#customer_payment_value').val((one_time-opening)/4);
                  // else if (selected_time == 'Half-Yearly')
                  //   $('#customer_payment_value').val((one_time-opening)/2);
                  // else if (selected_time == 'Annually')
                  //   $('#customer_payment_value').val((one_time-opening));

                  if(selected_payment_agent == 'Partial Payment Of Total Amount'){
                    // console.log(selected_payment_agent);             
                    $('#real_payment').show();
                    
                  }
                }
                else{
                  $('#timeframe').css('display','none');

                  if(selected_payment_agent == 'Partial Payment Of Total Amount'){
                    $('#real_payment').show();
                  }

                  $('#customer_payment_value').val($('#produkt_one_time_payment_value').val()/12);
                  $('#opening_payment_on_one_time').css('display','none');
                }

              });
              

            }
            else if (selected_payment_agent == 'Partial Payment Of Total Amount'){
              
              $('#one_time_payment').css('display','none');
            
              $('#savings_amount_per_month').css('display','block');
              $('#term').css('display','block');
              $('#term_value').prop('required',true);
              $('#amount').css('display','block');
              $('#opening_payment').css('display','block');
              $('#dynamic_percentage_per_year').css('display','block');
              $('#total_amount').css('display','block');
              $('#agent_remuneration_percent').css('display','block');
              $('#agent_remuneration_price').css('display','block');

              $('#timeframe').css('display','none');

              $('#agent_remuneration_percent_value').change(function(){
                
                if($('#custom_fields_contracts_produkt_payment option:selected').val() == 'One Time Payment'){
                  
                  $('#customer_payment_value').val($('#agent_remuneration_price_value').val());

                } 
                else if($('#custom_fields_contracts_produkt_payment option:selected').val() == 'Partial Payment'){

                  $('#customer_payment_value').val($('#agent_remuneration_price_value').val());
                } 
                else {
                  $('#customer_payment_value').val(($('#agent_remuneration_price_value').val()-$('#produkt_opening_payment_on_one_time_value').val()));
                }


              });

              //when add
              $('#custom_fields_contracts_produkt_payment').change(function(){
                
                var selected_payment = $(this).val();
                if(selected_payment == 'One Time Payment')
                {
                  $('#timeframe').css('display','none');
                  $('#real_payment').hide();
                  $('#customer_payment_value').val($('#agent_remuneration_price_value').val());
                  $('#opening_payment_on_one_time').css('display','none');
                }

                else if (selected_payment == 'Partial Payment With Increased Starting Payment')
                {
                  $('#timeframe').css('display','none');
                  $('#opening_payment_on_one_time').css('display','block');
                  
                  var opening = $('#produkt_opening_payment_on_one_time_value').val();
                  
                  if(selected_payment_agent == 'Partial Payment Of Total Amount'){
                    // console.log(selected_payment_agent);             
                    $('#real_payment').show();
                    var real_payment_term = $('#real_payment_term').val();
                    $('#customer_payment_value').val(($('#agent_remuneration_price_value').val()-opening)/real_payment_term);
                    
                  }
                }
                else{
                  $('#timeframe').css('display','none');

                  if(selected_payment_agent == 'Partial Payment Of Total Amount'){
                    $('#real_payment').show();
                    var real_payment_term = $('#real_payment_term').val();
                    $('#customer_payment_value').val($('#agent_remuneration_price_value').val()/real_payment_term);
                  }

                  $('#customer_payment_value').val($('#produkt_one_time_payment_value').val()/12);
                  $('#opening_payment_on_one_time').css('display','none');
                }

              });

            }


          });
          //when edit
          
          $('#custom_fields_contracts_produkt_payment').change(function(){
                var selected_payment_agent = $('#custom_fields_contracts_produkt_remuneration option:selected').val();
                var seleted_payment = $('#custom_fields_contracts_produkt_payment').val();
                var seleted_time = $('#custom_fields_contracts_produkt_timeframe option:selected').val();
                if(seleted_payment == 'One Time Payment')
                {

                  $('#timeframe').css('display','none');
                  $('#opening_payment_on_one_time').css('display','none');
                  // $('#customer_payment_value').val($('#produkt_one_time_payment_value').val());
                  if(selected_payment_agent == 'One Time Payment'){
                    
                    $('#customer_payment_value').val($('#produkt_one_time_payment_value').val());
                  }
                  else if(selected_payment_agent == 'Partial Payment Of Total Amount'){
                    $('#customer_payment_value').val($('#agent_remuneration_price_value').val());
                  }
                }
                else if (seleted_payment == 'Partial Payment With Increased Starting Payment')
                {
                  $('#timeframe').css('display','none');
                  $('#opening_payment_on_one_time').css('display','block');
                  var one_time = $('#produkt_one_time_payment_value').val();
                  var agent_price = $('#agent_remuneration_price_value').val();
                  var opening = $('#produkt_opening_payment_on_one_time_value').val();
                  // if (seleted_time == 'Monthly')
                  //   $('#customer_payment_value').val((one_time-opening)/12);
                  // else if (seleted_time == 'Quarterly')
                  //   $('#customer_payment_value').val((one_time-opening)/4);
                  // else if (seleted_time == 'Half-Yearly')
                  //   $('#customer_payment_value').val((one_time-opening)/2);
                  // else if (seleted_time == 'Annually')
                  //   $('#customer_payment_value').val((one_time-opening));
                  if(selected_payment_agent == 'One Time Payment'){
                    // console.log("3333");
                    $('#customer_payment_value').val((one_time-opening)/12);
                  }
                  else if(selected_payment_agent == 'Partial Payment Of Total Amount'){
                    $('#customer_payment_value').val((agent_price-opening)/12);
                  }
                }
                else{
                  
                  $('#timeframe').css('display','none');
                  $('#opening_payment_on_one_time').css('display','none');
                  if(selected_payment_agent == 'One Time Payment')
                    $('#customer_payment_value').val($('#produkt_one_time_payment_value').val()/12);
                  else if(selected_payment_agent == 'Partial Payment Of Total Amount'){
                    $('#customer_payment_value').val($('#agent_remuneration_price_value').val()/12);
                  }
                }

              });

          $('#produkt_opening_payment_on_one_time_value').change(function(){
            var selected_payment_agent = $('#custom_fields_contracts_produkt_remuneration option:selected').val();
            var seleted_time = $('#custom_fields_contracts_produkt_timeframe option:selected').val();
            var one_time = $('#produkt_one_time_payment_value').val();
            var agent_price = $('#agent_remuneration_price_value').val();
            var opening = $(this).val();

            // if (seleted_time == 'Monthly')
            //   $('#customer_payment_value').val((one_time-opening)/12);
            // else if (seleted_time == 'Quarterly')
            //   $('#customer_payment_value').val((one_time-opening)/4);
            // else if (seleted_time == 'Half-Yearly')
            //   $('#customer_payment_value').val((one_time-opening)/2);
            // else if (seleted_time == 'Annually')
            //   $('#customer_payment_value').val((one_time-opening));
            if(selected_payment_agent == 'One Time Payment'){
                    // console.log("3333");
                    $('#customer_payment_value').val((one_time-opening)/12);
                  }
                  else if(selected_payment_agent == 'Partial Payment Of Total Amount')
                    $('#customer_payment_value').val((agent_price-opening)/12);
            
          });

          $('input[type="range"]').on('input', function() {

            var control = $(this),
              controlMin = control.attr('min'),
              controlMax = control.attr('max'),
              controlVal = control.val(),
              controlThumbWidth = control.data('thumbwidth');

            var range = controlMax - controlMin;
            
            var position = ((controlVal - controlMin) / range) * 100;
            var positionOffset = Math.round(controlThumbWidth * position / 100) - (controlThumbWidth / 2);
            var output = control.next('output');
            
            output
              .css('left', 'calc(' + position + '% - ' + positionOffset + 'px)')
              .text(controlVal);

            $('#display_percent').empty();
            $('#display_percent').append(controlVal);

          });

          // term calculate in produkt
          $('.datepicker').change(function(){
              
              var month1 = $('#datestart').val().substring(3,5);
              var year1 = $('#datestart').val().substring(6);

              var month2 = $('#dateend').val().substring(3,5);
              var year2 = $('#dateend').val().substring(6);

              var real_payment_term = 12*(year2-year1)+parseInt(month2)-parseInt(month1);
              $('#real_payment_term').val(real_payment_term);
              $('#real_payment_term').prop('readonly',true);

              var selected_remuneration = $('#custom_fields_contracts_produkt_remuneration').val();
              var selected_payment = $('#custom_fields_contracts_produkt_payment').val();
              var agent_remuneration_price_value = $('#agent_remuneration_price_value').val();
              var open_value = $('#produkt_opening_payment_on_one_time_value').val();

               if(selected_remuneration == 'Partial Payment Of Total Amount'){
                if(selected_payment == 'Partial Payment')
                  $('#customer_payment_value').val(agent_remuneration_price_value/real_payment_term);
                else if(selected_payment == 'Partial Payment With Increased Starting Payment'){
                  $('#customer_payment_value').val((agent_remuneration_price_value - open_value)/real_payment_term);
                }
               }


              // var open = ($('#opening_payment_check').prop("checked") == true) ? $('#produkt_opening_payment_value').val() : 0;
              // var saving = $('#savings_amount_per_month_value').val();
              // var term = parseInt(month2)-parseInt(month1);
              // var dynamicPercent = ($('#dynamic_percent_check').prop("checked") == true) ? $('#dynamic_percentage_per_year_value').val() : 0;

              // var amount = parseInt(saving)*parseInt(term)*12;
              // var totalAmount = amount+amount*parseInt(dynamicPercent)*0.01+parseInt(open);
              // var remunerationPercent = $('#agent_remuneration_percent_value').val();

              // $('#amount_value').val(amount);
              // $('#total_amount_value').val(totalAmount);
              // $('#agent_remuneration_price_value').val(totalAmount*remunerationPercent/100);
              
              // if ($('#contract_type option:selected').val() ==1 && $('#custom_fields_contracts_produkt_remuneration option:selected').val() == 'Partial Payment Of Total Amount')
              // {
              //   var seleted_time = $('#custom_fields_contracts_produkt_timeframe option:selected').val();
              //   if (seleted_time == 'Monthly')
              //     $('#customer_payment_value').val($('#agent_remuneration_price_value').val()*12);
              //   else if (seleted_time == 'Quarterly')
              //     $('#customer_payment_value').val($('#agent_remuneration_price_value').val()*4);
              //   else if (seleted_time == 'Half-Yearly')
              //     $('#customer_payment_value').val($('#agent_remuneration_price_value').val()*2);
              //   else if (seleted_time == 'Annually')
              //     $('#customer_payment_value').val($('#agent_remuneration_price_value').val());  
              // }
              

            });
          
          
          // Nettoprodukt First Remuneration Value Changes
          $('#produkt_one_time_payment_value').change(function(){

              var seleted_time = $('#custom_fields_contracts_produkt_timeframe option:selected').val();

              if (seleted_time == 'Monthly')
                $('#customer_payment_value').val($('#produkt_one_time_payment_value').val()/12);
              else if (seleted_time == 'Quarterly')
                $('#customer_payment_value').val($('#produkt_one_time_payment_value').val()/4);
              else if (seleted_time == 'Half-Yearly')
                $('#customer_payment_value').val($('#produkt_one_time_payment_value').val()/2);
              else if (seleted_time == 'Annually')
                $('#customer_payment_value').val($('#produkt_one_time_payment_value').val());

              if($('#custom_fields_contracts_produkt_payment').val() == 'One Time Payment')
                $('#customer_payment_value').val($('#produkt_one_time_payment_value').val());

          });

          // Nettoprodukt Second Remuneration Value Changes

          $('.produkt_calc_values').change(function(){

              var open = ($('#opening_payment_check').prop("checked") == true) ? $('#produkt_opening_payment_value').val() : 0;
              var saving = $('#savings_amount_per_month_value').val();
              var term = $('#term_value').val();
              var dynamicPercent = ($('#dynamic_percent_check').prop("checked") == true) ? $('#dynamic_percentage_per_year_value').val() : 0;
              // console.log(open, saving, term, dynamicPercent);
              var amount = parseInt(saving)*parseInt(term);
              var totalAmount = amount+amount*parseInt(dynamicPercent)*0.01+parseInt(open);
              var remunerationPercent = $('#agent_remuneration_percent_value').val();

              $('#amount_value').val(amount);
              $('#total_amount_value').val(totalAmount);
              $('#agent_remuneration_price_value').val(totalAmount*remunerationPercent*0.01);

              
              var seleted_time = $('#custom_fields_contracts_produkt_timeframe option:selected').val();
              if (seleted_time == 'Monthly')
                $('#customer_payment_value').val($('#agent_remuneration_price_value').val()*12);
              else if (seleted_time == 'Quarterly')
                $('#customer_payment_value').val($('#agent_remuneration_price_value').val()*4);
              else if (seleted_time == 'Half-Yearly')
                $('#customer_payment_value').val($('#agent_remuneration_price_value').val()*2);
              else if (seleted_time == 'Annually')
                $('#customer_payment_value').val($('#agent_remuneration_price_value').val());
              
              
          });

          $('#opening_payment_check').click(function(){

              if($(this).prop("checked") == true)
                $('#opening_hidden').css("display",'block');
              else{
                $('#opening_hidden').css("display",'none');
                $('#produkt_opening_payment_value').val(0);
              }
                

                var open = ($('#opening_payment_check').prop("checked") == true) ? $('#produkt_opening_payment_value').val() : 0;
                var saving = $('#savings_amount_per_month_value').val();
                var term = $('#term_value').val();
                var dynamicPercent = ($('#dynamic_percent_check').prop("checked") == true) ? $('#dynamic_percentage_per_year_value').val() : 0;
                // console.log(open, saving, term, dynamicPercent);
                var amount = parseInt(saving)*parseInt(term);
                var totalAmount = amount+amount*parseInt(dynamicPercent)*0.01+parseInt(open);
                var remunerationPercent = $('#agent_remuneration_percent_value').val();

                $('#amount_value').val(amount);
                $('#total_amount_value').val(totalAmount);
                $('#agent_remuneration_price_value').val(totalAmount*remunerationPercent*0.01);

                
                var seleted_time = $('#custom_fields_contracts_produkt_timeframe option:selected').val();
                if (seleted_time == 'Monthly')
                  $('#customer_payment_value').val($('#agent_remuneration_price_value').val()*12);
                else if (seleted_time == 'Quarterly')
                  $('#customer_payment_value').val($('#agent_remuneration_price_value').val()*4);
                else if (seleted_time == 'Half-Yearly')
                  $('#customer_payment_value').val($('#agent_remuneration_price_value').val()*2);
                else if (seleted_time == 'Annually')
                  $('#customer_payment_value').val($('#agent_remuneration_price_value').val());
              
            });

            $('#dynamic_percent_check').click(function(){

              if($(this).prop("checked") == true)
              {
                $('#dynamic_hidden').css("display",'block');
                $('#dynamic_value_hidden').css("display",'block');
              }
              else
              {
                $('#dynamic_hidden').css("display",'none');
                $('#dynamic_value_hidden').css("display",'none');
                // $('#dynamic_percentage_per_year_value').val(-5);
              }

              var open = ($('#opening_payment_check').prop("checked") == true) ? $('#produkt_opening_payment_value').val() : 0;
              var saving = $('#savings_amount_per_month_value').val();
              var term = $('#term_value').val();
              var dynamicPercent = ($('#dynamic_percent_check').prop("checked") == true) ? $('#dynamic_percentage_per_year_value').val() : 0;
              // console.log(open, saving, term, dynamicPercent);
              var amount = parseInt(saving)*parseInt(term);
              var totalAmount = amount+amount*parseInt(dynamicPercent)*0.01+parseInt(open);
              var remunerationPercent = $('#agent_remuneration_percent_value').val();

              $('#amount_value').val(amount);
              $('#total_amount_value').val(totalAmount);
              $('#agent_remuneration_price_value').val(totalAmount*remunerationPercent*0.01);

              var seleted_time = $('#custom_fields_contracts_produkt_timeframe option:selected').val();
              if (seleted_time == 'Monthly')
                $('#customer_payment_value').val($('#agent_remuneration_price_value').val()*12);
              else if (seleted_time == 'Quarterly')
                $('#customer_payment_value').val($('#agent_remuneration_price_value').val()*4);
              else if (seleted_time == 'Half-Yearly')
                $('#customer_payment_value').val($('#agent_remuneration_price_value').val()*2);
              else if (seleted_time == 'Annually')
                $('#customer_payment_value').val($('#agent_remuneration_price_value').val());
            
            });





            /* Adding New TimeTracking*/
            
            // appValidateForm($('form'),{name:'required',start_date:'required',billing_type:'required'});

            // $('select[name="timetracking[status]"]').on('change',function(){
            //     // console.log("status");
            //     var status = $(this).val();
            //     var mark_all_tasks_completed = $('.mark_all_tasks_as_completed');
            //     var notify_project_members_status_change = $('.notify_project_members_status_change');
            //     mark_all_tasks_completed.removeClass('hide');
            //     if(typeof(original_project_status) != 'undefined'){
            //         if(original_project_status != status){

            //             mark_all_tasks_completed.removeClass('hide');
            //             notify_project_members_status_change.removeClass('hide');

            //             if(status == 4 || status == 5 || status == 3) {
            //                 $('.recurring-tasks-notice').removeClass('hide');
            //                 var notice = "<?php //echo _l('project_changing_status_recurring_tasks_notice'); ?>";
            //                 notice = notice.replace('{0}', $(this).find('option[value="'+status+'"]').text().trim());
            //                 $('.recurring-tasks-notice').html(notice);
            //                 $('.recurring-tasks-notice').append('<input type="hidden" name="cancel_recurring_tasks" value="true">');
            //                 mark_all_tasks_completed.find('input').prop('checked',true);
            //             } else {
            //                 $('.recurring-tasks-notice').html('').addClass('hide');
            //                 mark_all_tasks_completed.find('input').prop('checked',false);
            //             }
            //         } else {
            //             mark_all_tasks_completed.addClass('hide');
            //             mark_all_tasks_completed.find('input').prop('checked',false);
            //             notify_project_members_status_change.addClass('hide');
            //             $('.recurring-tasks-notice').html('').addClass('hide');
            //         }
            //     }

            //     if(status == 4){
            //         $('.project_marked_as_finished').removeClass('hide');
            //     } else {
            //         $('.project_marked_as_finished').addClass('hide');
            //         $('.project_marked_as_finished').prop('checked',false);
            //     }
            // });

            // $('form').on('submit',function(){
            //     $('input[name="project_rate_per_hour"]').prop('disabled',false);
            // });

            var progress_input = $('#timetracking_progress');
            var progress_from_tasks = $('#progress_from_tasks');
            var progress = progress_input.val();
            // console.log("progress",progress);
            $('.project_progress_slider').slider({
                min:0,
                max:100,
                value:progress,
                disabled:progress_from_tasks.prop('checked'),
                slide: function( event, ui ) {
                    progress_input.val( ui.value );
                    $('.label_progress').html(ui.value+'%');
                }
            });

            progress_from_tasks.on('change',function(){
                var _checked = $(this).prop('checked');
                $('.project_progress_slider').slider({disabled:_checked});
            });

            /*End TimeTracking*/

            /*Task Creation*/
          

            // ajax-search on timetracking select
          var edit_index = '<?php echo $contract->tasks_ids?>';
          // console.log("edit_index",edit_index);
          var index_arr = edit_index.split(",");
          // console.log(index_arr);
          function task_rel_select(){
            // console.log("2",_rel_id);
            var serverData = {};
            serverData.rel_id = _rel_id.val();
            data.type = _rel_type.val();
            init_ajax_search(_rel_type.val(),_rel_id,serverData);
           }
          
          if(edit_index == '')
          {
            var _rel_id = $('#task_0_rel_id'),
            _rel_type = $('#task_0_rel_type'),
            data = {};
            task_rel_select();
            
          }
          else{
            for(let i=0; i<index_arr.length; i++){
              var _rel_id = $('#task_'+i+'_rel_id'),
              _rel_type = $('#task_'+i+'_rel_type'),
              data = {};
              // console.log("1",_rel_id);
              task_rel_select();

            }
          }
          
          $('.btn-remove').click(function(){
            // console.log("ddd");
            var parent = this.parentNode.parentNode.parentNode;
            parent.remove();
          });

          $('select.repeat_every_task').change(function(){
              var number = this.id.match(/\d+/)[0];
              // console.log(number)
              var val = $(this).val();
              val == 'custom' ? $('.recurring_custom_'+number+'').removeClass('hide') : $('.recurring_custom_0').addClass('hide');

              if (val !== '' && val != 0) {
                  $("body").find('#cycles_wrapper_'+number+'').removeClass('hide');
              } else {
                  $("body").find('#cycles_wrapper_'+number+'').addClass('hide');
                  $("body").find('#cycles_wrapper_'+number+' #task_'+number+'_cycles').val(0);
                  $('#task_'+number+'_unlimited_cycles').prop('checked', true).change();
              }
          });

          var rel_timetracking = JSON.parse('<?php if(isset($timetracking_rel)) echo $timetracking_rel; else echo '""';?>');
          var taskCnt = index_arr.length ;
          $('#add_task').click(function(){
            $('#total_tasks_creation').append(
            '<div id="task_creation'+taskCnt+'"><hr class="hr-panel-heading"><div class="row"><div class="col-md-12"><button type="button" style="float:right" class="btn btn-danger btn-remove" id="remove_task_'+taskCnt+'"><i class="fa fa-minus"></i></button><hr/>  <div class="form-group" app-field-wrapper="task['+taskCnt+'][name]"><label for="task_'+taskCnt+'_name" class="control-label"><?php echo _l('task_add_edit_subject')?></label><input type="text" id="task_'+taskCnt+'_name" name="task['+taskCnt+'][name]" class="form-control" value="" required></div><div class="row"><div class="col-md-6"><div class="form-group"><label for="task_'+taskCnt+'_priority" class="control-label"><?php echo _l('task_add_edit_priority'); ?></label><select name="task['+taskCnt+'][priority]" class="selectpicker" id="task_'+taskCnt+'_priority" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"><?php foreach(get_tasks_priorities() as $priority) { ?><option value="<?php echo $priority['id']; ?>"<?php if(isset($task) && $task->priority == $priority['id'] || !isset($task) && get_option('default_task_priority') == $priority['id']){echo ' selected';} ?>><?php echo $priority['name']; ?></option><?php } ?><?php hooks()->do_action('task_priorities_select', (isset($task) ? $task : 0)); ?></select></div></div><div class="col-md-6"><div class="form-group"><label for="task_'+taskCnt+'_repeat_every" class="control-label"><?php echo _l('task_repeat_every'); ?></label><select name="task['+taskCnt+'][repeat_every]" id="task_'+taskCnt+'_repeat_every" class="selectpicker repeat_every_task" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"><option value="no-repeat"><?php echo _l('task_no_repeat'); ?></option><option value="1-week"><?php echo _l('week'); ?></option><option value="2-week">2 <?php echo _l('weeks'); ?></option><option value="1-month" >1 <?php echo _l('month'); ?></option><option value="2-month" >2 <?php echo _l('months'); ?></option><option value="3-month" >3 <?php echo _l('months'); ?></option><option value="6-month" >6 <?php echo _l('months'); ?></option><option value="1-year" >1 <?php echo _l('year'); ?></option><option value="custom" ><?php echo _l('recurring_custom'); ?></option></select></div></div></div> <div class="recurring_custom_'+taskCnt+' <?php if((isset($task) && $task->custom_recurring != 1) || (!isset($task))){echo 'hide';} ?>"><div class="row"><div class="col-md-6"><?php $value = (isset($task) && $task->custom_recurring == 1 ? $task->repeat_every : 1); ?><div class="form-group" app-field-wrapper="task['+taskCnt+'][repeat_every_custom]"><input type="number" id="task_'+taskCnt+'_repeat_every_custom" name="task['+taskCnt+'][repeat_every_custom]" class="form-control" min="1" value="<?php echo $value?>" aria-invalid="false"></div></div><div class="col-md-6"><select name="task['+taskCnt+'][repeat_type_custom]" id="task_'+taskCnt+'_repeat_type_custom" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"><option value="day" ><?php echo _l('task_recurring_days'); ?></option><option value="week" ><?php echo _l('task_recurring_weeks'); ?></option><option value="month" ><?php echo _l('task_recurring_months'); ?></option><option value="year" ><?php echo _l('task_recurring_years'); ?></option></select></div></div></div><div id="cycles_wrapper_'+taskCnt+'" class="<?php if(!isset($task) || (isset($task) && $task->recurring == 0)){echo ' hide';}?>"><?php $value = (isset($task) ? $task->cycles : 0); ?><div class="form-group recurring-cycles"><label for="task_'+taskCnt+'_cycles"><?php echo _l('recurring_total_cycles'); ?><?php if(isset($task) && $task->total_cycles > 0){ echo '<small>' . _l('cycles_passed', $task->total_cycles) . '</small>';}?></label><div class="input-group"><input type="number" class="form-control"<?php if($value == 0){echo ' disabled'; } ?> name="task['+taskCnt+'][cycles]" id="task_'+taskCnt+'_cycles" value="<?php echo $value; ?>" <?php if(isset($task) && $task->total_cycles > 0){echo 'min="'.($task->total_cycles).'"';} ?>><div class="input-group-addon"><div class="checkbox"><input type="checkbox"<?php if($value == 0){echo ' checked';} ?> id="task_'+taskCnt+'_unlimited_cycles" class="task_cycle"><label for="task_'+taskCnt+'_unlimited_cycles"><?php echo _l('cycles_infinity'); ?></label></div></div></div></div></div><div class="row"><div class="col-md-6"><div class="form-group" id="task_'+taskCnt+'_rel_id_wrapper"><label for="rel_id" class="control-label"><?php echo _l('timetracking')?></label><div id="task_'+taskCnt+'_rel_id_select"><select name="task['+taskCnt+'][rel_id]" id="task_'+taskCnt+'_rel_id"  data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>"><option value="'+rel_timetracking.id+'" selected>'+rel_timetracking.name+'</option></select></div></div></div></div>    </div>   </div></div></div>'
              );

            appDatepicker();
            appSelectPicker();

            $('.btn-remove').click(function(){
              // console.log("ddd");
              var parent = this.parentNode.parentNode.parentNode;
              parent.remove();
            });


            $('select.repeat_every_task').change(function(){
               
              var number = this.id.match(/\d+/)[0];
              var val = $(this).val();
              val == 'custom' ? $('.recurring_custom_'+number).removeClass('hide') : $('.recurring_custom_'+number).addClass('hide');
              if (val !== '' && val != 0) {
                  $("body").find('#cycles_wrapper_'+number).removeClass('hide');
              } else {
                  $("body").find('#cycles_wrapper_'+number).addClass('hide');
                  $("body").find('#cycles_wrapper_'+number+' #task_'+number+'_cycles').val(0);
                  $('#task_'+number+'_unlimited_cycles').prop('checked', true).change();
              }
            });


            
            var _rel_id = $('#task_'+taskCnt+'_rel_id'),
           _rel_type = $('#task_'+taskCnt+'_rel_type'),
           data = {};

           $(function(){
              var serverData = {};
              serverData.rel_id = _rel_id.val();
              data.type = 'project';
              init_ajax_search('project',_rel_id,serverData);
            });

             // console.log("current",taskCnt);
            
            $('.task_cycle').change(function(){
              var number = this.id.match(/\d+/)[0];
              if($(this).prop('checked')){
                
                $('body').find('#task_'+number+'_cycles').prop('disabled',true);
              }
              else{
                
                $('body').find('#task_'+number+'_cycles').prop('disabled',false);
              }
            });

           taskCnt++;
          });
          

          
          /*End Task*/

          $('#timetracking_estimated_hours').change(function(){
            var hours = $('#timetracking_estimated_hours').val();
            var rate = $('#timetracking_project_rate_per_hour').val();
            $('#customer_payment_value').val((hours*rate));
            // var seleted_time = $('#custom_fields_contracts_beratung_timeframe option:selected').val();

            // if (seleted_time == 'Monthly')
            //       $('#customer_payment_value').val((hours*rate)*12);
            //     else if (seleted_time == 'Quarterly')
            //       $('#customer_payment_value').val((hours*rate)*4);
            //     else if (seleted_time == 'Half-Yearly')
            //       $('#customer_payment_value').val((hours*rate)*2);
            //     else if (seleted_time == 'Annually')
            //       $('#customer_payment_value').val((hours*rate));

          });

          $('#timetracking_project_rate_per_hour').change(function(){
            var hours = $('#timetracking_estimated_hours').val();
            var rate = $('#timetracking_project_rate_per_hour').val();
            $('#customer_payment_value').val((hours*rate));
            // var seleted_time = $('#custom_fields_contracts_beratung_timeframe option:selected').val();

            // if (seleted_time == 'Monthly')
            //       $('#customer_payment_value').val((hours*rate)*12);
            //     else if (seleted_time == 'Quarterly')
            //       $('#customer_payment_value').val((hours*rate)*4);
            //     else if (seleted_time == 'Half-Yearly')
            //       $('#customer_payment_value').val((hours*rate)*2);
            //     else if (seleted_time == 'Annually')
            //       $('#customer_payment_value').val((hours*rate));

          });
          

          $('#contract-timetracking-form').submit(function(e){
            
            var client = $('#client option:selected').val();
            var startDate = $('#datestart').val();
            // var dueDate = $('#dateend').val();
            
            $('#timetracking_start_date').val(startDate);
            // $('#timetracking_due_date').val(dueDate);
            $('#timetracking_client').val(client);

            e.preventDefault();
            $.ajax({
              url:'<?php echo admin_url('contracts/timetracking_on_contract') ?>',
              method: 'POST',
              data: new FormData(this),
              contentType:false,
              processData:false,
              success:function(data){
                var res = JSON.parse(data);
                console.log(res);
                alert(res.msg);
                if(res.status == 'add'){
                  $('#task_heading').css('display','block');
                  $('#button_group').css('display','block');
                  $('#timetracking_id').val(res.id);
                  $('#timetracking_action').val('edit');
                  // $('#timetracking_rel').val(res.rel_val);
                  rel_timetracking = res.rel_val;
                }
              }
            });
          });



           $('#contract-tasks-form').submit(function(e){

            var startDate = $('#datestart').val();
            // var dueDate = $('#dateend').val();
            $('#tasks_start_date').val(startDate);
            // $('#tasks_due_date').val(dueDate);

            e.preventDefault();
            $.ajax({
              url:'<?php echo admin_url('contracts/tasks_on_contract') ?>',
              method: 'POST',
              data: new FormData(this),
              contentType:false,
              processData:false,
              success:function(data){
                $('#save').prop('disabled',false);
                var res = JSON.parse(data);
                if(res.status && res.status == 'add'){
                  alert(res.msg)
                  $('#tasks_ids').val(res.ids);
                  $('#tasks_action').val('edit');
                }
                else {
                  if(res.flag == true){
                    alert(res.msg);
                    $('#tasks_ids').val(res.ids);
                  }
                }
                
              }
            });
           });

   });
</script>
</body>
</html>
