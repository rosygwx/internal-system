<?php
/**
  * Contract Model
*/

class Color_model extends CI_Model{

	public function __construct(){
		$this->load->database();
		$this->color_table = 'colorcode';
	}


	public function lists( $fields = '*' , $where = '', $like = '', $json = true, $orderby = array('id'=>'desc'), $page = 1, $pagesize = 500 ){
		if( is_array( $fields ) ) {
			$fld = implode( ',' , $fields );
		}else
			$fld = $fields;
		
		$this->db->select( $fld , false);


		if( is_array( $where ) ) {
			foreach ( $where as $key => $value ) {
				if(is_array( $value )){
					$this->db->where_in( $key, $value);
				}else{
					$this->db->where( $key , $value );
				}
			}
		}

		if( is_array( $like ) ){
			foreach($like as $value){
				if( isset($value['name']) && isset($value['value'])){
					$this->db->like( $value['name'], $value['value']);
				}
			}
		}
		//$this->db->join('client','client.client_id = contract.client_id');

		if ( is_array($orderby) ){
			foreach($orderby as $order => $sort){
				$this->db->order_by( $order , $sort );
			}
		}

		$query = $this->db->get( $this->color_table , $pagesize , ($page-1) * $pagesize);

		$total = $this->db->query('SELECT FOUND_ROWS() count;');

		$reuslt = array();
		if($json)
			$result = $query->result();
		else
			$result = $query->result_array();

		return array( 'result' => $result , 'total' => $total->row()->count );
	}


	public function add($data) {
		$filds = array('office','colorname','isused','isdeleted');
		foreach($filds as $value){
			if( isset($data[$value])  ){
				$newarr[$value] = $data[$value];
			}
			else
				continue;
		}
		$this->db->insert($this->color_table, $newarr); 
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	
	public function update($data,$where){
		$filds = array('office','colorname','isused','isdeleted');
		foreach($filds as $value){
			if(isset($data[$value])){
				$newarr[$value] = $data[$value];
			}else
				continue;
		}
		return  $this->db->update($this->color_table, $newarr , $where); 
	}


	public function test(){
		$query = $this->db->get( $this->color_table );
		//$result = $this->db->query($query);
		var_dump($query->result());
		//var_dump($result->result());
	}
}



?>