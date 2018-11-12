<?php
/**
	* Login model
	* @filename		login_model.php
	* @create date  2018-05-31 
*/
class Login_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('cookie');
		$this->load->library('session');
		$this->load->database();

		$this->login_table = 'employee';
	}


	/** 
	* Login
	* @param	array()	$arr	user info 
	* @return	array()
	*/
	public function checkin( $fields,$where = '' ,  $json = true )
	{
		if($where["username"]!='')
		{
			$this->db->where( "username" , $where["username"] );
		}
		$this->db->where( "isdeleted" , '0' );
		
		$total = $this->db->count_all_results( $this->login_table );

		
		//var_dump($total);
		if($total > 0)
		{
			if( is_array( $where ) )
			{
				foreach( $where as  $key=>$value )
				{
					$this->db->where( $key , $value );
				}
			}

			$this->db->select( $fields , false );
			$this->db->where( "isdeleted" , '0' );
			$query = $this->db->get( $this->login_table );
			//echo $this->db->last_query();
			//var_dump($query);
			if( $json)
			{
				$result = $query->row();
			}
			else 
			{
				$result = $query->result_array();
            }
			//var_dump($result);exit;
			if(!empty($result))
			{
				$this->db->update($this->login_table, ['last_login_time'=>date('Y-m-d H:i:s')], ['employee_id'=>$result->employee_id]);
				$user = array( 'userid' => $result->employee_id ,'username'=>$result->username,'nick_name'=>$result->nick_name,'office' => $result->office,'groupid' => $result->group_id);	
				return $user;
			}
		}else{
			return array( 'userid' => 0 );
		}
		//return $result;
	}

	/**
	* if logged in
	*
	* @param	int	    $url	redirect url
	* @param	string	$session	
	* @param	string	$cookie	 
	* @return	int			
	*/
	public function keep_login() 
	{
		$user = $this->session->userdata('user');
		$setcookie = $this->input->cookie('remember', TRUE);
		$userinfo = json_decode($setcookie,true);
		if($user)
		{
			return true;
		}
		elseif( isset($userinfo['uid']) && $userinfo['uid'] > 0 )
		{
			$this->set_session($userinfo,'user');
			$this->set_cookie($userinfo,'remember');
			return true;
		}
		else
		{
			return false;
		}
	}

	function prev_url($url) 
	{
		if(!empty($url))
		{
			header('Location:'.$url);
		}
		else
		{
			header('Location: /login/index/');
		}
	}

	/**
	* Login
	*
	* @param	array	$post	用户登录填写的信息
	* @return	array			
	*/
	public function user_select($post) 
	{
		$array = array();
		$url = '/account/lists/';
		$getuser = $this->tool_model->curl_post($url,$post);
		$getuser = json_decode($getuser,true);
		$user = $getuser['result']['account'];
		
		if($user)
		{
			$user[0]['check']=true;
		}
		else
		{
			$user[0]['check'] =false;
		}
		return $user;
	}

	/**
	* 存入session
	*
	* @param	array	$array	存入的value
	* @param	string	$key	存入的键值
	* @return	null
	*/
	public function set_session($array,$key='') 
	{
		$this->session->set_userdata($key,$array);
	}

	/**
	* 存入session
	*
	* @param	array	$array	存入的value
	* @param	string	$key	存入的键值
	* @return	null
	*/
	public function set_cookie($array,$key) 
	{
		$cookie = array(
					'name'   => $key,
					'value'  => json_encode($array),
					'expire' => 3600*24*30,
					'path' => '/',
				);
		$this->input->set_cookie($cookie);
	}
	
/**
	* 登出操作
	*
	* @param	array	$array	存入的value
	* @param	string	$key	存入的键值
	* @return	null
	*/
	public function logout() 
	{
        $this->session->unset_userdata('user');
        $cookie = array(
					'name'   => 'remember',
					'value'  => '',
					'path' => '/',
				);
		$this->input->set_cookie($cookie);
		$this->tool_model->redirect('/login/index/');
	}
}
