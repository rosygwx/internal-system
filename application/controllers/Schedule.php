<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description: Contract
 * Author: Rosy
 * Last Update: 04/12/2018
 */

class Schedule extends CI_Controller {

	public function __construct(){
		parent::__construct();

		
		//login
		$this->load->library('session');
		$this->load->model('tool_model');
		
		$user = $this->session->userdata('user');
		if(!$user){
			$this->tool_model->redirect(BASE_URL().'login','Please login.');
		}
		
		$this->load->database();
		$this->load->model('schedule_model');
		$this->load->model('contract_model');
		$this->load->model('employee_model');
		$this->load->model('truck_model');
		$this->load->model('task_model');
		$this->load->model('worklog_model');
		$this->load->model('company_model');
		$this->load->model('tool_model');

		$this->schedule_lists_fields = 'SQL_CALC_FOUND_ROWS company.company_name as company,company.phone as phone,company.client_name as client, company.type as company_type, task.hwy_id, task.section_from, task.section_to, task.mile, task.unit_price, schedule.schedule_id, schedule.schedule_year, schedule.schedule_week, schedule.schedule_date, schedule.date, schedule.btime_req, schedule.btime, schedule.etime, schedule.location , schedule.status , schedule.task_id, schedule.crew_id, task_cat.tcat_name, task_cat.tcat_id, task_cat.category, task_cat.index_centerline, contract.poc, contract.pon, contract.contract_id_pannell, contract.contract_id';//join with table company, contract, task, task_cat

		$this->schedule_update_fields = 'SQL_CALC_FOUND_ROWS company.type as company_type, contract.contract_id_pannell, schedule.task_id, task.hwy_id, task.section_from, task.section_to, task.mile, task.tract, task_cat.txdot_type, task_cat.tcat_name, task_cat.index_centerline, schedule.schedule_id, schedule.location, schedule.schedule_date, schedule.date, schedule.btime, schedule.etime, schedule.ifschedule, schedule.status, schedule.comment, schedule.unit_price, schedule.crew_id';

		$this->schedule_update_com_fields = 'SQL_CALC_FOUND_ROWS company.type as company_type, contract.contract_id_pannell, contract.cancel_hour,schedule.task_id, schedule.schedule_id, schedule.location, schedule.schedule_date, schedule.date, schedule.btime_req, schedule.btime, schedule.etime, schedule.working_hour, schedule.disposal_price, schedule.traffic_control_price, schedule.travel_hour, schedule.ifschedule, schedule.status, schedule.comment,schedule.contact, schedule.unit_price, schedule.pon';

		$this->schedule_duplicate = 'schedule.task_id, schedule.btime_req, schedule.location, schedule.comment, schedule.pon, schedule.contact, schedule.ifschedule, schedule.disposal_price, schedule.traffic_control_price, case when schedule.ifschedule = 1 then task.unit_price else task.unit_price_2 end unit_price, schedule.travel_hour';

		$this->employee_lists_fields = 'SQL_CALC_FOUND_ROWS employee.employee_id,employee.first_name,employee.last_name,employee.nick_name,employee.rate';
		$this->truck_lists_fields = 'SQL_CALC_FOUND_ROWS truck.truck_id, truck.number, truck.type';
		$this->task_lists_fields = 'SQL_CALC_FOUND_ROWS contract.year,contract.bdate,task.task_id,task.cycle';
		
		$this->worklog_lists_fields = 'SQL_CALC_FOUND_ROWS worklog.wl_id, worklog.schedule_id, worklog.ticket_id, worklog.employee_id, worklog.truck_id, worklog.contract_id, worklog.schedule_date, worklog.category, worklog.crew_id, employee.nick_name, employee.last_name, truck.number truck_number';

		$this->calendar_lists = 'SQL_CALC_FOUND_ROWS company.type, company.company_name, company.company_id, company.colorcode, contract.contract_id_pannell, contract.contract_id_ori, task.hwy_id, task.tract, task.cycle, task.frequency, task_cat.tcat_name, task_cat.category, task_cat.index_centerline, schedule.schedule_id, schedule.schedule_year, schedule.schedule_week, schedule.schedule_date, schedule.date, schedule.status, schedule.mindate, schedule.maxdate, task.mile, task.section_from, task.section_to ';

		$this->company_lists = 'SQL_CALC_FOUND_ROWS company.company_name, company.company_id';

		$this->contract_lists = 'SQL_CALC_FOUND_ROWS contract.contract_id, contract.contract_id_ori, contract.contract_id_pannell';

		$this->txdot_print_sch = 'SQL_CALC_FOUND_ROWS schedule.schedule_id, schedule.schedule_date, schedule.date, schedule.btime, schedule.etime, schedule.status, schedule.crew_id, task.task_id, task.section_from, task.section_to, task.hwy_id, task.tract, task.mile, task_cat.tcat_name type, task_cat.tcat_id, task_cat.index_centerline, task_cat.category, contract.contract_id_pannell company_name';

		$this->txdot_print_wl = 'SQL_CALC_FOUND_ROWS worklog.wl_id, worklog.contract_id, worklog.schedule_date, worklog.category, worklog.employee_id, worklog.truck_id, worklog.crew_id';

		$this->ticket_print = 'SQL_CALC_FOUND_ROWS worklog.wl_id, worklog.schedule_id, worklog.ticket_id, concat(employee.nick_name, " ", employee.last_name) employee_name, truck.number truck_number, truck.type truck_type, schedule.schedule_date, schedule.btime_req, schedule.btime, schedule.travel_hour, schedule.location, schedule.comment, schedule.contact, schedule.ifschedule, schedule.traffic_control_price, schedule.disposal_price, schedule.pon, contract.poc, contract.contract_id_pannell company_name';

		$this->monthlyBillCom = 'SQL_CALC_FOUND_ROWS worklog.wl_id, worklog.schedule_id, worklog.ticket_id, concat(employee.nick_name, " ", employee.last_name) employee_name, truck.number truck_number, truck.type truck_type, schedule.schedule_date,schedule.date, schedule.btime, schedule.etime, schedule.billing_hour, schedule.contact, schedule.ifschedule, schedule.traffic_control_price, schedule.disposal_price, schedule.unit_price, contract.pon, contract.poc, contract.contract_id_pannell company_name';

		$this->current = 'schedule';//nevigation
		$this->currentRep = 'report';//nevigation
	}

	/**
	* Purpose: Schedule list, depend on schedule_date
	* @param {array} array  company, company_type, status, date and etc
	* @return: {array}  result, search, page, current and etc
	*/
	public function lists()
	{
		$input = $this->input->get();
		
		//isset($input['company']) && !empty($input['company']) ? $like[] = ['name'=>'contract.contract_id_pannell','value'=>$input['company'] ] : '';
		isset($input['contract_id']) && !empty($input['contract_id']) ? $where['task.contract_id'] = $input['contract_id']:'';
		isset($input['task_id']) && !empty($input['task_id']) ? $where['schedule.task_id'] = $input['task_id']:'';
		isset($input['category']) && $input['category'] ? $where['task_cat.category'] = $input['category']:[1,2];
		$where['schedule.schedule_date >='] = isset($input['bdate']) && $input['bdate'] != '' ? date("Y-m-d", strtotime(urldecode($input['bdate']))):date("Y-m-d", strtotime('- 1 day'));
		$where['schedule.schedule_date <='] = isset($input['edate']) && $input['edate'] != '' ? date("Y-m-d", strtotime(urldecode($input['edate']))):date("Y-m-d");
		

		isset($input['week']) && $input['week'] != '' ? $where['schedule.schedule_week'] = $input['week']:'';
		isset($input['year']) && $input['year'] != '' ? $where['schedule.schedule_year'] = $input['year']:'';
		$where['company.type'] =  1;
		$where['contract.status'] =  1;
		


		$orderby = ['schedule_year'=>'asc', 'schedule_month'=>'asc', 'schedule_week'=>'asc'];

		//distinct schedule_date and contract_id, deal with page issue
		$page_dis = isset($input['page']) && $input['page'] != '' ? $input['page'] : 1 ;
		$pagesize_dis = 10;

		$distinct_result = $this->schedule_model->lists('distinct schedule.schedule_date, contract.contract_id, contract.contract_id_pannell, task_cat.category', $where, $like, $json = true, $orderby = ['schedule.schedule_date'=>'desc','contract.contract_id'=>'desc','task_cat.category'=>'asc'], $page_dis, $pagesize_dis, '', 1);
		
		$where_in = [];
		if($distinct_result['result']){
			foreach($distinct_result['result'] as $dis){
				!in_array($dis->schedule_date, $where_in['schedule.schedule_date']) ? $where_in['schedule.schedule_date'][] = $dis->schedule_date: '';
				if(!in_array($dis->contract_id, $where_in['contract.contract_id'])){
					$where_in['contract.contract_id'][] = $dis->contract_id;
					//$contract[$dis->contract_id] = $dis->contract_id_pannell;
				}
				!in_array($dis->category, $where_in['task_cat.category']) ? $where_in['task_cat.category'][] = $dis->category: '';
			}

			//distince above has set the limitations
			$page = 1 ;
			$pagesize = 10000;
			$where['company.type'] = 1;//txdot
			
			$result = $this->schedule_model->lists($this->schedule_lists_fields, $where, $like, $json = true, $orderby = ['schedule.crew_id'=>'asc','schedule_date'=>'desc','schedule_id'=>'desc'], $page, $pagesize,$where_in);


			$schedule_id_arr = [];

			foreach($result['result'] as $res){
				//$schedule[$res->schedule_date][$res->contract_id]['hwy_id'][] = $res->hwy_id;
				$category = $res->category == 1 ? 'De' : 'Sw';
				
				/*//task, version 1
				$schedule[$res->schedule_date][$res->contract_id][$res->category]['task'][$res->task_id]['content'] = '('.$category.') Tract: '.$res->tract.' '.$res->hwy_id.' '.$res->tcat_name;
				$schedule[$res->schedule_date][$res->contract_id][$res->category]['task'][$res->task_id]['status'] = $res->status;
				$schedule[$res->schedule_date][$res->contract_id][$res->category]['task'][$res->task_id]['mile'] = $res->mile;*/


				//task, version 2
				//$temp_type = $this->txdotTypeConvert($res->tcat_id, 0);
				$temp_type = $res->tcat_name;

				!in_array($temp_type, $schedule[$res->schedule_date][$res->contract_id][$res->category]['crew'][$res->crew_id]['task'][$res->hwy_id]) ? $schedule[$res->schedule_date][$res->contract_id][$res->category]['crew'][$res->crew_id]['task'][$res->hwy_id][]= $temp_type : '';



				//overall actual mile
				$schedule[$res->schedule_date][$res->contract_id][$res->category]['crew'][$res->crew_id]['all_mile'] = $schedule[$res->schedule_date][$res->contract_id][$res->category]['crew'][$res->crew_id]['all_mile'] ? $schedule[$res->schedule_date][$res->contract_id][$res->category]['crew'][$res->crew_id]['all_mile'] + $res->mile * $res->index_centerline : $res->mile*$res->index_centerline;


				//overall status
				if(!isset($schedule[$res->schedule_date][$res->contract_id][$res->category]['status'])){
				$schedule[$res->schedule_date][$res->contract_id][$res->category]['status'] = $res->status;
				}else{
					if($schedule[$res->schedule_date][$res->contract_id][$res->category]['status'] == 1 && $res->status == 0 ){
						$schedule[$res->schedule_date][$res->contract_id][$res->category]['status'] = 0;
					}
				}

				unset($temp_type,$category);	
			}
			
			//worklog
			$worklog = [];
			
			$where_worklog['worklog.schedule_date'] = $where_in['schedule.schedule_date'];
			$where_worklog['worklog.contract_id'] = $where_in['contract.contract_id'];
			$where_worklog['worklog.category'] = $where_in['task_cat.category'];
			$res_worklog = $this->worklog_model->listsTxdot($this->worklog_lists_fields, $where_worklog, '', $json = true, $orderby = '', 1, 100);
			
			foreach($res_worklog['result'] as $rw){
				$schedule[$rw->schedule_date][$rw->contract_id][$rw->category]['crew'][$rw->crew_id]['employee'][] = $rw->nick_name.' '.$rw->last_name;
				$schedule[$rw->schedule_date][$rw->contract_id][$rw->category]['crew'][$rw->crew_id]['truck'][] = $rw->truck_number;
			}
		}

		//all contract
		$where_contract['type'] = 1;//txdot
		$res_contract = $this->contract_model->lists($this->contract_lists, $where_contract,  $like = '', $json = true, $orderby = ['contract.contract_id_pannell'=>'asc'], 1, 500);
		$contract = [];
		foreach($res_contract['result'] as $res){
			$contract[$res->contract_id] = $res->contract_id_pannell;
		}
		

		//search scope
		//$search['company'] = isset($input['company']) && !empty($input['company']) ? $input['company'] : '';
		$search['contract_id'] = isset($input['contract_id']) && !empty($input['contract_id']) ? $input['contract_id'] : '';
		$search['category'] = isset($input['category']) && $input['category'] ? $input['category'] : 0;
		$search['bdate'] = isset($input['bdate']) && $input['bdate'] != '' ? urldecode($input['bdate']) :date("m/d/Y", strtotime('- 1 day'));
		$search['edate'] = isset($input['edate']) && $input['edate'] != '' ? urldecode($input['edate']) :date("m/d/Y");

		//pagination
		$total = $distinct_result['total'];
		$url = BASE_URL().'schedule/lists';
		$pageinfo = $this->tool_model->makepage($page_dis,$pagesize_dis,$total,$url,$search);
		

		$data['schedule'] = $schedule;
		$data['worklog'] = $worklog;
		$data['contract'] = $contract;
		$data['search'] = $search;
		$data['page'] = $pageinfo;
		$data['current'] = $this->current;
		//echo '<pre>';
		//var_dump($data['contract']);

		$this->load->view('schedule/lists',$data);
	}

