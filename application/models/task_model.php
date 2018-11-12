<?php
/**
  * Contract Model
*/

class Task_model extends CI_Model{

	public function __construct(){
		$this->load->database();
		$this->load->model('contract_model');
		$this->load->model('tool_model');
		$this->task_table = 'task';
		$this->task_cat_table = 'task_cat';
	}


	public function lists( $fields = '*' , $where = '', $like = '', $json = true, $orderby = array('id'=>'desc'), $page = 1, $pagesize = 500 ){
		if( is_array( $fields ) ) {
			$fld = implode( ',' , $fields );
		}else
			$fld = $fields;
		
		$this->db->select( $fld , false);


		if( is_array( $where ) ) {
			foreach ( $where as $key => $value ) {
				if(is_array( $value )){
					$this->db->where_in( $key, $value);
				}else{
					$this->db->where( $key , $value );
				}
			}
		}

		$this->db->where( 'contract.isdeleted' , 0 );
		$this->db->where( 'company.isdeleted' , 0 );
		$this->db->where( 'task.isdeleted' , 0 );
		$this->db->where( 'task_cat.isdeleted' , 0 );

		if( is_array( $like ) ){
			foreach($like as $value){
				if( isset($value['name']) && isset($value['value'])){
					$this->db->like( $value['name'], $value['value']);
				}
			}
		}

		$this->db->join('contract','task.contract_id = contract.contract_id');
		$this->db->join('company','company.company_id = contract.company_id');
		$this->db->join('task_cat','task_cat.tcat_id = task.tcat_id');

		if ( is_array($orderby) ){
			foreach($orderby as $order => $sort){
				$this->db->order_by( $order , $sort );
			}
		}

		$query = $this->db->get( $this->task_table , $pagesize , ($page-1) * $pagesize);
		//var_dump($this->db->last_query());
		$total = $this->db->query('SELECT FOUND_ROWS() count;');

		$reuslt = array();
		if($json)
			$result = $query->result();
		else
			$result = $query->reuslt_array();

		return array( 'result' => $result , 'total' => $total->row()->count );
	}


	public function add($data) {
		$filds = array('contract_id', 'tcat_id','unit_price', 'unit_price_2', 'unit','ifpostpone', 'traffic_control_price', 'disposal_price', 'ifweek','addtime');
		foreach($filds as $value){
			if( isset($data[$value])  ){
				$newarr[$value] = $data[$value];
			}
			else
				continue;
		}
		$this->db->insert($this->task_table, $newarr); 
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}
	
	
	public function update($data,$where){
		$filds = array('tcat_id','unit_price', 'unit_price_2', 'traffic_control_price', 'disposal_price', 'unit','ifpostpone', 'ifweek');
		foreach($filds as $value){
			if(isset($data[$value])){
				$newarr[$value] = $data[$value];
			}else
				continue;
		}
		return  $this->db->update($this->task_table, $newarr , $where); 
	}

	

	public function category_lists( $fields = '*' , $where = '', $like = '', $json = true, $orderby = array('id'=>'desc'), $page = 1, $pagesize = 500 ){
		if( is_array( $fields ) ) {
			$fld = implode( ',' , $fields );
		}else
			$fld = $fields;
		
		$this->db->select( $fld , false);


		if( is_array( $where ) ) {
			foreach ( $where as $key => $value ) {
				if(is_array( $value )){
					$this->db->where_in( $key, $value);
				}else{
					$this->db->where( $key , $value );
				}
			}
		}

		if( is_array( $like ) ){
			foreach($like as $value){
				if( isset($value['name']) && isset($value['value'])){
					$this->db->like( $value['name'], $value['value']);
				}
			}
		}

		//$this->db->join('contract','task.contract_id = contract.contract_id');
		//$this->db->join('client','client.client_id = contract.client_id');

		if ( is_array($orderby) ){
			foreach($orderby as $order => $sort){
				$this->db->order_by( $order , $sort );
			}
		}

		$query = $this->db->get( $this->task_cat_table , $pagesize , ($page-1) * $pagesize);
		//var_dump($this->db->last_query());
		$total = $this->db->query('SELECT FOUND_ROWS() count;');

		$reuslt = array();
		if($json)
			$result = $query->result();
		else
			$result = $query->reuslt_array();

		return array( 'result' => $result , 'total' => $total->row()->count );
	}

