<?php $this->load->view('template/header.php');?>
<?php $this->load->view('template/sidebar.php');?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		/*th.ui-widget-header .fc-description {
		    font-size: 1.0em;
		    font-family: Verdana, Arial, Sans-Serif;
		}*/

		/*font size*/
		.fc-title {
		    font-size: 12pt;
		}
		
		/*#calendar {
		   height:10000px;
		}*/


	</style>
	<script type="text/javascript">
		$(function () {
            $('#datetimepicker4').datetimepicker({
            	format:'MM/YYYY',
            	viewMode: 'months'
            });

            $('.datetimepickerModal').datetimepicker({
            	format:'MM/DD/YYYY',

            });
        });

		
        $(document).ready(function(){
        	/*$('#calendar').fullCalendar( 'gotoDate', "<?php echo $search['year']?>", "<?php echo $search['month']?>" );*/
        	//$('#calendar').fullCalendar( 'gotoDate', '2018', '1' );
        	//var defaltDate = <?php echo $defaultDate?> ? <?php echo $defaultDate?> : ;

        	//var calHeight = 10000;

        	var calendar = $('#calendar').fullCalendar({
        		//height:calHeight,//no scroller
		    	//contentHeight:calHeight,//no scroller

        		editable:true,
        		defaultDate: moment('<?php echo $defaultDate?>'),
        		//

        		header:{
        			left:'prev,next today',
        			center:'title',
        			right:'month,basicWeek,agendaDay'
        		},
        		/*validRange: {
				    start: '2018-03-01',
				    end: '2018-06-01'
				  },*/
        		//events:'<?php echo BASE_URL(); ?>schedule/listsCalendar',

        		events: function(start, end, timezone, callback) {
        			/*var monthYear = <?php echo json_encode($search["date"]); ?>;
        			console.log(<?php echo json_encode($search["date"]); ?> );
        			console.log(monthYear );

        			if(monthYear){
        				monthYear = '01/'+monthYear;
        				console.log(monthYear );
        				var startDate = moment(monthYear, 'DD/MM/YYYY').unix() - 24*60*60*7;
        				var endDate = moment(monthYear, 'DD/MM/YYYY').unix() + 24*60*60*37;
        			}else{
        				var startDate = start.unix();
        				var endDate = end.unix();
        			}
        			console.log(startDate);
        			console.log(endDate);
*/
        			
	                 $.ajax({
	                 url: '<?php echo base_url() ?>schedule/listsCalendar',
	                 dataType: 'json',
	                 data: {
		                 // our hypothetical feed requires UNIX timestamps
		                 start: start.unix(),
		                 end: end.unix(),
		                 contract: <?php echo json_encode($search["contract"]); ?> ,
		                 category: <?php echo json_encode($search["category"]); ?> ,
	                 },

	                 success: function(msg) {
	                     var events = msg.events;
	                     console.log(msg.events);

	                     callback(events);

	                 }
	                 });
	             },

	             eventClick: function(event, jsEvent, view) {
			          $('#name').val(event.title);
			          $('#description').val(event.description);
			          $('#contract').val(event.contract);
			          $('#tcat_name').val(event.tcat_name);
			          $('#tract').val(event.tract);
			          $('#cycle').val(event.cycle);
			          $('#frequency').val(event.frequency);
			          $('#section_from').val(event.section_from);
			          $('#section_to').val(event.section_to);
			          $('#btime').val(event.btime);
			          $('#etime').val(event.etime);
			          $('#date').val(moment(event.start).format('MM/DD/YYYY'));
			          if(event.status == 1) {
			          	$('#status').attr("checked", true);
			          }else{
			          	$('#status').attr("checked",false);
			          }
			          $('#order').val(event.order);
			          console.log(event.status);
			          
			          /*$('#start_date').val(moment(event.start).format('YYYY/MM/DD HH:mm'));
			          if(event.end) {
			            $('#end_date').val(moment(event.end).format('YYYY/MM/DD HH:mm'));
			          } else {
			            $('#end_date').val(moment(event.start).format('YYYY/MM/DD HH:mm'));
			          }*/
			          $('#event_id').val(event.id);
			          $('#editModal').modal();
			       },

			    /*views: {
			        month: {
			            height: 'parent',

			        }
			    },*/

			    eventRender: function(event, element)
				{ 
					//console.log(event);
				    //element.find('.fc-event-title').append("<br/>" + event.description); 
				    element.find('.fc-title').after("<br/><span style=\"font-size:13px\">" + event.description + "</span>");
				    
				    /*$('#calendar').fullCalendar( 'gotoDate', 2017);*/
				},

				/*rerenderEvents: function(event, element, view)
				{
					return ['0', event.company_id].indexOf($('#company_selector').val()) >= 0
				}*/

				dragOpacity: {//设置拖动时事件的透明度
					agenda: .5,
					'':.6
				},

				/*eventDrop: function(event,dayDelta,revertFunc) {
					//console.log(allDay);
					$.post("<?php echo BASE_URL()?>schedule/drag",{"id":event.id,"daydiff":dayDelta
			        },function(msg){
			        	console.log(event);
						if(msg!=1){
							alert(msg);
							revertFunc(); //恢复原状
						}
					});
			    },*/
        		
        		eventDrop: function(event, delta, revertFunc) {

				    //alert(event.title + " was dropped on " + event.start.format());

				    //if (!confirm("Are you sure about this change?")) {
				      //revertFunc();
				    //}else{
				    	console.log(event.start.format(),event.id);
				    	$.post("<?php echo BASE_URL()?>schedule/drag", {id: event.id, dayDiff: event.start.format()}, function(data){
				    		console.log(data);
				    		console.log(data.status);
				    		if(data != 1){
				    			revertFunc();
				    		}
				    	});
				    //}

				},

        	});

        	/*$('#company_selector').on('change',function(){
			    $('#calendar').fullCalendar('events');
			})*/
        });
	</script>
