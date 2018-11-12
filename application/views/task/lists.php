<?php $this->load->view('template/header.php');?>
<?php $this->load->view('template/sidebar.php');?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript">
		$(function(){	  				
			//
			$('.btn-submit').on('click',function(e){
				var select = document.getElementById("type_mul");
			    var str = [];
			    for(i=0;i<select.length;i++){
			        if(select.options[i].selected){
			            str.push(select[i].value);
			        }
			    }
			    $("#type_mul").val(str);


			})
		});
			/*console.log($('#type').val());
			var arr = [];
			console.log($('#type').selectpicker('val', arr));
			console.log(arr);
			var type_mul = $('#type').val();
			document.getElementById("type").value = type_mul;*/

			
	        console.log(select);
	        console.log(str);
	    
		//multiple type
		//var arr = '';
		//$('#type').selectpicker('val', arr);
		//console.log(arr);

		//check all
		function check_all(obj,name) { 
		    var checkboxs = document.getElementsByName(name); 
		    for(var i=0;i<checkboxs.length;i++){
					checkboxs[i].checked = obj.checked;
			} 
		} 

		function changeall(e) {
			e.preventDefault(); 
			var i=0;
			var taskid_all = [];
			var taskid_all_status = $(this).data('status');
			$(".table-task input[name='checkall[]']").each(function(){
				if( this.checked == true) { 
					i=1;
					taskid_all.push($(this).val());
				}
			});
			if(i==0) { alert('At least check one.'); }
			else { 
				$.ajax({
			 		type: "POST",
			 		url: "<?=BASE_URL()?>/schedule/aaaaaaaaadd_week/",
					data: {"checkall":taskid_all,"status":taskid_all_status},
			 		success: function(msg){
			 			console.log(msg);
						window.location.reload();
					}
			 	}); 	
			}
		}
	</script>
</head>

