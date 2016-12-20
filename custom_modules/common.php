<?
include_once 'modules/DB.php';
include_once 'modules/connect.php';
include_once 'modules/profile.php';
include_once 'modules/MIME.php';
include_once 'modules/catalog.php';
include_once 'modules/session.php';


function getProflieRegDetails($profile_id) {
	
	$SQL = "SELECT * FROM tUsers WHERE profile_id={$profile_id}";
	$Query = QueryDB($SQL);
	$Result = ReadFromDB($Query);

	return $Result;
}

function debug($data){
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

function redirectToPage($url, $sessionMessage, $sessionMessageClass) {
	session_start();
	$_SESSION['sessionMessage'] = $sessionMessage;
	$_SESSION['sessionMessageClass'] = $sessionMessageClass;
	header('Location: ' . $url); 
		exit;
}

function getProfileEmergencyContact($Profile_ID){

	$SQL = "SELECT * FROM tUserEmergencyDetails WHERE profile_id={$Profile_ID}";
	$Query = QueryDB($SQL);
	$Result = ReadFromDB($Query);

	return $Result;
}

function getUserEmployer($Profile_ID){

	$SQL = "SELECT * FROM tUserEmployer WHERE profile_id={$Profile_ID}";
	$Query = QueryDB($SQL);
	$Result = ReadFromDB($Query);

	return $Result;
}


function getUserPreferences($Profile_ID){

	$SQL = "SELECT * FROM tUserPreferences WHERE profile_id={$Profile_ID}";
	$Query = QueryDB($SQL);
	$Result = ReadFromDB($Query);

	return $Result;
}


function getMedicalAidCards($Profile_ID){

	$SQL = "SELECT * FROM tPatientMedicalAidCards WHERE profile_id={$Profile_ID}";
	$Query = QueryDB($SQL);
	$Result = ReadFromDB($Query);

	return $Result;
}

function getSpecialities(){

	$SQL = "SELECT * FROM tSpecialty ORDER BY display_order";
	$Query = QueryDB($SQL);
	while($Result = ReadFromDB($Query)){
		$specialities [$Result['id']] = $Result['specialty_name'];

	}

	return $specialities;
}

function getLanguages(){

	$SQL = "SELECT * FROM tLanguage ORDER BY id";
	$Query = QueryDB($SQL);
	while($Result = ReadFromDB($Query)){
		$language [$Result['id']] = $Result['language'];

	}

	return $language;
}

function getSpecialityName($speciality_id){

	$SQL = "SELECT specialty_name FROM tSpecialty WHERE id={$speciality_id}";
	$Query = QueryDB($SQL);
	$Result = ReadFromDB($Query);
	return $Result['specialty_name'];
}

function activeUserProfile($profileId) {
	$sql = 'Update Profiles set Status_NUM = "-1" WHERE Profile_ID = "' . $profileId . '"';
    UpdateDB($sql);
}

function loginUser($data, $url) {
	$registrationURL = ThisURL . ROOT_URL . "/LSM.php";
    $data['APIKey'] = 'f0e8212b6bda3ced017c4659bd6ea90b';
    $data['Format'] = 'json';
    $responseJSON = httpPost($registrationURL, $data);
    $resp = json_decode($responseJSON);

    session_start();
	$_SESSION['sessionMessage'] = 'You have been logged in successfully';
	$_SESSION['sessionMessageClass'] = 'alert-success';

    if($resp->Error_NUM == '0'){
    	header('Location: ' . $url.'&Session_ID='.$resp->Session_ID); 
		exit;
    }
}

function httpPost($url, $data) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    
    $response = curl_exec($curl);
    curl_close($curl);
    if($response === false) {
	    return 'Curl error: ' . curl_error($curl);
	} 
    curl_close($curl);
    return $response;
}

function saveUserProfilePic($profileId, $directory) {
	$sql = 'Update tUsers set profilepic = "' . $directory . '" WHERE Profile_ID = "' . $profileId . '"';
    UpdateDB($sql);
}

function getUpcomingAppointments($profileId) {
	$SQL = 'SELECT tAppointments.first_name, tAppointments.last_name, tAppointments.title, tAppointments.start_date, tAppointments.end_date, tAppointments.id FROM tAppointments
		WHERE tAppointments.doctor_profile_id = "' . $profileId . '"
		AND tAppointments.cancel = 0 
		AND tAppointments.start_date > Now() 
		ORDER BY tAppointments.start_date LIMIT 4';

	$Query = QueryDB($SQL);
	$upcomentAppointments = array();
	while ($Result = ReadFromDB($Query)){
		$upcomentAppointments[] = $Result;
	}

	return $upcomentAppointments;	
}

function getDoctorAppointments($profileId) {
	$SQL = 'SELECT tUsers.first_name, tUsers.last_name, tAppointments.title, tAppointments.start_date,tAppointments.payment_made, tAppointments.confirmed,tAppointments.end_date, tAppointments.id, tAppointments.payment_amount FROM tAppointments LEFT JOIN tUsers ON tUsers.profile_id = tAppointments.patient_profile_id
		WHERE tAppointments.doctor_profile_id = "' . $profileId . '" AND tAppointments.cancel = 0';

	$Query = QueryDB($SQL);
	$appointments = array();
	while ($Result = ReadFromDB($Query)){
		$appointments[] = $Result;
	}

	return $appointments;	
}

