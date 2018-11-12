<?php $this->load->view('template/header.php');?>
<?php $this->load->view('template/sidebar.php');?>


	<div class="container" id="main">
		<div style="display:inline-block;padding:5px;">
			<span><h3>Company Lists</h3></span>
	  		<span><a class="btn btn-primary btn-sm" href="<?php echo BASE_URL();?>company/add">&nbsp;Add company</a></span>
		</div>
	  
	  <form class="form form-inline" action="" method='get'>	
		<div class="form-group">
			<!-- <label for="title">Company Name:</label> -->
			<input class="form-control" type='text' name='company' value='<?php echo $search['company'];?>' placeholder="Company Name...">
		</div> 
		<div class="form-group">
			<!-- <label for="title">Client Name:</label> -->
			
			<input class="form-control" type='text' name='client' value='<?php echo $search['client'];?>' placeholder="PM Name...">
		</div> 
		<div class="form-group">
			<!-- <label for="status">Status:</label> -->
			<select name='type' class="form-control">
				<option value='' <?php if($search['type'] == '' ){echo 'selected';}?>>All Type</option>
				<option value='1' <?php if($search['type'] ==1){echo 'selected';}?>>TXDot</option>
				<option value='2' <?php if($search['type'] ==2){echo 'selected';}?>>Commercial</option>
			</select>
		</div>
		
		 <button type="submit" class="btn btn-primary btn-sm">Search</button>
	</form>

	<hr>
	
	<table class='table table-hover'>
	  <form  action="<?php echo BASE_URL();?>company/lists/" method='post'>
		<style>.table>thead>tr>th,.table>tbody>tr>td,.table>tfoot>tr>td{
				vertical-align: middle;
				}
		</style>
		<thead>
			<tr>
			  <th>Company</th>
			  <th>PM</th>
			  <th>Phone</th>
			  <!--
			  <th>Status</th>
			  <th style='text-align: left'></th>-->
			  <th>Type</th>
			  <th>Opt</th>			  
			</tr>
		</thead>
		<tbody>
		<?php foreach($company as $k => $v){?>
			<tr class='a-center odd'>									
				<td><?php echo $v->company;?></td>					
				<td><?php echo $v->client;?></td>					
				<td><?php echo $v->phone;?></td>					
								
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

				<?php if($v->type==1){ ?>
					<td>TxDOT</td>
				<?php }elseif($v->type==2){ ?>
					<td>Commercial</td>
				<?php } ?>

				<td class="text-left" style="width:35%;position: relative; ">
					
					<!-- <a href="<?php echo BASE_URL();?>company/view/?contract_id=<?=$v->contract_id?>">View </a> 
					<a href="<?php echo BASE_URL();?>company/update/?contract_id=<?=$v->contract_id?>">Modify </a> -->
					<a href="<?php echo BASE_URL();?>contract/lists/?company_id=<?php echo $v->company_id; ?>">Contract </a>
					<a href="<?php echo BASE_URL();?>contract/add/?id=<?php echo $v->company_id; ?>">AddContract </a>
					
					<!-- <a onclick='return confirm("Confirm to delete?")' href="<?php echo BASE_URL();?>company/delete/?company_id=<?=$v->company_id?>">Delete</a>  -->
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