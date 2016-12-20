<?php
	include_once 'custom_modules/common.php';

	$message = array(
		'sessionMessage' => 'Cannot cancel booking.',
		'sessionMessageClass' => 'alert-danger'
	);
	if (!isset($_POST['id']) || empty($_POST)) {
		echo json_encode($message);
		exit;
	}

	$appointmentId = $_POST['id'];

	$appointment = getPatientAppointmentById($appointmentId);

	$cancelled = cancelBooking($appointmentId);

	if ($cancelled) {
		$message = array(
			'sessionMessage' => 'Booking with Dr. ' . $appointment['doctorName'] . ' cancelled successfully.',
			'sessionMessageClass' => 'alert-success'
		);
	}
	
	echo json_encode($message);
	exit;