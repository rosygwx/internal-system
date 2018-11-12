<?php
/**
  * Contract Model
*/

class Contract_model extends CI_Model{

	public function __construct(){
		$this->load->database();
		$this->contract_table = 'contract';
		$this->schedule_table = 'schedule';
	}


	public function lists( $fields = '*' , $where = '', $like = '', $json = true, $orderby = array('contract.contract_id'=>'desc'), $page = 1, $pagesize = 500 ){
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
		$this->db->where( 'contract.isdeleted' , 0 );
		$this->db->where( 'company.isdeleted' , 0 );

		if( is_array( $like ) ){
			foreach($like as $value){
				if( isset($value['name']) && isset($value['value'])){
					$this->db->like( $value['name'], $value['value']);
				}
			}
		}
		$this->db->join('company','company.company_id = contract.company_id','left');

		if($where['company.type'] == 1){
		$this->db->join('(select contract.contract_id, avg(task.unit_price / (task.mile * task_cat.index_centerline)) as price_per_mile
	from task
	left join task_cat 
	on task.tcat_id = task_cat.tcat_id
    left join contract
    on task.contract_id = contract.contract_id
    where task.isdeleted = 0
    group by task.contract_id) as a','a.contract_id = contract.contract_id');//price per mile
	}else{
		$this->db->join("task", 'contract.contract_id = task.contract_id','left');
		$this->db->where( 'task.isdeleted' , 0 );
		$this->db->group_by('contract.contract_id');
	}

		if ( is_array($orderby) ){
			foreach($orderby as $order => $sort){
				$this->db->order_by( $order , $sort );
			}
		}

		$query = $this->db->get( $this->contract_table , $pagesize , ($page-1) * $pagesize);
		//echo $this->db->last_query();
		$total = $this->db->query('SELECT FOUND_ROWS() count;');
		//echo $this->db->last_query();
		$reuslt = array();
		if($json)
			$result = $query->result();
		else
			$result = $query->result_array();
		//var_dump($result);
		return array( 'result' => $result , 'total' => $total->row()->count );
	}


