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
		    padding: 0.3em;
		    background-color: white;
		  }

	</style>

	<script>
    //Check if decimal
		function isNumber(){
			 $onblurvalue = document.getElementById("vali_num");
			 $onblurvalue.value = $onblurvalue.value.replace(/[^\d.-]/g,'');
		}

		//fill in iftravelhour and traffic_our when choose the contract
		function trafficHour(e){
			var task_id = document.getElementById("task_id").value;
			var task = <?php echo json_encode($task)?>;
			//console.log(task[0]);
			for(i = 0; i< task.length; i++){
				if(task[i]['task_id'] == task_id){
					if(task[i]['travel_hour'] == 0 || task[i]['travel_hour'] == null){
						var iftravelhour = 0;
						document.getElementById("iftravelhour").checked = false;
					}else{
						var iftravelhour = 1;
						document.getElementById("iftravelhour").checked = true;
					}

					document.getElementById("unit_price").value = task[i]['unit_price'] ;
					document.getElementById("unit_price_2").value = task[i]['unit_price_2'];
					document.getElementById("traffic_charge").value = task[i]['traffic_control_price'];
					document.getElementById("disposal_charge").value = task[i]['disposal_price'];
					document.getElementById("pon").value = task[i]['pon'];
					break;
				}
			}

			
			//console.log(unit_price,unit_price_2);
			
		}

		

		$(function () {
			//var now = new Date();
			//console.log(date.getFullYear());
			//dateFormat(now,'MM-DD-YYYY');
			//var today =  0 + (now.getMonth()+1) + '-' +now.getDate()+ '-' +now.getFullYear();
			
			
			
            $('.datetimepicker').datetimepicker({
            	format:'MM/DD/YYYY',
            	//minDate:moment()
            });
       		
       		//$('.datetimepicker').val(today);
       		

        	
            $('.datetimepicker_start').datetimepicker({
            	format:'hh:mm a',
            	useCurrent: false
            });
            //$('#btime').val('');
        
        
        
            $('.datetimepicker_end').datetimepicker({
            	format:'hh:mm a',
            	useCurrent: false
            });
        

       
	        var scntDiv = $('#addLucy');
	        var i = 1;
	        var nextTicket = 0;
	        //console.log(i);
	        $.get("<?php echo BASE_URL().'worklog/nextTicket'?>", function(data){
	        	nextTicket = data;
	        });
	        $('#addScnt').on('click', function() {
        		var addhtml = '<div id="block_'+i+'"> <div class="form-group"> <label class="col-md-2 control-label">Ticket #:</label> <div class="col-md-10"> <input type="text" class="form-control" name="ticket_id[]" value="'+nextTicket+'"> </div> </div> <div class="form-group ui-widget"> <label class="col-md-2 control-label">Driver:</label> <div class="col-md-10"> <select class="form-control" id="combobox" name="employee_id[]"> <option value=""></option> <?php foreach($driver as $dr){ ?> <option value="<?php echo $dr->id; ?>"><?php echo $dr->name; ?></option> <?php } ?> </select> </div> </div> <div class="form-group"> <label class="col-md-2 control-label">Truck:</label> <div class="col-md-10"> <select class="form-control truck_id" id="combobox" name="truck_id[]" > <option value=""></option> <?php foreach($truck as $tk=>$tv){ ?> <option value="<?php echo $tk; ?>"><?php echo $tv; ?></option> <?php } ?> </select>  </div> </div> </div>';
        		
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
    



   <!--  <div id="block"><div class="form-group"> <label class="col-md-2 control-label">Ticket #:</label> <div class="col-md-10"> <input type="text" class="form-control" name="ticket_id[]" value="'+nextTicket+'" > </div> </div> <div class="form-group"> <label class="col-md-2 control-label">Driver:</label> <div class="col-md-10"> <input type="text" class="form-control" name="employee_id[]" > </div> </div> <div class="form-group"> <label class="col-md-2 control-label">Truck:</label> <div class="col-md-10"> <input type="text" class="form-control" name="truck_id[]" > </div> </div><div class="form-group" ><div class="col-md-12 text-center" ><input type="button" class="btn btn-primary deleteScnt" id="deleteScnt" value="Delete Driver and Truck"></div></div></div> -->




</head>

<body>
	<div class="container" id="main">
		<div class="panel-heading">
			<h3>Add Schedule (Commercial)</h3>
		</div>
		<div class="panel-body">
			<form action="<?php echo BASE_URL();?>schedule/add" method='post' class='form-horizontal' enctype='multipart/form-data' onsubmit="return finalCheck();">
				<input type='hidden' name='backurl'  id='backurl' value='<?=$_SERVER['HTTP_REFERER']?>' ></input>
				<input type='hidden' name='company_type'  id='company_type' value='2' ></iput>
				<input type='hidden' name='unit_price'  id='unit_price' value=<?php echo $task[0]->unit_price ?> ></iput>
				<input type='hidden' name='unit_price_2'  id='unit_price_2' value=<?php echo $task[0]->unit_price_2 ?> ></iput>
				
		    	<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>Contract Name:</label>
					<div class="col-md-10">
						<?php if($task_id){ ?>
							<input type="text" class="form-control" name="contract_name"  autocomplete="off" value="<?php echo $task[0]->contract_id_pannell?>"  readonly >
							<input type='hidden' name='task_id'  id='task_id' value='<?php echo $task[0]->task_id?>' ></input>
						<?php }else{ ?> 
							<select class="form-control" name='task_id' id="task_id" onchange="trafficHour(event)" required>
								<?php foreach($task as $t){?>
									<option value="<?php echo $t->task_id;?>"><?php echo $t->contract_id_pannell;?></option>
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
						<input type="text" class="form-control" name="location" id="location" autocomplete="off">
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
					<label class="col-md-2 control-label"><i>*</i>Date:</label>
					<div class='col-md-10 date'>
	                    <input type='text' class="form-control datetimepicker" id='date' name="date" autocomplete="off" required>
	                </div> 
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label"><i>*</i>Request Start Time:</label>
					<div class='col-md-10 date'>
	                    <input type='text' class="form-control datetimepicker_start" id='btime_req' name="btime_req" autocomplete="off" required>
	                </div> 
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Actual Start Time:</label>
					<div class='col-md-10 date'>
	                    <input type='text' class="form-control datetimepicker_start" id='btime' name="btime" autocomplete="off">
	                </div> 
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">End Time:</label>
					<div class='col-md-10 date'>
	                    <input type='text' class="form-control datetimepicker_end" id='etime' name="etime" autocomplete="off" >
	                </div> 
				</div>
		    	
		    	<div class="form-group">
					<label class="col-md-2 control-label">Options:</label>
					<div class='col-md-10'>
						<div class='col-md-10 checkbox' style="padding-bottom: 8px">
							<input type='checkbox' name="unscheduled" value="1" >Unscheduled
						</div>
						<div class='col-md-3 checkbox'>
							<input type='checkbox' name="iftravelhour" value="1" id="iftravelhour" <?php if($task[0]->travel_hour){?> checked <?php } ?> >Travel Hour
						</div>
						<div class='col-md-3 input-group'>
							<input type='text' class="form-control" name="travel_hour" value="2" >
							<span class="input-group-addon"> hr</span>
						</div>
						<div class='col-md-3 checkbox'>
							<input type='checkbox' name="iftraffic" id="iftraffic" value="1" >Traffic Control
						</div>
						<div class='col-md-3 input-group'>
							<span class="input-group-addon">$</span>
							<input type='text' class="form-control" name="traffic_charge" id="traffic_charge" value="100.00" >
							<span class="input-group-addon">/hr</span>
						</div>
	                    <div class='col-md-3 checkbox'>
							<input type='checkbox' name="ifdisposal" value="1" >Disposal
						</div>
						<div class='col-md-3 input-group'>
							<span class="input-group-addon">$</span>
							<input type='text' class="form-control" name="disposal_charge" id="disposal_charge" value="300.00">
						</div>
						
	                </div> 
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">*Contact:</label>
					<div class='col-md-10'>
	                    <input type='text' class="form-control" id='contact' name="contact" autocomplete="off"  placeholder="e.g.: John Smith 972-000-0000" required>
	                </div> 
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">PO#:</label>
					<div class='col-md-10'>
	                    <input type='text' class="form-control" id='pon' name="pon" autocomplete="off" value='<?php echo $task[0]->pon; ?>' >
	                </div> 
				</div>

				<div class="form-group">
					<label class="col-md-2 control-label">Comment:</label>
					<div class='col-md-10'>
	                    <input type='text' class="form-control" id='comment' name="comment" autocomplete="off"  placeholder="e.g.: Call John upon arrival, leave at 3pm" maxlength=160>
	                </div> 
				</div>

				<hr>

				<div class="form-group" >
					<div class="col-md-12 text-center" >
	                    <input type='button' class="btn btn-primary" id='addScnt' value="Add A Ticket">
	                </div> 
				</div>
					
				<!-- <div style="display:inline;">
					

					<div class="form-group" >
						<div class="col-md-4 text-center" >
		                    <input type='button' class="btn btn-primary" id='deleteScnt' value="Delete A Ticket">
		                </div> 
					</div>
				</div> -->
				


				<div id="addLucy">
					




				</div>


				
				

				<hr>
				


				<div class="form-group">
					<div class="col-md-12 text-center" >
						<input type='submit' class='btn btn-primary btn-submit' value='Submit' >
						<input type='button' onclick='history.go(-1)' class='btn btn-primary' value='Cancel'/>
					</div>
				</div>
				

			</form>
		</div>
	  
	  
	</div>

</body>
</html>