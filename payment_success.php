<?php

	include_once 'custom_modules/common.php';
	include_once 'modules/connect.php';

	$data = $_POST;
	if (!isset($_GET['id']) || empty($_GET['id'])) {
		redirectToPage(ThisURL, 'Cannot find appointment.', 'alert-danger');
	}

	$appointmentId = $_GET['id'];
	$appointmentDetails = getPatientAppointmentById($appointmentId);

	if (empty($appointmentDetails)) {
		redirectToPage(ThisURL, 'Cannot find appointment.', 'alert-danger');
	}

	$paidAmount = APPOINTMENT_FEE;

	savePatientPayment($appointmentId, $paidAmount);

	redirectToPage(ThisURL, 'Payment successful. Dr ' . $appointmentDetails['doctorName'] . ' has been notified of your payment.', 'alert-success');