<body>
	<div class="container" id="main">
		<div><h3>Task Lists</h3></div>
		<?php if($company_type == 1){?>
			<div style="display:inline-block;padding:5px;">
	  			<!-- <span><a class="btn btn-primary btn-sm" href="<?php echo BASE_URL();?>/task/add">&nbsp;Add task</a></span> -->
		  		
			</div>
		  
			<form class="form form-inline" action="" method='get'>	
			  	<div class="form-group">
					<select name='company_type' class="form-control">
						<option value='1' <?php if($search['company_type'] ==1){echo 'selected';}?>>TXDot</option>
						<option value='2' <?php if($search['company_type'] ==2){echo 'selected';}?>>Commercial</option>
					</select>
				</div>
				<div class="form-group">
					<input class="form-control" type='text' name='contract_name' value='<?php echo $search['contract_name'];?>' placeholder="Contract name...">
				</div> 
				<div class="form-group">
					<input class="form-control" type='text' name='hwy_id' value='<?php echo $search['hwy_id'];?>' placeholder="Highway name...">
				</div> 
				<!--<div class="form-group">
					<select name='ifschedule' class="form-control">
						<option value='' <?php if($search['ifschedule'] == '' ){echo 'selected';}?>>Scheduled/Unscheduled</option>
						<option value='1' <?php if($search['ifschedule'] ==1){echo 'selected';}?>>Scheduled</option>
						<option value='0' <?php if($search['ifschedule'] ==2){echo 'selected';}?>>Unscheduled</option>
					</select>
				</div> -->
				<div class="form-group">
					<select name='category' class="form-control">
						<option value='' <?php if($search['category'] == '' ){echo 'selected';}?>>All Cate</option>
						<option value='1' <?php if($search['category'] ==1){echo 'selected';}?>>Debris</option>
						<option value='2' <?php if($search['category'] ==2){echo 'selected';}?>>Sweeping</option>
						<option value='3' <?php if($search['category'] ==3){echo 'selected';}?>>TMA</option>
					</select>
				</div>
				<div class="form-group">
					<select name='orderby' class="form-control">
						<option value='' <?php if($search['orderby'] == 1 ){echo 'selected';}?>>Sort by Date</option>
						<option value='1' <?php if($search['orderby'] == 2){echo 'selected';}?>>Sort by HWY</option>
						<option value='2' <?php if($search['orderby'] == 3){echo 'selected';}?>>Sort by Type</option>
					</select>
				</div>
				<!-- <div class="form-group">
					<select multiple id="type_mul" name='type_mul' class="selectpicker form-control">
						<option value='' <?php if($search['type_mul'] == '' ){echo 'selected';}?>>All Type</option>
						<option value='1' <?php if(in_array(1, $search['type_mul'])){echo 'selected';}?>>Type I</option>
						<option value='2' <?php if(in_array(2, $search['type_mul'])){echo 'selected';}?>>Type II</option>
						<option value='8' <?php if(in_array(8, $search['type_mul'])){echo 'selected';}?>>Type I&II</option>
						<option value='3' <?php if(in_array(3, $search['type_mul'])){echo 'selected';}?>>Type III</option>
						<option value='4' <?php if(in_array(4, $search['type_mul'])){echo 'selected';}?>>Type IV</option>
						<option value='5' <?php if(in_array(5, $search['type_mul'])){echo 'selected';}?>>Type V</option>
						<option value='6' <?php if(in_array(6, $search['type_mul'])){echo 'selected';}?>>Type VI</option>
						<option value='7' <?php if(in_array(7, $search['type_mul'])){echo 'selected';}?>>Type VII</option>
					</select>
				</div> -->
				
				<button type="submit" class="btn btn-submit btn-primary btn-sm" onclick="getVal()">Search</button>
			</form>

			<hr>
	

	
			<table class='table table-hover table-task'>
			  <form  action="<?php echo BASE_URL();?>company/lists/" method='post'>
				<style>.table>thead>tr>th,.table>tbody>tr>td,.table>tfoot>tr>td{
						vertical-align: middle;
						}
				</style>
				<thead>
					<tr>
					  <!-- <th class="text-left"><input type="checkbox" onclick="check_all(this,'checkall[]')" >  </th> -->
					  <th>Contract</th>
					  <th>HWY</th>
					  <th>Category</th>
					  <th>Type</th>
					  <th>From</th>
					  <th>To</th>
					  <th>CNTR Mile</th>
					  <th>Cycle</th>
					  <!-- <th>Freq</th> -->
					  <!-- <th>Unit Price</th> -->
					  <!-- <th>Process</th> -->
					  <th>Opts</th>
					  <!--
					  <th>Status</th>
					  <th style='text-align: left'></th>-->
					  			  
					</tr>
				</thead>
				<tbody>
				<?php foreach($task as $k => $v){?>
					<tr >
						<!-- <td class="text-left"><input name="checkall[]" type="checkbox" value="<?=$pv->bd_id?>"></td>	 -->
						<td><?php echo $v->contract_id_pannell;?></td>					
						<td><?php echo $v->hwy_id;?></td>

						<?php if($v->category==1){ ?>
							<td>Debris</td>
						<?php }elseif($v->category==2){ ?>
							<td>Sweeping</td>
						<?php } ?>	

						<td><?php echo $v->tcat_name;?></td>
						<td style="max-width:90px"><?php echo $v->section_from;?></td>
						<td style="max-width:90px"><?php echo $v->section_to;?></td>
						<td><?php echo $v->mile;?></td>
						<td><?php echo $v->cycle;?></td>
						<!-- <td><?php echo $v->fre;?></td> -->
						<!-- <td><?php echo $v->unit_price;?></td> -->
						<!-- <td><?php echo $v->process;?></td> -->

										
						<!--
						<?php if($v->status==0){ ?>
							<td style="color:#999;font-weight:700;">Bidding</td>
						<?php }elseif($v->status==1){ ?>
							<td style="color:#5cb85c;font-weight:700;">Active</td>
						<?php }elseif($v->status==2){ ?>
							<td style="color:#999;font-weight:700;">Complete</td>
						<?php }elseif($v->status==3){ ?>
							<td style="color:#999;font-weight:700;">Terminate</td>
						<?php } ?>
						-->

						<td class="text-left" style="position: relative; ">
							
							<!-- <a href="<?php echo BASE_URL();?>company/view/?contract_id=<?=$v->contract_id?>">View </a> 
							<a href="<?php echo BASE_URL();?>company/update/?contract_id=<?=$v->contract_id?>">Modify </a> -->
							
							<a href="<?php echo BASE_URL();?>schedule/lists/?task_id=<?php echo $v->task_id; ?>">Schedule </a>
							<a href="<?php echo BASE_URL();?>schedule/add/?id=<?php echo $v->task_id; ?>" style="padding-left: 3%;">AddSchedule </a>

							
							<!-- <a onclick='return confirm("Confirm to delete?")' href="<?php echo BASE_URL();?>company/delete/?company_id=<?=$v->company_id?>">Delete</a>  -->
						</td>	
					 
					</tr>
					<?php }?>
				</tbody>
				
			  </form>
			</table>

		<?php }else{ ?>
			<form class="form form-inline" action="" method='get'>	
			  	<div class="form-group">
					<select name='company_type' class="form-control">
						<option value='1' <?php if($search['company_type'] ==1){echo 'selected';}?>>TXDot</option>
						<option value='2' <?php if($search['company_type'] ==2){echo 'selected';}?>>Commercial</option>
					</select>
				</div>
				<div class="form-group">
					<input class="form-control" type='text' name='contract_name' value='<?php echo $search['contract_name'];?>' placeholder="Task name...">
				</div> 
				<!-- 
				<div class="form-group">
					<select name='category' class="form-control">
						<option value='' <?php if($search['category'] == '' ){echo 'selected';}?>>All Cate</option>
						<option value='1' <?php if($search['category'] ==1){echo 'selected';}?>>Debris</option>
						<option value='2' <?php if($search['category'] ==2){echo 'selected';}?>>Sweeping</option>
						<option value='3' <?php if($search['category'] ==3){echo 'selected';}?>>TMA</option>
					</select>
				</div>
				<div class="form-group">
					<select name='orderby' class="form-control">
						<option value='' <?php if($search['orderby'] == 1 ){echo 'selected';}?>>Sort by Date</option>
						<option value='1' <?php if($search['orderby'] == 2){echo 'selected';}?>>Sort by HWY</option>
						<option value='2' <?php if($search['orderby'] == 3){echo 'selected';}?>>Sort by Type</option>
					</select>
				</div>
				<div class="form-group">
					<select multiple id="type_mul" name='type_mul' class="selectpicker form-control">
						<option value='' <?php if($search['type_mul'] == '' ){echo 'selected';}?>>All Type</option>
						<option value='1' <?php if(in_array(1, $search['type_mul'])){echo 'selected';}?>>Type I</option>
						<option value='2' <?php if(in_array(2, $search['type_mul'])){echo 'selected';}?>>Type II</option>
						<option value='8' <?php if(in_array(8, $search['type_mul'])){echo 'selected';}?>>Type I&II</option>
						<option value='3' <?php if(in_array(3, $search['type_mul'])){echo 'selected';}?>>Type III</option>
						<option value='4' <?php if(in_array(4, $search['type_mul'])){echo 'selected';}?>>Type IV</option>
						<option value='5' <?php if(in_array(5, $search['type_mul'])){echo 'selected';}?>>Type V</option>
						<option value='6' <?php if(in_array(6, $search['type_mul'])){echo 'selected';}?>>Type VI</option>
						<option value='7' <?php if(in_array(7, $search['type_mul'])){echo 'selected';}?>>Type VII</option>
					</select>
				</div> -->
				
				<button type="submit" class="btn btn-submit btn-primary btn-sm" onclick="getVal()">Search</button>
			</form>

			<hr>
			<table class='table table-hover table-task'>
			  <form  action="<?php echo BASE_URL();?>company/lists/" method='post'>
				<style>.table>thead>tr>th,.table>tbody>tr>td,.table>tfoot>tr>td{
						vertical-align: middle;
						}
				</style>
				<thead>
					<tr>
					  <!-- <th class="text-left"><input type="checkbox" onclick="check_all(this,'checkall[]')" >  </th> -->
					  <th>Task</th>
					  <th>POC</th>
					  <th>PO#</th>
					  <th>Opts</th>
					  <!--
					  <th>Status</th>
					  <th style='text-align: left'></th>-->
					  			  
					</tr>
				</thead>
				<tbody>
				<?php foreach($task as $k => $v){?>
					<tr >
						<!-- <td class="text-left"><input name="checkall[]" type="checkbox" value="<?=$pv->bd_id?>"></td>	 -->
						<td><?php echo $v->contract_id_pannell;?></td>					
						<td><?php echo $v->poc;?></td>
						<td><?php echo $v->pon;?></td>
										
						<!--
						<?php if($v->status==0){ ?>
							<td style="color:#999;font-weight:700;">Bidding</td>
						<?php }elseif($v->status==1){ ?>
							<td style="color:#5cb85c;font-weight:700;">Active</td>
						<?php }elseif($v->status==2){ ?>
							<td style="color:#999;font-weight:700;">Complete</td>
						<?php }elseif($v->status==3){ ?>
							<td style="color:#999;font-weight:700;">Terminate</td>
						<?php } ?>
						-->

						<td class="text-left" style="position: relative; ">
							
							<!-- <a href="<?php echo BASE_URL();?>company/view/?contract_id=<?=$v->contract_id?>">View </a> 
							<a href="<?php echo BASE_URL();?>company/update/?contract_id=<?=$v->contract_id?>">Modify </a> -->
							
							<a href="<?php echo BASE_URL();?>schedule/add/?id=<?php echo $v->task_id; ?>">AddSchedule </a>
							
							<!-- <a onclick='return confirm("Confirm to delete?")' href="<?php echo BASE_URL();?>company/delete/?company_id=<?=$v->company_id?>">Delete</a>  -->
						</td>	
					 
					</tr>
					<?php }?>
				</tbody>
				
			  </form>
			</table>


		<?php } ?>

	




	<div class="pull-right"> 
		<?php $this->load->view('template/page.php');?>     
	</div>

	<!-- <span class="text-left">
			<a class="btn btn-info btn-sm btn-changeall" href="#" data-status="1">Schedule All</a>
			<a class="btn btn-info btn-sm btn-changeall" href="#" data-status="2">全部拒绝</a>
	</span> -->
	</div>

</body>
</html>