function getPaymentMethods() {
 	$SQL = 'SELECT * FROM tPayment_Methods';
	$Query = QueryDB($SQL);
	$paymentMethods = array();
	while ($Result = ReadFromDB($Query)){
		$paymentMethods[] = $Result;
	}

	return $paymentMethods;	
}

function getPaymentMethodById($id) {
 	$SQL = 'SELECT * FROM tPayment_Methods WHERE id = "' . $id .  '"';
	$Query = QueryDB($SQL);
	$paymentMethods = array();
	return ReadFromDB($Query);
}

function saveBooking ($data) {
	$startDate = date("Y-m-d H:i:s", strtotime($data['start_date']));
	$endDate = date("Y-m-d H:i:s", strtotime($data['end_date']));
	$patientProfileId = !empty($data['patient_profile_id']) ? $data['patient_profile_id'] : 0;

	$sql = 'INSERT INTO tAppointments (title, start_date, end_date, patient_profile_id, doctor_profile_id, date_created, payment_method, first_name, last_name, email, cell_phone) VALUES ("' . addslashes($data['title']) . '", "' . $startDate . '", "' . $endDate . '", "' . addslashes($patientProfileId) . '", "' . addslashes($data['doctor_profile_id']) . '", "' . addslashes(date("Y-m-d H:i:s")) . '", "' . addslashes($data['payment_method']) . '", "' . addslashes($data['first_name']) . '", "' . addslashes($data['last_name']) . '", "' . addslashes($data['email']) . '", "' . addslashes($data['cell_phone']) . '");';
	return InsertDB($sql, 'id');
}


function sendDoctorBookingEmail($data) {
    $mailId = 3;
    $welcomeMessageDetails = RetrieveMessage($mailId);
    $nameFrom = $welcomeMessageDetails['From_STRING'];
    $emailFrom = $welcomeMessageDetails['FromEmail_STRING'];
    $ccTo = $welcomeMessageDetails['CCTo_STRING'];
    $replyTo = $welcomeMessageDetails['ReplyTo_STRING'];
    $subject = stripslashes($welcomeMessageDetails['MailSubject_STRING']);
    $textMessage = stripslashes($welcomeMessageDetails['MailText_STRING']);
    $htmlMessage = stripslashes($welcomeMessageDetails['MailHTML_STRING']);
    $priority = $welcomeMessageDetails['Priority_NUM'];
    $fullname = $data['first_name'] . ' ' . $data['last_name'];
    $patientEmail = $data['email'];
    $patientCellphone = $data['cell_phone'];
    $bookingDate = date('d-F-Y H:i', strtotime($data['start_date'])) . ' ' . date('d-F-Y H:i', strtotime($data['end_date']));

    $doctorDetails = getProflieRegDetails($data['doctor_profile_id']);
    $fullDoctorName = $doctorDetails['first_name'] . ' ' . $doctorDetails['last_name'];
    $emailTo = $doctorDetails['email'];

    $paymentMethodArray = array('cd' => 'Card Payment', 'cs' => 'Cash', 'ma' => 'Medical Aid');

    $paymentMethod = $data['payment_method'];
    $here = '<a href="'.ThisURL.'">here</a>';

    $htmlMessage = str_replace("**paymentMethod**", $paymentMethodArray[$paymentMethod], $htmlMessage);
    $htmlMessage = str_replace("**doctorName**", $fullDoctorName, $htmlMessage);
    $htmlMessage = str_replace("**patientName**", $fullname, $htmlMessage);
    $htmlMessage = str_replace("**BookingDate**", $bookingDate, $htmlMessage);
    $htmlMessage = str_replace("**patientEmail**", $patientEmail, $htmlMessage);
    $htmlMessage = str_replace("**patientCellphone**", $patientCellphone, $htmlMessage);
    $htmlMessage = str_replace("**here**", $here, $htmlMessage);

    SendMultipartMIMEMail ($emailTo, $$nameFrom.' <'.$emailFrom.'>', $ccTo, $replyTo, $subject, $textMessage, $htmlMessage, $priority);

}

function RetrieveMessage ($Mail_ID) {
    $SQL = "SELECT * FROM Mails WHERE Mail_ID = '$Mail_ID'";
    $Query = QueryDB($SQL);
    return ReadFromDB($Query);
}

function getSimilarDoctors($specialyId, $thisDoctor) {
	$SQL = "SELECT * FROM tUsers WHERE speciality_id={$specialyId} AND profile_id <> {$thisDoctor} ORDER BY RAND() limit 5";
	$Query = QueryDB($SQL);
	$similarDoctors = array();
	while ($Result = ReadFromDB($Query)){
		$similarDoctors[] = $Result;
	}

	return $similarDoctors;
}

