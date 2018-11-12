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

	<script>
    
        
    </script>
    
</head>

<body>
	<div class="container" id="main">
		<div class="panel-heading">
			<h3>Add Company </h3>
		</div>
		<div class="panel-body">
			<form action="<?php echo BASE_URL();?>/company/add" method='post' class='form-horizontal' enctype='multipart/form-data'>
			<input type='hidden' name='backurl'  id='backurl' value='<?=$_SERVER['HTTP_REFERER']?>' ></input>
				
				

				<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>Company Name:</label>
					<div class="col-md-10">
						<input type="text" class="form-control J-presearch-parts" name="company_name" id="company_name" autocomplete="off" required>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Client Name:</label>
					<div class="col-md-10">
						<input type="text" class="form-control J-presearch-parts" name="client_name" id="client_name"  autocomplete="off" required>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Type</label>
					<div class=" col-md-4">
						<select class="form-control" name="type">
							<option value="1">TxDOT</option>
							<option value="2">Commercial</option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Phone:</label>
					<div class='col-md-10'>
	                    <input type='phone' class="form-control" name="phone" id="phone" />
	                </div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Email:</label>
					<div class='col-md-10'>
	                    <input type='email' class="form-control" name="email" id="email" />
	                </div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Location:</label>

					<div class='col-md-6'>
						<label class="control-label">Address:</label>
						<span>
	                    	<input type='address' class="form-control" name="address" />
	                    </span>
	                </div>

	                <div class='col-md-4'>
						<label class="control-label">City:</label>
						<span>
	                    	<input type='city' class="form-control" name="city" />
	                    </span>
	                </div>

	                <div class='col-md-offset-2 col-md-4'>
						<label class="control-label">State:</label>
						<span>
	                    	<input type='state' class="form-control" name="state" />
	                    </span>
	                </div>
	                
	                <div class='col-md-4'>
						<label class="control-label">Postcode:</label>
						<span>
	                    	<input type='postcode' class="form-control" name="postcode" id="postcode"/>
	                    </span>
	                </div>
				</div>
					

				

				<div class="form-group">
					<div class="col-md-12 text-center" >
						<input id="addCompany" type='submit' class='btn btn-primary btn-submit' value='Submit'>
						<input type='button' onclick='history.go(-1)' class='btn btn-primary' value='Cancel'/>
					</div>
				</div>

				

			</form>
		</div>
	  
	  
	</div>

</body>
</html>