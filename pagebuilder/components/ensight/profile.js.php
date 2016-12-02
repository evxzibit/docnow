<?
include_once ("../../../modules/connect.php");

$Path = str_replace ("http://", "' + document.location.protocol + '//", ThisURL).ROOT_URL;
$Session_ID = $_COOKIE['Session_ID'];
?>
function __CookiesEnabled () {
//--- Detects cookie support in the browser

	if (navigator.cookieEnabled) {
		return "&cookies=1";
	} else {
		document.cookie = 'cookieCutter';
		return "&cookies=" + (document.cookie.indexOf ('cookieCutter') != -1 ? "1" : "0");
	}

}

function __SupportsJava () {
//--- Detects Java support in the browser

	var javaSupported = "0";

	if (typeof (navigator.javaEnabled ()) == "boolean") {
		javaSupported = (navigator.javaEnabled () ? "1" : "0");
	}

	return "&java=" + javaSupported;

}

function __SupportsJavaScript () {
//--- Detects Javascript support in the browser

	return "&javascript=" + "1"; // default, since this uses Javascript

}

function __SupportsFlash () {
//--- Detects Adobe Flash support in the browser

	flashSupported = "0";
	flashVersion = "0";

	if (window.ActiveXObject) {

		for (i = 10; i > 0; i--) {
			try {
				var flash = new ActiveXObject ("ShockwaveFlash.ShockwaveFlash." + i);
				flashSupported = "1";
				flashVersion = i + ".0";
				break;
			} catch (e) {
				//--- Do nothing
			}
		}

	} else
	if ((navigator.plugins) && (navigator.plugins.length)) {

		for (i = 0; i < navigator.plugins.length; i++) {
			if (navigator.plugins[i].name.indexOf ('Shockwave Flash') != -1) {
				flashSupported = "1";
				flashVersion = navigator.plugins[i].description.split(" ")[2];
				break;
			}
		}

	}

	return "&flash=" + flashSupported + "&flash_ver=" + flashVersion;

}

function __GetScreenDimensions () {
//--- Returns screen dimensions and color depth

	var screenW = "0";
	var screenH = "0";
	var screenColorDepth = "0";

	if (typeof (screen) == "object") {
		screenColorDepth = (screen.pixelDepth ? screen.pixelDepth : screen.colorDepth);
		screenW = screen.width;
		screenH = screen.height;
	}

	return "&screenW=" + screenW + "&screenH=" + screenH + "&screenColorDepth=" + screenColorDepth;

}

function __GetBrowserDimensions () {
//--- Returns browser dimensions

	var browserW = "0";
	var browserH = "0";

	if (parseInt (navigator.appVersion) > 3) {

		if ((navigator.appName == "Microsoft Internet Explorer") && (document.body)) {
			browserW = document.body.offsetWidth;
			browserH = document.body.offsetHeight;
		} else
		if (navigator.appName == "Netscape") {
			browserW = window.innerWidth;
			browserH = window.innerHeight;
		}

	}

	return "&browserW=" + browserW + "&browserH=" + browserH;

}

function __GetBrowserTimezone () {
//--- Returns the browser's timezone

	var timezone = "0";

	var NOW = new Date ();

	timezone = NOW.getTimezoneOffset () / 60 * -1;
	if (timezone == 0) {
		timezone = "0";
	}

	return "&timezone=" + timezone;

}

function __CompileTrackingImage () {
//--- Compiles an image to send information back to Ensight

	document.write ('<img src="<? echo $Path; ?>/track.php?Session_ID=<? echo $Session_ID; ?>' + __CookiesEnabled () + __SupportsJava () + __SupportsJavaScript () + __SupportsFlash () + __GetScreenDimensions () + __GetBrowserDimensions () + __GetBrowserTimezone () + '" width="1" height="1" />');

}

__CompileTrackingImage ();