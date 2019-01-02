<!DOCTYPE html>
<html lang="en">

<head>
	<title></title>
	<meta charset="utf-8">
	<meta name="description" content="<?php echo (isset($description)) ? $description : '';?>">
	<meta name="keywords" content="<?php echo (isset($keywords)) ? $keywords : '';?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	


	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
	<script type="text/javascript" src="<?=BASE_URL()?>assets/js/jquery.min.js"></script>
	<script type="text/javascript" src="<?=BASE_URL()?>assets/js/bootstrap.min.js"></script>
	<!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/2.14.1/moment.min.js"></script> -->
	<script type="text/javascript" src="<?=BASE_URL()?>assets/js/moment.min.js"></script>
	<!-- <script type="text/javascript" src="<?=BASE_URL()?>assets/js/bootstrap-datetimepicker.js"></script> -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

	<script type="text/javascript" src="<?=BASE_URL()?>assets/js/typeahead.js"></script>
		<!--	Plugin for the Datepicker, full documentation here: https://github.com/Eonasdan/bootstrap-datetimepicker -->
	<script type="text/javascript" src="<?=BASE_URL()?>assets/js/jquery.running.js"></script>
	<script type="text/javascript" src="<?=BASE_URL()?>assets/js/jquery.number-run.min.js"></script>
	<script type="text/javascript" src="<?=BASE_URL()?>assets/js/odometer.min.js"></script>
	<!-- <script type="text/javascript" src="<?=BASE_URL()?>assets/js/jquery.mask.js"></script> -->
	<script type="text/javascript" src="<?=BASE_URL()?>assets/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?=BASE_URL()?>assets/js/fullcalendar.min.js"></script>
	<script type="text/javascript" src="<?=BASE_URL()?>assets/js/zoom.js"></script>


	<link rel="stylesheet" type="text/css" href="<?=BASE_URL()?>assets/css/bootstrap.min.css">
	<!-- <link rel="stylesheet" type="text/css" href="/ci/assets/css/bootstrap-datetimepicker.css"> -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css">
	<link rel="stylesheet" type="text/css" href="/ci/assets/css/pannell.css">
	<link rel="stylesheet" type="text/css" href="/ci/assets/css/jquery.running.css">
	<link rel="stylesheet" type="text/css" href="/ci/assets/css/odometer-theme-default.css">
	<link rel="stylesheet" type="text/css" href="/ci/assets/css/jquery-ui.min.css">
	<link rel="stylesheet" type="text/css" href="/ci/assets/css/jquery-ui.theme.min.css">
	<link rel="stylesheet" type="text/css" href="/ci/assets/css/fontawesome-all.css">
	<link rel="stylesheet" type="text/css" href="/ci/assets/css/fullcalendar.css">
	<link rel="stylesheet" type="text/css" href="/ci/assets/css/zoom.css">


	<script type="text/javascript">
		//notice
		function notice_show(key, value){
			console.log(value);
			if( value ) {
				$('.notice-'+key).show();
				$('.notice-'+key).find('b').html(value);
			} 
			// else {
			// 	$('.notice-'+key).closest('li').hide();
			// }
		}

		$.ajax('<?=BASE_URL()?>welcome/notice',{
		type:'GET',
		dataType:'json',
		success:function(result){
			//console.log(result);
			$.each(result.notice,notice_show);
		},
		error:function(){
		  $('pend-notice').html('Oops!');
		}
	});	
	</script>
	<style type="text/css">
		/*#header {
			width: 100%;
			height: 50px;
		}*/

		#footer {
			width: 100%;
			height: 100px;
		}

		/*#sidebar {
			width: 15%;
			float: left;
		}*/


		/*#sidebar {
			left: -40%;
		}
*/
		#main {
			width: 85%;
			float: left;

		
		}

		input[type=checkbox] {
            /*content: '';
            display: block;*/
            height: 20px;
            width: 20px;
            /*background-image: url('./img/sprite.png');*/
            /*background-repeat: no-repeat;
            background-position: -25px -32px;
            visibility: visible;*/
        }

        input[type=radio] {
            height: 20px;
            width: 20px;
        }


	</style>
</head>
<body>
	
	<?php $this->load->library('session'); $user = $this->session->userdata('user'); ?>
	<nav class="navbar navbar-default" role="nevigation">
	  <div class="container-fluid">
	    <div class="navbar-header list-inline" style="display: inline-block;">
	      <a class="navbar-brand" href="#">Pannell Industries Inc.</a>
	      <ul class="list-inline  navbar-text" style="float:left;display: inline-block;">
				
					
				<!-- <li><span class="notice-unschedule"><a href="<?=BASE_URL()?>schedule/lists/?status=0&period=1"><b class="text-danger">...</b> schedule left this month</a> </span></li>
				<li><span class="notice-unrevenue"><a href="<?=BASE_URL()?>contract/revenue/?date=&unschedule=1"><b class="text-danger">...</b> revenue left this month </a> </span></li> -->
				<li><span class="notice-bonding"><span><b class="text-danger">...</b> Bonding </span> </span></li>
				
				

				
			
			</ul>
	    </div>
	    <ul class="nav navbar-text " style="float:right;">
	    	<p class="">Welcome, <?php echo $user['nick_name']; ?> 
	    		<a href="<?php echo BASE_URL().'login/logout'; ?>">Logout</a>
	    	</p> 
	    </ul>	      
	  </div>
	</nav>


<!--
<div class="topnav">
  <a class="active" href="#home">Pannell Industries Inc.</a>
  
  <div class="topnav-right" id="header">
    <a href="#search">Welcome, </a>
    <a href="#about">Rosy</a>
  </div>
</div>
	-->
</body>
</html>