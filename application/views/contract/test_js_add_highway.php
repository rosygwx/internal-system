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
		//add/remove hwy
		$(function() {
	        var scntDiv = $('#p_scents');
	        var i = $('#p_scents p').length + 1;
	        console.log(i);
	        $('#addScnt').on('click', null, function() {
                $('<p><div class="form-group"><label class="col-md-2 control-label">Highway Name:</label><div class="col-md-10"><input type="text" class="form-control J-presearch-hwy" name="hwy_id[]"  autocomplete="off" required></div></div><div class="form-group"><label class="col-md-2 control-label">Centerline Mileage:</label><div class="col-md-10"><input type="text" class="form-control" name="mileage[]"  autocomplete="off" required></div></div><div class="form-group"><label class="col-md-2 control-label">Section:</label><div class="col-md-5"><input type="text" class="form-control" name="section_from[]" value="" placeholder="From... " /></div><div class="col-md-5"><input type="text" class="form-control" name="section_from[]" value="" placeholder="To... " /></div></div><?php foreach ( $task_cat as $ktc => $vtc ) { ?><div class="form-group"><label class="col-md-2 control-label" style="" >Service - <?php if($ktc==1) echo "Debris"; else echo "Sweeping" ; ?>: </label><div class="col-md-10"><label class="control-label"> Type: </label><?php foreach($vtc as $vvtc) { ?><label class="col-md-offset-1 checkbox-inline" style=""><span><input type="checkbox" name="tcat_id[]" value="<?php echo $vvtc["tcat_id"]; ?>" > 	<?php echo $vvtc["tcat_name"]; ?></span></label><?php } ?></div><div class="col-md-offset-2 col-md-10"><label class="control-label">Cycle:</label><span><input type="text" class="form-control" name="cycle[]"  ></span></div><div class="col-md-offset-2"><label class="col-md-10 " style="float:left;">Frequency:</label><div class="col-md-3"><span><input type="text" class="form-control" name="fre_1[]" onkeyup="value=value.replace(/[^\d\.]/g,"")"  ></span></div><div class="col-md-2"><span style="font-size:18px;">times per</span></div><div class="col-md-3"><span><input type="text" class="form-control" name="fre_2[]" onkeyup="value=value.replace(/[^\d\.]/g,"")" ></span></div><div class="col-md-3"><span><select class="form-control" name="fre_3[]"><option value="1">Week</option><option value="2">Month</option><option value="3">Year</option></select></span></div></div><div class="col-md-offset-2"><label class="col-md-10 " style="float:left;">Unit Price:</label><div class="col-md-4"><span><input type="text" class="form-control" name="unit_price[]" onkeyup="value=value.replace(/[^\d\.]/g,"")"  ></span></div><div class="col-md-1"><span style="font-size:18px;">per</span></div><div class="col-md-4"><span><input type="text" class="form-control" name="unit[]" placeholder="Mile, acre... " ></span></div></div></div><?php } ?></p>').appendTo(scntDiv);
                i++;
                console.log(i);
                return false;
	        });
	        
	        $('#remScnt').on('click',  function() {
	        console.log(i); 
                if( i > 2 ) {
                        $(this).parents('p').remove();
                        i--;

                }
                return false;

	        });
		});
	</script>
	</script>
</head>

<body>
	<div class="container" id="main">
		<div class="panel-heading">
			<h3>Add Contract (TxDOT) </h3>
		</div>
		<div class="panel-body">
			<!-- <form action="<?php echo BASE_URL();?>/contract/add" method='post' class='form-horizontal' enctype='multipart/form-data'>
			<input type='hidden' name='backurl'  id='backurl' value='<?=$_SERVER['HTTP_REFERER']?>' ></input>
			<input type='hidden' name='company_id'  id='company_id' value='<?=$company['company_id']?>' ></input>
			<input type='hidden' name='type'  id='type' value='1' ></input>


			<div class="form-group">
					<label class="col-md-2 control-label"><a href="#" id="addScnt">Add a Highway</a></label>
					<div class="col-md-10">
						
					</div>
				</div> -->


				<h2><a href="#" id="addScnt">Add Another Input Box</a></h2>



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
								
						        	<input type="text" class="form-control" name="section_from" value="" placeholder="From... " />
						        
			                </div>
			                <div class='col-md-5'>
								
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
						</div>						
						<?php } ?>    
				    </p>




				    <!--  <p>
				        <label for="p_scnts"><input type="text" id="p_scnt" size="20" name="p_scnt" value="" placeholder="Input Value" /></label>
				    </p> -->
				</div>

		</div>
	</div>
</body>