	/**
	* Purpose: Contract list
	* @param {array} array  company, company_type, status, date and etc
	* @return: {array}  result, search, page, current and etc
	*/
	public function listsCom()
	{
		$input = $this->input->get();
		//var_dump($input['date']);
		isset($input['company']) && !empty($input['company']) ? $like[] = ['name'=>'company.company_name','value'=>$input['company'] ] : '';
		isset($input['task_id']) && !empty($input['task_id']) ? $where['schedule.task_id'] = $input['task_id']:'';
		isset($input['hwy_id']) && !empty($input['hwy_id']) ? $like[] = ['name'=>'task.hwy_id','value'=>$input['hwy_id'] ] : '';

		isset($input['bdate']) && $input['bdate'] != '' ? $where['schedule.schedule_date >='] = date("Y-m-d", strtotime(urldecode($input['bdate']))):'';
		isset($input['edate']) && $input['edate'] != '' ? $where['schedule.schedule_date <='] = date("Y-m-d", strtotime(urldecode($input['edate']))):'';
		isset($input['week']) && $input['week'] != '' ? $where['schedule.schedule_week'] = $input['week']:'';
		isset($input['year']) && $input['year'] != '' ? $where['schedule.schedule_year'] = $input['year']:'';
		
		$where['company.type'] = 2;
		$orderby = ['schedule_date'=>'asc']; 
		$page = isset($input['page']) && $input['page'] != '' ? $input['page'] : 1 ;
		$pagesize = 50;
		
		$result = $this->schedule_model->lists($this->schedule_lists_fields, $where, $like, $json = true, $orderby = ['schedule_date'=>'desc','schedule_id'=>'desc'], $page, $pagesize,$where_in);


		//driver
		$employee_where['group_id'] = 4;//driver
		$employee_where['status'] = 1;//active
		$res_driver = $this->employee_model->lists($this->employee_lists_fields, $where_driver, '', $json = true, $orderby = ['nick_name'=>'asc'], 1, 500);
		$driver = [];
		foreach($res_driver['result'] as $rd){
			$driver[$rd->employee_id] = $rd->nick_name;
		}

		//truck
		$where_truck['type'] = [1,2,3,4];
		$where_truck['status'] = 1;
		$res_truck = $this->truck_model->lists($this->truck_lists_fields, $where_truck, '', $json = true, $orderby = ['type'=>'asc', 'number'=>'asc'], 1, 500);
		$truck = [];
		$truck_type = [1=>'SWE', 2=>'SWE-V', 3=>'DEB', 4=>'TMA', 5=>'OTHER'];
		foreach($res_truck['result'] as $rt){
			$truck[$rt->truck_id] = $rt->number.' ('.$truck_type[$rt->type].')';
		}
		

		$schedule_id_arr = [];


		foreach($result['result'] as $res){	
				$res->btime = $res->btime ? $res->btime : '';
				$res->etime = $res->etime ? $res->etime : '';

				$res->schedule_date = $res->schedule_date ? date('m/d/Y', strtotime($res->schedule_date)) : '';
				$res->date = $res->date ? date('m/d/Y', strtotime($res->date)) : '';

				$schedule_period = $this->tool_model->getStartAndEndDate($res->schedule_week,$res->schedule_year);//week
				$res->schedule_period = $res->schedule_date ? $res->schedule_date : $schedule_period["week_start"]." to ".$schedule_period["week_end"];

				unset($res->employee_id);

				$schedule_id_arr[] = $res->schedule_id;			
			}

	
		//echo '<pre>';
		//var_dump($result);

		//worklog
		$worklog = [];
		if($schedule_id_arr){
			$where_worklog['worklog.schedule_id'] = $schedule_id_arr;
			$res_worklog = $this->worklog_model->lists($this->worklog_lists_fields, $where_worklog, '', $json = true, $orderby = '', 1, $pagesize * 5);
			
			foreach($res_worklog['result'] as $rw){
				$worklog[$rw->schedule_id]['ticket_id'][] = $rw->ticket_id;
				$worklog[$rw->schedule_id]['employee_id'][] = $driver[$rw->employee_id];
				$worklog[$rw->schedule_id]['truck_id'][] = $truck[$rw->truck_id];
			}
		}
		
		
		
		//search scope
		$search['company'] = isset($input['company']) && !empty($input['company']) ? $input['company'] : '';
		$search['company_type'] = isset($input['company_type']) && !empty($input['company_type']) ? $input['company_type'] : 1;
		$search['hwy_id'] = isset($input['hwy_id']) && !empty($input['hwy_id']) ? $input['hwy_id'] : '';
		$search['status'] = isset($input['status']) && $input['status'] != '' ? (int)$input['status']:'';
		$search['bdate'] = isset($input['bdate']) && $input['bdate'] != '' ? urldecode($input['bdate']) :'';
		$search['edate'] = isset($input['edate']) && $input['edate'] != '' ? urldecode($input['edate']) :'';
		$search['date'] = isset($input['date']) ? $input['date'] : date('m-d-Y');
		$search['week'] = isset($input['week']) && !empty($input['week']) ? $input['week'] : '';
		$search['year'] = isset($input['year']) && !empty($input['year']) ? $input['year'] : '';
		$search['period'] = isset($input['period']) && !empty($input['period']) ? $input['period'] : '';
		
		//pagination
		$total = $result['total'];
		$url = BASE_URL().'schedule/listsCom';
		$pageinfo = $this->tool_model->makepage($page,$pagesize,$total,$url,$search);
		

		$data['schedule'] = $result['result'];
		$data['worklog'] = $worklog;
		$data['contract'] = $contract;
		$data['search'] = $search;
		$data['page'] = $pageinfo;
		$data['current'] = $this->current;
		//echo '<pre>';
		//var_dump($data['current']);

		$this->load->view('schedule/listsCom',$data);
		
		
	}

	/**
	* Purpose: Schedule add page (commercial)
	* @param null
	* @return: {json}
	*/
	public function add(){
		$input = $this->input->post();
		
		if(!$input){
			$input = $this->input->get();
			$task_id = isset($input['id']) && !empty($input['id']) ? $input['id'] : '';
			$task_id ? $task_where['task.task_id'] = $task_id : '';
			$task_where['contract.status'] = 1;
			$task_where['company.type'] = 2;
			$task = $this->task_model->lists('contract.contract_id_pannell, contract.travel_hour, contract.office, contract.pon, task.task_id, company.type, task.unit_price, task.unit_price_2, task.traffic_control_price, task.disposal_price', $task_where, $like, $json = true, $orderby=['contract.contract_id_pannell'=>'acs'], 1, 300);
			

			//driver
			$employee_where['group_id'] = 4;//driver
			$employee_where['status'] = 1;//active
			$employee = $this->employee_model->lists($this->employee_lists_fields , $employee_where, $like = '', $json = true, $orderby = array('nick_name'=>'asc'), 1, 500);
			foreach($employee['result'] as $em){
				$em->id = $em->employee_id;
				$em->name = $em->nick_name." ".$em->last_name;
				unset($em->employee_id, $em->first_name, $em->last_name, $em->nick_name);
			}

			//truck
			$where_truck['type'] = [1,2,3,4];
			$where_truck['status'] = 1;
			$res_truck = $this->truck_model->lists($this->truck_lists_fields, $where_truck, '', $json = true, $orderby = ['number'=>'asc', 'type'=>'asc'], 1, 500);
			$truck = [];
			$truck_type = [1=>'SWE', 2=>'SWE-V', 3=>'DEB', 4=>'TMA', 5=>'OTHER'];
			foreach($res_truck['result'] as $rt){
				$truck[$rt->truck_id] = $rt->number.' ('.$truck_type[$rt->type].')';
			}



			

			//$data['task'] = array_merge([(object)['task_id'=>0, 'contract_id_pannell'=>'', 'travel_hour'=>0]], $task['result']);
			$data['task'] = $task['result'];
			$data['task_id'] = $task_id ? $task_id : '';
			$data['driver'] = $employee['result'];
			$data['truck'] = $truck;
			$data['current'] = $this->current;
			//var_dump($data['task']);

			if($task['result'][0]->type == 1){//txdot

			}else{//commercial
				$this->load->view('schedule/add',$data);
			}
			
		}else{
			//echo "<pre>";
			//var_dump($input);exit;
			$backurl = isset($input['backurl']) && !empty($input['backurl']) ? $input['backurl'] : "" ;
			$company_type = isset($input['company_type']) && !empty($input['company_type']) ? $input['company_type'] : 2 ;
			$arr['task_id'] = isset($input['task_id']) && !empty($input['task_id']) ? $input['task_id'] : "" ;
			$arr['schedule_date'] = isset($input['date']) && !empty($input['date']) ? date("Y-m-d", strtotime(str_replace('-', '/', $input['date']))) : date("Y-m-d");
			
			$arr['ifschedule'] = isset($input['unscheduled']) && $input['unscheduled'] != '' ? 2 : 1 ;
			$arr['unit_price'] = isset($input['unscheduled']) && $input['unscheduled'] != '' ? $input['unit_price_2'] : $input['unit_price'] ;
			$arr['comment'] = isset($input['comment']) && $input['comment'] != '' ? $input['comment'] : "" ;
			$arr['contact'] = isset($input['contact']) && $input['contact'] != '' ? $input['contact'] : "" ;
			$arr['schedule_year'] = date('Y', strtotime($arr['schedule_date']));
			$arr['schedule_month'] = date('n', strtotime($arr['schedule_date']));
			$arr['schedule_week'] = ltrim(date('W', strtotime($arr['schedule_date'])), '0');


			if($company_type==2){//commercial
				$arr['location'] = isset($input['location']) && !empty($input['location']) ? $input['location'] : "" ;
				$arr['btime_req'] = isset($input['btime_req']) && !empty($input['btime_req']) ? $input['btime_req'] : null ;
				$arr['btime'] = isset($input['btime']) && !empty($input['btime']) ? $input['btime'] : null ;
				$arr['etime'] = isset($input['etime']) && !empty($input['etime']) ? $input['etime'] : null ;
				isset($input['iftravelhour']) && !empty($input['iftravelhour']) ? $arr['travel_hour'] = $input['travel_hour'] : 0;
				isset($input['iftraffic']) && !empty($input['iftraffic']) ? $arr['traffic_control_price'] = $input['traffic_charge'] : '';
				isset($input['ifdisposal']) && !empty($input['ifdisposal']) ? $arr['disposal_price'] = $input['disposal_charge'] : '';
				isset($input['pon']) && !empty($input['pon']) ? $arr['pon'] = $input['pon'] : '';

				//calculate billing hour
				if($arr['btime'] && $arr['etime']){
					$dateeee = $arr['etime'] - $arr['btime'] > 0 ? '2018-05-30' : '2018-05-31';
					$arr['working_hour'] = ceil((strtotime($dateeee.$arr['etime']) - strtotime('2018-05-30'.$arr['btime']))/60/60);
					//$arr['travel_hour'] = 2;
					$arr['billing_hour'] = $arr['working_hour'] + $arr['travel_hour'];
				}

				//work_log
				if(isset($input['employee_id']) && !empty($input['employee_id'])){

					//employee rate
					if(array_filter($input['employee_id'],'strlen')){
						$where_rate['employee_id'] = $input['employee_id'] ;
						$employee = $this->employee_model->lists($this->employee_lists_fields , $where_rate, $like = '', $json = true, $orderby = array('nick_name'=>'asc'), 1, 500);
						$rate = [];
						foreach($employee['result'] as $r){
							$rate[$r->employee_id] = $r->rate;
						}
					}

					$arr_work = [];
					foreach($input['ticket_id'] as $ticket_key=>$ticket_id){
						if($ticket_id || $input['employee_id'][$ticket_key] || $input['truck_id'][$ticket_key]){
							$arr_work[] = ['schedule_id'=>'', 'ticket_id'=>$ticket_id , 'employee_id'=>$input['employee_id'][$ticket_key], 'truck_id'=>$input['truck_id'][$ticket_key], 'ifbill'=>1, 'status'=>0, 'employee_rate'=>$rate[$input['employee_id'][$ticket_key]], 'addtime'=>date('Y-m-d H:i:s')];
						}
					}
				}

				//ifschedule
				$weekday = ltrim(date('w', strtotime($arr['schedule_date'])), '0');
				//$arr['ifschedule'] = $weekday == 6 || $weekday ==0 || ($arr['btime'] > '17:00' && $arr['btime'] < '23:59') || ($arr['btime'] < '07:00' && $arr['btime'] > '00:00') ? 2 : 1;

			}
			//var_dump($arr,$arr_work,$weekday);exit;

			$res = $this->schedule_model->add($arr);
			
			if($res){
				//var_dump($res);

				if($arr_work){
					for($i=0;$i<count($arr_work);$i++){
						$arr_work[$i]['schedule_id'] = $res;
					}

					//add work log
					$res_work = $this->worklog_model->add_batch($arr_work);
					if($res_work){
						$this->tool_model->redirect($backurl, "Add successfully.");exit;
					}else{
						$this->tool_model->redirect($backurl, "Add schedule successfully, but not work log.");exit;
					}
				}else{
					$this->tool_model->redirect($backurl, "Add successfully.");exit;
				}
			}else{
				$this->tool_model->redirect($backurl, "Add schedule and work log unsuccessfully.");exit;
			}
		}
	}



