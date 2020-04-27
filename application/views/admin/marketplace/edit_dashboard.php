<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php 
  $id = get_staff_user_id();
  // print_r($placeholder);exit();
  init_head(); 
  
?>
<style type="text/css">
  .hr_1{
    border-top:2px solid #bfcbd9;
    margin-top: 20px;
  }
  .hr_2{
    border-margin:2px solid #bfcbd9;
    margin-bottom:  20px;
  }
  .edit_img{
    width: 100%;
    height: 100%;
  }
  .edit_input{
    width: 100%;
    text-align: center;
    margin-left: 32px;
    text-overflow: ellipsis;
  }
    .switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
     
  }

  .switch input { 
    opacity: 0;
    width: 0;
    height: 0;
  }

  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }

  input:checked + .slider {
    background-color: #2196F3;
  }

  input:focus + .slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked + .slider:before {
    -webkit-transform: translateX(26px);
    -ms-transform: translateX(26px);
    transform: translateX(26px);
  }

  /* Rounded sliders */
  .slider.round {
    border-radius: 34px;
  }

  .slider.round:before {
    border-radius: 50%;
  }
</style>
<!-- Edit Page -->
	<div id="wrapper">
    <div class="content">
      <div class="row">
        <form action="<?php echo admin_url('marketplace/change'); ?>" method="post" enctype="multipart/form-data">
          <div class="col-md-12">
            <div class="panel_s">
               <input type="hidden" name="<?php  echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
               
               <div class="panel-body" style="background-color: #0099cc">
                 <div class="col-md-4" style="float: right">
                   <button class="btn btn-success" type="submit" value="Submit" name="submit" style="float: right; width:150px;margin-left: 20px;"><?php echo _l('save')?></button>
                   <button class="btn btn-primary" type ="button" style="float: right; width: 150px;margin-left: 20px;" value="Refresh Page" onClick="window.location.reload();"><?php echo _l('reset')?></button>
                 </div>
               </div>
            </div>
            <div class="panel_s">
              <div class="panel-body">
                <!--  begin  profile content -->
                <!-- Showroom -->
                <div class="col-md-6">
                  <h2 style="margin-left: 30px"><?php echo _l('placeholder_profile')?></h2>
                  <hr class="hr_1">
                  <div class="col-md-12">
                    <div class="col-md-12">
                    <div class="col-md-6">
                      <h4><?php echo _l('showroom_active')?></h4> 
                    </div>
                    <div class="col-md-6">
                      <?php if($placeholder['showroom']==1) {?>
                        <label class="switch" style="margin-left: 40px">
                         <input type="checkbox" checked id="box" name="showroom">
                         <span class="slider round"></span>
                       </label>
                       <?php }?>
                       <?php if($placeholder['showroom']==0) {?>
                         <label class="switch" style="margin-left: 40px">
                           <input type="checkbox"  id="box" name="showroom">       
                           <span class="slider round"></span>
                         </label>
                       <?php }?>
                    </div>
                  </div>
                  <br>
                  <!-- Headline -->
                  <div class="col-md-12" style="margin-top: 20px;">
                    <div class="col-md-4">
                      <h4><?php echo _l('headline')?></h4> 
                    </div>
                    <div class="col-md-6">
                      <input type="text" name="headline" id="headline" value="<?php echo $placeholder['title'];?>" class="edit_input">
                    </div>
                  </div>
                  <br>
                  <!-- Name -->
                  <div class="col-md-12" style="margin-top: 20px;">
                    <div class="col-md-4">
                      <h4><?php echo _l('placeholder_name')?></h4> 
                    </div>
                    <div class="col-md-6">
                      <input type="text" name="name" id="name" value="<?php echo $placeholder['placeholder_name'];?>" class="edit_input">
                    </div>
                  </div>
                  <br>
                  <!-- Subtitle -->
                  <div class="col-md-12" style="margin-top: 20px;">
                    <div class="col-md-4">
                      <h4><?php echo _l('placeholder_subtitle')?></h4> 
                    </div>
                    <div class="col-md-6">
                      <input type="text" name="subtitle" id="subtitle" value="<?php echo $placeholder['sub_title'];?>" class="edit_input">
                    </div>
                  </div>
                  <br>

                  <!-- Email -->
                  <div class="col-md-12" style="margin-top: 20px;">
                    <div class="col-md-4">
                      <h4><?php echo _l('placeholder_email')?></h4> 
                    </div>
                    <div class="col-md-6">
                      <input type="text" name="email" id="email" value="<?php echo $placeholder['email'];?>" class="edit_input">
                    </div>
                  </div>
                  <br>

                  <!-- Logo -->
                  <div class="col-md-12">
                    <hr class="hr_1">
                    <div class="col-md-6">
                      <h4><?php echo _l('placeholder_logo')?></h4> 
                    </div>
                    <div class="col-md-3">
                      <img id="logo_preview" src="<?php echo site_url($placeholder['logo_url']);?>" class="edit_img"/><br/>
                      <br>
                      <input type="file" id="logo_image" name="logo" class="input-image-file" style="display: none;"/>
                      <button type="button" class="btn btn-primary " onclick="changeImage('#logo_image')"><?php echo _l('placeholder_select_image')?></button>
                    </div>
                  </div>
                  <br>
                  <!-- Title -->
                  <div class="col-md-12">
                    <hr class="hr_1">
                    <!-- Title Picture -->
                    <div class="col-md-12" style="margin-bottom: 10px;">
                      <div class="col-md-6" style="padding-left: 0px;">
                        <h4><?php echo _l('placeholder_video_bg')?></h4> 
                      </div>
                      <div class="col-md-3" >
                      <img id="video_preview" src="<?php echo site_url($placeholder['video_pic']);?>" class="edit_img"/><br/>
                      <br>
                       <input type="file" id="video_image" name="video" class="input-image-file" style="display: none;"/>
                       <button type="button" class="btn btn-primary" onclick="changeImage('#video_image')"><?php echo _l('placeholder_select_image')?></button>
                      </div>
                    </div>
                    <!-- Title URL -->
                    <div class="col-md-12" style="padding-left: 0px;">
                      <div class="col-md-7" style="overflow: auto;">
                        <h4><?php echo _l('placeholder_video_url')?></h4>
                      </div>
                      <div class="col-md-4">
                        <input type="text" name="title_video" id="title_video" value="<?php echo $placeholder['video_url'];?>" class="edit_input">
                      </div>
                    </div>
                  </div>
                  <br>
                
                  <div class="col-md-12">
                    <hr class="hr_1">
                    <div class="col-md-6">
                      <h4><?php echo _l('placeholder_title_img')?></h4> 
                    </div>
                    <div class="col-md-3">
                      <img id="title_preview" src="<?php echo site_url($placeholder['title_pic']);?>" class="edit_img"/><br/>
                      <br>
                         <input type="file" id="title_image" name="title" class="input-image-file" style="display: none;" />
                         <button type="button" class="btn btn-primary" onclick="changeImage('#title_image')" ><?php echo _l('placeholder_select_image')?></button>      
                    </div>
                  </div>
                </div>
              </div>
               <!--  end  profile content --> 
               <!--  begin blog content -->
                <div class="col-md-6" id="Blogs">
                   <h2 style="margin-left: 30px"><?php echo _l('placeholder_blogs')?></h2> 
                   <hr class="hr_1">
                  <div class="col-md-12" id="blog_content">

                    <?php for ($i=0; $i < count($blog_component); $i++) { 
                      echo $blog_component[$i];

                    }?>
                    
                  </div>
                <div class="col-md-12">
                  <button type="button" class="btn btn-success" id="add_blog" style="float: right;margin-right: 45px"><i class="fa fa-plus"></i></button>
                </div>
                  
                </div>
               <!--  end blog content -->

               <!--  start product table -->
               <div class="col-md-12">
                <div class="container box" style="margin-top: 40px; width: 100% !important;">
                  <h2 style="text-align: center;" align="center"><?php echo _l('placeholder_products')?></h2>
                  <div class="table-responsive">
                    <button type="button" data-toggle="modal" data-target="#products_Modal" class="btn btn-info btn-xs" style="margin-bottom:10px;"><?php echo _l('placeholder_product_add')?></button>
                    <table id="products" class="table table-bordered table-scriped" style="width: 100% !important">
                      <thead>
                        <tr>
                          <!-- <th >Id</th> -->
                          <th style="width:30%;"><?php echo _l('placeholder_product_name')?></th>
                          <!-- <th style="width:50%;" >Products URL</th>
                          <th style="width:10%;">Edit</th> -->
                          <th style="width:10%;"><?php echo _l('placeholder_product_delete')?></th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
               </div>
               
                <!-- end product table -->
                <!-- start contact -->
                <div class="col-md-12" style="display: flex;justify-content: center;margin-top: 40px">
                  <div class="col-md-8" id="contacts">
                    <h2 style="text-align: center;"><?php echo _l('placeholder_contacts')?></h2> 
                    <hr class="hr_1">
                    <div class="col-md-12" id="contact_content">

                      <?php for ($i=0; $i < count($contact_component); $i++) { 
                        echo $contact_component[$i];

                      }?>
                    </div>
                    <div class="col-md-12">
                      <button type="button" class="btn btn-success" id="add_contact" style="float: right;margin-right: 45px"><i class="fa fa-plus"></i></button>
                    </div>
                  </div>
                </div>
                
                <!-- end contact -->
              </div>
            </div>
          </div>
          <input type="hidden" id="total_id" name="total_id" value="<?php echo $id;?>">
        </form>
      </div> 
    </div>
  </div>
