<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php 
 // print_r($member); exit();
init_head(); ?>
<style type="text/css">
 #divLoading
{
display : none;
}
#divLoading.show
{
display : block;
position : fixed;
z-index: 100;
background-image : url('https://my.dipay.de/uploads/loader.gif');
background-color:#666;
opacity : 0.4;
background-repeat : no-repeat;
background-position : center;
left : 0;
bottom : 0;
right : 0;
top : 0;
}
#loadinggif.show
{
left : 50%;
top : 50%;
position : absolute;
z-index : 101;
width : 32px;
height : 32px;
margin-left : -16px;
margin-top : -16px;
}
#loadinggif.hide
{
  display : none;
}
</style>
<div id="wrapper">
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
                        <input type="text" class="form-control profile" name="firstname" value="<?php if(isset($member)){echo $member->firstname;} ?>" placeholder="John">
                    </div>
                    <div class="form-group">
                        <label for="lastname" class="control-label"><?php echo _l('staff_add_edit_lastname'); ?></label>
                        <input type="text" class="form-control profile" name="lastname" value="<?php if(isset($member)){echo $member->lastname;} ?>" placeholder="Doe" >
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label"><?php echo _l('staff_add_edit_email'); ?></label>
                        <input type="email"<?php if(has_permission('staff','','edit')){ ?> name="email"<?php } else { ?> disabled="true"<?php } ?> class="form-control profile" value="<?php echo $member->email; ?>" id="email" placeholder="JohnDoe@example.com" >
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
                        $selected =( isset($member)&&!empty($member->country) ? $member->country : $customer_default_country);
                        echo render_select('country',$countries,array( 'country_id',array( 'short_name')), 'clients_country',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                        ?>
                        <!-- ///added inputs end -->
                    <?php $value = (isset($member) ? $member->phonenumber : ''); ?>
                    <?php echo render_input('phonenumber','staff_add_edit_phonenumber',$value); ?>

                    <div class="form-group" id="products_list">
                      
                      <div class="checkbox checkbox-primary">
                          <input type="checkbox" class="profile_additional_item" id="index1" name="index1" <?php if(isset($member->index1)&&$member->index1 == 1) echo 'checked';?>>
                          <label for="index1">Finanzanlagenvermittler mit Erlaubnis nach ξ 34f</label> 
                      </div>
                      <div class="checkbox checkbox-primary">
                          <input type="checkbox" class="profile_additional_item" id="index2" name="index2"<?php if(isset($member->index2)&&$member->index2 == 1) echo 'checked';?> >
                          <label  for="">Versicherungsmakler mit Erlaubnis ξ 34d</label> 
                      </div>
                      <div class="checkbox checkbox-primary">
                          <input type="checkbox" class="profile_additional_item" id="index3" name="index3" <?php if(isset($member->index3)&&$member->index3 == 1) echo 'checked';?>>
                          <label for="index3">Immobilienkreditvermittler mit Erlaubnis nach ξ 34i</label> 
                      </div>
                      <div class="checkbox checkbox-primary">
                          <input type="checkbox" class="profile_additional_item" id="index4" name="index4"<?php if(isset($member->index4)&&$member->index4 == 1) echo 'checked';?> >
                          <label  for="index4">Honorar-Finanzanlagenberater ξ 34h</label> 
                      </div>
                      <div class="checkbox checkbox-primary">
                          <input type="checkbox"  id="index5" name="index5" <?php if(isset($member->index5)&&$member->index5 == 1) echo 'checked';?> >
                          <label for="index5">sonstige Personen (z.B.Mitarbeiter, Sekretärin etc.)</label> 
                      </div>
                      
                    </div>
                    <?php if(isset($member)){?>
                    <div class="form-group" id="vermittlernumdiv">
                    <?php }?>
                    <?php if (!isset($member)){?>
                    <div class="form-group" id="vermittlernumdiv" style="display: none;">
                    <?php }?>
                        <label for="vermittlernum" class="control-label">Vermittlernummer</label>
                        <input type="number" name="vermittlernum" class="form-control" value="<?php if(isset($member)) echo $member->vermittlernum; else echo NULL;?>" id="vermittlernum"  >
                    </div>

                    <!-- <div class="form-group">
                        <label for="hourly_rate"><?php echo _l('staff_hourly_rate'); ?></label>
                        <div class="input-group">
                           <input type="number" name="hourly_rate" value="<?php if(isset($member)){echo $member->hourly_rate;} else {echo 0;} ?>" id="hourly_rate" class="form-control">
                           <span class="input-group-addon">
                           <?php echo $base_currency->symbol; ?>
                           </span>
                        </div>
                     </div> -->
                     
                    <!-- <?php if(get_option('disable_language') == 0){ ?>
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
                      <?php } ?> -->
                      <!-- <div class="form-group select-placeholder">
                        <label for="direction"><?php echo _l('document_direction'); ?></label>
                        <select class="selectpicker" data-none-selected-text="<?php echo _l('system_default_string'); ?>" data-width="100%" name="direction" id="direction">
                          <option value="" <?php if(isset($member) && empty($member->direction)){echo 'selected';} ?>></option>
                          <option value="ltr" <?php if(isset($member) && $member->direction == 'ltr'){echo 'selected';} ?>>LTR</option>
                          <option value="rtl" <?php if(isset($member) && $member->direction == 'rtl'){echo 'selected';} ?>>RTL</option>
                      </select>
                      </div> -->
                  
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
             <!-- <button type="submit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button> -->
             <div class="btn-bottom-toolbar text-right" >
                <button type="submit" class="btn btn-info" id="save"><?php echo _l('save');?></button>
             </div>
             <?php echo form_close(); ?>
         </div>
     </div>
 </div>
 <input type="hidden" name="stripe_confirm" id="stripe_confirm" value="<?php if(!empty($stripe_info->id)) echo $stripe_info->id; else echo '';?>">
 <!--for stripe split start-->
<div class="col-md-5">
    <div class="panel_s">
        <div class="panel-body">
          <h4 class="no-margin">
            Stripe Bank Details</h4>
           <hr class="hr-panel-heading">
            <?php echo form_open_multipart('admin/staff/stripe_bank_details',array('id'=>'stripe_bank_details')); ?>
              <div class="form-group">
                <label for="stripe_bank_email" class="control-label"><?php echo _l('staff_add_edit_email'); ?></label>
                <input type="email" name="stripe_bank_email" id="stripe_bank_email"  class="form-control" value="<?php if(isset($stripe_info->stripe_email)) echo $stripe_info->stripe_email; else echo NULL ?>" required >
              </div>
              <div class="form-group">
                <label for="currency" class="control-label">Currency</label>
                <select class="form-control" id="bank_account.currency" name="bank_account.currency">
                  <option value="eur">EUR - Euro</option>
                </select>
              </div>
              <div class="form-group">
                <label for="country" class="control-label">Country of bank account</label>
                <select class="form-control" id="bank_account.country" name="bank_account.country">
                  <option value="DE">Germany</option>
                </select>
              </div>
              <div class="form-group">
                <label for="account_numbers" class="control-label">IBAN</label>
                <input id="account_numbers[account_number]" name="account_numbers" id="account_numbers" placeholder="DE55370400440532014000" type="text" class="form-control" value="<?php if(isset($stripe_info->IBAN)) echo $stripe_info->IBAN; else echo NULL ?>">
              </div>
              <div class="form-group">
                <label for="confirm_account_numbers" class="control-label">Confirm IBAN</label>
               <input id="confirm_account_numbers" name="confirm_account_numbers" id="confirm_account_numbers" placeholder="DE55370400440532014000" type="text" data-rule-equalto="#account_numbers" class="form-control" value="<?php if(isset($stripe_info->IBAN)) echo $stripe_info->IBAN; else echo NULL ?>">
              </div>
              <div class="form-group" id="identityFront">
                <label for="identity_proof" class="control-label">*Identity Proof Front(smaller than 5 mb(JPEG or PNG))</label>
               <input id="identity_proof_front" name="identity_proof_front" placeholder="ID Proof Front Image Url" type="file" class="form-control" accept="image/x-png,image/jpeg">
              </div>
              <div class="form-group" id="identityBack">
                 <label for="identity_proof" class="control-label">*Identity Proof Back(smaller than 5 mb(JPEG or PNG))</label>
                 <input id="identity_proof_back" name="identity_proof_back" placeholder="ID Proof Back Image Url" type="file" class="form-control" accept="image/x-png,image/jpeg">
                 
              </div>
              <div class="form-group" id="addtionalId">
               <label for="identity_proof" class="control-label">*Addtional Proff  (document showing address (JPEG or PNG) smaller than 5 mb)</label>
               <input id="addtional_id_proof" name="addtional_id_proof" placeholder="Addtional Id Proof Image Url" type="file" class="form-control" accept="image/x-png,image/jpeg">
              </div>
              <button type="submit" id="sripeSubmit" class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
          <?php echo form_close(); ?>  
          
              <div id="divLoading" class="loader"></div>
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
</div>
</div>
</div>
<?php init_tail(); ?>
<script>
 $(function(){
   appValidateForm($('#staff_profile_table'),{firstname:'required',lastname:'required',email:'required'});
   appValidateForm($('#staff_password_change_form'),{oldpassword:'required',newpassword:'required',newpasswordr: { equalTo: "#newpassword"}});


   $("#sripeSubmit").prop("disabled", true);

   /*appValidateForm($('#stripe_bank_details'),{account_numbers:'required',confirm_account_numbers:'required',confirm_account_numbers: { equalTo: "#account_numbers"}});*/

//upload identity code start
  var csrf_token='';
  $("#identity_proof_front").change(function(){
    var myform = document.getElementById("stripe_bank_details");
    var stripeForm = new FormData(myform);
    var files = $('#identity_proof_front')[0].files[0];
    var files_size = $('#identity_proof_front')[0].files[0].size; 
    var csrf_token_name = 'csrf_token_name';
    var csrf_token = $('input[name=csrf_token_name]').val();
    var hash = '<?php echo $this->security->get_csrf_hash();?>';
     stripeForm.append('csrf_token_name',csrf_token);
    //alert(files_size);
    if(files_size > 5000000) {
           alert("File must be smaller that 5 mb");
          
          $("div#identityFront").removeClass('has-success has-feedback');
          $("div#identityFront").addClass('has-error has-feedback');
          $("div#identityFront").append('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
          $('#identity_proof_front').val(''); 
          return false;
    };
    $("div#divLoading").addClass('show');
    $.ajax({ 
            url: '<?php echo base_url();?>admin/staff/id_proff_front', 
            type: 'post', 
            data: stripeForm, 
            contentType: false,
            cache: false,
            processData:false,
            success: function(response){ 
                 $("div#divLoading").removeClass('show');
                 var res = JSON.parse(response);
                 if(res.success==1){
                  $("div#identityFront").removeClass('has-error has-feedback');
                  $("div#identityFront span").remove();
                  $("div#identityFront").addClass('has-success has-feedback');
                  $("div#identityFront").append('<span class="glyphicon glyphicon-ok form-control-feedback"></span>');
                  //$('input[name=csrf_token_name]').val(response.token);
                  //alert(res.message);
                 }else{
                  $("div#identityFront").addClass('has-error has-feedback');
                  $("div#identityFront").append('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
                  alert(res.message);
                  //$('input[name=csrf_token_name]').val(response.token);
                 }
                //$('input[name=csrf_token_name]').val(response.token);
            }, 
        });

  });

  $("#identity_proof_back").change(function(){
     
    var myform = document.getElementById("stripe_bank_details");
    var stripeForm = new FormData(myform);
    var files = $('#identity_proof_back')[0].files[0]; 
    var files_size = $('#identity_proof_back')[0].files[0].size; 
    var csrf_token_name = 'csrf_token_name';
    var csrf_token = $('input[name=csrf_token_name]').val();
    var hash = '<?php echo $this->security->get_csrf_hash();?>';
     stripeForm.append('csrf_token_name',csrf_token);


     if(files_size > 5000000) {
            alert("File must be smaller that 5 mb");
            $("div#identityBack").removeClass('has-success has-feedback');
            $("div#identityBack").addClass('has-error has-feedback');
            $("div#identityBack").append('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
            $('#identity_proof_back').val(''); 
            return false;
    };
     $("div#divLoading").addClass('show');
    $.ajax({ 
            url: '<?php echo base_url();?>admin/staff/id_proff_back', 
            type: 'post', 
            data: stripeForm, 
            contentType: false,
            cache: false,
            processData:false,
            success: function(response){ 
                 $("div#divLoading").removeClass('show');
                 var res = JSON.parse(response);
                 if(res.success==1){
                  $("div#identityBack").removeClass('has-error has-feedback');
                  $("div#identityBack span").remove();
                  $("div#identityBack").addClass('has-success has-feedback');
                  $("div#identityBack").append('<span class="glyphicon glyphicon-ok form-control-feedback"></span>');
                 //$('input[name=csrf_token_name]').val(response.token);
                  //alert(res.message);
                 }else{
                  $("div#identityBack").addClass('has-error has-feedback');
                  $("div#identityBack").append('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
                  alert(res.message);
                  //$('input[name=csrf_token_name]').val(response.token);
                 }
                //$('input[name=csrf_token_name]').val(response.token);
            }, 
        });


  });

  $("#addtional_id_proof").change(function(){
      
  
    var myform = document.getElementById("stripe_bank_details");
    var stripeForm = new FormData(myform);
    var files = $('#addtional_id_proof')[0].files[0]; 
    var files_size = $('#addtional_id_proof')[0].files[0].size;
    var csrf_token_name = 'csrf_token_name';
    var csrf_token = $('input[name=csrf_token_name]').val();
    var hash = '<?php echo $this->security->get_csrf_hash();?>';
     stripeForm.append('csrf_token_name',csrf_token);



     if(files_size > 5000000) {
            alert("File must be smaller that 5 mb");
            $("div#addtionalId").removeClass('has-error has-feedback');
            $("div#addtionalId").addClass('has-error has-feedback');
            $("div#addtionalId").append('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
            $('#addtional_id_proof').val(''); 
            return false;
    };
    $("div#divLoading").addClass('show');
    $.ajax({ 
            url: '<?php echo base_url();?>admin/staff/addtional_id_proof', 
            type: 'post', 
            data: stripeForm, 
            contentType: false,
            cache: false,
            processData:false,
            success: function(response){ 
                 $("div#divLoading").removeClass('show');
                 $("#sripeSubmit").prop("disabled", false);
                 var res = JSON.parse(response);
                 if(res.success==1){
                  $("div#addtionalId span").remove();
                  $("div#addtionalId").removeClass('has-error has-feedback');
                  $("div#addtionalId").addClass('has-success has-feedback');
                  $("div#addtionalId").append('<span class="glyphicon glyphicon-ok form-control-feedback"></span>');
                  $('input[name=csrf_token_name]').val(response.token);
                  //alert(res.message);
                 }else{
                   $("div#addtionalId").addClass('has-error has-feedback');
                  $("div#addtionalId").append('<span class="glyphicon glyphicon-remove form-control-feedback"></span>');
                  alert(res.message);
                 }
                //$('input[name=csrf_token_name]').val(response.token);
            }, 
        });
  });
  
//upload identity code end
 });
 $(document).ready(function(){
  document.getElementById("address").placeholder = "Genslerstraße 84";
  document.getElementById("city").placeholder = "Berlin Wedding";
  document.getElementById("state").placeholder = "Berlin";
  document.getElementById("zip").placeholder = "13359";
  document.getElementById("phonenumber").placeholder = "491234567890";

  $('.profile_additional_item').change(function(){
    if($('#index1').prop("checked") || $('#index2').prop("checked") || $('#index3').prop("checked") || $('#index4').prop("checked")) {
      $('#vermittlernumdiv').show();
    }
    else{
      $('#vermittlernumdiv').hide();
    }
  });

  $('.profile').prop('required',true);
  $('#address').prop('required', true);
  $('#city').prop('required', true);
  $('#state').prop('required', true);
  $('#zip').prop('required', true);
  $('#phonenumber').prop('required', true);
 });

 $('#stripe_bank_details').submit(function(){
    $('#stripe_confirm').val(true);
 });

 $('#staff_profile_table').submit(function(e){
    var stripe_confirm = $('#stripe_confirm').val();
    if(!stripe_confirm){
      e.preventDefault();
      alert("complete stripe");  
    }
 })
 
</script>
</body>
</html>

