<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
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
               <?php echo form_open($this->uri->uri_string()); ?>
               <?php $attrs = (isset($role_type) ? array() : array('autofocus'=>true)); ?>
               <?php $value = (isset($role_type) ? $role_type->role_type_name : ''); ?>
               <?php echo render_input('role_type_name','role_type_add_edit_name',$value,'text',$attrs); ?>
               <hr />
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
     appValidateForm($('form'),{name:'required'});
   });
</script>
</body>
</html>