<?php init_tail(); ?>
<div id="products_Modal" class="modal fade">
  <div class="modal-dialog">
    <form method="post" id="products_form">
      <input type="hidden" name="<?php  echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>"/>
      <input type="hidden" id="total_id" name="total_id" value="<?php echo $id;?>">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            &times;
          </button>
          <h4 class="modal-title"><?php echo _l('add_product')?></h4>
        </div>
        <div class="modal-body">
          <label><?php echo _l('chooose_PDF')?></label>
          <input type="file" name="pdf" id="pdf" />
        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-success" name="action" id="action" value="<?php echo _l('placeholder_product_add')?>"/>
          <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close')?></button>
        </div>
      </div>
    </form>
  </div>
</div>


<script type="text/javascript">

function changeImage(target) {
  console.log("target", target)
  $(target).click();
}

$(document).on('change','.input-image-file',function(){
  var imgPath = this.value;
  var ext = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
  if (ext == "gif" || ext == "png" || ext == "jpg" || ext == "jpeg"){
    readURL(this, this.parentNode.children[0].getAttribute('id'));
  }
      
  else
      alert("Please select image file (jpg, jpeg, png).");
});


function readURL(input, preview) {

  if (input.files && input.files[0]) {

      var reader = new FileReader();
      reader.readAsDataURL(input.files[0]);
      reader.onload = function (e) {
          $('#' + preview).attr('src', e.target.result);
      };
  }

}