	/**
	* Purpose: Schedule update page (txdot)
	* @param {array} array - id and etc.
	* @return: 
	*/
	public function update(){
		$input = $this->input->get();
		
		
		if($input){
			$backurl = $_SERVER['HTTP_REFERER'];
			$schedule_date = isset($input['date']) && !empty($input['date']) ? date('Y-m-d',strtotime(urldecode($input['date']))) : '';
			$contract_id = isset($input['id']) && !empty($input['id']) ? $input['id'] : '';
			$category = isset($input['cat']) && !empty($input['cat']) ? $input['cat'] : '';
			if($schedule_date && $contract_id && $category){
				//schedule
				$where_schedule['schedule.schedule_date'] = $schedule_date;
				$where_schedule['contract.contract_id'] = $contract_id;
				$where_schedule['task_cat.category'] = $category;
				$orderby_schedule = ['schedule.crew_id'=>'asc', 'task_cat.tcat_id'=>'asc', 'task.tract'=>'asc'];
				$res_schedule = $this->schedule_model->lists($this->schedule_update_fields, $where_schedule, '' , $json = true, $orderby_schedule, 1, 500);
				$schedule_row = $res_schedule['result'];

				
				$schedule['schedule_date'] = $schedule_row[0]->schedule_date ? date('m/d/Y', strtotime($schedule_row[0]->schedule_date)) : '';
				$schedule['contract_id'] = $contract_id;
				$schedule['category'] = $category;
				$schedule['contract_id_pannell'] = $schedule_row[0]->contract_id_pannell;
				
				$status_all = $schedule_row[0]->status;//default
				$mile_all = 0;//default

				foreach($schedule_row as $sc){
					$temp['task_id'] = $sc->task_id;
					$temp['schedule_id'] = $sc->schedule_id;
					$temp['schedule_date'] = $sc->schedule_date ? date('m/d/Y', strtotime($sc->schedule_date)) : '';
					$temp['date'] = $sc->date ? date('m/d/Y', strtotime($sc->date)) : '';
					$temp['hwy_id'] = $sc->hwy_id;
					$temp['section_from'] = $sc->section_from;
					$temp['section_to'] = $sc->section_to;
					$temp['mile'] = sprintf("%.2f",$sc->mile);
					$temp['tract'] = $sc->tract;
					//$temp['txdot_type'] = $this->txdotTypeConvert($sc->txdot_type);
					$temp['txdot_type'] = $sc->tcat_name;
					$temp['btime'] = $sc->btime ? date('h:i a', strtotime('2018-11-07 '.$sc->btime)) : '';
					$temp['etime'] = $sc->etime ? date('h:i a', strtotime('2018-11-07 '.$sc->etime)) : '';
					$temp['status'] = $sc->status == 1 ? $sc->date : '';
					$temp['crew_id'] = $sc->crew_id ?  $sc->crew_id : 1;
					$mile_all = $mile_all + $sc->mile * $sc->index_centerline;

					$status_all == 1 && $sc->status == 0 ? $status_all = 0 : '';

					$task[] = $temp;
					$taskId[] = $sc->task_id;
					unset($temp);
				}
				$schedule['mile_all'] = $mile_all;
				$schedule['status_all'] = $status_all;
				

				//extra task
				$whereScheduleEx2['schedule_date'] = $schedule_date;
				$whereScheduleEx['contract.contract_id'] = $contract_id;
				$whereScheduleEx['task_cat.category'] = [3,4]; //txdot extra task
				$orderby_schedule = ['schedule.crew_id'=>'asc', 'task_cat.tcat_id'=>'asc', 'task.tract'=>'asc'];
				//var_dump($whereScheduleEx);
				$resScheduleExRow = $this->task_model->lists('task.task_id, task.tract, task.hwy_id, task.cycle, task.section_from, task.section_to, task.unit_price, task_cat.tcat_name, schedule.unitSum, schedule2.schedule_id, schedule2.unit, schedule2.comment', $whereScheduleEx, '' , $json = true, ['task_cat.tcat_id'=>'asc'], 1, 500, $ifExtra = 1, $whereScheduleEx2);
				$resScheduleExRow = $resScheduleExRow['result'];
				//var_dump($resScheduleExRow);

				foreach($resScheduleExRow as $res){
					$temp['task_id'] = $res->task_id ? $res->task_id : '';
					$temp['schedule_id'] = $res->schedule_id ? $res->schedule_id : '';
					$temp['tract'] = $res->tract ? $res->tract : '-';
					$temp['hwy_id'] = $res->hwy_id ? $res->hwy_id : '-';
					$temp['cycle'] = $res->cycle ? $res->cycle : '-';
					$temp['section_from'] = $res->section_from ? $res->section_from : '-';
					$temp['section_to'] = $res->section_to ? $res->section_to : '-';
					$temp['tcat_name'] = $res->tcat_name ? $res->tcat_name : '-';
					$temp['unit'] = $res->unit ? $this->tool_model->formatDouble($res->unit) : '';
					$temp['unitSum'] = $res->unitSum ? $this->tool_model->formatDouble($res->unitSum) : '-';
					$temp['unit_price'] = $res->unit_price ? $res->unit_price : "0.00";
					$temp['comment'] = $res->comment ? $res->comment : "";
					$resScheduleEx[] = $temp;
					unset($temp);
				}



				//worklog
				$where_worklog['worklog.schedule_date'] = date('Y-m-d',strtotime($schedule_date));
				$where_worklog['worklog.contract_id'] = $contract_id;
				$where_worklog['worklog.category'] = $category;
				$res_worklog = $this->worklog_model->listsTxdot($this->worklog_lists_fields, $where_worklog, '', $json = true, $orderby = '', 1, 20);
				
				//driver
				$employee_where['group_id'] = 4;//driver
				$employee_where['status'] = 1;//active
				$res_driver = $this->employee_model->lists($this->employee_lists_fields, $employee_where, '', $json = true, $orderby = ['nick_name'=>'asc'], 1, 500);
				$driver = [];
				foreach($res_driver['result'] as $rd){
					$driver[$rd->employee_id] = $rd->nick_name.' '.$rd->last_name;
				}

				//truck
				$where_truck['type'] = [1,2,3,4];
				$where_truck['status'] = 1;
				$res_truck = $this->truck_model->lists($this->truck_lists_fields, $where_truck, '', $json = true, $orderby = ['number'=>'asc', 'type'=>'asc'], 1, 500);
				$truck = [];
				$truck_type = [1=>'SWE', 2=>'SWE-V', 3=>'DEB', 4=>'TMA', 5=>'OTHER'];
				foreach($res_truck['result'] as $rt){
					$truck[$rt->truck_id] = $rt->number.' ('.$truck_type[$rt->type].')';
				}
				//var_dump($truck);

				$data['schedule'] = $schedule;
				$data['worklog'] = $res_worklog['result'];
				$data['task'] = $task;
				$data['taskEx'] = $resScheduleEx;
				$data['taskId'] = $taskId;
				$data['driver'] = $driver;
				$data['truck'] = $truck;
				$data['backurl'] = $backurl;
				$data['current'] = $this->current;
				
				$this->load->view('schedule/update', $data);

			}else{
				$this->tool_model->redirect($backurl, 'Oops, something is missing. Ask technician. Error code #193801');exit;
			}
		}else{
			$input = $this->input->post();
			//echo '<pre>';
			//var_dump($input);exit;
			$backurl = isset($input['backurl']) && !empty($input['backurl']) ? urldecode($input['backurl']) : $_SERVER['HTTP_REFERER'];
			$schedule_date = isset($input['schedule_date']) && !empty($input['schedule_date']) ? date('Y-m-d',strtotime(urldecode($input['schedule_date']))) : '';
			$contract_id = isset($input['contract_id']) && !empty($input['contract_id']) ? $input['contract_id'] : '';
			$category = isset($input['category']) && !empty($input['category']) ? $input['category'] : '';

			//var_dump($schedule_date, $contract_id, $category);exit;


			if($schedule_date && $contract_id && $category){
				$schedule_date_arr = isset($input['schedule_date_arr']) && !empty($input['schedule_date_arr']) ? $input['schedule_date_arr'] : '';
				$date_arr = isset($input['date']) && !empty($input['date']) ? $input['date'] : '';
				$schedule_id_arr = isset($input['schedule_id_arr']) && !empty($input['schedule_id_arr']) ? $input['schedule_id_arr'] : '';
				$wl_id_arr = isset($input['wl_id']) && !empty($input['wl_id']) ? $input['wl_id'] : '';
				$btime_arr = isset($input['btime']) && !empty($input['btime']) ? $input['btime'] : '';
				$etime_arr = isset($input['etime']) && !empty($input['etime']) ? $input['etime'] : '';
				$status = isset($input['status']) && !empty($input['status']) ? $input['status'] : '';
				$crew_id_arr = isset($input['crew_id']) && !empty($input['crew_id']) ? $input['crew_id'] : '';
				$employee_id_arr = isset($input['employee_id']) && !empty($input['employee_id']) ? $input['employee_id'] : '';
				$truck_id_arr = isset($input['truck_id']) && !empty($input['truck_id']) ? $input['truck_id'] : '';
				$crew_wl_id_arr = isset($input['crew_wl_id']) && !empty($input['crew_wl_id']) ? $input['crew_wl_id'] : '';

				$task_id_arr_extra = isset($input['task_id_arr_extra']) && !empty($input['task_id_arr_extra']) ? $input['task_id_arr_extra'] : '';
				$schedule_id_arr_extra = isset($input['schedule_id_arr_extra']) && !empty($input['schedule_id_arr_extra']) ? $input['schedule_id_arr_extra'] : '';
				$unit_price_arr_extra = isset($input['unit_price_arr_extra']) && !empty($input['unit_price_arr_extra']) ? $input['unit_price_arr_extra'] : '';
				$unit_arr_extra = isset($input['unit_arr_extra']) && !empty($input['unit_arr_extra']) ? $input['unit_arr_extra'] : '';
				$comment_arr_extra = isset($input['comment_arr_extra']) && !empty($input['comment_arr_extra']) ? $input['comment_arr_extra'] : '';
				


				//update schedule
				foreach($schedule_id_arr as $key=>$schedule_id){
					$whereSch['schedule_id'] = $schedule_id;
					//var_dump($key);exit;
					if($schedule_date_arr[$key]){
						$updateSch['schedule_date'] = date('Y-m-d',strtotime($schedule_date_arr[$key]));
						$updateSch['schedule_year'] = date('Y', strtotime($schedule_date_arr[$key]));
						$updateSch['schedule_month'] = date('n', strtotime($schedule_date_arr[$key]));
						$updateSch['schedule_week'] = date('W', strtotime($schedule_date_arr[$key]));
					}

					if($date_arr[$key]){
						$updateSch['date'] = date('Y-m-d',strtotime($date_arr[$key]));
						$updateSch['status'] = 1;
					}else{
						$updateSch['date'] = "";
						$updateSch['status'] = 0;
					}
					
					$updateSch['btime'] = $btime_arr[$key] ? date('H:i:s', strtotime('2018-11-07 '.$btime_arr[$key])) : "";
					$updateSch['etime'] = $etime_arr[$key] ? date('H:i:s', strtotime('2018-11-07 '.$etime_arr[$key])) : "";
					
					$updateSch['crew_id'] = $crew_id_arr[$key] ? $crew_id_arr[$key] : 1 ;

					//var_dump($updateSch, $whereSch);
					//exit;
					$res_update = $this->schedule_model->update($updateSch, $whereSch);
					unset($updateSch, $whereSch);
				}

				////////worklog
				$where_worklog['worklog.schedule_date'] = date('Y-m-d',strtotime($schedule_date));
				$where_worklog['worklog.contract_id'] = $contract_id;
				$where_worklog['worklog.category'] = $category;
				$res_worklog = $this->worklog_model->listsTxdot('wl_id', $where_worklog, '', $json = true, $orderby = '', 1, 500);

				foreach($res_worklog['result'] as $res){
					$wl_id_ori[] = $res->wl_id;
				}

				//employee rate
				if(array_filter($input['employee_id'])){
					//echo 1;
					$where_rate['employee_id'] = $input['employee_id'] ;
					$employee = $this->employee_model->lists($this->employee_lists_fields , $where_rate, $like = '', $json = true, $orderby = array('nick_name'=>'asc'), 1, 500);
					$rate = [];
					foreach($employee['result'] as $r){
						$rate[$r->employee_id] = $r->rate;
					}
				}

				//var_dump($wl_id_ori, $wl_id, $wl_id_arr);exit;
				//update and add worklog
				foreach($wl_id_arr as $key => $wl_id){
					if(in_array($wl_id, $wl_id_ori)){
						unset($wl_id_ori[array_search($wl_id, $wl_id_ori)]);

						$whereWl['wl_id'] = $wl_id;
						$updateWl['employee_id'] = $employee_id_arr[$key] ? $employee_id_arr[$key] : '';
						$updateWl['employee_rate'] = $employee_id_arr[$key] ? $rate[$employee_id_arr[$key]] : '' ;
						$updateWl['truck_id'] = $truck_id_arr[$key] ? $truck_id_arr[$key]: '' ;
						$updateWl['crew_id'] = $crew_wl_id_arr[$key] ? $crew_wl_id_arr[$key]: '' ;

						//var_dump($updateWl, $whereWl);exit;
						$res = $this->worklog_model->update($updateWl, $whereWl);

						unset($updateWl, $whereWl);

					}else{
						if($employee_id_arr[$key] || $truck_id_arr[$key]){
							$addWl['schedule_date'] = date('Y-m-d',strtotime($schedule_date));
							$addWl['contract_id'] = $contract_id;
							$addWl['category'] = $category;
							$addWl['employee_id'] = $employee_id_arr[$key] ? $employee_id_arr[$key] : '';
							$addWl['employee_rate'] = $employee_id_arr[$key] ? $rate[$employee_id_arr[$key]] : '' ;
							$addWl['truck_id'] = $truck_id_arr[$key] ? $truck_id_arr[$key] : '';
							$addWl['crew_id'] = $crew_wl_id_arr[$key] ? $crew_wl_id_arr[$key] : '';

							//var_dump($addWl);exit;
							$res = $this->worklog_model->add($addWl);

							unset($addWl);
						}
						

					}
				}

				//delete the worklogs from the update page
				if(array_filter($wl_id_ori)){
					foreach($wl_id_ori as $wl){
						$whereWl['wl_id'] = $wl;
						$deleteWl['isdeleted'] = 1;

						//var_dump($deleteWl, $whereWl);
						$res = $this->worklog_model->update($deleteWl, $whereWl);

						unset($deleteWl, $whereWl);
					}
				}


				//update extra task
				foreach($task_id_arr_extra as $key=>$task_id){
					if($schedule_id_arr_extra[$key]){//update the schedule
						//echo 1 ;exit;
						$whereSch['schedule_id'] = $schedule_id_arr_extra[$key];

						if($unit_arr_extra[$key]){//remain the record

							$updateSch['unit'] = $unit_arr_extra[$key];
							$updateSch['comment'] = $comment_arr_extra[$key];
							$res_update = $this->schedule_model->update($updateSch, $whereSch);

						}else{//delete the record

							$res_update = $this->schedule_model->delete($schedule_id_arr_extra[$key]);

						}

						
						unset($updateSch, $whereSch);

					}else{//add the schedule
						if($unit_arr_extra[$key]){

							$addSch['schedule_date'] = $addSch['ori_date'] = $addSch['date'] = date('Y-m-d',strtotime($schedule_date));
							$addSch['schedule_year'] = date('Y', strtotime($schedule_date));
							$addSch['schedule_month'] = date('n', strtotime($schedule_date));
							$addSch['schedule_week'] = date('W', strtotime($schedule_date));

							$addSch['task_id'] = $task_id_arr_extra[$key];
							$addSch['unit'] = $unit_arr_extra[$key];
							$addSch['unit_price'] = $unit_price_arr_extra[$key];
							$addSch['comment'] = $comment_arr_extra[$key];
							$addSch['status'] = 1;
							$addSch['crew_id'] = 1;

							//var_dump($addSch);exit;
							$res_add = $this->schedule_model->add($addSch);
							unset($addSch);
						}else{
							continue;
						}

					}

				}

				//var_dump($backurl);exit;
				$this->tool_model->redirect($backurl, 'Update successfully.');exit;
			}else{
				
				$this->tool_model->redirect($backurl, 'Oops, something is missing. Ask technician. Error code #193802');exit;
			}
			
			
		}
		

	}



