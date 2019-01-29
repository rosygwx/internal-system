<?php $this->load->view('template/header.php');?>
<?php $this->load->view('template/sidebar.php');?>
<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript">
		$(function(){
			$('#running').running();

			$("#number-example-1").numberRun({
		        attr: true,
		        speed: 400
		    });

		    $('.date').datetimepicker({
        	format:"MM/YYYY"
        });
		})

		setTimeout(function(){
		    odometer.innerHTML = document.getElementById("odometer").value;
		}, 1000);

		
	</script>
	<style type="text/css">
		.odometer {
		    font-size: 30px;
		    color: red;
		}
	</style>
	<title></title>
	
</head>

<body>
	<div class="container" id="main">
	  <h3>Revenue</h3>
		<div>
			<span style="display:inline-block;">
			  <form class="form form-inline" action="" method='get'>	
				<div class="form-group">
					<select name='company_type' class="form-control">
						<option value='1' <?php if($search['company_type'] ==1){echo 'selected';}?>>TxDOT</option>
						<option value='2' <?php if($search['company_type'] ==2){echo 'selected';}?>>Commercial</option>
					</select>
				</div>

				<div class="form-group">
					<select name='status' class="form-control">
						<option value='' <?php if($search['status'] == ''){echo 'selected';}?>>All Status</option>
						<option value='1' <?php if($search['status'] ==1){echo 'selected';}?>>Active</option>
						<option value='2' <?php if($search['status'] ==2){echo 'selected';}?>>Inactive</option>
					</select>
				</div>

				<div class="form-group" style="position: relative;">
					<input class="form-control date" type="text" name="date" placeholder="" <?php if($search['date']):?> value="<?php echo $search['date']; ?>" <?php endif; ?> autocomplete="off" required>
				</div>
				
				<button type="submit" class="btn btn-submit btn-primary btn-sm" onclick="getVal()">Search</button>
			  </form>
			</span >
			<?php if($company_type==2){?>
				<!-- $ <span id="number-example-1" number="<?php echo $sum; ?>"  style="display:inline-block;"><?php echo $sum;?>
				</span> -->
				<!-- $ <span id="running" class="animateNum" data-animatetarget="<?php echo number_format($sum,1); ?>"><?php echo $sum; ?></span> -->
				<span style="font-size: 30px; color:red; vertical-align: middle;">$</span> 
				<div class="odometer" id="odometer" value="<?php echo number_format($sum, 2);?>"><?php echo number_format($sum, 2); ?></div>
			<?php } ?>
		</div>
	
	<hr>
	<!--txdot-->
	<?php if($company_type == 1){?>
		<table class='table table-hover'>
			<thead>
				<tr>
				  <th>Contract</th>
				  <th>Earned/Quote</th>
				  <th>Process/Standard Process</th>
				  <th>Earned/Quote(selected month)</th>
				  <th>Extra Task <br>(selected month)</th>
				  <th>Process(selected month)</th>
				  <!-- <th>Standard Revenue Process</th> -->
				 
				  <!--<th style='text-align: left'></th>-->
				  <th>Opt</th>		  
				</tr>
			</thead>
			<tbody>
			<?php foreach($revenue as $k => $v){?>
				<tr class='a-center odd'>				
					
					<td <?php if($v->contract_id == 0){ ?> style="font-style:italic;" <?php } ?>><?php echo $v->contract_id_pannell;?></td>
					

					<td><?php echo "$".number_format($v->earned_contract,2)." / $".number_format($v->quote_real,2);?></td>	

					<?php if($v->contract_id != 0){ ?>
						<td><?php echo $v->revenue_real." / ".$v->revenue_standard;?></td>
					<?php }else{ ?>		
						<td> </td>
					<?php }?>	


					<td><?php echo "$".number_format($v->earned_current_month,2)." / $".number_format($v->current_month,2);?></td>	

					<td><?php echo "$".number_format($v->earned_current_month_extra,2);?></td>	


					<?php if($v->contract_id != 0){ ?>
						<td class="progress progress-striped active" style="background-color: white;box-shadow: none;  ">
							<span class="progress-bar <?php if($v->current_process>=100)echo "progress-bar-success"; else echo "progress-bar-warning";?> " role="progressbar" aria-valuenow="<?php echo $v->current_process;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php if($v->current_process>=100)echo 100; else echo $v->current_process;?>%">
		                                           
		                                        <?php echo $v->current_process."%";?>
		                                        	
		                    </span>
		                </td>
		                <td class="text-left" style="width:10%; position: relative; ">
		                	<a href="<?php echo BASE_URL(); ?>contract/monthlyBill/?id=<?=$v->contract_id?>">MonthlyBill  </a>
		                	<a style="padding-left:3%;" href="<?php echo BASE_URL(); ?>contract/dailyReport/?id=<?=$v->contract_id?>">DailyReport  </a>
		                </td>	
	                <?php }else{ ?>	
	                	<td></td>
	                	<td></td>
	                <?php } ?>			
				</tr>
				<?php }?>
			</tbody>
		</table>
	<!--commercial-->
	<?php }else{ ?>
		<table class='table table-hover'>
			<thead>
				<tr>
					<th>Contract</th>
					<th>Earned</th>
					<th>Earned(Current Month)</th>
					<th>Total Billing Hour</th>
					<!-- <th>Total Count</th>
					<th>Schedule Work Ratio</th> -->
					<th>Opt</th>
				</tr>
			</thead>

			<?php foreach($revenue as $rev){?>
				<tr>
					<td><?php echo $rev->contract_id_pannell; ?></td>
					<td><?php echo "$".number_format($rev->sum,2); ?></td>
					<td><?php echo "$".number_format($rev->sum_current,2); ?></td>
					<td><?php echo $rev->sum_hour; ?></td>
					<!-- <td><?php echo $rev->count; ?></td>
					<td><?php echo number_format($rev->schedule*100,2)."%"; ?></td> -->
					<td class="text-left" style="width:10%;position: relative; ">
	                	<a href="<?php echo BASE_URL(); ?>contract/monthlyBill/?id=<?=$rev->contract_id?>">MonthlyBill  </a>
	                </td>
				</tr>
			<?php }?>


	<?php } ?>
	<!-- <div class="pull-right"> 
		<?php //$this->load->view('template/page.php');?>     
	</div> -->
	</div>
</body>
</html>