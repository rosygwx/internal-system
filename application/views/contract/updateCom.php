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
		
	</script>
</head>

<body>
	<div class="container" id="main">
		<div class="panel-heading">
			<h3>Update Contract (Commercial) </h3>
		</div>
		<div class="panel-body">
			<form action="<?php echo BASE_URL();?>contract/updateCom" method='post' class='form-horizontal' enctype='multipart/form-data'>
			<input type='hidden' name='backurl'  id='backurl' value='<?php echo $_SERVER['HTTP_REFERER']?>' ></input>
			<input type='hidden' name='task_id'  id='task_id' value='<?=$contract->task_id?>' ></input>
			<input type='hidden' name='contract_id'  id='contract_id' value='<?=$contract->contract_id?>' ></input>

				
				<div class="form-group nc1" >
					<label class="col-md-2 control-label"><i>*</i>Company Name:</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="company_name" value="<?php echo $contract->company_name?>"  autocomplete="off" readonly>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">POC:</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="poc" value="<?php echo $contract->poc?>" autocomplete="off" >
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">PO#:</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="pon" value="<?php echo $contract->pon ?>" autocomplete="off" >
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>Office:</label>
					<div  class="col-md-10">
						<select class="form-control" name="office">
							<option value="1"<?php if($contract->office == 1){ ?> selected <?php } ?>>Dallas</option>
							<option value="2"<?php if($contract->office == 2){ ?> selected <?php } ?>>Houston</option>
							<option value="3"<?php if($contract->office == 3){ ?> selected <?php } ?>>Mission</option>
						</select> 
					</div>
				</div>


				<div class="form-group">
					<label class="col-md-2 control-label">Charge:</label>
					<div class="col-md-10">

						<div class='col-md-3 checkbox'>
							<input type='checkbox' name="ifschedule" value="1" id="ifschedule" <?php if($contract->unit_price != 0){ ?> checked <?php } ?> >Schedule Sweeping Service:
						</div>
						<div class="col-md-3 input-group">
							<span class="input-group-addon"> $</span>
							<input type='text' class="form-control" name="schedule_price" value="<?php echo $contract->unit_price?>" autocomplete="off" >
							<span class="input-group-addon"> /hr</span>
						</div>

						<div class='col-md-3 checkbox'>
							<input type='checkbox' name="ifunschedule" value="1" id="ifunschedule" <?php if($contract->unit_price_2 != 0){ ?> checked <?php } ?> >Unschedule Sweeping Service:
						</div>
						<div class="col-md-3 input-group">
							<span class="input-group-addon"> $</span>
							<input type='text' class="form-control" name="unschedule_price" value="<?php echo $contract->unit_price_2?>" autocomplete="off">
							<span class="input-group-addon"> /hr</span>
						</div>

						<div class='col-md-3 checkbox'>
							<input type='checkbox' name="iftraffic" value="1" id="iftraffic" <?php if($contract->traffic_control_price != 0){ ?> checked <?php } ?> >Traffic Control Service:
						</div>
						<div class="col-md-3 input-group">
							<span class="input-group-addon"> $</span>
							<input type='text' class="form-control" name="traffic_control_price" value="<?php echo $contract->traffic_control_price?>" autocomplete="off">
							<span class="input-group-addon"> /hr</span>
						</div>

						<div class='col-md-3 checkbox'>
							<input type='checkbox' name="ifdisposal" value="1" id="ifdisposal" <?php if($contract->disposal_price != 0){ ?> checked <?php } ?> >Disposal Service:
						</div>
						<div class="col-md-3 input-group">
							<span class="input-group-addon"> $</span>
							<input type='text' class="form-control" name="disposal_price" value="<?php echo $contract->disposal_price?>" autocomplete="off">
							<span class="input-group-addon"> /hr</span>
						</div>

						<div class="col-md-3 checkbox">
							<input type='checkbox' name="iftravelhour" value="1" id="iftravelhour" <?php if($contract->travel_hour ){ ?> checked <?php } ?> >Travel Hour:
						</div>
						<div class="col-md-3 input-group">
							<input type='text' class="form-control" name="travel_hour" value="<?php echo $contract->travel_hour?>" autocomplete="off">
							<span class="input-group-addon"> hr</span>
						</div>

						<div class="col-md-3 checkbox">
							<input type='checkbox' name="ifcancelhour" value="1" id="ifcancelhour" <?php if($contract->cancel_hour ){ ?> checked <?php } ?> >Cancel Working Hour:
						</div>
						<div class="col-md-3 input-group">
							<input type='text' class="form-control" name="cancel_hour" value="<?php echo $contract->cancel_hour?>" autocomplete="off">
							<span class="input-group-addon"> hr</span>
						</div>
					</div>
				</div>

				

				
				<br>


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