function getPatientUpcomingAppointments($profileId) {
	$SQL = 'SELECT tUsers.profile_id, tUsers.last_name, tUsers.first_name,tUsers.profilepic, tAppointments.start_date, tAppointments.end_date, tAppointments.id FROM tAppointments
		LEFT JOIN tUsers on tUsers.profile_id = tAppointments.doctor_profile_id
		WHERE tAppointments.patient_profile_id = "' . $profileId . '"
		AND tAppointments.cancel = 0 
		AND tAppointments.start_date >= Now() 
		ORDER BY tAppointments.start_date LIMIT 4';

	$Query = QueryDB($SQL);
	$upcomentAppointments = array();
	while ($Result = ReadFromDB($Query)){
		$upcomentAppointments[] = $Result;
	}
	return $upcomentAppointments;	
}

function getFeaturedDoctors() {
	$SQL = "SELECT * FROM tUsers WHERE featured=1 AND active=1 ORDER BY RAND()";
	$Query = QueryDB($SQL);
	$featuredDoctors = array();
	while ($Result = ReadFromDB($Query)){
		$featuredDoctors[] = $Result;
	}

	return $featuredDoctors;
}

function getPatientPastAppointments($profileId) {
	$SQL = 'SELECT tUsers.profile_id, tUsers.last_name, tUsers.first_name,tUsers.profilepic, tAppointments.start_date, tAppointments.end_date, tAppointments.id FROM tAppointments
		LEFT JOIN tUsers on tUsers.profile_id = tAppointments.doctor_profile_id
		WHERE tAppointments.patient_profile_id = "' . $profileId . '"
		AND tAppointments.cancel = 0 
		AND tAppointments.start_date < Now() 
		ORDER BY tAppointments.start_date LIMIT 4';

	$Query = QueryDB($SQL);
	$pastAppointments = array();
	while ($Result = ReadFromDB($Query)){
		$pastAppointments[] = $Result;
	}
	return $pastAppointments;	
}

function getDoctorPastAppointments($profileId) {
	$SQL = 'SELECT tAppointments.review_appointment_sent, tAppointments.first_name, tAppointments.last_name, tAppointments.title, tAppointments.start_date, tAppointments.end_date, tAppointments.id AS `appointment_id`
		FROM tAppointments
		WHERE tAppointments.doctor_profile_id = "' . $profileId . '"
		AND tAppointments.cancel = 0 
		AND tAppointments.end_date < Now() 
		ORDER BY DATE(tAppointments.start_date) LIMIT 4';

	$Query = QueryDB($SQL);
	$pastAppointments = array();
	while ($Result = ReadFromDB($Query)){
		$pastAppointments[] = $Result;
	}
	return $pastAppointments;	
}

function confirmAppointment($data) {
	$confirmation = $data['confirmation'] == 'reschedule' ? false : true;
	$resheduleStartDate = $data['reschedule_start_date'];
	$resheduleSEndDate = $data['reschedule_end_date'];
	$appointmentId = $data['appointment_id'];
	$appointmentDetails = getPatientAppointmentById($appointmentId);

	if (!$confirmation){
		$sql = 'Update tAppointments set reschedule = 1, confirmed = 1, start_date = "' . $resheduleStartDate . '", end_date = "' . $resheduleSEndDate . '" WHERE id = "' . $appointmentId . '"';
		UpdateDB($sql);
		sendResheduleEmail($appointmentDetails);
	} elseif($data['confirmation'] == 'request-payment') {
		$sql = 'Update tAppointments set payment_request_sent = 1, reschedule = 0, payment_request_sent_date = "' . date('Y-m-d H:i:s') . '" WHERE id = "' . $appointmentId . '"';
		UpdateDB($sql);
		sendPatientPaymentRequest($appointmentDetails);
	}
}

function sendPatientPaymentRequest($appointmentDetails) {
	$mailId = 8;
    $welcomeMessageDetails = RetrieveMessage($mailId);
    $nameFrom = $welcomeMessageDetails['From_STRING'];
    $emailFrom = $welcomeMessageDetails['FromEmail_STRING'];
    $ccTo = $welcomeMessageDetails['CCTo_STRING'];
    $replyTo = $welcomeMessageDetails['ReplyTo_STRING'];
    $subject = stripslashes($welcomeMessageDetails['MailSubject_STRING']);
    $textMessage = stripslashes($welcomeMessageDetails['MailText_STRING']);
    $htmlMessage = stripslashes($welcomeMessageDetails['MailHTML_STRING']);
    $priority = $welcomeMessageDetails['Priority_NUM'];
    $patientName = $appointmentDetails['patientName'];
    $patientEmail = $appointmentDetails['email'];
    $patientCellphone = $appointmentDetails['cell_phone'];
    $appointmentTime = date('d-F-Y H:i', strtotime($appointmentDetails['start_date'])) . ' ' . date('d-F-Y H:i', strtotime($appointmentDetails['end_date']));

    $doctorName = $appointmentDetails['doctorName'];
    $paymentMethod = $appointmentDetails['paymentMethod'];
    $doctorSpeciality = $appointmentDetails['doctorSpeciality'];
    $doctorAddress = $appointmentDetails['doctorAddress'];
    $appointmentPaymentLink = ThisURL . ROOT_URL . '/pay_appointment.php?id=' . $appointmentDetails['id'];

    $htmlMessage = str_replace("*||paymentMethod||*", $paymentMethod, $htmlMessage);
    $htmlMessage = str_replace("*||doctorName||*", $doctorName, $htmlMessage);
    $htmlMessage = str_replace("*||patientName||*", $patientName, $htmlMessage);
    $htmlMessage = str_replace("*||appointmentTime||*", $appointmentTime, $htmlMessage);
    $htmlMessage = str_replace("*||patientEmail||*", $patientEmail, $htmlMessage);
    $htmlMessage = str_replace("*||patientCell||*", $patientCellphone, $htmlMessage);
    $htmlMessage = str_replace("*||doctorAddress||*", $doctorAddress, $htmlMessage);
    $htmlMessage = str_replace("*||doctorSpeciality||*", $doctorSpeciality, $htmlMessage);
    $htmlMessage = str_replace("*||appointmentPaymentLink||*", $appointmentPaymentLink, $htmlMessage);

    SendMultipartMIMEMail ($patientEmail, $nameFrom.' <'.$emailFrom.'>', $ccTo, $replyTo, $subject, $textMessage, $htmlMessage, $priority);
}

