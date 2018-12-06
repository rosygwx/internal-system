<?php $this->load->view('template/header.php');?>
<?php $this->load->view('template/sidebar.php');?>

<!DOCTYPE html>
<html>
<head>
	<title>Truck Expense </title>
	<style type="text/css">
		th {
			text-align: center;
		}
		td {
			text-align: right;
		}
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
			<span><h3>Truck Expenses </h3></span>
		</div>

		<form class="form form-inline" method="get" action="<?php echo BASE_URL().'truck/reportExpense'?>">
			<!-- <div class="form-group" style="position: relative;">
				<select class="form-control" name="contract_id"  autocomplete="off">
					<?php foreach($contract as $con){ ?>
						<option value="<?php echo $con->contract_id; ?>"> <?php echo $con->contract_id_pannell; ?></option>
					<?php } ?>
				</select>
			</div> -->
			<div class="form-group" style="position: relative;">
				<input class="form-control bdate" type="text" name="bdate" placeholder="Date from..." <?php if($search['bdate']){?> value="<?php echo $search['bdate']?>" <?php } ?> autocomplete="off">
			</div>
			<div class="form-group" style="position: relative;">
				<input class="form-control edate" type="text" name="edate" placeholder="Date to..." <?php if($search['edate']){?> value="<?php echo $search['edate']?>" <?php } ?> autocomplete="off">
			</div>
			<!-- <div class="form-group" style="position: relative;">
				<select class="form-control" name="ifschedule" autocomplete="off"> 
					<option value=""> All status </option>
					<option value="1"> Scheduled </option>
					<option value="2"> Unscheduled </option>
				</select>
			</div> -->
			<input type="submit" class='btn btn-primary btn-submit'  value='Search'>
		</form>
	
		<hr>

		<?php if($report){ ?>
			<div class="col-lg-12">
				<table width="100%" class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" role="grid" aria-describedby="dataTables-example_info">
					<thead>
						<tr role="row" >
							<th class="sorting_asc" style="width:17%">
								Name
							</th>
							<th class="sorting_asc" style="width:17%">
								Part Cost
							</th>
							<th class="sorting_asc" style="width:17%">
								Labor Cost
							</th>
							<th class="sorting_asc" style="width:17%">
								Purchase Cost
							</th>
							<th class="sorting_asc" style="width:17%">
								Fuel Cost
							</th>
							<th class="sorting_asc">
								Total
							</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($report as $rep){?>
						<tr class="gradA odd" role="row">
							<td style="text-align: center">
								<?php echo $rep['asset_name']; ?>
							</td>
							<td>
								<?php echo  '$'.$rep['cost_part']; ?>
							</td>
							<td>
								<?php echo  '$'.$rep['cost_labor']; ?>
							</td>
							<td>
								<?php echo  '$'.$rep['cost_purchase']; ?>
							</td>
							<td>
								<?php echo '$'.$rep['cost_fuel']; ?>
							</td>
							<td>
								<?php echo '$'.$rep['sub_sum']; ?>
							</td>
						</tr>
						<?php } ?>
						<tr class="gradA odd" role="row">
							<td  style="text-align: center">
								Total:
							</td>
							<td>
								<?php echo '$'.number_format($sum['cost_part'], 2);  ?>
							</td>
							<td>
								<?php echo '$'.number_format($sum['cost_labor'], 2);  ?>
							</td>
							<td>
								<?php echo '$'.number_format($sum['cost_purchase'], 2);  ?>
							</td>
							<td>
								<?php echo '$'.number_format($sum['cost_fuel'], 2);  ?>
							</td>
							<td>
								<?php echo '$'.number_format($sum['total'], 2);  ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		<?php } ?>
	</div>
</body>


</html>
