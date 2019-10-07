<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('authentication/includes/head.php'); ?>
<body class="login_admin"<?php if(is_rtl()){ echo ' dir="rtl"'; } ?> style="background-image: url(<?php echo site_url('/assets/images/1.png') ?>); background-repeat: no-repeat; background-size: 100%,100%;">
 <div class="container">
  <div class="row" style="display: flex;">
    <div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 10%;">
      <div class="col-md-6 col-md-offset-4 col-sm-6 col-xs-6 col-sm-offset-2 authentication-form-wrapper" style="height: 100%">
        <img src="<?php echo site_url('/assets/images/1.png') ?>" style="width: 100%; height: 100%;">
      </div>
     <div class="col-md-6 col-md-offset-4 col-sm-6 col-xs-6 col-sm-offset-2 authentication-form-wrapper" style="height: 100%; background-color: white;display: flex;flex-direction: column;justify-content: space-around;">
        <div class="company-logo">
          <?php get_company_logo(); ?>
        </div>
        <div class="mtop40 authentication-form">
          <h1><?php echo _l('admin_auth_login_heading'); ?></h1>
          <?php $this->load->view('authentication/includes/alerts'); ?>
           <form action="<?php echo admin_url('authentication')?>" method="post">
            <input type="hidden" name="<?php  echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <?php echo validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>
          <?php hooks()->do_action('after_admin_login_form_start'); ?>
          <div class="form-group">
            <label for="email" class="control-label"><?php echo _l('admin_auth_login_email'); ?></label>
            <input type="email" id="email" name="email" class="form-control" autofocus="1" value="<?php if (isset($email)) echo $email;?>">
          </div>
          <div class="form-group">
            <label for="password" class="control-label"><?php echo _l('admin_auth_login_password'); ?></label>
            <input type="password" id="password" name="password" class="form-control" value="<?php if (isset($password)) echo $password;?>"></div>
            <div class="checkbox">
              <label for="remember">
               <input type="checkbox" id="remember" name="remember"> <?php echo _l('admin_auth_login_remember_me'); ?>
             </label>
           </div>

           <div class="_buttons">
            <a href="<?php echo admin_url('authentication/register'); ?>" class="btn btn-info pull-left display-block" style="width: 100%;
            margin-bottom: 5px;"><?php echo _l('admin_auth_register_button'); ?></a>
          </div>

           <div class="form-group">
            <button type="submit" class="btn btn-info btn-block"><?php echo _l('admin_auth_login_button'); ?></button>
          </div>

          <div class="form-group">
            <a href="<?php echo admin_url('authentication/forgot_password'); ?>"><?php echo _l('admin_auth_login_fp'); ?></a>
          </div>
          
          <?php hooks()->do_action('before_admin_login_form_close'); ?>
          <?php echo form_close(); ?>


          <form action="<?php echo admin_url('authentication/trial_payments')?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="<?php  echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <input type="hidden" id="t_email" name="email" class="form-control" autofocus="1" value="<?php if (isset($email)) echo $email;?>">
            <input type="hidden" id="t_password" name="password" class="form-control" value="<?php if (isset($password)) echo $password;?>">

            <?php if (isset($warning)) {?>
          <div class="form-group">
            <?php echo $warning;?>
            <?php echo $buy;?>
          </div>
          <?php }?>
          <?php if(get_option('recaptcha_secret_key') != '' && get_option('recaptcha_site_key') != ''){ ?>
          <div class="g-recaptcha" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
          <?php } ?>
          </form>
        </div>
     </div>
  </div>  
</div>
</div>
</body>
</html>