</head>

<body>
	

	<div class="container" id="main">
		<div style="display:inline-block;padding:5px;">
			<span><h3>Calendar (TxDot)</h3></span>
	  		<!-- <span><a class="btn btn-primary btn-sm" href="<?php echo BASE_URL();?>/task/add">&nbsp;Add task</a></span> -->
		</div>
	  	
		<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="myModalLabel">Update Schedule</h4>
		      </div>
		      <div class="modal-body">
		      <form action="<?php echo Base_URL(); ?>schedule/updateCalendar" method="POST">
		      
		      <input type="hidden" class="form-control" name="fromurl" value="<?php echo str_replace('/ci/', '', $_SERVER['REQUEST_URI']);?>" id="fromurl" readonly>
		      <div class="form-group">
		                 <label for="p-in" class="col-md-4 label-heading">Contract</label>
		                <div class="col-md-8 ui-front">
		                    <input type="text" class="form-control" name="contract" value="" id="contract" readonly>
		                </div>
		        </div>
		      <div class="form-group">
		                <label for="p-in" class="col-md-4 label-heading">Type</label>
		                <div class="col-md-8 ui-front">
		                    <input type="text" class="form-control" name="tcat_name" value="" id="tcat_name" readonly>
		                </div>
		        </div>
		        <div class="form-group">
		                <label for="p-in" class="col-md-4 label-heading">Tract</label>
		                <div class="col-md-8 ui-front">
		                    <input type="text" class="form-control" name="tract" value="" id="tract" readonly>
		                </div>
		        </div>
		        
		        <div class="form-group">
		                <label for="p-in" class="col-md-4 label-heading">From</label>
		                <div class="col-md-8">
		                    <input type="text" class="form-control" name="section_from" id="section_from" readonly>
		                </div>
		        </div>
		        <div class="form-group">
		                <label for="p-in" class="col-md-4 label-heading">To</label>
		                <div class="col-md-8">
		                    <input type="text" class="form-control" name="section_to" id="section_to" readonly>
		                </div>
		        </div>
		       <!--  <div class="form-group">
		                <label for="p-in" class="col-md-4 label-heading">Cycle</label>
		                <div class="col-md-8">
		                    <input type="text" class="form-control" name="cycle" id="cycle" readonly>
		                </div>
		        </div> -->
		        <div class="form-group">
		                <label for="p-in" class="col-md-4 label-heading">Frequency</label>
		                <div class="col-md-8">
		                    <input type="text" class="form-control" name="frequency" id="frequency" readonly>
		                </div>
		        </div>
		        <div class="form-group">
		                <label for="p-in" class="col-md-4 label-heading">Scheduled Date</label>
		                <div class="col-md-8">
		                    <input type="text" class="form-control datetimepickerModal" name="date" id="date">
		                </div>
		        </div>
		        <div class="form-group">
	                    <label for="p-in" class="col-md-4 label-heading">Completed</label>
	                    <div class="col-md-8">
	                        <input type="checkbox" class="form-control" style="height: 25px;" name="status" id="status" value="1" >
	                    </div>
		        </div>
		        <!-- <div class="form-group">
		                    <label for="p-in" class="col-md-4 label-heading">Delete Event</label>
		                    <div class="col-md-8">
		                        <input type="checkbox" name="delete" value="1">
		                    </div>
		            </div> -->
		            <input type="hidden" name="event_id" id="event_id" value="0" />
		            <input type="hidden" name="order" id="order" value="0" />
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        <input type="submit" class="btn btn-primary" value="Update Event">
		        </form>
		      </div>
		    </div>
		  </div>
	    </div>




	   

	
	  <form class="form form-inline" action="<?php echo BASE_URL().'schedule/calendar'; ?>" method="get">	
		<div class="form-group">
			 <select id='company_selector' name='contract' class="form-control">
				<?php foreach($contract as $com){ ?>
					<option value='<?php echo $com->contract_id; ?>' <?php if($search['contract'] == $com->contract_id){ ?>selected <?php } ?> > <?php echo $com->contract_id_pannell; ?> </option>
				<?php } ?>
			</select>
		</div> 
		
		<div class="form-group" style="position:relative">
			<div class="controls" >
				<input class="form-control" type="text" name="date"  id='datetimepicker4' value="<?php echo $search['date'];?>" placeholder="Month/Year..." autocomplete="off">
			</div>
		</div> 

		<div class="form-group">
			 <select id='category' name='category' class="form-control">
				<option value='' <?php if($search['category'] == ''){ ?>selected <?php } ?> > Debris & Sweeping</option>
				<option value='1' <?php if($search['category'] == 1){ ?>selected <?php } ?> > Debris </option>
				<option value='2' <?php if($search['category'] == 2){ ?>selected <?php } ?> > Sweeping</option>
			</select>
		</div> 
		
