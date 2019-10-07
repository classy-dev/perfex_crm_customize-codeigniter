<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Marketplace extends AdminController
{
    

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Marketplace_model');
    }

   
    public function index()
    {   
        if (!has_permission('marketplace', '', 'view')) {
               access_denied('View');
           }
        $pre_result = $this->Marketplace_model->get_placeholder_data();
        
        for ($i=0; $i < count($pre_result) ; $i++) 
            { 
                
                if($pre_result[$i]['logo_url'] == null)
                $pre_result[$i]['logo_url'] = '/assets/images/noimage.jpg';
                // print_r($pre_result[$i]['logo_url']);
            }
        // exit();
        $result['placeholder_data'] = $pre_result; 
        $this->load->view('admin/marketplace/mp_dashboard',$result);
      
    }
    
    public function home($id)
    {   
        if (!has_permission('marketplace', '', 'view')) {
               access_denied('Home');
           }
        $pre_result = $this->Marketplace_model->get_home_placeholder($id);
        // print_r($pre_result);exit();
        $result_blog = $this->Marketplace_model->get_home_placeholder_blog($id);
        $result_contact = $this->Marketplace_model->get_home_placeholder_contact($id);
        $result_product = $this->Marketplace_model->get_home_placeholder_product($id);
        
        if ($pre_result[0]['image_url'] == null) $pre_result[0]['image_url']='/assets/images/noimage.jpg';
        if ($pre_result[0]['title_pic'] == null) $pre_result[0]['title_pic']='/assets/images/noimage.jpg';
        if ($pre_result[0]['logo_url'] == null) $pre_result[0]['logo_url']='/assets/images/noimage.jpg';
        if ($pre_result[0]['video_pic'] == null) $pre_result[0]['video_pic']='/assets/images/noimage.jpg';
       

        for ($i = 0; $i < count($result_blog) ; $i++)
        {
            if($result_blog[$i]['blog_pic'] == null)
            $result_blog[$i]['blog_pic'] = '/assets/images/noimage.jpg';

            if($result_blog[$i]['link_url'] == null)
            $result_blog[$i]['link_url'] = '';
        }

        for ($i = 0; $i < count($result_contact) ; $i++)
        {
            if($result_contact[$i]['contact_pic'] == null)
            $result_contact[$i]['contact_pic'] = '/assets/images/noimage.jpg';

            
        }

        $result['home_placeholder'] = $pre_result;
        $result['home_placeholder_blog'] = $result_blog;
        $result['home_placeholder_product'] = $result_product;
        $result['home_placeholder_contact'] = $result_contact;
        // print_r($result); exit();
        $this->load->view('admin/marketplace/home_dashboard', $result);
        
    }  

    public function edit($id)
    {
       

       if (!has_permission('marketplace', '', 'edit')) {
               access_denied('Edit');
           }
        $pre_result = $this->Marketplace_model->get_edit_placeholder($id);
        // print_r($pre_result); exit();

        if($pre_result)
       {
            if($pre_result[0]['image_url'] == null)
            $pre_result[0]['image_url'] = '/assets/images/noimage.jpg';

            if($pre_result[0]['logo_url'] == null)
                $pre_result[0]['logo_url'] = '/assets/images/noimage.jpg';

            if($pre_result[0]['title_pic'] == null)
                $pre_result[0]['title_pic'] = '/assets/images/noimage.jpg';

            if($pre_result[0]['video_pic'] == null)
                $pre_result[0]['video_pic'] = '/assets/images/noimage.jpg';
        }

        $pre_result_blog = $this->Marketplace_model->get_home_placeholder_blog($id);
        // print_r($pre_result_blog);exit();

        for ($i = 0; $i < count($pre_result_blog) ; $i++)
        {
            if($pre_result_blog[$i]['blog_pic'] == null)
            $pre_result_blog[$i]['blog_pic'] = '/assets/images/noimage.jpg';
           

        }

        $data = [];
        $i=0; 
        foreach ($pre_result_blog as $rows) {
            
            $sub_data = '
                <div class="col-md-12 blog-container">
                    <input type="hidden" name="b_id[]" value="'.$rows['id'].'">
                    <div class="col-md-12" >
                        <div class="col-md-6">
                            <h4>Blog Picture</h4>
                        </div>
                        <div class="col-md-3" style="margin-bottom:10px">
                            <img id="blog_preview'.$i.'" src="'. site_url($rows['blog_pic']).'" class="edit_img "/>
                            <br/>
                            <br>
                            <input type="file" id="blog_image'.$i.'" name="blog_image[]" class="input-image-file" style="display: none;"/>
                            <button type="button" class="btn btn-primary" onclick="changeImage('.'\'#blog_image'.$i.'\')">Select a Image</button>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-4">
                            <h4>Blog Headline</h4>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="blog_h[]" value="'.$rows['blog_headline'].'" class="edit_input">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-4">
                            <h4>Blog Text</h4>
                        </div>
                        <div class="col-md-6" style="overflow:auto; margin-left:32px">
                             <textarea rows="4" cols="48" name="blog_t[]" >'.$rows['blog_txt'].'</textarea>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-4">
                            <h4>Blog URL</h4>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="blog_u[]" value="'.$rows['link_url'].'" class="edit_input">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger blog-remove" id="remove_blog'.$i.'" style="float: right;"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <hr style="border:1px solid #bfcbd9">
                </div>';

            $data[] = $sub_data; 
            $i++;
        }
        
        // contact
        $pre_result_contact = $this->Marketplace_model->get_home_placeholder_contact($id);

        for ($i = 0; $i < count($pre_result_contact) ; $i++)
        {
            if($pre_result_contact[$i]['contact_pic'] == null)
            $pre_result_contact[$i]['contact_pic'] = '/assets/images/noimage.jpg';
           
        }
        $data_contact = [];
        $j=0; 
        foreach ($pre_result_contact as $rows) {
            
            $sub_data_contact = '
                <div class="col-md-12 contact-container">
                    <input type="hidden" name="c_id[]" value="'.$rows['id'].'">
                    <div class="col-md-12" >
                        <div class="col-md-6">
                            <h4>Contact Picture</h4>
                        </div>
                        <div class="col-md-3" style="margin-bottom:10px">
                            <img id="contact_preview'.$j.'" src="'. site_url($rows['contact_pic']).'" class="edit_img "/>
                            <br/>
                            <br>
                            <input type="file" id="contact_image'.$j.'" name="contact_image[]" class="input-image-file" style="display: none;"/>
                            <button type="button" class="btn btn-primary" onclick="changeImage('.'\'#contact_image'.$j.'\')">Select a Image</button>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-4">
                            <h4>Contact Name</h4>
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="contact_n[]" value="'.$rows['contact_name'].'" class="edit_input">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-4">
                            <h4>Contact Phone</h4>
                        </div>
                        <div class="col-md-6">
                             
                             <input type="text" name="contact_p[]" value="'.$rows['contact_phone'].'" class="edit_input">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="col-md-4">
                            <h4>Contact Email</h4>
                        </div>
                        <div class="col-md-6">
                            <input type="email" name="contact_e[]" value="'.$rows['contact_email'].'" class="edit_input">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger blog-remove" id="remove_contact'.$j.'" style="float: right;"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <hr style="border:1px solid #bfcbd9">
                </div>';

            $data_contact[] = $sub_data_contact; 
            $j++;
        }
     
        
        $result = [];

        if($pre_result){
            $result['placeholder'] = isset($pre_result[0]) ? $pre_result[0] : [];
            $result['placeholder_blog'] = $pre_result_blog;
            $result['placeholder_contact'] = $pre_result_contact;
            $result['blog_component'] = $data;
            $result['contact_component'] =$data_contact;
            $this->load->view('admin/marketplace/edit_dashboard',$result); 
        }
        else
            $this->load->view('admin/marketplace/add_dashboard',$result);

    }


    public function reArrayFiles($file_post) {
        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);

        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }

        return $file_ary;
    }


    public function change()

    {   

        // print_r($_POST);exit();
        if(isset($_FILES['blog_new_image'])){
            $new_files = $this->reArrayFiles($_FILES['blog_new_image']);
           
        }

        if(isset($_FILES['blog_image'])){
            $old_files = $this->reArrayFiles($_FILES['blog_image']);
            
        }
        
        if(isset($_FILES['contact_new_image'])){
            $new_files_contact = $this->reArrayFiles($_FILES['contact_new_image']);
           
        }
        if(isset($_FILES['contact_image'])){
            $old_files_contact = $this->reArrayFiles($_FILES['contact_image']);
            
        }

        if (!has_permission('marketplace', '', 'edit')) {
               access_denied('Edit');
           }
        
        $folderPath = "uploads/";
    
        if (! is_writable($folderPath) || ! is_dir($folderPath)) {
            echo "error";
             exit();
        }

        ///profile
        $data = [];

        if (isset($_POST['showroom'])){
              $data['showroom'] = 1;
        }
         else{ 
            $data['showroom'] = 0;
        }
         $data['title'] = $_POST['headline'];
         $data['video_url'] = $_POST['title_video'];
         $data['placeholder_name'] = $_POST['name'];
         $data['sub_title'] = $_POST['subtitle'];
         $data['email'] = $_POST['email'];

        if (move_uploaded_file($_FILES["logo"]["tmp_name"], $folderPath . $_FILES["logo"]["name"])) {
            $data['logo_url'] = $folderPath . $_FILES["logo"]["name"];
        }
            
        if (move_uploaded_file($_FILES["title"]["tmp_name"], $folderPath . $_FILES["title"]["name"])) {
             $data['title_pic'] = $folderPath . $_FILES["title"]["name"];
         }
            
        // if (move_uploaded_file($_FILES["profile"]["tmp_name"], $folderPath . $_FILES["profile"]["name"])) {
        //     $data['image_url'] = $folderPath . $_FILES["profile"]["name"];
        // }

        if (move_uploaded_file($_FILES["video"]["tmp_name"], $folderPath . $_FILES["video"]["name"])) {
            $data['video_pic'] = $folderPath . $_FILES["video"]["name"];
        }

        //end profile

        ///blog content update and add

        $blog_loaded_id = $this->Marketplace_model->get_blog_id_array($_POST['total_id']);

        for ($i=0; $i < count($blog_loaded_id) ; $i++) { 
            $id_array1[] = $blog_loaded_id[$i]['id']; 
        }
        
       

        if(isset($_FILES['blog_image']))
        {

            for ($i=0; $i < count($old_files) ; $i++) { 

            if (move_uploaded_file($old_files[$i]["tmp_name"], $folderPath . $old_files[$i]["name"]))   
                    $data_old_blog[$i]['blog_pic'] = $folderPath . $old_files[$i]["name"];

             $data_old_blog[$i]['blog_txt'] = $_POST['blog_t'][$i];
             $data_old_blog[$i]['blog_headline'] = $_POST['blog_h'][$i];
             $data_old_blog[$i]['link_url'] = $_POST['blog_u'][$i];
             $blog_changed_id[$i]['id'] = $_POST['b_id'][$i];
             $blog_id[$i] = $_POST['b_id'][$i];
             $this->Marketplace_model->update_placeholder_blog($data_old_blog[$i],$blog_id[$i]);
             }
        }

        
       
        if(isset($_FILES['blog_new_image']))
        {
           for ($i=0; $i < count($new_files) ; $i++) { 

            if (move_uploaded_file($new_files[$i]["tmp_name"], $folderPath . $new_files[$i]["name"]))   
                    $data_new_blog[$i]['blog_pic'] = $folderPath . $new_files[$i]["name"];

             $data_new_blog[$i]['blog_txt'] = $_POST['blog_new_t'][$i];
             $data_new_blog[$i]['link_url'] = $_POST['blog_new_u'][$i];
             $data_new_blog[$i]['blog_headline'] = $_POST['blog_new_h'][$i];
             $data_new_blog[$i]['staffid'] = $_POST['total_id'];
             $this->Marketplace_model->insert_single_blog($data_new_blog[$i]);
            }  
        }
        // echo("blog_image");
        // print_r($_FILES['blog_image']);
        // echo("blog_new_image");
        // print_r($_FILES['blog_new_image']);
        // echo("id_array1");
        // print_r($id_array1);
        // echo("blog_id");
        // print_r($blog_id);
        // exit();
        
        if(isset($id_array1))
        {
            if(isset($_POST['b_id']))
            {
                 $remove_blog = array_diff($id_array1,$blog_id);
                 // print_r($remove_blog);exit();
                  foreach ($remove_blog as $value) {
                    $this->Marketplace_model->delete_blog($value);
                    
                }
            }
            else
            {
                foreach ($id_array1 as  $value) {
                    $this->Marketplace_model->delete_blog($value);
                    
                }
            }

           
        }


        /// contact add and remove


        $contact_loaded_id = $this->Marketplace_model->get_contact_id_array($_POST['total_id']);


        for ($i=0; $i < count($contact_loaded_id) ; $i++) { 
            $id_array2[] = $contact_loaded_id[$i]['id']; 
        }

        if(isset($_FILES['contact_image']))
        {

            for ($i=0; $i < count($old_files_contact) ; $i++) { 

            if (move_uploaded_file($old_files_contact[$i]["tmp_name"], $folderPath . $old_files_contact[$i]["name"]))   
                    $data_old_contact[$i]['contact_pic'] = $folderPath . $old_files_contact[$i]["name"];

             $data_old_contact[$i]['contact_name'] = $_POST['contact_n'][$i];
             $data_old_contact[$i]['contact_phone'] = $_POST['contact_p'][$i];
             $data_old_contact[$i]['contact_email'] = $_POST['contact_e'][$i];
             $contact_changed_id[$i]['id'] = $_POST['c_id'][$i];
             $contact_id[$i] = $_POST['c_id'][$i];
             $this->Marketplace_model->update_placeholder_contact($data_old_contact[$i],$contact_id[$i]);
             }
        }
        // print_r($contact_loaded_id);
        // exit();
        if(isset($_FILES['contact_new_image']))
        {
           for ($i=0; $i < count($new_files_contact) ; $i++) { 

            if (move_uploaded_file($new_files_contact[$i]["tmp_name"], $folderPath . $new_files_contact[$i]["name"]))   
                    $data_new_contact[$i]['contact_pic'] = $folderPath . $new_files_contact[$i]["name"];

             $data_new_contact[$i]['contact_name'] = $_POST['contact_new_n'][$i];
             $data_new_contact[$i]['contact_phone'] = $_POST['contact_new_p'][$i];
             $data_new_contact[$i]['contact_email'] = $_POST['contact_new_e'][$i];
             $data_new_contact[$i]['staffid'] = $_POST['total_id'];
             $this->Marketplace_model->insert_single_contact($data_new_contact[$i]);
            }  
        }
        
        if(isset($id_array2))
        {
            if(isset($_POST['c_id']))
            {
                 $remove_contact = array_diff($id_array2,$contact_id);
                 // print_r($remove_blog);exit();
                  foreach ($remove_contact as $value) {
                    $this->Marketplace_model->delete_contact($value);
                    
                }
            }
            else
            {
                foreach ($id_array2 as  $value) {
                    $this->Marketplace_model->delete_contact($value);
                    
                }
            }

           
        }


        $pre_result = $this->Marketplace_model->update_placeholder($data,$_POST['total_id']);
        // print_r($pre_result); exit();
        for ($i=0; $i < count($pre_result) ; $i++) 
            { 
                
                if($pre_result[$i]['logo_url'] == null)
                $pre_result[$i]['logo_url'] = '/assets/images/noimage.jpg';
                // print_r($pre_result[$i]['logo_url']);
            }
        $edit_res['placeholder_data'] = $pre_result;
        // print_r($edit_res);exit();
        $this->load->view('admin/marketplace/mp_dashboard',$edit_res);

   }

    public function add()

    {   

        // print_r($_FILES);
        // print_r($_POST); exit();
        if(isset($_FILES['blog_new_image'])){
            $new_files = $this->reArrayFiles($_FILES['blog_new_image']);
           
        }
        if(isset($_FILES['blog_image'])){
            $old_files = $this->reArrayFiles($_FILES['blog_image']);
            
        }
        
        if(isset($_FILES['contact_new_image'])){
            $new_files_contact = $this->reArrayFiles($_FILES['contact_new_image']);
           
        }
        if(isset($_FILES['contact_image'])){
            $old_files_contact = $this->reArrayFiles($_FILES['contact_image']);
            
        }

        if (!has_permission('marketplace', '', 'edit')) {
               access_denied('Edit');
           }
        
        $folderPath = "uploads/";
    
        if (! is_writable($folderPath) || ! is_dir($folderPath)) {
            echo "error";
             exit();
        }

        ///profile
        $data = [];

        if (isset($_POST['showroom'])){
              $data['showroom'] = 1;
        }
         else{ 
            $data['showroom'] = 0;
        }

         $data['title'] = $_POST['headline'];
         $data['video_url'] = $_POST['title_video'];
         $data['placeholder_name'] = $_POST['name'];
         $data['sub_title'] = $_POST['subtitle'];
         $data['email'] = $_POST['email'];

        if (move_uploaded_file($_FILES["logo"]["tmp_name"], $folderPath . $_FILES["logo"]["name"])) {
            $data['logo_url'] = $folderPath . $_FILES["logo"]["name"];
        }
            
        if (move_uploaded_file($_FILES["title"]["tmp_name"], $folderPath . $_FILES["title"]["name"])) {
             $data['title_pic'] = $folderPath . $_FILES["title"]["name"];
         }
            
        if (move_uploaded_file($_FILES["profile"]["tmp_name"], $folderPath . $_FILES["profile"]["name"])) {
            $data['image_url'] = $folderPath . $_FILES["profile"]["name"];
        }

        if (move_uploaded_file($_FILES["video"]["tmp_name"], $folderPath . $_FILES["video"]["name"])) {
            $data['video_pic'] = $folderPath . $_FILES["video"]["name"];
        }

        //end profile

        ///blog content update and add

        $blog_loaded_id = $this->Marketplace_model->get_blog_id_array($_POST['total_id']);

        for ($i=0; $i < count($blog_loaded_id) ; $i++) { 
            $id_array1[] = $blog_loaded_id[$i]['id']; 
        }
        if(isset($_FILES['blog_image']))
        {

            for ($i=0; $i < count($old_files) ; $i++) { 

            if (move_uploaded_file($old_files[$i]["tmp_name"], $folderPath . $old_files[$i]["name"]))   
                    $data_old_blog[$i]['blog_pic'] = $folderPath . $old_files[$i]["name"];

             $data_old_blog[$i]['blog_txt'] = $_POST['blog_t'][$i];
             $data_old_blog[$i]['blog_headline'] = $_POST['blog_h'][$i];
             $data_old_blog[$i]['link_url'] = $_POST['blog_u'][$i];
             $blog_changed_id[$i]['id'] = $_POST['b_id'][$i];
             $blog_id[$i] = $_POST['b_id'][$i];
             $this->Marketplace_model->update_placeholder_blog($data_old_blog[$i],$blog_id[$i]);
             }
        }

        
       
        if(isset($_FILES['blog_new_image']))
        {
           for ($i=0; $i < count($new_files) ; $i++) { 

            if (move_uploaded_file($new_files[$i]["tmp_name"], $folderPath . $new_files[$i]["name"]))   
                    $data_new_blog[$i]['blog_pic'] = $folderPath . $new_files[$i]["name"];

             $data_new_blog[$i]['blog_txt'] = $_POST['blog_new_t'][$i];
             $data_new_blog[$i]['blog_headline'] = $_POST['blog_new_h'][$i];
             $data_new_blog[$i]['link_url'] = $_POST['blog_new_u'][$i];
             $data_new_blog[$i]['staffid'] = $_POST['total_id'];
             $this->Marketplace_model->insert_single_blog($data_new_blog[$i]);
            }  
        }

        

        if(isset($id_array1))
        {
            if(isset($_POST['b_id']))
            {
                 $remove_blog = array_diff($id_array1,$blog_id);
                  foreach ($remove_blog as $value) {
                    $this->Marketplace_model->delete_blog($value);
                }
            }
            else
            {
                foreach ($id_array1 as  $value) {
                    $this->Marketplace_model->delete_blog($value);
                }
            }

           
        }
        ///end blog
        
        /// contact add and remove


        $contact_loaded_id = $this->Marketplace_model->get_contact_id_array($_POST['total_id']);


        for ($i=0; $i < count($contact_loaded_id) ; $i++) { 
            $id_array2[] = $contact_loaded_id[$i]['id']; 
        }

        if(isset($_FILES['contact_image']))
        {

            for ($i=0; $i < count($old_files_contact) ; $i++) { 

            if (move_uploaded_file($old_files_contact[$i]["tmp_name"], $folderPath . $old_files_contact[$i]["name"]))   
                    $data_old_contact[$i]['contact_pic'] = $folderPath . $old_files_contact[$i]["name"];

             $data_old_contact[$i]['contact_name'] = $_POST['contact_n'][$i];
             $data_old_contact[$i]['contact_phone'] = $_POST['contact_p'][$i];
             $data_old_contact[$i]['contact_email'] = $_POST['contact_e'][$i];
             $contact_changed_id[$i]['id'] = $_POST['c_id'][$i];
             $contact_id[$i] = $_POST['c_id'][$i];
             $this->Marketplace_model->update_placeholder_contact($data_old_contact[$i],$contact_id[$i]);
             }
        }
        // print_r($contact_loaded_id);
        // exit();
        if(isset($_FILES['contact_new_image']))
        {
           for ($i=0; $i < count($new_files_contact) ; $i++) { 

            if (move_uploaded_file($new_files_contact[$i]["tmp_name"], $folderPath . $new_files_contact[$i]["name"]))   
                    $data_new_contact[$i]['contact_pic'] = $folderPath . $new_files_contact[$i]["name"];

             $data_new_contact[$i]['contact_name'] = $_POST['contact_new_n'][$i];
             $data_new_contact[$i]['contact_phone'] = $_POST['contact_new_p'][$i];
             $data_new_contact[$i]['contact_email'] = $_POST['contact_new_e'][$i];
             $data_new_contact[$i]['staffid'] = $_POST['total_id'];
             $this->Marketplace_model->insert_single_contact($data_new_contact[$i]);
            }  
        }
        
        if(isset($id_array2))
        {
            if(isset($_POST['c_id']))
            {
                 $remove_contact = array_diff($id_array2,$contact_id);
                 // print_r($remove_blog);exit();
                  foreach ($remove_contact as $value) {
                    $this->Marketplace_model->delete_contact($value);
                    
                }
            }
            else
            {
                foreach ($id_array2 as  $value) {
                    $this->Marketplace_model->delete_contact($value);
                    
                }
            }

           
        }
        
        $data['staffid'] = $_POST['total_id'];
        $pre = $this->Marketplace_model->get_edit_placeholder($_POST['total_id']);
        if($pre) 
        {
            $pre_result = $this->Marketplace_model->get_placeholder_data();
            for ($i=0; $i < count($pre_result) ; $i++) 
            { 
                
                if($pre_result[$i]['logo_url'] == null)
                $pre_result[$i]['logo_url'] = '/assets/images/noimage.jpg';
                // print_r($pre_result[$i]['logo_url']);
            }  
            $edit_res['placeholder_data'] = $pre_result;

            $this->load->view('admin/marketplace/mp_dashboard',$edit_res);
        }
        else 
        {
            $pre_result = $this->load->Marketplace_model->insert_placeholder($data);
             for ($i=0; $i < count($pre_result) ; $i++) 
            { 
                
                if($pre_result[$i]['logo_url'] == null)
                $pre_result[$i]['logo_url'] = '/assets/images/noimage.jpg';
                // print_r($pre_result[$i]['logo_url']);
            }  
            $edit_res['placeholder_data'] = $pre_result;

            $this->load->view('admin/marketplace/mp_dashboard',$edit_res);
        }

   }
  
   public function products_load() {

        if (!has_permission('marketplace', '', 'edit')) 
        {
               access_denied('Edit');
           }

        else
        {
            
           $fetch_data = $this->Marketplace_model->products_make_datatables($_POST['total_id']);
           // print_r($fetch_data); exit();
            $data =  array();
            foreach ($fetch_data as $rows) {
                $sub_data = array();
                // $sub_data['id'] = $rows->id;
                $sub_data['product_name'] = $rows->products_name;
                // $sub_data['product_url'] = $rows->products_url;
                 // $sub_data['update'] = '<button type="button" name="update" id="'.$rows->id.'" class="btn btn-warning btn-xs update">Update</button>';
                $sub_data['delete'] = '<button type="button" name="delete" id="'.$rows->id.'" class="btn btn-danger btn-xs delete">Delete</button>'; 
                $data[] = $sub_data;
            }
            $output = array(
                "draw"                  =>    intval($_POST["draw"]),
                "recordsTotal"          =>    $this->Marketplace_model->products_get_all_data($_POST['total_id']),
                "recordsFiltered"       =>    $this->Marketplace_model->products_get_filtered_data($_POST['total_id']),
                "data"                  =>    $data
            );
            echo json_encode($output);  
        }
        
   }


   public function products_add(){
    
        if (!has_permission('marketplace', '', 'edit')) 
        {
               access_denied('Edit');
        }
        else
        {
                $new_data = array();
                $new_data['staffid'] = $_POST['total_id'];
                $folderPath = "uploads/";
                if (move_uploaded_file($_FILES["pdf"]["tmp_name"], $folderPath . $_FILES["pdf"]["name"])) {
                    $new_data['products_name'] =  $_FILES["pdf"]["name"];
                    $new_data['products_url'] = $folderPath . $_FILES["pdf"]["name"];
                }
                $result = $this->Marketplace_model->products_insert($new_data);
                echo("Products Added Successfully");
            
           
        }
   }

   public function products_single_get()
   {
        $output =  array();
        $data = $this->Marketplace_model->products_single_get($_POST['products_id']);
        foreach ($data as $rows) {

            $output['products_name'] = $rows->products_name;
            $output['products_url'] = $rows->products_url;

        }
        echo json_encode($output);
   }
   public function products_single_remove()
   {
        // print_r($_POST);exit();
        $remove_data = $this->Marketplace_model->products_single_remove($_POST['products_id']);
        echo ("Products Deleted Successfully");
   }
}
?>
