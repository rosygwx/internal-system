<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description: Contract
 * Author: Rosy
 * Last Update: 03/30/2018
 */

class Truck extends CI_Controller {

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
		$this->load->model('truck_model');
		$this->load->model('manager_plus_model');
		$this->load->model('tool_model');

		$this->truck_lists_fields = 'SQL_CALC_FOUND_ROWS ';

		$this->current = 'truck';//nevigation
		$this->currentRep = 'report';//nevigation
	}

	/**
	* Purpose: Truck list
	* @param {array} array  
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
		

		$result = $this->truck_model->lists($this->truck_lists_fields, $where, $like, $json = true, $orderby = '', $page, $pagesize);
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
	* Purpose: Truck add page
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
	* Purpose: Truck edit page
	* @param {array} array - contract_id and etc.
	* @return: {json}
	*/
	public function update(){
		$input = $this->input->get();
		$contract_id = isset($input['id']) && !empty($input['id']) ? $input['id'] : '';
		if($contract_id){
			$result = $this->truck_model->lists($this->contract_update_fields, '', '' , $json = true, $orderby = '', 1, 1);
			$data['contract'] = $result['result'];
			$data['current'] = $this->current;
			$this->load->view('contract/update',$data);
		}else{

		}
	}

	/**
	* Purpose: Truck report from ManagerPlus
	* @param {array} array - contract_id and etc.
	* @return: {json}
	*/
	public function reportExpense(){
		$input = $this->input->get();
		if(isset($input['bdate']) & isset($input['edate'])){
			$bdate = !empty($input['bdate']) ? date('Y-m-d', strtotime($input['bdate'])) : '2015-12-27';
			$edate = !empty($input['edate']) ? date('Y-m-d', strtotime($input['edate'])) : date('Y-m-d');

			$reportRaw = $this->manager_plus_model->AssetReport($bdate, $edate);
			//var_dump($reportRaw);

			$report = [];
			$sum = ['cost_part'=>0, 'cost_labor'=>0, 'cost_purchase'=>0, 'cost_fuel'=>0, 'total'=>0];
			foreach($reportRaw as $rep){
				$reportCur['asset_id'] = $rep->{'Asset ID'};
				$reportCur['asset_name'] = $rep->{'Asset Code'};
				$reportCur['cost_part'] = number_format($rep->{'Part Cost'}, 2);
				$reportCur['cost_labor'] = number_format($rep->{'Labor Cost'}, 2);
				$reportCur['cost_purchase'] = number_format($rep->{'Purchase Cost'}, 2);
				$reportCur['cost_fuel'] = number_format($rep->{'Fuel Cost'}, 2);
				$reportCur['sub_sum'] = number_format($reportCur['cost_part'] + $reportCur['cost_labor'] + $reportCur['cost_purchase'] + $reportCur['cost_fuel'], 2);
				$report[] = $reportCur;

				$sum['cost_part'] += $reportCur['cost_part'];
				$sum['cost_labor'] += $reportCur['cost_labor'];
				$sum['cost_purchase'] += $reportCur['cost_purchase'];
				$sum['cost_fuel'] += $reportCur['cost_fuel'];
				$sum['total'] += $reportCur['sub_sum'];
			}

			$search['bdate'] = date('m/d/Y', strtotime($bdate));
			$search['edate'] = date('m/d/Y', strtotime($edate));

			$data['search'] = $search;
			$data['report'] = $report;
			$data['sum'] = $sum;
		}
		
		$data['current'] = $this->currentRep ;
		//var_dump($data['report']);
		$this->load->view('truck/reportExpense', $data);
	}


	public function test(){
		echo 'test';
		echo '<pre>';
		$this->contract_model->test();
	}
}

