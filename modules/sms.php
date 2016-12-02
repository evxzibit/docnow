<?
//--- SMS integration module
//--- Clickatell.com
//--- Handles messages up to 160 characters, make sure the message has line breaks
include_once (MODULE_FILES."/utils.php");

define ("SMS_API_ID", "<api_id>");
define ("SMS_USER", "<username>");
define ("SMS_PASS", "<password>");
define ("SMS_MAX_PARTS", "2");
define ("SMS_DEFAULT_PREFIX", "44");

function MySMS ($Mobile, $From, $Subject, $Body) {
//--- Sends the specified SMS

	//--- Clean up mobile format
	$Mobile = str_replace ("+", "", $Mobile);
	$Mobile = str_replace ("-", "", $Mobile);
	$Mobile = str_replace (" ", "", $Mobile);
	$Mobile = str_replace ("(", "", $Mobile);
	$Mobile = str_replace (")", "", $Mobile);

	//--- Replace leading 0 with country code
	if (substr ($Mobile, 0, 1) == "0") {
		$Mobile = SMS_DEFAULT_PREFIX.substr ($Mobile, 1);
	}

	$URL = "http://api.clickatell.com/http/sendmsg?api_id=".SMS_API_ID."&user=".SMS_USER."&password=".SMS_PASS."&to=".$Mobile."&from=".$From."&concat=".SMS_MAX_PARTS."&text=".urlencode (trim ($Body));
	$Response = PostHTTP ($URL, 80, "", None);

	if (is_array ($Response) && (substr ($Response[9], 0, 3) == "ID:")) {
		return 0; // return successful
	} else {
		return 1; // return failed
	}

}
?>