function getAppointmentById($appointment_id){
	$SQL = "SELECT start_date, end_date, tAppointments.id, first_name, last_name,email,payment_made, cell_phone,	tPayment_Methods.name as payment_method
			FROM tAppointments
			LEFT JOIN tPayment_Methods ON tPayment_Methods.id = tAppointments.payment_method
			 WHERE tAppointments.id={$appointment_id}";
	$Query = QueryDB($SQL);
	$Result = ReadFromDB($Query);

	return $Result;
}

function getReviews($appointment_id){

	$SQL = "SELECT * FROM tDocReview WHERE appointment_id={$appointment_id}";
	$Query = QueryDB($SQL);
	$Result = ReadFromDB($Query);

	return $Result;
}

function getReviewsStarAverage($profile_id){

  $SQL = "SELECT tDR.star FROM tDocReview tDR LEFT JOIN tAppointments tA ON tDR.appointment_id = tA.id WHERE tA.doctor_profile_id={$profile_id}";
  $Query = QueryDB($SQL);
  $RecordCount = CountRowsDB ($Query);

  while ($Result = ReadFromDB($Query)){

    $reviews += $Result['star'];
  }

  return $reviews/$RecordCount;
}

function sendPatienBookingConfirmation($data) {
    $mailId = 4;
    $welcomeMessageDetails = RetrieveMessage($mailId);
    $nameFrom = $welcomeMessageDetails['From_STRING'];
    $emailFrom = $welcomeMessageDetails['FromEmail_STRING'];
    $ccTo = $welcomeMessageDetails['CCTo_STRING'];
    $replyTo = $welcomeMessageDetails['ReplyTo_STRING'];
    $subject = stripslashes($welcomeMessageDetails['MailSubject_STRING']);
    $textMessage = stripslashes($welcomeMessageDetails['MailText_STRING']);
    $htmlMessage = stripslashes($welcomeMessageDetails['MailHTML_STRING']);
    $priority = $welcomeMessageDetails['Priority_NUM'];
    $patientName = $data['patientName'];
    $patientEmail = $data['email'];
    $patientCellphone = $data['cell_phone'];
    $appointmentTime = date('d-F-Y H:i', strtotime($data['start_date'])) . ' ' . date('d-F-Y H:i', strtotime($data['end_date']));

    $doctorName = $data['doctorName'];
    $paymentMethod = $data['paymentMethod'];
    $doctorSpeciality = $data['doctorSpeciality'];
    $doctorAddress = $data['doctorAddress'];

    $htmlMessage = str_replace("*||paymentMethod||*", $paymentMethod, $htmlMessage);
    $htmlMessage = str_replace("*||doctorName||*", $doctorName, $htmlMessage);
    $htmlMessage = str_replace("*||patientName||*", $patientName, $htmlMessage);
    $htmlMessage = str_replace("*||appointmentTime||*", $appointmentTime, $htmlMessage);
    $htmlMessage = str_replace("*||patientEmail||*", $patientEmail, $htmlMessage);
    $htmlMessage = str_replace("*||patientCell||*", $patientCellphone, $htmlMessage);
    $htmlMessage = str_replace("*||doctorAddress||*", $doctorAddress, $htmlMessage);
    $htmlMessage = str_replace("*||doctorSpeciality||*", $doctorSpeciality, $htmlMessage);
    $htmlMessage = str_replace("*||url||*", ThisURL, $htmlMessage);

    SendMultipartMIMEMail ($patientEmail, $nameFrom.' <'.$emailFrom.'>', $ccTo, $replyTo, $subject, $textMessage, $htmlMessage, $priority);

}

