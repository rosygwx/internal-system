<?php
/**
  * Client Model
*/

class Company_model extends CI_Model{

	public function __construct(){
		$this->load->database();
		$this->company_table = 'company';
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
		$this->db->where( 'isdeleted' , 0);

		if( is_array( $like ) ){
			foreach($like as $value){
				if( isset($value['name']) && isset($value['value'])){
					$this->db->like( $value['name'], $value['value']);
				}
			}
		}

		if ( is_array($orderby) ){
			foreach($orderby as $order => $sort){
				$this->db->order_by( $order , $sort );
			}
		}

		$query = $this->db->get( $this->company_table , $pagesize , ($page-1) * $pagesize);

		$total = $this->db->query('SELECT FOUND_ROWS() count;');

		$reuslt = array();
		if($json)
			$result = $query->result();
		else
			$result = $query->reuslt_array();

		return array( 'result' => $result , 'total' => $total->row()->count );
	}

	public function add($data) {
		$filds = array('company_name','client_name','type','phone','email','address','city','state','postcode','addtime');
		foreach($filds as $value){
			if( isset($data[$value])  ){
				$newarr[$value] = $data[$value];
			}
			else
				continue;
		}
		$this->db->insert($this->company_table, $newarr); 
		//echo $this->db->last_query();		
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
		return  $this->db->update($this->company_table, $newarr , $where); 
	}
}