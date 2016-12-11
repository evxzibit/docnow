<?php
	include_once 'custom_modules/common.php';
	include_once 'modules/connect.php';
	include_once "modules/profile.php";

	global $Profile_ID;
	global $Session_ID;

	if (!isset($_POST['booking-submit']) || empty($_POST)) {
		redirectToPage(ThisURL, 'Cannot find booking', 'alert-danger');
	}	

	session_start();
	$_SESSION['booking-data'] = $_POST;

	extract($_POST);

	$paymentMethod = getPaymentMethodById($payment_method);
?>

<div class="container">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 col-xs-12">
			<div class="tg-theme-heading">
				<h2>Booking Confirmation</h2>
				<span class="tg-roundbox"></span>
				<div class="tg-description">
					<p>Please ensure that the details captured below are correct.</p>
				</div>
			</div>
		</div>
		<div class="col-md-12 col-sm-12 col-xs-12 tg-packageswidth">
			<table class="table table-striped">
				<tbody>
				  <tr>
					<td><b>Payment Method:</b></td>
					<td><?=$paymentMethod['name']?></td>
				  </tr>
				  
				  <tr>
					<td><b>Patient Name:</b></td>
					<td><?=$first_name . ' ' . $last_name?></td>
				  </tr>
				  
				  <tr>
					<td><b>Email address:</b></td>
					<td><?=$email?></td>
				  </tr>
				  
				  <tr>
					<td><b>Cellphone Number:</b></td>
					<td><?=$cell_phone?></td>
				  </tr>
				  
				  <tr>
					<td><b>Appointment Time:</b></td>
					<td><strong>Start:</strong> <?=date('d-F-Y H:i', strtotime($start_date)) ?>
						<br><strong>End:</strong> <?=date('d-F-Y H:i', strtotime($end_date))?>
					</td>
				  </tr>
				  
				  <tr>
					<td><b>Doctor:</b></td>
					<td><?=$doctor_name?></td>
				  </tr>
				  
				  <tr>
					<td><b>Specialty:</b></td>
					<td><?=$doctor_speciality?></td>
				  </tr>
				  
				  <tr>
					<td><b>Location:</b></td>
					<td><?=$doctor_address?></td>
				  </tr>
				  
				</tbody>
			</table>

			<a href="/booking/save-booking.html?Session_ID=<?=$Session_ID?>" class="btn btn-success">Next</a>
			<a href="#" class="pull-right">Get Directions</a>
		</div>
	</div>
</div>