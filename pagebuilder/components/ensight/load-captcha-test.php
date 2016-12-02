<?
include_once ("../../../modules/utils.php");
?>
<img id="CAPTCHA_Image" src="load-captcha.php?Key=<? echo $_Item_ID; ?>&Session_ID=<? echo $Session_ID; ?>&Rand=<? echo GetRandom (999999); ?>" width="150" height="70" border="1" alt="" /><br /><a href="#" onclick="document.getElementById ('CAPTCHA_Image').src = 'load-captcha.php?Key=12345&Session_ID=<? echo $Session_ID; ?>&Rand=' + (Math.random () * 999999); return false"><small>Generate another random image</small></a><input type="text" name="CAPTCHA" style="width: 150px" />
