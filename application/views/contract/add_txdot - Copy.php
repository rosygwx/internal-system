<?php $this->load->view('template/header.php');?>
<?php $this->load->view('template/sidebar.php');?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">

		#header {
			width: 100%;
			height: 50px;
		}

		#footer {
			width: 100%;
			height: 100px;
		}

		#sidebar {
			width: 15%;
			float: left;
		}

		#main {
			width: 85%;
			float: left;
		}

	</style>
	<script type="text/javascript">
		//Count task selection
		function countChecked() {
			var n = $(this).closest('tbody').find( "input:checked" ).length;
			$(this).closest('.panel').find('.panel-title').find('span').html(n);
			console.log(n);
		}; 

		//Check if decimal
		function isNumber(){
			 //isNum = /^\d+(\.\d+)?$/;
			 //alert('must be number');
			 //return value.replace(/[^\d\.]/g,'');
			 //var.replace(/[^0-9\.]+/g, '');
			 //console.log(value);
			 $onblurvalue = document.getElementById("vali_num");
			 $onblurvalue.value = $onblurvalue.value.replace(/[^\d.-]/g,'');
			 //return value.replace(/[^\d\.]/g,'');
		}

		//add/remove hwy
		$(function() {
	        var scntDiv = $('#p_scents');
	        var i = $('#p_scents p').size() + 1;
	        
	        $('#addScnt').live('click', function() {
                $('<p><label for="p_scnts"><input type="text" id="p_scnt" size="20" name="p_scnt_' + i +'" value="" placeholder="Input Value" /></label> <a href="#" id="remScnt">Remove</a></p>').appendTo(scntDiv);
                i++;
                return false;
	        });
	        
	        $('#remScnt').live('click', function() { 
                if( i > 2 ) {
                        $(this).parents('p').remove();
                        i--;
                }
                return false;
	        });
		});
	</script>
</head>

