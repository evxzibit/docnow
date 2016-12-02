<?
//--- Captcha image generator
include_once ("../../../modules/DB.php");
include_once ("../../../modules/connect.php");
include_once ("../../../modules/profile.php");
include_once ("../../../modules/session.php");
include_once ("../../../modules/preferences.php");

function GenerateCAPTCHAKey ($Letters, $Sequence) {
//--- Generate a new key for the CAPTCHA using the seed

	return md5 ($Letters."*".$Sequence);

}

//--- Expects at least the Session_ID and $Key variables

$bgImage = 'images/captcha.gif';
$RandomPassword = GeneratePassword (6);

$_img = imagecreatefromgif ($bgImage);
$font = imageloadfont ("images/captcha-font.gdf");
imagestring ($_img, $font, 0, 17, "r a n d o m ", imagecolorallocate ($_img, 0xcc, 0xbb, 0xaa));
imagestring ($_img, $font, 0, 17, " m o d n a r", imagecolorallocate ($_img, 0xcc, 0xbb, 0xaa));
imagestring ($_img, $font, 10, 15, $RandomPassword, imagecolorallocate ($_img, 0x66, 0x44, 0x55));
imagestring ($_img, $font, 17, 24, $RandomPassword, imagecolorallocate ($_img, 0x55, 0x66, 0x77));

header ("Content-type: image/png");
imagepng ($_img);
imagedestroy ($_img);

$Profile_ID = LocateSession ($Session_ID);

//--- Save in preference so that form system can expect it
CreatePreference ($Profile_ID, None, "__CAPTCHAKey".$Key, GenerateCAPTCHAKey ($RandomPassword, $Session_ID."&".$Key));
?>