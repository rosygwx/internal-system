<?php
/**
  * Contract Model
*/

class Schedule_model extends CI_Model{

	public function __construct(){
		$this->load->database();
		$this->load->model('worklog_model');
		$this->schedule_table = 'schedule';
		$this->task_table = 'task';
	}


	public function lists( $fields = '*' , $where = '', $like = '', $json = true, $orderby = array('schedule.addtime'=>'desc'), $page = 1, $pagesize = 500, $where_in = '', $distinct = 0){
		if( is_array( $fields ) ) {
			$fld = implode( ',' , $fields );
		}else
			$fld = $fields;
		
		$selectsql = $this->db->select( $fld , false);


		if( is_array( $where ) ) {
			foreach ( $where as $key => $value ) {
				if(is_array( $value )){
					$wheresql[] = $this->db->where_in( $key, $value);
				}else{
					$wheresql[] = $this->db->where( $key , $value );
				}
			}
		}

		$wheresql[] = $this->db->where( 'schedule.isdeleted' , 0 );
		$wheresql[] = $this->db->where( 'contract.isdeleted' , 0 );
		$wheresql[] = $this->db->where( 'company.isdeleted' , 0 );
		$wheresql[] = $this->db->where( 'task.isdeleted' , 0 );
		$wheresql[] = $this->db->where( 'task_cat.isdeleted' , 0 );

		if( is_array( $like ) ){
			foreach($like as $value){
				if( isset($value['name']) && isset($value['value'])){
					$likesql[] = $this->db->like( $value['name'], $value['value']);
				}
			}
		}
		$joinsql[] = $this->db->join('task','schedule.task_id = task.task_id');
		$joinsql[] = $this->db->join('task_cat','task.tcat_id = task_cat.tcat_id');
		$joinsql[] = $this->db->join('contract','task.contract_id = contract.contract_id');
		$joinsql[] = $this->db->join('company','contract.company_id = company.company_id');

		if ( is_array($orderby) ){
			foreach($orderby as $order => $sort){
				$ordersql[] = $this->db->order_by( $order , $sort );
			}
		}


		/*if( is_array($where_or) ){
			foreach($where_or as $k=>$v){
				for($i = 0; $i < count($v); $i++){
					$i == 0 ? $this->db->where($k, "'".$v[$i]."'", FALSE) : $this->db->or_where($k, "'".$v[$i]."'", FALSE);
				}				
			}
			
		}*/

		/*if( is_array($where_or ) ){
			foreach($where_or as $w){
				$this->db->where($w);
			}		
		}*/

		if( is_array($where_in )){
			foreach($where_in as $key => $value){
				$this->db->where_in($key, $value);
			}
		}


		$distinct ? $this->db->distinct() : '';
		//$total =$this->db->get( $this->schedule_table  )->num_rows();
		//$total = $this->db->query('SELECT FOUND_ROWS() count;');
		$total = $this->db->count_all_results($this->schedule_table, false);
		//var_dump($this->db->last_query());
		$query = $this->db->get( '', $pagesize , ($page - 1) * $pagesize );

		//var_dump($this->db->last_query());
		
		$reuslt = array();
		if($json)
			$result = $query->result();
		else
			$result = $query->reuslt_array();

		return array( 'result' => $result , 'total' => $total);
	}


