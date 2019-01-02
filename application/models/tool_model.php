<?php

class Tool_model extends CI_Model {

	public function __construct()
	{
		//$this->load->library('cache');
		$this->load->library('session');
		$this->load->model('schedule_model');
		$this->load->model('task_model');
		$this->user = $this->session->userdata('user');
		$this->task_lists_fields = 'SQL_CALC_FOUND_ROWS contract.year, contract.month, contract.bdate, task.contract_id, task.task_id, task.cycle, task.unit_price, task_cat.category';
	}

	/**
	* Pagination
	*
	* @param	int	$current    current page
	* @param	int	$pagesize	number of records per page
	* @param	int	$total		total number of records
	* @param	string	$url	path
	* @param	array()	$arr	search variables
	* @return	array()			records
	*/
	 public function makepage($current,$pagesize,$total,$url,$arr=array())
	 {
	 	$pagecount = ceil($total/$pagesize);
	 	$pageinfo = array();
	 	$pageinfo['current'] = $current < $pagecount ? $current :$pagecount;
	 	$pageinfo['pagecount'] = $pagecount;
	 	$pageinfo['total'] = $total;
	 	$fix = '';
	 	if(is_array($arr) && count($arr) > 0)
	 	{
	 		foreach($arr as $k=>$v)
	 		{
	 			if($k != 'page')
	 			{
	 				$fix .= "&".$k.'='.urlencode($v);
	 			}
	 		}
	 	}
	 	$next = $current < $pagecount ? $current+1 :$pagecount;
	 	$pageinfo['front'] = $url.'?page='.($current-1).$fix;
	 	$pageinfo['next'] = $url.'?page='.($next).$fix;
	 	$pageinfo['first'] = $url.'?page=1'.$fix;
	 	$pageinfo['last'] = $url.'?page='.$pagecount.$fix;
	 	return $pageinfo;
	 }
	 
	/**
	* Redirect to other page
	*
	* @param	int	$url	redirect url
	* @param	int	$msg	notification
	* @return	null			
	*/
	 
	 public function redirect($url,$msg='')
	 {
	 	if($msg !== '')
	 	{
	 		$al = 'alert("'.$msg.'");';
	 	}
	 	else
	 	{
	 		$al = '';
	 	}
	 	echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script charset="utf-8">'.$al.'window.location.href="'.$url.'";</script>';exit;
	 }

	/**
	* Create bulk schedule with schedule_year, schedule_month, schedule_week
	*
	* @param	int	$arr_taskid	
	* @return	null			
	*/
	 
