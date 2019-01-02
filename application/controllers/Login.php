<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Description: Login
 * Author: Rosy
 * Last Update: 05/31/2018
 */

class Login extends CI_Controller {

	public function __construct(){
		parent::__construct();

		$this->load->library('session');
		$this->load->model('login_model');
		$this->load->model('tool_model');



		$this->user_fields = 'SQL_CALC_FOUND_ROWS employee_id, username, password, nick_name, group_id, office';

		$this->contract_update_fields = '';

		$this->current = 'login';//nevigation
	}

	/**
	* Purpose: Index login page
	* @param 
	* @return
	*/
	public function index()
	{
		$user = $this->session->userdata('user');
		$input = 	$this->input->get();
		$data['fromurl'] = 	isset($input['fromurl']) && !empty($input['fromurl']) ? urldecode($input['fromurl']) : '';
		
		if($user){
			$this->tool_model->redirect(BASE_URL());//home page
		}else{
			$data['current'] = $this->current;
			$data['title'] = $this->title;
			$this->load->view('login/login.php',$data);//log page
		}
	}


	/**
	* Purpose: Login submit
	* @param {array} array - Email, password & fromurl
	* @return: null
	*/
	public function login_do()
	{
		$data = 	$this->input->post();
		//var_dump($data);exit;
		$username = isset($data['username']) && !empty($data['username']) ? $data['username'] : '';
		$password = isset($data['password']) && !empty($data['password']) ? md5($data['password']) : '';
		$fromurl = 	isset($data['fromurl']) && !empty($data['fromurl']) ? $data['fromurl'] : BASE_URL().'schedule/lists?company_type=2';//fromurl=<?php echo urlencode($_SERVER['REQUEST_URI']); ? >
		//var_dump($fromurl);
		/*
		if(isset($_POST['g-recaptcha-response'])){
          $captcha=$_POST['g-recaptcha-response'];
        }
        if(!$captcha){
		  $this->tool_model->redirect("/welcome/login",'Verification failure.');
          exit;
        }
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6Lc45AETAAAAAHNM-c4DTta3SahQSgGj_2HK_dDK&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
        if($response.success==false) {
			$this->tool_model->redirect("/welcome/login",'Verification failure');
			exit;
        } else {
			*/
			
			if( ($username == '') || ($password == '') )
			{
				$this->tool_model->redirect($fromurl,'Username or password is missing.');
				exit;
			}else{

				$arr = array(
					'username'=>$username,
					'password'=>$password,
				);
				
				
				$res = $this->login_model->checkin( $this->user_fields, $arr, $json = true);
				
				//var_dump($res);exit;
				
				if($res['userid']>0)
				{
					$user = array(
						'userid'=>$res['userid'],
						'username'=>$res['username'],
						'nick_name'=>ucfirst(strtolower($res['nick_name'])),
						'office'=>$res['office'],
						'groupid'=>$res['groupid']
					);
					$this->session->set_userdata('user', $user);
					
					//$hint = 'Login success.';
					$this->tool_model->redirect($fromurl,'Login success.');exit;
				}else{
					$this->tool_model->redirect(BASE_URL().'login/?fromurl='.BASE_URL().'schedule/lists','Login failure. Please try again.');exit;
				}
			
			
			}
	}


	/**
	* Purpose: Logout submit
	* @param: null
	* @returns: null 
	*/
	public function logout()
	{
		$user = $this->session->userdata('user');
		$input = $this->input->get();
		$data['fromurl'] = 	isset($input['fromurl']) && !empty($input['fromurl']) ? $input['fromurl'] : '';//fromurl=<?php echo urlencode($_SERVER['REQUEST_URI']); ? > 
		if($user){
			$this->session->set_userdata('user', '');
			//$data['current'] = $this->current_home;
		}else{
			//$data['current'] = $this->current;
			//$data['title'] = $this->title;
		}
		//var_dump($data);
		$this->load->view('login/login.php',$data);//log page
	}


	public function test(){
		echo 'test';
		echo '<pre>';
		$this->contract_model->test();
	}
}