	/**
	* Purpose: Schedule update page (commercial)
	* @param {array} array - id and etc.
	* @return: 
	*/
	public function updateCom(){
		$input = $this->input->get();
		$schedule_id = isset($input['id']) && !empty($input['id']) ? $input['id'] : '';
		if($schedule_id){
			$where_schedule['schedule_id'] = $schedule_id;
			$res_schedule = $this->schedule_model->lists($this->schedule_update_com_fields, $where_schedule, '' , $json = true, $orderby = '', 1, 1);
			$schedule = $res_schedule['result'][0];
			//var_dump($schedule);
			$schedule->schedule_date = date('m-d-Y', strtotime($schedule->schedule_date));
			$schedule->btime_req = $schedule->btime_req || $schedule->btime_req == '00:00:00' ? date('h:i a', strtotime($schedule->btime_req)) : '';
			$schedule->btime = $schedule->btime || $schedule->btime == '00:00:00' ? date('h:i a', strtotime($schedule->btime)) : '';
			$schedule->etime = $schedule->etime || $schedule->etime == '00:00:00' ? date('h:i a', strtotime($schedule->etime)) : '';
			$schedule->iftravelhour = $schedule->travel_hour ? 1 : 0;
			$schedule->travel_hour = $schedule->travel_hour ? $schedule->travel_hour : '';

			if(!(int)$schedule->traffic_control_price){
				$schedule->traffic_control_price = '';
				$schedule->iftraffic = 0;
			}else{
				$schedule->iftraffic = 1;
			}

			if(!(int)$schedule->disposal_price){
				$schedule->disposal_price = '';
				$schedule->ifdisposal = 0;
			}else{
				$schedule->ifdisposal = 1;
			}

			$schedule->standard_cancel_hour = $schedule->cancel_hour;
			$schedule->cancel_hour = $schedule->status==2 && $schedule->working_hour && $schedule->working_hour != '0' ? $schedule->working_hour : '';

			

			$schedule->ifdisposal = $schedule->disposal_price && $schedule->disposal_price != '0.00' ? 1 : 0;

			$schedule->unscheduled = $schedule->ifschedule == 1 ? 0 : 1;
			//var_dump($schedule->cancel_hour,$schedule->standard_cancel_hour,$schedule->status);
			
			//task
			$task_where['contract.status'] = 1;
			$task_where['company.type'] = 2;
			$task = $this->task_model->lists('contract.contract_id_pannell, contract.travel_hour, contract.office, task.task_id, task.unit_price, task.unit_price_2, company.type', $task_where, $like, $json = true, $orderby=['contract.contract_id_pannell'=>'acs'], 1, 300);
			
			//worklog
			$where_worklog['worklog.schedule_id'] = $schedule_id;
			$res_worklog = $this->worklog_model->lists($this->worklog_lists_fields, $where_worklog, '', $json = true, $orderby = '', 1, 10);
			//var_dump($res_worklog);
			
			//driver
			$employee_where['group_id'] = 4;//driver
			$employee_where['status'] = 1;//active
			$res_driver = $this->employee_model->lists($this->employee_lists_fields, $employee_where, '', $json = true, $orderby = ['nick_name'=>'asc'], 1, 500);
			$driver = [];
			foreach($res_driver['result'] as $rd){
				$driver[$rd->employee_id] = $rd->nick_name.' '.$rd->last_name;
			}

			//truck
			$where_truck['truck.type'] = [1,2,3,4];
			$where_truck['truck.status'] = 1;
			$res_truck = $this->truck_model->lists($this->truck_lists_fields, $where_truck, '', $json = true, $orderby = ['truck.number'=>'asc','truck.type'=>'asc' ], 1, 500);
			$truck = [];
			$truck_type = [1=>'SWE', 2=>'SWE-V', 3=>'DEB', 4=>'TMA', 5=>'OTHER'];
			foreach($res_truck['result'] as $rt){
				$truck[$rt->truck_id] = $rt->number.' ('.$truck_type[$rt->type].')';
			}

			$data['task'] = $task['result'];
			$data['schedule'] = $schedule;
			$data['worklog'] = $res_worklog['result'];
			$data['driver'] = $driver;
			$data['truck'] = $truck;
			$data['company_type'] = $res_schedule['result'][0]->company_type;
			$data['current'] = $this->current;
			//var_dump($data['schedule'],$data['worklog'],$driver,$truck);
			
			if($data['company_type'] == 1){//txdot
				$this->load->view('schedule/update',$data);
			}else{
				$this->load->view('schedule/update_commercial',$data);
			}	
		}else{
			$input = $this->input->post();
			//echo "<pre>";
			//var_dump($input);exit;
			$schedule_id = isset($input['schedule_id']) && !empty($input['schedule_id']) ? $input['schedule_id'] : "" ;
			$backurl = isset($input['backurl']) && !empty($input['backurl']) ? $input['backurl'] : $_SERVER['HTTP_REFERER'] ;

			if($schedule_id){
				$company_type = isset($input['company_type']) && !empty($input['company_type']) ? $input['company_type'] : 1 ;
				$wl_id = isset($input['wl_id']) && !empty($input['wl_id']) ? $input['wl_id'] : '' ;
				$arr['schedule_date'] = isset($input['date']) && !empty($input['date']) ? date("Y-m-d", strtotime(str_replace('-', '/', $input['date']))) : date("Y-m-d");

				
				$arr['ifschedule'] = isset($input['unscheduled']) && $input['unscheduled'] != '' ? 2 : 1 ;
				$arr['unit_price'] = isset($input['unscheduled']) && $input['unscheduled'] != '' ? $input['unit_price_2'] : $input['unit_price'] ;
				$arr['comment'] = isset($input['comment']) && $input['comment'] != '' ? $input['comment'] : "" ;
				$arr['contact'] = isset($input['contact']) && $input['contact'] != '' ? $input['contact'] : "" ;
				$arr['schedule_year'] = date('Y', strtotime($arr['schedule_date']));
				$arr['schedule_month'] = date('n', strtotime($arr['schedule_date']));
				$arr['schedule_week'] = ltrim(date('W', strtotime($arr['schedule_date'])), '0');


				if($company_type==2){//commercial
					$arr['location'] = isset($input['location']) && !empty($input['location']) ? $input['location'] : "" ;
					$arr['btime_req'] = isset($input['btime_req']) && !empty($input['btime_req']) ? date('H:i:s', strtotime($input['btime_req'])) : null ;
					$arr['btime'] = isset($input['btime']) && !empty($input['btime']) ? date('H:i:s', strtotime($input['btime'])) : null ;
					$arr['etime'] = isset($input['etime']) && !empty($input['etime']) ?  date('H:i:s', strtotime($input['etime'])) : null ;
					isset($input['task_id']) && !empty($input['task_id']) ? $arr['task_id'] = $input['task_id'] : '';
					$arr['travel_hour'] = isset($input['iftravelhour']) && !empty($input['iftravelhour']) ? (int)$input['travel_hour'] : 0;
					$arr['traffic_control_price'] = isset($input['iftraffic']) && !empty($input['iftraffic']) && isset($input['traffic_charge']) && !empty($input['traffic_charge']) ? number_format($input['traffic_charge'], 2 ) : '';
					$arr['disposal_price'] = isset($input['ifdisposal']) && !empty($input['ifdisposal']) && isset($input['disposal_charge']) && !empty($input['disposal_charge'])? number_format($input['disposal_charge'], 2) : '';
					$arr['pon'] = isset($input['pon']) && !empty($input['pon']) ? $input['pon'] : '';
					$arr['status'] = isset($input['status']) && $input['status'] != 0 ? $input['status'] : 0;

					//calculate billing hour
					if($arr['btime'] && $arr['etime']){
						$dateeee = $arr['etime'] - $arr['btime'] > 0 ? '2018-05-30' : '2018-05-31';
						$arr['working_hour'] = ceil((strtotime($dateeee.$arr['etime']) - strtotime('2018-05-30'.$arr['btime']))/60/60);
						
						$arr['billing_hour'] = $arr['working_hour'] + $arr['travel_hour'];
					}

					$arr['date'] = date('Y-m-d', strtotime($arr['schedule_date']));
					
					
					if($arr['status'] == 2){
						//calculate working hours when cancel bill
						$arr['working_hour'] = isset($input['cancel_hour']) && !empty($input['cancel_hour']) ? (int)$input['cancel_hour'] : 0;
					}
					

					//ifschedule
					$weekday = ltrim(date('w', strtotime($arr['schedule_date'])), '0');
					//$arr['ifschedule'] = $weekday == 6 || $weekday ==0 || ($arr['btime'] > '17:00' && $arr['btime'] < '23:59') || ($arr['btime'] < '07:00' && $arr['btime'] > '00:00') ? 2 : 1;

				}
				//var_dump($arr,$arr_worklog_add,$arr_worklog_update,$where_worklog_update);exit;

				$res = $this->schedule_model->update($arr, ['schedule_id'=>$schedule_id]);
				
				if($res){

					//work_log
					if(isset($input['employee_id']) && !empty($input['employee_id'])){

						//original worklog
						$where_worklog['worklog.schedule_id'] = $schedule_id;
						$res_worklog = $this->worklog_model->listsTxdot('wl_id', $where_worklog, '', $json = true, $orderby = '', 1, 10);

						foreach($res_worklog['result'] as $res){
							$wl_id_ori[] = $res->wl_id;
						}
						//var_dump($wl_id_ori);

						//employee rate
						if(array_filter($input['employee_id'],'strlen')){
							//echo 1;
							$where_rate['employee_id'] = $input['employee_id'] ;
							$employee = $this->employee_model->lists($this->employee_lists_fields , $where_rate, $like = '', $json = true, $orderby = array('nick_name'=>'asc'), 1, 500);
							$rate = [];
							foreach($employee['result'] as $r){
								$rate[$r->employee_id] = $r->rate;
							}
						}

						$arr_worklog_add = [];
						$arr_worklog_update = [];
						
						foreach($input['ticket_id'] as $ticket_key=>$ticket_id){
							if($ticket_id || $input['employee_id'][$ticket_key] || $input['truck_id'][$ticket_key]){

								if($wl_id[$ticket_key]){
									
									unset($wl_id_ori[array_search($wl_id, $wl_id_ori)]);

									$arr_worklog_update = ['ticket_id'=>$ticket_id , 'employee_id'=>$input['employee_id'][$ticket_key], 'truck_id'=>$input['truck_id'][$ticket_key], 'ifbill'=>1, 'employee_rate'=>$rate[$input['employee_id'][$ticket_key]]];

									$resWl = $this->worklog_model->update($arr_worklog_update, ['wl_id'=>$wl_id[$ticket_key]]);
								}else{
									$arr_worklog_add[] = ['schedule_id'=>$schedule_id, 'ticket_id'=>$ticket_id , 'employee_id'=>$input['employee_id'][$ticket_key], 'truck_id'=>$input['truck_id'][$ticket_key], 'ifbill'=>1, 'status'=>0, 'employee_rate'=>$rate[$input['employee_id'][$ticket_key]], 'addtime'=>date('Y-m-d H:i:s')];

									$resWl = $this->worklog_model->add_batch($arr_worklog_add);
								}
							}
						}
						//var_dump($wl_id_ori);exit;
						//delete the worklogs from the update page
						if(array_filter($wl_id_ori)){
							foreach($wl_id_ori as $wl){
								$whereWl['wl_id'] = $wl;
								$deleteWl['isdeleted'] = 1;

								//var_dump($deleteWl, $whereWl);
								$res = $this->worklog_model->update($deleteWl, $whereWl);

								unset($deleteWl, $whereWl);
							}
						}
					}

					$this->tool_model->redirect($backurl, "Update successfully.");exit;

				}else{
					$this->tool_model->redirect($backurl, "Update schedule and work log unsuccessfully.");exit;
				}
			}else{
				$this->tool_model->redirect($backurl, "Update schedule and work log unsuccessfully.");exit;
			}
		}
	}


