
<!DOCTYPE html>
<html>
<script type="text/javascript">
	 

</script>
<head>
	<title></title>
	<style type="text/css">
		
		table, th, tr {
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
		}

	</style>
	<script type="text/javascript" src="<?=BASE_URL()?>assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?=BASE_URL()?>assets/js/jquery-ui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/ci/assets/css/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="/ci/assets/css/jquery-ui.theme.min.css">
</head>

<body>
	<!-- <h1 class="title text-center"><?php echo $title;?></h1> -->
	<table>
		<!-- title -->
		<tr class="title">
			<th class="titleYear"><?php echo $title['year']?></th>
			<th class="titleMonth" colspan="3"><?php echo $title['month']?></th>
			<th class="titleCategory" colspan="3"><?php echo $title['category']?></th>
		</tr>

		<!-- weekday -->
		<tr class="weekday">
			<th class="">Sunday</th>
			<th class="">Monday</th>
			<th class="">Tuesday</th>
			<th class="">Wednesday</th>
			<th class="">Thursday</th>
			<th class=""> Friday </th>
			<th class="">Saturday</th>
		</tr>

		<!-- calendar -->
		<?php for($i = 0; $i < count($calendar) + 1; $i++) {?>
			<tr class="day">
				<?php foreach($calendar[$i] as $call){?>
					<th>
						<?php if($call['class'] == 'date') {?>
							<p class="date" <?php if($call['ifCurrentMonth'] != 1){?> style="color: #B0B0B0;" <?php } ?> ><?php echo $call['day']?></p>
						<?php }else{ ?>
							<p class="content"><?php foreach($call['content']['task'] as $cont){
													echo "Tract ".$cont.'<br>';
												} 
												echo $call['content']['mile_all_actual'] && $ifMileActual == 1?'Total actual miles: '.$call['content']['mile_all_actual']:'';?>

							</p>
						<?php }?>
					</th>
				<?php } ?>
			</tr>
		<?php } ?>
	</table>

	<br>
	<div><?php echo $note?></div>
</body>
</html>