function getPatientAppointmentById($appointmentId) {

	$SQL = "SELECT tUsers.address AS doctorAddress, 
		CONCAT(tUsers.first_name, ' ', tUsers.last_name) as doctorName, 
		CONCAT(tAppointments.first_name, ' ', tAppointments.last_name) as patientName,
		tUsers.email as doctorEmail,
		tAppointments.payment_amount,
		tAppointments.payment_date,
		tAppointments.first_name,
		tAppointments.last_name,
		tAppointments.start_date, tAppointments.end_date, 
		tPayment_Methods.name as paymentMethod, 
		tAppointments.cell_phone,
		tAppointments.doctor_profile_id,
		tAppointments.email,
		tAppointments.id, 
		tSpecialty.specialty_name AS `doctorSpeciality` 
		FROM tAppointments 
		LEFT JOIN tUsers on tUsers.profile_id = tAppointments.doctor_profile_id 
		LEFT JOIN tPayment_Methods ON tPayment_Methods.id = tAppointments.payment_method 
		LEFT JOIN tSpecialty ON tSpecialty.id = tUsers.speciality_id 
	WHERE tAppointments.id = {$appointmentId}";
			
	$Query = QueryDB($SQL);
	return ReadFromDB($Query);	
}

/*function httpPost($url, $data) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    if($response === false) {
	    return 'Curl error: ' . curl_error($curl);
	} 
    curl_close($curl);
    return $response;
}*/

function getDoctorAppointmentsdates($profileId, $start_date, $end_date) {
    $SQL = "SELECT tAppointments.start_date FROM tAppointments 
        LEFT JOIN tUsers ON tUsers.profile_id = tAppointments.patient_profile_id
        WHERE tAppointments.doctor_profile_id = '" . $profileId . "'
        AND tAppointments.start_date BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59'";

    $Query = QueryDB($SQL);
    $appointments = array();
    while ($Result = ReadFromDB($Query)){
        $appointments[] = $Result['start_date'];
    }

    return $appointments;   
}

function daysBetween($start, $end){
   $dates = array();
   while($start <= $end){
       array_push(
           $dates,
           date(
            'Y-m-d',
            $start
           )
       );
       $start += 86400;
   }
   return $dates;
}

function createGoogleProfile($data) {
	$sqlProfile = 'SELECT * FROM Profiles WHERE Login_STRING = "' . $data['email'] . '"';

	$QueryProfile = QueryDB($sqlProfile);
	$readProfile = ReadFromDB($QueryProfile);

	if (!empty($readProfile)) {
		$profileDetails = getProflieRegDetails($readProfile['Profile_ID']);
		$isDoctor = false;
		if ($profileDetails['doctor']) {
			$isDoctor = true;
		}
		$url = $isDoctor ? '/doctors/settings.html': '/patients/settings.html';
		$loggedIn = LoginProfile($readProfile['Profile_ID']);
		$sessionId = CreateUniqueSession($readProfile['Profile_ID']);
		return array('Error_NUM' => 0, 'Error_Msg' => 'Loggin successfully.', 'session_id' => $sessionId, 'url' => $url);
	}

	if (empty($readProfile)) {
		$sqlProfile = 'INSERT INTO Profiles (Login_STRING, KeepAlive_NUM, FirstVisit_DATE, Status_NUM) VALUES ("' . $data['email']. '", "1", "' . date('Y-m-d H:i:s') . '", "-1");';
		$queryProfileId = InsertDB($sqlProfile, 'Profile_ID');
		if (!empty($queryProfileId)) {
		 	$sqlUsers = 'INSERT INTO tUsers (profile_id, first_name, last_name, email) VALUES ("' . $queryProfileId. '", "' . $data['first_name'] . '", "' . $data['last_name'] . '", "' . $data['email'] . '");';
		 	$queryUsersId = InsertDB($sqlUsers, 'id');
		 	if (empty($queryUsersId)) {
		 		return array('Error_NUM' => 1, 'Error_Msg' => 'Unable to create user at this time. Please try again');
		 	} else {
				$loggedIn = LoginProfile($queryProfileId);
				$sessionId = CreateUniqueSession($queryProfileId);
				return array('Error_NUM' => 0, 'Error_Msg' => 'User logged in successfully', 'session_id' => $sessionId, 'url' => '/patients/settings.html');
			}

		 } {
		 	return array('Error_NUM' => 1, 'Error_Msg' => 'Cannot sign you in at this time. Please try again later.');
		 }
	}
}

function checkActiveAppointment($doctorProfileId, $patientProfileId) {
	$SQL = 'SELECT * FROM tAppointments
		WHERE tAppointments.doctor_profile_id = "' . $doctorProfileId . '"
		AND tAppointments.patient_profile_id = "' . $patientProfileId . '"
		AND tAppointments.start_date <= Now()
		AND tAppointments.cancel = 0 
		AND tAppointments.end_date >= Now() LIMIT 1';

	$Query = QueryDB($SQL);
	$Result = ReadFromDB($Query);

	return !empty($Result);
}

function saveNotification($data) {
	$doctorProfileId = $data['doctor_profile_id'];
	$patientProfileId = $data['patient_profile_id'];
	$message = addslashes($data['message']);
	$created = date('Y-m-d H:i:s');
	$sender = $data['sender'];

	$sql = 'INSERT INTO tNotifications (doctor_profile_id, patient_profile_id, message, sender, created) VALUES ("' . $doctorProfileId . '", "' . $patientProfileId . '", "' . $message . '", "' . $sender . '", "' . $created . '");';
	$notificationId = InsertDB($sql, 'id');

	if (!empty($notificationId)) {
		sendEmailNotification($data);
		return true;
	} else {
		return false;
	}

}

