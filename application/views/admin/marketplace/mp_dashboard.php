<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php 
// print_r($placeholder_data); exit();
init_head(); 

// foreach ($placeholder_data as $value) {
//     print_r($value['showroom']);
//     # code...
//   }
//   exit();

?>
<style>
    .open_sans {
        font-family: 'Open Sans', sans-serif;
    }
    .card {
          box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
          width: 300px;
          height: 100%;
          /*max-width: 300px;*/
          margin: 30px;
          text-align: center;
          font-family: arial;
          background-color: #FFFFFF;
    }

    .title {
          color: grey;
          font-size: 18px;
          height: 18px;
        }
    .bg-img {
        background-image: url(<?php echo site_url('/assets/images/color-pattern.png')?>);
        background-size: 101%;
    }
</style>

        <link rel="stylesheet" type="text/css" href="<?php echo site_url('/assets/load/skin_modern_silver.css') ?>"/>
        <link rel="stylesheet" type="text/css" href="<?php echo site_url('/assets/load/html_content.css')?>"/>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">
        <script type="text/javascript" src="<?php echo site_url('/assets/java/unminified.js')?>"></script>
        
        <script type="text/javascript">
            var carousel;
            
            FWDRLU3DCUtils.onReady(function(){
                carousel = new FWDUltimate3DCarousel({
                    
                    //required settings
                    carouselHolderDivId:"myDiv",
                    carouselDataListDivId:"carouselData",
                    displayType:"responsive",
                    autoScale:"yes",
                    carouselWidth:1850,
                    carouselHeight:338,
                    mainFolderPath:"<?php echo site_url('/assets/load')?>",
                    skinPath:"skin_modern_warm",

                    initializeOnlyWhenVisible:"no",
                                                
                    //main settings
                    
                    // backgroundImagePath:"load/skin_modern_warm/main_skin/background.jpg",
                    thumbnailsBackgroundImagePath:"",
                    // scrollbarBackgroundImagePath:"load/skin_modern_warm/main_skin/scrollbarBackground.jpg",
                    backgroundRepeat:"repeat-x",
                    carouselStartPosition:"center",
                    carouselTopology:"ring",
                    carouselXRadius:900,
                    carouselYRadius:0,
                    carouselXRotation:10,
                    carouselYOffset:0,
                    showCenterImage:"no",
                    centerImagePath:"load/media/logo.png",
                    centerImageYOffset:0,
                    showDisplay2DAlways:"no",
                    slideshowDelay:5000,
                    autoplay:"no",
                    disableNextAndPrevButtonsOnMobile:"no",
                    showLargeNextAndPrevButtons:"no",
                    largeNextAndPrevButtonsOffest:15,
                    controlsMaxWidth:940,
                    controlsHeight:31,
                    controlsPosition:"bottom",
                    slideshowTimerColor:"#777777",
                    rightClickContextMenu:"developer",
                    addKeyboardSupport:"yes",
                    useDragAndSwipe:"yes",
                                                
                    //thumbnail settings
                    thumbnailWidth:300,
                    thumbnailHeight:200,
                    thumbnailBorderSize:0,
                    thumbnailMinimumAlpha:.3,
                    thumbnailBackgroundColor:"#666666",
                    thumbnailBorderColor1:"#FFFFFF",
                    thumbnailBorderColor2:"#DDDDDD",
                    transparentImages:"no",
                    maxNumberOfThumbnailsOnMobile:13,
                    showTextUnderThumbnail:"no",
                    showThumbnailsGradient:"yes",
                    showThumbnailsHtmlContent:"no",
                    textBackgroundColor:"#333333",
                    textBackgroundOpacity:.7,
                    showText:"yes",
                    showTextBackgroundImage:"yes",
                    showFullTextWithoutHover:"no",
                    showThumbnailBoxShadow:"yes",
                    thumbnailBoxShadowCss:"0px 2px 2px #555555",
                    showReflection:"yes",
                    reflectionHeight:60,
                    reflectionDistance:0,
                    reflectionOpacity:.2,
                                                
                    //scrollbar settings
                    disableScrollbarOnMobile:"yes",
                    enableMouseWheelScroll:"yes",
                    scrollbarHandlerWidth:300,
                    scrollbarTextColorNormal:"#777777",
                    scrollbarTextColorSelected:"#000000",
                    
                    //bullets navigation settings
                    showBulletsNavigation:"no",
                    bulletsBackgroundNormalColor1:"#fcfdfd",
                    bulletsBackgroundNormalColor2:"#e4e4e4",
                    bulletsBackgroundSelectedColor1:"#000000",
                    bulletsBackgroundSelectedColor2:"#666666",
                    bulletsShadow:"0px 0px 4px #888888",
                    bulletsNormalRadius:7,
                    bulletsSelectedRadius:8,
                    spaceBetweenBullets:16,
                    bulletsOffset:14,
                                                
                    //combobox settings
                    
                    showAllCategories:"no",
                    comboBoxPosition:"topright",
                    selectorBackgroundNormalColor1:"#FFFFFF",
                    selectorBackgroundNormalColor2:"#dddddd",
                    selectorBackgroundSelectedColor1:"#FFFFFF",
                    selectorBackgroundSelectedColor2:"#FFFFFF",
                    selectorTextNormalColor:"#8b8b8b",
                    selectorTextSelectedColor:"#000000",
                    buttonBackgroundNormalColor1:"#FFFFFF",
                    buttonBackgroundNormalColor2:"#dddddd",
                    buttonBackgroundSelectedColor1:"#FFFFFF",
                    buttonBackgroundSelectedColor2:"#FFFFFF",
                    buttonTextNormalColor:"#8b8b8b",
                    buttonTextSelectedColor:"#000000",
                    comboBoxShadowColor:"#999999",
                    comboBoxHorizontalMargins:12,
                    comboBoxVerticalMargins:12,
                    comboBoxCornerRadius:0,
                                                
                    //lightbox settings
                    buttonsAlignment:"in",
                    itemBoxShadow:"none",
                    descriptionWindowAnimationType:"opacity",
                    descriptionWindowPosition:"bottom",
                    slideShowAutoPlay:"no",
                    videoAutoPlay:"no",
                    nextVideoOrAudioAutoPlay:"yes",
                    videoLoop:"no",
                    audioAutoPlay:"no",
                    audioLoop:"no",
                    backgroundOpacity:.9,
                    descriptionWindowBackgroundOpacity:.95,
                    buttonsHideDelay:3,
                    slideShowDelay:4,
                    defaultItemWidth:1640,
                    defaultItemHeight:480,
                    itemOffsetHeight:50,
                    spaceBetweenButtons:1,
                    buttonsOffsetIn:2,
                    buttonsOffsetOut:5,
                    itemBorderSize:5,
                    itemBorderRadius:0,
                    itemBackgroundColor:"#333333",
                    itemBorderColor1:"#FFFFFF",
                    itemBorderColor2:"#dddddd",
                    lightBoxBackgroundColor:"#000000",
                    descriptionWindowBackgroundColor:"#FFFFFF",
                    videoPosterBackgroundColor:"#0099FF",
                    videoControllerBackgroundColor:"#FFFFFF",
                    audioControllerBackgroundColor:"#FFFFFF",
                    timeColor:"#000000"
                });
                
            })
            
        </script>
    
    </head>

