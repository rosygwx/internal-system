
<!DOCTYPE html>
<html>
<script type="text/javascript">
	 

</script>
<head>
	<title></title>
	<style type="text/css">
		#dailyReport , #summary{
			border-collapse: collapse;
			font-family: Arial;
			margin-top: 10px;
			margin-bottom: 20px;
		}

		#summary {
			margin-top: 10px;
			margin-bottom: 20px;
		}

		#dailyReport {
			margin-top: 10px;
			margin-bottom: 20px;
		}

		#dailyReport td, #dailyReport th, #summary td, #summary th {
			border: 1px solid black;
		}

		#dailyReport th , #summary th{
			font-size: 12px;
			background-color: #DBDBDB;
			padding-top: 2px;
		    padding-bottom: 2px;
		    text-align: center;
		}

		#dailyReport td  , #summary td {
			font-size: 10px;
			padding-top: 2px;
		    padding-bottom: 2px;
		    padding-left: 2px;
		    text-align: center;
		}

		#dailyReport tr:hover {
			background-color: #F1F0F0;
		}

		.caption {
			text-align: left;
			font-size: 14px;
		}

	</style>
	<script type="text/javascript" src="<?=BASE_URL()?>assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?=BASE_URL()?>assets/js/jquery-ui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/ci/assets/css/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="/ci/assets/css/jquery-ui.theme.min.css">
</head>

<body>
	<div class="container" id="main">
	  <h3>DAILY WORKING SHEET - TxDOT <?php echo $contractCur['name']; ?> COUNTY <?php echo $contractCur['ori_id']?></h3>
	  
	  <table id="summary">
		<tr role="row">
			<th>
				COMPANY
			</th>
			<td>
				PANNELL INDUSTRIES, INC.
			</td>
		</tr>
		<tr role="row">
			<th>
				DATE
			</th>
			<td>
				<?php echo $date;?>
			</td>
		</tr>
		
	  </table>
	
	<?php foreach($report as $kri => $vri){?>
	<div class="col-lg-12">
		<div class="panel panel-default">
			<!-- <div class="panel-heading">
				<h4><?php echo $task_cat[$kri]['category'].' '.$task_cat[$kri]['type']; ?></h4>
			</div> -->
			<div class="panel-body">
				<div id="dataTables-example_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
					<div class="row">
						<div class="col-sm-12">
							<table id="dailyReport" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer dtr-inline" role="grid" aria-describedby="dataTables-example_info">
								<!-- <caption class="caption" align="top">hey</caption> -->
								<thead>
									<tr role="row">
										<th class="sorting_asc" colspan="2">
										<?php echo $task_cat[$kri]['category']; ?>
										</th>
									</tr>
									<tr role="row">
										<th class="sorting_asc" width="5%">
											Tract
										</th>
										<th class="sorting_asc" width="8%">
											HWY
										</th>
										<th class="sorting_asc" width="8%">
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
										<th class="sorting_asc" width="">
											Comment
										</th>
										
									</tr>
								</thead>
								<tbody>
									<?php foreach($vri as $kkri => $vvri){?>
									<tr class="gradA odd" role="row" style="position:relative;">
										<td>
											<?php echo $vvri['tract']; ?>
										</td>
										<td>
											<?php echo $vvri['hwy_id']; ?>
										</td>
										<td>
											<?php echo $vvri['type']; ?>
										</td>
										<td style="text-align:left;">
											<?php echo $vvri['from']; ?>
										</td>
										<td style="text-align:left;">
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