function sendEmailNotification($data) {
    $mailId = 7;
    $welcomeMessageDetails = RetrieveMessage($mailId);
    $nameFrom = $welcomeMessageDetails['From_STRING'];
    $emailFrom = $welcomeMessageDetails['FromEmail_STRING'];
    $ccTo = $welcomeMessageDetails['CCTo_STRING'];
    $replyTo = $welcomeMessageDetails['ReplyTo_STRING'];
    $subject = stripslashes($welcomeMessageDetails['MailSubject_STRING']);
    $textMessage = stripslashes($welcomeMessageDetails['MailText_STRING']);
    $htmlMessage = stripslashes($welcomeMessageDetails['MailHTML_STRING']);
    $priority = $welcomeMessageDetails['Priority_NUM'];

    $patientDetails = getProflieRegDetails($data['patient_profile_id']);
    $fullPatientName = $patientDetails['first_name'] . ' ' . $patientDetails['last_name'];
    $patientEmail = $patientDetails['email'];
    $patientCellphone = $patientDetails['cell_phone'];

    $doctorDetails = getProflieRegDetails($data['doctor_profile_id']);
    $fullDoctorName = 'DR. ' . $doctorDetails['first_name'] . ' ' . $doctorDetails['last_name'];

    $emailTo = ($data['sender'] == 'patient') ? $doctorDetails['email'] : $patientEmail;
    $userName = ($data['sender'] == 'patient') ? $fullDoctorName : $fullPatientName;
    $sender = ($data['sender'] == 'patient') ? $fullPatientName : $fullDoctorName ;

    $htmlMessage = str_replace("*|username|*", $userName, $htmlMessage);
    $htmlMessage = str_replace("*|message|*", $data['message'], $htmlMessage);
    $htmlMessage = str_replace("*|created|*", $data['created'], $htmlMessage);
    $htmlMessage = str_replace("*|sender|*", $sender, $htmlMessage);


    SendMultipartMIMEMail ($emailTo, $nameFrom.' <'.$emailFrom.'>', $ccTo, $replyTo, $subject, $textMessage, $htmlMessage, $priority);

}

function loadDoctorNotifications($doctorProfileId) {
	$SQL = "SELECT 
			CONCAT(tUsers.first_name, ' ', tUsers.last_name) as patientName,
			tNotifications.created,
			tNotifications.message
			FROM tNotifications
			LEFT JOIN tUsers ON tUsers.profile_id = tNotifications.doctor_profile_id
			WHERE tNotifications.doctor_profile_id = {$doctorProfileId}
			AND sender = 'patient'
			ORDER BY tNotifications.created DESC";

	$Query = QueryDB($SQL); 
	$notifications = array();
    while ($Result = ReadFromDB($Query)){
        $notifications[] = $Result;
    }

    return $notifications;
}

function loadPatientNotifications($patientProfileId) {
	$SQL = "SELECT 
			CONCAT(tUsers.first_name, ' ', tUsers.last_name) as doctorName,
			tNotifications.created,
			tNotifications.message
			FROM tNotifications
			LEFT JOIN tUsers ON tUsers.profile_id = tNotifications.patient_profile_id
			WHERE tNotifications.patient_profile_id = {$patientProfileId}
			AND sender = 'doctor'
			ORDER BY tNotifications.created DESC";

	$Query = QueryDB($SQL); 
	$notifications = array();
    while ($Result = ReadFromDB($Query)){
        $notifications[] = $Result;
    }

    return $notifications;
}

function savePatientPayment($appointmentId, $paidAmount) {
	$sql = 'Update tAppointments set payment_made = 1,  payment_amount = "' . $paidAmount . '", payment_date = "' . date('Y-m-d H:i:s') . '" WHERE id = "' . $appointmentId . '"';
	UpdateDB($sql);
	sendDocotorPaymentNotification($appointmentId);	
}

function sendDocotorPaymentNotification($appointmentId) {
	$data = getPatientAppointmentById($appointmentId);

    $mailId = 9;
    $welcomeMessageDetails = RetrieveMessage($mailId);
    $nameFrom = $welcomeMessageDetails['From_STRING'];
    $emailFrom = $welcomeMessageDetails['FromEmail_STRING'];
    $ccTo = $welcomeMessageDetails['CCTo_STRING'];
    $replyTo = $welcomeMessageDetails['ReplyTo_STRING'];
    $subject = stripslashes($welcomeMessageDetails['MailSubject_STRING']);
    $textMessage = stripslashes($welcomeMessageDetails['MailText_STRING']);
    $htmlMessage = stripslashes($welcomeMessageDetails['MailHTML_STRING']);
    $priority = $welcomeMessageDetails['Priority_NUM'];
    $patientName = $data['patientName'];
    $patientEmail = $data['email'];
    $patientCellphone = $data['cell_phone'];
    $appointmentTime = date('d-F-Y H:i', strtotime($data['start_date'])) . ' ' . date('d-F-Y H:i', strtotime($data['end_date']));

    $doctorName = $data['doctorName'];
    $paymentMethod = $data['paymentMethod'];
    $paymentAmount = $data['payment_amount'];
    $paymentDate = $data['payment_date'];
    $doctorEmail = $data['doctorEmail'];

    $htmlMessage = str_replace("*||paymentMethod||*", $paymentMethod, $htmlMessage);
    $htmlMessage = str_replace("*||paymentAmount||*", $paymentAmount, $htmlMessage);
    $htmlMessage = str_replace("*||paymentDate||*", $paymentDate, $htmlMessage);
    $htmlMessage = str_replace("*||doctorName||*", $doctorName, $htmlMessage);
    $htmlMessage = str_replace("*||patientName||*", $patientName, $htmlMessage);
    $htmlMessage = str_replace("*||appointmentTime||*", $appointmentTime, $htmlMessage);
    $htmlMessage = str_replace("*||patientEmail||*", $patientEmail, $htmlMessage);
    $htmlMessage = str_replace("*||patientCell||*", $patientCellphone, $htmlMessage);


    SendMultipartMIMEMail ($doctorEmail, $nameFrom.' <'.$emailFrom.'>', $ccTo, $replyTo, $subject, $textMessage, $htmlMessage, $priority);	
}


