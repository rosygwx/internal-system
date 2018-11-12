<?php
/**
  * Work Log Model
*/

class Worklog_model extends CI_Model{

	public function __construct(){
		$this->load->database();
		$this->worklog_table = 'worklog';
	}


	public function lists( $fields = '*' , $where = '', $like = '', $json = true, $orderby = array('id'=>'desc'), $page = 1, $pagesize = 500, $groupby = '' ){
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
		$this->db->where('worklog.isdeleted', 0);
		$this->db->where('schedule.isdeleted', 0);
		$this->db->where('employee.isdeleted', 0);
		//$this->db->where('truck.isdeleted', 0);//whyyyyyyyy?????
		$this->db->where('task.isdeleted', 0);
		$this->db->where('task_cat.isdeleted', 0);
		$this->db->where('contract.isdeleted', 0);
		$this->db->where('company.isdeleted', 0);
		

		if( is_array( $like ) ){
			foreach($like as $value){
				if( isset($value['name']) && isset($value['value'])){
					$this->db->like( $value['name'], $value['value']);
				}
			}
		}
		$this->db->join('schedule','worklog.schedule_id = schedule.schedule_id','left');
		$this->db->join('employee','worklog.employee_id = employee.employee_id','left');
		$this->db->join('truck','worklog.truck_id = truck.truck_id','left');
		$this->db->join('task','schedule.task_id = task.task_id','left');
		$this->db->join('task_cat','task.tcat_id = task_cat.tcat_id','left');
		$this->db->join('contract','task.contract_id = contract.contract_id','left');
		$this->db->join('company','contract.company_id = company.company_id','left');

		if ( is_array($orderby) ){
			foreach($orderby as $order => $sort){
				$this->db->order_by( $order , $sort );
			}
		}


		if ( $groupby ){
			$this->db->group_by( $groupby );
		}


		//$total = $this->db->query('SELECT FOUND_ROWS() count;');
		$total = $this->db->count_all_results($this->worklog_table, false);
		$query = $this->db->get( '', $pagesize , ($page-1) * $pagesize);
		//echo $this->db->last_query();
		

		$reuslt = array();
		if($json)
			$result = $query->result();
		else
			$result = $query->reuslt_array();

		return array( 'result' => $result , 'total' => $total );
	}


	public function listsTxdot( $fields = '*' , $where = '', $like = '', $json = true, $orderby = array('id'=>'desc'), $page = 1, $pagesize = 500, $groupby = '' ){
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

		$this->db->where('worklog.isdeleted', 0);

		if( is_array( $like ) ){
			foreach($like as $value){
				if( isset($value['name']) && isset($value['value'])){
					$this->db->like( $value['name'], $value['value']);
				}
			}
		}

		$this->db->join('employee','worklog.employee_id = employee.employee_id','left');
		$this->db->join('truck','worklog.truck_id = truck.truck_id','left');

		if ( is_array($orderby) ){
			foreach($orderby as $order => $sort){
				$this->db->order_by( $order , $sort );
			}
		}


		if ( $groupby ){
			$this->db->group_by( $groupby );
		}


		$total = $this->db->count_all_results($this->worklog_table, false);
		$query = $this->db->get( '', $pagesize , ($page-1) * $pagesize);
		
		//echo $this->db->last_query();
		

		$reuslt = array();
		if($json)
			$result = $query->result();
		else
			$result = $query->reuslt_array();

		return array( 'result' => $result , 'total' => $total );
	}


	public function add($data) {
		$filds = array('schedule_id', 'contract_id', 'schedule_date', 'category', 'ticket_id', 'employee_id','truck_id', 'employee_rate', 'ifbill','status','addtime');
		foreach($filds as $value){
			if( isset($data[$value])  ){
				$newarr[$value] = $data[$value];
			}
			else
				continue;
		}
		$this->db->insert($this->worklog_table, $newarr); 
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	public function add_batch($data) {
		$filds = array('schedule_id', 'ticket_id', 'employee_id','truck_id', 'employee_rate', 'ifbill','status','addtime');
		$newarr = [];
		foreach($data as $da){
			foreach($filds as $value){
				if( isset($da[$value])  ){
					$newarrrrr[$value] = $da[$value];
				}else
					continue;
			}
			$newarr[] = $newarrrrr;
		}
		$this->db->insert_batch($this->worklog_table, $newarr); 
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	
	public function update($data,$where){
		$filds = array('ticket_id', 'employee_id','truck_id', 'employee_rate', 'ifbill','status', 'isdeleted');
		foreach($filds as $value){
			if(isset($data[$value])){
				$newarr[$value] = $data[$value];
				//$newarr[$value] = 'worklog.'.$data[$value];
			}else
				continue;
		}
		//$this->db->join('employee', 'employee.employee_id = worklog.employee_id');
		return  $this->db->update($this->worklog_table, $newarr , $where); 
	}

	public function delete($where){
		if($where['schedule_id'] || $where['wl_id']){
			$data['isdeleted'] = 1;
			return $this->db->update($this->worklog_table, $data , $where);
		}else{
			return false;
		}
	}

	public function nextTicket(){
		$this->db->select_max('ticket_id');
		$this->db->where('isdeleted', 0);
		$result = $this->db->get($this->worklog_table);
		return $result->row()->ticket_id + 1;
	}

	/**/

	public function test(){
		$query = $this->db->get( $this->worklog_table );
		//$result = $this->db->query($query);
		var_dump($query->result());
		//var_dump($result->result());
	}
}



?>