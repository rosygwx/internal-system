<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description: Contract
 * Author: Rosy
 * Last Update: 03/30/2018
 */

class Task extends CI_Controller {

	public function __construct(){
		parent::__construct();

		
		//login
		$this->load->library('session');
		$this->load->model('tool_model');
		$user = $this->session->userdata('user');
		if(!$user){
			$this->tool_model->redirect(BASE_URL().'login','Please login.');
		}
		
		$this->load->model('task_model');
		$this->load->model('tool_model');

		$this->task_lists_fields = 'SQL_CALC_FOUND_ROWS task.task_id, task.tcat_id, task.hwy_id, task.section_from, task.section_to, task.fre_1, task.fre_2, task.fre_3, task.unit_price, task.unit, task.mile, task.cycle, company.company_name, company.type, contract.contract_id_pannell, contract.pon, contract.poc, task_cat.tcat_name, task_cat.category';

		$this->task_update_fields = '';

		$this->allowed_col_num = 17;

		$this->current = 'task';//nevigation
	}

	/**
	* Purpose: Task list
	* @param {array} array  company, status, date and etc
	* @return: {array}  result, search, page, current and etc
	*/
	public function lists()
	{
		$input = $this->input->get();
		//var_dump($input);
		isset($input['contract_id']) && $input['contract_id'] != '' ? $where['contract.contract_id'] = $input['contract_id']:'';
		isset($input['contract_name']) && !empty($input['contract_name']) ? $like[] = ['name'=>'contract.contract_id_pannell','value'=>$input['contract_name'] ] : '';
		isset($input['hwy_id']) && !empty($input['hwy_id']) ? $like[] = ['name'=>'task.hwy_id','value'=>$input['hwy_id'] ] : '';
		isset($input['category']) && $input['category'] != '' ? $where['task_cat.category'] = $input['category']:'';
		$where['company.type'] = isset($input['company_type']) && $input['company_type'] != '' ? $input['company_type']:1;//1:txdot, 2:hourly work
		isset($input['type']) && $input['type'] != '' ? $where['task_cat.type'] = $input['type']:'';
		isset($input['status']) && $input['status'] != '' ? $where['contract.status'] = $input['status']:'';

		isset($input['orderby']) && $input['orderby'] != '' ? $orderby = $input['orderby']: 1 ;
		$page = isset($input['page']) && $input['page'] != '' ? $input['page'] : 1 ;
		$pagesize = 60;
		
		switch ($orderby) {
			case '1':
				$orderby = '';
				break;
			case '2':
				$orderby = ['task.hwy_id'=>'asc' , 'task_cat.tcat_name'=>'asc'];
				break;
			case '3':
				$orderby = ['task_cat.tcat_name'=>'asc'];
				break;
			default:
				$orderby = '';
				break;
		}

		$result = $this->task_model->lists($this->task_lists_fields, $where, $like, $json = true, $orderby, $page, $pagesize);
		//echo '<pre>';var_dump($result);exit;
		foreach($result['result'] as $res){
			//format frequency
			switch ($res->fre_3) {
				case '1':
					$res->fre_3 = 'week';
					break;
				case '2':
					$res->fre_3 = 'month';
					break;
				case '3':
					$res->fre_3 = 'year';
					break;
				default:
					$res->fre_3 = 'week';
					break;
			}

			if($res->fre_2 == 1){
				$res->fre_2 = '';
			}elseif($res->fre_2 > 1){
				$res->fre_2 .= ' ';
				$res->fre_3 .= 's';
			}

			$time = $res->fre_1 >= 2 ? ' times/' : ' time/';
			
			$res->fre = $res->fre_1.$time.$res->fre_2.$res->fre_3;

			//format unit price
			$res->unit_price = '$'.$res->unit_price.'/'.$res->unit;
		}
		
		//search scope
		$search['contract_id'] = isset($input['contract_id']) && !empty($input['contract_id']) ? $input['contract_id'] : '';
		$search['company'] = isset($input['company']) && !empty($input['company']) ? $input['company'] : '';
		$search['hwy_id'] = isset($input['hwy_id']) && !empty($input['hwy_id']) ? $input['hwy_id'] : '';
		$search['category'] = isset($input['category']) && !empty($input['category']) ? $input['category'] : '';
		$search['company_type'] = isset($input['company_type']) && !empty($input['company_type']) ? $input['company_type'] : '';
		$search['type'] = isset($input['type']) && !empty($input['type']) ? $input['type'] : '';
		$search['status'] = isset($input['status']) && $input['status'] != '' ? $input['status']:'';

		$search['orderby'] = isset($input['orderby']) && $input['orderby'] != '' ? $input['orderby']: 1 ;
		$search['bdate'] = isset($input['bdate']) && $input['bdate'] != '' ? $input['bdate']:'';
		$search['edate'] = isset($input['edate']) && $input['edate'] != '' ? $input['edate']:'';
		
		//pagination
		$total = $result['total'];
		$url = BASE_URL().'task/lists';
		$pageinfo = $this->tool_model->makepage($page,$pagesize,$total,$url,$search);
		

		$data['task'] = $result['result'];
		$data['company_type'] = $where['company.type'];
		$data['search'] = $search;
		$data['page'] = $pageinfo;
		$data['current'] = $this->current;
		//echo '<pre>';
		//var_dump($data);

		$this->load->view('task/lists',$data);
	}

