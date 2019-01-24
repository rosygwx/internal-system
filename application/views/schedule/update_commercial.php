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
       		
       		//var scheduleDate = document.getElementById('scheduleDate').value;
       		//$('.datetimepicker').val(scheduleDate);
       		
       		$('.datetimepicker_start_req').datetimepicker({
            	format:'hh:mm a',
            	useCurrent: false
            });

        	
            $('.datetimepicker_start').datetimepicker({
            	format:'hh:mm a',
            	useCurrent: false
            });
        
        
        
            $('.datetimepicker_end').datetimepicker({
            	format:'hh:mm a',
            	useCurrent: false
            });
        

       
	        var scntDiv = $('#addLucy');
	        var i = <?php echo count($worklog) + 1; ?>;
	        var nextTicket = 0;
	        
	        $.get("<?php echo BASE_URL().'worklog/nextTicket'?>", function(data){
	        	nextTicket = data;
	        });
	        $('#addScnt').on('click', function() {
        		var addhtml = '<div id="block_'+i+'"><input type="hidden" name="wl_id[]" value=""> <div class="form-group"> <label class="col-md-2 control-label">Ticket #:</label> <div class="col-md-10"> <input type="text" class="form-control" name="ticket_id[]" value="'+nextTicket+'"> </div> </div> <div class="form-group ui-widget"> <label class="col-md-2 control-label">Driver:</label> <div class="col-md-10"> <select class="form-control" id="combobox" name="employee_id[]"> <option value=""></option> <?php foreach($driver as $dk=>$dr){ ?> <option value="<?php echo $dk; ?>"><?php echo $dr; ?></option> <?php } ?> </select> </div> </div> <div class="form-group"> <label class="col-md-2 control-label">Truck#:</label> <div class="col-md-10"><select class="form-control truck_id" id="combobox" name="truck_id[]"> <option value=""></option> <?php foreach($truck as $tk=>$tv){ ?> <option value="<?php echo $tk; ?>"><?php echo $tv; ?></option> <?php } ?> </select>  </div> </div> </div>';
        		
                $(addhtml).appendTo(scntDiv);
                i++;
                nextTicket++;
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
                    nextTicket--;
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
			<h3>Update Schedule (Commercial)</h3>
		</div>
		<div class="panel-body">
			<form action="<?php echo BASE_URL();?>schedule/updateCom" method='post' class='form-horizontal' enctype='multipart/form-data' onsubmit="return finalCheck();">
				<input type='hidden' name='backurl'  id='backurl' value='<?=$_SERVER['HTTP_REFERER']?>' ></input>
				<input type='hidden' name='schedule_id'  id='schedule_id' value='<?php echo $schedule->schedule_id?>' ></input>
				<input type='hidden' name='company_type'  id='company_type' value='2' ></input>
				<input type='hidden' name='unit_price'  id='unit_price' value='<?php echo $task[0]->unit_price?>' ></input>
				<input type='hidden' name='unit_price_2'  id='unit_price_2' value='<?php echo $task[0]->unit_price_2?>' ></input>
				
		    	<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>Contract Name:</label>
					<div class="col-md-10">
						<!--
						<input type="text" class="form-control" name="contract_name"  autocomplete="off" value="<?php echo $schedule->contract_id_pannell; ?>" >
						-->
						<?php if($task_id){ ?>
							<input type="text" class="form-control" name="contract_name"  autocomplete="off" value="<?php echo $task[0]->contract_id_pannell?>"  readonly >
							<input type='hidden' name='task_id'  id='task_id' value='<?php echo $task[0]->task_id?>' ></input>
						<?php }else{ ?> 
							<select class="form-control" name='task_id' id="task_id" onchange="trafficHour(event)">
								<?php foreach($task as $t){?>
									<option value="<?php echo $t->task_id;?>" <?php if($t->task_id == $schedule->task_id){?> selected <?php } ?> ><?php echo $t->contract_id_pannell;?></option>
								<?php } ?>
							</select>
						<?php } ?>
						
					</div>
				</div>

				<!-- <div class="form-group">
					<label class="col-md-2 control-label">Ticket ID:</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="ticket_id">
					</div>
				</div> -->

				<div class="form-group">
					<label class="col-md-2 control-label">Location:</label>
					<div class="col-md-10">
						<input type="text" class="form-control" name="location" id="location" value="<?php echo $schedule->location;?>" >
					</div>
				</div>

				<!-- <div class="form-group"  style="vertical-align: middle;">
					<label class="col-md-2 control-label"><i>*</i>Scheduled/<br>Unscheduled:</label>
					<div class="col-md-1">
						<input type="radio" name="ifschedule" value="1" checked>Scheduled
					</div>
					<div class="col-md-2">
						<input type="radio" name="ifschedule" value="2">Unscheduled
					</div>
				</div> -->

				<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>Schedule Date:</label>
					<div class='col-md-10 date'>
	                    <input type='text' class="form-control datetimepicker" id='scheduleDate' name="date"  value="<?php echo $schedule->schedule_date; ?> " autocomplete="off" required>
	                </div> 
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>Request Start Time:</label>
					<div class='col-md-10 date'>
	                    <input type='text' class="form-control datetimepicker_start_req" id='btime_req' name="btime_req" value="<?php echo $schedule->btime_req; ?>"  autocomplete="off" required>
	                </div> 
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Actual Start Time:</label>
					<div class='col-md-10 date'>
	                    <input type='text' class="form-control datetimepicker_start" id='btime' name="btime" value="<?php echo $schedule->btime; ?>" autocomplete="off" >
	                </div> 
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">End Time:</label>
					<div class='col-md-10 date'>
	                    <input type='text' class="form-control datetimepicker_end" id='etime' name="etime" value="<?php echo $schedule->etime; ?>" autocomplete="off" >
	                </div> 
				</div>
		    	
		    	<div class="form-group">
					<label class="col-md-2 control-label">Options:</label>
					<div class='col-md-10'>
						<div class='col-md-10 checkbox' style="padding-bottom: 8px">
							<input type='checkbox' name="unscheduled" value="1" <?php if($schedule->unscheduled){ ?> checked <?php } ?> >Unscheduled
						</div>
						<div class='col-md-3 checkbox'>
							<input type='checkbox' name="iftravelhour" value="1" id="iftravelhour" <?php if($schedule->iftravelhour){?> checked <?php } ?> >Travel Hour
						</div>
						<div class='col-md-3 input-group'>
							<input type='text' class="form-control" name="travel_hour"  value="<?php echo $schedule->travel_hour?>"  >
							<span class="input-group-addon"> hr</span>
						</div>
						<div class='col-md-3 checkbox'>
							<input type='checkbox' name="iftraffic" value="1" id="iftraffic" <?php if($schedule->iftraffic){ ?> checked <?php } ?>>Traffic Control
						</div>
						<div class='col-md-3 input-group'>
							<span class="input-group-addon">$</span>
							<input type='text' class="form-control vali_num" name="traffic_charge" value="<?php echo $schedule->traffic_control_price; ?>" >
							<span class="input-group-addon">/hr</span>
						</div>
	                    <div class='col-md-3 checkbox'>
							<input type='checkbox' name="ifdisposal" value="1" <?php if($schedule->ifdisposal){ ?> checked <?php } ?>>Disposal
						</div>
						<div class='col-md-3 input-group'>
							<span class="input-group-addon">$</span>
							<input type='text' class="form-control vali_num" name="disposal_charge" value="<?php echo $schedule->disposal_price; ?>">
						</div>
						
	                </div> 
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Contact:</label>
					<div class='col-md-10'>
	                    <input type='text' class="form-control" id='contact' name="contact" value="<?php echo $schedule->contact; ?>" >
	                </div> 
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Po#:</label>
					<div class='col-md-10'>
	                    <input type='text' class="form-control" id='pon' name="pon" value="<?php echo $schedule->pon; ?>" autocomplete="off">
	                </div> 
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Comment:</label>
					<div class='col-md-10'>
	                    <input type='text' class="form-control" id='comment' name="comment" value="<?php echo $schedule->comment; ?>" maxlength=160>
	                </div> 
				</div>

				<div class="form-group form-inline">
					<label class="col-md-2 control-label"><i>*</i>Status:</label>
					<div  class='col-md-10' >
						<input type='radio' name="status" class="status" value="1" <?php if($schedule->status){ ?> checked <?php } ?>>Completed
						<input type='radio' name="status" class="status" value="0" style="margin-left:30px" <?php if($schedule->status==0){ ?> checked <?php } ?>>Incompleted
						<input type='radio' name="status" class="status" value="3" style="margin-left:30px" <?php if($schedule->status==3){ ?> checked <?php } ?>>Cancelled - Not Bill
						<input type='radio' name="status" id="status2" value="2" style="margin-left:30px" <?php if($schedule->status==2){ ?> checked <?php } ?>>Cancelled - Bill

							<div class='col-md-2 input-group' ><input type='text' class='form-control' name='cancel_hour'  id='cancel_hour' value='<?php echo $schedule->cancel_hour;?>'   ><span class='input-group-addon'> hr</span></div>

						
	                </div>
				</div>

				<hr>

				<div class="form-group" >
					<div class="col-md-12 text-center" >
	                    <input type='button' class="btn btn-primary" id='addScnt' value="Add A Ticket">
	                    <input type='button' class="btn btn-primary" id='deleteScnt' value="Delete A Ticket">
	                </div> 
				</div>


				<div id="addLucy">
					<?php if($worklog){ for($i=0;$i<count($worklog);$i++){ ?>
					<div id="block_<?php echo $i+1; ?>"> 
						<input type="hidden" name="wl_id[]" value="<?php echo $worklog[$i]->wl_id; ?>">
						<div class="form-group"> 
							<label class="col-md-2 control-label">Ticket #:</label> 
							<div class="col-md-10"> 
								<input type="text" class="form-control" name="ticket_id[]" value="<?php echo $worklog[$i]->ticket_id; ?>"> 
							</div> 
						</div> 
						<div class="form-group "> 
							<label class="col-md-2 control-label">Driver:</label> 
							<div class="col-md-10"> 
								<select class="form-control"  name="employee_id[]"> 
									<option value=""></option> 
									<?php foreach($driver as $dk=>$dv){ ?> 
										<option value="<?php echo $dk; ?>" <?php if($worklog[$i]->employee_id == $dk){?> selected <?php } ?> ><?php echo $dv; ?></option> 
									<?php } ?> 
								</select> 
							</div> 
						</div> 
						<div class="form-group"> 
							<label class="col-md-2 control-label">Truck#:</label> 
							<div class="col-md-10"> 
								<select class="form-control" name="truck_id[]"> 
									<option value=""></option> 
									<?php foreach($truck as $tk=>$tv){ ?> 
										<option value="<?php echo $tk; ?>" <?php if($worklog[$i]->truck_id == $tk){?> selected <?php } ?> ><?php echo $tv; ?></option> 
									<?php } ?> 
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