<body>
	<div class="container" id="main">
		<div class="panel-heading">
			<h3>Add Contract (TxDOT) </h3>
		</div>
		<div class="panel-body">
			<form action="<?php echo BASE_URL();?>/contract/add" method='post' class='form-horizontal' enctype='multipart/form-data'>
			<input type='hidden' name='backurl'  id='backurl' value='<?=$_SERVER['HTTP_REFERER']?>' ></input>
			<input type='hidden' name='company_id'  id='company_id' value='<?=$company['company_id']?>' ></input>
			<input type='hidden' name='type'  id='type' value='1' ></input>
				
				<div class="col-md-12">
					<h4 class="page-header" style="margin-top:10px;">I Contract Info</h4>
				</div>
				<!-- <div class="form-group">
					<label class="col-md-2 control-label">Order:</label>
					<div class="col-md-10">
						<input class="form-control" id="ordernumber" type="text" name="ordernumber" value='999'>
					</div>
				</div> -->

				<!--<label class="col-md-2 control-label"><a href="#" id="remScnt">Add a Highway</a></label>  -->
				
				<br>
				<div id="p_scents">
				    <p>
				    	<div class="form-group">
							<label class="col-md-2 control-label">Highway Name:</label>
							<div class="col-md-10">
								<input type="text" class="form-control J-presearch-hwy" name="hwy_id"  autocomplete="off" required>
							</div>
						</div>

				    	<div class="form-group">
							<label class="col-md-2 control-label">Centerline Mileage:</label>
							<div class="col-md-10">
								<input type="text" class="form-control" name="mileage"  autocomplete="off" required>
							</div>
						</div>

				    	<div class="form-group">
							<label class="col-md-2 control-label">Section:</label>
							<div class='col-md-5'>
								<!-- <label class="control-label" for="p_scnts">From: -->
						        	<input type="text" class="form-control" name="section_from" value="" placeholder="From... " />
						        <!-- </label> -->
			                </div>
			                <div class='col-md-5'>
								<!-- <label class="control-label" for="p_scnts">To: 11111-->
						        	<input type="text" class="form-control" name="section_from" value="" placeholder="To... " />
						        <!-- </label> -->
			                </div>
			                
						</div>
						
						<?php foreach ( $task_cat as $ktc => $vtc ) { ?>
				        <div class="form-group">
				        	
							<label class="col-md-2 control-label" style="" >Service - <?php if($ktc==1) echo 'Debris'; else echo 'Sweeping' ; ?>: </label>

							<div class="col-md-10">
								<label class="control-label"> Type: </label>
								<?php foreach($vtc as $vvtc) { ?>
									<label class="col-md-offset-1 checkbox-inline" style="">
										<span>
											<input type="checkbox" name="tcat_id" value="<?php echo $vvtc['tcat_id']; ?>" > 	<?php echo $vvtc['tcat_name']; ?>
										</span>
									</label>
								<?php } ?>
							</div>

							<div class="col-md-offset-2 col-md-10">
								<label class="control-label">Cycle:</label>
								<span><input type="text" class="form-control" name="cycle"  ></span>
							</div>	

							

							<div class="col-md-offset-2">
								<label class="col-md-10 " style="float:left;">Frequency:</label>
								<div class="col-md-3">
									<span><input type="text" class="form-control" name="fre_1" onkeyup="value=value.replace(/[^\d\.]/g,'')"  ></span>
								</div>
								<div class="col-md-2">
									<span style="font-size:18px;">times per</span>
								</div>
								<div class="col-md-3">
									<span><input type="text" class="form-control" name="fre_2" onkeyup="value=value.replace(/[^\d\.]/g,'')" ></span>
								</div>
								<div class="col-md-3">
									<span>
				                    	<select class="form-control" name="fre_3">
											<option value="1">Week</option>
											<option value="2">Month</option>
											<option value="3">Year</option>
										</select>
				                    </span>
								</div>
							</div>

							<div class="col-md-offset-2">
								<label class="col-md-10 " style="float:left;">Unit Price:</label>
								<div class="col-md-4">
									<span><input type="text" class="form-control" name="unit_price" onkeyup="value=value.replace(/[^\d\.]/g,'')"  ></span>
								</div>
								<div class="col-md-1">
									<span style="font-size:18px;">per</span>
								</div>
								<div class="col-md-4">
									<span><input type="text" class="form-control" name="unit" placeholder="Mile, acre... " ></span>
								</div>
								
							</div>


							<!-- <div class="col-md-offset-2  col-md-5">
								<label class="control-label">Unit Price:</label>
								<span><input type="text" class="form-control" name="unit_price" onkeyup="value=value.replace(/[^\d\.]/g,'')"  ></span>
							</div>	

							

							<div class="col-md-5">
								<label class="control-label">Per:</label>
								<span><input type="text" class="form-control" name="unit" placeholder="e.g.: mile, acre... " ></span>
							</div> -->

						<!-- 
							<div class="col-md-offset-2">
								<label class="control-label col-md-10 ">Frequncy:</label>
								<div class="col-md-3">
									<span><input type="text" class="form-control" name="fre_1"  ></span>
								</div>

								<div class="col-md-2">
									<label class="control-label">. </label>
									<span>times/</span>
								</div>

								<div class="col-md-2">
									<label class="control-label">.  </label>
									<span><input type="text" class="form-control" name="fre_2" ></span>
								</div>

								<div class="col-md-2">
									<label class="control-label"> . </label>
									<span>
				                    	<select class="form-control" name="fre_3">
											<option value="1">Week</option>
											<option value="2">Month</option>
											<option value="3">Year</option>
										</select>
				                    </span>
								</div> 
									
							</div>-->
						</div>


							
						<?php } ?> 
						


				        
				    </p>
				</div>














				<div class="form-group">
					<label class="col-md-2 control-label">Company:</label>
					<div class="col-md-10">
						<input type="text" class="form-control J-presearch-parts" name="company_name" placeholder="Company Name..." value="<?php echo $company['company_name'];?>" autocomplete="off" required>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Original Contract ID:</label>
					<div class="col-md-10">
						<input type="text" class="form-control J-presearch-parts" name="id_ori" autocomplete="off" required>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Pannell Contract ID:</label>
					<div class="col-md-10">
						<input type="text" class="form-control J-presearch-parts" name="id_pannell" placeholder="e.g.: DAL_Company Name_01" autocomplete="off" required>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Status</label>
					<div class=" col-md-4">
						<select class="form-control" name="status">
							<option value="0">Bidding</option>
							<option value="5">Bid Success-In Process</option>
							<option value="1">Bid Success-Done</option>
							<option value="4">Bid Fail</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Sign Date:<br>(If bid success)</label>
					<div class='col-md-10 date' id='datetimepicker1'>
	                    <input type='text' class="form-control" id='datetimepicker4' name="sign_date" />
	                </div>
				</div>

				<br>

				<!-- Task Info -->
				<div class="col-md-12">
					<h4 class="page-header" style="margin-top:10px;">II Task Info</h4>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Miles:</label>
					<div class="col-md-10">
						<input type="text" class="form-control J-presearch-parts" name="mile" required>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Cycle:</label>
					<div class="col-md-10">
						<input type="text" class="form-control J-presearch-parts" name="cycle" required>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Frequncy:</label>
					<div class='col-md-2'>
						<span>
	                    	<input class="form-control" name="fre_1" />
	                    </span>
	                </div>
	                <div class='col-md-1'>
	                	<span style="font-size:18px;">times / </span>
	                </div>
	                <div class='col-md-2'>
						<span>
	                    	<input class="form-control" name="fre_2" />
	                    </span>
	                </div>
	                <div class='col-md-2'>
						<span>
	                    	<select class="form-control" name="fre_3">
								<option value="1">Week</option>
								<option value="2">Month</option>
								<option value="3">Year</option>
							</select>
	                    </span>
	                </div>

	                
				</div>

				<div class="form-group form-ad">
					<label class="col-md-2 control-label">
						<i>*</i>Task Choices:<br>
						<span style="color:red">(Click to expand)</span>
					</label>
					<div class="col-md-10">
						<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
							<!--Debris-->
							<div class="panel panel-primary">
								<a style="text-decoration:none;" class="panel-primary" data-toggle="collapse" data-parent="#accordion" href="#ctr_debris" aria-expanded="true" aria-controls="ad_app" role="tab" id="headingApp">
									<div class="panel-heading"><h4 class="panel-title">Debirs Removal (selected <span>0</span>)</h4></div>
								</a>
								<div id="ctr_debris" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingApp">
									<div class="panel-body">
										<table class="table table-responsive">
											<thead>
												<tr>
													<th class="text-left">Type</th>
													<th class="text-left">Unit Price($)</th>
													<th>Selected</th>
												</tr>
											</thead>
											<tbody>
											
												<tr>
													<td>Type I&II</td>
													<td><input type='text' class="form-control col-md-2" name="unit_price_debris[]" onkeyup="value=value.replace(/[^\d\.]/g,'')"></td>
													<td class="text-center"><input class="form-control" type="checkbox" name="type_debris[]" value="8"></td>
												</tr>
												<tr>
													<td>Type III</td>
													<td><input type='text' class="form-control col-md-2" name="unit_price_debris[]" onkeyup="value=value.replace(/[^\d\.]/g,'')"></td>
													<td class="text-center"><input class="form-control" type="checkbox" name="type_debris[]" value="3"></td>
												</tr>
												<tr>
													<td>Type IV</td>
													<td><input type='text' class="form-control col-md-2" name="unit_price_debris[]" onkeyup="value=value.replace(/[^\d\.]/g,'')"></td>
													<td class="text-center"><input class="form-control" type="checkbox" name="type_debris[]" value="4"></td>
												</tr>
												<tr>
													<td>Type V</td>
													<td><input type='text' class="form-control col-md-2" name="unit_price_debris[]" onkeyup="value=value.replace(/[^\d\.]/g,'')"></td>
													<td class="text-center"><input class="form-control" type="checkbox" name="type_debris[]" value="5"></td>
												</tr>
												<tr>
													<td>Type VI</td>
													<td><input type='text' class="form-control col-md-2" name="unit_price_debris[]" onkeyup="value=value.replace(/[^\d\.]/g,'')"></td>
													<td class="text-center"><input class="form-control" type="checkbox" name="type_debris[]" value="6"></td>
												</tr>
												<tr>
													<td>Type VII</td>
													<td><input type='text' class="form-control col-md-2" name="unit_price_debris[]" onkeyup="value=value.replace(/[^\d\.]/g,'')"></td>
													<td class="text-center"><input class="form-control" type="checkbox" name="type_debris[]" value="7"></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>

							<!--Sweeping-->
							<div class="panel panel-primary">
								<a style="text-decoration:none;" class="panel-primary" data-toggle="collapse" data-parent="#accordion" href="#ctr_sweep" aria-expanded="true" aria-controls="ad_app" role="tab" id="headingApp">
									<div class="panel-heading"><h4 class="panel-title">Sweeping (selected <span>0</span>)</h4></div>
								</a>
								<div id="ctr_sweep" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingApp">
									<div class="panel-body">
										<table class="table table-responsive">
											<thead>
												<tr>
													<th class="text-left">Type</th>
													<th class="text-left">Unit Price($)</th>
													<th>Selected</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Type I</td>
													<td><input type='text' class="form-control col-md-2" name="unit_price_sweeping[]" onkeyup="value=value.replace(/[^\d\.]/g,'')"> </td>
													<td class="text-center"><input class="form-control" type="checkbox" name="type_sweep[]" value="1"></td>
												</tr>
												<tr>
													<td>Type II</td>
													<td><input type='text' class="form-control col-md-2" name="unit_price_sweeping[]" onkeyup="value=value.replace(/[^\d\.]/g,'')"></td>
													<td class="text-center"><input class="form-control" type="checkbox" name="type_sweep[]" value="2"></td>
												</tr>
												<tr>
													<td>Type I&II</td>
													<td><input type='text' class="form-control col-md-2" name="unit_price_sweeping[]" onkeyup="value=value.replace(/[^\d\.]/g,'')"></td>
													<td class="text-center"><input class="form-control" type="checkbox" name="type_sweep[]" value="8"></td>
												</tr>
												<tr>
													<td>Type III</td>
													<td><input type='text' class="form-control col-md-2" name="unit_price_sweeping[]" onkeyup="value=value.replace(/[^\d\.]/g,'')"></td>
													<td class="text-center"><input class="form-control" type="checkbox" name="type_sweep[]" value="3"></td>
												</tr>
												<tr>
													<td>Type IV</td>
													<td><input type='text' class="form-control col-md-2" name="unit_price_sweeping[]" onkeyup="value=value.replace(/[^\d\.]/g,'')"></td>
													<td class="text-center"><input class="form-control" type="checkbox" name="type_sweep[]" value="4"></td>
												</tr>
												<tr>
													<td>Type V</td>
													<td><input type='text' class="form-control col-md-2" name="unit_price_sweeping[]" onkeyup="value=value.replace(/[^\d\.]/g,'')"></td>
													<td class="text-center"><input class="form-control" type="checkbox" name="type_sweep[]" value="5"></td>
												</tr>
												<tr>
													<td>Type VI</td>
													<td><input type='text' class="form-control col-md-2" name="unit_price_sweeping[]" onkeyup="value=value.replace(/[^\d\.]/g,'')"></td>
													<td class="text-center"><input class="form-control" type="checkbox" name="type_sweep[]" value="6"></td>
												</tr>
												<tr>
													<td>Type VII</td>
													<td><input type='text' class="form-control col-md-2" name="unit_price_sweeping[]" onkeyup="value=value.replace(/[^\d\.]/g,'')"></td>
													<td class="text-center"><input class="form-control" type="checkbox" name="type_sweep[]" value="7"></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>

				<div class="form-group">
					<div class="col-md-12 text-center" >
						<input type='submit' class='btn btn-primary btn-submit' value='Submit'>
						<input type='button' onclick='history.go(-1)' class='btn btn-primary' value='Cancel'/>
					</div>
				</div>

				

			</form>
		</div>
	  
	  
	</div>

	<script type="text/javascript">
		/*$('.J-presearch-client').typeahead({
			items: 16,
			minLength:2,
			fitToElement:true,
			autoSelect:false,
			source: function(company, result) {

				$.ajax({

					url:"<?=BASE_URL()?>/client/lists_ajax",
					method:"get",
					data:{company:company},
					dataType:"json",
					success:function(data){
						console.log(1);
						result($.map(data, function(item){	
							console.log(1);
							var companyitem = item;
							return item;
						}));
					},
				});
			},
			afterSelect: function (companyitem) {
				console.log(1);
				//window.location.href='<?=BASE_URL()?>/part/searchByKeyword?keyword='+keyworditem;
			}
		});*/
        $(function () {
            $('#datetimepicker4').datetimepicker({
            	format:'MM-DD-YYYY'
            });
        });
	</script>

</body>
</html>