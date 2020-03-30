<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Modal Contact -->
<div class="modal fade" id="contact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo form_open(admin_url('clients/form_contact/'.$customer_id.'/'.$contactid),array('id'=>'contact-form','autocomplete'=>'off')); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $title; ?><br />
                    <small class="color-white" id="">
                        <?php 
                        if(get_company_name($customer_id,true) == 'company') 
                            { echo get_company_name($customer_id, true); }
                        if (get_company_name($customer_id,true) != 'company') 
                            { 
                                echo get_client_profile($customer_id)->person_firstname.' '.get_client_profile($customer_id)->person_lastname;
                                // echo get_client_profile($customer_id)->firstname;
                            } ?></small></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php if(isset($contact)){ ?>
                        <img src="<?php echo contact_profile_image_url($contact->id,'thumb'); ?>" id="contact-img" class="client-profile-image-thumb">
                        <?php if(!empty($contact->profile_image)){ ?>
                        <a href="#" onclick="delete_contact_profile_image(<?php echo $contact->id; ?>); return false;" class="text-danger pull-right" id="contact-remove-img"><i class="fa fa-remove"></i></a>
                        <?php } ?>
                        <hr />
                        <?php } ?>
                        <div id="contact-profile-image" class="form-group<?php if(isset($contact) && !empty($contact->profile_image)){echo ' hide';} ?>">
                            <label for="profile_image" class="profile-image"><?php echo _l('client_profile_image'); ?></label>
                            <input type="file" name="profile_image" class="form-control" id="profile_image">
                        </div>
                        <?php if(isset($contact)){ ?>
                        <div class="alert alert-warning hide" role="alert" id="contact_proposal_warning">
                            <?php echo _l('proposal_warning_email_change',array(_l('contact_lowercase'),_l('contact_lowercase'),_l('contact_lowercase'))); ?>
                            <hr />
                            <a href="#" id="contact_update_proposals_emails" data-original-email="" onclick="update_all_proposal_emails_linked_to_contact(<?php echo $contact->id; ?>); return false;"><?php echo _l('update_proposal_email_yes'); ?></a>
                            <br />
                            <a href="#" onclick="close_modal_manually('#contact'); return false;"><?php echo _l('update_proposal_email_no'); ?></a>
                        </div>
                        <?php } ?>



                        <!-- // For email exist check -->
                        <?php echo form_hidden('contactid',$contactid); ?>

                        <?php if(get_client_profile($customer_id)->profile_title == 'company'){
                            $client = get_client_profile($customer_id);

                            $value=( isset($client) ? $client->company : '');
                            $attrs = (isset($client) ? array() : array('autofocus'=>true));
                            
                            echo render_input( 'company', 'client_company',$value,'text',$attrs);

                            $value=( isset($client) ? $client->company_form : '');
                            echo render_input( 'company_form', 'client_company_form',$value);

                            $value=( isset($client) ? $client->company_address : '');
                            echo render_textarea( 'company_address', 'client_company_address',$value);

                            $value=( isset($client) ? $client->company_email : '');
                            echo render_input( 'company_email', 'client_company_email',$value,'email');

                            $value=( isset($client) ? $client->company_phonenumber : '');
                            echo render_input( 'company_phonenumber', 'client_company_phonenumber',$value);

                            $value=( isset($client) ? $client->company_commercial_register_number : '');
                            echo render_input( 'company_commercial_register_number', 'client_company_commercial_register_number',$value);

                           } ?>
                        <?php if(isset(get_client_profile($customer_id)->profile_title) && get_client_profile($customer_id)->profile_title != 'company'){

                            $client = get_client_profile($customer_id);

                            $value=( isset($client) ? $client->person_firstname : '');
                            echo render_input( 'person_firstname', 'client_person_firstname',$value);

                            $value=( isset($client) ? $client->person_lastname : ''); 
                            echo render_input( 'person_lastname', 'client_person_lastname',$value);

                            $value=( isset($client) ? $client->person_street : ''); 
                            echo render_input( 'person_street', 'client_person_street',$value);

                            $value=( isset($client) ? $client->person_city : '');
                            echo render_input( 'person_city', 'client_person_city',$value);
                            
                            $value=( isset($client) ? $client->person_email : '');
                            echo render_input( 'person_email', 'client_person_email',$value);

                           } ?>

                        <!-- <?php $value=( isset($contact) ? $contact->firstname : ''); ?>
                        <?php echo render_input( 'firstname', 'client_firstname',$value); ?>
 -->
                        <!-- <?php $value=( isset($contact) ? $contact->lastname : ''); ?>
                        <?php echo render_input( 'lastname', 'client_lastname',$value); ?>

                        <?php $value=( isset($contact) ? $contact->title : ''); ?>
                        <?php echo render_input( 'title', 'contact_position',$value); ?>

                        <?php $value=( isset($contact) ? $contact->email : ''); ?>
                        <?php echo render_input( 'email', 'client_email',$value, 'email'); ?>

                        <?php $value=( isset($contact) ? $contact->phonenumber : ''); ?>
                        <?php echo render_input( 'phonenumber', 'client_phonenumber',$value,'text',array('autocomplete'=>'off')); ?> -->

                        <?php if(!isset($client)){?>
                        <input type="hidden" name="password" id="password" value="">
                        <?php }?>
                    <?php $rel_id=( isset($contact) ? $contact->id : false); ?>
                    <?php echo render_custom_fields( 'contacts',$rel_id); ?>


                    <!-- fake fields are a workaround for chrome autofill getting the wrong fields -->
                    <input  type="text" class="fake-autofill-field" name="fakeusernameremembered" value='' tabindex="-1" />
                    <input  type="password" class="fake-autofill-field" name="fakepasswordremembered" value='' tabindex="-1"/>

                <hr />
                <div class="checkbox checkbox-primary">
                    <input type="checkbox" name="is_primary" id="contact_primary" <?php if((!isset($contact) && total_rows(db_prefix().'contacts',array('is_primary'=>1,'userid'=>$customer_id)) == 0) || (isset($contact) && $contact->is_primary == 1)){echo 'checked';}; ?> <?php if((isset($contact) && total_rows(db_prefix().'contacts',array('is_primary'=>1,'userid'=>$customer_id)) == 1 && $contact->is_primary == 1)){echo 'disabled';} ?>>
                    <label for="contact_primary">
                        <?php echo _l( 'contact_primary'); ?>
                    </label>
                </div>
                <?php if(!isset($contact) && total_rows(db_prefix().'emailtemplates',array('slug'=>'new-client-created','active'=>0)) == 0){ ?>
                <div class="checkbox checkbox-primary">
                    <input type="checkbox" name="donotsendwelcomeemail" id="donotsendwelcomeemail">
                    <label for="donotsendwelcomeemail">
                        <?php echo _l( 'client_do_not_send_welcome_email'); ?>
                    </label>
                </div>
                <?php } ?>
                <!-- <?php if(total_rows(db_prefix().'emailtemplates',array('slug'=>'contact-set-password','active'=>0)) == 0){ ?>
                <div class="checkbox checkbox-primary">
                    <input type="checkbox" name="send_set_password_email" id="send_set_password_email">
                    <label for="send_set_password_email">
                        <?php echo _l( 'client_send_set_password_email'); ?>
                    </label>
                </div>
                <?php } ?> -->
                <hr />
                <p class="bold"><?php echo _l('customer_permissions'); ?></p>
                <p class="text-danger"><?php echo _l('contact_permissions_info'); ?></p>
                <?php
                $default_contact_permissions = array();
                if(!isset($contact)){
                    $default_contact_permissions = @unserialize(get_option('default_contact_permissions'));
                }
                ?>
                <?php foreach($customer_permissions as $permission){ ?>

                <div class="col-md-6 row">
                    <div class="row">
                        <div class="col-md-6 mtop10 border-right">
                            <span><?php echo $permission['name']; ?></span>
                        </div>
                        <div class="col-md-6 mtop10">
                            <div class="onoffswitch">
                                <input type="checkbox" id="<?php echo $permission['id']; ?>" class="onoffswitch-checkbox" <?php if(isset($contact) && has_contact_permission($permission['short_name'],$contact->id) || is_array($default_contact_permissions) && in_array($permission['id'],$default_contact_permissions)){echo 'checked';} ?> value="<?php echo $permission['id']; ?>" name="permissions[]" >
                                <label class="onoffswitch-label" for="<?php echo $permission['id']; ?>"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <?php } ?>
                 <hr />
                <p class="bold"><?php echo _l('email_notifications'); ?><?php if(is_sms_trigger_active()){echo '/SMS';} ?></p>
                <div id="contact_email_notifications">
                <div class="col-md-6 row">
                    <div class="row">
                        <div class="col-md-6 mtop10 border-right">
                            <span><?php echo _l('invoice'); ?></span>
                        </div>
                        <div class="col-md-6 mtop10">
                            <div class="onoffswitch">
                                <input type="checkbox" id="invoice_emails" data-perm-id="1" class="onoffswitch-checkbox" <?php if(isset($contact) && $contact->invoice_emails == '1'){echo 'checked';} ?>  value="invoice_emails" name="invoice_emails">
                                <label class="onoffswitch-label" for="invoice_emails"></label>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="col-md-6 row">
                    <div class="row">
                        <div class="col-md-6 mtop10 border-right">
                            <span><?php //echo _l('estimate'); ?></span>
                        </div>
                        <div class="col-md-6 mtop10">
                            <div class="onoffswitch">
                                <input type="checkbox" id="estimate_emails" data-perm-id="2" class="onoffswitch-checkbox" <?php //if(isset($contact) && $contact->estimate_emails == '1'){echo 'checked';} ?>  value="estimate_emails" name="estimate_emails">
                                <label class="onoffswitch-label" for="estimate_emails"></label>
                            </div>
                        </div>
                    </div>
                </div> -->
                <div class="col-md-6 row">
                    <div class="row">
                        <div class="col-md-6 mtop10 border-right">
                            <span><?php echo _l('credit_note'); ?></span>
                        </div>
                        <div class="col-md-6 mtop10">
                            <div class="onoffswitch">
                                <input type="checkbox" id="credit_note_emails" data-perm-id="3" class="onoffswitch-checkbox" <?php if(isset($contact) && $contact->credit_note_emails == '1'){echo 'checked';} ?>  value="credit_note_emails" name="credit_note_emails">
                                <label class="onoffswitch-label" for="credit_note_emails"></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 row">
                    <div class="row">
                        <div class="col-md-6 mtop10 border-right">
                            <span><?php echo _l('time_tracking'); ?></span>
                        </div>
                        <div class="col-md-6 mtop10">
                            <div class="onoffswitch">
                                <input type="checkbox" id="project_emails" data-perm-id="4" class="onoffswitch-checkbox" <?php if(isset($contact) && $contact->project_emails == '1'){echo 'checked';} ?>  value="project_emails" name="project_emails" >
                                <label class="onoffswitch-label" for="project_emails"></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 row">
                    <div class="row">
                        <div class="col-md-6 mtop10 border-right">
                            <span><?php echo _l('contract'); ?></span>
                        </div>
                        <div class="col-md-6 mtop10">
                            <div class="onoffswitch">
                                <input type="checkbox" id="contract_emails" data-perm-id="2" class="onoffswitch-checkbox" <?php if(isset($contact) && $contact->contract_emails == '1'){echo 'checked';} ?>  value="contract_emails" name="contract_emails">
                                <label class="onoffswitch-label" for="contract_emails"></label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 row">
                    <div class="row">
                        <div class="col-md-6 mtop10 border-right">
                            <span><?php echo _l('tickets'); ?></span>
                        </div>
                        <div class="col-md-6 mtop10">
                            <div class="onoffswitch">
                                <input type="checkbox" id="ticket_emails" data-perm-id="5" class="onoffswitch-checkbox" <?php if(isset($contact) && $contact->ticket_emails == '1'){echo 'checked';} ?>  value="ticket_emails" name="ticket_emails" >
                                <label class="onoffswitch-label" for="ticket_emails"></label>
                            </div>
                        </div>
                        <div class="col-md-6 mtop10 border-right">
                            <span><i class="fa fa-question-circle" data-toggle="tooltip" data-title="<?php echo _l('only_project_tasks'); ?>"></i> <?php echo _l('task'); ?></span>
                        </div>
                        <div class="col-md-6 mtop10">
                            <div class="onoffswitch">
                                <input type="checkbox" id="task_emails" data-perm-id="6" class="onoffswitch-checkbox" <?php if(isset($contact) && $contact->task_emails == '1'){echo 'checked';}?>  value="task_emails" name="task_emails">
                                <label class="onoffswitch-label" for="task_emails"></label>
                            </div>
                        </div>

                    </div>
                </div>
                 
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-info" data-loading-text="<?php echo _l('wait_text'); ?>" autocomplete="off" data-form="#contact-form"><?php echo _l('submit'); ?></button>
    </div>
    <?php echo form_close(); ?>
