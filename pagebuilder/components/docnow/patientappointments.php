<?php	

	require_once ('modules/profile.php');
	include_once 'modules/connect.php';
	include_once 'custom_modules/common.php';
	include_once 'flash_message.php';

	global $Profile_ID;
	global $Session_ID;

	$appointments = getPatientAppointments($Profile_ID, $_POST);
?>

<script type="text/javascript">
	$(document).ready(function(){
		$.getJSON("http://ip-api.com/json/<?=$_SERVER['REMOTE_ADDR']?>", function(data) {
        	$('#lat1').val(data.lat);
        	$('#lng1').val(data.lon);
        });

		$('#book_dr').click(function () {

	     	var value = $(this).attr("id");
	     	
           	$('#specialty-form').submit();
        	
    	});
	});

</script>
<span class="session-write-script hidden"><?='/live/session_write.php'?></span>
<form action="/search/&Session_ID=<?=$Session_ID?>" method="post" id="specialty-form">
	
	<input type="hidden" name="speciality" value="1" id="speciality1">
	<input type="hidden" name="lat" id="lat1" value="">
	<input type="hidden" name="lng" id="lng1" value="">
</form>

<div class="tg-description" style="padding-bottom: 10px;">
	<div class="col-md-3">
		<a href="javascript:" class="tg-btn" style="width: 100%;" id="book_dr"><span style="font-size: 14px;">Book Dr</span></a>
	</div>
	
	<div class="col-md-3">
		<a href="/patients/patient-notifications.html?Session_ID=<?=$Session_ID?>" class="tg-btn" style="width: 100%;">Notifications</a>
	</div>
	
	<div class="col-md-3">
		<a href="/patients/patient-appointments.html?Session_ID=<?=$Session_ID?>" class="tg-btn" style="width: 100%;">Appointments</a>
	</div>
	
	<div class="col-md-3">
		<a href="/patients/settings.html?Session_ID=<?=$Session_ID?>" class="tg-btn" style="width: 100%;">Settings</a>
	</div>
</div>   

<div class="tg-refinesearcharea">
	<form class="form-refinesearch tg-haslayout" method="post" id="searchform" action="<?=$_SERVER['REQUEST_URI']?>">
		<fieldset>
			<div class="row">
				<div class="col-sm-2">
					<div class="form-group">
						<div class="form-group">
							<span class="">
								<select id="status" name="status" class="">
									<?php 
										$statusOptions = array(
											'all' => 'All bookings',
											'cancel' => 'Cancelled bookings',
											'future' => 'Future bookings',
											'past' => 'Past bookings',
 										);
									?>
									<?php foreach($statusOptions as $key => $statusOption):?>
										<option value="<?=$key?>" <?=($key == $_REQUEST['status'] ? 'selected="selected"' : '')?>><?=$statusOption?></option>
									<?php endforeach;?>
								</select>
							</span>
						</div>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<button type="submit" class="tg-btn" id="search">search</button>
					</div>
				</div>
			</div>
		</fieldset>
	</form>
</div>

<span class="reschedule-modal-url hidden"><?='/live/reschedulebookingmodal.php'?></span>
<div class="">
	<?php if (!empty($appointments['appointments'])):?>
		<table class="table">
			<thead>
				<tr>
					<th>
						Doctor
					</th>
					<th>
						Date
					</th>
					<th>
						Location
					</th>
					<th>
						Contact
					</th>
					<th>
						Action
					</th>
				</tr>
			</thead>
					<tbody>
		<?php foreach($appointments['appointments'] as $appointment) :?>
		<?php 
			$doctorProfileId = $appointment['profile_id'];
			$appointmentId = $appointment['id'];
			$class = $appointment['cancel'] ? 'danger' : 'success';
			$doctorName = $appointment['doctorName'];

			if (date('d-F-Y H:i', strtotime($appointment['end_date'])) < date('d-F-Y H:i')):
				$class = 'warning';
			endif;
		?>
				
		<tr class="<?=$class?>">
			<td>
				<a href="/doctor/doctor-details.html?doctor_profile_id=<?=$doctorProfileId?>"><?=$appointment['doctorName']?></a>
			</td>
			<td>
				<?php
					$startDate = date('d-F-Y H:i', strtotime($appointment['start_date']));
					$endDate = date('d-F-Y H:i', strtotime($appointment['end_date']));
				?>
				<strong>Start:</strong> <?=$startDate?>
				<br><strong>End:</strong> <?=$endDate?>
			</td>
			<td>
				<?=$appointment['address']?>
			</td>
			<td>
				<p><i class="fa fa-phone"></i>
				<?=$appointment['email']?></p>
				<p><i class="fa fa-envelope-o"></i>
				<?=$appointment['cell_phone']?></p>
			</td>
			<td>
				<?php if (date('d-F-Y H:i', strtotime($appointment['end_date'])) >= date('d-F-Y H:i')): ?>
					<?php if ($appointment['cancel']) :?>
						<p class="text-danger">Cancelled</p>
					<?php endif; ?>
					<?php if (!$appointment['cancel']) :?>
						<a href="/live/cancelpatientbooking.php" class="btn btn-danger cancel-booking" data-booking-id="<?=$appointmentId?>">Cancel</a>

						<a href="/live/reshedulepatientbooking.php" class="btn btn-warning reschedule-booking" data-booking-id="<?=$appointmentId?>" data-doctor-name="<?=$doctorName?>" data-booking-start="<?=$startDate?>" data-booking-end="<?=$endDate?>">Reshedule</a>
					<?php endif; ?>
				<?php endif; ?>
			</td>
		</tr>
					
				
			<!-- </div> -->
		<!-- </div> -->
	<?php endforeach; ?>
		</tbody>
	</table>
	<?=$appointments['paging']?>
	<?php else:?>
		<div class="alert alert-info">No appointment found.</div>
	<?php endif;?>
