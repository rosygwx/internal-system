<?php $this->load->view('template/header.php');?>
<?php $this->load->view('template/sidebar.php');?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	
</head>

<body>
	<div class="container" id="main">
		<div style="display:inline-block;padding:5px;">
			<span><h3>Employee Lists</h3></span>
	  		<!-- <span><a class="btn btn-primary btn-sm" href="<?php echo BASE_URL();?>/company/add">&nbsp;Add company</a></span> -->
		</div>
	  
	    <form class="form form-inline" action="" method='get'>	
			<div class="form-group">
				<!-- <label for="title">Company Name:</label> -->
				<input class="form-control" type='text' name='name' value='<?php echo $search['name'];?>' placeholder="employee name...">
			</div> 
			
			<div class="form-group">
				<!-- <label for="status">Status:</label> -->
				<select name='status' class="form-control">
					<option value='' <?php if($search['status'] == '' ){echo 'selected';}?>>All Status</option>
					<option value='0' <?php if($search['status'] === '0'){echo 'selected';}?>>Initial</option>
					<option value='1' <?php if($search['status'] ==1){echo 'selected';}?>>Active</option>
					<option value='2' <?php if($search['status'] ==2){echo 'selected';}?>>Terminated</option>
					<option value='3' <?php if($search['status'] ==3){echo 'selected';}?>>Retired</option>
				</select>
			</div>
		
		    <button type="submit" class="btn btn-primary btn-sm">Search</button>
		</form>

		<!-- <hr> -->
		
		<table class='table table-hover'>
		  <form  action="<?php echo BASE_URL();?>/company/lists/" method='post'>
			<style>.table>thead>tr>th,.table>tbody>tr>td,.table>tfoot>tr>td{
					vertical-align: middle;
					}
			</style>
			<thead>
				<tr>
				  <th>Name</th>
				  <th>Office</th>
				  <th>Group</th>
				  <th>StartDate</th>
				  <!--
				  <th>Status</th>
				  <th style='text-align: left'></th>-->
				  <th>Status</th>
				  <th>Opt</th>			  
				</tr>
			</thead>
			<tbody>
			<?php foreach($employee as $k => $v){?>
				<tr class='a-center odd'>									
					<td><?php echo $v->name;?></td>					
					<td><?php echo $v->office;?></td>					
					<td><?php echo $v->group_id;?></td>					
					<td><?php echo date('m/d/Y', strtotime($v->bdate));?></td>					
									
					<!--
					<?php if($v->status==0){ ?>
						<td style="color:#999;font-weight:700;">Bidding</td>
					<?php }elseif($v->status==1){ ?>
						<td style="color:#5cb85c;font-weight:700;">Active</td>
					<?php }elseif($v->status==2){ ?>
						<td style="color:#999;font-weight:700;">Complete</td>
					<?php }elseif($v->status==3){ ?>
						<td style="color:#999;font-weight:700;">Terminate</td>
					<?php } ?>
					-->

					<?php if($v->status===0){ ?>
						<td style="">Initial</td>
					<?php }elseif($v->status==1){ ?>
						<td style="">Active</td>
					<?php }elseif($v->status==2){ ?>
						<td style="">Terminated</td>
					<?php }elseif($v->status==3){ ?>
						<td style="">Retired</td>
					<?php } ?>

					<td class="text-left" style="width:35%;position: relative; ">
						
						<!-- <a href="<?php echo BASE_URL();?>/company/view/?contract_id=<?=$v->contract_id?>">View </a> 
						<a href="<?php echo BASE_URL();?>/company/update/?contract_id=<?=$v->contract_id?>">Modify </a>
						
						<a href="<?php echo BASE_URL();?>/contract/add/?id=<?php echo $v->company_id; ?>">AddContract </a>
						
						<a onclick='return confirm("Confirm to delete?")' href="<?php echo BASE_URL();?>/company/delete/?company_id=<?=$v->company_id?>">Delete</a>  -->


					</td>	
				 
				</tr>
				<?php }?>
			</tbody>
			</form>
		</table>
		<div class="pull-right"> 
			<?php $this->load->view('template/page.php');?>     
		</div>

	</div>

</body>
</html>