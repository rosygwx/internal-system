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
		//Pick date
		$(function () {
            $('.datetimepicker').datetimepicker({
            	format:'MM/DD/YYYY',
            	useCurrent: false
            });

            //Check if decimal
			$(".ifNumber").on('change', function () {
				 this.value = this.value.replace(/[^\d.-]/g,'');
			});

        });
		//Count task selection
		function countChecked() {
			var n = $(this).closest('tbody').find( "input:checked" ).length;
			$(this).closest('.panel').find('.panel-title').find('span').html(n);
			console.log(n);
		}; 



		//input new company name or select old company
		$(document).ready(function(){

			$('input[type=radio][name=newCom]').change(function(){
				if (this.value == '1') {
					$(".nc1").show();
		            $(".nc2").hide();
		        }
		        else if (this.value == '2') {
		        	$(".nc2").show();
		            $(".nc1").hide();  
		        }
			});

		});
	</script>
</head>

<body>
	<div class="container" id="main">
		<div class="panel-heading">
			<h3>Update Contract (TxDOT) </h3>
		</div>
		<div class="panel-body">
			<form action="<?php echo BASE_URL();?>contract/update" method='post' class='form-horizontal' enctype='multipart/form-data'>
			<input type='hidden' name='backurl'  id='backurl' value='<?=$_SERVER['HTTP_REFERER']?>' ></input>
			<input type='hidden' name='id'  id='id' value='<?=$contract->contract_id?>' ></input>
				

				<!-- I Contract Info -->
				<div class="col-md-12">
					<h4 class="page-header" style="margin-top:10px;">I&nbsp&nbsp&nbspContract Info</h4>
				</div>

<!-- 				<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>New County:</label>
					<div class="col-md-10" style="display:inline;" >
						<input type="radio"  name="newCom" value="1" class="newCom" checked="checked" >Yes &nbsp&nbsp
						<input type="radio"  name="newCom" value="2" class="newCom">No
					</div>
				</div>
				

				<div class="form-group nc1" >
					<label class="col-md-2 control-label"><i>*</i>County Name:</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="company_name"  autocomplete="new-company_name" required>
					</div>
				</div> -->


				<!-- <div class="form-group nc2" style="display:none;"> -->
				<div class="form-group nc2">
					<label class="col-md-2 control-label"><i>*</i>County Name:</label>
					<div class="col-md-10">
						<select name="company_id" class="form-control oldCom" required>
							<?php foreach($company as $kcom => $vcom){ ?>
								<option value="<?php echo $kcom; ?>" <?php if($kcom == $contract->company_id){?>selected <?php } ?>  ><?php echo $vcom; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>Office:</label>
					<div class="col-md-10">
						<select name="office" class="form-control">
							<option value="1" <?php if($contract->office == 1){?>selected <?php } ?> >Dallas</option>
							<option value="2" <?php if($contract->office == 2){?>selected <?php } ?> >Houston</option>
							<option value="3" <?php if($contract->office == 3){?>selected <?php } ?> >Mission</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>Contract Name:</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="id_pannell" value="<?php echo $contract->contract_id_pannell?>" autocomplete="off" required>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>Original Contract ID:</label>
					<div class="col-md-10">
						<input type="text" class="form-control" value="<?php echo $contract->contract_id_ori?>" name="id_ori" autocomplete="off" required>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>Quote:</label>
					<div class="col-md-10">
						<div class="input-group">
							<span class="input-group-addon"> $</span>
							<input type='text' class="form-control ifNumber" value="<?php echo  $contract->quote_real?>" name="quote_real"  autocomplete="off" required>
						</div>
					</div>
				</div>

				<!-- <div class="form-group">
					<label class="col-md-2 control-label">Status</label>
					<div class=" col-md-10">
						<select class="form-control" name="status">
							<option value="0">Bidding</option>
							<option value="5">Bid Success-In Process</option>
							<option value="1">Bid Success-Done</option>
							<option value="4">Bid Fail</option>
						</select>
					</div>
				</div> -->

				<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>Sign Date:<!-- <br>(If bid success) --></label>
					<div class='col-md-10 date'>
	                    <input type='text' class="form-control datetimepicker" name="sign_date" value="<?php echo  $contract->sign_date?>" autocomplete="off" required>
	                </div> 
				</div>


				<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>Start Date:</label>
					<div class='col-md-10 date'>
	                    <input type='text' class="form-control datetimepicker" name="bdate" value="<?php echo  $contract->bdate?>" autocomplete="off" required>
	                </div> 
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>Duration:</label>
					<div class="col-md-10">
						<div class="input-group">
							<input type="text" class="form-control ifNumber" name="year" value="<?php echo  $contract->year?>"autocomplete="new-year" required>
							<span class="input-group-addon"> year</span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>Status:</label>
					<div class="col-md-10">
						<select name="status" class="form-control">
							<option value="1" <?php if($contract->status == 1){?>selected <?php } ?> >Active</option>
							<option value="2" <?php if($contract->status == 2){?>selected <?php } ?> >Comlete</option>
							<option value="3" <?php if($contract->status == 3){?>selected <?php } ?> >Terminate</option>
						</select>
					</div>
				</div>


				<br>

				<!-- II Task Info -->
				<!-- <div class="col-md-12">
					<h4 class="page-header" style="margin-top:10px;">II&nbsp&nbsp&nbspTask Info</h4>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>Upload CSV:</label>
					<div class="col-md-10">
						<div class="input-group">
							<input name="csv" type="file" value="" class="form-control-file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required >
						</div>
						<br>
						<div>For more instruction of the CSV, please refer to "Task - Template.xlsx":<br>
							<img src="../../ci/assets/img/upload_csv_template.PNG" alt="" data-action="zoom" width="300px" >
						</div>
					</div>
				</div> -->

				<br>


				<!-- <div class="form-group">
					<label class="col-md-2 control-label"><a href="#" id="addScnt">Add a Highway</a></label>
					<div class="col-md-10">
						
					</div>
				</div>
				<label class="col-md-2 control-label"><a href="#" id="remScnt">Add a Highway</a></label>
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
							<div class='col-md-10'>
						        	<input type="text" class="form-control" name="section_from" value="" placeholder="From... " />
			                </div>
			                <div class='col-md-10'>
						        	<input type="text" class="form-control" name="section_from" value="" placeholder="To... " />
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
								<div class="col-md-10">
									<span><input type="text" class="form-control" name="unit_price" onkeyup="value=value.replace(/[^\d\.]/g,'')"  ></span>
								</div>
								<div class="col-md-1">
									<span style="font-size:18px;">per</span>
								</div>
								<div class="col-md-10">
									<span><input type="text" class="form-control" name="unit" placeholder="Mile, acre... " ></span>
								</div>
								
							</div>
						</div>						
						<?php } ?>    
				    </p>
				</div> -->


				<div class="form-group">
					<div class="col-md-12 text-center" >
						<input type='submit' class='btn btn-primary btn-submit' value='Submit' onclick='submitValidate()'>
						<input type='button' onclick='history.go(-1)' class='btn btn-primary' value='Cancel'/>
					</div>
				</div>

				

			</form>
		</div>
	  
	  
	</div>

</body>
</html>