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

		.checkbox {
			margin-left: 15px;
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


		$(document).ready(function(){

			var companyName = $(".oldCom").find("option:selected").text();
			$('#company_name_old').val(companyName);

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

			$('.oldCom').change(function(){
				var companyName = $(".oldCom").find("option:selected").text();
				$('#company_name_old').val(companyName);
			});

			
		});

		$(function () {
			$('.datetimepicker').datetimepicker({
            	format:'MM/DD/YYYY',
            	//minDate:moment()
            });
        });

	</script>
</head>

<body>
	<div class="container" id="main">
		<div class="panel-heading">
			<h3>Add Contract (Commercial) </h3>
		</div>
		<div class="panel-body">
			<form action="<?php echo BASE_URL();?>contract/addCom" method='post' class='form-horizontal' enctype='multipart/form-data'>
			<input type='hidden' name='backurl'  id='backurl' value='<?=$_SERVER['HTTP_REFERER']?>' ></input>
			<input type='hidden' name='company_id'  id='company_id' value='<?=$company['company_id']?>' ></input>
			<input type='hidden' name='type'  id='type' value='2' ></input>
			<input type='hidden' name='company_name_old'  id='company_name_old' value='' ></input>
				
				
				<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>New Company:</label>
					<!-- <label for="one"> -->
					<div class="col-md-10 " style="display:inline;" >
						<input type="radio"  name="newCom" value="1" class="newCom" checked="checked" >Yes &nbsp&nbsp
						<input type="radio"  name="newCom" value="2" class="newCom">No
					</div>
					<!-- </label> -->
				</div>
				

				<div class="form-group nc1" >
					<label class="col-md-2 control-label"><i>*</i>Company Name:</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="company_name"  autocomplete="off" >
					</div>
				</div>


				<div class="form-group nc2" style="display:none;">
					<label class="col-md-2 control-label"><i>*</i>Company Name:</label>
					<div class="col-md-10">
						<select name="company_id" class="form-control oldCom" required>
							<?php foreach($company as $kcom => $vcom){ ?>
								<option value="<?php echo $kcom; ?>"><?php echo $vcom; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Sign Date:</label>
					<div class="col-md-10 ">
						<input type="text" class="form-control datetimepicker" name="sign_date" autocomplete="off" >
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">POC:</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="poc" autocomplete="off" >
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">PO#:</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="pon" autocomplete="off" >
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>Office:</label>
					<div  class="col-md-10">
						<select class="form-control" name="office">
							<option value="1">Dallas</option>
							<option value="2">Houston</option>
							<option value="3">Mission</option>
						</select> 
					</div>
				</div>


				<div class="form-group">
					<label class="col-md-2 control-label">Charge:</label>
					<div class="col-md-10">

						<div class="row">
							<div class='col-md-3 checkbox'>
								<input type='checkbox' name="ifschedule" value="1" id="ifschedule" checked >Schedule Sweeping Service:
							</div>
							<div class="col-md-3 ">
								<div class="input-group">
									<span class="input-group-addon"> $</span>
									<input type='text' class="form-control" name="schedule_price" value="120.00" autocomplete="off">
									<span class="input-group-addon"> /hr</span>
								</div>
							</div>
						</div>

						<div class="row">
							<div class='col-md-3 checkbox'>
								<input type='checkbox' name="ifunschedule" value="1" id="ifunschedule" checked >Unschedule Sweeping Service:
							</div>
							<div class="col-md-3">
								<div class=" input-group">
									<span class="input-group-addon"> $</span>
									<input type='text' class="form-control" name="unschedule_price" value="125.00" autocomplete="off">
									<span class="input-group-addon"> /hr</span>
								</div>
							</div>
						</div>

						<div class="row">
							<div class='col-md-3 checkbox'>
								<input type='checkbox' name="iftraffic" value="1" id="iftraffic" checked >Traffic Control Service:
							</div>
							<div class="col-md-3">
								<div class="input-group">
									<span class="input-group-addon"> $</span>
									<input type='text' class="form-control" name="traffic_control_price" value="100.00" autocomplete="off">
									<span class="input-group-addon"> /hr</span>
								</div>
							</div>
						</div>

						<div class="row">
							<div class='col-md-3 checkbox'>
								<input type='checkbox' name="ifdisposal" value="1" id="ifdisposal" checked >Disposal Service:
							</div>
							<div class="col-md-3">
								<div class="input-group">
									<span class="input-group-addon"> $</span>
									<input type='text' class="form-control" name="disposal_price" value="250.00" autocomplete="off">
									<!-- <span class="input-group-addon"> /hr</span> -->
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-3 checkbox">
								<input type='checkbox' name="iftravelhour" value="1" id="iftravelhour" checked >Travel Hour:
							</div>
							<div class="col-md-3">
								<div class="input-group">
									<input type='text' class="form-control" name="travel_hour" value="2" autocomplete="off">
									<span class="input-group-addon"> hr</span>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-3 checkbox">
								<input type='checkbox' name="ifcancelhour" value="1" id="iftravelhour" checked >Cancel Working Hour:
							</div>
							<div class="col-md-3 ">
								<div class="input-group">
									<input type='text' class="form-control" name="cancel_hour" value="4" autocomplete="off">
									<span class="input-group-addon"> hr</span>
								</div>
							</div>
							<div class="col-md-5"> *When cancelled, the final "Billing Hour" is "Cancel Working Hour" + "Travel Hour"</div>
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