	/**
	* Purpose: Duplicate commercial schedule
	* @param {array} array - id, date, etc.
	* @return: {json}
	*/
	public function duplicate(){
		$input = $this->input->post();
		$schedule_id = isset($input['id']) && !empty($input['id']) ? $input['id'] : '';
		$date = isset($input['date']) && !empty($input['date']) ? date('Y-m-d', strtotime($input['date'])) : '';
		$btime_req = isset($input['btime_req']) && !empty($input['btime_req']) ? $input['btime_req'] : '';
		$backurl = isset($input['backurl']) && !empty($input['backurl']) ? urldecode($input['backurl']) : $_SERVER['HTTP_REFERER'] ;
		//var_dump($input);exit;
		if($schedule_id && $date){
			$where_schedule['schedule_id'] = $schedule_id;
			$res_schedule = $this->schedule_model->lists($this->schedule_duplicate, $where_schedule, '' , $json = true, $orderby = '', 1, 1);

			if($res_schedule['total']){
				$res_schedule = $res_schedule['result'][0];
				$addSch['task_id'] = $res_schedule->task_id;
				$addSch['schedule_year'] = date('Y', strtotime($date));
				$addSch['schedule_month'] = date('n', strtotime($date));
				$addSch['schedule_week'] = ltrim(date('W', strtotime($date)), '0');
				$addSch['schedule_date'] = $date;
				$addSch['btime_req'] = $btime_req ? $btime_req : $res_schedule->btime_req;
				$addSch['location'] = $res_schedule->location;
				$addSch['comment'] = $res_schedule->comment;
				$addSch['pon'] = $res_schedule->pon;
				$addSch['contact'] = $res_schedule->contact;
				$addSch['ifschedule'] = $res_schedule->ifschedule;
				$addSch['disposal_price'] = $res_schedule->disposal_price;
				$addSch['traffic_control_price'] = $res_schedule->traffic_control_price;
				$addSch['unit_price'] = $res_schedule->unit_price;
				$addSch['travel_hour'] = $res_schedule->travel_hour;
				$addSch['addtime'] = date('Y-m-d H:i:s');

				$res_add = $this->schedule_model->add($addSch);

				if($res_add){
					$this->tool_model->redirect($backurl, 'New schedule has been created!');
				}else{
					$this->tool_model->redirect($backurl, 'Oops, something is missing. Ask technician. Error code #193803.');
				}
			}else{
				$this->tool_model->redirect($backurl, 'Oops, something is missing. Ask technician. Error code #193802.');
			}
		}else{
			$this->tool_model->redirect($backurl, 'Oops, something is missing. Ask technician. Error code #193801.');
		}
	}


	/**
	* Purpose: Complete schedule ajax
	* @param {array} array - id, date, etc.
	* @return: {json}
	*/
	public function complete(){
		$input = $this->input->post();
		$schedule_id = isset($input['id']) && !empty($input['id']) ? $input['id'] : '';
		
		if($schedule_id){
			$backurl = isset($input['backurl']) && !empty($input['backurl']) ? $input['backurl'] : $_SERVER['HTTP_REFERER'] ;
			$status = isset($input['status']) && $input['status'] != '' ? $input['status'] : 1;
			isset($input['date']) && !empty($input['date']) ? $this->db->set('date', date("Y-m-d", strtotime($input['date']))) : $this->db->set('date', 'schedule_date', FALSE);;

			$this->db->set('status', $status);
			$this->db->where('schedule_id', $schedule_id);
			$result = $this->db->update('schedule');
			//echo json_encode($this->db->last_query());exit;
			//$result = $this->schedule_model->update($arr, ['schedule_id'=>$schedule_id]);
			if($result){
				//$this->tool_model->redirect($backurl, 'Set up success.');
				echo json_encode(1);
			}else{
				//$this->tool_model->redirect($backurl, 'Set up failure.');
				echo json_encode(0);
			}
		}else{
			//$this->tool_model->redirect($backurl,'');
			echo json_encode(0);
		}
	}

	/**
	* Purpose: Schedule delete (ajax)
	* @param {array} array - id, date, etc.
	* @return: {json}
	*/
	public function delete(){
		$input = $this->input->post();
		$schedule_id = isset($input['id']) && !empty($input['id']) ? $input['id'] : '';

		if($schedule_id){
			$result = $this->schedule_model->delete($schedule_id);
			if($result){
				echo json_encode(1);
			}else{
				echo json_encode(0);
			}
		}else{
			echo json_encode(0);
		}
	}

	/**
	* Purpose: Schedule calendar page
	* @param null
	* @return: {json}
	*/
	public function calendar(){
		$input = $this->input->get();
		isset($input['contract']) && !empty($input['contract']) ? $where['company.contract_id'] = $input['contract'] : '';
		isset($input['category']) && !empty($input['category']) ? $where['task_cat.category'] = $input['category'] : '';
		

		/*//schedule with no schedule date
		$where['company.type'] = 1;
		$where['schedule.schedule_date'] = null ;
		$result = $this->schedule_model->lists($this->calendar_lists, $where, $like = '', $json = true, $orderby = ['schedule.schedule_date'=>'desc'], 1, 500);*/

		//contract
		$where_contract['type'] = 1;//txdot
		$res_contract = $this->contract_model->lists($this->contract_lists, $where_contract,  $like = '', $json = true, $orderby = ['contract.contract_id_pannell'=>'asc'], 1, 500);
		
		//search
		$search['contract'] = isset($input['contract']) && !empty($input['contract']) ? $input['contract'] : '';
		$search['category'] = isset($input['category']) && !empty($input['category']) ? $input['category'] : '';
		if(isset($input['date']) && !empty($input['date'])){
			$search['date'] = $input['date'];
			$date = explode('/',urldecode($input['date']));
			$data['defaultDate'] = $date['1'].'-'.$date['0'].'-01';
		}else{
			$data['defaultDate'] = date('Y-m-d');
		}

		//$data['no_schedule_date'] = $result['result'];
		$data['contract'] = array_merge([(object)['contract_id' => 0, 'contract_id_pannell' => 'Select Contract']], $res_contract['result']);
		$data['search'] = $search;
		$data['current'] = $this->current;
		//var_dump($search);
		$this->load->view('schedule/calendar', $data);
	}



	/**
	* Purpose: Content of schedule calendar， depend on schedule_date for unfinished ones, date for finished ones
	* @param null
	* @return: {json}
	*/
	public function listsCalendar(){
		// Our Start and End Dates
	    $start = $this->input->get("start");
	    $end = $this->input->get("end");
	    $contract = $this->input->get("contract");
	    $category = $this->input->get("category");
	    
	    //echo json_encode($start);exit;
	    $startdt = new DateTime('now'); // setup a local datetime
	    $startdt->setTimestamp($start); // Set the date based on timestamp
	    $start_format = $startdt->format('Y-m-d');

	    $enddt = new DateTime('now'); // setup a local datetime
	    $enddt->setTimestamp($end); // Set the date based on timestamp
        $end_format = $enddt->format('Y-m-d');

	  	
		$where['company.type'] = 1;
		$contract ? $where['contract.contract_id'] = $contract : '' ;
		$category ? $where['task_cat.category'] = $category : '' ;
		
		//$where['schedule.schedule_month'] = 5;//test
		//$where['schedule.schedule_year'] = 2018;//test
		//$where['schedule.schedule_date is not null'] = null;//test
		//$start_format ? $where['schedule.schedule_date >='] = $start_format : '';
		//$end_format ? $where['schedule.schedule_date <='] = $end_format : '';
		$whereQuery = [];
		$whereQuery[] = '((schedule.schedule_date >= "'.$start_format.'" and schedule.schedule_date <= "'.$end_format.'") or (schedule.date >= "'.$start_format.'" and schedule.date <= "'.$end_format.'"))';

		/*$start_format ? $whereQuery[] = '(schedule.schedule_date >= "'.$start_format.'" or schedule.date >= "'.$start_format.'")' : '';
		$end_format ? $whereQuery[] = '(schedule.schedule_date <= "'.$end_format.'" or schedule.date <= "'.$end_format.'")' :'';*/


		$result = $this->schedule_model->lists($this->calendar_lists, $where, $like, $json = true, $orderby = ['schedule.schedule_date'=>'asc', 'contract.contract_id'=>'asc', 'task_cat.tcat_id'=>'asc', 'task.tract'=>'asc'], 1, 10000, $where_in = '', $distinct = 0, $whereQuery);
		//echo '<pre>';
		//var_dump($result['result']);
		$order = 0;//testing
		$data = [];
		$count = [];
		foreach($result['result'] as $res){
			if($res->schedule_date){
				/*if(in_array($res->company_id, array(5, 6))){//harris, brazoria
					$color = 'pink';
				}elseif(in_array($res->company_id, array(7, 8))){//cameron, hildago
					$color = 'green';
				}elseif(in_array($res->company_id, array(1, 4, 9))){//dallas, tarrant dbi, tarrant
					$color = '#75BEF7';
				}*/
				//var_dump($res->tcat_name);
				/*switch ($res->company_id) {
					case '5'://harris pink
						$color = '#FB60D4';
						break;
					case '6'://brazoria pink
						$color = '#FD30C9';
						break;
					case '7'://cameron lime
						$color = '#65FB00';
						break;
					case '8'://hildago lime
						$color = '#9FF865';
						break;
					case '1'://dallas aqua
						$color = '#3CA1FA';
						break;
					case '4'://tarrant dbi aqua
						$color = '#80C0F9';
						break;
					case '9'://tarrant aqua
						$color = '#0289FF';
						break;
					case '39'://collin aqua
						$color = '#0289FF';
						break;
					case '58'://Denton aqua
						$color = '#3B54F7';
						break;
					default://grey
						$color = '#C9CACC';
						break;
				}*/

				$color = $res->colorcode ? $res->colorcode : '#C9CACC';//default grey


				if($res->date == null || $res->date == '' || $res->date == '0000-00-00'){
					$color = '#C9CACC';//grey
					$showDate = $res->schedule_date;
					$status = 0;
				}else{
					$showDate = $res->date;
					$status = 1;
				}

				$category = $res->category == 1 ? 'Debris' : 'Sweeping';

				
				
				//constraint drag period
				/*if($res->mindate || $res->maxdate){
					$subCon['id'] = 'constraint_'.$res->schedule_id;
					$res->mindate ? $subCon['start'] = $res->mindate : '';
					$res->maxdate ? $subCon['end'] = $res->maxdate : '';
					$subCon['rendering'] = 'background';

					$data[] = $subCon;
				}*/

				//
				$sub = array(
					'id'			=>		$res->schedule_id,
					'title' 		=> 		$res->company_name.' '.$category,
					'description'	=>		'Type '.$res->tcat_name.', Tract '.$res->tract.', '.$res->hwy_id,
					'start'			=>		$showDate,
					'end'			=>		$showDate,
					'schedule_date'	=>		$res->schedule_date,
					'complete_date'	=>		$res->date,
					'color'			=>		$color,
					'contract'		=>		$res->contract_id_pannell,
					'tcat_name'		=>		$res->tcat_name,
					'tract'		    =>		$res->tract,
					'cycle'		    =>		$res->cycle,
					'frequency'		=>		$res->frequency,
					'section_from'	=>		(string)$res->section_from,
					'section_to'	=>		(string)$res->section_to,
					'status'		=>		$status,
					'order'			=>		$order ++,
					
				);

				$data[] = $sub;
				//var_dump($res->schedule_id,count($data));
				unset($color, $status, $sub, $subCon);

				//count mileage
				$count[$showDate] = $count[$showDate] ? $count[$showDate] + $res->mile * $res->index_centerline : $res->mile * $res->index_centerline;
			}
			
		}

		foreach($count as $showDate=>$cvalue){
			$sub = array(
					'id'			=>		'',
					'title' 		=> 		'Total Mileage:',
					'description'	=>		sprintf("%.2f", $cvalue ),
					'start'			=>		$showDate,
					'end'			=>		$showDate
					
				);
			$data[] = $sub;
			unset($sub);
		}


		//$data['company_id_test'] = $company;
		//$data['start_test'] = $start;
		//$data['end_test'] = $end;

		echo json_encode(array("events" => $data));
		
	}



