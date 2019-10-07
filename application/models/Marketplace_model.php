<?php

	
	class Marketplace_model	 extends CI_Model
	{

		function get_placeholder_data(){

			$query = $this->db->query("select * from tblplaceholder");
			return $query->result_array();

		} 

		function get_home_placeholder($id){

			$query = $this->db->query("select * from tblplaceholder where staffid='$id'");
			return $query->result_array();
			
		}

		function get_home_placeholder_blog($id){

			$query = $this->db->query("select * from tblplaceholder_blog where staffid ='$id'");
			return $query->result_array();
			
		}
		function get_home_placeholder_contact($id){

			$query = $this->db->query("select * from tblplaceholder_contact where staffid ='$id'");
			return $query->result_array();
			
		}

		function get_home_placeholder_product($id){

			$query = $this->db->query("select id,staffid,products_name,products_url from tblplaceholder_products where staffid ='$id'");
			return $query->result_array();
			
		}

		// edit page functions
		function get_edit_placeholder($id){

			$query = $this->db->query("select * from tblplaceholder where staffid='$id'");
			// return $query->result();
			return $query->result_array();
		}

		
		

		function update_placeholder($data,$id){
			$this->db->where('staffid',$id);
			$this->db->update('tblplaceholder',$data);
			$query = $this->db->query("select * from tblplaceholder");
			return $query->result_array();
		    // return get_placeholder_data();
			// return $query;
		}

		function insert_placeholder($data){
			$this->db->insert('tblplaceholder',$data);
			$query = $this->db->query("select * from tblplaceholder");
			return $query->result_array();
		}

		function insert_single_blog($data){
			$this->db->insert('tblplaceholder_blog',$data);
		}
		function update_placeholder_blog($data,$id){
			$this->db->where('id',$id);
			$this->db->update('tblplaceholder_blog',$data); 
		}

		function insert_single_contact($data){
			$this->db->insert('tblplaceholder_contact',$data);
		}
		function update_placeholder_contact($data,$id){
			$this->db->where('id',$id);
			$this->db->update('tblplaceholder_contact',$data); 
		}

		function get_blog_id_array($id){
			
			$query = $this->db->query("select id from tblplaceholder_blog where staffid='$id'");
			return $query->result_array();
		}

		function get_contact_id_array($id){
			
			$query = $this->db->query("select id from tblplaceholder_contact where staffid='$id'");
			return $query->result_array();
		}

		function delete_blog($id){
			$this->db->where("id",$id);
			$this->db->delete('tblplaceholder_blog');
		}
		function delete_contact($id){
			$this->db->where("id",$id);
			$this->db->delete('tblplaceholder_contact');
		}

		// edit products variables and fuctions
		var $table = "tblplaceholder_products";
		var $select_column =  array("id","products_name","products_url");
		var $order_column = array(null,"products_name","products_url",null,null);


		function products_make_query($id){
			$this->db->select($this->select_column);
			$this->db->from($this->table);
			$this->db->where("staffid",$id);
			if (isset($_POST["search"]["value"])) {
				$this->db->like("products_name",$_POST["search"]["value"]);
			}
			if (isset($_POST["order"])) {
				$this->db->order_by($this->order_column[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
			}
			else{
				$this->db->order_by("id","DESC");

			}

		}
	
		function products_make_datatables($id){
			$this->products_make_query($id);
			if($_POST['length']!= -1){
				$this->db->limit($_POST['length'],$_POST['start']);
			}
			$query = $this->db->get();
			return $query->result();
		}

		function products_get_filtered_data($id){
			$this->products_make_query($id);
			$query = $this->db->get();
			return $query->num_rows();
		}
		function products_get_all_data($id)
		{
			$this->db->select("*");
			$this->db->from($this->table);
			$this->db->where("staffid",$id);
			return $this->db->count_all_results();
		}

		function products_insert($data){
			$this->db->insert('tblplaceholder_products',$data);
			// $query = $this->db->query("select id,products_name,products_url from tblplaceholder_products");
			// return $query->result_array();
		}
		function products_update($data,$products_id){
			$this->db->where("id",$products_id);
			$this->db->update('tblplaceholder_products',$data);
		}
		function products_single_get($products_id){
			$this->db->where("id",$products_id);
			$query = $this->db->get('tblplaceholder_products'); 
			return $query->result();

		}
		function products_single_remove($products_id){
			$this->db->where("id",$products_id);
			$query = $this->db->delete('tblplaceholder_products'); 
			// return $query->result();

		}


	}

?>