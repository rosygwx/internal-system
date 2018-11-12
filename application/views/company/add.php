<?php $this->load->view('template/header.php');?>
<?php $this->load->view('template/sidebar.php');?>

	<script>
    $(function() {
        function validateEmail(email) {
            let re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }
        function validatePhone(phone){
            let re = /^\d{3}-\d{3}-\d{4}$/;
            return re.test(phone);
        }
        function validatePostcode(postcode){
            let re = /^\d{5}$/;
            return re.test(postcode);
        }
        $('#addCompany').on('click',function(event){
            event.preventDefault();
            var client_name = $("#client_name").val();
            var company_name = $("#company_name").val();
            var email = $("#email").val();
            var phone = $("#phone").val();
            var postcode = $("#postcode").val();
            if (!client_name) {
                alert('Please fill out Client Name');
                return;
            } 
            if (!company_name) {
                alert('Please fill out Company Name');
                return;
            } 
            if (email && !validateEmail(email)) {
                alert('Please Check the email format! abc@example.com');
                return;
            } 
            if (phone && !validatePhone(phone)) {
                alert('Please Check the Phone format! xxx-xxx-xxxx');
                return;
            } 
            if (postcode && !validatePostcode(postcode)) {
                alert('Please Check the Postcode format! xxxxx');
                return;
            } 
            $(this).closest('form').submit();
        });
    });
        
    </script>
	<script type="text/javascript">
		jQuery(function($){
			$("#phone").mask("999-999-9999");
			$("#postcode").mask("99999");
		});
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

	<script type="text/javascript">
		$('.J-presearch-client').typeahead({
			items: 16,
			minLength:2,
			fitToElement:true,
			autoSelect:false,
			source: function(company, result) {

				$.ajax({

					url:"<?=BASE_URL()?>/client/lists_ajax",
					method:"get",
					data:{company:company},
					dataType:"json",
					success:function(data){
						console.log(1);
						result($.map(data, function(item){	
							console.log(1);
							var companyitem = item;
							return item;
						}));
					},
				});
			},
			afterSelect: function (companyitem) {
				console.log(1);
				//window.location.href='<?=BASE_URL()?>/part/searchByKeyword?keyword='+keyworditem;
			}
		});
        $(function () {
            $('#datetimepicker4').datetimepicker({
            	format:'MM-DD-YYYY'
            });
        });
	</script>

</body>
</html>