<!-- <div id="wrapper" style="background-color: #44B4E0"> -->
<div id="wrapper">
    <div class="screen-options-area"></div>
 
    <div class="content">
        <div class="row">

            <?php $this->load->view('admin/includes/alerts'); ?>

            <?php hooks()->do_action( 'before_start_render_dashboard_content' ); ?>

            <div class="clearfix"></div>

            <div class="col-md-12" >
                <div id="myDiv" style="width: 100% !important; margin: auto;"></div>
                    <div id="carouselData" style="display:none;">
                        <div data-cat="Category one">
                        <?php if($placeholder_data){ ?>      
                            <?php foreach($placeholder_data as $res){ ?>
                                <?php if ($res['showroom'] == 0) continue;?>
                                <ul>
                                    <li data-url="none" data-target="_blank" ></li>
                                    <li data-thumbnail-path="<?php echo site_url($res['logo_url']);?>">
                                        
                                    </li>
                                    <li data-thumbnail-text="" data-thumbnail-text-title-offset="35" data-thumbnail-text-offset-top="10" data-thumbnail-text-offset-bottom="7">
                                        <a href="<?php echo admin_url('marketplace/home/'.$res['staffid']); ?>">
                                        <h1 style="text-align:center;color: white;overflow-wrap: break-word;" class="open_sans">
                                            <?php echo $res['placeholder_name'];?>
                                            <br>
                                        </h1>
                                        </a>

                                    </li>
                                </ul>
                             <?php } ?>
                          <?php } ?>

                        
                        <?php if($placeholder_data){ ?>      
                            <?php foreach($placeholder_data as $res){ ?>
                                <?php if ($res['showroom'] == 0) continue;?>
                                <ul>
                                    <li data-url="none" data-target="_blank" ></li>
                                    <li data-thumbnail-path="<?php echo site_url($res['logo_url']);?>">
                                        
                                    </li>
                                    <li data-thumbnail-text="" data-thumbnail-text-title-offset="35" data-thumbnail-text-offset-top="10" data-thumbnail-text-offset-bottom="7">
                                        <a href="<?php echo admin_url('marketplace/home/'.$res['staffid']); ?>">
                                        <h1 style="text-align:center;color: white;overflow-wrap: break-word;" class="open_sans">
                                            
                                            <?php echo $res['placeholder_name'];?>
                                            <br>
                                        </h1>
                                        </a>
                                    </li>
                                </ul>
                             <?php } ?>
                          <?php } ?>

                          <?php if($placeholder_data){ ?>      
                            <?php foreach($placeholder_data as $res){ ?>
                                <?php if ($res['showroom'] == 0) continue;?>
                                <ul>
                                    <li data-url="none" data-target="_blank" ></li>
                                    <li data-thumbnail-path="<?php echo site_url($res['logo_url']);?>">
                                        
                                    </li>
                                    <li data-thumbnail-text="" data-thumbnail-text-title-offset="35" data-thumbnail-text-offset-top="10" data-thumbnail-text-offset-bottom="7">
                                        <a href="<?php echo admin_url('marketplace/home/'.$res['staffid']); ?>">
                                        <h1 style="text-align:center;color: white;overflow-wrap: break-word;" class="open_sans">
                                            
                                            <?php echo $res['placeholder_name'];?>
                                            <br>
                                        </h1>
                                        </a>

                                    </li>
                                </ul>
                             <?php } ?>
                          <?php } ?>

                        </div>
                    </div>              
                    </div>

            <?php hooks()->do_action('after_dashboard_top_container'); ?>

            <!-- middle space -->
            <div class="col-md-12"> </div>
              
            <?php hooks()->do_action('after_dashboard_half_container'); ?>
            <!-- list of sellers -->
            <div class="col-md-12">
                <nav class="navbar navbar-expand-sm bg-img navbar-primary">
                      <ul class="navbar-nav" style="float: none;margin-left: 20px;margin-top: 5px">
                        <li class="nav-item active">
                          <h4 style="color: white"><?php echo _l('List of Sellers')?><h4>
                        </li>
                      </ul>
                </nav>
                   
                <div  class="container text-center" style="width: 100%;">
                    <div class="row" style="display: flex; justify-content: center; flex-wrap: wrap;">
                        
                            <?php if($placeholder_data){ 
                                // print_r($placeholder_data);
                                ?> 
                                <?php foreach ($placeholder_data as $res) { ?>
                                   <?php if (!$res['showroom']) continue;?>
                                    <div class="card"  style="width:300px;height: 400px; position: relative;">
                                        <a href="<?php echo admin_url('marketplace/home/'.$res['staffid']) ?>">
                                            <img src="<?php echo site_url($res['logo_url'])?>"  style="width:80%;height: 45%;margin-top: 10%">
                                            <div class="col-md-12" style="overflow-wrap: break-word;">
                                               <h1 style="text-align:center" class="open_sans">
                                                <?php echo $res['placeholder_name'];?>
                                                <br>
                                            </h1> 
                                            </div>
                                        </a>
                                        <div class="col-md-12" style="padding:0;position: absolute; bottom: 0;">
                                           <img src="<?php echo site_url("/assets/images/color-pattern.png")?>" style="width: 101%">
                                        </div>                             
                                    </div>
                                
                                <?php } ?>
                            <?php } ?>    
                    </div>   
                </div>
            </div>
        </div>
    </div>
<?php init_tail(); ?>
</body>
</html>
