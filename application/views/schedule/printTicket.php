
<!DOCTYPE html>
<html>
<script type="text/javascript">
	 

</script>
<head>
	<title></title>
	<style type="text/css">
		
		/*table, th, tr {
			font-family: "Arial";
			border: 1px solid black;
			border-collapse: collapse;
		}



		table {
			width: 100%;
		}

		
		.title {
			font-weight: bold;
			font-size: 40px;
			padding-top: 5px;
			
		}

		.titleYear {
			background-color: #278468;
			color: #ffffff;
		}

		.titleCategory {
			font-style: italic;
		}

		.weekday {
			font-size: 20px;
			margin-top: 30px;
			margin-bottom: 30px;
		}

		
		.day {
			border: 0px solid transparent;
			font-size: 15px;

		}

		.date {
			text-align: left;
			padding-top: 0px;
			padding-bottom: 0px;
			padding-left: 10px;
			height: 20px;
			margin-top: 3px;
			margin-bottom: 3px;

		}

		.content {
			text-align: left;
			padding-top: 5px;
			padding-bottom: 5px;
			padding-left: 20px;
			min-height: 20px;
			margin-top: 3px;
			margin-bottom: 3px;
		}*/

		.body, .title, .p1, .p2, .p3, .p4 {
			margin-left: 20px;
			margin-right: 20px;
			
			/*text-align: center;*/
			width: 900px;

			font-size: 18px;
		}

		body {
			padding-bottom: 0px;
		}

		.title, .p1 , .p4{
			/*display:flex;*/
			display: inline-block;
			margin-bottom: 0px;

		}

		.titleLeft {
			float: left;
			width: 30%;
		}

		.titleMid {
			float: left;
			position: relative;
			width: 40%;
			text-align:center;
		}

		.titleMid > img {
			width:80%;
			position: relative;
		}

		.titleRight {
			float: right;
			text-align: right;
            position: relative;
            width: 30%;
		}

		.clear {
			clear: both;
		}

		.p1 {
			/*margin-left: 20px;
			margin-right: 20px;*/
			margin-bottom: 30px;

		}

		.ptitle {
			font-size: 17px;
			/*margin-bottom: 10px;*/
			text-align: center;
		}

		.p1Left, .p1Right {
			float: left;
			position: relative;
			width: 40%;
			text-align:left;
			margin-left: 45px;
			margin-right: 45px;
		}

		.content {
			/*text-decoration:underline;*/
			border-bottom: 1px solid black;
			height: 22px;
		}

		.content div:after {
			/*content: "";
			display: block;
		    background: linear-gradient(to right, #000000, #000000);
		    width: 100%;
		    height: 1px;
		    bottom: 2px;*/
		}

		.sch {
			width: 100%;
			table-layout:fixed;
			border-collapse: collapse;
		}
		
		.sch td, .sch th{
			border-left: 2px solid black;
			border-right: 2px solid black;
			text-align: center;
			height: 30px;
		}

		.sch td {
			border-bottom: 2px solid black;
			margin-top: 20px;

		}

		.p3 {
			margin-top: 25px;
			margin-bottom: 25px;
		}

		.checkbox {
			width: 100%;
			table-layout:fixed;
		}

		.p4Left, .p4Right {
			float: left;
			position: relative;
			width: 50%;
			text-align:left;
			bottom: 0px;
		}

		.bottomLine {
			width:300px;
			height:1px;
			margin-left:100px;
			padding:0px;
			background-color:black;
			color:black;
			/*overflow:hidden;*/
		}

		.cutLine {
			/*border-bottom: 1px dashed black;*/
			padding-bottom: 65px;
		}
	</style>
	<script type="text/javascript" src="<?=BASE_URL()?>assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?=BASE_URL()?>assets/js/jquery-ui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/ci/assets/css/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="/ci/assets/css/jquery-ui.theme.min.css">
</head>

