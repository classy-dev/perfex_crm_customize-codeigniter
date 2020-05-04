<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <!-- <div class="screen-options-area"></div>
    <div class="screen-options-btn">
        <?php //echo _l('dashboard_options'); ?>
    </div> -->
    <?php if ($profile[0]['profile_complete'] == 1){?>
    <div class="content">
        <div class="row">

            <?php $this->load->view('admin/includes/alerts'); ?>

            <?php hooks()->do_action( 'before_start_render_dashboard_content' ); ?>

            <div class="clearfix"></div>

            <div class="col-md-12 mtop30" data-container="top-12">
                <?php //render_dashboard_widgets('top-12'); ?>
            </div>

            <?php hooks()->do_action('after_dashboard_top_container'); ?>

            <div class="col-md-6" data-container="middle-left-6">
                <?php render_dashboard_widgets('middle-left-6'); ?>
            </div>
            <div class="col-md-6" data-container="middle-right-6">
                <?php render_dashboard_widgets('middle-right-6'); ?>
            </div>

            <?php hooks()->do_action('after_dashboard_half_container'); ?>

            <?php if($start_role != 1) { ?>
            <div class="col-md-8" data-container="left-8">
                <?php render_dashboard_widgets('left-8'); ?>
            </div>
            <?php }?>

             <?php if($start_role == 1) {?>
            <div class="col-md-12" data-container="left-8">
                <?php render_dashboard_widgets_case('left-8',$start_role); ?>
            </div>
            <?php }?>

            <?php if($start_role != 1) {?>
            <div class="col-md-4" data-container="right-4">
                <?php render_dashboard_widgets('right-4'); ?>
            </div>
            <?php }?>

            <?php if($start_role == 1) {?>
            <div class="col-md-4" data-container="right-4">
                <?php render_dashboard_widgets_case('right-4',$start_role); ?>
            </div>
            <?php }?>

            <div class="clearfix"></div>

            <div class="col-md-4" data-container="bottom-left-4">
                <?php render_dashboard_widgets('bottom-left-4'); ?>
            </div>
             <div class="col-md-4" data-container="bottom-middle-4">
                <?php render_dashboard_widgets('bottom-middle-4'); ?>
            </div>
            <div class="col-md-4" data-container="bottom-right-4">
                <?php render_dashboard_widgets('bottom-right-4'); ?>
            </div>

            <?php hooks()->do_action('after_dashboard'); ?>
        </div>
    </div>
    <?php }?>
</div>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" style=" width: 380px;margin: auto;border-radius: unset;">
      <div class="modal-body">
        <h2 class="modal-title" style="text-align: center;color: gray;font-weight: unset;margin-top: 20px;"><?php echo _l('welcome_to_dipay'); ?></h2>
        <img src="<?php echo base_url('assets/images/logo.png');?>" style="margin: auto;display: block;width: 50%;margin-top: 15px;margin-bottom: 15px;">
        
        <h4 style="margin-top: 20px;margin-bottom: 25px;font-weight: 500;color: #686464;"><?php echo _l('first_login'); ?></h4> 
        <h4 style="color: #6E6D6D;margin-bottom: 15px;">
             <a href="<?php echo admin_url('staff/edit_profile')?>" style="color: #284DF0"><?php echo _l('complete_profile'); ?></a><?php echo _l('after_complete_profile'); ?>
         </h4>
        <h4 style="color: #6E6D6D;margin-top: 25px;"><?php echo _l('end_speech'); ?></h4>
        <h4 style="color: #6E6D6D;margin-bottom: 30px;"><?php echo _l('end_intro'); ?></h4>
      </div>
    </div>

  </div>
</div>
<script>
    app.calendarIDs = '<?php echo json_encode($google_ids_calendars); ?>';

</script>
<?php init_tail(); ?>
<?php $this->load->view('admin/utilities/calendar_template'); ?>
<?php $this->load->view('admin/dashboard/dashboard_js'); ?>
</body>
</html>

<script type="text/javascript">
    $(document).ready(function(){

        var profile0 = '<?php echo json_encode($profile)?>';
        var profile = JSON.parse(profile0);
        if(profile[0].profile_complete != 1) {
            $('#myModal').modal('show');
        }
        
    });
</script>