<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="mtop15 preview-top-wrapper">
   <div class="row">
      <div class="col-md-3">
         <div class="mbot30">
            <div class="invoice-html-logo">
               <?php echo get_dark_company_logo(); ?>
            </div>
         </div>
      </div>
      <div class="clearfix"></div>
   </div>
   <div class="top" data-sticky data-sticky-class="preview-sticky-header">
      <div class="container preview-sticky-container">
         <div class="row">
            <div class="col-md-12">
               <div class="pull-left">
                  <h3 class="bold no-mtop invoice-html-number no-mbot">
                     <span class="sticky-visible hide">
                     <?php echo format_invoice_number($invoice->id); ?>
                     </span>
                  </h3>
                  <h4 class="invoice-html-status mtop7">
                     <?php echo format_invoice_status($invoice->status,'',true); ?>
                  </h4>
               </div>
               <div class="visible-xs">
                  <div class="clearfix"></div>
               </div>
               <a href="#"
                  class="btn btn-success pull-right mleft5 mtop5 action-button invoice-html-pay-now-top hide sticky-hidden
                  <?php if (($invoice->status != Invoices_model::STATUS_PAID && $invoice->status != Invoices_model::STATUS_CANCELLED
                     && $invoice->total > 0) && found_invoice_mode($payment_modes,$invoice->id, false)){ echo ' pay-now-top'; } ?>">
               <?php echo _l('invoice_html_online_payment_button_text'); ?>
               </a>
               <?php echo form_open($this->uri->uri_string()); ?>
               <button type="submit" name="invoicepdf" value="invoicepdf" class="btn btn-default pull-right action-button mtop5">
               <i class='fa fa-file-pdf-o'></i>
               <?php echo _l('clients_invoice_html_btn_download'); ?>
               </button>
               <?php echo form_close(); ?>
               <?php if(is_client_logged_in() && has_contact_permission('invoices')){ ?>
               <a href="<?php echo site_url('clients/invoices/'); ?>" class="btn btn-default pull-right mtop5 mright5 action-button go-to-portal">
               <?php echo _l('client_go_to_dashboard'); ?>
               </a>
               <?php } ?>
               <div class="clearfix"></div>
            </div>
         </div>
      </div>
   </div>
