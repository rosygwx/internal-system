<?php $this->load->view('template/header.php');?>
<?php $this->load->view('template/sidebar.php');?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	
</head>

<body>
	<div class="container" id="main">
	  <h3>Contract Lists</h3>
	  <form class="form form-inline" action="" method='get'>
	  	<div class="form-group">
			<!-- <label for="status">Status:</label> -->
			<select name='company_type' class="form-control">
				<option value='1' <?php if($search['company_type'] ==1){echo 'selected';}?>>TxDOT</option>
				<option value='2' <?php if($search['company_type'] ==2){echo 'selected';}?>>Commercial</option>
				
			</select>
		</div>

		<div class="form-group">
			<!-- <label for="title">Company Name:</label> -->
			<input class="form-control" type='text' name='company' value='<?php echo $search['company'];?>' placeholder="Contract Name..." autocomplete="off">
		</div> 
		
		<!-- <div class="form-group">
			<!-- <label for="status">Status:</label>
			<select name='status' class="form-control">
				<option value='' <?php if($search['status'] == '' ){echo 'selected';}?>>All Status</option>
				<option value='0' <?php if($search['status'] ===0){echo 'selected';}?>>Bidded</option>
				<option value='1' <?php if($search['status'] ==1){echo 'selected';}?>>In Progress</option>
				<option value='2' <?php if($search['status'] ==2){echo 'selected';}?>>Terminate</option>
				
			</select>
		</div> -->
		
		 <button type="submit" class="btn btn-primary btn-sm">Search</button>
	</form>

	<hr>
	
	<?php if($search['company_type'] == 1){?>
		<table class='table table-hover'>
		  <form  action="<?php echo BASE_URL();?>contract/lists/" method='post'>
			<style>.table>thead>tr>th,.table>tbody>tr>td,.table>tfoot>tr>td{
					vertical-align: middle;
					}
			</style>
			<thead>
				<tr>
				  <th>CTR ID</th>
				  <th>Quote</th>
				  <th>Price Per Mile</th>
				  <th>Start Date</th>
				  <th>Status</th>
				  <th>Opt</th>			  
				</tr>
			</thead>
			<tbody>
			<?php foreach($contract as $k => $v){?>
				<tr class='a-center odd'>				
					<!-- <td>
						<a href="<?php echo BASE_URL(); ?>contract/update/?id=<?php echo $v->contract_id; ?>"><?php echo $v->contract_id_pannell;?></a></td> -->
					<td><?php echo $v->contract_id_pannell;?></td>									
					<td>$<?php echo number_format($v->quote_real,2);?></td>					
					<td>$<?php echo number_format($v->price_per_mile,2);?></td>								
					<td><?php if($v->bdate != '0000-00-00')echo date('Y-m-d',strtotime($v->bdate)); else echo "-"; ?></td>	

					<?php if($v->status==0){ ?>
						<td style="color:#999;font-weight:700;">Bidding</td>
					<?php }elseif($v->status==1){ ?>
						<td style="color:#5cb85c;font-weight:700;">Bid Success</td>
					<?php }elseif($v->status==2){ ?>
						<td style="color:#999;font-weight:700;">Bid Success</td>
					<?php }elseif($v->status==3){ ?>
						<td style="color:#999;font-weight:700;">Terminate</td>
					<?php } ?>

					<td class="text-left" style="width:20%;">
						<style> .btn-xs { padding-left:0px; padding-right:0px; } </style>
						<!-- <a href="/contract/view/?contract_id=<?=$v->contract_id?>">View </a> 
						<a href="/contract/update/?contract_id=<?=$v->contract_id?>">Modify </a>-->
						
						<?php if(!in_array($v->status,array(0,4))) { ?> 
							<a href="<?php echo BASE_URL(); ?>task/lists/">Task  </a>
						<?php } ?>
						
						<a href="<?php echo BASE_URL(); ?>task/add/?id=<?=$v->contract_id?>" style="padding-left:3%;">AddTask  </a>
						<?php //} ?>
						
						
						<?php //} ?>
						<!-- <a onclick='return confirm("Confirm to delete?")' href="/contract/event_delete/?bdev_id=<?=$v->bdev_id?>">Delete</a>  -->
					</td>	
				 
				</tr>
				<?php }?>
			</tbody>
			</form>
		</table>
	<?php }else{ ?>
		<table class='table table-hover'>
		  <form  action="<?php echo BASE_URL();?>contract/lists/" method='post'>
			<style>.table>thead>tr>th,.table>tbody>tr>td,.table>tfoot>tr>td{
					vertical-align: middle;
					}
			</style>
			<thead>
				<tr>
				  <th>CTR ID</th>
				  <th>Scheduled Price</th>
				  <th>Unscheduled Price</th>
				  <!-- <th>Price Per Mile</th>
				  <th>Start Date</th> 
				  <th>Status</th>-->
				  <th style="width:30%">Opt</th>			  
				</tr>
			</thead>
			<tbody>
			<?php foreach($contract as $k => $v){?>
				<tr class='a-center odd'>				
					<!-- <td>
						<a href="<?php echo BASE_URL(); ?>contract/update/?id=<?php echo $v->contract_id; ?>"><?php echo $v->contract_id_pannell;?></a></td>	 -->					
					<td><?php echo $v->contract_id_pannell;?></td>			
					<td>$<?php echo number_format($v->unit_price,2);?></td>					
					<td>$<?php echo number_format($v->unit_price_2,2);?></td>					
					<!-- <td>$<?php echo number_format($v->price_per_mile,2);?></td>								
					<td><?php if($v->bdate != '0000-00-00')echo date('Y-m-d',strtotime($v->bdate)); else echo "-"; ?></td>	 

					<?php if($v->status==0){ ?>
						<td style="color:#999;font-weight:700;">Bidding</td>
					<?php }elseif($v->status==1){ ?>
						<td style="color:#5cb85c;font-weight:700;">Bid Success</td>
					<?php }elseif($v->status==2){ ?>
						<td style="color:#999;font-weight:700;">Bid Success</td>
					<?php }elseif($v->status==3){ ?>
						<td style="color:#999;font-weight:700;">Terminate</td>
					<?php } ?>-->

					<td class="text-left">
						 <!-- <a href="contract/view/?contract_id=<?=$v->contract_id?>">View </a>  -->
						<a href="<?php echo BASE_URL(); ?>contract/updateCom/?id=<?=$v->contract_id?>">Update </a>
						
						<!-- <?php if(!in_array($v->status,array(0,4))) { ?> 
							<a href="<?php echo BASE_URL(); ?>task/lists/" style="padding-left:3%;">Task  </a>
						<?php } ?>
						
						<a href="<?php echo BASE_URL(); ?>task/add/?id=<?=$v->contract_id?>" style="padding-left:3%;">AddTask  </a>
						<?php //} ?> -->
						
						
						<?php //} ?>
						<!-- <a onclick='return confirm("Confirm to delete?")' href="/contract/event_delete/?bdev_id=<?=$v->bdev_id?>">Delete</a>  -->
					</td>	
				 
				</tr>
				<?php }?>
			</tbody>
			</form>
		</table>
	<?php } ?>

	<div class="pull-right"> 
		<?php $this->load->view('template/page.php');?>     
	</div>

	</div>

</body>
</html>