function getPatientFavourites($patient_profile_id) {
	
    $SQL = "SELECT * FROM tPatientFavourites WHERE patient_profile_id={$patient_profile_id}";
    $Query = QueryDB($SQL);
    $patientFavourites = array();
    while ($Result = ReadFromDB($Query)){
        $patientFavourites[] = $Result;
    }

    return $patientFavourites;   
}

function getPatientAppointments($patientProfileId, $search = array()) {
	$filter = '';
	if (!empty($search)) {
		if (isset($search['status'])) :
			switch ($search['status']) {
				case 'cancel':
					$filter .= ' AND tAppointments.cancel = 1';
					break;
				case 'future':
					$filter .= ' AND tAppointments.end_date >= Now() AND tAppointments.cancel = 0';
					break;
				case 'past':
					$filter .= ' AND tAppointments.end_date < Now()';
					break;
				default:
					$filter = '';
					break;
			}
		endif;
	}

	// How many items to list per page
    $limit = 20;

	$SQL = "SELECT 
			tUsers.profile_id, 
			tUsers.address,
			tUsers.email,
			tUsers.cell_phone,
			CONCAT(tUsers.first_name, ' ', tUsers.last_name) as doctorName,
			tUsers.profilepic, 
			tAppointments.start_date, 
			tAppointments.end_date, 
			tAppointments.id,
			tAppointments.cancel
			FROM tAppointments
			LEFT JOIN tUsers on tUsers.profile_id = tAppointments.doctor_profile_id
			WHERE tAppointments.patient_profile_id = {$patientProfileId}
			$filter
			ORDER BY tAppointments.start_date DESC
			LIMIT $limit";



	$Query = QueryDB($SQL);
	$total = CountRowsDB ($Query);

	// How many ;pages will there be
    $pages = ceil($total / $limit);

    // What page are we currently on?
    $page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
        'options' => array(
            'default'   => 1,
            'min_range' => 1,
        ),
    )));

	$appointments = array();
	while ($Result = ReadFromDB($Query)) {
		$appointments['appointments'][] = $Result;
	}

	// Calculate the offset for the query
    $offset = ($page - 1)  * $limit;

    // Some information to display to the user
    $start = $offset + 1;
    $end = min(($offset + $limit), $total);

	// The "back" link
    $prevlink = ($page > 1) ? '<a href="?page=1" title="First page">&laquo;</a> <a href="?page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';

    // The "forward" link
    $nextlink = ($page < $pages) ? '<a href="?page=' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a href="?page=' . $pages . '" title="Last page">&raquo;</a>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';

    // Display the paging information
    $appointments['paging'] = '<div id="paging"><p>' . $prevlink . ' Page ' . $page . ' of ' . $pages . ' pages, displaying ' . $start . '-' . $end . ' of ' . $total . ' results ' . $nextlink . ' </p></div>';

	return $appointments;
}

function cancelBooking($appointmentId) {
	$sql = 'Update tAppointments set cancel = 1 WHERE id = "' . $appointmentId . '"';
	sendCancellationEmail($appointmentId);
    return UpdateDB($sql);
}

function rescheduleAppointment($data) {
	$resheduleStartDate = $data['reschedule_start_date'];
	$resheduleSEndDate = $data['reschedule_end_date'];
	$appointmentId = $data['appointment_id'];
	$appointmentDetails = getPatientAppointmentById($appointmentId);

	$message = array(
		'sessionMessage' => 'Cannot reschedule booking please try again.',
		'sessionMessageClass' => 'alert-danger'
	);

	$sql = 'Update tAppointments set reschedule = 1, start_date = "' . $resheduleStartDate . '", end_date = "' . $resheduleSEndDate . '" WHERE id = "' . $appointmentId . '"';
	$updated = UpdateDB($sql);

	if ($updated) {
		$message = array(
			'sessionMessage' => 'Booking with Dr. ' . $appointmentDetails['doctorName'] . ' rescheduled successfully.',
			'sessionMessageClass' => 'alert-success'
		);
		sendResheduleEmail($appointmentDetails);
		return $message;
	}
	
	return $message;
}