	/**
	* Purpose: Schedule update(calendar)
	* @param null
	* @return: {json}
	*/
	public function updateCalendar(){
		$schedule_id = $this->input->post("event_id");
	    $schedule_date = $this->input->post("date");
	    $schedule_date = date('Y-m-d', strtotime($schedule_date));
	    $status = $this->input->post("status");
	    $fromurl = $this->input->post("fromurl");
	    //var_dump($fromurl,$schedule_date);exit;
	    if(!$schedule_id){
	    	$this->tool_model->redirect($fromurl, "Unsuccess! Ask IT for ID.");
	    }else{
	    	$where_minmaxdate['schedule.schedule_id'] = $schedule_id;
	    	$result_minmaxdate = $this->schedule_model->lists('schedule.mindate, schedule.maxdate', $where_minmaxdate, '', $json = true, $orderby = ['schedule.schedule_date'=>'desc'], 1, 1);

	    	if($result_minmaxdate['result'][0]->mindate >= $schedule_date){
	    		$this->tool_model->redirect(BASE_URL().$fromurl, "Unsuccess! Schedule date is too early.");
	    	}elseif($result_minmaxdate['result'][0]->maxdate <= $schedule_date){
	    		$this->tool_model->redirect(BASE_URL().$fromurl, "Unsuccess! Schedule date is too late.");
	    	}else{
	    		$where['schedule_id'] = $schedule_id;
		    	$data['schedule_date'] = date('Y-m-d', strtotime($schedule_date));
		    	$data['status'] = $status == 1 ? 1 : 0;

		    	$res = $this->schedule_model->update($data,$where);
		    	if($res){
		    		//redirect(site_url("schedule/calendar"), "Success!");
		    		$this->tool_model->redirect(BASE_URL().$fromurl, "Success!");
		    	}else{
		    		$this->tool_model->redirect(BASE_URL().$fromurl, "Unsuccess! Ask IT for update failure.");
		    	}
	    	}

	    	
	    }
	}



	/**
	* Purpose: Monthly calendar download page
	* @param null
	* @return: {json}
	*/
	public function calendarDownload(){
		$input = $this->input->get();
		isset($input['company']) && !empty($input['company']) ? $where['company.company_id'] = $input['company'] : '';
		
		//schedules
		$where['company.type'] = 1;
		if(isset($input['date']) && !empty($input['date'])){
			$search['date'] = $input['date'];
			$date = explode('/',urldecode($input['date']));
			$defaultDate = $date[1].'-'.$date[0].'-01';
			$where['schedule_year'] = $date[1];
			$where['schedule_month'] = ltrim($date[0], '0');
		}else{
			$defaultDate = date('Y-m-d');
			$where['schedule_year'] = date('Y');
			$where['schedule_month'] = date('j');
		}
		
		$result = $this->schedule_model->lists($this->calendar_lists, $where, $like = '', $json = true, $orderby = [ 'schedule.schedule_week'=>'asc', 'task.hwy_id'=>'asc', 'task_cat.tcat_id'=>'asc','task.tract'=>'asc'], 1, 500);
		

		$schedule = [];
		$weekNum = [];
		foreach($result['result'] as $res){
			$temp_sch['type'] = $res->tcat_name;
			$temp_sch['tract'] = $res->tract;
			$temp_sch['hwy_id'] = $res->hwy_id;
			$temp_sch['from'] = $res->section_from;
			$temp_sch['to'] = $res->section_to;
			$temp_sch['mile'] = number_format($res->mile, 2);
			$temp_sch['cycle'] = $res->cycle;

			$weekNum[] = $res->schedule_week;

			$schedule[$res->schedule_week][$res->category][] = $temp_sch;

			$sum[$res->schedule_week][$res->category] += $res->mile * $res->index_centerline;
		}
		
		//var_dump($sum);
		$weekNum = array_unique($weekNum);
		sort($weekNum);
		//var_dump($weekNum);

		//month calendar
		$calendar =[];
		foreach($weekNum as $wn){

			/*//test
			$where['schedule_year'] = 2018;
			$where['schedule_month'] = 7;
			$wn = 31;*/

			$wn1 = $wn - 1;//the week before current week
			$wn2 = strlen($wn) == 2 ? $wn : '0'.$wn;//standardize the week number into 2 digital
			$wn21 = strlen($wn-1) == 2 ? $wn-1 : '0'.($wn-1);//standardize the previous week number into 2 digital

			$weekStart = date( "n", strtotime($where['schedule_year']."W".$wn21."7") ) == $where['schedule_month']  ? date( "M jS, Y (l)", strtotime($where['schedule_year']."W".$wn21."7") ) : date("M jS, Y (l)", strtotime($where['schedule_year']."-".$where['schedule_month']."-01")); // First day of week, either Sunday or first day of the month when it's not within current month

			$dayyyy = date('j', strtotime($where['schedule_year']."W".$wn2."6"));

			$weekEnd = date( "n", strtotime($where['schedule_year']."W".$wn2."6") ) == $where['schedule_month']  ? date( "M jS, Y (l)", strtotime($where['schedule_year']."W".$wn2."6") ) : date("M jS, Y (l)", strtotime("- ".$dayyyy." days", strtotime($where['schedule_year']."W".$wn2."6" ))); // Last day of week, either Saturday or the last da y of the month when it's not within current month 

			$calendar[$wn] = $weekStart.' - '.$weekEnd;

		}

		//company
		$where_company['type'] = 1;//txdot
		$where_company['company.company_id'] = isset($input['company']) && !empty($input['company']) ?  $input['company'] : '';
		$res_company = $this->company_model->lists('company.company_id, company.company_name', $where_company,  $like = '', $json = true, $orderby = ['company.company_name'=>'asc'], 1, 1);


		$data['defaultDate'] = $defaultDate;
		$data['yearMonth'] = date('M Y', strtotime($defaultDate));
		$data['schedule'] = $schedule;
		$data['calendar'] = $calendar;
		$data['company'] = $res_company['result'][0];
		$data['sum'] = $sum;

		//var_dump($schedule);
		$this->load->view('schedule/calendarDownload', $data);
	}


/**
	* Purpose: Monthly calendar download page 2 ----- real calendar ahaaaaaa
	* @param {array} contract_id, year and month
	* @return: array
	*/
	public function calendarDownload2(){
		$input = $this->input->get();
		isset($input['contract']) && !empty($input['contract']) ? $where['contract.contract_id'] = $input['contract'] : '';
		isset($input['category']) && !empty($input['category']) ? $where['task_cat.category'] = $input['category'] : '';
		$ifMileActual = isset($input['ifmileact']) && !empty($input['ifmileact']) ? $input['ifmileact'] : 0 ;//show actual miles or not

		if($where['contract.contract_id'] && $where['task_cat.category']){
			//schedules
			$where['company.type'] = 1;
			if(isset($input['date']) && !empty($input['date'])){
				$search['date'] = $input['date'];
				$date = explode('/',urldecode($input['date']));
				$defaultDate = $date[1].'-'.$date[0].'-01';
				$where['schedule_year'] = $date[1];
				$where['schedule_month'] = ltrim($date[0], '0');
			}else{
				$defaultDate = date('Y-m-d');
				$where['schedule_year'] = date('Y');
				$where['schedule_month'] = date('j');
			}
			
			$result = $this->schedule_model->lists($this->calendar_lists, $where, $like = '', $json = true, $orderby = [ 'schedule.schedule_week'=>'asc', 'task_cat.tcat_id'=>'asc','task.tract'=>'asc'], 1, 500);
			
			
			//calendar content
			$schedule = [];
			foreach($result['result'] as $res){
				if(!$schedule[$res->schedule_date]){
					$schedule[$res->schedule_date]['mile_all'] = $res->mile;
					$schedule[$res->schedule_date]['mile_all_actual'] = $res->mile * $res->index_centerline;
				}else{
					$schedule[$res->schedule_date]['mile_all'] = $schedule[$res->schedule_date]['mile_all'] + $res->mile;
					$schedule[$res->schedule_date]['mile_all_actual'] = $schedule[$res->schedule_date]['mile_all_actual'] + $res->mile * $res->index_centerline;
				}
				//var_dump($res->tcat_name);
				if(!$schedule[$res->schedule_date]['task'][$res->tract]){
					$schedule[$res->schedule_date]['task'][$res->tract] = $res->tract.': '.$res->hwy_id.', '.str_replace('Type', '',$res->tcat_name);
					ksort($schedule[$res->schedule_date]);

				}else{
					$schedule[$res->schedule_date]['task'][$res->tract] .= ' & '.str_replace('Type', '',$res->tcat_name);
				}
				
			}

			

			//find the first day of the calendar
			$firstDayOfMonth = $where['schedule_year'].'-'.$where['schedule_month'].'-01';
			$weekdayFirst = date('w', strtotime($firstDayOfMonth));
			$firstDayOfCalendar = date('Y-m-d', strtotime('- '.$weekdayFirst.' days', strtotime($firstDayOfMonth)));

			$lastDayOfMonth = date('Y-m-t', strtotime($firstDayOfMonth));
			$weekdayLast = date('w', strtotime($lastDayOfMonth));
			$lastDayOfCalendar = date('Y-m-d',strtotime('+ '.(6 - $weekdayLast).' days', strtotime($lastDayOfMonth)));

			//create calendar date
			//date_default_timezone_set('America/Chicago');
			$_calendar = range(strtotime($firstDayOfCalendar) + 60*60 + 1, strtotime($lastDayOfCalendar) + 60*60*2 + 1, 24*60*60);//adding 60*60(*2) to avoid the change in summer and winter time
			//$_calendar = range(strtotime($firstDayOfCalendar), strtotime($lastDayOfCalendar), 24*60*60);//adding 60*60(*2) to avoid the change in summer and winter time
			$_calendar = array_map(create_function('$v', 'return date("Y-m-d", $v);'), $_calendar);

			//combine calendar date and schedule
			$calendar = [];
			$i = 0;
			$j = 0;
			foreach($_calendar as $cld){
				$tem_date['date'] = $cld;
				$tem_date['day'] = date('d', strtotime($cld));
				$tem_date['ifCurrentMonth'] = date('m', strtotime($cld)) == $where['schedule_month'] ? 1 : 0;
				$tem_date['class'] = 'date';
				$calendar[$j * 2 + 1][] = $tem_date;

				$tem_cal['content'] = $schedule[$cld] ? $schedule[$cld] : [] ;
				$tem_cal['class'] = 'content';
				$calendar[$j * 2 + 2][] = $tem_cal;
				//var_dump($tem_cal['content']);
				if($i < 6){
					$i ++;
				}else{
					$i = 0;
					$j ++;
				}
			}

			//title & note
			$title['year'] = $where['schedule_year'];
			$title['month'] = $monthName = date('F', mktime(0, 0, 0, $where['schedule_month'], 18));
			$title['category'] = $where['task_cat.category'] == 1 ? 'Debris' : 'Sweeping';

			$note = '* '.$result['result'][0]->company_name.' County Calendar - '.$result['result'][0]->contract_id_ori;

			if($where['task_cat.category'] == 1 ){
				$note .= '<br>&nbsp&nbsp&nbspType I&II: DRBRIS REMOVAL - CENTER MEDIAN/MAIN LANES
<br>&nbsp&nbsp&nbspType III: DRBRIS REMOVAL - FRONTAGE ROAD
<br>&nbsp&nbsp&nbspType IV: DRBRIS REMOVAL - ENTRANCE & EXIT RAMPS
';
			}else{
				$note .= '<br>&nbsp&nbsp&nbspType I: SWEEPING - CENTER MEDIAN
<br>&nbsp&nbsp&nbspType II: SWEEPING - OUTSIDE MAINLANE
<br>&nbsp&nbsp&nbspType III: SWEEPING - FRONTAGE ROAD
<br>&nbsp&nbsp&nbspType IV: SWEEPING - ENTRANCE / EXIT RAMPS';
			}
			

			//
			$data['calendar'] = $calendar;
			$data['title'] = $title;
			$data['note'] = $note;
			$data['ifMileActual'] = $ifMileActual;
			
			$this->load->view('schedule/calendarDownload2', $data);


		}else{
			$this->tool_model->redirect(BASE_URL().'schedule/calendar', 'Please choose Contract Name, Month, and Sweeping/Debris.');
		}
		
	}



