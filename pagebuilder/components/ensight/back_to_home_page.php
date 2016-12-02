<?
global $Session_ID;
global $UserStatus;

if ($UserStatus < PROFILE_REGISTERED) {
?>
<!-- Back to Home component -->
Return to the <a href="<? echo ($IncludePath ? ThisURL : "").HOME; ?>?Session_ID=<? echo $Session_ID; ?>">Home Page</a>
<?
} else {
?>
<!-- Back to Home component -->
Return to your <a href="<? echo ($IncludePath ? ThisURL : "").PHP; ?>?Session_ID=<? echo $Session_ID; ?>">Personalized Home Page</a>
<?
} // end if
?>