	/**
	* Load Csv To SQL
	*/
    public function loadTaskCSVToSQL($contract_id = 1, $allowed_col_num = 16, $userid='')
    {
        //check column number
        /*$file_count = fopen($_FILES['csv']['tmp_name'], "r"); 
        $line = fgetcsv($file_count,1024);
        $count_column = count($line);*/

        //contract info
        $whereCtr['contract.contract_id'] = $contract_id;
        $contract = $this->contract_model->lists('contract.contract_id, contract.year, contract.bdate', $whereCtr, '', true, '', 1, 1);
        $year = $contract['result'][0]->year;
       
        //csv
        //load PHPExcel library
		$this->load->library("PHPExcel");//both work

		//Read PHPExcel
		/**  Create a new Reader of the type defined in $inputFileType  **/ 
		$objReader = PHPExcel_IOFactory::createReader('CSV'); 
		$objPHPExcel = $objReader->load($_FILES['csv']['tmp_name']); 

		$sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow(); 
		$highestColumn = $sheet->getHighestColumn();
		        
		for($row = 2; $row <= $highestRow; $row ++){
			$curCycle = $objPHPExcel->getActiveSheet()->getCell("G".$row)->getValue();
			$index[] = $objPHPExcel->getActiveSheet()->getCell("O".$row)->getValue();
			if(!in_array($curCycle/$year, [1, 2, 3, 4, 6, 12, 24, 52, 54, 104, 108, 156, 162])){
				$result['count'] = 0;
	            $result['code'] = 301;//cycle not right
	            $result['msg'] = "wrong cycle number  (".$curCycle.")";//cycle not right
	            $result['cycle'] = $curCycle;
	            //return $result;exit;
	            var_dump($result);
			}
		}

		$alphabet = range('A', 'Z');
		$highestColumnNum = array_search($highestColumn, $alphabet);

        //var_dump($highestRow,$highestColumnNum,$year);exit;

        //if(is_int($year)){
        	if($highestColumnNum==$allowed_col_num){//column number is right
	            $result = array();

	            $table = 'task';

	            $sql1 = "select count(*) count from $table";
	            $query1 = $this->db->query($sql1);
	            $r1 = $query1->result_array();
	            $r1 = $r1[0]['count'];
	            
				$sql_insert = "INSERT INTO ".$table."(contract_id,hwy_id,section_from,section_to,tcat_id,tract,mile,cycle,unit_price,start_index";
				if($userid) $sql_insert .=",last_update_by";
				$sql_insert .=")
					SELECT contract_id,hwy_id,section_from,section_to,tcat_id,tract,mile,cycle,unit_price,start_index";
				if($userid) $sql_insert .=",".$userid;
	            $sql_insert .=" FROM temporary_table
	                ON DUPLICATE KEY UPDATE contract_id = rtrim(VALUES(contract_id)), hwy_id = VALUES(hwy_id), section_from = VALUES(section_from), section_to = VALUES(section_to), tcat_id = VALUES(tcat_id),tract = VALUES(tract),mile = VALUES(mile),cycle = VALUES(cycle),unit_price = VALUES(unit_price),start_index = VALUE(start_index)";
				if($userid) $sql_insert .=",last_update_by = ".$userid;
				$sql_insert .=";";
				
				$_FILES['csv']['tmp_name'] = str_replace("\\", "/", $_FILES['csv']['tmp_name']);

	            $bulksql = array(
	                "USE pannell;",
	                
					"CREATE TABLE temporary_table LIKE pannell.".$table.";",
	                

	                "LOAD DATA LOCAL INFILE '".$_FILES['csv']['tmp_name']."'
	                INTO TABLE temporary_table
	                CHARACTER SET UTF8 
	                FIELDS 
	                    TERMINATED by ','
	                    
	                
	                IGNORE 1 LINES
	                (@col1, @col2, @col3, @col4, @col5, @col6, @col7, @col8, @col9, @col10, @col11, @col12, @col13, @col14, @col15) 
					set contract_id=@col12, hwy_id=@col3, section_from=@col4, section_to=@col5, tcat_id=@col11, tract=@col1, mile=@col6, cycle=@col7, unit_price=@col9, start_index=@col15;",
					
					$sql_insert            
	            );


	            //ENCLOSED BY '\"'
	            //ESCAPED BY '\"'
				 


				 //LINES 
						//TERMINATED BY '\r'
				 //OPTIONALLY ENCLOSED BY '\"'
				 //ESCAPED BY ''
				 
				
	            foreach($bulksql as $sql2){
	            	//var_dump($sql2);
	                //$query2 = $this->db->query($sql2);
	            }
	            //exit;
	            $affected_rows = $this->db->affected_rows();
	            
				$start_id = $this->db->insert_id();
				
				
	            

				//$this->db->query("DROP TABLE temporary_table;");
				//exit;
	            $sql3 = "select count(*) count from $table";
	            $query3 = $this->db->query($sql3);
	            $r3 = $query3->result_array();
	            $r3 = $r3[0]['count'];
				//count added rows
				$added_rows = (int)$r3 - (int)$r1;
				//
				$complete_id = $start_id + $added_rows - 1;
				
				//count updated rows
				if($added_rows){
					$affected_rows = $affected_rows-$added_rows;
				}
				if(fmod($affected_rows,2) == 1)
					$updated_rows = ($affected_rows-1)/2;
	            else
					$updated_rows = $affected_rows/2;
	            
				
				//make the schedule
				//$arrTaskId = range($start_id, $complete_id);
				$res_sch = $this->tool_model->loadTaskCSVToSQLSch($contract_id);

				if($res_sch['code'] == 200){
					$result['added_rows'] = $added_rows;
		            $result['updated_rows'] = $updated_rows;
		            $result['code'] = 200;
		            $result['sql2'] = $sql2;
		            $result['query2'] = $query2;
		            $result['r1'] = $r1;
		            $result['r3'] = $r3;
		            $result['file'] = $file;
		            $result['month'] = $month;
		            $result['bdate'] = $bdate;
		            $result['start_id'] = $start_id;
		            $result['complete_id'] = $complete_id;
		            $result['contract_id'] = $contract_id;
		            
				}else{
		            $result['code'] = $res_sch['code'];
		            $result['msg'] = $res_sch['msg'];//column not right
		            $result['count_column'] = $highestColumnNum;
		            $result['line'] = $line;
		           
				}



	            
	            
	            // if($count>=0)
	            // {
	                // $result['message'] = "<div class='alert alert-info' id='result_message'><b> total $count records have been added to the table $table, table total row is $r3.</b></div>";
	            // }else{
	                // $result["message"] = "<div class='alert alert-info' id='result_message'><b> $table, $file</b></div> ";
	            // }
	            //var_dump($r1,$r3,$query2);exit;
	            
	        }else{
	            $result['count'] = 0;
	            $result['code'] = 300;//column not right
	            $result['msg'] = "wrong column number (".$highestColumnNum.')';//column not right
	            $result['count_column'] = $highestColumnNum;
	            $result['line'] = $line;
	            
	        }
        /*}else{
        	$result['count'] = 0;
            $result['code'] = 301;//year is not an integer
            $result['msg'] = "year is not an integer";//year is not an integer
            $result['count_column'] = $count_column;
            $result['line'] = $line;
            
            return $result;
        }*/
        return $result;
        
        

    }


