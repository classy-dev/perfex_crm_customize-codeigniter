<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('authentication/includes/head.php'); ?>

<body class="authentication register" style="background-image: url(<?php echo site_url('/assets/images/1.png') ?>); background-repeat: no-repeat; background-size: 100%,100%;">
  <div class="container">
   <div class="row" style="display: flex;">
    <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 10%;">
      <div class="col-md-6 col-md-offset-4 col-sm-6 col-xs-6 col-sm-offset-2 authentication-form-wrapper" style="height: 100%">
        <img src="<?php echo site_url('/assets/images/1.png') ?>" style="width: 100%; height: 100%;">
      </div>
      <div class="col-md-6 col-md-offset-4 col-sm-6 col-xs-6 col-sm-offset-2 authentication-form-wrapper" style="height: 100%; background-color: white;display: flex;flex-direction: column;justify-content: space-around;">
         <div class="company-logo">
          <?php echo get_company_logo(); ?>
         </div>
          <?php echo form_open_multipart($this->uri->uri_string(),array('class'=>'staff-form','autocomplete'=>'off')); ?>
         <div class="mtop40 authentication-form">

              <div class="form-group" app-field-wrapper="company">
                <label for="company" class="control-label"><?php echo _l('staff_add_edit_company')?></label>
                <input type="text" id="company" name="company" class="form-control" autofocus="1" value="">
              </div>

              <div class="form-group" app-field-wrapper="firstname">
                <label for="firstname" class="control-label"><?php echo _l('staff_add_edit_firstname')?></label>
                <input type="text" id="firstname" name="firstname" class="form-control" autofocus="1" value="" required>
              </div>

              <div class="form-group" app-field-wrapper="lastname">
                <label for="lastname" class="control-label"><?php echo _l('staff_add_edit_lastname')?></label>
                <input type="text" id="lastname" name="lastname" class="form-control" value="" required>
              </div>

              <div class="form-group" app-field-wrapper="email">
                <label for="email" class="control-label"><?php echo _l('staff_add_edit_email')?></label>
                <input type="email" id="email" name="email" class="form-control" autocomplete="off" value="" required>
              </div>

              <!-- <div class="form-group" app-field-wrapper="address">
                <label for="address" class="control-label"><?php echo _l('staff_add_edit_address')?></label>
                <textarea id="address" name="address" class="form-control" rows="4" value="" required ></textarea>
              </div>

              <div class="form-group" app-field-wrapper="city">
                <label for="city" class="control-label"><?php echo _l('staff_add_edit_city')?></label>
                <input type="text" id="city" name="city" class="form-control" value="" required>
              </div>

              <div class="form-group" app-field-wrapper="state">
                <label for="state" class="control-label"><?php echo _l('staff_add_edit_state')?></label>
                <input type="text" id="state" name="state" class="form-control" value="" required>
              </div>

              <div class="form-group" app-field-wrapper="zip">
                <label for="zip" class="control-label"><?php echo _l('staff_add_edit_zip')?></label>
                <input type="text" id="zip" name="zip" class="form-control" value="" required>
              </div>

              <div class="form-group" app-field-wrapper="country">
                <label for="country" class="control-label"><?php echo _l('staff_add_edit_country')?></label>
                <select name="country" data-live-search="true" id="country" class="form-control selectpicker" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
          
                    <?php foreach(get_all_countries() as $country){?>
                      <option value="<?php echo $country['country_id']; ?>" ><?php echo $country['short_name']; ?></option>
                      <?php }?>
                  </select>
              </div>

              <div class="form-group">
                <label for="password" class="control-label"><?php echo _l('admin_auth_login_password'); ?></label>
                <input type="password" id="password" name="password" class="form-control" value="<?php if (isset($password)) echo $password;?>" required>
              </div> -->
            
             
             <div class="btn-bottom-toolbar text-right btn-toolbar-container-out" style="margin-top: 15px">
             <button type="submit" class="btn btn-info" style="margin-right: 8px;"><?php echo _l('submit'); ?></button>
             <a href="<?php echo admin_url('authentication') ?>" class="btn btn-danger"><?php echo _l('cancel'); ?></a>
             </div>
         </div> 
        <?php echo form_close(); ?>
      </div>
    </div>  
    </div>
  </div>
</body>
</html>