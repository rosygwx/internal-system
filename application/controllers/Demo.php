<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description: Contract
 * Author: Rosy
 * Last Update: 03/30/2018
 */

class Contract extends CI_Controller {

	public function __construct(){
		parent::__construct();

		/*
		//login
		$this->load->library('session');
		$this->load->model('tool_model');
		$user = $this->session->userdata('user');
		if(!$user){
			$this->tool_model->redirect(BASE_URL().'login','Please login.');
		}
		*/
		$this->load->model('contract_model');
		$this->load->model('tool_model');

		$this->contract_lists_fields = 'SQL_CALC_FOUND_ROWS client.company_name as company,client.phone as phone,contract.sign_date as signdate,contract.status as status, contract.contract_id_pannell,contract.contract_id';

		$this->contract_update_fields = '';

		$this->current = 'contract';//nevigation
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
