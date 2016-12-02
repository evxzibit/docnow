<?
//---------------------------------------------------------------------
//--- 301s
//---------------------------------------------------------------------

//--- Enter any permanent redirects in here
$_Redirect_URLs = array (
);

//---------------------------------------------------------------------
//--- Module starts
//---------------------------------------------------------------------

if ($_SERVER['QUERY_STRING']) {
	$_SERVER['QUERY_STRING'] = preg_replace (':_origURL='.$_GET['_origURL'].'&?:', '', $_SERVER['QUERY_STRING']);
	$_ThisURL = $_SERVER['PHP_SELF'].($_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : '');
} else {
	$_ThisURL = $_SERVER['PHP_SELF'];
}

if (in_array ($_ThisURL, array_keys ($_Redirect_URLs))) {
	header ("HTTP/1.1 301 Moved Permanently");
	header ("Location: ".$_Redirect_URLs[$_ThisURL]); header ("Connection: close"); die ();
} else {
	header ($_SERVER['SERVER_PROTOCOL']." 404 Not Found", true, 404);
	header ("Status: 404 Not Found");
}
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN"> 
<html><head> 
<title>404 Not Found</title> 
</head><body> 
<h1>Not Found</h1> 
<p>The requested URL <? echo $_SERVER['PHP_SELF']; ?> was not found on this server.</p> 
</body></html>