</div>
<style>
	body.modal-open div.modal-backdrop { 
	    z-index: 0; 
	}
</style>
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="cancel-modal-content">
    	<div class="modal-body">
    	</div>
    	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Do  not cancel booking</button>
		<button type="button" class="btn btn-danger" id="confirm-cancellation">Confirm cancellation</button>
		</div>
    </div>
  </div>
</div>

<div class="modal fade" id="rescheduleModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="reschedule-modal-content">
<!-- <script type="text/javascript" src="/live/js/moment.min.js"></script>
<script type="text/javascript" src="/live/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="/live/css/bootstrap-datetimepicker.min.css"/> -->
    </div>
  </div>
</div>

<script type="text/javascript">
	$(function() {
		'use strict';

		var 
			cancelBtn = $('.cancel-booking'),
			rescheduleBtn = $('.reschedule-booking'),
			sessionWriteScript = $('.session-write-script').html();

		cancelBtn.on('click',function (e) {
			e.preventDefault();
			e.stopImmediatePropagation();

			var $this = $(this),
				appointmentId = $this.data('bookingId'),
				cancelUrl = $this.attr('href');

				console.log('appointmentId: ', appointmentId);
				console.log('cancelUrl: ', cancelUrl);

			$(".modal-body").html('Are you sure you want to cancel this appointment?');
			$('#cancelModal').modal('show', {backdrop: 'static', keyboard: false});
			
			$('#confirm-cancellation').on('click', function(){
				$.ajax({
					type: 'post',
					url: cancelUrl,
					data: {
						id: appointmentId
				 	}
				}).done(function (response) {
					console.log(response);
					var responseObj = $.parseJSON(response);
					$.ajax({
						type: 'post',
						url: sessionWriteScript,
						data: {
							sessionMessage: responseObj.sessionMessage,
					 		sessionMessageClass: responseObj.sessionMessageClass
					 	}
					}).done(function () {
						window.location.reload();
					});
				});
			});
			
		});

		/*$('#reschedule_start_date').datetimepicker();
		$('#reschedule_end_date').datetimepicker({
			useCurrent: false //Important! See issue #1075
		});
		$("#reschedule_start_date").on("dp.change", function (e) {
			$('#reschedule_end_date').data("DateTimePicker").minDate(e.date);
		});
		$("#reschedule_end_date").on("dp.change", function (e) {
			$('#reschedule_start_date').data("DateTimePicker").maxDate(e.date);
		});*/
		var
			reschedulebookingmodalUrl = $('.reschedule-modal-url').html();

		rescheduleBtn.on('click', function(rescheduleEvent) {
			rescheduleEvent.preventDefault();
			rescheduleEvent.stopImmediatePropagation();
			var $this = $(this);

			$("#reschedule-modal-content").load(reschedulebookingmodalUrl + '?appointmentId=' + $this.data('bookingId'), function() {});

			// var $this = $(this);
			/*$('#modal-doctor-name').html($this.data('doctorName'));
			$('#modal-booking-start').html($this.data('bookingStart'));
			$('#modal-booking-end').html($this.data('bookingEnd'));
			$('#appointmentId').val($this.data('bookingId'));*/

			$('#rescheduleModal').modal('show', {backdrop: 'static', keyboard: false});
			
			/*$('#confirm-patient-reshedule').on('submit',function (e) {
				e.preventDefault();
				e.stopImmediatePropagation();

				var $thisForm = $(this);

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
			});*/
		});
		
	});
</script>