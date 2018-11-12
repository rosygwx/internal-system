<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description: Contract
 * Author: Rosy
 * Last Update: 03/30/2018
 */

class Report extends CI_Controller {

	public function __construct(){
		parent::__construct();

		/*
		//login
		$this->load->library('session');
		$this->load->model('tool_model');
		$user = $this->session->userdata('user');
		if(!$user){
			$this->tool_model->redirect(BASE_URL().'login/?fromurl='.str_replace('/ci/', '', $_SERVER["REQUEST_URI"]),'Please login.');
		}
		*/
		$this->load->model('contract_model');
		$this->load->model('tool_model');

		$this->billComMon_fields = 'SQL_CALC_FOUND_ROWS worklog.wl_id, worklog.schedule_id, worklog.ticket_id, concat(employee.nick_name, " ", employee.last_name) employee_name, truck.number truck_number, truck.type truck_type, schedule.schedule_date, schedule.btime, schedule.travel_hour, schedule.location, schedule.comment, schedule.ifschedule, schedule.traffic_control_price, schedule.disposal_price, contract.poc, contract.pon, company.company_name';

		$this->contract_update_fields = '';

		$this->current = 'report';//nevigation
	}

	/**
	* Purpose: Export monthly commercial billing sheet to excel
	* @param {array} array  StartDate, EndDate
	* @return: {array}  csv
	*/
	public function billComMon(){
		$input = $this->input->post();
		if(!$input){
			//$input = $this->input->get();
			$this->load->view(BASE_URL().'schedule/billComMon');
		}else{
			$whereBill['schedule.date >='] = isset($input['bDate']) && !empty($input['bDate']) ? $input['bDate'] : date('Y-m-d', strtotime("-1 week last Wednesday"));
			$whereBill['schedule.date <='] = isset($input['eDate']) && !empty($input['eDate']) ? $input['eDate'] : date('Y-m-d', strtotime(" last Tuesday"));

			$worklog = $this->worklog_model->lists($this->billComMon_fields , $whereBill, $like = '', $json = true, $orderby = array('schedule_date'=>'asc', 'worklog.ticket_id'=>'asc'), 1, 1000);
			
			/*$data = file_get_contents($path);
        	force_download($filename, $data);*/
		}
		
	}

	/**
	* Purpose: Contract list
	* @param {array} array  company, status, date and etc
	* @return: {array}  result, search, page, current and etc
	*/
	public function lists()
	{
		$input = $this->input->get();
		
		isset($input['company']) && !empty($input['company']) ? $like[] = ['name'=>'client.company_name','value'=>$input['company'] ] : '';
		isset($input['status']) && $input['status'] != '' ? $where['contract.status'] = $input['status']:'';
		isset($input['bdate']) && $input['bdate'] != '' ? $where['contract.sign_date >='] = $input['bdate']:'';
		isset($input['edate']) && $input['edate'] != '' ? $where['contract.sign_date <='] = $input['edate']:'';
		$page = isset($input['page']) && $input['page'] != '' ? $input['page'] : 1 ;
		$pagesize = 15;
		

		$result = $this->contract_model->lists($this->contract_lists_fields, $where, $like, $json = true, $orderby = '', $page, $pagesize);
		//echo '<pre>';var_dump($result);exit;
		
		
		//search scope
		$search['company'] = isset($input['company']) && !empty($input['company']) ? $input['company'] : '';
		$search['status'] = isset($input['status']) && $input['status'] != '' ? $input['status']:'';
		$search['bdate'] = isset($input['bdate']) && $input['bdate'] != '' ? $input['bdate']:'';
		$search['edate'] = isset($input['edate']) && $input['edate'] != '' ? $input['edate']:'';
		
		//pagination
		$total = $result['total'];
		$url = BASE_URL().'contract/lists';
		$pageinfo = $this->tool_model->makepage($page,$pagesize,$total,$url,$search);
		

		$data['contract'] = $result['result'];
		$data['search'] = $search;
		$data['page'] = $pageinfo;
		$data['current'] = $this->current;
		//echo '<pre>';
		//var_dump($search);

		$this->load->view('contract/lists',$data);
	}

	/**
	* Purpose: Contract add page
	* @param null
	* @return: {json}
	*/
	public function add(){
		$input = $this->input->post();
		$company_id = isset($input['company_id']) && !empty($input['company_id']) ? $input['company_id'] : '';
		if(!$company_id){
			
			$data['current'] = $this->current;
			$this->load->view('contract/add',$data);
		}else{

		}
	}

	/**
	* Purpose: Contract edit page
	* @param {array} array - contract_id and etc.
	* @return: {json}
	*/
	public function update(){
		$input = $this->input->get();
		$contract_id = isset($input['id']) && !empty($input['id']) ? $input['id'] : '';
		if($contract_id){
			$result = $this->contract_model->lists($this->contract_update_fields, '', '' , $json = true, $orderby = '', 1, 1);
			$data['contract'] = $result['result'];
			$data['current'] = $this->current;
			$this->load->view('contract/update',$data);
		}else{

		}
	}

	public function test(){
		echo 'test';
		echo '<pre>';
		$this->contract_model->test();
	}
}
