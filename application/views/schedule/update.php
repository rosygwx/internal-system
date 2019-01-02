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

	  .custom-combobox {
	    position: relative;
	    display: inline-block;
	  }
	  .custom-combobox-toggle {
	    position: absolute;
	    top: 0;
	    bottom: 0;
	    margin-left: -1px;
	    padding: 0;
	    right:13px;

	  }
	  .custom-combobox-input {
	    margin: 0;
	    padding: 1.0em;
	    background-color: white;
	  }

	 

        .width9{
        	width: 9%;
        }

        .width7{
        	width: 7%;
        }

		/*.timepicker {
			height: 2em;
		}*/

		.scheduleTable {
			min-height:300px;
			max-height:700px;
			overflow-y:scroll;  /*纵向滚动条始终显示 */
			overflow-x:none;
		}

		.table td{
		  position:relative;
		}

		th, .text-center {
			text-align: center;
		}

		.lalala {
			margin-bottom: 33px;
		}
	</style>

	<script>
    //Check if decimal
		function isNumber(){
			 $onblurvalue = document.getElementByClass("vali_num");
			 $onblurvalue.value = $onblurvalue.value.replace(/[^\d.-]/g,'');
		}
		$(function () {
			//var now = new Date();
			//console.log(date.getFullYear());
			//dateFormat(now,'MM-DD-YYYY');
			//var today =  0 + (now.getMonth()+1) + '-' +now.getDate()+ '-' +now.getFullYear();
			
			

             $('.datetimepicker').datetimepicker({
            	format:'MM/DD/YYYY',
            	useCurrent: false
            });
       		
       		$('.timepicker').datetimepicker({
            	format:'HH:mm',
            	useCurrent: false
            });
        

       
	        var scntDiv = $('#addLucy');
	        var i = <?php echo count($worklog) + 1;?>;
	        console.log(i);
	        /*$.get("<?php echo BASE_URL().'worklog/nextTicket'?>", function(data){
	        	nextTicket = data;
	        });*/
	        $('#addScnt').on('click', function() {
        		//var addhtml = '<div id="block_'+i+'" class="lalala"><input type="hidden" name="wl_id[]" value="">  <div class="form-group ui-widget"> <label class="col-md-2 control-label">Driver:</label> <div class="col-md-10"> <select class="form-control" id="combobox" name="employee_id[]"> <option value=""></option> <?php foreach($driver as $dk=>$dr){ ?> <option value="<?php echo $dk; ?>"><?php echo $dr; ?></option> <?php } ?> </select> </div> </div> <div class="form-group"> <label class="col-md-2 control-label">Truck#:</label> <div class="col-md-10"><select class="form-control truck_id" id="combobox" name="truck_id[]"> <option value=""></option> <?php foreach($truck as $tk=>$tv){ ?> <option value="<?php echo $tk; ?>"><?php echo $tv; ?></option> <?php } ?> </select>  </div> </div> </div>';
        		
        		var addhtml = '<div id="block_'+i+'" class="lalala"> <div class="form-group "> <input type="hidden" name="wl_id[]" value="">             <label class="col-md-1 control-label">Driver:</label> <div class="col-md-3"> <select class="form-control" name="employee_id[]"> <option value="0"></option> <?php foreach($driver as $dk=>$dv){ ?> <option value="<?php echo $dk; ?>" <?php if($worklog[$i]->employee_id == $dk){?> selected <?php } ?> ><?php echo $dv; ?></option> <?php } ?> </select> </div>                 <label class="col-md-1 control-label">Truck#:</label> <div class="col-md-3"> <select class="form-control"  name="truck_id[]"> <option value="0"></option> <?php foreach($truck as $tk=>$tv){ ?> <option value="<?php echo $tk; ?>" <?php if($worklog[$i]->truck_id == $tk){?> selected <?php } ?> ><?php echo $tv; ?></option> <?php } ?> </select> </div>               <label class="col-md-1 control-label">Crew#:</label> <div class="col-md-3"> <select class="form-control"  name="crew_wl_id[]"> <option value=1 <?php if($worklog[$i]->crew_id == 1){?> selected <?php } ?> > 1 </option> <option value=2 <?php if($worklog[$i]->crew_id == 2){?> selected <?php } ?> > 2 </option> <option value=3 <?php if($worklog[$i]->crew_id == 3){?> selected <?php } ?> > 3 </option> </select> </div>          </div>';

                $(addhtml).appendTo(scntDiv);
                i++;
			});

			$('#deleteScnt').on('click', function() { 
				console.log(i);
	        	if( i >= 1 ) {
		        	var parent = document.getElementById("addLucy");
		        	var removeId = "block_" + (i-1);
		        	console.log(removeId);
					var child = document.getElementById(removeId);
					parent.removeChild(child);

                    //$(this).parents('p').remove();
                    i--;
                }
       		 });

			$("#location").autocomplete({
				source: function(term, response){
					var taskId = document.getElementById('task_id').value;
					$.getJSON('<?php echo BASE_URL().'schedule/autocompleteLocation';?>', {q: term, t: taskId}, function(data) {
						response(data);
						
					});
				}
			});

			$("#contact").autocomplete({
				source: function(term, response){
					var taskId = document.getElementById('task_id').value;
					$.getJSON('<?php echo BASE_URL().'schedule/autocompleteContact';?>', {q: term, t: taskId}, function(data) {
						response(data);
					});
				}
			});

			$('#status2').change(function(){
				var stanCanHour = <?php echo json_encode($schedule->standard_cancel_hour); ?>;
				var billHour = <?php echo json_encode($schedule->cancel_hour); ?>;
				var cancelHour = billHour ? billHour : stanCanHour;
				
				document.getElementById("cancel_hour").value = cancelHour ;
			});

			$('.status').change(function(){
				document.getElementById("cancel_hour").value = '';
			});

			$('.status-schedule').change(function(){

			});

        });

        (function( $ ) {
	    $.widget( "custom.combobox", {
	      _create: function() {
	        this.wrapper = $( "<div>" )
	          //.addClass( "custom-combobox" )
	          .insertAfter( this.element );
	 
	        this.element.hide();
	        this._createAutocomplete();
	        this._createShowAllButton();
	      },
	 
	      _createAutocomplete: function() {
	        var selected = this.element.children( ":selected" ),
	          value = selected.val() ? selected.text() : "";
	 
	        this.input = $( "<input>" )
	          .appendTo( this.wrapper )
	          .val( value )
	          .attr( "title", "" )
	          .addClass( "custom-combobox-input form-control" )
	          .autocomplete({
	            delay: 0,
	            minLength: 0,
	            source: $.proxy( this, "_source" )
	          })
	          .tooltip({
	            tooltipClass: "ui-state-highlight"
	          });
	 
	        this._on( this.input, {
	          autocompleteselect: function( event, ui ) {
	            ui.item.option.selected = true;
	            this._trigger( "select", event, {
	              item: ui.item.option
	            });
	          },
	 
	          autocompletechange: "_removeIfInvalid"
	        });
	      },
	 
	      _createShowAllButton: function() {
	        var input = this.input,
	          wasOpen = false;
	 
	        $( "<a>" )
	          .attr( "tabIndex", -1 )
	          .attr( "title", "Show All Items" )
	          .tooltip()
	          .appendTo( this.wrapper )
	          .button({
	            icons: {
	              primary: "ui-icon-triangle-1-s"
	            },
	            text: false
	          })
	          .removeClass( "ui-corner-all" )
	          .addClass( "custom-combobox-toggle ui-corner-right" )
	          .mousedown(function() {
	            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
	          })
	          .click(function() {
	            input.focus();
	 
	            // 如果已经可见则关闭
	            if ( wasOpen ) {
	              return;
	            }
	 
	            // 传递空字符串作为搜索的值，显示所有的结果
	            input.autocomplete( "search", "" );
	          });
	      },
	 
	      _source: function( request, response ) {
	        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
	        response( this.element.children( "option" ).map(function() {
	          var text = $( this ).text();
	          if ( this.value && ( !request.term || matcher.test(text) ) )
	            return {
	              label: text,
	              value: text,
	              option: this
	            };
	        }) );
	      },
	 
	      _removeIfInvalid: function( event, ui ) {
	 
	        // 选择一项，不执行其他动作
	        if ( ui.item ) {
	          return;
	        }
	 
	        // 搜索一个匹配（不区分大小写）
	        var value = this.input.val(),
	          valueLowerCase = value.toLowerCase(),
	          valid = false;
	        this.element.children( "option" ).each(function() {
	          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
	            this.selected = valid = true;
	            return false;
	          }
	        });
	 
	        // 找到一个匹配，不执行其他动作
	        if ( valid ) {
	          return;
	        }
	 
	        // 移除无效的值
	        this.input
	          .val( "" )
	          .attr( "title", value + " didn't match any item" )
	          .tooltip( "open" );
	        this.element.val( "" );
	        this._delay(function() {
	          this.input.tooltip( "close" ).attr( "title", "" );
	        }, 2500 );
	        this.input.data( "ui-autocomplete" ).term = "";
	      },
	 
	      _destroy: function() {
	        this.wrapper.remove();
	        this.element.show();
	      }
	    });
	  })( jQuery );
	 
	  $(function() {
	    $( "#combobox" ).combobox();
	    $( "#toggle" ).click(function() {
	      $( "#combobox" ).toggle();
	    });
	  });

	  function finalCheck(){
		var ifTraffic = $('#iftraffic').prop("checked");
		var trafficCharge = document.getElementById('traffic_charge').value;

		//count dump truck
		var truck = <?php echo json_encode($truck);?>;
		var dumpNum = 0;
		$(".truck_id").each(function(){
			var truckId = $(this).val();
			var truckName = truck[truckId];
			console.log(truckId, truckName);
			truckName.indexOf("DUM") > -1 ? dumpNum ++ : '';
		});

		if(dumpNum != 0  && (ifTraffic == false || trafficCharge == 0 || trafficCharge == null)){
			alert("Please select the traffic control service and enter its price.")
			return false;
		}
		return true;
		//return dumpNum != 0  && (ifTraffic == false || trafficCharge == 0 || trafficCharge == null) ?  false : true;
		/*var lucy = dumpNum != 0  && (ifTraffic == false || trafficCharge == 0 || trafficCharge == null) ?   0 : 1;

		console.log(lucy);
		return false;*/

	}
    </script>
    


