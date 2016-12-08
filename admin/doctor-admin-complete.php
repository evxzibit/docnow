<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

//--- ENVENT Source Code (ESC)
//--- First Created on Wednesday, May 14 2003 by John Ginsberg
//--- For ENSIGHT

//--- Module Name: custom-Stores-complete.php
//--- Contains: None

//--- Modified by:
//--- Modified by:

//--- Description:
//--- Saves cache item details

//--- Permissions:
//--- ALLOW_ALL

//----------------------------------------------------------------

include_once ("../modules/DB.php"); 
include_once ("../modules/connect.php"); 
include_once ("../modules/profile.php"); 

extract($_POST);

/*echo $specialty;die;*/

switch ($Action) {
	case 'A':	
				
				break;
	case 'E':	
				UpdateProfile ($Profile_ID, $email, None);

				$SQL1 = "UPDATE tUsers SET first_name='".mysql_real_escape_string($first_name)."',last_name='".mysql_real_escape_string($last_name)."',cell_phone='".mysql_real_escape_string($cell_phone)."',address_1='".mysql_real_escape_string($address_1)."',',city='".mysql_real_escape_string($city)."',province='".mysql_real_escape_string($province)."',birth_date='".mysql_real_escape_string($birth_date)."', ',country_id='".mysql_real_escape_string($country_id)."',work_number='".mysql_real_escape_string($work_number)."',home_number='".mysql_real_escape_string($home_number)."',prefered_number='".mysql_real_escape_string($prefered_number)."',gender='".mysql_real_escape_string($gender)."', address='".mysql_real_escape_string($address)."', lat='{$lat}', lng='{$lng}', speciality_id = '{$specialty}', language ='".join(',', $language)."', education='".mysql_real_escape_string($education)."' WHERE profile_id=".$Profile_ID;

				$Result = UpdateDB ($SQL1,None);
				break;

	case 'D':	
				DeleteStore ($StoreID, $GooglePlacesID);
				break;
}

Redirect ("doctor-admin.php?Start=$Start&SortBy=$SortBy&srchf=$srchf&OrderBy=$OrderBy&Session_ID=$Session_ID");

?>