function sendResheduleEmail($data) {
    $mailId = 10;
    $welcomeMessageDetails = RetrieveMessage($mailId);
    $nameFrom = $welcomeMessageDetails['From_STRING'];
    $emailFrom = $welcomeMessageDetails['FromEmail_STRING'];
    $ccTo = $welcomeMessageDetails['CCTo_STRING'];
    $replyTo = $welcomeMessageDetails['ReplyTo_STRING'];
    $subject = stripslashes($welcomeMessageDetails['MailSubject_STRING']);
    $PtextMessage = stripslashes($welcomeMessageDetails['MailText_STRING']);
    $DtextMessage = stripslashes($welcomeMessageDetails['MailText_STRING']);
    $patienthtmlMessage = stripslashes($welcomeMessageDetails['MailHTML_STRING']);
    $doctorhtmlMessage = stripslashes($welcomeMessageDetails['MailHTML_STRING']);
    $priority = $welcomeMessageDetails['Priority_NUM'];
    $doctorName = 'Dr. ' . $data['doctorName'];
    $patientName = $data['patientName'];

    $startDate = date('d-F-Y H:i', strtotime($data['start_date']));
    $endDate = date('d-F-Y H:i', strtotime($data['end_date']));

    $patientEmail = $data['email'];
    
    $patientDetails = "Patient details:" . "\n";
    $patientDetails .= "CellPhone: " . $data['cell_phone'] . "\n";
    $patientDetails .= "Email: " . $data['email'] . "\n";

    $doctorDetails = "Doctor details:" . "\n";
    $doctorDetails .= "CellPhone: " . $data['doctorCellPhone'] . "\n";
    $doctorDetails .= "Email: " . $data['doctorEmail'] . "\n";
    $doctorDetails .= "Address: " . $data['doctorAddress'] . "\n";

    $patienthtmlMessage = str_replace("*|username|*", $patientName, $patienthtmlMessage);
    $patienthtmlMessage = str_replace("*|appointmentuser|*", $doctorName, $patienthtmlMessage);
    $patienthtmlMessage = str_replace("*|startdate|*", $startDate, $patienthtmlMessage);
    $patienthtmlMessage = str_replace("*|enddate|*", $endDate, $patienthtmlMessage);
    $patienthtmlMessage = str_replace("*|otherDetails|", $doctorDetails, $patienthtmlMessage);

    $doctorhtmlMessage = str_replace("*|username|*", $doctorName, $doctorhtmlMessage);
    $doctorhtmlMessage = str_replace("*|appointmentuser|*", $patientName, $doctorhtmlMessage);
    $doctorhtmlMessage = str_replace("*|startdate|*", $startDate, $doctorhtmlMessage);
    $doctorhtmlMessage = str_replace("*|enddate|*", $endDate, $doctorhtmlMessage);
    $doctorhtmlMessage = str_replace("*|otherDetails|", $patientDetails, $doctorhtmlMessage);    

    SendMultipartMIMEMail ($data['doctorEmail'], $nameFrom.' <'.$emailFrom.'>', $ccTo, $replyTo, $subject, $DtextMessage, $patienthtmlMessage, $priority);
    SendMultipartMIMEMail ($data['email'], $nameFrom.' <'.$emailFrom.'>', $ccTo, $replyTo, $subject, $PtextMessage, $doctorhtmlMessage, $priority);
}

function sendCancellationEmail($appointmentId) {
	$data = $appointmentDetails = getPatientAppointmentById($appointmentId);
    $mailId = 11;
    $messageDetails = RetrieveMessage($mailId);
    $nameFrom = $messageDetails['From_STRING'];
    $emailFrom = $messageDetails['FromEmail_STRING'];
    $ccTo = $messageDetails['CCTo_STRING'];
    $replyTo = $messageDetails['ReplyTo_STRING'];
    $subject = stripslashes($messageDetails['MailSubject_STRING']);
    $textMessage = stripslashes($messageDetails['MailText_STRING']);
    $htmlMessage = stripslashes($messageDetails['MailHTML_STRING']);
    $priority = $messageDetails['Priority_NUM'];
    $doctorName = 'Dr. ' . $data['doctorName'];
    $patientName = $data['patientName'];

    $startDate = date('d-F-Y H:i', strtotime($data['start_date']));
    $endDate = date('d-F-Y H:i', strtotime($data['end_date']));

    $patientEmail = $data['email'];
    $cellPhone = $data['cell_phone'];

    $htmlMessage = str_replace("*|patientName|*", $patientName, $htmlMessage);
    $htmlMessage = str_replace("*|DoctorName|*", $doctorName, $htmlMessage);
    $htmlMessage = str_replace("*|startDate|*", $startDate, $htmlMessage);
    $htmlMessage = str_replace("*|endDate|*", $endDate, $htmlMessage);
    $htmlMessage = str_replace("*|patientEmail|*", $patientEmail, $htmlMessage);
    $htmlMessage = str_replace("*|patientCellphone|*", $cellPhone, $htmlMessage);  

    SendMultipartMIMEMail ($data['doctorEmail'], $nameFrom.' <'.$emailFrom.'>', $ccTo, $replyTo, $subject, $DtextMessage, $htmlMessage, $priority);
}

?>