</head>

<body>
	<div class="container" id="main">
		<div class="panel-heading">
			<h3>Update Schedule (TxDot)</h3>
		</div>
		<div class="panel-body">
			<form action="<?php echo BASE_URL();?>schedule/update" method='post' class='form-horizontal' enctype='multipart/form-data' onsubmit="return finalCheck();">
				<input type='hidden' name='backurl'  id='backurl' value='<?=$_SERVER['HTTP_REFERER']?>' ></input>
				<input type='hidden' name='contract_id'  id='contract_id' value='<?php echo $schedule['contract_id']?>' ></input>
				<input type='hidden' name='schedule_date'  id='schedule_date' value='<?php echo $schedule['schedule_date']?>' ></input>
				<input type='hidden' name='category'  id='category' value='<?php echo $schedule['category']?>' ></input>
				<input type='hidden' name='company_type'  id='company_type' value='2' ></input>
				
		    	<div class="form-group">
					<label class="col-md-2 control-label">Contract Name:</label>
					<div class="col-md-10">
						<div><?php echo $schedule['contract_id_pannell']?></div>
					</div>
				</div>

				

				<div class="form-group">
					<label class="col-md-2 control-label">Category:</label>
					<div class="col-md-10">
						<div><?php echo $schedule['category'] == 1 ? 'Debris' : 'Sweeping'?></div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Total Actual Mileage:</label>
					<div class="col-md-10">
						<div><?php echo $schedule['mile_all']?></div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Schedule Date:</label>
					<div class="col-md-10">
						<div><?php echo date('D, m/d/Y', strtotime($schedule['schedule_date']))?></div>
					</div>
				</div>

				<!-- <div class="form-group">
					<label class="col-md-2 control-label">Complete Date:</label>
					<div class="col-md-10 date">
						<input type="text" class="form-control datetimepicker" name="date" id="location" value="<?php echo $schedule['date'];?>" >
					</div>
				</div> -->
				
				<div class="form-group">
					<label class="col-md-2 control-label">Schedule:</label>
					
				</div>

				<div class="form-group">
					<!-- <label class="col-md-2 control-label">Schedule:</label> -->
					<div class="col-md-12 scheduleTable">
						<table class="table table-responsive table-striped table-bordered table-hover dataTable no-footer dtr-inline">
							<thead>
								<tr>
									<th>Tract</th>
									<th>Type</th>
									<th>HWY</th>
									<th>From</th>
									<th>To</th>
									<th>Mile</th>
									<th class="width9">Schedule<br>Date</th>
									<th class="width7">Start<br>Time</th>
									<th class="width7">Stop<br>Time</th>
									<th class="width9">Complete<br>Date</th>
									<th class="width9">Crew</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($task as $t){?>
								<input type='hidden' name='task_id_arr[]' value='<?php echo $t['task_id']?>' ></input>
								<input type='hidden' name='schedule_id_arr[]'  value='<?php echo $t['schedule_id']?>' ></input>
								<tr class="gradA odd" role="row">
									<td class="text-center"><?php echo $t['tract'];?></td>
									<td class="text-center"><?php echo $t['txdot_type'];?></td>
									<td class="text-center"><?php echo $t['hwy_id'];?></td>
									<td><?php echo $t['section_from'];?></td>
									<td><?php echo $t['section_to'];?></td>
									<td><?php echo $t['mile'];?></td>
									
									<td class="date"><input class="datetimepicker form-control" type="text" name="schedule_date_arr[]" value="<?php echo $t['schedule_date'];?>" autocomplete="off"></td>

									<td class="date"><input class="timepicker form-control" type="text" name="btime[]" value="<?php echo $t['btime'];?>" autocomplete="off"></td>
									<td class="date"><input class="timepicker form-control" type="text" name="etime[]" value="<?php echo $t['etime'];?>" autocomplete="off"></td>
									<td class="date"><input class="datetimepicker form-control" type="text" name="date[]" value="<?php echo $t['date'];?>" autocomplete="off"></td>
									<td>
										<select class="form-control" name="crew_id[]">
											<option value="1" <?php if($t['crew_id'] == 1){?> selected <?php } ?> >1</option>
											<option value="2" <?php if($t['crew_id'] == 2){?> selected <?php } ?> >2</option>
											<option value="3" <?php if($t['crew_id'] == 3){?> selected <?php } ?> >3</option>
										</select>
									</td>
									<!-- <td><input class="status-schedule" type="checkbox" name="status[]" value="<?php echo $t['schedule_id']?>" <?php if($t['status']==1){ ?> checked <?php } ?> ></td> -->
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>
				</div>



				<hr>

				<div class="form-group" >
					<div class="col-md-12 text-center" >
	                    <input type='button' class="btn btn-primary" id='addScnt' value="Add Drivers and Trucks">
	                    <input type='button' class="btn btn-primary" id='deleteScnt' value="Delete Drivers and Trucks">
	                </div> 
				</div>


				<div id="addLucy">
					<?php if($worklog){ for($i=0;$i<count($worklog);$i++){ ?>
					<div id="block_<?php echo $i+1; ?>" class="lalala"> 
						<input type="hidden" name="wl_id[]" value="<?php echo $worklog[$i]->wl_id; ?>">
						<div class="form-group "> 
							<label class="col-md-1 control-label">Driver:</label> 
							<div class="col-md-3"> 
								<select class="form-control" name="employee_id[]"> 
									<option value="0"></option> 
									<?php foreach($driver as $dk=>$dv){ ?> 
										<option value="<?php echo $dk; ?>" <?php if($worklog[$i]->employee_id == $dk){?> selected <?php } ?> ><?php echo $dv; ?></option> 
									<?php } ?> 
								</select> 
							</div> 

							<label class="col-md-1 control-label">Truck#:</label> 
							<div class="col-md-3"> 
								<select class="form-control" name="truck_id[]"> 
									<option value="0"></option> 
									<?php foreach($truck as $tk=>$tv){ ?> 
										<option value="<?php echo $tk; ?>" <?php if($worklog[$i]->truck_id == $tk){?> selected <?php } ?> ><?php echo $tv; ?></option> 
									<?php } ?> 
								</select> 
							</div> 

							<label class="col-md-1 control-label">Crew#:</label> 
							<div class="col-md-3"> 
								<select class="form-control" name="crew_wl_id[]"> 
									<option value=1 <?php if($worklog[$i]->crew_id == 1){?> selected <?php } ?> > 1 </option> 
									<option value=2 <?php if($worklog[$i]->crew_id == 2){?> selected <?php } ?> > 2 </option> 
									<option value=3 <?php if($worklog[$i]->crew_id == 3){?> selected <?php } ?> > 3 </option> 
									
								</select> 
							</div> 
						</div> 
						
					</div>
					<?php } } ?>
				</div>

				<hr>
				


				<div class="form-group">
					<div class="col-md-12 text-center" >
						<input type='submit' class='btn btn-primary btn-submit' value='Submit'>
						<input type='button' onclick='history.go(-1)' class='btn btn-primary' value='Cancel'/>
					</div>
				</div>
				

			</form>
		</div>
	  
	  
	</div>

</body>
</html>