<body>
	<!-- <h1 class="title text-center"><?php echo $title;?></h1> -->
	<?php 
	if($ticket){?>
		<?php for($i = 0; $i < count($ticket) * 2; $i ++){
			$j = $i % 2 == 0 ? $i / 2 : ($i - 1) / 2;
				?>
			<div class="body">
				<!-- phone,  logo,  ticket no -->
				<div class="title">
					<div class="titleLeft">
						<div>Phone: (214)-388-9999</div>
						<div>Fax: (214)-388-9998</div>
					</div>
					<div class="titleMid" ><img src="<?=BASE_URL()?>assets/img/logo_rec_big1.jpg" style=""></div>
					<div class="titleRight" >
						<div>Billing Reference No.</div>
						<div>No. <?php echo $ticket[$j]->ticket_id ?></div>
						<div><strong><i><?php echo $ticket[$j]->btime_req ?></i></strong></div>
					</div>
				</div>

				<!-- to clear float -->
				<div class="clear"> </div>

				<!-- customer, location -->
				<div class="p1">
					<div class="p1Left">
						<p class="ptitle">CUSTOMER</p>
						<div class="content">&nbsp<?php echo $ticket[$j]->company_name; ?></div>
						<div class="content">&nbsp<?php echo $ticket[$j]->contactName; ?></div>
						<div class="content">&nbsp<?php echo $ticket[$j]->phone; ?></div>
					</div>
					<div class="p1Right">
						<p class="ptitle">LOCATION</p>
						<div class="content">&nbsp<?php echo $ticket[$j]->location1; ?></div>
						<div class="content">&nbsp<?php echo $ticket[$j]->location2; ?></div>
						<div class="content">&nbsp<?php echo $ticket[$j]->pon ? $ticket[$j]->pon : ''; ?></div>		
					</div>
				</div>

				<!-- to clear float -->
				<div class="clear"> </div>

				<!-- start, stop, employee, unit, date -->
				<div class="p2">
					<table class="sch">
						<tr>
							<th>START</th>
							<th>STOP</th>
							<th>EMPLOYEE</th>
							<th>UNIT</th>
							<th>DATE</th>
						</tr>
						<tr>
							<td><?php echo $ticket[$j]->btime;?></td>
							<td><?php echo $ticket[$j]->etime;?></td>
							<td><?php echo $ticket[$j]->employee_name;?></td>
							<td><?php echo $ticket[$j]->truck_number;?></td>
							<td><?php echo $ticket[$j]->schedule_date;?></td>
						</tr>
					</table>
				</div>

				<!-- to clear float -->
				<div class="clear"> </div>

				<!-- check box -->
				<div class="p3">
					<table class="checkbox">
						<tr>
							<td>
								<input type="checkbox" onclick="return false;" <?php if($ticket[$j]->ifschedule == 2){?> checked <?php } ?>>Unscheduled Service
							</td>
							<td>
								<input type="checkbox" onclick="return false;" <?php if($ticket[$j]->ifTrafficControl == 1){?> checked <?php } ?>>Traffic Control
							</td>
							<td colspan="2">
								<strong>&nbsp&nbsp&nbsp&nbsp&nbsp&nbspAdditional Comments:</strong>
							</td>
						</tr>
						<tr>
							<td>
								<input type="checkbox" onclick="return false;" <?php if($ticket[$j]->ifWeekend == 1){?> checked <?php } ?>>Weekend Service
							</td>
							<td>
								<input type="checkbox" onclick="return false;" <?php if($ticket[$j]->ifDisposal == 1){?> checked <?php } ?>>Disposal
							</td>
							<td  colspan="2" >
								<div><?php echo $ticket[$j]->comment1; ?></div>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="checkbox" onclick="return false;" <?php if($ticket[$j]->ifMech == 1){?> checked <?php } ?>>Mechanical Sweeper
							</td>
							<td  colspan="2">
								<div><?php echo $ticket[$j]->comment2; ?></div>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<input type="checkbox" onclick="return false;" <?php if($ticket[$j]->ifVac == 1){?> checked <?php } ?>>Vacuum Sweeper
							</td>
							<td  colspan="2">
								<div><?php echo $ticket[$j]->comment3; ?></div>
							</td>
						</tr>
					</table>
				</div>

				<!-- to clear float -->
				<div class="clear"> </div>

				<!-- signature -->
				<div class="p4">
					<div class="p4Left">
						
						<div>Signature &nbsp X</div>
						<hr class="bottomLine"></hr>
					</div>
					<div class="p4Left">
						<div>
							* Billing hours are counted in one hour increments. <br> 
							* Billing will include travel time in and out.
						</div>
					</div>
				</div>

				<!-- to clear float -->
				<div class="clear"> </div>


			</div>
			
			<?php if($i % 2 == 0) {?><div class="cutLine"></div><?php } ?>
			
			
		<?php } ?>
	<?php }else{
		echo 'No tickets to print (No trucks or drivers have been assigned).';
	} ?>
	
</body>
</html>