<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php //print_r($contract);
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
                        <input type="checkbox" id="trash" name="trash"<?php if(isset($contract->trash)){if($contract->trash == 'on'){echo ' checked';}}; ?>>
                        <label for="trash"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('contract_trash_tooltip'); ?>" ></i> <?php echo _l('contract_trash'); ?></label>
                     </div>
                     <div class="checkbox checkbox-primary checkbox-inline">
                        <input type="checkbox" name="not_visible_to_client" id="not_visible_to_client" <?php if(isset($contract->not_visible_to_client)){if($contract->not_visible_to_client == 'on'){echo 'checked';}}; ?>>
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
                  <div class="form-group select-placeholder">
                     <label for="clientid" class="control-label"><span class="text-danger">* </span><?php echo _l('contract_client_string'); ?></label>
                     <select id="clientid" name="client" data-live-search="true" data-width="100%" class="ajax-search" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
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
                  </div>

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
                     <div class="col-md-6">
                        <?php $value = (isset($contract->dateend) ? _d($contract->dateend) : ''); ?>
                        <?php echo render_date_input('dateend','contract_end_date',$value); ?>
                     </div>
                     <input type="hidden" name="day_diff" id="day_diff" value="<?php if(isset($contract->day_diff)) echo $contract->day_diff; else echo 0;?>">
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
                        if(!isset($contract->subscription)) echo '<div id="subscrip" style="display:none">';
                        if(isset($contract->subscription)) echo '<div id="subscrip">';
                        echo render_select('subscription',$subscriptions,array('id','name'),'Subscription',$selected);
                        echo "</div>";
                       }
                  ?>

                  <!-- Consulting  -->
                  <?php if(!isset($contract->contract_type)||($contract->contract_type!=3&&$contract->contract_type!=1)) { ?> <div id="consulting" style="display: none"><?php } ?>
                  <?php if(isset($contract->contract_type)&&($contract->contract_type==3||$contract->contract_type==1)) { ?><div id="consulting"><?php } ?>
                    <?php $value = (isset($contract->consulting_client_point) ? $contract->consulting_client_point : ''); ?>
                    <!-- <?php //echo render_textarea('consulting_client_point',_l('consulting_client_point'),$value,array('rows'=>10)); ?> -->
                    <div class="row custom-fields-form-row">
                      <div class="col-md-12">
                          <div class="form-group">
                            <label for="consulting_client_point" class="control-label" style="margin-bottom:9px;"><?php echo _l('consulting_client_point');?></label>
                            <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                              <select data-fieldto="contracts_ser" data-fieldid="12" name="consulting_client_point" id="consulting_client_point" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                                <option value=""></option>
                                <option <?php if($contract->consulting_client_point == 'Allianz') echo 'selected';?> value="Allianz"><?php echo _l('Allianz');?></option>
                                <option <?php if($contract->consulting_client_point == 'Alte Leipziger') echo 'selected';?> value="Alte Leipziger"><?php echo _l('Alte_Leipziger');?></option>
                                <option <?php if($contract->consulting_client_point == 'WWK') echo 'selected';?> value="WWK"><?php echo _l('WWK');?></option>
                                <option <?php if($contract->consulting_client_point == 'Pro Life') echo 'selected';?> value="Pro Life"><?php echo _l('Pro Life');?></option>
                              </select>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>

                  <!--Servicegebührenvereinbarung Payment -->
                  <?php if(!isset($contract->contract_type)||$contract->contract_type!=2) { ?> <div id="contract_ser" style="display: none;"><?php } ?>
                  <?php if(isset($contract->contract_type)&&$contract->contract_type==2) { ?><div id="contract_ser"><?php } ?>
                    <div class="row custom-fields-form-row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="custom_fields[contracts_ser][12]" class="control-label" style="margin-bottom:9px;"><?php echo _l('payment_method');?></label>
                          <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="contracts_ser" data-fieldid="12" name="custom_fields[contracts_ser][12]" id="custom_fields_contracts_ser__12_" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                              <option value=""></option>
                              <option <?php if($contract->service_p_m == 'Bank Transfer') echo 'selected';?> value="Bank Transfer"><?php echo _l('bank_transfer');?></option>
                              <option <?php if($contract->service_p_m == 'Immediate Transfer') echo 'selected';?> value="Immediate Transfer"><?php echo _l('immediate_transfer');?></option>
                              <option <?php if($contract->service_p_m == 'Debit') echo 'selected';?> value="Debit"><?php echo _l('debit');?></option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group"><label for="custom_fields[contracts_ser][13]" class="control-label" style="margin-bottom:9px;"><?php echo _l('payment_timeframe');?></label>
                          <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="contracts_ser" data-fieldid="13" name="custom_fields[contracts_ser][13]" id="custom_fields_contracts_ser__13_" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                              <option value=""></option>
                              <!-- <option <?php //if($contract0['session']['custom_fields']['contracts_ser']['13'] == 'Daily') echo 'selected';?> value="Daily"><?php //echo _l('daily');?></option> -->
                              <option <?php if($contract->service_p_t == 'Quaterly') echo 'selected';?> value="Quaterly"><?php echo _l('quaterly');?></option>
                              <option <?php if($contract->service_p_t == 'Monthly') echo 'selected';?> value="Monthly"><?php echo _l('monthly');?></option>
                              <option <?php if($contract->service_p_t == 'Half-Yearly') echo 'selected';?> value="Half-Yearly"><?php echo _l('half_yearly');?></option>
                              <option <?php if($contract->service_p_t == 'Annually') echo 'selected';?> value="Annually"><?php echo _l('annually');?></option>
                            </select>
                          </div>
                        </div>
                      </div>
                     </div>
                  </div>

                  <!-- Vergütungsvereinbarung Beratung Payment -->
                  <?php if(!isset($contract->contract_type)||$contract->contract_type!=3) { ?> <div id="contracts_beratung" style="display: none;"><?php } ?>
                  <?php if(isset($contract->contract_type)&&$contract->contract_type==3) { ?><div id="contracts_beratung"><?php } ?>
                    <div class="row custom-fields-form-row">
                      <div class="col-md-6">
                        <div class="form-group"><label for="custom_fields[contracts_beratung][13]" class="control-label" style="margin-bottom:9px;"><?php echo _l('remuneration');?></label>
                          <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="contracts_beratung" data-fieldid="13" name="custom_fields[contracts_beratung][13]" id="custom_fields_contracts_beratung__13_" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                              <option value=""></option>
                              <option <?php if($contract->beratung_p == 'One Time Payment') echo 'selected';?> value="One Time Payment"><?php echo _l('one_time_payment');?></option>
                              <option <?php if($contract->beratung_p == 'Payment According To Time Spent') echo 'selected';?> value="Payment According To Time Spent"><?php echo _l('payment_according_to_time_spent');?></option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="custom_fields[contracts_beratung][12]" class="control-label" style="margin-bottom:9px;"><?php echo _l('payment_method');?></label>
                          <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="contracts_beratung" data-fieldid="12" name="custom_fields[contracts_beratung][12]" id="custom_fields_contracts_beratung__12_" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                              <option value=""></option>
                              <option <?php if($contract->beratung_p_m == 'Bank Transfer') echo 'selected';?> value="Bank Transfer"><?php echo _l('bank_transfer');?></option>
                              <option <?php if($contract->beratung_p_m == 'Immediate Transfer') echo 'selected';?> value="Immediate Transfer"><?php echo _l('immediate_transfer');?></option>
                              <option <?php if($contract->beratung_p_m == 'Debit') echo 'selected';?> value="Debit"><?php echo _l('debit');?></option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Nettoprodukt -->
                  <?php if(!isset($contract->contract_type)||$contract->contract_type!=1) { ?> <div id="contracts_produkt" style="display: none;"><?php } ?>
                  <?php if(isset($contract->contract_type)&&$contract->contract_type==1) { ?><div id="contracts_produkt"><?php } ?>
                    <div class="row custom-fields-form-row">
                      <div class="col-md-6" id="remuneration">
                        <div class="form-group"><label for="custom_fields[contracts_produkt][13]" class="control-label" style="margin-bottom:9px;"><?php echo _l('remuneration');?></label>
                          <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="contracts_produkt" data-fieldid="13" name="custom_fields[contracts_produkt][13]" id="custom_fields_contracts_produkt__13_" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
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
                          <label for="one_time_payment_value"><?php echo _l('one_time_payment_value'); ?></label>
                          <div class="input-group" data-toggle="tooltip" title="<?php echo _l('one_time_payment_value'); ?>">
                            <input type="number" class="form-control" name="one_time_payment_value" id="one_time_payment_value" value="<?php if(isset($contract->one_time_payment_value)){echo $contract->one_time_payment_value; }?>">

                            <div class="input-group-addon">
                               <?php echo $base_currency->symbol; ?>
                            </div>
                          </div>
                        </div>
                      </div>

                      <?php if(!isset($contract->produkt_remuneration)||($contract->produkt_remuneration =='One Time Payment')) { ?> <div class="col-md-6" id="savings_amount_per_month" style="display: none;"><?php } ?>
                      <?php if(isset($contract->produkt_remuneration)&&($contract->produkt_remuneration !='One Time Payment')) { ?><div class="col-md-6" id="savings_amount_per_month"><?php } ?>
                        <div class="form-group">
                          <label for="savings_amount_per_month_value"><?php echo _l('savings_amount_per_month_value'); ?></label>
                          <div class="input-group" data-toggle="tooltip" title="<?php echo _l('savings_amount_per_month_value'); ?>">
                            <input type="number" class="form-control calc" name="savings_amount_per_month_value" id="savings_amount_per_month_value" value="<?php if(isset($contract->savings_amount_per_month_value)){echo $contract->savings_amount_per_month_value; }?>">
                            <div class="input-group-addon">
                               <?php echo $base_currency->symbol; ?>
                            </div>
                          </div>
                        </div>
                      </div>

                      <?php if(!isset($contract->produkt_remuneration)||($contract->produkt_remuneration =='One Time Payment')) { ?> <div class="col-md-6" id="term" style="display: none;"><?php } ?>
                      <?php if(isset($contract->produkt_remuneration)&&($contract->produkt_remuneration !='One Time Payment')) { ?><div class="col-md-6" id="term"><?php } ?>
                        <div class="form-group">
                          <label for="term_value"><?php echo _l('term_value'); ?></label>
                          <input type="number" class="form-control calc" name="term_value" id="term_value" value="<?php if(isset($contract->term_value)){echo $contract->term_value; }?>" readonly>
                        </div>
                      </div>

                      <?php if(!isset($contract->produkt_remuneration)||($contract->produkt_remuneration =='One Time Payment')) { ?> <div class="col-md-12" id="amount" style="display: none;"><?php } ?>
                      <?php if(isset($contract->produkt_remuneration)&&($contract->produkt_remuneration !='One Time Payment')) { ?><div class="col-md-12" id="amount"><?php } ?>
                        <div class="form-group">
                          <label for="amount_value"><?php echo _l('amount_value'); ?></label>
                          <div class="input-group" data-toggle="tooltip" title="<?php echo _l('amount_value'); ?>">
                            <input type="number" class="form-control calc" name="amount_value" id="amount_value" value="<?php if(isset($contract->amount_value)){echo $contract->amount_value; }?>" readonly>
                            <div class="input-group-addon">
                               <?php echo $base_currency->symbol; ?>
                            </div>
                          </div>
                        </div>
                      </div>

                      <?php if(!isset($contract->produkt_remuneration)||($contract->produkt_remuneration =='One Time Payment')) { ?> <div class="col-md-6" id="opening_payment" style="display: none;"><?php } ?>
                      <?php if(isset($contract->produkt_remuneration)&&($contract->produkt_remuneration !='One Time Payment')) { ?><div class="col-md-6" id="opening_payment" ><?php } ?>
                        <div class="form-group">
                          <label for="opening_payment_value"><?php echo _l('opening_payment_value'); ?></label>
                          <div class="input-group" data-toggle="tooltip" title="<?php echo _l('opening_payment_value'); ?>">
                            <input type="number" class="form-control calc" name="opening_payment_value" id="opening_payment_value" value="<?php if(isset($contract->opening_payment_value)){echo $contract->opening_payment_value; }?>">
                            <div class="input-group-addon">
                               <?php echo $base_currency->symbol; ?>
                            </div>
                          </div>
                        </div>
                      </div>

                      <?php if(!isset($contract->produkt_remuneration)||($contract->produkt_remuneration =='One Time Payment')) { ?> <div class="col-md-6" id="dynamic_percentage_per_year" style="display: none;"><?php } ?>
                      <?php if(isset($contract->produkt_remuneration)&&($contract->produkt_remuneration !='One Time Payment')) { ?><div class="col-md-6" id="dynamic_percentage_per_year"><?php } ?>
                        <div class="range-control ">
                        <!-- <div class="form-group"> -->
                          <label for="dynamic_percentage_per_year_value"><?php echo _l('dynamic_percentage_per_year_value'); ?></label>
                          <input id="dynamic_percentage_per_year_value" class="calc" name="dynamic_percentage_per_year_value" type="range" min="5" max="30" step="1" value="<?php if(isset($contract->dynamic_percentage_per_year_value)) echo $contract->dynamic_percentage_per_year_value; else echo 5;?>" data-thumbwidth="20" style="margin-top: 5%;margin-bottom: 17%">
                          <output name="rangeVal"><?php if(isset($contract->dynamic_percentage_per_year_value)) echo $contract->dynamic_percentage_per_year_value; else echo 5;?></output>
                        </div>
                      </div>

                      <?php if(!isset($contract->produkt_remuneration)||($contract->produkt_remuneration =='One Time Payment')) { ?> <div class="col-md-12" id="total_amount" style="display: none;"><?php } ?>
                      <?php if(isset($contract->produkt_remuneration)&&($contract->produkt_remuneration !='One Time Payment')) { ?><div class="col-md-12" id="total_amount"><?php } ?>
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

                      <?php if(!isset($contract->produkt_remuneration)||($contract->produkt_remuneration =='One Time Payment')) { ?> <div class="col-md-6" id="agent_remuneration_percent" style="display: none;"><?php } ?>
                      <?php if(isset($contract->produkt_remuneration)&&($contract->produkt_remuneration !='One Time Payment')) { ?><div class="col-md-6" id="agent_remuneration_percent"><?php } ?>
                        <div class="form-group">
                          <label for="agent_remuneration_percent_value"><?php echo _l('agent_remuneration_percent_value'); ?></label>
                          <input type="number" class="form-control calc" name="agent_remuneration_percent_value" id="agent_remuneration_percent_value" value="<?php if(isset($contract)){echo $contract->agent_remuneration_percent_value; }?>">
                        </div>
                      </div>

                      <?php if(!isset($contract->produkt_remuneration)||($contract->produkt_remuneration =='One Time Payment')) { ?> <div class="col-md-6" id="agent_remuneration_price" style="display: none;"><?php } ?>
                      <?php if(isset($contract->produkt_remuneration)&&($contract->produkt_remuneration !='One Time Payment')) { ?><div class="col-md-6" id="agent_remuneration_price"><?php } ?>
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
                        <div class="form-group"><label for="custom_fields[contracts_produkt][14]" class="control-label" style="margin-bottom:9px;"><?php echo _l('payment');?></label>
                          <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="contracts_produkt" data-fieldid="14" name="custom_fields[contracts_produkt][14]" id="custom_fields_contracts_produkt__14_" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                              <option value=""></option>
                              <option <?php if($contract->produkt_p == 'One Time Payment') echo 'selected';?> value="One Time Payment"><?php echo _l('one_time_payment');?></option>
                              <option <?php if($contract->produkt_p == 'Partial Payment') echo 'selected';?> value="Partial Payment"><?php echo _l('partial_payment');?></option>
                              <option <?php if($contract->produkt_p == 'Partial Payment With Increased Starting Payment') echo 'selected';?> value="Partial Payment With Increased Starting Payment"><?php echo _l('partial_payment_with_increased_starting_payment');?></option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="custom_fields[contracts_produkt][12]" class="control-label" style="margin-bottom:9px;"><?php echo _l('payment_method');?></label>
                          <div class="dropdown bootstrap-select form-control bs3" style="width: 100%;">
                            <select data-fieldto="contracts_produkt" data-fieldid="12" name="custom_fields[contracts_produkt][12]" id="custom_fields_contracts_produkt__12_" class="selectpicker form-control" data-width="100%" data-none-selected-text="Nothing selected" data-live-search="true" tabindex="-98">
                              <option value=""></option>
                              <option <?php if($contract->produkt_p_m == 'Bank Transfer') echo 'selected';?> value="Bank Transfer"><?php echo _l('bank_transfer');?></option>
                              <option <?php if($contract->produkt_p_m == 'Immediate Transfer') echo 'selected';?> value="Immediate Transfer"><?php echo _l('immediate_transfer');?></option>
                              <option <?php if($contract->produkt_p_m == 'Debit') echo 'selected';?> value="Debit"><?php echo _l('debit');?></option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>   

                </div>


                <!-- contract value -->
                <?php if(!isset($contract)||$contract->contract_type!=2) { ?> <div id="contract_value_form" style="display: none;"><?php } ?>
                <?php if(isset($contract)&&$contract->contract_type==2) { ?><div  id="contract_value_form" ><?php } ?>
                  <div class="row custom-fields-form-row">
                    <div class="col-md-12">
                      <div class="form-group">
                       <label for="contract_value"><?php echo _l('contract_value'); ?></label>
                       <div class="input-group" data-toggle="tooltip" title="<?php echo _l('contract_value_tooltip'); ?>">
                          <input type="number" class="form-control" name="contract_value" id="contract_value" value="<?php if(isset($contract)){echo $contract->contract_value; }?>">
                          <div class="input-group-addon">
                             <?php echo $base_currency->symbol; ?>
                          </div>
                       </div>
                       <input type="hidden" name="sub_arr" id="sub_arr" value="<?php if(isset($contract->sub_arr))  print_r($contract->sub_arr); else echo "";?>">
                       <input type="hidden" name="sub_tax" id="sub_tax" value="<?php if(isset($contract->sub_tax))  print_r($contract->sub_tax); else echo "";?>">
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

        $('#contract_type').attr("required",true);

        // Customer:
          var customer_array0 = '<?php echo json_encode($customer)?>';
          var customer_array = JSON.parse(customer_array0);
          $('#clientid').change(function(){
            for (var i = 0; i < customer_array.length; i++)
             {
                if(customer_array[i].userid == $('#clientid option:selected').val()){
                  $('#customer').empty();
                  $('#customer').append(customer_array[i].company);
                  // console.log("customer",$('#customer').val())
                  $('#cus_value').val(customer_array[i].company);

                  $('#customer_address').empty();
                  $('#customer_address').append(customer_array[i].address + '</br>'+ customer_array[i].city + '</br>'+customer_array[i].state + '</br>'+customer_array[i].zip + '</br>'+customer_array[i].short_name);
                  $('#cus_addr_value').val(customer_array[i].address + '</br>'+ customer_array[i].city + '</br>'+customer_array[i].state + '</br>'+customer_array[i].zip + '</br>'+customer_array[i].short_name);
                  }

             }
          });

          //fixed customer
          var current_customer_id = $('#clientid').val();
          if (current_customer_id != ''){
            for (var p = 0; p < customer_array.length; p++)
            {
              if (customer_array[p].userid == current_customer_id)
              {
                // console.log(customer_array[p])
                $('#customer').empty();
                $('#customer').append(customer_array[p].company);
                $('#cus_value').val(customer_array[p].company);
                $('#customer_address').empty();
                $('#customer_address').append(customer_array[p].address + '</br>'+ customer_array[p].zip + ', '+customer_array[p].city + '</br>'+customer_array[p].state + ', '+customer_array[p].short_name);
                $('#cus_addr_value').val(customer_array[p].address + '</br>'+ customer_array[p].city + '</br>'+customer_array[p].state + '</br>'+customer_array[p].zip + '</br>'+customer_array[p].short_name);
              }
              
            }
            
          }
        // 
        // Contract type:(#contract_type->contract_type select, #contract&&#contract_ser->other field, #subscrip->subscription div, #contract_ser->payment)
          $('#contract_type').change(function(){
              var create_test = $('#contract_type option:selected').val();
              // console.log("create_test", create_test)
              if(create_test==2) {
                  $('#contract').css("display","none");
                  $('#subscrip').css("display","block");
                  $('#contract_opt').css("display","none");
                  $('#contract_ser').css("display","block");
                  $('#contract_value_form').css("display","block");
                  $('#contracts_beratung').css("display","none");
                  $('#contracts_produkt').css("display","none");
                  $('#consulting').css("display","none");

                  $('#subscription').attr("required",true);
                  $('#contract_value').attr('required',true);
                  $('#custom_fields_contracts_ser__12_').attr("required",true);
                  $('#custom_fields_contracts_ser__13_').attr("required",true);
                  $('#dateend').attr("required",true);
                  $('#description').attr("required",true);
                }
              else if (create_test == 3){
                  $('#contract').css("display","none");
                  $('#subscrip').css("display","none");
                  $('#consulting').css("display","block");
                  $('#contract_opt').css("display","none");
                  $('#contract_ser').css("display","none");
                  $('#contracts_beratung').css("display","block");
                  $('#contracts_produkt').css("display","none");
                  $('#contract_value_form').css("display","none");
                  
                  $('#consulting_client_point').attr('required',true);
                  $('#dateend').attr("required",true);
                  $('#custom_fields_contracts_beratung__12_').attr("required",true);
                  $('#custom_fields_contracts_beratung__13_').attr("required",true);
                  $('#description').removeAttr('required');
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
                  // $('#payment').css("display","none");


                  $('#consulting_client_point').attr('required',true);
                  $('#dateend').attr("required",true);
                  $('#custom_fields_contracts_produkt__12_').attr("required",true);
                  $('#custom_fields_contracts_produkt__13_').attr("required",true);
                  if($('#custom_fields_contracts_produkt__13_ option:selected').val() == 'One Time Payment') $('#one_time_payment').attr('required', true);
                  else if ($('#custom_fields_contracts_produkt__13_ option:selected').val() == 'Partial Payment Of Total Amount') $('#opening_payment').attr('required', true);
                  $('#savings_amount_per_month').attr('required', true);
                  $('#description').removeAttr('required');
              }
              
            });

          

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
           //
          $('#subscription').change(function(){
               var sub = $('#subscription option:selected').val();
               var contract_value;
               for (var i = 0; i < subscription.length; i++)
               {
                  if(subscription[i].id == sub){
                    contract_value = subscription[i].monthly_costs;
                    $('#sub_tax').val(subscription[i].taxrate);
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
            });

          // scroll bar 
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

          });

          // term calculate in produkt
          $('.datepicker').change(function(){

              var month1 = $('#datestart').val().substring(3,5);
              var year1 = $('#datestart').val().substring(6);

              var month2 = $('#dateend').val().substring(3,5);
              var year2 = $('#dateend').val().substring(6);
              
              $('#term_value').val(12*(year2-year1)+parseInt(month2)-parseInt(month1) + 1);
              $('#term_value').prop('readonly',true);

              var open = $('#opening_payment_value').val();
              var saving = $('#savings_amount_per_month_value').val();
              var term = parseInt(month2)-parseInt(month1) + 1;
              var dynamicPercent = $('#dynamic_percentage_per_year_value').val();
              var amount = parseInt(saving)*parseInt(term)*12;
              var totalAmount = amount+amount*parseInt(dynamicPercent)*0.01+parseInt(open);
              var remunerationPercent = $('#agent_remuneration_percent_value').val();

              $('#amount_value').val(amount);
              $('#total_amount_value').val(totalAmount);
              $('#agent_remuneration_price_value').val(totalAmount*remunerationPercent/100);
            });
          

          // amount value calculate
          $('.calc').change(function(){
            
              var open = $('#opening_payment_value').val();
              var saving = $('#savings_amount_per_month_value').val();
              var term = $('#term_value').val();
              var dynamicPercent = $('#dynamic_percentage_per_year_value').val();
              var amount = parseInt(saving)*parseInt(term)*12;
              var totalAmount = amount+amount*parseInt(dynamicPercent)*0.01+parseInt(open);
              var remunerationPercent = $('#agent_remuneration_percent_value').val();

              $('#amount_value').val(amount);
              $('#total_amount_value').val(totalAmount);
              $('#agent_remuneration_price_value').val(totalAmount*remunerationPercent*0.01);
              $('#agent_remuneration_price_value').prop("readonly",true);

          });
          
   });
   

</script>
</body>
</html>
