<?
//--- Global settings and function definitions
//--- Included within connect.php

//----------------------------------------------------------------
//--- Call-back functions
//----------------------------------------------------------------

function MyErrors ($Prefix, $Statement, $SQL, $Message) {
//--- Custom DB error handler

	return false; // default to display errors

}

function MyEvents ($Caller, $Event, $Parameters) {
//--- Define your custom event handler here

	return;

}

function MyFusion ($Tag) {
//--- Define your custom fusion processor here

	switch ($Tag['name']) {
		case 'A':			return "<a".($Tag['attributes']['href'] ? " href=\"".$Tag['attributes']['href']."\"" : "").($Tag['attributes']['name'] ? " name=\"".$Tag['attributes']['name']."\"" : "").($Tag['attributes']['target'] ? " target=\"".$Tag['attributes']['target']."\"" : "").($Tag['attributes']['class'] ? " class=\"".$Tag['attributes']['class']."\"" : "").($Tag['attributes']['style'] ? " style=\"".$Tag['attributes']['style']."\"" : "").">".$Tag['content']."</a>";
		case 'DISPLAY':		return $Tag['content'];
		case 'FORM':		if (is_array ($Tag['content'])) {
								$Tag['content'] = $Tag['content'][0];
							}
							return $Tag['content'];
		case 'TITLE':		return "<h1>".$Tag['content']."</h1>";
		default:			return $Tag['content'];
	}

}

function FusionText ($Tag) {
//--- Redefine the fusion processor for text

	switch ($Tag['name']) {
		case 'A':			return $Tag['content']." <".$Tag['attributes']['href'].">";
		case 'DISPLAY':		return $Tag['content'];
		case 'FORM':		if (is_array ($Tag['content'])) {
								$Tag['content'] = $Tag['content'][0];
							}
							return $Tag['content'];
		case 'TITLE':		return '++++ '.$Tag['content'];
		default:			return $Tag['content'];
	}

}

/*
//--- Custom admin permission levels (values between 20 and 99)
define ("ALLOW_CUSTOM_1", 20);
define ("ALLOW_CUSTOM_2", 21);
$CustomPermissionLevels = array (
	array ("name" => "Custom 1", "desc" => "", "value" => ALLOW_CUSTOM_1),
	array ("name" => "Custom 2", "desc" => "", "value" => ALLOW_CUSTOM_2)
);

//--- Custom user access levels (values between 2 and 98)
define ("PROFILE_CUSTOM_1", 2);
define ("PROFILE_CUSTOM_2", 3);
$CustomAccessLevels = array (
	array ("name" => "Custom 1", "value" => PROFILE_CUSTOM_1),
	array ("name" => "Custom 2", "value" => PROFILE_CUSTOM_2)
);

//--- Custom reference status levels (values between 20 and 99)
define ("REFERENCE_CUSTOM_1", 20);
define ("REFERENCE_CUSTOM_2", 21);
$CustomStatusLevels = array (
	array ("name" => "Custom 1", "value" => REFERENCE_CUSTOM_1),
	array ("name" => "Custom 2", "value" => REFERENCE_CUSTOM_2)
);

//--- Custom media item targets (for campaign manager)
//--- Deprecated
define ("TARGET_CUSTOM_1", 1);
define ("TARGET_CUSTOM_2", 2);
$CustomMediaTargets = array (
	array ("name" => "Custom 1", "value" => TARGET_CUSTOM_1),
	array ("name" => "Custom 2", "value" => TARGET_CUSTOM_2)
);

//--- Category manager custom links
//--- Deprecated
$CustomCategoryLinks = array (
	array ("text" => "Sample Link", "url" => "sample-link.php?", "width" => 0, "height" => 0, "permission" => PROFILE_GUEST)
);

//--- Content manager custom links
//--- These links appear inside content items under the "Item Tasks" section
$CustomContentLinks = array (
	array ("text" => "Sample Link", "url" => "sample-link.php?", "width" => 0, "height" => 0, "permission" => PROFILE_GUEST)
);

//--- Template manager custom links
//--- These links appear inside template items under the "Item Tasks" section
$CustomTemplateLinks = array (
	array ("text" => "Sample Link", "url" => "sample-link.php?", "width" => 0, "height" => 0, "permission" => PROFILE_GUEST)
);

//--- Custom profile links
//--- These appear inside a profile under the "Profile Tasks" section
$CustomProfileLinks = array (
	array ("text" => "Sample Link", "url" => "sample-link.php?", "width" => 0, "height" => 0, "permission" => PROFILE_GUEST)
);

//--- Form editor custom links
//--- Deprecated
$CustomFormLinks = array (
	array ("text" => "Sample Link", "url" => "sample-link.php?", "width" => 0, "height" => 0, "permission" => PROFILE_GUEST)
);

//--- MetaData editor custom links
//--- Deprecated
$CustomMetaDataLinks = array (
	array ("text" => "Sample Link", "url" => "sample-link.php?", "width" => 0, "height" => 0, "permission" => PROFILE_GUEST)
);

//--- Custom home links
//--- These links appear within the Home section
$CustomHomeLinks = array (
	array ("text" => "Sample Link", "url" => "sample-link.php?", "width" => 0, "height" => 0, "permission" => PROFILE_GUEST)
);

//--- Custom segment options
//--- A user-defined function with name as defined by "function" must exist and must return a valid SQL statement in the format "SELECT Profile_ID FROM ..."
$CustomSegments = array (
	array ("text" => "Sample Segment", "function" => "SampleSegment")
);

//--- ====== DO NOT TOUCH! ======
define ("ORDER_BY_DATE", 1);
define ("ORDER_BY_PROFILE", 2);
define ("ORDER_BY_ITEM", 3);
define ("ORDER_BY_TYPE", 4);
define ("ORDER_BY_STATUS", 5);
define ("ORDER_BY_CODE", 6);
define ("ORDER_BY_VIEWED", 7);
define ("ORDER_BY_PARENT", 8);
define ("ORDER_BY_NAME", 9);
define ("ORDER_BY_PREDEF", 10);
define ("ORDER_BY_VISITS", 11);
define ("ORDER_BY_SCORE", 12);
define ("ORDER_BY_GROUP", 13);
define ("ORDER_BY_LANGUAGE", 14);
define ("ORDER_BY_CATEGORY", 15);
define ("ORDER_BY_SENT", 20);
define ("ORDER_BY_READ", 21);
define ("ORDER_BY_TO", 22);
define ("ORDER_BY_FROM", 23);
define ("ORDER_BY_SUBJECT", 24);
define ("ORDER_BY_PAGE", 25);
define ("ORDER_BY_VERSION", 26);
define ("ORDER_BY_COUNT", 27);
define ("ORDER_BY_EMAIL", 28);
define ("ORDER_BY_PASSWORD", 29);
define ("ORDER_BY_ACTION", 30);
define ("ORDER_BY_HISTORY", 31);
define ("ORDER_BY_MOBILE", 32);
define ("ORDER_BY_SUBJECT", 33);
define ("ORDER_BY_REVISION", 34);
define ("ORDER_BY_CAMPAIGN", 35);
define ("ORDER_BY_PUB_DATE", 36);
define ("ORDER_BY_TICKET", 37);
define ("ORDER_BY_OWNER", 38);
define ("ORDER_BY_ROLE", 39);
define ("ORDER_BY_ADDRESS", 40);
define ("ORDER_BY_DISPLAY", 41);
//--- ====== DO NOT TOUCH! ======
*/
?>