function reset(){
  location.reload();
}



$(document).ready(function(){
    var total_id = $('#total_id').val();
    // console.log(total_id);
    var new_blog_no = 0;
    var new_contact_no = 0;
    var dataTable = $('#products').DataTable({
      "processing":true,
      "serverSide":true,
      "order":[],
      "ajax":{
        url:"<?php echo admin_url('marketplace/products_load');?>",
        type:"POST",
        dataType : "json",
        data:{
          <?php echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>",total_id:total_id
        },

        dataSrc: function ( data ) {
             $('.dataTables_wrapper').removeClass('table-loading');
             return data.data;
        }
      },    
      "columns" : [
        {data:"product_name"},
        {data:"delete"}
       ]
    });


    // $(document).on('submit','#products_form',function(event)
   // $('#action').click(function()
   // $(document).on('click','#action',function()
   $('#products_form').submit(function(event)
    {
        event.preventDefault();
        var pdf = $('#pdf').val();
        var extension = $('#pdf').val().split('.').pop().toLowerCase();

        if(jQuery.inArray(extension,['pdf']) == -1)
        {
          alert('Invalid PDF File');
          $('#pdf').val('');
          return false;
        }
        else
        {
          $.ajax({
            url:'<?php echo admin_url('marketplace/products_add') ?>',
            method:'POST',
            data:new FormData(this),
            contentType:false,
            processData:false,
            success:function(data)
            {
              alert(data);
              $('#products_Modal').modal('hide');
              dataTable.ajax.reload();
              }
          });
        }
    });
    
    $(document).on('click','.delete',function(){
        var products_id = $(this).attr("id");
        // console.log($(this));
        if (confirm("Are you sure you want to delete this?")) {
          $.ajax({
              url:'<?php echo admin_url('marketplace/products_single_remove') ?>',
              method:'POST',
              data:{<?php  echo $this->security->get_csrf_token_name(); ?> : "<?php echo $this->security->get_csrf_hash(); ?>"},
              success:function(data)
              {
                alert(data);
                dataTable.ajax.reload();
              }
          });
        }
        else{
          return false;
        }
    });

    $('#add_blog').click(function(){
        $('#blog_content').append('<div class="col-md-12 blo-new-container" style="margin-bottom:60px"><div class="col-md-12" ><div class="col-md-6"><h4><?php echo _l('blog_pic')?></h4></div><div class="col-md-3"style="margin-bottom:10px"><img id="blog_new_preview' + new_blog_no + '" src="<?php echo site_url('/assets/images/noimage.jpg')?>" class="edit_img "/><br/><br><input type="file" id="blog_new_image' + new_blog_no + '" name="blog_new_image[]" class="input-image-file" style="display: none;"/><button type="button" class="btn btn-primary" onclick="changeImage('+'\'#blog_new_image'+ new_blog_no +'\')"><?php echo _l('placeholder_select_image')?></button></div></div><div class="col-md-12"><div class="col-md-4"><h4><?php echo _l('blog_headline')?></h4></div><div class="col-md-6"><input type="text" name="blog_new_h[]' + new_blog_no + '" value="" class="edit_input"></div></div><div class="col-md-12"><div class="col-md-4"><h4><?php echo _l('blog_text')?></h4></div><div class="col-md-6" style="overflow:auto; margin-left:32px"><textarea rows="4" cols="48" name="blog_new_t[]'+ new_blog_no +'" ></textarea></div></div><div class="col-md-12"><div class="col-md-4"><h4><?php echo _l('blog_url')?></h4></div><div class="col-md-6"><input type="text" name="blog_new_u[]" value="" class="edit_input"></div><div class="col-md-2"><button type="button" class="btn btn-danger blog-remove"  style="float: right;"><i class="fa fa-minus"></i></button></div></div><hr style="border:1px solid #bfcbd9"><div>');

          new_blog_no++;
    });

    // $('.blog-remove').click(function(){

      $(document).on('click','.blog-remove',function(){
         //alert('dd');
         var first_parent = this.parentNode;
         var second_parent = first_parent.parentNode;
         var third_parent = second_parent.parentNode;
         // console.log(third_parent);
         third_parent.remove();

      });

      $('#add_contact').click(function(){
        $('#contact_content').append('<div class="col-md-12 contact-new-container"><div class="col-md-12" ><div class="col-md-6"><h4><?php echo _l('contact_pic')?></h4></div><div class="col-md-3"style="margin-bottom:10px"><img id="contact_new_preview' + new_contact_no + '" src="<?php echo site_url('/assets/images/noimage.jpg')?>" class="edit_img "/><br/><br><input type="file" id="contact_new_image' + new_contact_no + '" name="contact_new_image[]" class="input-image-file" style="display: none;"/><button type="button" class="btn btn-primary" onclick="changeImage('+'\'#contact_new_image'+ new_contact_no +'\')"><?php echo _l('placeholder_select_image')?></button></div></div><div class="col-md-12"><div class="col-md-4"><h4><?php echo _l('contact_name')?></h4></div><div class="col-md-6"><input type="text" name="contact_new_n[]' + new_contact_no + '" value="" class="edit_input"></div></div><div class="col-md-12"><div class="col-md-4"><h4><?php echo _l('contact_phone')?></h4></div><div class="col-md-6"><input type="text" name="contact_new_p[]'+ new_contact_no +'" value="" class="edit_input" ></div></div><div class="col-md-12"><div class="col-md-4"><h4><?php echo _l('contact_email')?></h4></div><div class="col-md-6"><input type="email" name="contact_new_e[]" value="" class="edit_input"></div><div class="col-md-2"><button type="button" class="btn btn-danger contact-remove"  style="float: right;"><i class="fa fa-minus"></i></button></div></div><hr style="border:1px solid #bfcbd9"><div>');

          new_contact_no++;
    });

      $(document).on('click','.contact-remove',function(){
         //alert('dd');
         var first_parent = this.parentNode;
         var second_parent = first_parent.parentNode;
         var third_parent = second_parent.parentNode;
         // console.log(third_parent);
         third_parent.remove();

      });

  });
 

</script>