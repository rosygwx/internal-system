<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description: Contract
 * Author: Rosy
 * Last Update: 04/12/2018
 */

class Employee extends CI_Controller {

	public function __construct(){
		parent::__construct();

		
		//login
		$this->load->library('session');
		$this->load->model('tool_model');
		$user = $this->session->userdata('user');
		if(!$user){
			$this->tool_model->redirect(BASE_URL().'login/?fromurl='.str_replace('/ci/', '', $_SERVER["REQUEST_URI"]),'Please login.');
		}
		
		$this->load->model('employee_model');
		$this->load->model('tool_model');

		$this->employee_lists_fields = 'SQL_CALC_FOUND_ROWS employee.employee_id, employee.first_name, employee.last_name, employee.office, employee.group_id, employee.status, employee.bdate';

		$this->employee_update_fields = '';

		$this->current = 'employee';//nevigation
	}

	/**
	* Purpose: Contract list
	* @param {array} array  company, status, date and etc
	* @return: {array}  result, search, page, current and etc
	*/
	public function lists()
	{
		$input = $this->input->get();
		
		isset($input['name']) && !empty($input['name']) ? $like_query[] = '(employee.nick_name like "%'.$input['name'].'%" OR employee.first_name like "%'.$input['name'].'%" OR employee.last_name like "%'.$input['name'].'%" )' : '';
		isset($input['status']) && $input['status'] != '' ? $where['employee.status'] = $input['status']:'';
		
		$page = isset($input['page']) && $input['page'] != '' ? $input['page'] : 1 ;
		$pagesize = 30;
		

		$result = $this->employee_model->lists($this->employee_lists_fields, $where, $like, $json = true, $orderby = ['bdate'=>'desc'], $page, $pagesize, $like_query);
		//echo '<pre>';var_dump($result);exit;
		foreach($result['result'] as $res){
			//format name
			$res->name = $res->first_name.' '.$res->last_name;
			unset($res->first_name,$res->last_name);

			//format office
			switch ($res->office) {
				case 1:
					$res->office = 'Dallas';
					break;

				case 2:
					$res->office = 'Houston';
					break;
				
				default:
					$res->office = 'Mission';
					break;
			}

			//format group
			switch ($res->group_id) {
				case 1:
					$res->group_id = 'Admin';
					break;

				case 2:
					$res->group_id = 'Admin';
					break;

				case 3:
					$res->group_id = 'Office Staff';
					break;

				case 4:
					$res->group_id = 'Driver';
					break;
				
				default:
					$res->group_id = 'Mechanic';
					break;
			}


		}
		//var_dump($result);
		//search scope
		$search['name'] = isset($input['name']) && !empty($input['name']) ? $input['name'] : '';
		$search['status'] = isset($input['status']) && $input['status'] != '' ? $input['status']:'';
		
		
		//pagination
		$total = $result['total'];
		$url = BASE_URL().'employee/lists';
		$pageinfo = $this->tool_model->makepage($page,$pagesize,$total,$url,$search);
		

		$data['employee'] = $result['result'];
		$data['search'] = $search;
		$data['page'] = $pageinfo;
		$data['current'] = $this->current;
		//echo '<pre>';
		//var_dump($search);

		$this->load->view('employee/lists',$data);
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
