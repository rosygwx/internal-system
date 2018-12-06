<?php
/**
  * Truck Model
*/

class Truck_model extends CI_Model{

	public function __construct(){
		$this->load->database();
		$this->truck_table = 'truck';
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

		$this->db->where( 'truck.isdeleted' , 0 );
		// $this->db->where( 'company.isdeleted' , 0 );
		// $this->db->where( 'task.isdeleted' , 0 );
		// $this->db->where( 'task_cat.isdeleted' , 0 );

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

		$query = $this->db->get( $this->truck_table , $pagesize , ($page-1) * $pagesize);
		//echo $this->db->last_query();
		$total = $this->db->query('SELECT FOUND_ROWS() count;');

		$reuslt = array();
		if($json)
			$result = $query->result();
		else
			$result = $query->reuslt_array();

		return array( 'result' => $result , 'total' => $total->row()->count );
	}


	public function add($data) {
		$filds = array('ctr_unique_id', 'cityid','mername', 'meroname', 'date','bdate', 'edate','ad','note_ctr' ,'mem_sign' ,'mem_sign' , 'mem_fill','mem_renewal','mer_sign', 'mer_title', 'mer_contact', 'attach_ctr', 'address', 'phone', 'email','tencent',  'business', 'introduction', 'pic', 'note_mer','attach_mer', 'amount_total', 'note_pay', 'addtime');
		foreach($filds as $value){
			if( isset($data[$value])  ){
				$newarr[$value] = $data[$value];
			}
			else
				continue;
		}
		$this->db->insert($this->schedule_table, $newarr); 
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	
	public function update($data,$where){
		$filds = array('ctr_unique_id','cityid','mername','meroname', 'date','bdate','edate','ad','note_ctr','mem_sign','mem_fill','mem_renewal','mer_sign','mer_contact','attach_ctr','address','phone','email','tencent',  'business', 'introduction', 'pic', 'note_mer','attach_mer', 'amount_total', 'note_pay','mem_ad','isuse_ad' , 'ispaid' , 'uptime');
		foreach($filds as $value){
			if(isset($data[$value])){
				$newarr[$value] = $data[$value];
			}else
				continue;
		}
		return  $this->db->update($this->schedule_table, $newarr , $where); 
	}


	public function test(){
		$query = $this->db->get( $this->contract_table );
		//$result = $this->db->query($query);
		var_dump($query->result());
		//var_dump($result->result());
	}
}



?>