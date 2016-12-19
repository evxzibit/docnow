<?php
	include_once 'custom_modules/common.php';

	if (!isset($_GET['appointmentId'])) {
		echo 'Appointment reference not found.';
		exit;
	}
	$appointment_id = $_GET['appointmentId'];

	$appointmentDetails = getPatientAppointmentById($appointment_id);

	if (empty($appointmentDetails)) {
		echo 'Appointment details not found.';
		exit;
	}

	$doctorProfileId = $appointmentDetails['doctor_profile_id'];
	$i =1;
	$date = new DateTime('Africa/Johannesburg');
	$startdate = $date->format('Y-m-d');
	$date->add(new DateInterval('P2D'));
	$enddate = $date->format('Y-m-d');
	$date->add(new DateInterval('P1D'));
	$nextdate = $date->format('Y-m-d');

	$starttime = strtotime('08:00');
	$endtime = strtotime('16:30');

	$days = daysBetween(strtotime($startdate), strtotime($enddate));      
	$bookedDates = getDoctorAppointmentsdates($doctorProfileId, $startdate, $enddate);
?>

<div class="modal-header">
<span class="session-write-script hidden"><?='/live/session_write.php'?></span>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<h4 class="modal-title" id="myModalLabel">Appointment details</h4>
</div>
<form id="confirm-patient-reshedule" action="/live/reshedulepatientbooking.php" method="post">
	<div class="modal-body">
		<div class="table-container booking-details">
			<table class="table table-responsive">
				<tr>
					<th>Doctor</th>
					<td><?=$appointmentDetails['doctorName']?></td>
				</tr>	
				<tr class="info">
					<?php
						$bookingStartDate = date('d-F-Y H:i', strtotime($appointmentDetails['start_date']));
						$bookingEndDate = date('d-F-Y H:i', strtotime($appointmentDetails['end_date']));
					?>
					<th>Current appointment date and time</th>
					<td class="patient-name">
						<strong>Start:</strong> <?=$bookingStartDate?>
						<br><strong>End:</strong> <?=$bookingEndDate?>
					</td>
				</tr>
				<tr class="success">
					<th>New appointment date and time</th>
					<td>
						<strong>Start:</strong> <span id="display-new-start-date"></span>
						<br><strong>End:</strong> <span id="display-new-end-date"></span>
					</td>
				</tr>
			</table>
			<input type='hidden' class="hidden" name="appointment_id" value="<?=$appointment_id?>" id="appointmentId" />	
		</div>

<input type="hidden" name="profile_id_<?=$i?>" id="profile_id_<?=$i?>" value="<?=$doctorProfileId?>">

