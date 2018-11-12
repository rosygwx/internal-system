<?php $this->load->view('template/header.php');?>
<?php $this->load->view('template/sidebar.php');?>
<!DOCTYPE html>
<html>
<script type="text/javascript">
	 
	$(function () {
		console.log('1');
        $('.datetimepicker').datetimepicker({
        	format:"MM/YYYY"
        	//viewMode: 'months'
        });
    });
</script>
<head>
	<title></title>
	
</head>

<body>
	<div class="container" id="main">
	  <h3>Monthly Bill (TxDOT)</h3>

	  <form class="form form-inline" method="get" action="<?php echo BASE_URL().'contract/monthlyBill'?>">
	  		
			<div class="form-group" style="position: relative;">
				<select class="form-control" name="contract_id"  autocomplete="off">
					<?php foreach($contract as $ckey => $con){ ?>
						<option value="<?php echo $ckey; ?>"> <?php echo $con['contract_id_pannell']; ?></option>
					<?php } ?>
				</select>
			</div>

			<div class="form-group" >	
				<div class="date" >
	         		<input class="form-control datetimepicker" id='' type='text' name='date' format="mm-YYYY" value="<?php //echo date('F', mktime(0, 0, 0, $date['month'], 18)).'-'.$date['year'];
	         		echo $date['month'].'-'.$date['year']?>" placeholder="Service Month" autocomplete="off">  
	         	</div>
			</div>
			
			
			<input type="submit" class='btn btn-primary btn-submit'  value='Search'>
			<input type="submit" class='btn btn-primary btn-submit'  value='Save to PDF'>
		</form>



	 <div>

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
			<label class="col-md-2 control-label">Service Month:</label>
			<div class="col-md-4 date" >
         		<input class="form-control datetimepicker" id='' type='text' name='date' format="mm-YYYY" value="<?php //echo date('F', mktime(0, 0, 0, $date['month'], 18)).'-'.$date['year'];
         		echo $date['month'].'-'.$date['year']?>" autocomplete="off">  
         	</div>
		</div>
		
	</div>	

	<hr>
	


	<?php foreach($bill as $kbi => $vbi){?>
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4><?php echo $task_cat[$kbi]['category'].' '.$task_cat[$kbi]['type']; ?></h4>
			</div>
			<div class="panel-body">
				<div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
					<div class="row">
						<div class="col-sm-12">
							<table width="100%" class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" role="grid" aria-describedby="dataTables-example_info">
								<thead>
									<tr role="row">
										<th class="sorting_asc">
											Tract
										</th>
										<th class="sorting_asc">
											HWY
										</th>
										<th class="sorting_asc">
											Type
										</th>
										<th class="sorting_asc">
											Section
										</th>
										<th class="sorting_asc">
											Amount
										</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($vbi as $kkbi => $vvbi){?>
									<tr class="gradA odd" role="row">
										<td>
											<?php echo $vvbi['tract']; ?>
										</td>
										<td>
											<?php echo $vvbi['hwy_id']; ?>
										</td>
										<td>
											<?php echo $vvbi['type']; ?>
										</td>
										<td>
											<?php echo $vvbi['section']; ?>
										</td>
										<td>
											<?php echo '$'.number_format($vvbi['amount'], 2); ?>
										</td>
									</tr>
									<?php }?>
									<tr class="gradA odd" role="row">
										<td colspan="4">
											Total:
										</td>
										<td>
											<?php echo '$'.number_format($sum[$kbi], 2); ?>
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