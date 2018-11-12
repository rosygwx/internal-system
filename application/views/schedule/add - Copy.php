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

	<script>
    //Check if decimal
		function isNumber(){
			 $onblurvalue = document.getElementById("vali_num");
			 $onblurvalue.value = $onblurvalue.value.replace(/[^\d.-]/g,'');
		}
        
    </script>
    
</head>

<body>
	<div class="container" id="main">
		<div class="panel-heading">
			<h3>Add Task (TxDOT)</h3>
		</div>
		<div class="panel-body">
			<form action="<?php echo BASE_URL();?>/task/add" method='post' class='form-horizontal' enctype='multipart/form-data'>
			<input type='hidden' name='backurl'  id='backurl' value='<?=$_SERVER['HTTP_REFERER']?>' ></input>
				
				

			<!-- <div class="form-group">
					<label class="col-md-2 control-label"><a href="#" id="addScnt">Add a Highway</a></label>
			<div class="col-md-10"> -->
						
					<!-- </div>
				</div> -->
				<!--<label class="col-md-2 control-label"><a href="#" id="remScnt">Add a Highway</a></label>  -->
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
								<input type="text" class="form-control" name="mileage" onkeyup="value=value.replace(/[^\d\.]/g,'')" autocomplete="off" required>
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
						        	<input type="text" class="form-control" name="section_to" value="" placeholder="To... " />
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
											<input type="checkbox" name="tcat_id_<?php echo $ktc; ?>[]" value="<?php echo $vvtc['tcat_id']; ?>" > 	<?php echo $vvtc['tcat_name']; ?>
										</span>
									</label>
								<?php } ?>
							</div>

							<div class="col-md-offset-2 col-md-10">
								<label class="control-label">Cycle:</label>
								<span><input type="text" class="form-control" name="cycle_<?php echo $ktc; ?>" onkeyup="value=value.replace(/[^\d\.]/g,'')"  ></span>
							</div>	

							

							<div class="col-md-offset-2">
								<label class="col-md-10 " style="float:left;">Frequency:</label>
								<div class="col-md-3">
									<span><input type="text" class="form-control" name="fre_1_<?php echo $ktc; ?>" onkeyup="value=value.replace(/[^\d\.]/g,'')"  ></span>
								</div>
								<div class="col-md-2">
									<span style="font-size:18px;">times per</span>
								</div>
								<div class="col-md-3">
									<span><input type="text" class="form-control" name="fre_2_<?php echo $ktc; ?>" onkeyup="value=value.replace(/[^\d\.]/g,'')" value="1"></span>
								</div>
								<div class="col-md-3">
									<span>
				                    	<select class="form-control" name="fre_3_<?php echo $ktc; ?>">
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
									<span><input type="text" class="form-control" name="unit_price_<?php echo $ktc; ?>" onkeyup="value=value.replace(/[^\d\.]/g,'')"  ></span>
								</div>
								<div class="col-md-1">
									<span style="font-size:18px;">per</span>
								</div>
								<div class="col-md-4">
									<span><input type="text" class="form-control" name="unit_<?php echo $ktc; ?>" placeholder="Mile, acre... " ></span>
								</div>
								
							</div>
						</div>						
						<?php } ?>    
				    </p>
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

</body>
</html>