	/**
	* Purpose: Auto complete location when add and update schedule
	* @param null
	* @return: {json}
	*/
	public function autocompleteLocation(){
		//$_REQUEST['q']['term'] = 'air';
		$like[] = ['name' => 'schedule.location', 'value' => $_REQUEST['q']['term']];
		$_REQUEST['t']['taskId'] ? $where['schedule.task_id'] = $_REQUEST['t'] : '';
		$where['schedule.location <>'] = null;
		//var_dump($like);
		$res = $this->schedule_model->lists( 'distinct schedule.location' , $where, $like, $json = true, $orderby = array('schedule.addtime'=>'desc'), 1, 500);
		$result = [];
		foreach($res['result'] as $r){
			$result[] = $r->location;
		}
		echo json_encode($result);
	}

	/**
	* Purpose: Auto complete contact when add and update schedule
	* @param null
	* @return: {json}
	*/
	public function autocompleteContact(){
		//$_REQUEST['q']['term'] = 'air';
		$like[] = ['name' => 'schedule.contact', 'value' => $_REQUEST['q']['term']];
		$_REQUEST['t']['taskId'] ? $where['schedule.task_id'] = $_REQUEST['t'] : '';
		$where['schedule.contact <>'] = null;
		//var_dump($like);
		$res = $this->schedule_model->lists( 'distinct schedule.contact' , $where, $like, $json = true, $orderby = array('schedule.addtime'=>'desc'), 1, 500);
		$result = [];
		foreach($res['result'] as $r){
			$result[] = $r->contact;
		}
		echo json_encode($result);
	}


	/**
	* Purpose: Drag events in calendar
	* @param  {array} array - id, dayDiff, etc.
	* @return: {json}
	*/
	public function drag(){
		$input = $this->input->post();
		$schedule_id = isset($input['id']) && !empty($input['id']) ? $input['id'] : '' ;
		$date = isset($input['dayDiff']) && !empty($input['dayDiff']) ? $input['dayDiff'] : '' ;

		if(!($schedule_id && $date)){
			echo json_encode(['msg' => 'miss id or date']);exit;
		}else{
			$where_minmaxdate['schedule_id'] = $schedule_id;
			$res_minmaxdate = $this->schedule_model->lists( 'schedule.mindate, schedule.maxdate, schedule.status' , $where_minmaxdate, $like, $json = true, $orderby = array('schedule.addtime'=>'desc'), 1, 1);
			$res_minmaxdate = $res_minmaxdate['result'][0];
			if($res_minmaxdate->status == 1){
				echo json_encode(['msg' => 'cannot drag completed schedule' ]);exit;
			}elseif($res_minmaxdate->mindate && $res_minmaxdate->maxdate && $date < $res_minmaxdate->mindate && $date > $res_minmaxdate->maxdate){
				echo json_encode(['msg' => 'out of valid date range']);exit;	
			}else{
				$where['schedule_id'] = $schedule_id;
				$data['schedule_date'] = date('Y-m-d', strtotime($date));
				$data['schedule_year'] = date('Y', strtotime($date));
				$data['schedule_month'] = date('n', strtotime($date));
				$data['schedule_week'] = date('W', strtotime($date));

				$res = $this->schedule_model->update($data,$where);
				
				if($res){
					echo json_encode(1);exit;
				}else{
					echo json_encode(['msg' => 'failed']);exit;
				}		
			}		
		}
	}


	/**
	* Purpose: print commercial tickets
	* @param  {array} array - checkall, company_type, etc.
	* @return: {json}
	*/
	public function printTxdotWork(){
		$input = $this->input->post();
		//var_dump($input);

		//$input['checkall'][] = "19714";//test, disposal_price
		//$input['checkall'][] = "19730";//test, traffic_control_price

		$combine = isset($input['checkall']) && !empty($input['checkall']) ? $input['checkall'] : '';
		
		$workReport = [];
		if($combine){
			foreach($combine as $com){
				$infoCurArr = explode('#', $com);

				//get task info from schedule table
				$whereSch['contract.contract_id'] = $infoCurArr[0];
				$whereSch['task_cat.category'] = $infoCurArr[1];
				$whereSch['schedule.schedule_date'] = date('Y-m-d', strtotime($infoCurArr[2]));	
				//var_dump($whereSch);
				$resSchedule = $this->schedule_model->lists( $this->txdot_print_sch ,  $whereSch,  $like = '', $json = true, $orderby = ['schedule.crew_id'=>'desc', 'task.hwy_id'=>'asc', 'task_cat.tcat_id'=>'asc' , 'task.tract'=>'asc'], 1, 500);
				//var_dump($resSchedule);

				//get driver and truck info from worklog table
				$whereWl['worklog.contract_id'] = $infoCurArr[0];
				$whereWl['worklog.category'] = $infoCurArr[1];
				$whereWl['worklog.schedule_date'] = date('Y-m-d', strtotime($infoCurArr[2]));	
				$resWl = $this->worklog_model->listsTxdot( $this->txdot_print_wl , $whereWl, $like = '', $json = true, $orderby = array('worklog.schedule_id'=>'asc'), 1, 500 );

				//var_dump($resWl);

				//migrate task, driver and truck into 1 working report
				$curWorkReport['contract_name'] = $resSchedule['result'][0]->company_name;
				$curWorkReport['category'] = $infoCurArr[1];
				$curWorkReport['date'] = date('Y-m-d', strtotime($infoCurArr[2]));

				//default temp value
				$tempType = ''; //last task's type
				$tempHwy = ''; //last task's hwy id
				$tempFrom = ''; //last task's from
				$tempTo = ''; //last task's to
				$tempMile = ''; //last task's mile
				$tempTract = ''; //last task's tract
				
				$tempLine = 1; //last task's line number

				$thisFrom = ''; //from this turn
				$thisTo = ''; //to this turn
				$thisMile = ''; //mile this turn
				$thisTract = ''; //tract this turn

				foreach($resSchedule['result'] as $res){

					//$type = $this->txdotTypeConvert($res->type, $iftype = 0);

					$thisMile = $res->mile;
					$thisFrom = $res->section_from;
					$thisTo = $res->section_to;
					$thisTract = $res->tract;
					$thisDate = $res->date && $res->date != '0000-00-00' ? date('m/d/Y', strtotime($res->date)) : '';
					$thisBtime = $res->btime && $res->btime != '00:00:00' ? date('h:ia', strtotime($res->btime)) : '';
					$thisEtime = $res->etime && $res->etime != '00:00:00' ? date('h:ia', strtotime($res->etime)) : '';

					if($tempType != $res->type || $tempHwy != $res->hwy_id){//blank row
						$curWorkReport['task'][$res->crew_id][] = ['tract'=>'', 'type'=>'', 'hwy_id'=>'', 'section_from'=>'', 'section_to'=>'', 'btime'=>'', 'etime'=>'', 'date'=>''];
						
					}else{

						//if combine with previous
						if($thisFrom == $tempTo || $thisTo == $tempFrom){
							
							$maxKey = 0;
							foreach($curWorkReport['task'][$res->crew_id] as $keyyyy=>$valueless){
								$keyyyy > $maxKey ? $maxKey = $keyyyy : '';
							}
							//echo '<pre>';
							//var_dump($curWorkReport['task'][$res->crew_id],$maxKey);
							unset($curWorkReport['task'][$res->crew_id][$maxKey]);

							if($tempLine == 2) unset($curWorkReport['task'][$res->crew_id][$maxKey - 1]) ;

							$thisMile += $tempMile;
							$thisTract = $tempTract.'&'.$thisTract;
							$thisFrom == $tempTo ? $thisFrom = $tempFrom : $thisTo = $tempTo;
							

						}
					}

//add task
					$curWorkReport['task'][$res->crew_id][] = ['tract'=>$thisTract, 'type'=>$res->type, 'hwy_id'=>$res->hwy_id, 'section_from'=>$thisFrom, 'section_to'=>$thisTo, 'btime'=>$thisBtime, 'etime'=>$thisEtime, 'mile'=>sprintf("%.2f", $thisMile), 'date'=>$thisDate];
					
					
					//if($res->tract == 3){var_dump($curWorkReport['task'][$res->crew_id]);exit;}


					//duplicate the task and reverse the from and to for type I, II and III, except collin sweeping tract 5 type I and II
					if(in_array($res->tcat_id, [1 ,2, 7, 8, 9]) && !in_array($res->task_id, [1616,1617,1634,1635])){
						$curWorkReport['task'][$res->crew_id][] = ['tract'=>$thisTract, 'type'=>$res->type, 'hwy_id'=>$res->hwy_id, 'section_from'=>$thisTo, 'section_to'=>$thisFrom, 'btime'=>$thisBtime, 'etime'=>$thisEtime, 'mile'=>sprintf("%.2f", $thisMile), 'date'=>$thisDate];

						$tempLine = 2;

					}else{
						$tempLine = 1;
					}



					//set temp value
					$tempType = $res->type; 
					$tempHwy = $res->hwy_id; 
					$tempFrom = $thisFrom; 
					$tempTo = $thisTo; 
					$tempTract = $thisTract; 
					$tempMile = $thisMile; 
						

				}
				
				
				
				foreach($resWl['result'] as $wl){
					$curWorkReport['driver'][$wl->crew_id][] = $wl->employee_id ? $wl->employee_id : '&nbsp';
					$curWorkReport['truck'][$wl->crew_id][] = $wl->truck_id ?  $wl->truck_id : '&nbsp';
				}

				

				for($i = 1; $i <= count($curWorkReport['task']) ; $i ++){
					

					//sub 3 drivers positions
					if(count($curWorkReport['driver'][$i]) == null){
						$curWorkReport['driver'][$i] = ['&nbsp', '&nbsp', '&nbsp'];
						$curWorkReport['truck'][$i] = ['&nbsp', '&nbsp', '&nbsp'];
					}elseif(count($curWorkReport['driver'][$i]) < 3){
						
						for($j = 0; $j < 3 - count($curWorkReport['driver'][$i]) ; $j++){
							$curWorkReport['driver'][$i][] = '&nbsp';
							$curWorkReport['truck'][$i][] = '&nbsp';
						}
						
					}

					$workReport[] = array(
						'contract_name'   =>  $curWorkReport['contract_name'],
						'category'		  =>  $curWorkReport['category'],
						'date'		 	  =>  $curWorkReport['date'],
						'task'		 	  =>  array_slice($curWorkReport['task'][$i], 1),
						'truck'		 	  =>  $curWorkReport['truck'][$i],
						'driver'		  =>  $curWorkReport['driver'][$i]
					);
				}
				//$workReport[] = $curWorkReport;
				unset($curWorkReport);
				
			}
			//echo '<pre>';
			//var_dump($workReport);

			//driver
			$employee_where['group_id'] = 4;//driver
			$employee_where['status'] = 1;//active
			$res_driver = $this->employee_model->lists($this->employee_lists_fields, $where_driver, '', $json = true, $orderby = ['nick_name'=>'asc'], 1, 500);
			$driver = [];
			foreach($res_driver['result'] as $rd){
				$driver[$rd->employee_id] = $rd->nick_name.' '.$rd->last_name;
			}

			//truck
			$where_truck['type'] = [1,2,3,4];
			$where_truck['status'] = 1;
			$res_truck = $this->truck_model->lists($this->truck_lists_fields, $where_truck, '', $json = true, $orderby = ['type'=>'asc', 'number'=>'asc'], 1, 500);
			$truck = [];
			$truck_type = [1=>'SWE', 2=>'SWE-V', 3=>'DEB', 4=>'TMA', 5=>'OTHER'];
			foreach($res_truck['result'] as $rt){
				//$truck[$rt->truck_id] = $rt->number.' ('.$truck_type[$rt->type].')';
				$truck[$rt->truck_id] = $rt->number;
			}

			
			$data['workReport'] = $workReport;
			$data['driver'] = $driver;
			$data['truck'] = $truck;
		}
		
		$this->load->view('schedule/printTxdotWork', $data);
		
	}




