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

		
		//login
		$this->load->library('session');
		$this->load->model('tool_model');
		$user = $this->session->userdata('user');
		if(!$user){
			$this->tool_model->redirect(BASE_URL().'login/?fromurl='.str_replace('/ci/', '', $_SERVER["REQUEST_URI"]),'Please login.');
		}
		//var_dump($this->session->userdata('user'));
		$this->load->model('contract_model');
		$this->load->model('company_model');
		$this->load->model('task_model');
		$this->load->model('tool_model');

		$this->contract_lists_fields_txdot = 'SQL_CALC_FOUND_ROWS company.company_name as company,company.phone as phone,contract.sign_date as signdate,contract.status as status, contract.contract_id_pannell,contract.contract_id, contract.addtime as addtime, contract.bdate as bdate, contract.quote_real, a.price_per_mile';

		$this->contract_lists_fields_hour = 'SQL_CALC_FOUND_ROWS company.company_name as company,company.phone as phone,contract.sign_date as signdate,contract.status as status, contract.contract_id_pannell,contract.contract_id, contract.addtime as addtime, contract.bdate as bdate, contract.quote_real, avg(task.unit_price) unit_price, task.unit_price_2, task.traffic_control_price, task.disposal_price, contract.travel_hour, contract.cancel_hour';

		$this->contract_update_fields = 'company.company_name, contract.contract_id, contract.contract_id_pannell, contract.poc, contract.pon, contract.office, task.unit_price, task.unit_price_2, task.traffic_control_price, task.disposal_price, task.task_id, contract.travel_hour , contract.cancel_hour';
		$this->company_fields = 'company_id, company_name, type';
		$this->task_category_fields = 'tcat_id, category, tcat_name';
		$this->bill_fields = 'schedule.schedule_id, schedule.comment, task.tcat_id, task.tract, task.hwy_id, task.section_from, task.section_to, task.unit_price, task.mile,  task.cycle, contract.contract_id_pannell, task_cat.category, task_cat.tcat_name type, task_cat.index_centerline , company.type company_type';

		$this->current = 'contract';//nevigation
		$this->currentRev = 'revenue';//nevigation
		$this->currentRep = 'report';//nevigation

	}

	/**
	* Purpose: Contract list
	* @param {array} array  company, status, date and etc
	* @return: {array}  result, search, page, current and etc
	*/
	public function lists()
	{
		$input = $this->input->get();
		
		//$this->output->enable_profiler(TRUE);

		isset($input['company']) && !empty($input['company']) ? $like[] = ['name'=>'company.company_name','value'=>$input['company'] ] : '';
		isset($input['status']) && $input['status'] != '' ? $where['contract.status'] = $input['status']:'';
		$where['company.type'] = isset($input['company_type']) && $input['company_type'] != '' ? $input['company_type'] : 1 ;//1:txdot, 2:commercial
		isset($input['category']) && $input['category'] != '' ? $where['task_cat.category'] = $input['category']:'';
		isset($input['type']) && $input['type'] != '' ? $where['company.type'] = $input['type'] : '' ; 
		$order = isset($input['orderby']) && $input['orderby'] != '' ? $input['orderby'] : 1 ;

		$page = isset($input['page']) && $input['page'] != '' ? $input['page'] : 1 ;
		$pagesize = 20;
		
		$orderby = [];
		if($order ==1 && $where['company.type'] == 1){
			$orderby = array('a.price_per_mile' => "desc", 'contract.contract_id' => 'asc');
		}elseif($where['company.type'] == 2){
			$orderby = array('company.company_name' => "asc", 'contract.contract_id_pannell' => 'asc');
		}
		//var_dump($where);
		if($where['company.type'] == 1){
			$fields = $this->contract_lists_fields_txdot;
		}else{
			$fields = $this->contract_lists_fields_hour;
		}
		
		$result = $this->contract_model->lists($fields, $where, $like, $json = true, $orderby , $page, $pagesize);
		//echo '<pre>';var_dump($result);exit;

		
		//search scope
		$search['company'] = isset($input['company']) && !empty($input['company']) ? $input['company'] : '';
		$search['hwy_id'] = isset($input['hwy_id']) && !empty($input['hwy_id']) ? $input['hwy_id'] : '';
		$search['status'] = isset($input['status']) && $input['status'] != '' ? $input['status']:'';
		$search['company_type'] = isset($input['company_type']) && $input['company_type'] != '' ? $input['company_type']:1;
		$search['category'] = isset($input['category']) && $input['category'] != '' ? $input['category']:'';
		$search['type'] = isset($input['type']) && $input['type'] != '' ? $input['type']:'';
		$search['bdate'] = isset($input['bdate']) && $input['bdate'] != '' ? $input['bdate']:'';
		$search['edate'] = isset($input['edate']) && $input['edate'] != '' ? $input['edate']:'';
		
		//pagination
		$total = $result['total'];
		$url = BASE_URL().'contract/lists';
		$pageinfo = $this->tool_model->makepage($page,$pagesize,$total,$url,$search);
		

		$data['contract'] = $result['result'];
		$data['company_type'] = $where['company_type'];
		$data['search'] = $search;
		$data['page'] = $pageinfo;
		$data['current'] = $this->current;
		//echo '<pre>';
		//var_dump($result);

		$this->load->view('contract/lists',$data);
	}

	/**
	* Purpose: Contract add page (txdot)
	* @param 
	* @return: 
	*/
	public function add(){
		$input = $this->input->post();
		$contract_id_pannell = isset($input['id_pannell']) && !empty($input['id_pannell']) ? $input['id_pannell'] : '';
		if(!$contract_id_pannell){
			$input = $this->input->get();

			//company info
			$whereCom['type'] = 1;
			$result_company = $this->company_model->lists( $this->company_fields , $whereCom, $like = '', $json = true, $orderby = ['company.company_name'=>'asc'], $page = 1, $pagesize = 500 );
			foreach($result_company['result'] as $res){
				$company[$res->company_id] = $res->company_name;
			}

			$data['company'] = $company;
			$data['current'] = $this->current;
			
			$this->load->view('contract/add_txdot',$data);
			
		}else{
			echo '<pre>';
			//var_dump($input,$_FILES['csv']['tmp_name']);exit;

			$ifNewCom = isset($input['newCom']) && !empty($input['newCom']) ? $input['newCom'] : 0;

			$arrContract['contract_id_pannell'] = isset($input['id_pannell']) && !empty($input['id_pannell']) ? $input['id_pannell'] : '';
			$arrContract['contract_id_ori'] = isset($input['id_ori']) && !empty($input['id_ori']) ? $input['id_ori'] : '';
			$arrContract['sign_date'] = isset($input['sign_date']) && !empty($input['sign_date']) ? date('Y-m-d',strtotime($input['sign_date'])) : '';
			$arrContract['bdate'] = isset($input['bdate']) && !empty($input['bdate']) ? date('Y-m-d',strtotime($input['bdate'])) : '';
			$arrContract['quote_real'] = isset($input['quote_real']) && !empty($input['quote_real']) ? $input['quote_real'] : '';
			$arrContract['year'] = isset($input['year']) && !empty($input['year']) ? $input['year'] : '';
			$arrContract['office'] = isset($input['office']) && !empty($input['office']) ? $input['office'] : '';
			$arrContract['status'] = 1;
			$arrContract['addtime'] = date('Y-m-d H:i:s');

			$backurl = isset($input['backurl']) && !empty($input['backurl']) ? $input['backurl'] : BASE_URL.'contract/lists';

			if($ifNewCom == 1){//a new company
				//add company attributes
				$arrCompany['company_name'] = isset($input['company_name']) && !empty($input['company_name']) ? $input['company_name'] : '';
				$arrCompany['type'] = 1;//txdot
				$arrCompany['addtime'] = $arrContract['addtime'] = $arrTask['addtime'] = $arrSchedule['addtime'] = date('Y-m-d H:i:s');

				//var_dump($arrCompany);exit;
				//add company
				//$resCompany = $this->company_model->add($arrCompany);
				$resCompany = 1;//test


				if(!$resCompany){
					//fail to insert company
					$this->tool_model->redirect(BASE_URL().'contract/add','Oops, something is missing. Ask technician. Error code #3151401');exit;
				}else{
					$arrContract['company_id'] = $resCompany;
				}

			}else{//not a new company
				$arrContract['company_id'] = isset($input['company_id']) && !empty($input['company_id']) ? $input['company_id'] : '';
				
			}
			//var_dump($arrContract);exit;
			//add contract
			//$res_ins_ctr = $this->contract_model->add($arrContract);
			$res_ins_ctr = 64;//test

			if($res_ins_ctr){
				
				if($_FILES['csv']['tmp_name']){
					$res_ins_task_sch = $this->task_model->loadTaskCSVToSQL($res_ins_ctr, CSV_COLUMN_NUMBER);
					//var_dump($res_ins_task_sch);exit;
					
					if($res_ins_task_sch['code']==200){
						
						$this->tool_model->redirect($backurl,'Congratulations! New contract has been added.');exit;
						
					}else{
						//fail to insert task and schedule
						$this->tool_model->redirect(BASE_URL().'contract/add', 'Oops, something is missing. <br>Error code #3151404.'.$res_ins_task_sch['code'].' '.$res_ins_task_sch['msg']);exit;
					}
					

					
				}else{
					//fail to receive csv
					$this->tool_model->redirect(BASE_URL().'contract/add', 'Oops, something is missing. Error code #3151403');exit;
				}
			}else{
				//fail to insert contract
				$this->tool_model->redirect(BASE_URL().'contract/add', 'Oops, something is missing. Error code #3151402');exit;
			}
		}
	}


	/**
	* Purpose: Contract add page (Commercial)
	* @param 
	* @return: {json}
	*/
	public function addCom(){
		$input = $this->input->post();
		
		if(!$input){
			$input = $this->input->get();

			//company
			$whereCom['company.type'] = 2;//commercial
			$resCom = $this->company_model->lists($this->company_fields , $whereCom, $like = '', $json = true, $orderby = array('company.company_name'=>'asc'), 1, 500);
			foreach($resCom['result'] as $com){
				$company[$com->company_id] = $com->company_name;
			}

			$data['company'] = $company;
			$data['current'] = $this->current;
			//var_dump($data['task_cat']);
			
			$this->load->view('contract/add_com',$data);
			
		
		}else{
			//echo '<pre>';
			//var_dump($input);exit;

			$backurl = isset($input['backurl']) && !empty($input['backurl']) ? $input['backurl'] : BASE_URL().'contract/addCom';

			//add contract attributes
			$arrContract['poc'] = isset($input['poc']) && !empty($input['poc']) ? $input['poc'] : '';
			$arrContract['pon'] = isset($input['pon']) && !empty($input['pon']) ? $input['pon'] : '';
			$arrContract['travel_hour'] = ($input['iftravelhour'] == 1 && isset($input['travel_hour'])) && !empty($input['travel_hour']) ? $input['travel_hour'] : 0;
			$arrContract['cancel_hour'] = ($input['ifcancelhour'] == 1 && isset($input['cancel_hour'])) && !empty($input['cancel_hour']) ? $input['cancel_hour'] : 0;

			$arrContract['office'] = isset($input['office']) && !empty($input['office']) ? $input['office'] : 1;
			$arrContract['status'] = 1;


			//add task attributes
			$input['ifschedule'] == 1  && isset($input['schedule_price']) && !empty($input['schedule_price']) ? $arrTask['unit_price'] = $input['schedule_price'] : '';
			$input['ifunschedule'] == 1  && isset($input['unschedule_price']) && !empty($input['unschedule_price']) ? $arrTask['unit_price_2'] = $input['unschedule_price'] : '';
			$input['iftraffic'] == 1  && isset($input['traffic_control_price']) && !empty($input['traffic_control_price']) ? $arrTask['traffic_control_price'] = $input['traffic_control_price'] : '';
			$input['ifdisposal'] == 1  && isset($input['disposal_price']) && !empty($input['disposal_price']) ? $arrTask['disposal_price'] = $input['disposal_price'] : '';
			
			$arrTask['tcat_id'] = 14;
			$arrTask['unit'] = 3;//charge per hour
			$arrTask['ifpostpone'] = 0;
			$arrTask['ifweek'] = 0;
			//var_dump($arrTask, $arrContract);exit;

			$ifNewCom = isset($input['newCom']) && !empty($input['newCom']) ? $input['newCom'] : 1;

			if($ifNewCom == 1){//a new company
				//add company attributes
				$arrCompany['company_name'] = isset($input['company_name']) && !empty($input['company_name']) ? $input['company_name'] : '';
				$arrCompany['type'] = $arrContract['type'] = isset($input['type']) && !empty($input['type']) ? $input['type'] : '';
				$arrCompany['addtime'] = $arrContract['addtime'] = $arrTask['addtime'] =  date('Y-m-d H:i:s');

				//add company
				$resCompany = $this->company_model->add($arrCompany);

				if(!$resCompany){
					$this->tool_model->redirect($backurl,'Oops, something is missing. Ask technician. Error code #3151401');exit;
				}else{
					$arrContract['company_id'] = $resCompany;
					$arrContract['contract_id_pannell'] =  isset($input['poc']) && !empty($input['poc']) ? $input['company_name'].' - '.$input['poc'] : $input['company_name'];
				}

			}else{//not a new company
				$arrContract['company_id'] = isset($input['company_id']) && !empty($input['company_id']) ? $input['company_id'] : '';

				$arrContract['contract_id_pannell'] =  isset($input['poc']) && !empty($input['poc']) ? $input['company_name_old'].' - '.$input['poc'] : $input['company_name_old'];
				
			}

			
			//echo '<pre>';var_dump($input,$arrCompany,$arrContract, $arrTask);exit;
			
			//add contract
			$resContract = $this->contract_model->add($arrContract, 2);

			if(!$resContract){
				$this->tool_model->redirect($backurl,'Oops, something is missing. Ask technician. Error code #3151402');exit;
			}else{
				$arrTask['contract_id'] = $resContract;
				//add task
				$resTask = $this->task_model->add($arrTask);

				if(!$resTask){
					$this->tool_model->redirect($backurl,'Oops, something is missing. Ask technician. Error code #3151403');exit;
				}else{
					$this->tool_model->redirect($backurl,'Add successful!');exit;
				}
			}		
		}
	}

	/**
	* Purpose: Contract edit page
	* @param {array} array - contract_id and etc.
	* @return: {json}
	*/
	public function updateCom(){
		$input = $this->input->get();
		$contract_id = isset($input['id']) && !empty($input['id']) ? $input['id'] : '';
		if($contract_id){
			$backurl = isset($input['backurl']) && !empty($input['backurl']) ? $input['backurl'] : BASE_URL().'contract/lists/?company_type=2';
			$where['contract.contract_id'] = $contract_id;
			$result = $this->contract_model->lists($this->contract_update_fields, $where, '' , $json = true, $orderby = '', 1, 1);
			if(!$result['result']){
				$this->tool_model->redirect($backurl,'Oops, something is missing. Ask technician. Error code #3151401');exit;
			}else{
				$contract = $result['result'][0];
				//var_dump($contract);
				$contract->unit_price = $contract->unit_price != 0.00 || $contract->unit_price == null ? $contract->unit_price :  null ;
				$contract->unit_price_2 = $contract->unit_price_2 != 0.00 || $contract->unit_price_2 == null ? $contract->unit_price_2 :  null ;
				$contract->traffic_control_price = $contract->traffic_control_price != 0.00 || $contract->traffic_control_price == null ? $contract->traffic_control_price :  null ;
				$contract->disposal_price = $contract->disposal_price != 0.00 || $contract->disposal_price == null ? $contract->disposal_price :  null ;
				$contract->travel_hour = $contract->travel_hour != 0 || $contract->travel_hour == null ? $contract->travel_hour :  null ;
				$contract->cancel_hour = $contract->cancel_hour != 0 || $contract->cancel_hour == null ? $contract->cancel_hour :  null ;
				$data['contract'] = $contract;
				$data['current'] = $this->current;
				//var_dump($contract);
				$this->load->view('contract/updateCom',$data);
			}
		}else{
			$input = $this->input->post();

			$contract_id = isset($input['contract_id']) && !empty($input['contract_id']) ? $input['contract_id'] : '';
			$task_id = isset($input['task_id']) && !empty($input['task_id']) ? $input['task_id'] : '';
			$backurl = isset($input['backurl']) && !empty($input['backurl']) ? $input['backurl'] : BASE_URL().'contract/lists/?company_type=2';

			if(!$contract_id || !$task_id){
				$this->tool_model->redirect($backurl,'Oops, something is missing. Ask technician. Error code #3151401');exit;
			}else{
				$whereCon['contract_id'] = $contract_id;
				$company_name = isset($input['company_name']) && !empty($input['company_name']) ? $input['company_name'] : '';
				$updateCon['poc'] = isset($input['poc']) && !empty($input['poc']) ? $input['poc'] : '';
				$updateCon['contract_id_pannell'] = $company_name.' - '.$updateCon['poc'];
				$updateCon['pon'] = isset($input['pon']) && !empty($input['pon']) ? $input['pon'] : '';
				$updateCon['office'] = isset($input['office']) && !empty($input['office']) ? (int)$input['office'] : '';
				
				$updateCon['travel_hour'] = ($input['iftravelhour'] == 1 && isset($input['travel_hour'])) && !empty($input['travel_hour']) ? $input['travel_hour'] : 0;
				$updateCon['cancel_hour'] = ($input['ifcancelhour'] == 1 && isset($input['cancel_hour'])) && !empty($input['cancel_hour']) ? $input['cancel_hour'] : 0;
				//var_dump($updateCon);exit;
				$resUpdateCon = $this->contract_model->update($updateCon, $whereCon, $type = 2);

				if(!$resUpdateCon){
					$this->tool_model->redirect($backurl,'Oops, something is missing. Ask technician. Error code #3151402');exit;
				}else{
					$whereTask['task_id'] = $task_id;
					$updateTask['unit_price'] = $input['ifschedule'] == 1  && isset($input['schedule_price']) && !empty($input['schedule_price']) ? $input['schedule_price'] : '0.00';//can't set to null since model is using update(table,value,where)
					$updateTask['unit_price_2'] = $input['ifunschedule'] == 1  && isset($input['unschedule_price']) && !empty($input['unschedule_price']) ? $input['unschedule_price'] : '0.00';
					$updateTask['traffic_control_price'] = $input['iftraffic'] == 1  && isset($input['traffic_control_price']) && !empty($input['traffic_control_price']) ? $input['traffic_control_price'] : '0.00';
					$updateTask['disposal_price'] = $input['ifdisposal'] == 1  && isset($input['disposal_price']) && !empty($input['disposal_price']) ? $input['disposal_price'] : '0.00';

					//var_dump($updateTask);exit;
					if(!$updateTask){
						$this->tool_model->redirect($backurl,'Update successful!');exit;
					}else{
						
						$resUpdateTask = $this->task_model->update($updateTask, $whereTask);

						if(!$resUpdateTask){
							$this->tool_model->redirect($backurl,'Oops, something is missing. Ask technician. Error code #3151404');exit;
						}else{
							$this->tool_model->redirect($backurl,'Update successful!');exit;
						}
					}
					
				}
			}
			
		}
	}

	/**
	* Purpose: Revenue page based on contract
	* @param {array} array - contract_id and etc.
	* @return: {json}
	*/
	public function revenue(){
		$input = $this->input->get();
		$company_type = isset($input['company_type']) && !empty($input['company_type']) ? $input['company_type'] : 1;//1:txdot, 2:commercial


		if($company_type ==1 ){//txdot
			$res = $this->contract_model->revenue();


			$sumQuoteReal = 0;
			$sumEarnedContract = 0;
			$sumEarnedCurrentMonth = 0;
			$sumCurrentMonth = 0;

			//var_dump($res);
			foreach($res as $ress){
				if($ress->revenue_standard < 0){
					$ress->revenue_standard = '0.00%';
				}elseif($ress->revenue_standard > 100){
					$ress->revenue_standard = '100.00%';
				}else{
					$ress->revenue_standard .= '%';
				}

				$sumQuoteReal += $ress->quote_real;
				$sumEarnedContract += $ress->earned_contract;
				$sumEarnedCurrentMonth += $ress->earned_current_month;
				$sumCurrentMonth += $ress->current_month;
			}

			$sum = [(object)['contract_id' => '0', 'contract_id_pannell' => 'TOTAL', 'quote_real' => $sumQuoteReal, 'earned_contract' => $sumEarnedContract, 'earned_current_month' => $sumEarnedCurrentMonth, 'current_month' => $sumCurrentMonth]];

			$res = array_merge($res, $sum);
		}else{//commercial
			$res = $this->contract_model->revenue_commercial();

			$sum = 0;
			$sum_cur = 0;

			foreach($res as $ress){
				$sum += $ress->sum;
				$sum_cur += $ress->current_month;
			}
		}
		


		//search variables
		$search['company_type'] = $company_type;

		$data['revenue'] = $res;
		$data['company_type'] = $company_type;
		$data['sum'] = $sum;
		$data['sum_cur'] = $sum_cur;
		$data['search'] = $search;
		$data['current'] = "revenue";
		//var_dump($res);
		$this->load->view('contract/revenue',$data);
	}

	/**
	* Purpose: Billing page based on contract and month
	* @param {array} array - contract_id and year-month.
	* @return: {json}
	*/
	public function monthlyBill(){
		$input = $this->input->get();
		$contract_id = isset($input['id']) && !empty($input['id']) ? $input['id'] : '';
		
		if($contract_id){
			//bill
			$where_bill['task.contract_id'] = $contract_id;
			$year_month = isset($input['date']) && !empty($input['date']) ? $input['date'] : date('m Y');
			$year_month = explode('/',$year_month);
			$where_bill['year(schedule.date)'] = $year_month[1]; 
			$where_bill['month(schedule.date)'] = trim($year_month[0], 0); 
			
			$res_bill = $this->contract_model->bill($this->bill_fields, $where_bill, $like = "", $json = true, $orderby = "");
			
			$bill = [];
			$task_cat = [];
			$sum = [];
			$sum[0] = 0;
			foreach($res_bill['result'] as $res){
				$bill[$res->tcat_id][] = ['tract' => $res->tract, 'schedule_id' => $res->schedule_id, 'hwy_id' => $res->hwy_id, 'section' => $res->section_to.' to '.$res->section_from, 'amount' => $res->unit_price, 'type' => $res->type] ;
				$sum[$res->tcat_id] += $res->unit_price;
				$sum[0] += $res->unit_price;
				if(!in_array($res->tcat_id, $task_cat)){
					$category = $res->category == 1 ? "Debris" : "Sweeping" ;
					$task_cat[$res->tcat_id] = ['type' => $res->type, 'category' => $category];
				}  
			}
			ksort($bill);

			//contract
			$where_contract['contract.contract_id'] = $contract_id;
			$res_contract = $this->contract_model->lists('contract.contract_id_pannell,contract.contract_id_ori,company.type', $where_contract);
			$contract = ['name' => $res_contract['result'][0]->contract_id_pannell, 'id' => $contract_id ,'ori_id' => $res_contract['result'][0]->contract_id_ori];

			$data['bill'] = $bill;
			$data['task_cat'] = $task_cat;
			$data['sum'] = $sum;
			$data['contract'] = $contract;
			$data['date'] = ['year' => $year_month[1], 'month' => $year_month[0] ];
			$data['company_type'] = $res_contract['result'][0]->type;
			$data['current'] = $this->current;
			//var_dump($data);
			//var_dump($bill[7], $sum, $data['date'] );
			

		}else{
			$where_contract['company.type'] = 1;//txdot
			$res_contract = $this->contract_model->lists($this->contract_lists_fields_txdot, $where_contract, $like = "", $json = true, $orderby = "");
			
			foreach ($res_contract['result'] as $value) {
				$contract[$value->contract_id] = ['contract_id_pannell' => $value->contract_id_pannell, 'contract_id_ori'=>$value->contract_id_ori]; 
			}

			$data['contract'] = $contract;

			var_dump($contract);
		}

		$this->load->view("contract/monthlyBill",$data);

	}

	/**
	* Purpose: Report page based on contract and date
	* @param {array} array - contract_id and date.
	* @return: 
	*/
	public function dailyReport(){
		$input = $this->input->get();
		$contract_id = isset($input['id']) && !empty($input['id']) ? $input['id'] : '';
		
		if($contract_id){
			//report
			$where_report['task.contract_id'] = $contract_id;
			$year_month = isset($input['date']) && !empty($input['date']) ? $input['date'] : date('m/d/Y');
			$ifPrint = isset($input['ifPrint']) && !empty($input['ifPrint']) ? $input['ifPrint'] : 0;

			$date = date('Y-m-d', strtotime($year_month));
			$where_report['schedule.date'] = $date; 
			
			$res_report = $this->contract_model->bill($this->bill_fields, $where_report, $like = "", $json = true, $orderby = ['task.tcat_id'=>'asc']);
			//var_dump($res_report);
			$report = [];
			$task_cat = [];
			$sum = [];
			$sum[0] = 0;
			foreach($res_report['result'] as $res){
				$report[$res->tcat_id][] = ['tract' => $res->tract, 'schedule_id' => $res->schedule_id, 'hwy_id' => $res->hwy_id, 'to' => $res->section_to, 'from' => $res->section_from, 'amount' => number_format($res->unit_price, 2), 'type' => $res->type, 'mile' => number_format($res->mile, 2), 'cycle' => $res->cycle] ;
				//$sum[$res->tcat_id] += $res->unit_price;
				//$sum[0] += $res->unit_price;
				$sum[$res->tcat_id] += $res->mile * $res->index_centerline;
				$sum[0] += $res->mile * $res->index_centerline;
				if(!in_array($res->tcat_id, $task_cat)){
					$category = $res->category == 1 ? "Debris" : "Sweeping" ;
					$task_cat[$res->tcat_id] = ['type' => $res->type, 'category' => $category];
				}  
			}
			

			//contract
			$where_contract['contract.contract_id'] = $contract_id;
			$res_contract = $this->contract_model->lists('contract.contract_id_pannell,contract.contract_id_ori,company.type, company.company_name', $where_contract);
			$contract = ['name' => $res_contract['result'][0]->contract_id_pannell, 'id' => $contract_id ,'ori_id' => $res_contract['result'][0]->contract_id_ori, 'company_name' => strtoupper($res_contract['result'][0]->company_name) ];

			$data['id'] = $contract_id;
			$data['report'] = $report;
			$data['task_cat'] = $task_cat;
			$data['sum'] = $sum;
			$data['contract'] = $contract;
			$data['date'] = $year_month;
			$data['company_type'] = $res_contract['result'][0]->type;
			

		}

		//contract to choose
		$resCon = $this->contract_model->lists( 'contract.contract_id_pannell' , $where = '', $like = '', $json = true, $orderby = array('contract.contract_id_pannell'=>'asc'), 1, 500);


		$data['current'] = $this->currentRep;
		$data['contractAll'] = $resCon['result'];
		//var_dump($data);
		//var_dump($bill[7], $sum, $data['date'] );
		if($ifPrint==0){
			$this->load->view("contract/dailyReport",$data);
		}else{				
			$this->load->view("contract/dailyReportDownload",$data);
		}

	}



	public function test(){
		//echo 'test';
		//echo '<pre>';
		//$this->contract_model->test();

		//task category
		$arr_tc['company_type'] = 1;
		$arr_tc['status'] = 1;
		$arr_tc['isdeleted'] = 0;
		$result_task_cat = $this->task_model->category_lists( $this->task_category_fields , $arr_tc, $like = '', $json = true, $orderby = '', $page = 1, $pagesize = '' );
		//var_dump($result_task_cat);
		//categorized by category when txdot
		//if($company_type==1){
			$cat_txdot = array();
			foreach($result_task_cat['result'] as $res){
				$cat_txdot[$res->category][] = ['tcat_id'=>$res->tcat_id , 'tcat_name'=>$res->tcat_name];
			}
		//}
		$data['task_cat'] = $cat_txdot;
		var_dump($data);
		$this->load->view('contract/test_js_add_highway',$data);
	}
}
