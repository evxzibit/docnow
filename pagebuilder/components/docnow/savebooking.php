<?php
	/*error_reporting(E_ALL);
	ini_set('display_errors', 1);*/
	include_once 'custom_modules/common.php';
	include_once 'modules/connect.php';
	include_once "modules/profile.php";

	global $Profile_ID;
	global $Session_ID;
	global $UserStatus;

	session_start();

	if (!isset($_SESSION['booking-data']) || empty($_SESSION)) {
		redirectToPage(ThisURL . '?Session_ID=' . $Session_ID, 'Cannot find booking data.', 'alert-danger');
	}

	$data = $_SESSION['booking-data'];
	$data['patient_profile_id'] = $data['patient_exist'] ? $data['patient_profile_id'] : '';

	$appointmentId=saveBooking($data);
	if ($appointmentId) {
		sendDoctorBookingEmail($data);
		$doctorDetails = getProflieRegDetails($data['doctor_profile_id']);
		$doctorFullName = $doctorDetails['first_name'] . ' ' . $doctorDetails['last_name'];
		$appointmentDetails = getPatientAppointmentById($appointmentId);
		sendPatienBookingConfirmation($appointmentDetails);

		$url = $data['patient_exist'] ? ThisURL . '/patients/dashboard/?Session_ID=' . $Session_ID : ThisURL . '?Session_ID=' . $Session_ID;
		unset($_SESSION['booking-data']);
		redirectToPage($url, 'Thank you for making a booking with Dr ' . $doctorFullName . ' an email has been sent to you with your booking confirmation details..', 'alert-success');
	} else {
		unset($_SESSION['booking-data']);
		redirectToPage(ThisURL . '?Session_ID=' . $Session_ID, 'Cannot save booking data.', 'alert-danger');
	}

	exit;