</div>
<div class="clearfix"></div>
<div class="panel_s mtop20">
   <div class="panel-body">
      <div class="col-md-10 col-md-offset-1">
         <div class="row mtop20">
            <div class="col-md-6 col-sm-6 transaction-html-info-col-left">
               
               <address class="invoice-html-company-info">
                  <?php //echo format_organization_info(); ?>
                  <?php if(isset($staff_client) && $staff_client != null){?>
                     <h3><?php echo $staff_client[0]['staff_name']; ?></h3>
                     <div>
                       <h4><?php echo $staff_client[0]['staff_info']; ?></h> 
                     </div>
                  <?php }?>
               </address>
            </div>
            <div class="col-sm-6 text-right transaction-html-info-col-right">
               <span class="bold invoice-html-bill-to"><?php echo _l('invoice_bill_to'); ?>:</span>
               <address class="invoice-html-customer-billing-info">
                  <?php //echo format_customer_info($invoice, 'invoice', 'billing'); ?>
                   <?php if(isset($staff_client) && $staff_client != null){?>
                     <h4><?php echo $staff_client[0]['cus_value']; ?></h4>
                     <div>
                       <h5><?php echo $staff_client[0]['cus_addr_value']; ?></h5> 
                     </div>
                  <?php }?>
               </address>
               <!-- shipping details -->
               <?php if($invoice->include_shipping == 1 && $invoice->show_shipping_on_invoice == 1){ ?>
               <span class="bold invoice-html-ship-to"><?php echo _l('ship_to'); ?>:</span>
               <address class="invoice-html-customer-shipping-info">
                  <?php echo format_customer_info($invoice, 'invoice', 'shipping'); ?>
               </address>
               <?php } ?>
               <p class="no-mbot invoice-html-date">
                  <span class="bold">
                  <?php echo _l('invoice_data_date'); ?>
                  </span>
                  <?php echo _d($invoice->date); ?>
               </p>
               <h4 class="bold invoice-html-number"><?php echo format_invoice_number($invoice->id); ?></h4>
               <?php //if(!empty($invoice->duedate)){ ?>
               <!-- <p class="no-mbot invoice-html-duedate">
                  <span class="bold"><?php //echo _l('invoice_data_duedate'); ?></span>
                  <?php //echo _d($invoice->duedate); ?>
               </p> -->
               <?php //} ?>
               <?php if($invoice->sale_agent != 0 && get_option('show_sale_agent_on_invoices') == 1){ ?>
               <p class="no-mbot invoice-html-sale-agent">
                  <span class="bold"><?php echo _l('sale_agent_string'); ?>:</span>
                  <?php echo get_staff_full_name($invoice->sale_agent); ?>
               </p>
               <?php } ?>
               <?php if($invoice->project_id != 0 && get_option('show_project_on_invoice') == 1){ ?>
               <p class="no-mbot invoice-html-project">
                  <span class="bold"><?php echo _l('project'); ?>:</span>
                  <?php echo get_project_name_by_id($invoice->project_id); ?>
               </p>
               <?php } ?>
               <?php $pdf_custom_fields = get_custom_fields('invoice',array('show_on_pdf'=>1,'show_on_client_portal'=>1));
                  foreach($pdf_custom_fields as $field){
                    $value = get_custom_field_value($invoice->id,$field['id'],'invoice');
                    if($value == ''){continue;} ?>
               <p class="no-mbot">
                  <span class="bold"><?php echo $field['name']; ?>: </span>
                  <?php echo $value; ?>
               </p>
               <?php } ?>
            </div>
         </div>
         <div class="row">
            <div class="col-md-12">
               <div class="table-responsive">
                  <?php
                     $items = get_items_table_data($invoice, 'invoice');
                     // print_r($items);exit();
                     // echo $items->table();
                     ?>
                  <table class="table items items-preview invoice-items-preview" data-type="invoice">
                     <thead style="background:linear-gradient(to left, #86e259, #0dbddc, #0099ff) !important;">
                        <tr>
                           <!-- <th align="center" style="padding-top: 6px;">
                              <font style="vertical-align: inherit;">
                                 <font style="vertical-align: inherit;">#</font>
                              </font>
                           </th> -->
                           <th class="description" width="50%" align="center">
                              <font style="vertical-align: inherit;">
                                 <font style="vertical-align: inherit;"><?php echo _l('contracts_on_invoice'); ?></font>
                              </font>
                           </th>
                           <!-- <th align="right">
                              <font style="vertical-align: inherit;">
                                 <font style="vertical-align: inherit;"><?php echo _l('tax'); ?>
                                 </font>
                              </font>
                           </th> -->
                           <th align="right">
                              <font style="vertical-align: inherit;">
                                 <font style="vertical-align: inherit;"><?php echo _l('amount_without_tax_on_invoice'); ?></font>
                              </font>
                           </th>
                        </tr>
                     </thead>
                     <?php print_r($contract)?>
                     <tbody>
                        <tr nobr="true">
                           <!-- <td align="center">
                              <font style="vertical-align: inherit;">
                                 <font style="vertical-align: inherit;"><?php
                                 if(isset($invoice) && $invoice != null) echo $invoice->id;  ?></font>
                              </font>
                           </td> -->
                           <td align="center">
                              <font style="vertical-align: inherit;">
                                 <font style="vertical-align: inherit;"><?php if(isset($contract) && $contract != null) echo $contract->type_name;  ?></font>
                                 <?php if($contract->contract_type == 2){?>
                                    <font style="vertical-align: inherit;"><?php if(isset($contract) && $contract != null) echo '('. $contract->subscription_name . ')';  ?></font>
                                 <?php } ?>
                                 <?php if($contract->contract_type == 1){?>
                                    <font style="vertical-align: inherit;"><?php if(isset($contract) && $contract != null) echo '('. $contract->contract_product . ')';  ?></font>
                                 <?php } ?>
                                 <?php if($contract->contract_type == 3){?>
                                    <font style="vertical-align: inherit;"><?php if(!empty($contract->description)) echo '('. $contract->description . ')';  ?></font>
                                 <?php } ?>
                              </font>
                           </td>
                           <!-- <td align="right">
                              <font style="vertical-align: inherit;">
                                 <font style="vertical-align: inherit;"><?php 
                                 // print_r($tax); exit();
                                 if(isset($tax) && $tax[0]['id'] != null) echo $tax[0]['name'].' | '.$tax[0]['taxrate'].'%';  ?></font>
                              </font>
                           </td> -->
                           <td align="right">
                              <font style="vertical-align: inherit;">
                                 <font style="    vertical-align: inherit; margin-right: 15px;"><?php echo _l('excl_tax') ?></font>
                                 <font style="vertical-align: inherit;"><?php
                                 if(isset($invoice) && $invoice != null) echo app_format_money(((float)$invoice->total-(float)$invoice->total_tax), $invoice->currency_name); ;  ?></font>
                              </font>
                           </td>
                        </tr>
                     </tbody>
                  </table>
               </div>
            </div>
            <div class="col-md-6 col-md-offset-6">
               <table class="table text-right">
                  <tbody>
                     <tr id="total_tax">
                        <!-- <td><span class="bold"><?php //echo _l('invoice_subtotal'); ?><?php echo _l('amount_without_tax'); ?></span>
                        </td> -->
                        <?php if($contract->contract_type == 2){?>
                        <td><span class="bold"><?php if(isset($tax) && $tax[0]['id'] != null) echo $tax[0]['name'].' | '.$tax[0]['taxrate'].'%';  ?></span>
                        </td>
                        <?php }?>
                        <?php if($contract->contract_type == 3){?>
                        <td><span class="bold"><?php echo $contract->contract_tax;  ?></span>
                        </td>
                        <?php }?>
                        <td class="total_tax">
                           <?php echo app_format_money($invoice->total_tax, $invoice->currency_name); ?>
                        </td>
                     </tr>
                     <?php if(is_sale_discount_applied($invoice)){ ?>
                     <tr>
                        <td>
                           <span class="bold"><?php echo _l('invoice_discount'); ?>
                           <?php if(is_sale_discount($invoice,'percent')){ ?>
                           (<?php echo app_format_number($invoice->discount_percent,true); ?>%)
                           <?php } ?></span>
                        </td>
                        <td class="discount">
                           <?php echo '-' . app_format_money($invoice->discount_total, $invoice->currency_name); ?>
                        </td>
                     </tr>
                     <?php } ?>
                     <?php
                        foreach($items->taxes() as $tax){
                          echo '<tr class="tax-area"><td class="bold">'.$tax['taxname'].' ('.app_format_number($tax['taxrate']).'%)</td><td>'.app_format_money($tax['total_tax'], $invoice->currency_name).'</td></tr>';
                        }
                        ?>
                     <?php if((int)$invoice->adjustment != 0){ ?>
                     <tr>
                        <td>
                           <span class="bold"><?php echo _l('invoice_adjustment'); ?></span>
                        </td>
                        <td class="adjustment">
                           <?php echo app_format_money($invoice->adjustment, $invoice->currency_name); ?>
                        </td>
                     </tr>
                     <?php } ?>
                     <tr>
                        <td><span class="bold"><?php echo _l('invoice_total'); ?></span>
                        </td>
                        <td class="total">
                           <?php echo app_format_money($invoice->total, $invoice->currency_name); ?>
                        </td>
                     </tr>
                     <!-- <?php if(count($invoice->payments) > 0 && get_option('show_total_paid_on_invoice') == 1){ ?>
                     <tr>
                        <td><span class="bold"><?php echo _l('invoice_total_paid'); ?></span></td>
                        <td>
                           <?php echo '-' . app_format_money(sum_from_table(db_prefix().'invoicepaymentrecords',array('field'=>'amount','where'=>array('invoiceid'=>$invoice->id))), $invoice->currency_name); ?>
                        </td>
                     </tr>
                     <?php } ?>
                     <?php if(get_option('show_credits_applied_on_invoice') == 1 && $credits_applied = total_credits_applied_to_invoice($invoice->id)){ ?>
                     <tr>
                        <td><span class="bold"><?php echo _l('applied_credits'); ?></span></td>
                        <td>
                           <?php echo '-' . app_format_money($credits_applied, $invoice->currency_name); ?>
                        </td>
                     </tr>
                     <?php } ?>
                     <?php if(get_option('show_amount_due_on_invoice') == 1 && $invoice->status != Invoices_model::STATUS_CANCELLED) { ?>
                     <tr>
                        <td><span class="<?php if($invoice->total_left_to_pay > 0){echo 'text-danger ';} ?>bold"><?php //echo _l('invoice_amount_due'); ?><?php echo _l('amount_to_pay'); ?></span></td>
                        <td>
                           <span class="<?php if($invoice->total_left_to_pay > 0){echo 'text-danger';} ?>">
                           <?php echo app_format_money($invoice->total_left_to_pay, $invoice->currency_name); ?>
                           </span>
                        </td>
                     </tr>
                     <?php } ?> -->
                  </tbody>
               </table>
            </div>
            <?php if(get_option('total_to_words_enabled') == 1){ ?>
            <div class="col-md-12 text-center invoice-html-total-to-words">
               <p class="bold no-margin">
                  <?php echo  _l('num_word').': '.$this->numberword->convert($invoice->total, $invoice->currency_name); ?>
               </p>
            </div>
            <?php } ?>
            <?php if(count($invoice->attachments) > 0 && $invoice->visible_attachments_to_customer_found == true){ ?>
            <div class="clearfix"></div>
            <div class="invoice-html-files">
               <div class="col-md-12">
                  <hr />
                  <p class="bold mbot15 font-medium"><?php echo _l('invoice_files'); ?></p>
               </div>
               <?php foreach($invoice->attachments as $attachment){
                  // Do not show hidden attachments to customer
                  if($attachment['visible_to_customer'] == 0){continue;}
                  $attachment_url = site_url('download/file/sales_attachment/'.$attachment['attachment_key']);
                  if(!empty($attachment['external'])){
                  $attachment_url = $attachment['external_link'];
                  }
                  ?>
               <div class="col-md-12 mbot10">
                  <div class="pull-left"><i class="<?php echo get_mime_class($attachment['filetype']); ?>"></i></div>
                  <a href="<?php echo $attachment_url; ?>"><?php echo $attachment['file_name']; ?></a>
               </div>
               <?php } ?>
            </div>
            <?php } ?>
            <?php if(!empty($invoice->clientnote)){ ?>
            <div class="col-md-12 invoice-html-note">
               <b><?php echo _l('invoice_note'); ?></b><br /><br /><?php echo $invoice->clientnote; ?>
            </div>
            <?php } ?>
            <?php if(!empty($invoice->terms)){ ?>
            <div class="col-md-12 invoice-html-terms-and-conditions">
               <hr />
               <b><?php echo _l('terms_and_conditions'); ?></b><br /><br /><?php echo $invoice->terms; ?>
            </div>
            <?php } ?>
            <div class="col-md-12">
               <hr />
            </div>
            <div class="col-md-12 invoice-html-payments">
               <?php
                  $total_payments = count($invoice->payments);
                  if($total_payments > 0){ ?>
               <p class="bold mbot15 font-medium"><?php echo _l('invoice_received_payments'); ?></p>
               <table class="table table-hover invoice-payments-table">
                  <thead>
                     <tr>
                        <th><?php echo _l('invoice_payments_table_number_heading'); ?></th>
                        <th><?php echo _l('invoice_payments_table_mode_heading'); ?></th>
                        <th><?php echo _l('invoice_payments_table_date_heading'); ?></th>
                        <th><?php echo _l('invoice_payments_table_amount_heading'); ?></th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php foreach($invoice->payments as $payment){ ?>
                     <tr>
                        <td>
                           <span class="pull-left"><?php echo $payment['paymentid']; ?></span>
                           <?php echo form_open($this->uri->uri_string()); ?>
                           <button type="submit" value="<?php echo $payment['paymentid']; ?>" class="btn btn-icon btn-default pull-right" name="paymentpdf"><i class="fa fa-file-pdf-o"></i></button>
                           <?php echo form_close(); ?>
                        </td>
                        <td><?php echo $payment['name']; ?> <?php if(!empty($payment['paymentmethod'])){echo ' - '.$payment['paymentmethod']; } ?></td>
                        <td><?php echo _d($payment['date']); ?></td>
                        <td><?php echo app_format_money($payment['amount'], $invoice->currency_name); ?></td>
                     </tr>
                     <?php } ?>
                  </tbody>
               </table>
               <hr />
               <?php } else { ?>
               <h5 class="bold pull-left"><?php echo _l('invoice_no_payments_found'); ?></h5>
               <div class="clearfix"></div>
               <hr />
               <?php } ?>
            </div>
            <?php
               // No payments for paid and cancelled
               if (($invoice->status != Invoices_model::STATUS_PAID
               && $invoice->status != Invoices_model::STATUS_CANCELLED
               && $invoice->total > 0)){ ?>
            <div class="col-md-12">
               <div class="row">
                  <?php
                     $found_online_mode = false;
                     if(found_invoice_mode($payment_modes,$invoice->id,false)) {
                       $found_online_mode = true;
                       ?>
                  <div class="col-md-6 text-left">
                     <!-- <p class="bold mbot15 font-medium"><?php echo _l('invoice_html_online_payment'); ?></p> -->
                     <?php echo form_open($this->uri->uri_string(),array('id'=>'online_payment_form','novalidate'=>true)); ?>
                     <?php foreach($payment_modes as $mode){
                        if(!is_numeric($mode['id']) && !empty($mode['id'])) {
                          if(!is_payment_mode_allowed_for_invoice($mode['id'],$invoice->id)){
                            continue;
                         }
                         ?>
                     <!-- <div class="radio radio-success online-payment-radio">
                        <input type="radio" value="<?php echo $mode['id']; ?>" id="pm_<?php echo $mode['id']; ?>" name="paymentmode">
                        <label for="pm_<?php echo $mode['id']; ?>"><?php echo $mode['name']; ?></label>
                     </div> -->
                     <input type="hidden" value="<?php echo $mode['id']; ?>" id="pm_<?php echo $mode['id']; ?>" name="paymentmode">
                     <?php if(!empty($mode['description'])){ ?>
                     <div class="mbot15">
                        <?php echo $mode['description']; ?>
                     </div>
                     <?php }
                        }
                        } ?>
                     <div class="form-group mtop25">
                        <?php if(get_option('allow_payment_amount_to_be_modified') == 1){ ?>
                        <label for="amount" class="control-label"><?php echo _l('invoice_html_amount'); ?></label>
                        <div class="input-group">
                           <input type="number" required max="<?php echo $invoice->total_left_to_pay; ?>" data-total="<?php echo $invoice->total_left_to_pay; ?>" name="amount" class="form-control" value="<?php echo $invoice->total_left_to_pay; ?>">
                           <span class="input-group-addon">
                           <?php echo $invoice->symbol; ?>
                           </span>
                        </div>
                        <?php } else {
                           echo '<h4 class="bold mbot25">' . _l('invoice_html_total_pay', app_format_money($invoice->total_left_to_pay, $invoice->currency_name)) . '</h4>';
                           }
                        ?>
                     </div>
                     <div id="pay_button">
                        <input
                           id="pay_now"
                           type="submit"
                           name="make_payment"
                           class="btn btn-success"
                           value="<?php echo _l('invoice_html_online_payment_button_text'); ?>">
                     </div>
                     <input type="hidden" name="hash" value="<?php echo $hash; ?>">
                     <?php echo form_close(); ?>
                  </div>
                  <?php } ?>
                  <?php if(found_invoice_mode($payment_modes,$invoice->id)) { ?>
                  <!-- <div class="invoice-html-offline-payments <?php if($found_online_mode == true){echo 'col-md-6 text-right';}else{echo 'col-md-12';};?>">
                     <p class="bold mbot15 font-medium"><?php echo _l('invoice_html_offline_payment'); ?></p>
                     <?php foreach($payment_modes as $mode){
                        if(is_numeric($mode['id'])) {
                          if(!is_payment_mode_allowed_for_invoice($mode['id'],$invoice->id)){
                            continue;
                         }
                         ?>
                     <p class="bold"><?php echo $mode['name']; ?></p>
                     <?php if(!empty($mode['description'])){ ?>
                     <div class="mbot15">
                        <?php echo $mode['description']; ?>
                     </div>
                     <?php }
                        }
                        } ?>
                  </div> -->
                  <?php } ?>
               </div>
            </div>
            <?php } ?>
         </div>
      </div>
   </div>
</div>
<script>
   $(function(){
    new Sticky('[data-sticky]');
    var $payNowTop = $('.pay-now-top');
    if($payNowTop.length && !$('#pay_now').isInViewport()) {
      $payNowTop.removeClass('hide');
      $('.pay-now-top').on('click',function(e){
         e.preventDefault();
         $('html,body').animate({
          scrollTop: $("#online_payment_form").offset().top},
          'slow');
      });
   }

   $('#online_payment_form').appFormValidator();

   var online_payments = $('.online-payment-radio');
   if(online_payments.length == 1){
     online_payments.find('input').prop('checked',true);
   }
   });
</script>