	/**
	* Purpose: Task add page
	* @param null
	* @return: {json}
	*/
	public function add(){
		$input = $this->input->post();
		$hwy_id = isset($input['hwy_id']) && !empty($input['hwy_id']) ? $input['hwy_id'] : '';
		$company_type = 1;
		if(!$hwy_id && $company_type == 1){
			//task category
			$arr_tc['company_type'] = $company_type;//txdot
			$arr_tc['status'] = 1;
			$arr_tc['isdeleted'] = 0;
			$result_task_cat = $this->task_model->category_lists( $this->task_category_fields , $arr_tc, $like = '', $json = true, $orderby = '', $page = 1, $pagesize = '' );

			//categorized by category when txdot
			if($company_type==1){
				$cat_txdot = array();
				foreach($result_task_cat['result'] as $res){
					$cat_txdot[$res->category][] = ['tcat_id'=>$res->tcat_id , 'tcat_name'=>$res->tcat_name];
				}
			}

			$data['company'] = ['company_id' => $company_id , 'company_name' => $result_company['result'][0]->company_name];
			$data['task_cat'] = $company_type == 1 ? $cat_txdot : $result_task_cat['result'];
			$data['current'] = $this->current;
			//echo '<pre>';
			//var_dump($data['task_cat']);

			$data['current'] = $this->current;

			if($company_type==1){
				$this->load->view('task/add_txdot',$data);
			}else{

			}
		}else{
			var_dump($input);exit;
		}
	}

	/**
	* Purpose: Add bulk task by csv
	* @param null
	* @return: {json}
	*/
	public function add_csv(){
		$input = $this->input->get();
		$contract_id = isset($input['id']) && !empty($input['id']) ? $input['id'] : '';
		if(!$_FILES['csv']['tmp_name'] && $contract_id){
			$this->load->view('task/add_txdot_csv');
		}else{
			
			$res = $this->task_model->loadTaskCSVToSQL($contract_id, CSV_COLUMN_NUMBER);
			
			//$res = $this->task_model->test($_FILES['csv']['tmp_name'],$this->csv_table='task',$this->allowed_col_num);//test
			//$res['code'] = 200;//test
			//$res['start_id'] = 1607;//test
			//$res['complete_id'] = 1649;//test
			if($res['code']==200){
				
				//add schedule with week
				if($res['start_id'] && $res['complete_id']){
					$arr_task_id = range($res['start_id'], $res['complete_id']);
					//$arr_task_id = [1424];//test
					$res_add_week = $this->tool_model->add_week($arr_task_id);
					//exit;
					if($res_add_week){

						$this->tool_model->redirect(BASE_URL().'task/add_csv',$res['added_rows'].' records have been added. '.$res['updated_rows'].' records have been updated. Auto Scheduled.');exit;
					}else{
						$this->tool_model->redirect(BASE_URL().'task/add_csv',$res['added_rows'].' records have been added. '.$res['updated_rows'].' records have been updated. ');exit;
					}
				}else{
					$this->tool_model->redirect(BASE_URL().'task/add_csv',$res['added_rows'].' records have been added. '.$res['updated_rows'].' records have been updated.');exit;
				}
			}else{
				$this->tool_model->redirect(BASE_URL().'task/add_csv','Upload unsuccess. Error code '.$res['code']);exit;
			}
		}
	}

	/**
	* Purpose: Task edit page
	* @param {array} array - task_id and etc.
	* @return: {json}
	*/
	public function update(){
		$input = $this->input->get();
		$task_id = isset($input['id']) && !empty($input['id']) ? $input['id'] : '';
		if($task_id){
			$result = $this->contract_model->lists($this->task_update_fields, '', '' , $json = true, $orderby = '', 1, 1);
			$data['contract'] = $result['result'];
			$data['current'] = $this->current;
			$this->load->view('contract/update',$data);
		}else{

		}
	}

	public function test(){
		//echo 'test';
		//echo '<pre>';
		//$this->task_model->test();

		$arr_task_id = [446,447,450,464,481];//test
		$res_add_week = $this->tool_model->add_week($arr_task_id);
	}
}
