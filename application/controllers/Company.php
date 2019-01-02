<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description: Contract
 * Author: Rosy
 * Last Update: 04/02/2018
 */

class Company extends CI_Controller {

	public function __construct(){
		parent::__construct();

		
		//login
		$this->load->library('session');
		$this->load->model('tool_model');
		$user = $this->session->userdata('user');
		if(!$user){
			//$this->tool_model->redirect(BASE_URL().'login/?fromurl='.str_replace('/ci/', '', $_SERVER["REQUEST_URI"]),'Please login.');
			$this->tool_model->redirect(BASE_URL().'login','Please login.');
		}
		
		$this->load->model('company_model');
		$this->load->model('tool_model');

		

		$this->client_lists_fields = 'company_id, company_name as company, client_name as client, phone, type';
		$this->client_lists_ajax_field = 'company_id as id, company_name as company, client_name as client, phone, type';

		$this->current = 'company';//nevigation
	}

	/**
	* Purpose: Company list
	* @param {array} array  company, status, date and etc
	* @return: {array}  result, search, page, current and etc
	*/
	public function lists()
	{
		$input = $this->input->get();
		
		isset($input['company']) && !empty($input['company']) ? $like[] = ['name'=>'company.company_name','value'=>$input['company'] ] : '';
		isset($input['client']) && !empty($input['client']) ? $like[] = ['name'=>'company.client_name','value'=>$input['client'] ] : '';
		isset($input['status']) && $input['status'] != '' ? $where['contract.status'] = $input['status']:'';
		
		if(isset($input['type'])){
			if(!empty($input['type'])){
				$where['company.type'] = $input['type'];
			}
		}else{
			$where['company.type'] = 1;//default txdot
		}
		
		/*isset($input['bdate']) && $input['bdate'] != '' ? $where['contract.sign_date >='] = $input['bdate']:'';
		isset($input['edate']) && $input['edate'] != '' ? $where['contract.sign_date <='] = $input['edate']:'';*/
		$page = isset($input['page']) && $input['page'] != '' ? $input['page'] : 1 ;
		$pagesize = 15;
		

		$result = $this->company_model->lists($this->client_lists_fields, $where, $like, $json = true, $orderby = '', $page, $pagesize);
		//echo '<pre>';var_dump($result);exit;
		
		
		//search scope
		$search['company'] = isset($input['company']) && !empty($input['company']) ? $input['company'] : '';
		$search['client'] = isset($input['client']) && !empty($input['client']) ? $input['client'] : '';
		$search['status'] = isset($input['status']) && $input['status'] != '' ? $input['status']:'';
		$search['type'] = $where['company.type'];//default txdot
		/*$search['bdate'] = isset($input['bdate']) && $input['bdate'] != '' ? $input['bdate']:'';
		$search['edate'] = isset($input['edate']) && $input['edate'] != '' ? $input['edate']:'';*/
		
		//pagination
		$total = $result['total'];
		$url = BASE_URL().'company/lists';
		$pageinfo = $this->tool_model->makepage($page,$pagesize,$total,$url,$search);
		

		$data['company'] = $result['result'];
		$data['search'] = $search;
		$data['page'] = $pageinfo;
		$data['current'] = $this->current;
		//echo '<pre>';
		//var_dump($data);

		$this->load->view('company/lists',$data);
	}

	/**
	* Purpose: Client list ajax
	* @param {array} array  company, status, date and etc
	* @return: {array}  
	*/
	public function lists_ajax()
	{
		//header('Access-Control-Allow-Origin:*');
		$input = $this->input->get();
		$company_name = isset($input['company']) && !empty($input['company']) ? $input['company'] : '' ;
		$page = 1;
		$pagesize = 10;
		//var_dump($where);
		if($company_name){
			$like[] = ['name'=>company_name, 'value'=>$company_name];
			$result = $this->company_model->lists($this->client_lists_ajax_field, '', $like, $json = true, $orderby = '', $page, $pagesize);
		}else{
			$result = array('result'=>'','total'=>0);
		}

		echo json_encode($result);
	}

	/**
	* Purpose: Company add page
	* @param null
	* @return: {json}
	*/
	public function add(){
		$input = $this->input->post();
		$company_name = isset($input['company_name']) && !empty($input['company_name']) ? $input['company_name'] : '';
		if(!$company_name){
			$data['current'] = $this->current;
			$this->load->view('company/add',$data);
		}else{
			
			$arr['company_name'] = $company_name;
			$arr['client_name'] = isset($input['client_name']) && !empty($input['client_name']) ? $input['client_name'] : '';
			$arr['type'] = isset($input['type']) && !empty($input['type']) ? $input['type'] : 1; //1:TxDOT, 2:commercial
			$arr['phone'] = isset($input['phone']) && !empty($input['phone']) ? $input['phone'] : '' ;
			$arr['email'] = isset($input['email']) && !empty($input['email']) ? $input['email'] : '' ;
			$arr['address'] = isset($input['address']) && !empty($input['address']) ? $input['address'] : '' ;
			$arr['city'] = isset($input['city']) && !empty($input['city']) ? $input['city'] : '' ;
			$arr['state'] = isset($input['state']) && !empty($input['state']) ? $input['state'] : '' ;
			$arr['postcode'] = isset($input['postcode']) && !empty($input['postcode']) ? $input['postcode'] : '' ;
			$arr['addtime'] = date('Y-m-d H:i:s');

			$result = $this->company_model->add($arr);
			
			if($result){
				$this->tool_model->redirect(BASE_URL().'company/lists','Add successful!');exit;
			}else{
				$this->tool_model->redirect(BASE_URL().'company/lists','Oops, please check with IT department.');exit;
			}
		
		}
	}


	public function test(){
		echo 1;
	}

}