	public function test($file='', $table='task', $allowed_col_num=5,$userid=''){
		//check column number
        $file_count = fopen($_FILES['csv']['tmp_name'], "r"); 
        $line = fgetcsv($file_count,1024);
        $count_column = count($line);
		var_dump($line);

		$_FILES['csv']['tmp_name'] = str_replace("\\", "/", $_FILES['csv']['tmp_name']);
		
		var_dump($_FILES['csv']['tmp_name']);
		$bulksql = array(
                "USE pannell;",

                "LOAD DATA LOCAL INFILE '".$_FILES['csv']['tmp_name']."'
                INTO TABLE temporary_table
                CHARACTER SET UTF8 
                FIELDS 
                    TERMINATED by ','
                    ENCLOSED BY '\"'
            		ESCAPED BY '\"'
            	LINES TERMINATED BY '\n'
                
                IGNORE 1 LINES
                (@col1, @col2, @col3, @col4, @col5, @col6, @col7, @col8) 
				set contract_id=@col1, hwy_id=@col2, section_from=@col3, section_to=@col4, tcat_id=@col5, tract=@col6, mile=@col7, cycle=@col8;",
				
				$sql_insert
        
            );

		foreach($bulksql as $sql2){
			var_dump($sql2);
            $query2 = $this->db->query($sql2);

        }
	}


	
}

/**  Define a Read Filter class implementing PHPExcel_Reader_IReadFilter  
	class MyReadFilter implements PHPExcel_Reader_IReadFilter 
	{ 
	    public function readCell($column, $row, $worksheetName = '') { 
	        //  Read rows 1 to 7 and columns A to E only 
	        if ($row >= 1 && $row <= 7) { 
	            if (in_array($column,range('A','E'))) { 
	                return true; 
	            } 
	        } 
	        return false; 
	    } 
	} 
*/ 
?>