<?php $this->load->view('template/header.php');?>
<?php $this->load->view('template/sidebar.php');?>

<!DOCTYPE html>
<html>
<head>
	<title>Monthly Bill (Commercial)</title>
	<style type="text/css">
		
	</style>
	<script type="text/javascript">
		$(function(){
			$('.bdate').datetimepicker({
				format: 'MM/DD/YYYY'
			});
			$('.edate').datetimepicker({
				format: 'MM/DD/YYYY',
				useCurrent: false
			});
			$(".bdate").on("dp.change", function (e) {
	              $('.edate').data("DateTimePicker").minDate(e.date); 
	           });        
	        $(".edate").on("dp.change", function (e) {
	              $('.bdate').data("DateTimePicker").maxDate(e.date);        
	        });
        });
	</script>
</head>
<body>
	<div class="container" id="main">
		<div style="display:inline-block;padding:5px;">
			<span><h3>Monthly Bill (Commercial)</h3></span>
		</div>

		<form class="form form-inline" method="post" action="<?php echo BASE_URL().'schedule/monthlyBillCom'?>">
			<div class="form-group" style="position: relative;">
				<select class="form-control" name="contract_id"  autocomplete="off">
					<?php foreach($contract as $con){ ?>
						<option value="<?php echo $con->contract_id; ?>"> <?php echo $con->contract_id_pannell; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group" style="position: relative;">
				<input class="form-control bdate" type="text" name="bdate" placeholder="Date from..." autocomplete="off">
			</div>
			<div class="form-group" style="position: relative;">
				<input class="form-control edate" type="text" name="edate" placeholder="Date to..." autocomplete="off">
			</div>
			<div class="form-group" style="position: relative;">
				<select class="form-control" name="ifschedule" autocomplete="off"> 
					<option value=""> All status </option>
					<option value="1"> Scheduled </option>
					<option value="2"> Unscheduled </option>
				</select>
			</div>
			<input type="submit" class='btn btn-primary btn-submit'  value='Export to Excel'>
		</form>
	</div>
</body>
</html>
