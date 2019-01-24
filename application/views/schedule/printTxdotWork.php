<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		body {
			font-family: Arial;
		}
		.report {
			/*landscape
			width: 1200px;
			height: 900px;*/

			/*portrait*/
			width: 900px;
			height: 1200px;
		}

		.basic {
			padding-bottom: 10px;
		}

		.basicLeft, .basicRight {
			float: left;
			position: relative;
			width: 45%;
			text-align:left;
			/*margin-left: 45px;*/
			margin-right: 45px;
		}

		.basicTitle {
			width: 45%;
			display: inline-block;
		}

		.basicInfo {
			width: 45%;
			display: inline-block;
			border-bottom: 1px solid black;
			height: 20px;
			margin-top: 5px;
			margin-bottom: 0px;
		}

		

		.clear {
			clear: both;
		}

		.task {
			width: 100%;
			table-layout:fixed;
			border-collapse: collapse;
			margin-bottom: 20px;
		}

		.task td, .task th{
			border: 1px solid black;
			/*text-align: center;*/
			height: 18px;
		}

		.task th{
			background-color: #cccccc;
		}

		.task td {
			margin-left: 2px;
			/*font-size: 15px;*/
		}

		.lulu {
			display: inline-block;
		}

		.assign {
			width: 50%;
			table-layout:fixed;
			border-collapse: collapse;
			margin-bottom: 10px;
			float: left;
		}

		.assign td, .assign th{
			border: 1px solid black;
			text-align: left;
			height: 25px;
		}

		.assign th {
			background-color: #cccccc;
		}

		.note {
			width: 30%;
			padding-left: 4%;
		}

		.comment {
			margin-top: 30px;
		}

		.label {
			font-weight: bold;
		}

		.underline {
			margin-left: 85px;
			border-bottom: 1px solid black; 
			min-width: 90px;
		}

	</style>
</head>
<?php if($workReport){?>
<body>
	<?php foreach($workReport as $report){?>
		<div class="report">
			<h2>PANNELL INDUSTRIES, INC. TXDDOT DAILY WORKING REPORT</h2>

			<div class="basic">
				<div class="basicLeft">
					<div class="basicTitle">PROJECT</div>
					<div class="basicInfo"><?php echo $report['contract_name']; ?></div>
				</div>
				<div class="basicRight">
					<div class="basicTitle">WORK TYPE</div>
					<div class="basicInfo"><?php echo $report['category'] == 1 ? 'DEBRIS REMOVAL' : 'SWEEPING' ; ?></div>
				</div>
				<div class="basicLeft">
					<div class="basicTitle">DATE</div>
					<div class="basicInfo"><?php echo date('D, M d, Y', strtotime($report['date'])); ?></div>
				</div>
				<div class="basicRight">
					<div class="basicTitle">FILLED BY</div>
					<div class="basicInfo"></div>
				</div>
				<div class="clear"></div>
			</div>
			
			

			<div class="">
				<table class="task">
					<tr>
						<th style="width:6%">TRC</th>
						<th style="width:6%">TYPE</th>
						<th style="width:10%">HWY</th>
						<th style="">FROM</th>
						<th style="">TO</th>
						<th style="width:8%">ONE - PASS<br>MILE</th>
						<th style="width:8%">START<br>TIME</th>
						<th style="width:8%">STOP<br>TIME</th>
						<th style="width:11%">COMPLETE<br>DATE</th>
					</tr>
					<?php foreach($report['task'] as $task){?>
						<tr>
							<td style="text-align: center;"><?php echo $task['tract']?></td>
							<td style="text-align: center;"><?php echo $task['type']?></td>
							<td style="text-align: center;"><?php echo $task['hwy_id']?></td>
							<td><?php echo $task['section_from']?></td>
							<td><?php echo $task['section_to']?></td>
							<td style="text-align: center;"><?php echo $task['mile']?></td>
							<td><?php echo $task['btime']?></td>
							<td><?php echo $task['etime']?></td>
							<td><?php echo $task['date']?></td>
						</tr>
					<?php } ?>
					
				</table>
			</div>

			<div class="lulu">
				<table class="assign">
					<tr>
						<th>DRIVER NAME</th>
						<th>VEHICLE NUMBER</th>
					</tr>
					<?php for($i = 0; $i < count($report['driver']); $i ++){?>
					<tr>
						<td><?php echo $driver[$report['driver'][$i]];?></td>
						<td><?php echo $truck[$report['truck'][$i]];?></td>
					</tr>
					<?php } ?>
					<tr>
						<th colspan="2" >DEBRIS COLLECTED</th>
					</tr>
					<tr>
						<td>&nbsp</td>
						<td>&nbsp</td>
					</tr>
				</table>
				<table  class="note">
					<tr><th style="text-align: left;">Note</th></tr>
					<tr><td>Type I: Left Shoulder</td></tr>
					<tr><td>Type II: Right Shoulder</td></tr>
					<tr><td>Type III: Frontage Road</td></tr>
					<tr><td>Type IV: Entrance/Exit Ramp</td></tr>
					<tr><td>Type V: Highway Interchange</td></tr>	
				</table>
			</div>

			<div class="comment">
				<label class="label">Comment:</label>
				<div class="underline"></div>
				<div class="underline" style="padding-top:35px"></div>
				<div class="underline" style="padding-top:35px"></div>
			</div>


		</div>
	<?php } ?>
</body>
<?php }else echo 'No report is selected.';?>
</html>