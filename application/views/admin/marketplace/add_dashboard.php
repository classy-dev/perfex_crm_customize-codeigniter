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
<!-- Add Page -->
  <div id="wrapper">
    <div class="content">
      <div class="row">
        <form action="<?php echo admin_url('marketplace/add'); ?>" method="post" enctype="multipart/form-data">
          <div class="col-md-12">
            <div class="panel_s">
               <input type="hidden" name="<?php  echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
               
               <div class="panel-body" style="background-color: #0099cc">
                 <div class="col-md-4" style="float: right">
                   <button class="btn btn-success" type="submit" value="Submit" name="submit" style="float: right; width:150px;margin-left: 20px;">save changes</button>
                   <button class="btn btn-primary" type ="button" style="float: right; width: 150px;margin-left: 20px;" value="Refresh Page" onClick="window.location.reload();">reset options</button>
                 </div>
               </div>
            </div>
            <div class="panel_s">
              <div class="panel-body">
                <!--  begin  profile content -->
                <!-- showroom -->
                <div class="col-md-6">
                  <h2 style="margin-left: 30px">Placeholder Profile</h2>
                  <div class="col-md-12" >
                    <hr class="hr_1">
                    <div class="col-md-12"  >
                    
                    <div class="col-md-6">
                      <h4>Showrooom active</h4> 
                    </div>
                    <div class="col-md-6">
                      <label class="switch" style="margin-left: 40px">
                         <input type="checkbox"  id="box" name="showroom">       
                         <span class="slider round"></span>
                        </label>
                    </div>
                  </div>
                  <br>
                  <!-- Headline -->
                  <div class="col-md-12" style="margin-top: 20px;">
                    <div class="col-md-4">
                      <h4>Headline</h4> 
                    </div>
                    <div class="col-md-6">
                      <input type="text" name="headline" id="headline" value="" class="edit_input">
                    </div>
                  </div>
                  <br>
                  <!-- Name -->
                  <div class="col-md-12" style="margin-top: 20px;">
                    <div class="col-md-4">
                      <h4>Name</h4> 
                    </div>
                    <div class="col-md-6">
                      <input type="text" name="name" id="name" value="" class="edit_input">
                    </div>
                  </div>
                  <br>
                  <!-- Subtitle -->
                  <div class="col-md-12" style="margin-top: 20px;">
                    <div class="col-md-4">
                      <h4>Subtitle</h4> 
                    </div>
                    <div class="col-md-6">
                      <input type="text" name="subtitle" id="subtitle" value="" class="edit_input">
                    </div>
                  </div>
                  <br>
                  <!-- Email -->
                  <div class="col-md-12" style="margin-top: 20px;">
                    <div class="col-md-4">
                      <h4>My Email</h4> 
                    </div>
                    <div class="col-md-6">
                      <input type="text" name="email" id="email" value="" class="edit_input">
                    </div>
                  </div>
                  <br>
                  <!-- Logo -->
                  <div class="col-md-12">
                    <hr class="hr_1">
                    <div class="col-md-6">
                      <h4>Logo Image</h4> 
                    </div>
                    <div class="col-md-3">
                      <img id="logo_preview" src="<?php echo site_url('/assets/images/noimage.jpg');?>" class="edit_img"/><br/>
                      <br>
                      <input type="file" id="logo_image" name="logo" class="input-image-file" style="display: none;"/>
                      <button type="button" class="btn btn-primary " onclick="changeImage('#logo_image')">Select a Image</button>
                    </div>
                  </div>
                  <br>
                  <!-- Video -->
                  <div class="col-md-12">
                    <hr class="hr_1">
                    <!-- Video Picture -->
                    <div class="col-md-12" style="margin-bottom: 10px;">
                      <div class="col-md-6" style="padding-left: 0px;">
                        <h4>Video BG-Picture</h4> 
                      </div>
                      <div class="col-md-3" >
                      <img id="video_preview" src="<?php echo site_url('/assets/images/noimage.jpg');?>" class="edit_img"/><br/>
                      <br>
                       <input type="file" id="video_image" name="video" class="input-image-file" style="display: none;"/>
                       <button type="button" class="btn btn-primary" onclick="changeImage('#video_image')">Select a Image</button>
                      </div>
                    </div>
                    <!-- Video URL -->
                    <div class="col-md-12" style="padding-left: 0px;">
                      <div class="col-md-7">
                        <h4>Video URL(https://www.youtube.com/embed/)</h4>
                      </div>
                      <div class="col-md-4">
                        <input type="text" name="title_video" id="title_video" value="" class="edit_input">
                      </div>
                    </div>
                  </div>
                  <br>
                  <!-- Profile -->
                  <div class="col-md-12">
                    <hr class="hr_1">
                    <div class="col-md-6">
                      <h4>Profile Picture</h4> 
                    </div>
                    <div class="col-md-3">
                      <img id="profile_preview" src="<?php echo site_url('/assets/images/noimage.jpg');?>" class="edit_img"/><br/>
                      <br>
                         <input type="file" id="profile_image" name="profile" class="input-image-file" style="display: none;" />
                         <button type="button" class="btn btn-primary" onclick="changeImage('#profile_image')" >Select a Image</button>      
                    </div>
                  </div>
                  <!-- title picture -->
                  <div class="col-md-12">
                    <hr class="hr_1">
                    <div class="col-md-6">
                      <h4>Title Image</h4> 
                    </div>
                    <div class="col-md-3">
                      <img id="title_preview" src="<?php echo site_url('/assets/images/noimage.jpg');?>" class="edit_img"/><br/>
                      <br>
                         <input type="file" id="title_image" name="title" class="input-image-file" style="display: none;" />
                         <button type="button" class="btn btn-primary" onclick="changeImage('#title_image')" >Select a Image</button>      
                    </div>
                  </div>
                </div> 
                </div>
               <!--  end  profile content --> 
               <!--  begin blog content -->
                <div class="col-md-6" id="Blogs">
                   <h2 style="margin-left: 30px">Placeholder Blogs</h2>
                 
                  <div class="col-md-12"  id="blog_content">
                     <hr class="hr_1"> 
                    <?php 
                      if(isset($blog_component))
                      for ($i=0; $i < count($blog_component); $i++) { 
                        echo $blog_component[$i];
                    }?>
                    
                  </div>
                  <div class="col-md-12">
                  <button type="button" class="btn btn-success" id="add_blog" style="float: right;margin-right: 45px"><i class="fa fa-plus"></i></button>
                </div>
                  
                </div>
               <!--  end blog content -->
                <!-- start contact -->
                <div class="col-md-12" style="display: flex;justify-content: center;margin-top: 40px">
                  <div class="col-md-8" id="contacts">
                    <h2 style="text-align: center;">Placeholder Contacts</h2> 
                    <hr class="hr_1">
                    <div class="col-md-12" id="contact_content">

                      <?php 
                        if(isset($contact_component))
                        for ($i=0; $i < count($contact_component); $i++) { 
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

<script type="text/javascript">
//profile and blog      

        function changeImage(target) {
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

        // $('.input-image-file').change(function () {
        //         // alert('dd');

        //     });

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
    var new_blog_no = 0;
    var new_contact_no = 0;

      $('#add_blog').click(function(){
        $('#blog_content').append('<div class="col-md-12 blo-new-container" style="margin-bottom:60px"><div class="col-md-12" ><div class="col-md-6"><h4>Blog Picture</h4></div><div class="col-md-3"style="margin-bottom:10px"><img id="blog_new_preview' + new_blog_no + '" src="<?php echo site_url('/assets/images/noimage.jpg')?>" class="edit_img "/><br/><br><input type="file" id="blog_new_image' + new_blog_no + '" name="blog_new_image[]" class="input-image-file" style="display: none;"/><button type="button" class="btn btn-primary" onclick="changeImage('+'\'#blog_new_image'+ new_blog_no +'\')">Select a Image</button></div></div><div class="col-md-12"><div class="col-md-4"><h4>Blog Headline</h4></div><div class="col-md-6"><input type="text" name="blog_new_h[]' + new_blog_no + '" value="" class="edit_input"></div></div><div class="col-md-12"><div class="col-md-4"><h4>Blog Text</h4></div><div class="col-md-6" style="padding-left:47px"><textarea rows="4" cols="48" name="blog_new_t[]' + new_blog_no + '"></textarea></div></div><div class="col-md-12"><div class="col-md-4"><h4>Blog URL</h4></div><div class="col-md-6"><input type="text" name="blog_new_u[]" value="" class="edit_input"></div><div class="col-md-2"><button type="button" class="btn btn-danger blog-remove"  style="float: right;"><i class="fa fa-minus"></i></button></div></div><hr style="border:1px solid #bfcbd9"><div>');
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
        $('#contact_content').append('<div class="col-md-12 contact-new-container"><div class="col-md-12" ><div class="col-md-6"><h4>Contact Picture</h4></div><div class="col-md-3"style="margin-bottom:10px"><img id="contact_new_preview' + new_contact_no + '" src="<?php echo site_url('/assets/images/noimage.jpg')?>" class="edit_img "/><br/><br><input type="file" id="contact_new_image' + new_contact_no + '" name="contact_new_image[]" class="input-image-file" style="display: none;"/><button type="button" class="btn btn-primary" onclick="changeImage('+'\'#contact_new_image'+ new_contact_no +'\')">Select a Image</button></div></div><div class="col-md-12"><div class="col-md-4"><h4>Contact Name</h4></div><div class="col-md-6"><input type="text" name="contact_new_n[]' + new_contact_no + '" value="" class="edit_input"></div></div><div class="col-md-12"><div class="col-md-4"><h4>Contact Phone</h4></div><div class="col-md-6"><input type="text" name="contact_new_p[]'+ new_contact_no +'" value="" class="edit_input" ></div></div><div class="col-md-12"><div class="col-md-4"><h4>Contact Email</h4></div><div class="col-md-6"><input type="email" name="contact_new_e[]" value="" class="edit_input"></div><div class="col-md-2"><button type="button" class="btn btn-danger contact-remove"  style="float: right;"><i class="fa fa-minus"></i></button></div></div><hr style="border:1px solid #bfcbd9"><div>');

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