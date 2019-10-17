<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
  // print_r($client); exit();
 if(is_client_logged_in()) { ?>
<nav class="navbar navbar-default header">
   <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
         <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#theme-navbar-collapse" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
         </button>
         <?php get_company_logo('','navbar-brand logo'); ?>
      </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="theme-navbar-collapse">
         <ul class="nav navbar-nav navbar-right">
        <?php hooks()->do_action('customers_navigation_start'); ?>
        <!-- Standard menu items start -->
        
        <?php if(has_contact_permission('invoices')){ ?>
        <li class="customers-nav-item-invoices"><a class="<?php if($this->uri->segment(2)=="invoices"){echo "active";}?>" href="<?php echo site_url('clients/invoices'); ?>"><i class="fe fe-document" data-toggle="tooltip" title="Invoices"></i><?php if ($this->agent->is_mobile()) { echo '<span class="textmenu">Invoices</span>'; } ?></a></li>
        <?php } ?>
        <?php if(has_contact_permission('contracts')){ ?>
        <li class="customers-nav-item-contracts"><a class="<?php if($this->uri->segment(2)=="contracts"){echo "active";}?>" href="<?php echo site_url('clients/contracts'); ?>"><i class="fe fe-money" data-toggle="tooltip" title="Contracts"></i><?php if ($this->agent->is_mobile()) { echo '<span class="textmenu">Contracts</span>'; } ?></a></li>
        <?php } ?>
        <?php if(has_contact_permission('projects')){ ?>
        <li class="customers-nav-item-projects"><a class="<?php if($this->uri->segment(2)=="projects"){echo "active";}?><?php if($this->uri->segment(2)=="project"){echo "active";}?>" href="<?php echo site_url('clients/projects'); ?>"><i class="fe fe-layer" data-toggle="tooltip" title="Projects"></i><?php if ($this->agent->is_mobile()) { echo '<span class="textmenu">Projects</span>'; } ?></a></li>
        <?php } ?>
        <?php if(has_contact_permission('support')){ ?>
        <li class="customers-nav-item-tickets"><a class="<?php if($this->uri->segment(2)=="tickets"){echo "active";}?><?php if($this->uri->segment(2)=="open_ticket"){echo "active";}?>" href="<?php echo site_url('clients/tickets'); ?>"><i class="fe fe-commenting" data-toggle="tooltip" title="Support"></i><?php if ($this->agent->is_mobile()) { echo '<span class="textmenu">Support</span>'; } ?></a></li>
        <?php } ?>
        <?php if((get_option('use_knowledge_base') == 1 )) { ?>
		<li class="customers-nav-item-kb"><a class="<?php if($this->uri->segment(2)=="knowledge-base"){echo "active";}?><?php if($this->uri->segment(2)=="knowledge-base"){echo "active";}?>" href="<?php echo site_url('knowledge-base'); ?>"><i class="fe fe-question" data-toggle="tooltip" title="Knowledge Base"></i><?php if ($this->agent->is_mobile()) { echo '<span class="textmenu">Knowledge Base</span>'; } ?></a></li>
        <?php } ?>
        <?php if (is_gdpr() == 1) {?>
        <li class="customers-nav-item-gdpr"><a class="<?php if($this->uri->segment(2)=="gdpr"){echo "active";}?><?php if($this->uri->segment(2)=="gdpr"){echo "active";}?>" href="<?php echo site_url('clients/gdpr'); ?>"><i class="fe fe-quote-left" data-toggle="tooltip" title="GDPR"></i><?php if ($this->agent->is_mobile()) { echo '<span class="textmenu">GDPR</span>'; } ?></a></li>
        <?php } ?>
        <!-- Standard menu items end -->
            <?php hooks()->do_action('customers_navigation_end'); ?>
            <?php if(is_client_logged_in()) { ?>
               <li class="dropdown customers-nav-item-profile">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                     <img src="<?php echo contact_profile_image_url($contact->id,'thumb'); ?>" data-toggle="tooltip" data-title="  <?php echo $contact->firstname . ' ' .$contact->lastname; ?>" data-placement="bottom" class="client-profile-image-small mcenter">
                     <span class="caret" style="margin:0 auto;"></span>
                     </a>
                     <ul class="dropdown-menu animated fadeIn">
                        <li class="customers-nav-item-edit-profile">
                           <a href="<?php echo site_url('clients/profile'); ?>">
                              <?php echo _l('clients_nav_profile'); ?>
                           </a>
                        </li>
                        <?php if($contact->is_primary == 1){ ?>
                           <li class="customers-nav-item-company-info">
                              <a href="<?php echo site_url('clients/company'); ?>">
                                 <?php echo _l('client_company_info'); ?>
                              </a>
                           </li>
                        <?php } ?>
                        <?php if(can_logged_in_contact_update_credit_card()){ ?>
                           <li class="customers-nav-item-stripe-card">
                              <a href="<?php echo site_url('clients/credit_card'); ?>">
                                 <?php echo _l('credit_card'); ?>
                              </a>
                           </li>
                        <?php } ?>
                        <li class="customers-nav-item-announcements">
                           <a href="<?php echo site_url('clients/announcements'); ?>">
                           <?php echo _l('announcements'); ?>
                           <?php if($total_undismissed_announcements != 0){ ?>
                              <span class="badge"><?php echo $total_undismissed_announcements; ?></span>
                           <?php } ?>
                        </a>
                     </li>
                     <?php if(can_logged_in_contact_change_language()) {
                        ?>
                        <li class="dropdown-submenu pull-left customers-nav-item-languages">
                           <a href="#" tabindex="-1">
                              <?php echo _l('language'); ?>
                           </a>
                           <ul class="dropdown-menu dropdown-menu-left">
                              <li class="<?php if($client->default_language == ""){echo 'active';} ?>">
                                 <a href="<?php echo site_url('clients/change_language'); ?>">
                                    <?php echo _l('system_default_string'); ?>
                                    </a>
                                 </li>
                                 <?php foreach($this->app->get_available_languages() as $user_lang) { ?>
                                    <li <?php if($client->default_language == $user_lang){echo 'class="active"';} ?>>
                                       <a href="<?php echo site_url('clients/change_language/'.$user_lang); ?>">
                                          <?php echo ucfirst($user_lang); ?>
                                       </a>
                                    </li>
                                 <?php } ?>
                           </ul>
                        </li>
                     <?php } ?>
                     <li class="customers-nav-item-logout">
                        <a href="<?php echo site_url('authentication/logout'); ?>">
                           <?php echo _l('clients_nav_logout'); ?>
                        </a>
                     </li>
                  </ul>
               </li>
            <?php } ?>
            <?php hooks()->do_action('customers_navigation_after_profile'); ?>
         </ul>
      </div>
      <!-- /.navbar-collapse -->
   </div>
   <!-- /.container-fluid -->
</nav>
<?php } else { ?>
<?php if((get_option('use_knowledge_base') == 1 && !is_client_logged_in() && get_option('knowledge_base_without_registration') == 1) || (get_option('use_knowledge_base') == 1 && is_client_logged_in())){ ?>
<nav class="navbar navbar-default header">
   <div class="container-fluid">
      <!-- Brand and toggle get grouped for better mobile display -->
      <div class="navbar-header">
         <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#theme-navbar-collapse" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
         </button>
         <?php get_company_logo('','navbar-brand logo'); ?>
      </div>
      <div class="collapse navbar-collapse" id="theme-navbar-collapse">
         <ul class="nav navbar-nav navbar-right">
        <!-- Standard menu items start -->
		<li class="customers-nav-item-kb"><a class="<?php if($this->uri->segment(2)=="knowledge-base"){echo "active";}?><?php if($this->uri->segment(2)=="knowledge-base"){echo "active";}?>" href="<?php echo site_url('knowledge-base'); ?>"><i class="fe fe-question" data-toggle="tooltip" title="Knowledge Base"></i><?php if ($this->agent->is_mobile()) { echo '<span class="textmenu">Knowledge Base</span>'; } ?></a></li>
        <!-- Standard menu items end -->
         </ul>
      </div>
      <!-- /.navbar-collapse -->
   </div>
   <!-- /.container-fluid -->
</nav>
<?php } } ?>