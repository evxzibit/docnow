<?php
	session_start();

	$_SESSION['sessionMessage'] = isset($_POST['sessionMessage']) ? $_POST['sessionMessage'] : 'Registration successful. An email was sent to your email address with further instructions.';
	$_SESSION['sessionMessageClass'] = isset($_POST['sessionMessageClass']) ? $_POST['sessionMessageClass'] : 'alert-success';