	 public function add_week($arr_task_id = '')
	 {
	 	echo '<pre>';
	 	if($arr_task_id == '' || $arr_task_id == array()){
	 		return false;
	 	}else{
		 	//task info
			$where['task_id'] = $arr_task_id;
			//$where['task_id'] = 1424;//test,frequency based on week, 3 times per week
			//$where['task_id'] = 1607;//test,frequency based on week, 3 times per week, Collin
			//$where['task_id'] = 1423;//test,frequency based on week, 2 times per week
			//$where['task_id'] = 1609;//test,frequency based on week, 1 time per 2 weeks
			//$where['task_id'] = 1610;//test,frequency based on month, 2 times per 3 months
			//$where['task_id'] = 1797;//test

			$task_res = $this->task_model->lists($this->task_lists_fields, $where, $like, $json = true, $orderby, $page, $pagesize);

			$total_cycle = 0;
			//assign year and week to schedule
			foreach($task_res['result'] as $res){

				//var_dump($res->cycle/(52*$res->year), $res->cycle/(54*$res->year), $res->year);

				//frequency based on month or week, 99.999999% is debris, cycles per year inclues: 26, 52, 104, 156,,, 27, 54, 108, 162
				if(is_int($res->cycle/(52*$res->year)) || is_int($res->cycle/(54*$res->year)) || $res->cycle/(52*$res->year) == 0.5 || $res->cycle/(54*$res->year) == 0.5){//by week
					
					$ini_dayyyyyyy = date('w', strtotime($res->bdate));
					$ini_date = date('N',strtotime($res->bdate)) == 1 ? $res->bdate : date('Y-m-d',strtotime('+ '.(8-$ini_dayyyyyyy).' days', strtotime($res->bdate)));//set initial date to next Monday. Cities don't allow debirs work on Sunday
					


					$frequencyPerWeek = $res->cycle/(52*$res->year) ? $res->cycle/(52*$res->year) : $res->cycle/(54*$res->year) ; 
					$addMaxDate = $frequencyPerWeek == 0.5 ? 13 : 6;//calculate maxdate, when one time per 2 weeks, the period will be 2 weeks; otherwise it's 1 week


					for( $i=1; $i <= $res->cycle ; $i++){
						/*if($ini_month <= 12){
							$modi_year = $ini_year;
							$modi_week = $ini_week;
						}else{
							$modi_year = $ini_year = $ini_year + 1;
							$modi_week = $ini_week = $ini_week - 12;
						}*/

						$mindate = $ini_date;
						$maxdate = date('Y-m-d', strtotime("+ ".$addMaxDate." days", strtotime($mindate)));

						switch ($frequencyPerWeek) {//cycle per week
							case 3 :

								if($i % 3 == 1){
									$work_weekday = $ini_date;
								}elseif($i % 3 == 2){
									$work_weekday = date('Y-m-d', strtotime('+ 2 days', strtotime($ini_date)));
								}else{
									$work_weekday = date('Y-m-d', strtotime('+ 4 days', strtotime($ini_date)));

									$ini_date = date('Y-m-d', strtotime('+ 7 days', strtotime($ini_date)));
								}
								break;

							case 2:
								if($i == 1 ) $ini_date = date('Y-m-d', strtotime('+ 1 days', strtotime($ini_date)));//starts on Tuesday

								if($i % 2 == 1){
									$work_weekday = $ini_date;
								}else{
									$work_weekday = date('Y-m-d', strtotime('+ 2 days', strtotime($ini_date)));

									$ini_date = date('Y-m-d', strtotime('+ 7 days', strtotime($ini_date)));
								}
								break;

							case 1:
								$work_weekday = $ini_date;

								$ini_date = date('Y-m-d', strtotime('+ 7 days', strtotime($ini_date)));
								break;

							case 0.5:
								$work_weekday = $ini_date;

								$ini_date = date('Y-m-d', strtotime('+ 14 days', strtotime($ini_date)));
								break;


							default:
								$this->redirect(BASE_URL().'task/add_csv','ask IT (week mod)'.$res->cycle);exit;
								break;
						}

						$insert_data['mindate'] = $mindate;
						$insert_data['maxdate'] = $maxdate;
						$insert_data['schedule_year'] = date('Y', strtotime($work_weekday));
						$insert_data['schedule_month'] = date('n', strtotime($work_weekday));
						$insert_data['schedule_week'] = date('N', strtotime($work_weekday)) != 7 ? ltrim(date('W', strtotime($work_weekday)), '0') : ltrim(date('W', strtotime($work_weekday)), '0') + 1;//week starts from Sunday
						$insert_data['schedule_date'] = $work_weekday;
						$insert_data['task_id'] = $res->task_id;
						$insert_data['addtime'] = date('Y-m-d H:i:s');

						//$insert_data['ini_month'] = $ini_month;//test
						//$insert_data['cycle'] = $res->cycle;//test
						//$insert_data['total_year'] = $res->year;//test
						//$insert_data['total_month'] = $res->month;//test

						$insert_week[] = $insert_data;		
					}

				}else{//by month

					$ini_year = date('Y',strtotime($res->bdate));
					$ini_month = date('n',strtotime($res->bdate));

					//only for harris county
					if($res->contract_id == 11 && $res->cycle == 18){
						$ini_year = 2017;
						$ini_month = 3;
					}

					//settttttttingggggggggg
					$settle_to_next_week_1 = 4;//Thursday
					$settle_to_next_week_14 = $settle_to_next_week_1 - 1;//one day before $settle_to_next_week_1

					//var_dump($ini_year,$ini_month);//testing
					for( $i=1; $i <= $res->cycle ; $i++){
						if($ini_month <= 12){
							$modi_year = $ini_year;
							$modi_month = $ini_month;
						}else{
							$modi_year = $ini_year = $ini_year + 1;
							$modi_month = $ini_month = $ini_month - 12;
						}
						

						switch ($res->cycle/$res->month) {//cycle per month

							//case 12://cycle per year
							case 1://cycle per month

								$work_weekday = $modi_year.'-'.$modi_month.'-01';

								$settle_to_next_week = $settle_to_next_week_1;

								//one month period
								$insert_data['mindate'] = date('Y-m-01', strtotime($work_weekday));
								$insert_data['maxdate'] = date('Y-m-t', strtotime($work_weekday));

								$ini_month ++;

								break;
							
							//case 18://cycle per year
							case 1.5://cycle per month
								if($i % 3 == 1){

									$work_weekday = $modi_year.'-'.$modi_month.'-01';

									$settle_to_next_week = $settle_to_next_week_1;

									//two months period
									$insert_data['mindate'] = date('Y-m-01', strtotime($work_weekday));
									$insert_data['maxdate'] = date('Y-m-t', strtotime('+ 1 month', strtotime($work_weekday)));

								}elseif($i % 3 == 2){

									$work_weekday = $modi_year.'-'.$modi_month.'-14';

									$settle_to_next_week = $settle_to_next_week_14;

									//two months period
									$insert_data['mindate'] = date('Y-m-01', strtotime($work_weekday));
									$insert_data['maxdate'] = date('Y-m-t', strtotime('+ 1 month', strtotime($work_weekday)));

									$ini_month ++;
									
								}else{

									$work_weekday = $modi_year.'-'.$modi_month.'-01';

									$settle_to_next_week = $settle_to_next_week_1;
									
									//two months period
									$insert_data['mindate'] = date('Y-m-01', strtotime('- 1 month', strtotime($work_weekday)));
									$insert_data['maxdate'] = date('Y-m-t', strtotime($work_weekday));

									$ini_month ++;

								}

								break;

							//case 24://cycle per year
							case 2://cycle per month
								
								if($i % 2 == 1){

									$work_weekday = $modi_year.'-'.$modi_month.'-01';

									$settle_to_next_week = $settle_to_next_week_1;

									//first 2 weeks of the month
									$insert_data['mindate'] = date('Y-m-01', strtotime($work_weekday));
									$insert_data['maxdate'] = date('Y-m-d', strtotime('+ 14 days', strtotime($work_weekday)));

								}else{

									$work_weekday = $modi_year.'-'.$modi_month.'-14';

									$settle_to_next_week = $settle_to_next_week_14;

									//last 2 weeks of the month
									$insert_data['mindate'] = date('Y-m-d', strtotime($work_weekday));
									$insert_data['maxdate'] = date('Y-m-t', strtotime($work_weekday));

									$ini_month ++;

								}	

								break;

							default://months per cycle: 2,3,4,6,
								
								//$ini_month += 12 * $res->year / $res->cycle ;//per year
								$month_per_cycle = $res->month / $res->cycle ;//month per cycle

								
								if($res->contract_id == 11 && $res->cycle == 18){//only for harris county
									$work_weekday = $modi_year.'-'.$modi_month.'-01';//1st week
									//if(date("N", strtotime($work_weekday)) >= $settle_to_next_week_1) $work_weekday = date('Y-m-d', strtotime('+1 week',strtotime($work_weekday)));

									$settle_to_next_week = $settle_to_next_week_1;

									$ini_month += 1 ;

									$insert_data['mindate'] = date('Y-m-01', strtotime($work_weekday));
									$insert_data['maxdate'] = date('Y-m-t', strtotime($work_weekday));
									//break;
								}elseif(is_int($month_per_cycle)){

									$work_weekday = $modi_year.'-'.$modi_month.'-14' ;

									$settle_to_next_week = $settle_to_next_week_14;

									$ini_month += $month_per_cycle ;

									//months period
									$insert_data['mindate'] = date('Y-m-01', strtotime($work_weekday));
									$insert_data['maxdate'] = date('Y-m-t', strtotime("+ ".($month_per_cycle - 1)." months", strtotime($work_weekday)));
	
									
								}elseif($month_per_cycle = 1.5){//twice per 3 months

									$work_weekday = $modi_year.'-'.$modi_month.'-01' ;

									$settle_to_next_week = $settle_to_next_week_1;

									$insert_data['mindate'] = date('Y-m-01', strtotime($work_weekday));
									$insert_data['maxdate'] = date('Y-m-t', strtotime("+ 1 months", strtotime($work_weekday)));

									if($i % 2 == 1){
										$ini_month += 1 ;
									}else{
										$ini_month += 2 ;
									}
									
								}else{
									$this->redirect(BASE_URL().'task/add_csv','ask IT (month mod)'.$res->cycle);exit;
								}

								break;
						}
						
						$dayyyy = date('w', strtotime($work_weekday));
						if($res->task_id == 446)var_dump($work_weekday);
						//when schedule is after $settle_to_next_week, set up to next Sunday
						if(date("w", strtotime($work_weekday)) >= $settle_to_next_week) $work_weekday = date('Y-m-d', strtotime('+ '.(7 - $dayyyy).' days',strtotime($work_weekday)));

						//when date is 14, set up to previous Sunday
						if(date("d", strtotime($work_weekday)) == 14) {
							$work_weekday = date('Y-m-d', strtotime('- '.$dayyyy.' days',strtotime($work_weekday)));
							$insert_data['mindate'] = date('Y-m-d', strtotime($work_weekday));
						}


					
						$insert_data['schedule_year'] = $modi_year;
						$insert_data['schedule_month'] = $modi_month;
						$insert_data['schedule_week'] = date('N', strtotime($work_weekday)) != 7 ? ltrim(date('W', strtotime($work_weekday)), '0') : ltrim(date('W', strtotime($work_weekday)), '0') + 1;//week starts from Sunday
						$insert_data['schedule_date'] = $work_weekday;
						$insert_data['task_id'] = $res->task_id;
						$insert_data['unit_price'] = $res->unit_price;
						$insert_data['addtime'] = date('Y-m-d H:i:s');

						$insert_data['ini_month'] = $ini_month;//test
						$insert_data['cycle'] = $res->cycle;//test
						$insert_data['total_year'] = $res->year;//test
						$insert_data['total_month'] = $res->month;//test

						$insert_week[] = $insert_data;		
						
					}
				}
				//echo $res->task_id; echo "&nbsp;"; echo $total_cycle += $res->cycle; echo '<br>'; 
				unset($modi_year);
				unset($modi_month);
				unset($date_week3);
				unset($insert_data);
			}
			//echo "<pre>";
			//var_dump($insert_week);exit;

			//insert into schedule, and update task ifweek
			$insert_res = $this->schedule_model->add_batch($insert_week);

			if($insert_res){
				return true;
			}else{
				return false;
			}
			
	 	}
	 	
	 }