<div id="calendar_<?=$i?>">
	<script type="text/javascript">          
 
              $(document).ready(function() {
						console.log('window.location.href', window.location.href)
					var trs = $("#internalActivities<?=$i?> tr");
					var btnMore = $("#show_more<?=$i?>");
					var btnLess = $("#show_less<?=$i?>");
					var trsLength = trs.length;
					var currentIndex = 5;

					trs.hide();
					trs.slice(0, 5).show(); 
					checkButton<?=$i?>(trsLength, btnMore, btnLess);

					btnMore.click(function (e) { 
					    e.preventDefault();
					    $("#internalActivities<?=$i?> tr").slice(currentIndex, currentIndex + 5).show();
					    currentIndex += 5;
					    checkButton<?=$i?>(trsLength,btnMore, btnLess);
					});

					btnLess.click(function (e) { 
					    e.preventDefault();
					    $("#internalActivities<?=$i?> tr").slice(currentIndex - 5, currentIndex).hide();          
					    currentIndex -= 5;
					    checkButton<?=$i?>(trsLength, btnMore, btnLess);
					});

                	$(document).on('click', '.reschedule-new-date', function() {
                		var thisBtn = $(this),
                			choosenStartDate = thisBtn.data('startDate'),
                			choosenEndDate = thisBtn.data('endDate');
                		
                		$('#reschedule_start_date').val(choosenStartDate);
                		$('#reschedule_end_date').val(choosenEndDate);
                		$('#display-new-start-date').html(choosenStartDate);
                		$('#display-new-end-date').html(choosenEndDate);
                	});

				  $(document).on("click","a.next", function () {
				    var id = $(this).attr("id");
				    var nextdate = $('#nextdate_' + id).val();
				    var profile_id = $('#profile_id_' + id).val();

				    jQuery.ajax({
				      url: '/live/pagebuilder/components/docnow/freetimes.php?startdate=' + nextdate + '&profile_id=' + profile_id + '&id=' + id + '&action=next&viewonly=1',
				      type: 'get',
				      dataType: 'html',
				      success: function (obj) {
				       if(obj){
				            $('#calendar_' + id).html(obj);
				        }  
				      },
				      error: function () {
				        alert ('There was a problem. Please try again.');
				      }
				    
				    });
				  });

				  $(document).on("click","a.back", function () {
				    var id = $(this).attr("id");
				    var startdate = $('#startdate_' + id).val();
				    var profile_id = $('#profile_id_' + id).val();

				    jQuery.ajax({
				      url: '/live/pagebuilder/components/docnow/freetimes.php?startdate=' + startdate + '&profile_id=' + profile_id + '&id=' + id + '&action=back&viewonly=1',
				      type: 'get',
				      dataType: 'html',
				      success: function (obj) {
				       if(obj){				        
				            $('#calendar_' + id).html(obj);
				        }  
				      },
				      error: function () {
				        alert ('There was a problem. Please try again.');
				      }
				    
				    });
				  });

              });

              function checkButton<?=$i?>(trsLength, btnMore, btnLess) {
                  var currentLength = $("#internalActivities<?=$i?> tr:visible").length;

                  if (currentLength >= trsLength) {
                      btnMore.hide();            
                  } else {
                      btnMore.show();   
                  }
                  
                  if (trsLength > 5 && currentLength > 5) {
                      btnLess.show();
                  } else {
                      btnLess.hide();
                  }
              }

            $('#confirm-patient-reshedule').on('submit',function (e) {
				e.preventDefault();
				e.stopImmediatePropagation();

				var $thisForm = $(this),
					startDateVal = $('#reschedule_start_date').val(),
					sessionWriteScript = $('.session-write-script').html(),
					endDateVal = $('#reschedule_end_date').val();
				
				if (!startDateVal || !endDateVal) {
					alert('Please Select your new booking dates.');
					return false;
				}		

				$.ajax({
					type: 'post',
					url: $thisForm.attr('action'),
					data: new FormData($thisForm[0]),
					contentType : false,
					processData : false
				}).done(function(response){
					console.log(response);
					
					var responseObj = $.parseJSON(response);
					$.ajax({
						type: 'post',
						url: sessionWriteScript,
						data: {
							sessionMessage: responseObj.sessionMessage,
					 		sessionMessageClass: responseObj.sessionMessageClass
					 	}
					}).done(function (response) {
						window.location.reload();
					});
				});
			});

    </script>
			<input type="hidden" name="nextdate" id="nextdate_<?=$i?>" value="<?=$nextdate?>">
			<input type="hidden" name="startdate" id="startdate_<?=$i?>" value="<?=$startdate?>">
			<input type="hidden" name="reschedule_start_date" value="" id="reschedule_start_date">
			<input type="hidden" name="reschedule_end_date" value="" id="reschedule_end_date">
		<table style="width:100%" id="internalActivities<?=$i?>">
		  <tr>
			<?php if (date('Y-m-d') != $days[0]) : ?>
			    <th rowspan="19">
			    	<a href="javascript:" style="color: #062e4c !important;" class="back" id="<?=$i?>">
			    	<i class="fa fa-arrow-left" aria-hidden="true"></i>
			    	</a>
			    </th>
			<?php endif;?>

			<?php foreach ($days as $day): ?>
          		<th><?=date('d M Y', strtotime($day))?></th>
          	<?php endforeach; ?>  
			<th rowspan="19"> 
				<a href="javascript:" style=" color: #062e4c !important;" class="next" id="<?=$i?>"><i class="fa fa-arrow-right" aria-hidden="true"></i>
				</a>
			</th>

		  </tr>
			<?php for ($halfhour = $starttime;$halfhour <= $endtime; $halfhour = $halfhour + 30 * 60) { ?>
		      <tr>
			      <?php foreach ($days as $day) : ?>
			      <?php
			        $start = $day." ".date('H:i:s',$halfhour);
			        $end = date("Y-m-d H:i:s", strtotime($start . "+30 minutes"));  
			      ?>
			        <?php if (!in_array($start, $bookedDates, true)) : ?>
			        <?php

			        	echo '<td><a class="reschedule-new-date" data-end-date="' . $end . '" data-start-date="' . $start . '"  href="#">'.date('H:i',strtotime($start)).'-'.date('H:i', strtotime($end)).'</a></td>';
			        ?>
			        <?php else : ?>
			          <td>
			          	<a href="javascript:">-</a>
			          </td>
			        <?php endif;?>
			      <?php endforeach;?>
		      </tr>
		    <?php } ?>

		</table>

		<!-- <div class="form-group"> -->
			<button id="show_more<?=$i?>" class="tg-btn tg-btn-lg" >Show More</button>
		<!-- </div> -->

		<!-- <div class="form-group"> -->
			<button id="show_less<?=$i?>" class="tg-btn tg-btn-lg" >Show Less</button>
		<!-- </div> -->
</div>
</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close window</button>
		<button type="submit" class="btn btn-success" id="confirm-appointment-btn" value="reshedule-appointment">Confirm reschedule</button>
	</div>
</form>