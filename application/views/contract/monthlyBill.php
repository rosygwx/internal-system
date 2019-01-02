<?php $this->load->view('template/header.php');?>
<?php $this->load->view('template/sidebar.php');?>
<!DOCTYPE html>
<html>
<script type="text/javascript">
	 
	$(function () {
		console.log('1');
        $('.bdate').datetimepicker({
        	format:"MM/DD/YYYY"
        });
        $('.edate').datetimepicker({
        	format:"MM/DD/YYYY"
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
				<select class="form-control" name="id"  autocomplete="off">
					<?php foreach($contractAll as $ckey => $con){ ?>
						<option value="<?php echo $ckey; ?>" <?php if($contract_id == $ckey){?> selected <?php } ?>> <?php echo $con['contract_id_pannell']; ?></option>
					<?php } ?>
				</select>
			</div>


			<div class="form-group" style="position: relative;">
				<input class="form-control bdate" type="text" name="bdate" placeholder="Date from..." <?php if($bdate):?> value="<?php echo $bdate; ?>" <?php endif; ?> autocomplete="off" required>
			</div>
			<div class="form-group" style="position: relative;">
				<input class="form-control edate" type="text" name="edate" placeholder="Date to..." <?php if($edate):?> value="<?php echo $edate; ?>" <?php endif; ?> autocomplete="off" required>
			</div>
			
			<input type="submit" class='btn btn-primary btn-submit'  value='Search'>
			
	  </form>

	  <hr>
	  <?php if($contract_id){?>
		 <div>

			<div class="form-group">
				<label class="col-md-2 control-label">Contract Name:</label>
					<p type="text" class="form-control-static col-md-10" name="company_name"   ><?php echo $contractCur['name'];?>
					</p>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label">Original Contract ID:</label>
				<div class="col-md-10">
					<p type="text" class="form-control-static" name="id_ori"><?php echo $contractCur['ori_id'];?>
					</p>
				</div>
			</div>

			<div class="form-group">
				<label class="col-md-2 control-label">Total Projected Amount:</label>
				<div class="col-md-10">
					<p type="text" class="form-control-static" name="sum_pro"><?php echo "$".number_format($sum_pro,2);?>
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
				<div class="col-md-10" >
	         		<p type="text" class="form-control-static"><?php echo $bdate.' &nbspto '.$edate; ?></p>
	         	</div>
			</div>
			
		</div>	
		<hr>
	  <?php }?>
	
	


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
										<th class="sorting_asc" style="width:7%">
											Tract
										</th>
										<th class="sorting_asc" style="width:9%">
											HWY
										</th>
										<th class="sorting_asc" style="width:7%">
											Type
										</th>
										<th class="sorting_asc" style="width:25%">
											From
										</th>
										<th class="sorting_asc" style="width:25%">
											To
										</th>
										<th class="sorting_asc" style="width:15%">
											Date
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
											<?php echo $vvbi['section_from']; ?>
										</td>
										<td>
											<?php echo $vvbi['section_to']; ?>
										</td>
										<td>
											<?php echo $vvbi['date']; ?>
										</td>
										<td>
											<?php echo '$'.number_format($vvbi['amount'], 2); ?>
										</td>
									</tr>
									<?php }?>
									<tr class="gradA odd" role="row">
										<td colspan="6">
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