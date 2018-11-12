<?php $this->load->view('template/header.php');?>
<?php $this->load->view('template/sidebar.php');?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript">
		/*$('#setUp').on('click', function (event) {
			console.log(event);
		      var btnThis = $(event.relatedTarget); 
		      var modal = $(this); 
		      var modalId = btnThis.data('id');   
		      var content = btnThis.closest('tr').find('td').eq(2).text();
		      modal.find('.content').val(content);         
		});*/

		$(function () {
			$('.datetimepicker').datetimepicker({
            	format:'MM/DD/YYYY'
            });  

            $('.datetimepicker4').datetimepicker({
            	format:'MM/DD/YYYY'
            });

            $('.datetimepicker_start').datetimepicker({
            	format:'HH:mm',
            	
            });
        
            $('.datetimepicker_end').datetimepicker({
            	format:'HH:mm'
            });

            $('.datetimepicker_start_search').datetimepicker({
            	format:'MM/DD/YYYY'
            })
            /*.on("click",function(){  
	            $(".datetimepicker_start_search").datetimepicker("setEndDate",$(".datetimepicker_end_search").val());  
	        })*/
	        ;
        
            $('.datetimepicker_end_search').datetimepicker({
            	format:'MM/DD/YYYY',
            	useCurrent: false, 
            })
            /*.on("click",function(){  
	            $(".datetimepicker_end_search").datetimepicker("setStartDate",$(".datetimepicker_start_search").val());  
	        })*/
	        ;

	        $(".datetimepicker_start_search").on("dp.change", function (e) {
              $('.datetimepicker_end_search').data("DateTimePicker").minDate(e.date); 
           });        
          $(".datetimepicker_end_search").on("dp.change", function (e) {
              $('.datetimepicker_start_search').data("DateTimePicker").maxDate(e.date);        
          });

            
        });

        //check all
		function check_all(obj,name) { 
		    var checkboxs = document.getElementsByName(name); 
		    for(var i=0;i<checkboxs.length;i++){
					checkboxs[i].checked = obj.checked;
			} 
			console.log(checkboxs);
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
			 		url: "<?=BASE_URL()?>schedule/aaaaaaaaadd_week/",
					data: {"checkall":taskid_all,"status":taskid_all_status},
			 		success: function(msg){
			 			console.log(msg);
						window.location.reload();
					}
			 	}); 	
			}
		}

		function printTicket(e) {
			//e.preventDefault(); 
			//var taskid_all_status = $(this).data('status');
			var i=0;
			var id_all = [];

			$(document.getElementsByName("checkall[]")).each(function(){
				if( this.checked == true) { 
					i=1;
					id_all.push($(this).val());
				}
			});
			
			if(i==0) { alert('At least check one.'); return false;} else return true;
			/*else { 
				$.post("<?=BASE_URL()?>schedule/printTicket/", function(id_all){
					console.log(1);
					alert
				});
				$.ajax({
			 		type: "POST",
			 		url: "<?=BASE_URL()?>schedule/printTicket/",
					data: {"checkall":id_all}
			 		success: function(msg){
			 			console.log(msg);
						window.location.reload();
					}
			 	}); 	
			}*/
		}

		function editInfo(obj) {  
		    //var id = $(obj).attr("id");  
		    //获取表格中的一行数据  
		   /* console.log(document.getElementById("editInfo").data);
		    var stuno = document.getElementById("editInfo").rows[id].cells[0].value;  
		    var pass = document.getElementById("editInfo").rows[id].cells[1].value;  
		    var name = document.getElementById("editInfo").rows[id].cells[2].value;  
		    var sex = document.getElementById("editInfo").rows[id].cells[3].value;  
		    //向模态框中传值  
		    $('#stuno').val(stuno);  
		    $('#pass').val(pass);  
		    $('#stuname').val(name);  
		    if (sex == '女') {  
		        document.getElementById('women').checked = true;  
		    } else {  
		        document.getElementById('man').checked = true;  
		    }  
		    $('#update').modal('show');  */
		    /*$('#myModal').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget);
				console.log(button);
				var id = button.data('id');
				var truck = button.data('truck');
				var employee = button.data('employee');
				var modal = $(this);
				modal.find('#description').text(description);
				modal.find('#remedy').text(remedy);
			});*/

		}  

		/*//提交更改  
		function update() {  
		    //获取模态框数据  
		    var stuno = $('#stuno').val();  
		    var pass = $('#pass').val();  
		    var name = $('#stuname').val();  
		    var sex = $('input:radio[name="sex"]:checked').val();  
		    $.ajax({  
		        type: "post",  
		        url: "update.do",  
		        data: "stuno=" + stuno + "&pass=" + pass + "&name=" + name + "&sex=" + sex,  
		        dataType: 'html',  
		        contentType: "application/x-www-form-urlencoded; charset=utf-8",  
		        success: function(result) {  
		            //location.reload();  
		        }  
		    });  
		}  */


		$(document).on("click", ".open-AddDialog", function () {

		     var id = $(this).data('id');
		     var employee = $(this).data('employee');
		     var btime = $(this).data('btime');
		     var etime = $(this).data('etime');
		     var date = $(this).data('date');
		     var contract = $(this).data('contract');
		     var location = $(this).data('location');
		     $(".modal-body #id").val( id );
		     $(".modal-body #employee").val( employee );
		     $(".modal-body #btime").val( btime );
		     $(".modal-body #etime").val( etime );
		     $(".modal-body #date").val( date );
		     $(".modal-body #contract").val( contract );
		     $(".modal-body #location").val( location );
		     console.log(location);
		});

		$(document).on("click", ".complete", function () {
			var id = $(this).attr('scid');
			var status = $(this).attr('status');
			if (status == 1) {
				status = 0
			} else {
				status = 1
			}	
			console.log(status);
			$.ajax({
		        url: "<?php echo BASE_URL();?>schedule/complete/",
		        type: 'post',
		        crossDomain: true,
		        dataType: 'json',
		        data: {id:id, status:status, ajax:1}, 
		        success: function(r) {
					console.log(r);
		            if(r){
						location.reload();
					}
		        },
		        error: function(xhr, desc, err) {
		            console.log(xhr);
		            console.log("Details: " + desc + "\nError:" + err);
		        }
		    }); // end ajax call

		});

		$(function () {
			$(".delete").on('click', function(){
				if(confirm('Are you sure to delete this schedule? Its irrevocable.')){
					var id = $(this).attr('scid');

					$.ajax({
				        url: "<?php echo BASE_URL();?>schedule/delete/",
				        type: 'post',
				        crossDomain: true,
				        dataType: 'json',
				        data: {id:id, ajax:1}, 
				        success: function(r) {
				        	console.log(r);
				            if(r){
								location.reload();
							}
				        },
				        error: function(xhr, desc, err) {
				            console.log(xhr);
				            console.log("Details: " + desc + "\nError:" + err);
				        }
				    }); // end ajax call
				}		
			});
		});

	</script>
