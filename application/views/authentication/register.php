<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('authentication/includes/head.php'); ?>
<style type="text/css">
  h4{
    text-align: center;
    font-weight: 300;
  }
</style>
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

         <?php //if(!$email_sent_confirm){?>
          <?php  echo form_open_multipart($this->uri->uri_string(),array('class'=>'staff-form','autocomplete'=>'off'));?>
         <div class="mtop40 authentication-form">
          <?php $this->load->view('authentication/includes/alerts'); ?>
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
              <input type="hidden" name="password" id="password" value="">
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
              </div> -->

              <!-- <div class="form-group">
                <label for="password" class="control-label"><?php echo _l('admin_auth_login_password'); ?></label>
                <input type="password" id="password" name="password" class="form-control" value="<?php if (isset($password)) echo $password;?>" required>
              </div> -->
              
              <div class="form-group">   
                <div class="checkbox checkbox-primary">
                    <input type="checkbox" style="margin-left: 0" id="register_bottom" name="register_bottom" required>
                    <label for="register_bottom">Durch Absenden der Registrierung erkennen Sie die <a href="#" class="terms_policy" style="color: #284DF0">Nutzungsbedingungen</a>, sowie die <a href="#" class="terms_policy" style="color: #284DF0">Datenschutzbestimmungen </a>von
                    DIPAY als Teil des geschlossenen Kaufvertrages an und bestätigen, von Ihrem Widerrufsrecht als Verbraucher
                    Kenntnis genommen zu haben.</label> 
                </div>  
              </div>


             
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
  <div id="emailConfirm" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content" style=" width: 380px;margin: auto;border-radius: unset;margin-top: 35%">
      <div class="modal-body">
        <!-- <h2 class="modal-title" style="text-align: center;color: gray;font-weight: unset;margin-top: 20px;"><?php echo _l('welcome_to_dipay'); ?></h2> -->
        <!-- <img src="<?php echo base_url('assets/images/logo.png');?>" style="margin: auto;display: block;width: 50%;margin-top: 15px;margin-bottom: 15px;"> -->
        
        <h4 style="margin-top: 20px;margin-bottom: 40px;"><?php echo _l('thank_register'); ?></h4> 
        
        <h4 style="margin-top: 25px; margin-bottom: 30px;"><?php echo _l('check_email'); ?></h4>
        <h4 style="margin-bottom: 30px;"><?php echo _l('check_link'); ?></h4>
        <h4 style="margin-bottom: 30px;"><?php echo _l('diapy_team'); ?></h4>
      </div>
    </div>
  </div>
  </div>

  <div id="terms_policy_modal" class="modal fade">
  <div class="modal-dialog">
    <form method="post" id="blocks_form">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            &times;
          </button>
          <h4><?php echo _l('terms_and_policy'); ?></h4>
        </div>
        <div class="modal-body">
          <p class="">
              Terms and Conditions
              <br />
              <!-- <a href="<?php echo terms_url(); ?>" target="_blank"><?php echo terms_url(); ?></a> -->
          </p>
          <?php echo get_option('terms_and_conditions'); ?>
          <hr class="hr-panel-heading" />
          <p class="">
              <i class="fa fa-question-circle" data-toggle="tooltip" data-title="You may want to include the privacy policy in your terms and condtions content."></i> Privacy Policy
              <br />
              <!-- <a href="<?php echo privacy_policy_url(); ?>" target="_blank"><?php echo privacy_policy_url(); ?></a> -->
          </p>
          <?php echo get_option('terms_and_conditions'); ?>
        </div>
        <div class="modal-footer">
          <input type="button" class="btn btn-success" name="accept" id="accept" value="<?php echo _l('accept_policy'); ?>"/>
          <button type="button" class="btn btn-default" id="cancel"><?php echo _l('cancel'); ?></button>
        </div>
      </div>
    </form>
  </div>
  </div>

  <script type="text/javascript">
  $(document).ready(function(){
    // Random Password
    var length = 8,
    charset = "abcdefghijklnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
    retVal = "";
    for (var i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n));
    }
    $('#password').val(retVal);

    //Email Confirm
    var emailSent = '<?php echo $email_sent_confirm;?>';
    if(emailSent){
      $('#emailConfirm').modal('show');
    }
    // Terms and Policy
    $('.terms_policy').click(function(){
      $('#terms_policy_modal').modal('show');
    });

    $('#accept').click(function(){
      $('#register_bottom').prop('checked', true);
      $('#terms_policy_modal').modal('hide');
    })
    $('#cancel').click(function(){
      $('#register_bottom').prop('checked', false);
      $('#terms_policy_modal').modal('hide');
    })

  });
    
</script>
</body>
</html>