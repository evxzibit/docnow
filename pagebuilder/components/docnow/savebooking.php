<?php
	/*error_reporting(E_ALL);
	ini_set('display_errors', 1);*/
	include_once 'custom_modules/common.php';
	include_once 'modules/connect.php';
	include_once "modules/profile.php";

	global $Profile_ID;
	global $Session_ID;
	global $UserStatus;


	if (!isset($_POST['confirm-booking']) || empty($_POST)) {
		redirectToPage(ThisURL . '?Session_ID=' . $Session_ID, 'Cannot find booking data.', 'alert-danger');
	}	
	$appointmentId=saveBooking($_POST);
	if ($appointmentId) {		
		sendDoctorBookingEmail($_POST);
		$doctorDetails = getProflieRegDetails($_POST['doctor_profile_id']);
		$doctorFullName = $doctorDetails['first_name'] . ' ' . $doctorDetails['last_name'];
		$appointmentDetails = getPatientAppointmentById($appointmentId);
		sendPatienBookingConfirmation($appointmentDetails);
		$patientProfileDetails = getProflieRegDetails($_POST['patient_profile_id']);
		$url = !empty($patientProfileDetails) ? ThisURL . 'patients/dashboard/?Session_ID=' . $Session_ID : ThisURL . '?Session_ID=' . $Session_ID;

		redirectToPage($url, 'Thank you for making a booking with Dr ' . $doctorFullName . ' an email has been sent to you with your booking confirmation details..', 'alert-success');
	} else {
		redirectToPage(ThisURL . '?Session_ID=' . $Session_ID, 'Cannot save booking data.', 'alert-danger');
	}

	exit;