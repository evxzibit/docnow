<?php
	include_once 'custom_modules/common.php';

	$message = array(
		'sessionMessage' => 'Cannot cancel booking.',
		'sessionMessageClass' => 'alert-danger'
	);
	if (empty($_POST)) {
		echo json_encode($message);
		exit;
	}

	$data = $_POST;
	$message = rescheduleAppointment($data);
	
	echo json_encode($message);
	exit;