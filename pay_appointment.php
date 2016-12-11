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


    $pfParamString = '';
    $pfHost = ( PAYFAST_SERVER == 'LIVE' ) ? 
     'www.payfast.co.za' : 'sandbox.payfast.co.za';
    $data = array(
        'merchant_id' => PAYFAST_MERCHANT_ID,
        'merchant_key' => PAYFAST_MERCHANT_KEY,
        'return_url' => ThisURL . ROOT_URL . '/payment_success.php?id=' . $appointmentId,
        'cancel_url' => ThisURL . ROOT_URL . '/payment_cancel.php?id=' . $appointmentId,
        'notify_url' => ThisURL,
        'name_first' => $appointmentDetails['first_name'],
        'name_last' => $appointmentDetails['last_name'],
        'email_address' => $appointmentDetails['email'],
        'm_payment_id' => $appointmentDetails['id'],
        'amount' => APPOINTMENT_FEE,
        'item_name' =>  'DocNow appointment with Dr ' . $appointmentDetails['doctorName'] . ' on ' . date('d-F-Y H:i', strtotime($appointmentDetails['start_date'])),
        'item_description' => 'Appointment date' . date('d-F-Y H:i', strtotime($appointmentDetails['start_date'])) ,
    );

    // Strip any slashes in data
    foreach( $data as $key => $val ) {
        $pfData[$key] = stripslashes( $val );
    }

    // Dump the submitted variables and calculate security signature
    foreach( $pfData as $key => $val )    {
       if( $key != 'signature') {
           $pfParamString .= $key .'='. urlencode(trim( $val )) .'&';
        }
    }

    // Remove the last '&' from the parameter string
    $pfParamString = substr( $pfParamString, 0, -1 );
    $pfTempParamString = $pfParamString;

    session_start();
	$_SESSION['purchaseRequestData'] = $data;

    // Genrate signature from post
    $signature = md5( $pfTempParamString );
    $data['signature'] = $signature;
    $payfastUrl = "https://".$pfHost."/eng/process?".$pfTempParamString."&signature=".$signature;

    // Go to payfast to process payment
    header('Location: ' . $payfastUrl); 
		exit;