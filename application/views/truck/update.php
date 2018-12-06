<?php $this->load->view('template/header.php');?>
<?php $this->load->view('template/sidebar.php');?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">

		#header {
			width: 100%;
			height: 50px;
		}

		#footer {
			width: 100%;
			height: 100px;
		}

		#sidebar {
			width: 15%;
			float: left;
		}

		#main {
			width: 85%;
			float: left;
		}

	</style>
</head>

<body>
	<div class="container" id="main">
		<div class="panel-heading">
			<h3>Modify Company </h3>
		</div>
		<div class="panel-body">
			<form action="<?php echo BASE_URL();?>company/add" method='post' class='form-horizontal' enctype='multipart/form-data'>
			<input type='hidden' name='backurl'  id='backurl' value='<?=$_SERVER['HTTP_REFERER']?>' ></input>
				
				<div class="form-group">
					<label class="col-md-2 control-label">Company Name:</label>
					<div class="col-md-10">
						<input type="text" class="form-control J-presearch-parts" name="company_name" placeholder="Company Name..." value="<?php echo htmlentities($company);?>" autocomplete="off" required>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Client Name:</label>
					<div class="col-md-10">
						<input type="text" class="form-control J-presearch-parts" name="client_name" placeholder="Company Name..." value="<?php echo htmlentities($client);?>" autocomplete="off" required>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Sign Date:</label>
					<div class='col-md-10 date' id='datetimepicker1'>
	                    <input type='text' class="form-control" id='datetimepicker4' name="sign_date" />
	                </div>
				</div>

				<div class="form-group">
					<div class="col-md-12 text-center" >
						<input type='submit' class='btn btn-primary btn-submit' value='Submit'>
						<input type='button' onclick='history.go(-1)' class='btn btn-primary' value='Cancel'/>
					</div>
				</div>

				

			</form>
		</div>
	  
	  
	</div>

	<div>
		<?php $this->load->view('template/footer.php');?>
	</div>
</body>
</html>