<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct(){
		parent::__construct();

		$this->load->library('session');
		$this->load->model('tool_model');
		$user = $this->session->userdata('user');
		if(!$user){
			$this->tool_model->redirect(BASE_URL().'login/?fromurl='.str_replace('/ci/', '', $_SERVER["REQUEST_URI"]),'Please login.');
		}
	}

	public function index()
	{
		$this->load->view('welcome_message');
	}

	public function notice(){
		$this->load->model('tool_model');
		/*//schedule time
		//$where_unschedule['schedule.schedule_year'] = date('Y', strtotime("+1 week"));
		//$where_unschedule['schedule.schedule_week'] = date('W', strtotime("+1 week"));
		$where_unschedule['schedule.schedule_year'] = date('Y');
		$where_unschedule['schedule.schedule_week'] = date('W');
		$where_unschedule['schedule.schedule_date'] = null;

		$this->load->model('schedule_model');
		
		$result_unschedule = $this->schedule_model->lists('schedule.schedule_id', $where_unschedule, '', $json = true, $orderby = '', 1, 500);
		//var_dump( $result_unschedule);
		$notice['unschedule'] = $result_unschedule['total'];
		var_dump( $where_unschedule);*/

		//unfinished schedule this month
		$where_unfinish_month['schedule.schedule_year'] = date('Y');
		$where_unfinish_month['schedule.schedule_month'] = date('n');
		$where_unfinish_month['schedule.date'] = null;

		$this->load->model('schedule_model');
		
		$result_unfinish_month = $this->schedule_model->lists('schedule.schedule_id', $where_unfinish_month, '', $json = true, $orderby = '', 1, 1000000);
		$notice['unschedule'] = $result_unfinish_month['total'];


		//unfinished revenue this month
		$where_unfinish_revenue_month['schedule.schedule_year'] = date('Y');
		$where_unfinish_revenue_month['schedule.schedule_month'] = date('n');
		$where_unfinish_revenue_month['schedule.date'] = null;
		$result_unfinish_revenue_month = $this->schedule_model->lists('SQL_CALC_FOUND_ROWS contract.contract_id, task.mile * task_cat.index_centerline * task.unit_price  as total', $where_unfinish_revenue_month, $like, $json = true, $orderby = '', 1, 100000);
		//var_dump($result_unfinish_revenue_month['result']);
		$revenue_left = '';
		foreach($result_unfinish_revenue_month['result'] as $res){
			$revenue_left += $res->total;
		}
		
		$where_total_revenue_month['schedule.schedule_year'] = date('Y');
		$where_total_revenue_month['schedule.schedule_month'] = date('n');
		$result_total_revenue_month = $this->schedule_model->lists('SQL_CALC_FOUND_ROWS contract.contract_id, task.mile * task_cat.index_centerline * task.unit_price  as total', $where_total_revenue_month, $like, $json = true, $orderby = '', 1, 100000);
		$revenue_total = '';
		foreach($result_total_revenue_month['result'] as $res){
			$revenue_total += $res->total;
		}
		$percentage_left_month = number_format(100 * $revenue_left/$revenue_total, 2).'%';
		$notice['unrevenue'] = $percentage_left_month;
		$notice['rosy'] = 'eee';


		//bonding amount by last day last month
		$bonding = $this->schedule_model->getBonding();
		$notice['bonding'] = '$'.number_format($bonding,2);
		//var_dump($notice);


		echo json_encode(array('code'=>200,'notice'=>$notice)); 
	}

	public function test(){
		/*$redis = new Redis();
		$redis->connect('127.0.0.1',6379);  
		echo $redis->get('myname');*/

		$this->load->view('test.php');
	}

}
