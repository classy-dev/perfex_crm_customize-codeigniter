<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo site_url('/assets/css/profino-basics.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo site_url('/assets/css/profino-exhibitor.css') ?>">

<link rel="stylesheet" type="text/css" href="<?php echo site_url('/assets/css/profino-layout.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo site_url('/assets/css/profino-lists.css') ?>">
<link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

<script type="text/javascript" src="<?php echo site_url('/assets/js/vendor/modernizr.js') ?>"></script>
<script type="text/javascript" src="<?php echo site_url('/assets/js/vendor/lazysizes.js') ?>"></script>

<?php
init_head();
?>
<style type="text/css">
    
        @font-face {
        font-family: "Prometo W04 Regular";
        src: url("<?php echo site_url('/assets/fonts/new/e2b8e104b696e6a451519e16f12bd5f7.eot') ?>"); /* IE9*/
        src: url("<?php echo site_url('/assets/fonts/new/e2b8e104b696e6a451519e16f12bd5f7.eot')?>?#iefix") format("embedded-opentype"), /* IE6-IE8 */
        url("<?php echo site_url('/assets/fonts/new/e2b8e104b696e6a451519e16f12bd5f7.woff2')?>") format("woff2"), /* chrome、firefox */
        url("<?php echo site_url('/assets/fonts/new/e2b8e104b696e6a451519e16f12bd5f7.woff')?>") format("woff"), /* chrome、firefox */
        url("<?php echo site_url('/assets/fonts/new/e2b8e104b696e6a451519e16f12bd5f7.ttf')?>") format("truetype"), /* chrome、firefox、opera、Safari, Android, iOS 4.2+*/
        url("<?php echo site_url('/assets/fonts/new/e2b8e104b696e6a451519e16f12bd5f7.svg')?>#Prometo W04 Regular") format("svg"); /* iOS 4.1- */
    }
    .prometo{
        font-family:"Prometo W04 Regular" !important;

    }

    .open_sans {
        font-family: 'Open Sans', sans-serif;
    }

    .card_title
    {
        font-size: 25px;
        text-align: center;
    }
    .card_content
    {
        font-size: 18px;
        text-align: center;
    }
    .a
    {
        color: #555;
    }

    .modal-dialog {
        /*width: 1200px;*/
      max-width: 100%;
      margin: 30px auto;
    }

    .modal-body {
      position:relative;
      padding:0px;
    }
    .close {
      position:absolute;
      right:-30px;
      top:0;
      z-index:999;
      font-size:2rem;
      font-weight: normal;
      color:#fff;
      opacity:1;
    }
    .row_cus {
      display: flex;
      justify-content: center; 
      flex-wrap: wrap; 
      margin-left: -75px; 
      margin-right: -75px;  
    }
    .card_cus {
      /*max-width: 350px;*/
      height: 220px; 
      margin-left: 0;
      margin-bottom: 50px;
      margin-top: 30px; 
      border:solid 1px; 
      background:white;
    }
    .col-md-5_cus{
        width: 55%; 
        height: 100%;
        padding: 0;
    }
    .img_cus{
       /* width: 75%; 
        height: 75%; */
        /*margin-left: 17%;*/
        margin-top: 15%;
        margin-bottom: 15%;
    }
    .col-md-4_cus{
        width: 45%;
        padding: 0;
        height: 100%;
    }
</style>

<div id="wrapper">
     <!-- <div class="content" style="background-color: #44B4E0; "> -->
      <div class="content">
        <!-- <div class="row" style="margin-left: -40px;margin-right: -40px"> -->
          <div class="row">
            <?php $this->load->view('admin/includes/alerts'); ?>

            <?php hooks()->do_action( 'before_start_render_dashboard_content' ); ?>

            <div class="clearfix"></div>
            <div class="col-md-12"  data-container="home-overview" >
               <div class="content-wrap">
                    <section  class="section-content section-fullwide section-fixed section-padding-none" data-role="exhibitor" data-exhibitor-hash="8e296a067a37563370ded05f5a3bf3ec" id="first_section" style="padding-bottom: 15px">
                        <div class="stage stage-exhibitor" data-role="exhibitor-stage">
                         <div class="stage-item" style="background-color: white">
                            <figure>
                                <img class="stage-image-sizer-bg_pic transition-ratio lazyload" src="<?php echo site_url('/assets/images/Exhibition.png') ?>" />
                                <figcaption class="stage-caption">
                                    <div class="stage-caption-wrap">
                                        <div class="stage-caption-innerwrap">
                                            <div class="stage-caption-contentwrap">
                                                <!-- <div class="stage-position-helper"> -->
                                                    <div class="stage-position-element stage-position-element-player"  id="exhibitor-video" style="z-index: 2">
                                                            <!-- <a class="video-btn" data-toggle="modal" data-src="https://www.youtube.com/embed/A-twOC3W558" data-target="#myModal">  -->
                                                            <a class="video-btn" data-toggle="modal" data-src="<?php echo 'https://www.youtube.com/embed/'.$home_placeholder[0]['video_url'];?>" data-target="#myModal"> 
                                                            <!-- data-video-id="<?php foreach ($home_placeholder as $res)  echo $res['video_url'];?> "> -->
                                                            <div class="stage-position-element-vi">
                                                                <i class="fa fa-play" aria-hidden="true"></i> 
                                                            </div>
                                                            </a>
                                                        </div>

                                                    <div class="stage-position-element stage-position-element-chatbox" style="background-image: url(<?php echo site_url('/assets/images/circle.png')?>); background-size: 100%;">
                                                        <!-- <a component="groupchat/new" component-data='' href="<?php echo 'mailto:'.$res['email'];?>"> -->
                                                          <a data-scrollto="contact" href="#contacts">
                                                            <div class="stage-position-element-ci">
                                                                <!-- <i class="fa fa-commenting-o" aria-hidden="true"></i> -->
                                                                <img src="<?php echo site_url('/assets/images/chaticon.png') ?>" />
                                                            </div>
                                                            <div class="stage-position-element-ci_letter"><?php echo _l('Contact')?></div>
                                                        </a>
                                                    </div>

                                                    <div class="stage-position-element stage-position-element-productsbox" style="background-image: url(<?php echo site_url('/assets/images/circle.png')?>); background-size: 100%;">
                                                        <a data-scrollto="produkte" href="#products">
                                                            <div class="stage-position-element-pi">
                                                               <!-- <i class="fa fa-diamond" aria-hidden="true"></i> -->
                                                               <img src="<?php echo site_url('/assets/images/producticon.png') ?>" />
                                                            </div>
                                                            <div class="stage-position-element-pi_letter"><?php echo _l('Product')?></div>
                                                        </a>
                                                   </div>

                                                   <!-- <div class="stage-position-element stage-position-element-contactsbox" >
                                                        <a data-scrollto="contact" href="#contacts">
                                                            <div class="stage-position-element-coi">
                                                               <i class="fa fa-diamond" aria-hidden="true"></i>
                                                            </div>
                                                            <div class="stage-position-element-coi_letter">Contact</div>
                                                        </a>
                                                   </div> -->

                                                     <div class="stage-position-element-title_pic" >
                                                        <?php foreach ($home_placeholder as $res) {  if (isset($res['title_pic'])) ?>

                                                           <img src="<?php echo site_url($res['title_pic']) ?>" class="img_pic">
                                                        
                                                        <?php } ?>
                                                    </div>
                                                    <div class="stage-position-element-logo_pic" style="background-color: transparent;">
                                                        <?php foreach ($home_placeholder as $res) {  if (isset($res['logo_url']))  ?>

                                                           <img src="<?php echo site_url($res['logo_url']) ?>" class="img_pic">
                                                        
                                                        <?php } ?>
                                                    </div>

                                                    <div class="stage-position-element-video_pic" style="background-color: transparent;">
                                                        <?php foreach ($home_placeholder as $res) {  if (isset($res['video_pic']))  ?>

                                                           <img src="<?php echo site_url($res['video_pic']) ?>" class="img_pic">
                                                        
                                                        <?php } ?>
                                                    </div>
                                            </div>
                                        </div>
                                </figcaption>
                            </figure>
                         </div> 
                        </div>
                    </section>

                    <section class="section-content">
                        <div class="section-content-wrapper">
                            <div class="content-element content-padding-top content-padding-bottom">
                                 <h1 class="align-center text-blue " style="font-size: 45px"><span class=" prometo"><?php echo _l('welcome to')?><span class="prometo" style="color:grey"> <?php foreach ($home_placeholder as $res) { echo  $res['placeholder_name'];}?></span></span></h1>
                                 <h2 class="align-center section-headline-sub open_sans" ><?php echo $res['sub_title']; ?></h2>
                            </div>

                             <div class="content-element content-padding-top content-padding-bottom">
                                <ul class="grid flex-box">
                                    <?php foreach ($home_placeholder_blog as $key) { ?>
                                    <li class="transition-ratio flex-box">
                                    <div class="grid-element object-shadow-all object-radius-all" style="height: 400px">
                                        <a class="grid-element-click" href="<?php echo $key['link_url'];?>" target="_blank">
                                            <div class="grid-element-image" style="height:200px">
                                                
                                                    <img class="lazyload" data-sizes="auto" data-srcset="<?php echo site_url($key['blog_pic']);?>" style="height: 200px">
                                                
                                            </div>
                                             <div class="grid-element-text" >
                                                <div class="grid-element-title transition-background-color card_title prometo"><?php echo $key['blog_headline']; ?></div>
                                                <div class="grid-element-description transition-background-color card_content open_sans"><p><?php echo $key['blog_txt']; ?></p></div>
                                            </div>
                                        </a>
                                        <div style="display: none;" class="grid-element-actionbar">
                                            <ul>
                                                <li><a><div class="icon icon-like " data-favorites=true data-favorite-type="teaser" data-favorite-id="314" data-toggle-type=""></div></a></li>
                                                <li class=""><a href=""><div class="icon icon-share"></div></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    </li>
                                <?php }?>

                                </ul>

                                <div class="content-element content-padding-bottom" style="margin-top: 50px;">
                                    <ul class="button-grid flex-align-center" style="font-size: 24px;">
                                        <li>
                                            <a class="button"  data-action="aussteller" data-route="aussteller" href="<?php echo admin_url('marketplace');?>">
                                                <span class="button-text"><?php echo _l('To the MarketplaceHome')?></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>            
                    </section>
                    
                    <section class="section-content" id="products">
                        <div class="section-content-wrapper">
                            <div class="content-element content-padding-top content-padding-bottom">
                                <h1 class="align-center text-blue hashtag prometo" style="font-size: 45px"><span class="hashtag-word-1"><?php echo _l('Products and Other features')?></span><span class="hashtag-word-2"></span></h1>
                                <!-- <p class="align-center text-grey">Produkte und Verkaufshilfen</p> -->
                            </div>

                            <div class="content-element content-padding-bottom">
                                <!-- <h3 style="margin-left: 30px; color:#44B4E0;" class="prometo">Products and sales aids</h3> -->
                                <ul data-filter-max-items="5" style="font-size: 24px">
                                    <table class="table table-bordered" >
                                        <?php foreach ($home_placeholder_product as $key) {?>
                                            <tr>
                                                <td style="border:2px solid #bfcbd9; text-align: center; ">
                                                   <div class="col-md-1 ">
                                                       <a  class="a" target="_blank" href="<?php echo site_url($key['products_url']); ?>">
                                                        <i class="fa fa-file-pdf-o fa-2x" aria-hidden="true" style="color: #44B4E0"></i>
                                                        </a>
                                                   </div>
                                                        
                                                   <div class="col-md-11">
                                                    <a class="a" target="_blank" href="<?php echo site_url($key['products_url']); ?>">
                                                        <div class="open_sans"><?php echo $key['products_name']; ?></div>
                                                        </a> 
                                                        </a>    
                                                   </div>
                                                         
                                                    
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </table>   
                                 </ul>           
                            </div> 
                        </div>     
                    </section>

                    <!-- contact -->
                    <section class="section-content" id="contacts">
                        <div class="section-content-wrapper">
                            <div class="content-element content-padding-top content-padding-bottom">
                                 <h1 class="align-center prometo" style="font-size: 45px"><?php echo _l('Contact')?></h1>
                            </div>
                            <div class="row" style="display: flex;justify-content: center; flex-wrap: wrap; ">
                                <?php foreach ($home_placeholder_contact as $key) { ?>
                                <div class="col-lg-4 col-xs-8 col-sm-8 col-md-6">
                                    <div class="card_cus">
                                        <div class="row" style="display: flex;">
                                            <div style="width: 50%; padding: 7%">
                                               <img src="<?php echo site_url($key['contact_pic']);?>" class="img_cus" style="height:130px;" > 
                                            </div>
                                            <div style="width: 50%;">
                                                <div class="card-body open_sans" style="margin-top: 30%; padding-right: 15%">
                                                    <h2 class="card-text"><?php echo $key['contact_name'];?></h2>
                                                    <h4 class="card-text"><?php echo $key['contact_phone'];?></h4>
                                                    <a href="<?php echo 'mailto:'.$key['contact_email'];?>">
                                                        <h4 class="card-text"><?php echo $key['contact_email'];?></h4>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                            </div>  
                        </div>            
                    </section>
                </div>             
            </div>
        </div>
    </div>
    <footer class="footer offcanvas-transition">
    <div class="footer-wrap">

        <div class="footer-logo" style="margin-right: 40px">
            <a class="logo" href="#header" data-route="first_section" data-action="first_section" >
                <img src="<?php echo site_url('uploads/company/logo.png');?>" style="max-width: 200%;">
            </a>
        </div>
    </div>
</footer>
</div>
<?php init_tail(); ?>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>        
            <!-- 16:9 aspect ratio -->
        <div class="embed-responsive embed-responsive-16by9">
          <iframe class="embed-responsive-item" src="" id="video"  allowscriptaccess="always" allow="autoplay"></iframe>
        </div>
      </div>
    </div>
  </div>
</div> 
</body>
</html>


<script type="text/javascript">
    $(document).ready(function() {

        // Gets the video src from the data-src on each button

        var $videoSrc;  
        $('.video-btn').click(function() {
            $videoSrc = $(this).data( "src" );
        });
        console.log($videoSrc);

          
          
        // when the modal is opened autoplay it  
        $('#myModal').on('shown.bs.modal', function (e) {
            
        // set the video src to autoplay and not to show related video. Youtube related video is like a box of chocolates... you never know what you're gonna get
        $("#video").attr('src',$videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0" ); 
        })
          


        // stop playing the youtube video when I close the modal
        $('#myModal').on('hide.bs.modal', function (e) {
            // a poor man's stop video
            $("#video").attr('src',$videoSrc); 
        }) 
            
            


          
          
        // document ready  
    });



</script>