</div>
</div>
</div>
<?php if(!isset($contact)){ ?>
    <script>
        $(function(){
            // Guess auto email notifications based on the default contact permissios
            var permInputs = $('input[name="permissions[]"]');
            $.each(permInputs,function(i,input){
                input = $(input);
                if(input.prop('checked') === true){
                    $('#contact_email_notifications [data-perm-id="'+input.val()+'"]').prop('checked',true);
                }
            });

            var addInputs = [5,6];
            $.each(addInputs,function(i,dataPermID){
                // console.log(i, dataPermID);
                $('#contact_email_notifications [data-perm-id="'+dataPermID+'"]').prop('checked',true);
            });
        });

    </script>
<?php } ?>
<script>
    $('#company').prop('readonly',true);
    $('#company_form').attr('readonly',true);
    $('#company_address').prop('readonly',true);
    $('#company_email').prop('readonly',true);
    $('#company_phonenumber').prop('readonly',true);
    $('#company_commercial_register_number').prop('readonly',true);

    $('#person_firstname').prop('readonly',true);
    $('#person_lastname').prop('readonly',true);
    $('#person_city').prop('readonly',true);
    $('#person_street').prop('readonly',true);
    $('#person_email').prop('readonly',true);

    var length = 8,
    charset = "abcdefghijklnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789",
    retVal = "";
    for (var i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n));
    }
    console.log(retVal);
    $('#password').val(retVal);

</script>