<!-- 		<div class="form-group">
			<select name='company_type' class="form-control">
				<option value='' <?php if($search['company_type'] == '' ){echo 'selected';}?>>All Type</option>
				<option value='1' <?php if($search['company_type'] ==1){echo 'selected';}?>>TXDot</option>
				<option value='2' <?php if($search['company_type'] ==2){echo 'selected';}?>>Commercial</option>
			</select>
		</div>
		<div class="form-group">
			<select name='category' class="form-control">
				<option value='' <?php if($search['category'] == '' ){echo 'selected';}?>>All Cate</option>
				<option value='1' <?php if($search['category'] ==1){echo 'selected';}?>>Debris</option>
				<option value='2' <?php if($search['category'] ==2){echo 'selected';}?>>Sweeping</option>
				<option value='3' <?php if($search['category'] ==3){echo 'selected';}?>>TMA</option>
			</select>
		</div>
		<div class="form-group">
			<select name='orderby' class="form-control">
				<option value='' <?php if($search['orderby'] == 1 ){echo 'selected';}?>>Sort by Date</option>
				<option value='1' <?php if($search['orderby'] == 2){echo 'selected';}?>>Sort by HWY</option>
				<option value='2' <?php if($search['orderby'] == 3){echo 'selected';}?>>Sort by Type</option>
			</select>
		</div>
		<div class="form-group">
			<select multiple id="type_mul" name='type_mul' class="selectpicker form-control">
				<option value='' <?php if($search['type_mul'] == '' ){echo 'selected';}?>>All Type</option>
				<option value='1' <?php if(in_array(1, $search['type_mul'])){echo 'selected';}?>>Type I</option>
				<option value='2' <?php if(in_array(2, $search['type_mul'])){echo 'selected';}?>>Type II</option>
				<option value='8' <?php if(in_array(8, $search['type_mul'])){echo 'selected';}?>>Type I&II</option>
				<option value='3' <?php if(in_array(3, $search['type_mul'])){echo 'selected';}?>>Type III</option>
				<option value='4' <?php if(in_array(4, $search['type_mul'])){echo 'selected';}?>>Type IV</option>
				<option value='5' <?php if(in_array(5, $search['type_mul'])){echo 'selected';}?>>Type V</option>
				<option value='6' <?php if(in_array(6, $search['type_mul'])){echo 'selected';}?>>Type VI</option>
				<option value='7' <?php if(in_array(7, $search['type_mul'])){echo 'selected';}?>>Type VII</option>
			</select>
		</div>  -->
		
		<button type="submit" class="btn btn-submit btn-primary btn-sm" onclick="getVal()">Search</button>
		<?php if($search['contract'] && $search['date'] && $search['category']){ ?>
			<button class="btn btn-primary btn-sm" onclick="window.open('<?php echo BASE_URL()?>schedule/calendarDownload2/?contract=<?php echo $search['contract']?>&date=<?php echo urlencode($search['date'])?>&category=<?php echo $search['category']?>')">Download</button>
		<?php } ?>
		<!-- <a  onclick="getVal()">Search</a> -->
	</form>

	

	<hr>

	<div id="calendar">
		

		
	</div>
		<?php $this->load->view('template/footer.php');?>
	</div>

</body>
</html>