	public function add($data) {
		$filds = array('task_id', 'schedule_year', 'schedule_month', 'schedule_week', 'schedule_date', 'btime_req', 'btime', 'etime', 'working_hour', 'traffic_hour', 'billing_hour', 'comment', 'location', 'contact', 'ifschedule', 'travel_hour', 'traffic_control_price', 'disposal_price', 'unit_price', 'pon', 'addtime');
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

	public function add_batch($data) {
		$filds = array('task_id', 'schedule_year', 'schedule_month', 'schedule_week', 'schedule_date', 'mindate', 'maxdate', 'unit_price', 'addtime');
		$newarr = [];
		$task_id = [];
		$update_task = [];
		foreach($data as $da){
			foreach($filds as $value){
				if( isset($da[$value])  ){
					$newarrrrr[$value] = $da[$value];
				}else
					continue;
			}
			$newarr[] = $newarrrrr;
			
			//if(!in_array($da['task_id'], $task_id)) { array_push($update_task,  ['task_id' => $da['task_id'] , 'ifweek' => 1]) ; $task_id[] = $da['task_id'];}
		}
		//var_dump($newarr,$task_id,$update_task);
		$res = $this->db->insert_batch($this->schedule_table, $newarr); 
		//var_dump($res);
		//echo $this->db->last_query();

		$insert_id = $this->db->insert_id();

		//update ifweek in table task
		//$this->db->update_batch($this->task_table, $update_task, 'task_id');

		if($res)
			return true;
		else
			return false;
	}
	
	
	public function update($data,$where){
		$filds = array('task_id', 'schedule_year', 'schedule_month', 'schedule_week', 'schedule_date', 'date', 'btime_req', 'btime', 'etime', 'working_hour', 'travel_hour', 'billing_hour', 'comment', 'contact', 'location', 'ifschedule', 'traffic_control_price', 'disposal_price', 'unit_price', 'pon', 'status');
		foreach($filds as $value){
			if(isset($data[$value])){
				$newarr[$value] = $data[$value];
			}else
				continue;
		}
		return  $this->db->update($this->schedule_table, $newarr , $where); 
	}

	public function delete($id){
		if($id){
			$where['schedule_id'] = $id;
			$newarr['isdeleted'] = 1;
			$res = $this->db->update($this->schedule_table, $newarr , $where);

			if($res){
				return $this->worklog_model->delete($where);
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	//get bonding by last day of last month
	public function getBonding(){
	  	$sql = 'select sum(earned) earned, sum(quote_real) quoted from
				(
			    select contract_id, quote_real, sum(earned_contract) earned from
					(
			        select task.contract_id, contract.quote_real, task.unit_price * sum(case when schedule.date <= last_day(now() - interval 1 month) then 1 else 0 end) as earned_contract
					from schedule
					left join task
					on schedule.task_id = task.task_id
			        left join contract
					on contract.contract_id = task.contract_id
					left join company
					on company.company_id = contract.company_id
					where company.type = 1
					and ((contract.contract_id = 7 and schedule.schedule_date <= "2018-03-31")
						or (contract.contract_id = 13 and schedule.schedule_date <= "2017-09-30")
						or company.company_id in (1,4,5,6,9))
			        
					group by task.task_id
			        ) a
			    group by a.contract_id
			    ) b;' ;
		$res = $this->db->query($sql);
		$res = $res->result();
		$quoted = $res[0]->quoted;
		$earned = $res[0]->earned;
		$bonding = BONDING - $quoted + $earned;
		return $bonding;
	}



	public function test(){
		$this->task_lists_fields = 'SQL_CALC_FOUND_ROWS contract.year,contract.month,contract.bdate,task.contract_id,task.task_id,task.cycle';
		$arr_task_id = range(1374,1374);
		$where['task_id'] = $arr_task_id;
			$task_res = $this->task_model->lists($this->task_lists_fields, $where, $like, $json = true, $orderby, $page, $pagesize);
			var_dump($task_res);
			//assign year and week to schedule
			foreach($task_res['result'] as $res){
				
				$ini_year = date('Y',strtotime($res->bdate));
				$ini_month = date('n',strtotime($res->bdate));

				//only for harris county
				if($res->contract_id == 11 && $res->cycle == 18){
					$ini_year = 2017;
					$ini_month = 3;
				}

				//settttttttingggggggggg
				$settle_to_next_week_1 = 4;//Thursday
				$settle_to_next_week_14 = 3;//one day before $settle_to_next_week_1

				//var_dump($ini_year,$ini_month);//testing
				for( $i=1; $i <= $res->cycle ; $i++){
					if($ini_month <= 12){
						$modi_year = $ini_year;
						$modi_month = $ini_month;
					}else{
						$modi_year = $ini_year = $ini_year + 1;
						$modi_month = $ini_month = $ini_month - 12;
					}
					//var_dump($res->cycle,$i);
					//switch ($res->cycle/$res->year) {//cycle per year
					switch ($res->cycle/$res->month) {//cycle per month
						

						//case 12://cycle per year
						case 1://cycle per month
							$work_weekday = $modi_year.'-'.$modi_month.'-'.'1';//1st week
							if(date("N", strtotime($work_weekday)) >= $settle_to_next_week_1) $work_weekday = date('Y-m-d', strtotime('+1 week',strtotime($work_weekday)));
							$ini_month += 1 ;
							var_dump(date("N", strtotime($work_weekday)));
							break;
						
						//case 18://cycle per year
						case 1.5://cycle per month
							if($i % 3 == 1){
								$work_weekday = $modi_year.'-'.$modi_month.'-'.'1';//1st week
								if(date("N", strtotime($work_weekday)) >= $settle_to_next_week_1) $work_weekday = date('Y-m-d', strtotime('+1 week',strtotime($work_weekday)));
							}elseif($i % 3 == 2){
								$work_weekday = $modi_year.'-'.$modi_month.'-'.'14';//3rd week
								if(date("N", strtotime($work_weekday)) >= $settle_to_next_week_14) $work_weekday = date('Y-m-d', strtotime('+1 week',strtotime($work_weekday)));
								$ini_month += 1 ;
							}else{
								$work_weekday = $modi_year.'-'.$modi_month.'-'.'1';//1st week
								if(date("N", strtotime($work_weekday)) >= $settle_to_next_week_1) $work_weekday = date('Y-m-d', strtotime('+1 week',strtotime($work_weekday)));
								$ini_month += 1 ;
							}
							break;

						//case 24://cycle per year
						case 2://cycle per month
							if($i % 2 == 1){
								$work_weekday = $modi_year.'-'.$modi_month.'-'.'1';//1st week
								if(date("N", strtotime($work_weekday)) >= $settle_to_next_week_1) $work_weekday = date('Y-m-d', strtotime('+1 week',strtotime($work_weekday)));
							}else{
								$work_weekday = $modi_year.'-'.$modi_month.'-'.'14';//3rd week
								if(date("N", strtotime($work_weekday)) >= $settle_to_next_week_14) $work_weekday = date('Y-m-d', strtotime('+1 week',strtotime($work_weekday)));
								$ini_month += 1 ;
							}	
							break;

						default://2,6,
							$work_weekday = $modi_year.'-'.$modi_month.'-'.'14';//3rd week
							if(date("N", strtotime($work_weekday)) >= $settle_to_next_week_14) $work_weekday = date('Y-m-d', strtotime('+1 week',strtotime($work_weekday)));
							
							//$ini_month += 12 * $res->year / $res->cycle ;//per year
							$ini_month += $res->month / $res->cycle ;//per month
							if(!is_int($ini_month)){
								//only for harris county
								if($res->contract_id == 11 && $res->cycle == 18){
									$work_weekday = $modi_year.'-'.$modi_month.'-'.'1';//1st week
									if(date("N", strtotime($work_weekday)) >= $settle_to_next_week_1) $work_weekday = date('Y-m-d', strtotime('+1 week',strtotime($work_weekday)));
									$ini_month += 1 ;
									break;
								}
								else
								$this->redirect(BASE_URL().'schedule/lists','ask IT (month mod)'.$res->cycle);exit;
							}
							break;
					}
					
					$insert_data['schedule_year'] = $modi_year;
					$insert_data['schedule_week'] = ltrim(date('W', strtotime($work_weekday)), '0') != 52 ? ltrim(date('W', strtotime($work_weekday)), '0') : 1;
					$insert_data['task_id'] = $res->task_id;
					$insert_data['addtime'] = date('Y-m-d H:i:s');
					$insert_data['schedule_month'] = $modi_month;
					$insert_data['ini_month'] = $ini_month;
					$insert_data['cycle'] = $res->cycle;//test
					$insert_data['total_year'] = $res->year;//test
					$insert_data['total_month'] = $res->month;//test
					$insert_week[] = $insert_data;		
				}
				unset($modi_year);
				unset($modi_month);
				unset($date_week3);
				unset($insert_data);
			}
			//echo "<pre>";
			var_dump($insert_week);exit;
			//insert into schedule, and update task ifweek
			//$insert_res = $this->schedule_model->add_batch($insert_week);

			if($insert_res){
				return true;
			}else{
				return false;
			}
			echo "<pre>";
			var_dump($insert_week);exit;
	}





}



?>