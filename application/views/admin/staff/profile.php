<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<?php if ($stripe[0]['stripe_email'] == '' or $stripe[0]['stripe_email'] == null ) echo '<div id="wrapper" style="margin-left:0">' ?>
<?php if ($stripe[0]['stripe_email'] != '' or $stripe[0]['stripe_email'] != null) echo '<div id="wrapper">' ?>
<!-- <div id="wrapper"> -->
    <div class="content">
        <div class="row">
            <div class="col-md-7">
                <div class="panel_s">
                    <div class="panel-body">
                        <h4 class="no-margin">
                           <?php echo $title; ?>
                       </h4>
                       <hr class="hr-panel-heading" />
                       <?php echo form_open_multipart($this->uri->uri_string(),array('id'=>'staff_profile_table','autocomplete'=>'off')); ?>
                       <?php if(total_rows(db_prefix().'emailtemplates',array('slug'=>'two-factor-authentication','active'=>0)) == 0){ ?>
                       <div class="checkbox checkbox-primary">
                         <input type="checkbox" value="1" name="two_factor_auth_enabled" id="two_factor_auth_enabled"<?php if($current_user->two_factor_auth_enabled == 1){echo ' checked';} ?>>
                         <label for="two_factor_auth_enabled"><i class="fa fa-question-circle" data-placement="right" data-toggle="tooltip" data-title="<?php echo _l('two_factor_authentication_info'); ?>"></i>
                         <?php echo _l('enable_two_factor_authentication'); ?></label>
                     </div>
                     <hr />
                     <?php } ?>
                     <?php if($current_user->profile_image == NULL){ ?>
                     <div class="form-group">
                        <label for="profile_image" class="profile-image"><?php echo _l('staff_edit_profile_image'); ?></label>
                        <input type="file" name="profile_image" class="form-control" id="profile_image">
                    </div>
                    <?php } ?>
                    <?php if($current_user->profile_image != NULL){ ?>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-9">
                                <?php echo staff_profile_image($current_user->staffid,array('img','img-responsive','staff-profile-image-thumb'),'thumb'); ?>
                            </div>
                            <div class="col-md-3 text-right">
                                <a href="<?php echo admin_url('staff/remove_staff_profile_image'); ?>"><i class="fa fa-remove"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="firstname" class="control-label"><?php echo _l('staff_add_edit_firstname'); ?></label>
                        <input type="text" class="form-control" name="firstname" value="<?php if(isset($member)){echo $member->firstname;} ?>">
                    </div>
                    <div class="form-group">
                        <label for="lastname" class="control-label"><?php echo _l('staff_add_edit_lastname'); ?></label>
                        <input type="text" class="form-control" name="lastname" value="<?php if(isset($member)){echo $member->lastname;} ?>">
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label"><?php echo _l('staff_add_edit_email'); ?></label>
                        <input type="email"<?php if(has_permission('staff','','edit')){ ?> name="email"<?php } else { ?> disabled="true"<?php } ?> class="form-control" value="<?php echo $member->email; ?>" id="email">
                    </div>
                    <!-- added inputs -->
                    <?php $value=( isset($member) ? $member->address : ''); ?>
                     <?php echo render_textarea( 'address', 'client_address',$value); ?>
                     <?php $value=( isset($member) ? $member->city : ''); ?>
                     <?php echo render_input( 'city', 'client_city',$value); ?>
                     <?php $value=( isset($member) ? $member->state : ''); ?>
                     <?php echo render_input( 'state', 'client_state',$value); ?>
                     <?php $value=( isset($member) ? $member->zip : ''); ?>
                     <?php echo render_input( 'zip', 'client_postal_code',$value); ?>

                     <?php $countries= get_all_countries();
                        $customer_default_country = get_option('customer_default_country');
                        $selected =( isset($member) ? $member->country : $customer_default_country);
                        echo render_select('country',$countries,array( 'country_id',array( 'short_name')), 'clients_country',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                        ?>
                        <!-- ///added inputs end -->
                    <?php $value = (isset($member) ? $member->phonenumber : ''); ?>
                    <?php echo render_input('phonenumber','staff_add_edit_phonenumber',$value); ?>
                    <?php if(get_option('disable_language') == 0){ ?>
                    <div class="form-group">
                        <label for="country" class="control-label"><?php echo _l('localization_default_language'); ?></label>
                        <select name="default_language" data-live-search="true" id="default_language" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                            <option value=""><?php echo _l('system_default_string'); ?></option>
                            <?php foreach($this->app->get_available_languages() as $availableLanguage){
                                $selected = '';
                                if(isset($member)){
                                   if($member->default_language == $availableLanguage){
                                      $selected = 'selected';
                                  }
                              }
                              ?>
                              <option value="<?php echo $availableLanguage; ?>" <?php echo $selected; ?>><?php echo ucfirst($availableLanguage); ?></option>
                              <?php } ?>
                          </select>
                      </div>
                      <?php } ?>
                      <div class="form-group select-placeholder">
                        <label for="direction"><?php echo _l('document_direction'); ?></label>
                        <select class="selectpicker" data-none-selected-text="<?php echo _l('system_default_string'); ?>" data-width="100%" name="direction" id="direction">
                          <option value="" <?php if(isset($member) && empty($member->direction)){echo 'selected';} ?>></option>
                          <option value="ltr" <?php if(isset($member) && $member->direction == 'ltr'){echo 'selected';} ?>>LTR</option>
                          <option value="rtl" <?php if(isset($member) && $member->direction == 'rtl'){echo 'selected';} ?>>RTL</option>
                      </select>
                  </div>
                  
                <i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('staff_email_signature_help'); ?>"></i>
                <?php $value = (isset($member) ? $member->email_signature : ''); ?>
                <?php echo render_textarea('email_signature','settings_email_signature',$value, ['data-entities-encode'=>'true']); ?>
                <?php if(count($staff_departments) > 0){ ?>
                <div class="form-group">
                    <label for="departments"><?php echo _l('staff_edit_profile_your_departments'); ?></label>
                    <div class="clearfix"></div>
                    <?php
                    foreach($departments as $department){ ?>
                    <?php
                    foreach ($staff_departments as $staff_department) {
                     if($staff_department['departmentid'] == $department['departmentid']){ ?>
                     <div class="chip-circle mtop20"><?php echo $staff_department['name']; ?></div>
                     <?php }
                 }

                 ?>
                 <?php } ?>
             </div>
             <?php } ?>
             <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
             <?php echo form_close(); ?>
         </div>
     </div>
 </div>
 <div class="col-md-5">
    <div class="panel_s">

        <div class="panel-body">
           <h4 class="no-margin">
            <?php echo _l('staff_edit_profile_change_your_password'); ?>
        </h4>
        <hr class="hr-panel-heading" />
        <?php echo form_open('admin/staff/change_password_profile',array('id'=>'staff_password_change_form')); ?>
        <div class="form-group">
            <label for="oldpassword" class="control-label"><?php echo _l('staff_edit_profile_change_old_password'); ?></label>
            <input type="password" class="form-control" name="oldpassword" id="oldpassword">
        </div>
        <div class="form-group">
            <label for="newpassword" class="control-label"><?php echo _l('staff_edit_profile_change_new_password'); ?></label>
            <input type="password" class="form-control" id="newpassword" name="newpassword">
        </div>
        <div class="form-group">
            <label for="newpasswordr" class="control-label"><?php echo _l('staff_edit_profile_change_repeat_new_password'); ?></label>
            <input type="password" class="form-control" id="newpasswordr" name="newpasswordr">
        </div>
        <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
        <?php echo form_close(); ?>
    </div>
    <?php if($member->last_password_change != NULL){ ?>
    <div class="panel-footer">
        <?php echo _l('staff_add_edit_password_last_changed'); ?>:
        <span class="text-has-action" data-toggle="tooltip" data-title="<?php echo _dt($member->last_password_change); ?>">
        <?php echo time_ago($member->last_password_change); ?>
      </span>
    </div>
    <?php } ?>
</div>
</div>
<div class="col-md-5">
    <div class="panel_s">
        <div class="panel-body">
            <img src="<?php echo site_url('assets/images/stripe.png')?>">
            <!-- <form method="post" action="<?php echo admin_url('staff/stripe_info')?>"> -->
            <?php echo form_open('admin/staff/stripe_info',array('id'=>'stripe_info')); ?>
              <div class="form-group">
                <label for="stripe_email" class="control-label"><?php echo _l('stripe_email'); ?></label>
                <input type="email" name="stripe_email" id="stripe_email"  class="form-control" value="<?php if(isset($member->stripe_email)) echo $member->stripe_email; else echo NULL ?>" required >
              </div>

              <div class="form-group">
                <label for="stripe_pasword" class="control-label"><?php echo _l('stripe_password'); ?></label>
                <input type="password" name="stripe_password"  class="form-control" value="<?php //if(isset($member->stripe_password)) echo $member->stripe_password; 
                      //else echo NULL ?>" id="stripe_password" required>
              </div>
              <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
          <?php echo form_close(); ?>   
        </div>
    </div>
</div>
</div>
</div>
</div>
<?php init_tail(); ?>
<script>
 $(function(){
   appValidateForm($('#staff_profile_table'),{firstname:'required',lastname:'required',email:'required'});
   appValidateForm($('#staff_password_change_form'),{oldpassword:'required',newpassword:'required',newpasswordr: { equalTo: "#newpassword"}});
 });
</script>
</body>
</html>

