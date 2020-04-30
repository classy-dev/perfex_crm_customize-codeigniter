<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget" id="widget-<?php echo basename(__FILE__,".php"); ?>" data-name="<?php echo _l('finance_overview'); ?>">
   <?php if(has_permission('invoices','','view') || has_permission('invoices','','view_own') || (get_option('allow_staff_view_invoices_assigned') == 1 && staff_has_assigned_invoices()) || has_permission('proposals','','view') || has_permission('estimates','','view') || has_permission('estimates','','view_own') || (get_option('allow_staff_view_estimates_assigned') == 1 && staff_has_assigned_estimates()) || has_permission('proposals','','view_own') || (get_option('allow_staff_view_proposals_assigned') == 1 && staff_has_assigned_proposals())){ ?>
   <div class="finance-summary">
      <div class="panel_s">
         <div class="panel-body">
            <div class="widget-dragger"></div>
            <div class="row home-summary">
                  <?php if(has_permission('invoices','','view') || has_permission('invoices','','view_own') || get_option('allow_staff_view_invoices_assigned') == 1 && staff_has_assigned_invoices()){
                  ?>
                  <div class="col-md-6 col-lg-4 col-sm-6">
                     <div class="row">
                        <div class="col-md-12">
                           <p class="text-dark text-uppercase"><?php echo _l('home_invoice_overview'); ?></p>
                           <hr class="mtop15" />
                        </div>
                        <!-- <?php $percent_data = get_invoices_percent_by_status(6); ?> -->
                        <!-- <div class="col-md-12 text-stats-wrapper">
                           <a href="<?php echo admin_url('invoices/list_invoices?status=6'); ?>" class="text-muted mbot15 inline-block">
                              <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span> <?php echo format_invoice_status(6,'',false); ?>
                           </a>
                        </div> -->
                        <!-- <div class="col-md-12 text-right progress-finance-status">
                           <?php echo $percent_data['percent']; ?>%
                           <div class="progress no-margin progress-bar-mini">
                              <div class="progress-bar progress-bar-default no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_data['percent']; ?>">
                              </div>
                           </div>
                        </div> -->
                        <?php $percent_data = get_invoices_percent_by_status('not_sent'); ?>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="<?php echo admin_url('invoices/list_invoices?filter=not_sent'); ?>" class="text-muted inline-block mbot15">
                              <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span> <?php echo _l('not_sent_indicator'); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo $percent_data['percent']; ?>%
                           <div class="progress no-margin progress-bar-mini">
                              <div class="progress-bar progress-bar-default no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_data['percent']; ?>">
                              </div>
                           </div>
                        </div>
                        <?php $percent_data = get_invoices_percent_by_status(1); ?>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="<?php echo admin_url('invoices/list_invoices?status=1'); ?>" class="text-danger mbot15 inline-block">
                              <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span> <?php echo format_invoice_status(1,'',false); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo $percent_data['percent']; ?>%
                           <div class="progress no-margin progress-bar-mini">
                              <div class="progress-bar progress-bar-danger no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_data['percent']; ?>">
                              </div>
                           </div>
                        </div>
                        <?php $percent_data = get_invoices_percent_by_status(3); ?>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="<?php echo admin_url('invoices/list_invoices?status=3'); ?>" class="text-warning mbot15 inline-block">
                              <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span> <?php echo format_invoice_status(3,'',false); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo $percent_data['percent']; ?>%
                           <div class="progress no-margin progress-bar-mini">
                              <div class="progress-bar progress-bar-danger no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_data['percent']; ?>">
                              </div>
                           </div>
                        </div>
                        <?php $percent_data = get_invoices_percent_by_status(4); ?>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="<?php echo admin_url('invoices/list_invoices?status=4'); ?>" class="text-warning mbot15 inline-block">
                              <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span> <?php echo format_invoice_status(4,'',false); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo $percent_data['percent']; ?>%
                           <div class="progress no-margin progress-bar-mini">
                              <div class="progress-bar progress-bar-warning no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_data['percent']; ?>">
                              </div>
                           </div>
                        </div>
                        <?php $percent_data = get_invoices_percent_by_status(2); ?>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="<?php echo admin_url('invoices/list_invoices?status=2'); ?>" class="text-success mbot15 inline-block">
                              <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span> <?php echo format_invoice_status(2,'',false); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo $percent_data['percent']; ?>%
                           <div class="progress no-margin progress-bar-mini">
                              <div class="progress-bar progress-bar-success no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_data['percent']; ?>">
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <?php } ?>

                  <?php //if(!has_permission('contracts','','view')){
                  ?>
                  <div class="col-md-6 col-lg-4 col-sm-6">
                     <div class="row">
                        <div class="col-md-12">
                           <p class="text-dark text-uppercase"><?php echo _l('home_contracts_overview'); ?></p>
                           <hr class="mtop15" />
                        </div>
                        <!-- not sent -->
                        <!-- <?php $percent_data = null; ?>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="" class="text-muted inline-block mbot15">
                              <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span> <?php echo _l('not_sent_indicator'); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo $percent_data['percent']; ?>%
                           <div class="progress no-margin progress-bar-mini">
                              <div class="progress-bar progress-bar-default no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_data['percent']; ?>">
                              </div>
                           </div>
                        </div>
                     
                        <?php $percent_data = null; ?>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="" class="text-info mbot15 inline-block">
                              <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span> <?php echo _l('contracts_sent_overview'); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo $percent_data['percent']; ?>%
                           <div class="progress no-margin progress-bar-mini">
                              <div class="progress-bar progress-bar-info no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_data['percent']; ?>">
                              </div>
                           </div>
                        </div> -->
                        <!-- signed -->
                        <?php $percent_data = get_contracts_percent_by_status(1); ?>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="" class="text-success mbot15 inline-block">
                              <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span> <?php echo _l('contracts_signed_overview'); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo $percent_data['percent']; ?>%
                           <div class="progress no-margin progress-bar-mini">
                              <div class="progress-bar progress-bar-success no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_data['percent']; ?>">
                              </div>
                           </div>
                        </div>
                        <!-- unsigned -->
                        <?php $percent_data = get_contracts_percent_by_status(0); ?>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="" class="text-warning mbot15 inline-block">
                              <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span> <?php echo _l('contracts_unsigned_overview'); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo $percent_data['percent']; ?>%
                           <div class="progress no-margin progress-bar-mini">
                              <div class="progress-bar progress-bar-warning no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_data['percent']; ?>">
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <?php //} ?>



                  <?php //if(!has_permission('projects','','view')){
                  ?>
                  <div class="col-md-6 col-lg-4 col-sm-6">
                     <div class="row">
                        <div class="col-md-12">
                           <p class="text-dark text-uppercase"><?php echo _l('home_timetracking_overview'); ?></p>
                           <hr class="mtop15" />
                        </div>
                  
                        <?php $percent_data = get_timetracking_percent_by_status(2); ?>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="" class="text-info inline-block mbot15">
                              <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span> <?php echo _l('timetrack_running'); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo $percent_data['percent']; ?>%
                           <div class="progress no-margin progress-bar-mini">
                              <div class="progress-bar progress-bar-info no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_data['percent']; ?>">
                              </div>
                           </div>
                        </div>
                        <?php $percent_data = get_timetracking_percent_by_status(5); ?>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="" class="text-success mbot15 inline-block">
                              <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span> <?php echo _l('timetrack_ready'); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo $percent_data['percent']; ?>%
                           <div class="progress no-margin progress-bar-mini">
                              <div class="progress-bar progress-bar-success no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_data['percent']; ?>">
                              </div>
                           </div>
                        </div>
                        <!-- signed -->
                        <?php $percent_data = get_tasks_percent_by_status(4); ?>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="" class="text-info mbot15 inline-block">
                              <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span> <?php echo _l('task_to_do_overview'); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo $percent_data['percent']; ?>%
                           <div class="progress no-margin progress-bar-mini">
                              <div class="progress-bar progress-bar-info no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_data['percent']; ?>">
                              </div>
                           </div>
                        </div>

                        <?php $percent_data = get_tasks_percent_by_status(5); ?>
                        <div class="col-md-12 text-stats-wrapper">
                           <a href="" class="text-success mbot15 inline-block">
                              <span class="_total bold"><?php echo $percent_data['total_by_status']; ?></span> <?php echo _l('task_finished_overview'); ?>
                           </a>
                        </div>
                        <div class="col-md-12 text-right progress-finance-status">
                           <?php echo $percent_data['percent']; ?>%
                           <div class="progress no-margin progress-bar-mini">
                              <div class="progress-bar progress-bar-success no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $percent_data['percent']; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_data['percent']; ?>">
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <?php //} ?>


                  

                  
                  </div>
                  <?php if(has_permission('invoices','','view') || has_permission('invoices','','view_own')){ ?>
                  <hr />
                  <a href="#" class="hide invoices-total initialized"></a>
                  <div id="invoices_total" class="invoices-total-inline">
                     <?php load_invoices_total_template(); ?>
                  </div>
                  <?php } ?>
               </div>
            </div>
         </div>
         <?php } ?>
      </div>

