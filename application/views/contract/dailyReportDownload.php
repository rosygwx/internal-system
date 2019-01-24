
<!DOCTYPE html>
<html>
<script type="text/javascript">
	 

</script>
<head>
	<title></title>
	<style type="text/css">
		.page {
			/*height: 1000px;*/
			height: 1200px;
			
		}

		#intro {
			display: inline-block;
			margin-bottom: 0px;
			width: 100%;
		}

		#summary {
			float: left;
			width: 18%;
			margin-right:2%;
		}

		#worklog {
			/*float: center;
			position: center;
			padding-left: 10px;
			padding: .5em 1em;*/
			width: 15%;
		}

		#dailyReport , #summary , #worklog{
			border-collapse: collapse;
			font-family: Arial;
			margin-top: 10px;
			margin-bottom: 20px;
		}

		#dailyReport td, #dailyReport th, #summary td, #summary th , #worklog td, #worklog th {
			border: 1px solid black;
		}

		/*#worklog td, #worklog th {
			border-left-width: 0px;
		}*/

		#dailyReport th , #summary th, #worklog th {
			font-size: 13px;
			background-color: #DBDBDB;
			padding-top: 2px;
		    padding-bottom: 2px;
		    text-align: center;
		}

		#dailyReport td  , #summary td , #worklog td {
			/*font-size: 10px;*/
			padding-top: 2px;
		    padding-bottom: 2px;
		    padding-left: 2px;
		    text-align: center;
		}

		#summary td , #worklog td {
			font-size: 12px;
		}

		#dailyReport td {
			font-size: 12px;
		}

		#dailyReport tr:hover {
			background-color: #F1F0F0;
		}

		/*.caption {
			text-align: left;
			font-size: 14px;
		}*/

		.clear {
			clear: both;
		}

	</style>
	<script type="text/javascript" src="<?=BASE_URL()?>assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?=BASE_URL()?>assets/js/jquery-ui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/ci/assets/css/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="/ci/assets/css/jquery-ui.theme.min.css">
</head>

<body>
	<div class="container" id="main">
		<?php foreach($report as $key=>$res){
			if($res) {?>
			<div class="page">
				<h3>DAILY WORKING SHEET - TxDOT <?php echo $contractCur['name']; ?> COUNTY <?php echo $contractCur['ori_id']?></h3>
		  
				  <div id="intro">
					  <table id="summary">
						<tr role="row">
							<th colspan="2">
								COMPANY
							</th>
							<td>
								PANNELL INDUSTRIES, INC.
							</td>
						</tr>
						<tr role="row">
							<th colspan="2">
								DATE
							</th>
							<td>
								<?php echo $date;?>
							</td>
						</tr>	
						<tr role="row">
							<th rowspan="2">
								TIME
							</th>
							<th>
								FROM
							</th>
							<td>
								<?php echo $time[$key]['from'] ? date('H:i a', $time[$key]['from'] ) : '';?>
							</td>
						</tr>
						<tr role="row">
							<th>
								TO
							</th>
							<td>
								<?php echo $time[$key]['to'] ? date('H:i a', $time[$key]['to'] ) : '';?>
							</td>
						</tr>
						<tr role="row">
							<th colspan="2">
								SWEEPER
							</th>
							<td>
								<?php echo $truckType[$key]['sweeper'];?>
							</td>
						</tr>
						<tr role="row">
							<th colspan="2">
								DUMP TRUCK / TMA
							</th>
							<td>
								<?php echo $truckType[$key]['tma'];?>
							</td>
						</tr>
						<tr role="row">
							<th colspan="2">
								DEBRIS PICKUP
							</th>
							<td>
								<?php echo $truckType[$key]['debris'];?>
							</td>
						</tr>


					  </table>

					  <table id="worklog">
						<tr role="row">
							<th>
								DRIVER NAME
							</th>
							<th>
								UNIT#
							</th>
						</tr>
						
						<?php for ($i = 0; $i < max([count($truck[$key]), count($driver[$key])]); $i++ ) {?>
							<tr role="row">
								<td>
									<?php echo $driver[$key][$i]; ?>
								</td>
								<td>
									<?php echo $truck[$key][$i]; ?>
								</td>
							</tr>
						<?php }?>
							
					  </table>
				  </div>
				<!-- to clear float -->
				<div class="clear"> </div>

				<?php foreach($report[$key] as $kri => $vri){?>
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
													<th class="sorting_asc" width="4%">
														Tract
													</th>
													<th class="sorting_asc" width="7%">
														HWY
													</th>
													<th class="sorting_asc" width="5%">
														Type
													</th>
													<th class="sorting_asc" width="19%">
														From
													</th>
													<th class="sorting_asc" width="19%">
														To
													</th>
													<th class="sorting_asc" width="6%">
														Mileage
													</th>
													<th class="sorting_asc" width="5%">
														Cycle
													</th>
													<th class="sorting_asc" width="14%">
														Frequency
													</th>
													<th class="sorting_asc" width="9%">
														Complete Date
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
														<?php echo $vvri['frequency']; ?>
													</td>
													<td>
														<?php echo $vvri['date']; ?>
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
													<td colspan="4" >
														
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
			<?php }
		}
		 ?>
	</div>

</body>
</html>