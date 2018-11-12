<?php $this->load->view('template/header.php');?>
<?php $this->load->view('template/sidebar.php');?>
<!DOCTYPE html>
<html>
<head>
	<title></title>

	<style type="text/css">
		

	</style>
	<script>
    
        
    </script>
    
</head>

<body>
	<div class="container" id="main" >
		<div class="panel-heading fileupload">
			<h3>Add Task (TxDOT CSV)</h3>
		</div>
		<div style="margin-left:13px;">
	  		<form class="form" action="<?=BASE_URL()?>task/add_csv" method="post" enctype="multipart/form-data">
	  			<span class="control-fileupload">
					
					<input name="csv" type="file" value="" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required >
				</span>
				<br>
				<div>
					<button type="submit" class="btn btn-round btn-large btn-upload" style="width:137px!important;">Upload</button>
				</div>
			</form>
  		</div>

  		
	</div>

</body>
</html>