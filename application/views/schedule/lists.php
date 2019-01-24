<?php $this->load->view('template/header.php');?>
<?php $this->load->view('template/sidebar.php');?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		td {
			font-size: 15px;
		}
	</style>
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
	</script>
</head>

<body>
	<div class="container" id="main">
		<div style="display:inline-block;padding:5px;">
			<span><h3>Schedule Lists (TxDOT)</h3></span>
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
		</div> -->
		<div class="form-group" style="position:relative">
			<select name='contract_id' class="form-control">
				<option value='' <?php if($search['contract_id'] == '' ){echo "selected";}?>> Select Contract </option>
				<?php foreach($contract as $ckey=>$cname){?>
				<option value=<?php echo $ckey;?> <?php if($search['contract_id'] == $ckey ){echo "selected";}?>> <?php echo $cname;?> </option>
				<?php } ?>
			</select>
		</div> 	
		<div class="form-group" style="position:relative">
            <div>
                <input type="text" class="form-control datetimepicker_start_search" name="bdate" id="bdate" value='<?php echo $search['bdate'];?>' placeholder="From..." autocomplete="off">
            </div>
        </div>
        <div class="form-group" style="position:relative">
            <div>
                <input type="text" class="form-control datetimepicker_end_search" name="edate" id="edate" value='<?php echo $search['edate'];?>' placeholder="To..." autocomplete="off">
            </div>
        </div>
		
		<div class="form-group">
			<select name='category' class="form-control">
				<option value='0' <?php if($search['category'] == 0 ){echo 'selected';}?>>Debris & Sweeping</option>
				<option value='1' <?php if($search['category'] == 1 ){echo 'selected';}?>>Debris</option>
				<option value='2' <?php if($search['category'] == 2){echo 'selected';}?>>Sweeping</option>
			</select>
		</div> 
		
		
		
		<button type="submit" class="btn btn-submit btn-primary btn-sm" onclick="getVal()">Search</button>
		<!-- <input type="button" class="btn btn-submit btn-primary btn-sm" onclick="printTicket()" value="Print Tickets"></input> -->

	</form>


	<hr>
	
		<table class='table table-hover table-checkbox'>
		  <form  action="<?php echo BASE_URL();?>schedule/printTxdotWork/" method='post'>
			<style>.table>thead>tr>th,.table>tbody>tr>td,.table>tfoot>tr>td{
					vertical-align: middle;
					}
			</style>
			<input type="hidden" name="company_type" value="1"></input>
			<thead>
				<tr>
				  <th class="text-left"><input type="checkbox" onclick="check_all(this,'checkall[]')" >  </th>
				  <th style="width:10%">Schedule Date</th>
				  <th style="width:15%">Contract</th>
				  <th style="width:10%">Category</th>
				  <th style="width:160px">Actual Mileage</th>
				  <th style="width:200px">Task</th>
				  <th style="width:250px">Driver</th>
				  <th style="width:100px">Truck</th>
				  
				  <!-- <th>Start Time</th>
				  <th>End Time</th>
				  <th>Driver</th>
				  <th>Truck</th> -->
				  <th>Opts</th>
				  <!--
				  <th>Status</th>
				  <th style='text-align: left'></th>-->
				  			  
				</tr>
			</thead>
			<tbody>
			<?php foreach($schedule as $date => $scv){?>
				<?php foreach($scv as $contract_id => $scvv){?>
					<?php foreach($scvv as $category => $scvvv){?>
						<?php if($scvvv['status']===0){ ?>
							<!--<tr class="warning">-->
							<!-- <tr class="info"> -->
						<?php }elseif($scvvv['status']==1){ ?>
							<tr class="success">
						<?php }elseif($scvvv['status']==2){ ?>
							<tr class="danger">
						<?php }else{ ?>
							<tr>
						<?php }?>

							<td class="text-left"><input name="checkall[]" type="checkbox" value="<?php echo $contract_id.'#'.$category.'#'.$date?>"></td>

							<td  style="width:10%"><?php echo date('m/d/Y', strtotime($date));?></td>	
											
							<td  style="width:15%"><?php echo $contract[$contract_id];?></td>

							<td  style="width:10%"><?php echo $category==1?'Debris':'Sweeping';?></td>



							<td colspan="4">
								<table rules="rows" >
									<?php foreach($scvvv['crew'] as $scvvvk=>$crew) {?>
	        							<tr>
	        								<td style="width:160px;  padding: 8px"><?php echo $crew['all_mile'];?></td>
	        							
	        								<td style="width:200px">
												<?php //version 2
												foreach($crew['task'] as $hwy_id => $types){ 
												 	echo $hwy_id.': '; 
												 	$typpee = '';
													foreach($types as $type){
														$typpee .= $type.', ';
													}
													
													echo trim($typpee, ', ').'<br>';
												}?>

											</td>
											<td style="width:250px">
												<?php if($crew['employee']){
														foreach( $crew['employee'] as $employee){
															echo $employee.'<br>';
														}
													}else{
														echo '-';
													}
												?>	
											</td>
											<td style="width:100px">
												<?php if($crew['truck']){
														foreach( $crew['truck'] as $truck){
															echo $truck.'<br>';
														}
													}else{
														echo '-';
													}
												?>
											</td>
										</tr>
	        						<?php } ?>
        						</table>
        					</td>
								

								


							


							<td>
								<!-- <a class="open-AddDialog " data-toggle="modal"  data-id="<?php  ?>" data-employee="<?php  ?>" data-btime="<?php ?>" data-etime="<?php ?>" data-date="<?php ?>"  data-contract="<?php ?>" data-location="<?php ?>"  href="#addDialog">Setup</a> -->
								<!-- <a href="<?php echo BASE_URL();?>schedule/update/?id=<?php echo $contract_id?>&date=<?php echo $date?>&cat=<?php echo $category?>">Update </a>  -->
								<a href="<?php echo BASE_URL().'schedule/update/?id='.$contract_id.'&date='.$date.'&cat='.$category; ?>">Update </a> 

							</td>
						</tr>
					<?php } ?>
				<?php } ?>
			<?php } ?>



			</tbody>

			<input type='submit' class='btn btn-primary btn-submit'  value='Print Working Report'>
			</form>
		</table>
	


	<div class="pull-right"> 
		<?php $this->load->view('template/page.php');?>     
	</div>

	</div>

</body>
</html>