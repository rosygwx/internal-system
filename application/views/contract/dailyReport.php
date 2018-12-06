<?php $this->load->view('template/header.php');?>
<?php $this->load->view('template/sidebar.php');?>
<!DOCTYPE html>
<html>
<script type="text/javascript">
	 
	$(function () {
		console.log('1');
        $('.date').datetimepicker({
        	format:"MM/DD/YYYY"
        	//viewMode: 'months'
        });
    });

    function exportPdf(){
    	document.getElementById("ifPrint").value=1;
    	return true;
    }
</script>
<head>
	<title></title>
	
</head>

<body>
	<div class="container" id="main">
	  <h3>Daily Report (TxDOT)</h3>

	  <!-- version 1
	  <form class="form" action="" method='get'>

		<div class="form-group">
			<label class="col-md-2 control-label">Contract Name:</label>
				<p type="text" class="form-control-static col-md-10" name="company_name"   ><?php echo $contract['name'];?>
				</p>
		</div>

		<div class="form-group">
			<label class="col-md-2 control-label">Original Contract ID:</label>
			<div class="col-md-10">
				<p type="text" class="form-control-static" name="id_ori"><?php echo $contract['ori_id'];?>
				</p>
			</div>
		</div>

		<div class="form-group">
			<label class="col-md-2 control-label">Total Amount:</label>
			<div class="col-md-10">
				<p type="text" class="form-control-static" name="sum"><?php echo "$".number_format($sum[0],2);?>
				</p>
			</div>
		</div>

		<div class="form-group" >
			<label class="col-md-2 control-label">Service Date:</label>
			<div class="col-md-4 date" >
         		<input class="form-control datetimepicker" id='' type='text' name='date' value="<?php //echo date('F', mktime(0, 0, 0, $date['month'], 18)).'-'.$date['year'];
         		echo $date?>" autocomplete="off">  
         	</div>
		</div>
		<input class="form-control" type='hidden' name='id' value='<?php echo $contract['id']?>'> 
		<span class="col-md-1">
			<button type="submit" class="btn btn-primary btn-sm">Search</button>
		</span> 
		<span class="col-md-1">
			<button type="submit" class="btn btn-primary btn-sm" name="ifPrint" value="1">Download</button>
		</span> 
		<span class="col-md-1">
			<button type="button" onclick=" window.open('<?php echo Base_url()?>contract/dailyReport/?ifPrint=1&id=<?php echo $contract['id']; ?>&date=<?php echo urlencode($date);?>','_blank')"  class="btn btn-primary btn-sm">Print</button>
		</span>
		 
	</form> -->

	<!-- version 2 -->
	<form class="form form-inline" method="get" action="<?php echo BASE_URL().'contract/dailyReport'?>">
		<div class="form-group" style="position: relative;">
			<select class="form-control" name="id"  autocomplete="off">
				<?php foreach($contractAll as $kcon => $con){ ?>
					<option value="<?php echo $kcon; ?>" <?php if($id == $kcon){?> selected <?php } ?> > <?php echo $con['contract_id_pannell']; ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="form-group" style="position: relative;">
			<input class="form-control date" type="text" name="date" <?php if($date): ?> value="<?php echo $date;?>" <?php endif?> placeholder="Date..." autocomplete="off" required>
		</div>
		
		<!-- <div class="form-group" style="position: relative;">
			<select class="form-control" name="ifschedule" autocomplete="off"> 
				<option value=""> All status </option>
				<option value="1"> Scheduled </option>
				<option value="2"> Unscheduled </option>
			</select>
		</div> -->
		<input type="hidden" name="ifPrint" id="ifPrint" value="0">
		<input type="submit" class='btn btn-primary btn-submit'  value='Search'>
		<input type="submit" class='btn btn-primary btn-submit' onclick="return exportPdf();" value="Export to PDF">

	</form>
	<hr>
	


	<?php foreach($report as $kri => $vri){?>
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4><?php echo $task_cat[$kri]['category'].' '.$task_cat[$kri]['type']; ?></h4>
			</div>
			<div class="panel-body">
				<div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
					<div class="row">
						<div class="col-sm-12">
							<table width="100%" class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" role="grid" aria-describedby="dataTables-example_info">
								<thead>
									<tr role="row">
										<th class="sorting_asc" width="7%">
											Tract
										</th>
										<th class="sorting_asc" width="10%">
											HWY
										</th>
										<th class="sorting_asc" width="7%">
											Type
										</th>
										<th class="sorting_asc" width="25%">
											From
										</th>
										<th class="sorting_asc" width="25%">
											To
										</th>
										<th class="sorting_asc" width="8%">
											Mileage
										</th>
										<th class="sorting_asc" width="8%">
											Cycle
										</th>
										<th class="sorting_asc" >
											Comment
										</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($vri as $kkri => $vvri){?>
									<tr class="gradA odd" role="row">
										<td>
											<?php echo $vvri['tract']; ?>
										</td>
										<td>
											<?php echo $vvri['hwy_id']; ?>
										</td>
										<td>
											<?php echo $vvri['type']; ?>
										</td>
										<td>
											<?php echo $vvri['from']; ?>
										</td>
										<td>
											<?php echo $vvri['to']; ?>
										</td>
										<td>
											<?php echo $vvri['mile']; ?>
										</td>
										<td>
											<?php echo $vvri['cycle']; ?>
										</td>
										<td>
											<?php echo $vvri['comment']; ?>
										</td>
									</tr>
									<?php }?>
									<tr class="gradA odd" role="row">
										<td colspan="5" style="text-align:left;">
											TOTAL
										</td>
										<td>
											<?php echo number_format($sum[$kri], 2); ?>
										</td>
										<td>
											
										</td>
										<td>
											
										</td>
									</tr>
								</tbody>
							</table>
							
						</div>
					</div>
				</div>
			</div>
		</div>
		


	</div>
	<?php } ?>

	

	</div>

</body>
</html>