	/**
	* Return week number within the month
	*
	* @param	specific date	$spec_date	
	* @return	array weeks			
	*/
	 
	 public function weeks_in_month($spec_date = '')
	 {
	 	$spec_date = $spec_date ? $spec_date : date('Y-m-d');
	 		
	 	$spec_year = date('Y', strtotime($spec_date));
 		$spec_month = date('m', strtotime($spec_date));
		$start_date = $spec_year.'-'.$spec_month.'-01';
		$arr_week = [date('W', strtotime($start_date))];
		$k = 0;
		
		while ($k == 0) {
			$start_date = date('Y-m-d', strtotime("+1 week", strtotime($start_date)));

			if(date('m',strtotime($start_date)) == $spec_month){
				array_push($arr_week, date('W', strtotime($start_date)));
			}else{
				$k ++;
			}
		}
		return $arr_week;
	 }


	 /*upload task excel sheet into database, and arrange schedules

	 */
	 function loadTaskCSVToSQLSch($contract_id){
	 	if($contract_id){
	 		$taskRow = $this->db->query("
	 			select task.task_id, task.cycle, task.start_index, task.unit_price, tcat.category, ctr.year, ctr.bdate 
	 			from task
	 			left join contract ctr
	 			on task.contract_id = ctr.contract_id
	 			left join task_cat tcat
	 			on tcat.tcat_id = task.tcat_id
	 			where task.contract_id = $contract_id
	 			and task.isdeleted = 0
	 			and ctr.isdeleted = 0
	 			order by task.task_id");
		 	$task = $taskRow->result_array();
		 	$contractYear = $task[0]['year'];
		 	$bdate = $task[0]['bdate'];
		 	

		 	$tasktest[0] = $task[0];//test
		 	$task = $tasktest;//test
		 	//var_dump($contractYear,$bdate,$task);exit;

		 	foreach($task as $tasks){

		 		$startDate = $bdate;//first day of month


				if($tasks['category'] == 1 ){
					$skipDay = [6, 7];
					$temp_weekday = date('N', strtotime($startDate));
					$temp_weekday >= 2 || $temp_weekday <= 5 ? $startDate = date('Y-m-d', strtotime('+ '.(7-$temp_weekday).' days', strtotime($startDate))):'';
				}else{
					$skipDay = [5, 6];
				}
				

				$frequency = $tasks['cycle'] / $contractYear;


				$indexDate = $tasks['start_index'] - 1;
				

				$task_id = $tasks['task_id'];


				$unit_price = $tasks['unit_price'];

				//frequency determines addmonth, 24, 104, 108, 156, 162 will be decided later on based on the loop index
				$startDateAs1st = 'Y-m-1';
				if(in_array($frequency, [1, 2, 3, 4, 6, 12])){
					$freAddNum = 12 / $frequency;
					$freAddUnit = 'month';
				}elseif($frequency == 24){
					$freAddNum = 1;
					$freAddUnit = 'month';


				}elseif(in_array($frequency, [52, 54, 104, 108, 156, 162])){
					$freAddNum = 7;
					$freAddUnit = 'day';
					$startDateAs1st = 'Y-m-d';

					
				}


				for($i = 0; $i < $tasks['cycle']; $i ++){
					

					$indexDate2 = $indexDate;
					$dareYouTouchMyStartDate = 1;
					if(in_array($frequency, [104, 108])){
						switch ($i % 2) {
							case 0:
								$dareYouTouchMyStartDate = 0;
								break;
							
							default://1
								$indexDate2 = $indexDate + 2;
								break;
						}
						
					}elseif(in_array($frequency, [156, 162])){
						switch ($i % 3) {
							case 0:
								$dareYouTouchMyStartDate = 0;
								break;

							case 1:
								$dareYouTouchMyStartDate = 0;
								$indexDate2 = $indexDate + 2;
								break;

							default://2
								$indexDate2 = $indexDate + 4;
								break;
						}
						
					}elseif($frequency == 24){
						switch ($i % 2) {
							case 0:
								$dareYouTouchMyStartDate = 0;
								break;
							
							default://1
								$indexDate2 = $indexDate + 14;
								break;
						}

					}
						//how many friday, saturday and sunday before indexDate in this month
						$addDate = 0;
						$count = 0;
						while (1) {
							$weekDay = date('N', strtotime('+ '.($addDate + $count).' day', strtotime($startDate)));
							in_array($weekDay, $skipDay) ? $addDate ++ : $count ++;
							if($count == $indexDate2 + 1) break;
						}
					

						

					
					//calculate schedule date
					$update['taskId'] = $task_id;
					$update['unitPrice'] = $unit_price;
					$update['scheduleDate'] = $scheduleDate = date('Y-m-d', strtotime('+ '.($indexDate2 + $addDate).' day', strtotime($startDate)));

					$update['scheduleYear'] = date('Y', strtotime($scheduleDate));
					$update['scheduleMonth'] = date('n', strtotime($scheduleDate));
					$update['scheduleWeek'] = date('W', strtotime($scheduleDate));

					$arrUpdate .= "(".$update['taskId']."', '".$update['unitPrice']."', '".$update['scheduleYear']."', '".$update['scheduleMonth']."','".$update['scheduleWeek']."','".$update['scheduleDate']."','1'),";//Schedule_id, Year, Month, Week, Date
					var_dump($update, $dareYouTouchMyStartDate, $freAddNum, $freAddUnit);


					//set up next startDate
					$dareYouTouchMyStartDate == 1 ? $startDate = date($startDateAs1st, strtotime('+ '.$freAddNum.' '.$freAddUnit, strtotime($startDate))) : '';
					//var_dump($startDate);

					/*//collin customized
					$addMonth = in_array($update['scheduleMonth'], [3, 5, 7, 9]) ? 2 : 1;
					$startDate = date('Y-m-1', strtotime('+ '.$addMonth.' month', strtotime($startDate)));*/

					unset($update);
			 	}


			 	$arrUpdate = trim($arrUpdate, ",");

				exit;

				$res = $con->query('insert INTO schedule (task_id, unit_price, schedule_year, schedule_month, schedule_week, schedule_date, crew_id)
					VALUES
					'.$arrUpdate.'
					ON DUPLICATE KEY UPDATE
					task_id=VALUES(task_id), 
					unit_price=VALUES(unit_price), 
					schedule_year=VALUES(schedule_year), 
					schedule_month=VALUES(schedule_month),
					schedule_week=VALUES(schedule_week),
					schedule_date=VALUES(schedule_date)
					
					;
				');


			}


			//modify debris
			$temp_weekday = date('N', strtotime($bdate));
			$temp_weekday_name = date('D', strtotime($bdate));
			if($temp_weekday >= 2 || $temp_weekday <= 5){
				$ijij = 6 - $temp_weekday;//numbers of days that will be changed to the beginning
				$lastStartDate = date('Y-m-d', strtotime('+ '.$contractYear.' year last '.$temp_weekday_name, strtotime($bdate)));

				for($i = 0 ; $i < $ijij ; $i ++){
					$update['scheduleDate'] = $scheduleDate = date('Y-m-d', strtotime('+ '.$i.' day', strtotime($bdate)));

					$update['scheduleYear'] = date('Y', strtotime($scheduleDate));
					$update['scheduleMonth'] = date('n', strtotime($scheduleDate));
					$update['scheduleWeek'] = date('W', strtotime($scheduleDate));

					$res = $con->query("
						update schedule sc, task, contract ctr, task_cat tcat
						set sc.schedule_date = ".$update['scheduleDate'].",
							sc.schedule_year = ".$update['scheduleYear'].",
							sc.schedule_month = ".$update['scheduleMonth'].",
							sc.schedule_week = ".$update['scheduleWeek']."
						where 
							sc.task_id = task.task_id
							and task.contract_id = ctr.contract_id
							and task.tcat_id = tcat.tcat_id
							and tcat.tcat_id = 1

							and ctr.contract_id = ".$contract_id."

							and sc.schedule_date = ".($lastStartDate + $i)."  
							

					");


				}
				
			}

			$data['code'] = 200;
	 		$data['msg'] = 'success';
	 		return $data;
	 	}else{
	 		$data['code'] = 302;//no contract id
	 		$data['msg'] = 'no contract id';
	 		return $data;
	 	}
	 	
	 }

	 

	  function getStartAndEndDate($week, $year) {
		  $dto = new DateTime();
		  $dto->setISODate($year, $week);
		  $ret['week_start'] = $dto->format('m-d-Y');
		  $dto->modify('+6 days');
		  $ret['week_end'] = $dto->format('m-d-Y');
		  return $ret;
		}

	  function permission() {
	    $this->permission_model->permission();

	  }
	  function permission_city() {
	    $this->permission_model->permission_city();

	  }
	  function permission_mem_sign() {
	    $this->permission_model->permission_mem_sign();

	  }


}


?>