	public function add($data, $type=1) {
		$filds = $type == 1 ? array('contract_id_pannell','contract_id_ori','company_id','quote_real','office','status','sign_date','bdate','year','addtime') : array('contract_id_pannell', 'poc', 'pon', 'company_id', 'status','travel_hour','cancel_hour','office');
		foreach($filds as $value){
			if( isset($data[$value])  ){
				$newarr[$value] = $data[$value];
			}
			else
				continue;
		}
		$this->db->insert($this->contract_table, $newarr); 
		//echo $this->db->last_query();exit;
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	
	public function revenue(){
		$query = 'select company.company_name , contract.contract_id_pannell, a.contract_id, contract.quote_real, sum(earned_contract) as "earned_contract", concat(format(sum(earned_contract) / contract.quote_real * 100 ,2 ) , "%") as "revenue_real", 100* (timestampdiff(day,contract.bdate,now()))/(timestampdiff(day,contract.bdate,date_add(contract.bdate, interval contract.month month))) as "revenue_standard", sum(earned_current_month) as "earned_current_month", sum(current_month) as "current_month", format(sum(earned_current_month)/sum(current_month) * 100 ,2 ) as "current_process"

		from (select task.task_id, task.contract_id, task.unit_price * sum(case when schedule.date is not null then 1 else 0 end) as earned_contract, 
        task.unit_price * sum(case when year(schedule.date) = year(now()) and month(schedule.date) = month(now()) then 1 else 0 end) as earned_current_month, 
        task.unit_price * sum(case when schedule.schedule_year = year(now()) and schedule.schedule_month = month(now()) then 1 else 0 end) as current_month
        
	from schedule 
	left join task
	on task.task_id = schedule.task_id
	left join task_cat 
	on task.tcat_id = task_cat.tcat_id
    where task.isdeleted = 0
		and schedule.isdeleted = 0
	group by schedule.task_id) a
	left join contract
	on contract.contract_id = a.contract_id
	left join company
	on company.company_id = contract.company_id
	
where contract.isdeleted = 0
and company.type = 1	
group by a.contract_id
	;';

		$res = $this->db->query($query);
		return $res->result();

	}


	public function revenue_commercial(){
		$query = 'select ctr.contract_id, ctr.contract_id_pannell, sum(totalTask) sum , sum(totalCurrentTask) sum_current, sum(hourTask) sum_hour
from task
right join
	(select sc.schedule_id, task.task_id,
	sum((case when tr.type = 4 then sc.traffic_control_price  else task.unit_price end) * sc.billing_hour) + (case when sc.disposal_price is null then 0 else sc.traffic_control_price end) totalTask, 
    sum((case when tr.type = 4 
				then 
					case when year(sc.date) = year(now()) and month(sc.date) = month(now()) then sc.traffic_control_price else 0 end 
				else 
					case when year(sc.date) = year(now()) and month(sc.date) = month(now()) then sc.unit_price else 0 end 
				end) * sc.billing_hour)  + (case when sc.disposal_price is null then 0 else sc.disposal_price end) totalCurrentTask,
	sum(sc.billing_hour) hourTask


	from worklog wl
		left join schedule sc
		on wl.schedule_id = sc.schedule_id
		left join task
		on sc.task_id = task.task_id
		left join truck tr
		on wl.truck_id = tr.truck_id
        
		
	where wl.isdeleted = 0
		and sc.isdeleted = 0
		and task.isdeleted = 0
		and tr.isdeleted = 0
        and (sc.status = 1 or sc.status = 2)
		group by sc.schedule_id) a
	on a.task_id = task.task_id
    left join contract ctr
    on ctr.contract_id = task.contract_id
    left join company com
    on com.company_id = ctr.company_id
    where com.type = 2
    group by task.contract_id
    order by sum desc

       ;';

		$res = $this->db->query($query);
		return $res->result();

	}

/*

	public function revenue_commercial(){
			$query = 'select contract.contract_id, contract.contract_id_pannell, sum(schedule.billing_hour * (case when schedule.ifschedule = 1 then task.unit_price else task.unit_price_2 end)) sum, sum(schedule.billing_hour) as sum_hour, sum(case when schedule.ifschedule = 1 then 1 else 0 end) / count(schedule.schedule_id) as schedule, count(schedule_id) count, sum(case when month(schedule.date) = month(now()) then schedule.billing_hour * (case when schedule.ifschedule = 1 then task.unit_price else task.unit_price_2 end) else 0 end) as current_month
from schedule
left join task
on task.task_id = schedule.task_id
left join contract
on contract.contract_id = task.contract_id
left join company
on company.company_id = contract.company_id
where company.type = 2
and schedule.status = 1
and schedule.isdeleted = 0
and task.isdeleted = 0
and contract.isdeleted = 0
and company.isdeleted = 0
group by contract.contract_id
order by sum desc;';

		$res = $this->db->query($query);
		return $res->result();


	}

*/	
	
	public function update($data,$where,$type=1){
		if(!$where){
			return false;
		}else{
			$filds = $type == 1 ? array('contract_id_pannell','contract_id_ori','company_id','quote_real','office','status','sign_date') : array('contract_id_pannell', 'poc', 'pon', 'status','travel_hour','cancel_hour', 'office');
			foreach($filds as $value){
				if(isset($data[$value])){
					$newarr[$value] = $data[$value];
				}else
					continue;
			}
			//var_dump($newarr);exit;
			return  $this->db->update($this->contract_table, $newarr , $where); 
		}
	}

	public function bill( $fields = '*' , $where = '', $like = '', $json = true, $orderby = array('task.tcat_id'=>'asc'), $page = 1, $pagesize = 500 ){
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
		
		$this->db->where( 'schedule.isdeleted' , 0 );
		$this->db->where( 'task.isdeleted' , 0 );
		$this->db->where( 'contract.isdeleted' , 0 );
		$this->db->where( 'company.isdeleted' , 0 );

		if( is_array( $like ) ){
			foreach($like as $value){
				if( isset($value['name']) && isset($value['value'])){
					$this->db->like( $value['name'], $value['value']);
				}
			}
		}
		$this->db->join('task','task.task_id = schedule.task_id');
		$this->db->join('task_cat','task_cat.tcat_id = task.tcat_id');
		$this->db->join('contract','contract.contract_id = task.contract_id');
		$this->db->join('company','company.company_id = contract.company_id');
		


		if ( is_array($orderby) ){
			foreach($orderby as $order => $sort){
				$this->db->order_by( $order , $sort );
			}
		}

		$query = $this->db->get( $this->schedule_table , $pagesize , ($page-1) * $pagesize);
		//echo $this->db->last_query();
		$total = $this->db->query('SELECT FOUND_ROWS() count;');

		$reuslt = array();
		if($json)
			$result = $query->result();
		else
			$result = $query->result_array();
		//var_dump($result);
		return array( 'result' => $result , 'total' => $total->row()->count );
	}


	public function test(){
		$query = $this->db->get( $this->contract_table );
		//$result = $this->db->query($query);
		var_dump($query->result());
		//var_dump($result->result());
	}
}



?>