	/**
	* Purpose: print commercial tickets
	* @param  {array} array - checkall, company_type, etc.
	* @return: {json}
	*/
	public function printTicket(){
		$input = $this->input->post();
		//var_dump($input);

		//$input['checkall'][] = "19714";//test, disposal_price
		//$input['checkall'][] = "19730";//test, traffic_control_price

		$schedule_id = isset($input['checkall']) && !empty($input['checkall']) ? $input['checkall'] : '';
		$company_type = isset($input['company_type']) && !empty($input['company_type']) ? $input['company_type'] : '';
		

		if($schedule_id){
			$whereTicket['schedule.schedule_id'] = $schedule_id;
			
			$ticket = $this->worklog_model->lists( $this->ticket_print , $whereTicket, $like = '', $json = true, $orderby = array('worklog.schedule_id'=>'asc'), 1, 500 );
			//var_dump($ticket);
			if(!$ticket['total']){
				$data['ticket'] = '';
			}else{
				$reg='/(\d{1})/is';//匹配数字的正则表达式
				//var_dump($ticket['result']);
				foreach($ticket['result'] as $tic){
					$tic->employee_name = ucwords(strtolower($tic->employee_name));
					$tic->company_name = ucwords(strtolower($tic->company_name));
					//$tic->ifTrafficControl = $tic->traffic_control_price != null &&  $tic->traffic_control_price != 0 ? 1 : 0;
					$tic->ifDisposal = $tic->disposal_price != null &&  $tic->disposal_price != 0 ? 1 : 0;
					$tic->schedule_date = date('m/d/Y', strtotime($tic->schedule_date));
					$tic->btime_req = $tic->btime_req ? date('h:i a', strtotime(date('Y-m-d').' '.$tic->btime_req)) : date('h:i a', strtotime(date('Y-m-d').' '.$tic->btime)) ;
					$tic->btime = $tic->btime ? date('h:i a', strtotime(date('Y-m-d').' '.$tic->btime)) : '';
					$tic->etime = $tic->etime ? date('h:i a', strtotime(date('Y-m-d').' '.$tic->etime)) : '';
					$tic->ifWeekend = in_array(date('N', strtotime($tic->schedule_date)), [6,7]) ? 1: 0;
					//var_dump($tic->schedule_date);
					//truck type check box
					switch ($tic->truck_type) {
						case 1:
							$tic->ifMech = 1;
							$tic->ifVac = 0;
							$tic->ifTrafficControl = 0;
							break;

						case 2:
							$tic->ifMech = 0;
							$tic->ifVac = 1;
							$tic->ifTrafficControl = 0;
							break;
						
						case 4:
							$tic->ifMech = 0;
							$tic->ifVac = 0;
							$tic->ifTrafficControl = 1;
							break;

						default:
							$tic->ifMech = 0;
							$tic->ifVac = 0;
							$tic->ifTrafficControl = 0;
							break;
					}

					//contact person and phone
					if($tic->contact){
		        		preg_match_all($reg,$tic->contact,$number);
		        		$posFirstNum = strpos($tic->contact, $number[0][1]);
		        		$tic->contactName = substr($tic->contact, 0, $posFirstNum - 2);
		        		$tic->phone = substr($tic->contact,-(strlen($tic->contact)-$posFirstNum+1));
		        		//var_dump($tic->contact, $posFirstNum,$tic->contactName, $tic->phone);
						unset($tic->contact);
					}

					//limit location per line
					if($tic->location){
						$tic->location = ucwords(strtolower($tic->location));
						$length = $curLength = 35;

						if(strlen($tic->location) > $length){
							for($i = $length; $i > 0; $i --){
								if(substr($tic->location, $i-1, 1) == ' '){
									$curLength = $i; break;
								}
							}

							$tic->location1 = substr($tic->location, 0, $curLength);
							$tic->location2 = substr($tic->location, $curLength);
						}else{
							$tic->location1 = $tic->location;
							$tic->location2 = '';
						}	
						
					}else{
						$tic->location1 = '';
						$tic->location2 = '';
					}

					//limit comment per line
					if($tic->comment){
						$length = $curLength = 60;

						if(strlen($tic->comment) > $length){
							for($i = $length; $i > 0; $i --){
								if(substr($tic->comment, $i-1, 1) == ' '){
									$curLength = $i; break;
								}
							}

							$tic->comment1 = substr($tic->comment, 0, $curLength);
							$tic->comment2 = substr($tic->comment, $curLength);

							if(strlen($tic->comment2) > $length){
								for($i = $length; $i > 0; $i --){
									if(substr($tic->comment2, $i-1, 1) == ' '){
										$curLength = $i; break;
									}
								}

								$tic->comment22 = substr($tic->comment2, 0, $curLength);
								$tic->comment3 = substr($tic->comment2, $curLength);
								$tic->comment2 = $tic->comment22;

							}
						}else{
							$tic->comment1 = $tic->comment;
							$tic->comment2 = '';
						}	
						
					}else{
						$tic->comment1 = '';
						$tic->comment2 = '';
					}
					
					//pon
					$tic->pon ? $tic->pon = "PO# ".$tic->pon : '';


					unset( $tic->traffic_control_price , $tic->disposal_price, $tic->location );
				}
			}
			

			//var_dump($ticket['result']);
 		}
 		$data['ticket'] = $ticket['result'];
		$this->load->view('schedule/printTicket', $data);
		//return 1;
	}

	/**
	* Purpose: print commercial tickets
	* @param  {array} array - checkall, company_type, etc.
	* @return: 
	*/
	public function monthlyBillCom(){
		$input = $this->input->post();
		if(!$input){
			$whereCon['company.type'] = 2;//commercial
			$contratRes = $this->contract_model->lists('contract.contract_id,contract.contract_id_pannell', $whereCon, $like = '', $json = true, $orderby = array('contract.contract_id_pannell'=>'asc'), 1, 500);
			$data['contract'] = array_merge([ (object)['contract_id' => 0, 'contract_id_pannell' => 'All contracts'] ],$contratRes['result']);
			$data['current'] = $this->currentRep;
			//$data['contract'] = $contratRes['result'];
			//var_dump($data);
			$this->load->view('schedule/monthlyBillCom.php', $data);
		}else{

			isset($input['bdate']) && !empty($input['bdate']) ? $whereBill['schedule.schedule_date >='] = date('Y-m-d', strtotime(urldecode($input['bdate']))) : '';
			isset($input['edate']) && !empty($input['edate']) ? $whereBill['schedule.schedule_date <='] = date('Y-m-d', strtotime(urldecode($input['edate']))) : '';
			isset($input['contract_id']) && !empty($input['contract_id']) ? $whereBill['contract.contract_id'] = $input['contract_id'] : '';
			isset($input['ifschedule']) && !empty($input['ifschedule']) ? $whereBill['schedule.ifschedule'] = $input['ifschedule'] : '';
			$whereBill['schedule.status'] = [1, 2]; //1:complete, 2:cancel bill
			$whereBill['company.type'] = 2;//commercial
			//var_dump($whereBill);exit;
			$bill = $this->worklog_model->lists( $this->monthlyBillCom , $whereBill, $like = '', $json = true, $orderby = array('schedule.date'=>'asc', 'schedule.ticket_id'), 1, 500 );

			if(!$bill['result']){
				$this->tool_model->redirect(BASE_URL().'schedule/monthlyBillCom', 'No result has been found.');
			}else{
				//load ExcelPHP library
				//$this->load->library("PhpSpreadsheet");
				/*
				$spreadsheet = new Spreadsheet();
				$sheet = $spreadsheet->getActiveSheet();
				$sheet->setCellValue('A1', 'Welcome to Helloweba.');

				$writer = new Xlsx($spreadsheet);
				$writer->save('hello.xlsx');*/


				//load PHPExcel library
				$this->load->library("PHPExcel");//both work
				//require(APPPATH.'libraries\\PHPExcel.php');require(APPPATH.'libraries\\PHPExcel\\Writer\\Excel2007.php'); //both work

				//Create PHPExcel object
				$objPHPExcel = new PHPExcel(); 
				//var_dump($objPHPExcel);
				//Set properties 
				$objPHPExcel->getProperties()->setCreator("Luxixi");  
				$objPHPExcel->getProperties()->setLastModifiedBy("Luxixi");  
				$objPHPExcel->getProperties()->setTitle("Billing Sheet ".$input['bdate']." ".$input['edate']);  
				$objPHPExcel->getProperties()->setSubject("Sheet1");  
				$objPHPExcel->getProperties()->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");  
				$objPHPExcel->getProperties()->setKeywords("office 2007 openxml php");  
				$objPHPExcel->getProperties()->setCategory("Test result file");  
				  
				//Set the working sheet
				//$objPHPExcel->createSheet(); //there is a default sheet, no need to set a new one 
				$objPHPExcel->setActiveSheetIndex(0);

				//Set column title 
				$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Date');
				$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Operator');  
				$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Account Name');  
				$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Vehicle');  
				$objPHPExcel->getActiveSheet()->setCellValue('E1', 'PO#');  
				$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Job Ticket');  
				$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Start Time');
				$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Stop Time');
				$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Billable HR');
				$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Status');
				$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Rate');
				$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Total');

				//Set values
				for($i = 2; $i < count($bill['result'])  + 2 ; $i ++){
					$ifschedule = $bill['result'][$i-2]->ifschedule ==1 ? 'S' : 'U';
					$rate = $bill['result'][$i-2]->truck_type == 4 ? $bill['result'][$i-2]->traffic_control_price :  $bill['result'][$i-2]->unit_price;
					$schedule_date = isset($bill['result'][$i-2]->schedule_date) && !empty($bill['result'][$i-2]->schedule_date) ? date('m/d/Y', strtotime($bill['result'][$i-2]->schedule_date)) : '-';

					$objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $schedule_date);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $bill['result'][$i-2]->employee_name);  
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$i, $bill['result'][$i-2]->company_name);  
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$i, $bill['result'][$i-2]->truck_number);  
					$objPHPExcel->getActiveSheet()->setCellValue('E'.$i, (string)$bill['result'][$i-2]->pon);  
					$objPHPExcel->getActiveSheet()->setCellValue('F'.$i, $bill['result'][$i-2]->ticket_id);  
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$i, date('h:i a', strtotime($bill['result'][$i-2]->btime)));
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$i, date('h:i a', strtotime($bill['result'][$i-2]->etime)));
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, $bill['result'][$i-2]->billing_hour);
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, $ifschedule);
					//$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, (string)number_format($bill['result'][$i-2]->unit_price, 2));
					//$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, (string)number_format($bill['result'][$i-2]->billing_hour * $bill['result'][$i-2]->unit_price, 2));
					$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, $rate, 2);
					$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, $bill['result'][$i-2]->billing_hour * $rate, 2);

					$objPHPExcel->getActiveSheet()->getStyle('K'.$i.':L'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
					
				}

				//Save Excel 2007 file
				$contractName = isset($input['contract_id']) && !empty($input['contract_id']) ? ' '.$bill['result'][0]->company_name : '';
				$ifScheduleCur = $bill['result'][0]->ifschedule == 1 ? 'Scheduled' : 'Unscheduled';
				$ifSchedule = isset($input['ifschedule']) && !empty($input['ifschedule']) ? ' ('.$ifScheduleCur.')' : '';
					

				$filename = "Billing Sheet ".str_replace('/','',$input['bdate'])."-".str_replace('/','',$input['edate']).$contractName.$ifSchedule.".xlsx";
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheet.sheet');
				header('Content-Disposition: attachment;filename="'.$filename.'"');
				header('Cache-Control: max-age=0');
				//header("Content-Transfer-Encoding: UTF-8 ");
				
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				//$objWriter->save(str_replace('.php', '.xlsx', __FILE__));
				$objWriter->save('php://output');exit;
			}
		
		}
	}

	private function txdotTypeConvert($txdotType = 1, $iftype = 0){
		switch ($txdotType) {
			case '1':
				$txdotTypeCha = 'I';
				break;
			case '2':
				$txdotTypeCha = 'II';
				break;
			case '3':
				$txdotTypeCha = 'III';
				break;
			case '4':
				$txdotTypeCha = 'VI';
				break;
			case '5':
				$txdotTypeCha = 'V';
				break;
			case '6':
				$txdotTypeCha = 'VI';
				break;
			case '7':
				$txdotTypeCha = 'VII';
				break;
			case '8':
				$txdotTypeCha = 'I&II';
				break;
			default:
				$txdotTypeCha = 'I';
				break;
		}

		return $iftype ? 'Type '.$txdotTypeCha : $txdotTypeCha;
	}

	public function test(){
		echo 'test';
		echo '<pre>';
		$this->schedule_model->test();
	}
}