</head>

<body>
	<div class="container" id="main">
		<div style="display:inline-block;padding:5px;">
			<span><h3>Schedule Lists (Commercial)</h3></span>
	  		<!-- <span><a class="btn btn-primary btn-sm" href="<?php echo BASE_URL();?>task/add">&nbsp;Add task</a></span> -->
		</div>
	  
	  <!-- model start -->
		
		<div class="modal fade" id="addDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<form action="<?php echo Base_URL(); ?>schedule/update/testttttt" method="POST">
			    <div class="modal-dialog modal-lg">
			        <div class="modal-content">
			            <div class="modal-header">
			                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			                <h4 class="modal-title">Set Up Schedule</h4>
			            </div>

			            <div class="modal-body" style="margin-top: 10px;"> 
		                    <input class="form-control" type="hidden" id="id" name="schedule_id" placeholder="Shcedule ID..." value="">
		                    
		                    <div class="form-group">
		                        <label class="col-md-3">Contract Name</label>
		                        <div class="col-md-9">
		                         	<input class="form-control" type="text" id="contract"  name="contract"  value=""  disabled>
		                        </div>
		                    </div>
		                    <div class="form-group">
								<label class="col-md-3">Location:</label>
								<div class="col-md-9">
									<input class="form-control" type="text" id="location" name="location" value="">
								</div>
							</div>
		                    <div class="form-group">
		                        <label class="col-md-3">Complete Date</label>
		                        <div class="col-md-9">
		                         	<input class="form-control datetimepicker4" type="text" id="date"  name="date"  value="" placeholder="Date...">
		                        </div>
		                    </div>
		                    <div class="form-group">
		                        <label class="col-md-3">Start Time</label>
		                        <div class="col-md-9">
		                         	<input class="form-control datetimepicker_start" type="text" id="btime"  name="btime"  value="" placeholder="Begin Time...">
		                        </div>
		                    </div>
		                    <div class="form-group">
								<label class="col-md-3 ">End Time:</label>
								<div class="col-md-9">
				                    <input class="form-control datetimepicker_end" type="text" id="etime" name="etime" value="" placeholder="End Time...">
				                </div> 
							</div>
							<div class="form-group">
								<label class="col-md-3">Options:</label>
								<div class='col-md-9'>
									<div class='col-md-4 checkbox'>
										<input type='checkbox' id="iftraffic" name="iftraffic" value="1">Traffic Control
									</div>
									<div class='col-md-4 input-group'>
										<span class="input-group-addon">$</span>
										<input type='text' class="form-control" name="traffic_charge" name="traffic_charge" value="" >
									</div>
				                    <div class='col-md-4 checkbox' >
										<input type='checkbox' id="ifdisposal" name="ifdisposal" value="1"  >Disposal
									</div>
									<div class='col-md-4 input-group'>
										<span class="input-group-addon">$</span>
										<input type='text' class="form-control" id="disposal_charge" name="disposal_charge" value="">
									</div>
				                </div> 
							</div>
		                    <input type="hidden" type="text" name="backurl" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
			            	
			        	</div>

				        <div class="modal-footer text-center">    
				           <input type="submit" class="btn btn-default btn_blue" value="Submit">
				           <button type="button" class="btn btn-default btn_red" data-dismiss="modal">Cancel</button>
				        </div>
			        </div>
			   </div>
			</form>
		</div>
		<!-- model end -->

	  <form class="form form-inline" action="" method='get'>
	    <!-- <div class="form-group">
			<select name='company_type' class="form-control">
				<option value='1' <?php if($search['company_type'] == 1 ){echo 'selected';}?>>TxDOT</option>
				<option value='2' <?php if($search['company_type'] == 2){echo 'selected';}?>>Commercial</option>
			</select>
		</div>	 -->
		<div class="form-group">
			<!-- <label for="title">Company Name:</label> -->
			<input class="form-control" type='text' name='company' value='<?php echo $search['company'];?>' placeholder="Company name...">
		</div> 

		
		<div class="form-group" style="position:relative">
            <div>
                <input type="text" class="form-control datetimepicker_start_search" name="bdate" id="bdate" value='<?php echo $search['bdate'];?>' placeholder="Date From..." autocomplete="off">
            </div>
        </div>
        <div class="form-group" style="position:relative">
            <div>
                <input type="text" class="form-control datetimepicker_end_search" name="edate" id="edate" value='<?php echo $search['edate'];?>' placeholder="Date To..." autocomplete="off">
            </div>
        </div>
    
		<!-- <div class="form-group">
			<select name='period' class="form-control">
				<option value='' <?php if($search['period'] == '' ){echo 'selected';}?>>All Period</option>
				<option value='1' <?php if($search['period'] ==1){echo 'selected';}?>>Current Month</option>
				<option value='2' <?php if($search['period'] ==2){echo 'selected';}?>>Current Week</option>
				<option value='3' <?php if($search['period'] ==3){echo 'selected';}?>>Current Day</option>
			</select>
		</div>
		
		<div class="form-group">
			<select name='status' class="form-control">
				<option value='' <?php if($search['status'] == '' ){echo 'selected';}?>>All Status</option>
				<option value='1' <?php if($search['status'] == 1){echo 'selected';}?>>Completed</option>
				<option value='0' <?php if($search['status'] === 0 ){echo 'selected';} ?>>Incompleted</option>
				<option value='2' <?php if($search['status'] == 2){echo 'selected';}?>>Cancelled</option> 
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
		<!-- <input type="button" class="btn btn-submit btn-primary btn-sm" onclick="printTicket()" value="Print Tickets"></input> -->

	</form>


	<hr>
	
		<table class='table table-hover'>
		
		  <form  action="<?php echo BASE_URL();?>schedule/printTicket/" method='post' onsubmit="return printTicket();">
			<style>.table>thead>tr>th,.table>tbody>tr>td,.table>tfoot>tr>td{
					vertical-align: middle;
					}
			</style>
			<input type="hidden" name="company_type" value="2"></input>
			<thead>
				<tr>
				  <th class="text-left"><input type="checkbox" onclick="check_all(this,'checkall[]')" >  </th>
				  <th>ScheduleDate</th>
				  <th>Start</th>
				  <th>End</th>
				  <th>Company</th>
				  <th>Location</th>
				  <th>Ticket</th>
				  <th>Driver</th>
				  <th>Truck</th> 
				  <th>Opts</th>
				  <!-- <th>Price</th> -->
				  
				  <!-- <th>Start Time</th>
				  <th>End Time</th>
				  
				 
				  
				  <th>Status</th>
				  <th style='text-align: left'></th>-->
				  			  
				</tr>
			</thead>
			<tbody>
			<?php foreach($schedule as $k => $v){?>
				<?php if($v->status==0){ ?>
					<!-- <tr class="warning"> -->
					<!-- <tr class="info"> -->
				<?php }elseif($v->status==1){ ?>
					<tr class="success">
				<?php }elseif($v->status==2){ ?>
					<tr class="warning">
				<?php }elseif($v->status==3){ ?>
					<tr class="danger">
				<?php }else{ ?>
					<tr>
				<?php }?>
					<td class="text-left"><input name="checkall[]" type="checkbox" value="<?=$v->schedule_id?>"></td>	
					<td><?php echo $v->schedule_period;?></td>
					<td><?php if($v->btime)echo date('H:iA', strtotime($v->btime));else echo '-';?></td>
					<td><?php if($v->etime)echo date('H:iA', strtotime($v->etime));else echo '-';?></td>							
					<td><?php echo $v->contract_id_pannell?></td>					
					<td><?php echo $v->location?></td>
					<td style="width:6%"><?php if($worklog[$v->schedule_id]){
							foreach($worklog[$v->schedule_id]['ticket_id'] as $wl){?>
							<span>
								<?php echo $wl; ?>
							</span> 
							<?php }
						}else{?>
							<span>
								<?php echo '-'; ?>
							</span> 
							<?php } ?></td>
					<td style="width:7%"><?php if($worklog[$v->schedule_id]){
							foreach($worklog[$v->schedule_id]['employee_id'] as $wl){?>
								<span>
									<?php if($wl)  echo $wl; else echo '-'; ?>
								</span> 
							<?php }
							}else{?>
								<span>
									<?php echo '-'; ?>
								</span> 
								<?php } ?></td>
					<td style="width: 8%;"><?php if($worklog[$v->schedule_id]){
							foreach($worklog[$v->schedule_id]['truck_id'] as $wl){?>
								<span>
									<?php if($wl)  echo $wl; else echo '-'; ?>
								</span><br> 
								<?php }
							}else{?>
								<span>
									<?php echo '-'; ?>
								</span> 
								<?php } ?></td>

					
					
					<!-- <td><?php echo $v->unit_price;?></td> -->
					
					<!-- <td><?php echo $v->btime;?></td>
					<td><?php echo $v->etime;?></td>
					<td><?php foreach($v->driver as $kdr=>$vdr){ ?>
						<a href="<?=BASE_URL()?>/employee/lists/?id=<?=$kdr?>"> <?php echo $vdr; }?> </a></td>
					<td><?php foreach($v->truck as $ktr=>$vtr){ ?>
						<a href="<?=BASE_URL()?>/truck/lists/?id=<?=$ktr?>"> <?php echo $vtr; }?> </a></td> -->

					<td class="text-left" style="position: relative; ">
						
						<!-- <a href="<?php echo BASE_URL();?>company/view/?contract_id=<?=$v->contract_id?>">View </a> 
						<a href="<?php echo BASE_URL();?>company/update/?contract_id=<?=$v->contract_id?>">Modify </a> -->
						<!-- <?php if($v->status===0){ ?>
							<a href="<?php echo BASE_URL();?>schedule/update/?id=<?php echo $v->schedule_id; ?>">Setup </a>
						<?php }elseif(in_array($v->status, [1,3])){ ?>
							<a href="<?php echo BASE_URL();?>schedule/add/?id=<?php echo $v->task_id; ?>">EndTime</a>
						<?php } ?> -->
						<!-- <a onclick='return confirm("Confirm to delete?")' href="<?php echo BASE_URL();?>company/delete/?company_id=<?=$v->company_id?>">Delete</a>  -->

						<!-- <a class="open-AddDialog " data-toggle="modal"  data-id="<?php echo $v->schedule_id;?>" data-employee="<?php echo $v->employeejs;?>" data-btime="<?php echo date('H:i',strtotime($v->btime));?>" data-etime="<?php echo date('H:i',strtotime($v->etime));?>" data-date="<?php echo $v->date;?>" data-contract="<?php echo $v->contract_id_pannell;?>" data-location="<?php echo $v->location;?>" data-location="<?php echo $v->location;?>" href="#addDialog">Setup</a> -->

						<a href="<?php echo BASE_URL();?>schedule/updateCom/?id=<?=$v->schedule_id?>">Update </a> 

						
						<a class="complete" scid="<?php echo $v->schedule_id?>" status="<?php echo $v->status?>"  href="javascript:void(0)"> <?php if($v->status == 0)echo 'Complete';else echo 'Incomplete';?></a> 

						<a class="delete" scid="<?php echo $v->schedule_id?>" href="javascript:void(0)"> Delete</a> 

						
					</td>	
				 
				</tr>
				<?php }?>
			</tbody>
				
				<!-- <button type="button" class="btn btn-submit btn-primary btn-sm" onclick="printTicket()" value="">Print Ticketsss</button> -->
				<input type='submit' class='btn btn-primary btn-submit'  value='Print Tickets'>

			</form>
		</table>
		<div>
			*Background color: <strong>White:</strong> Incomplete; <strong>Green:</strong> Complete; <strong>Yellow:</strong> Cancel-Bill; <strong>Red:</strong> Cancel-Not Bill.
		</div>
	

	<div class="pull-right"> 
		<?php $this->load->view('template/page.php');?>     
	</div>

	</div>

</body>
</html>