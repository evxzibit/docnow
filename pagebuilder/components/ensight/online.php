<?
include_once ("modules/session.php");

global $Session_ID;

$Guest = RetrieveSessionCount (PROFILE_GUEST, None, None);
$Registered = RetrieveSessionCount (None, None, None) - $Guest;

echo "There ".($Guest != 1 ? "are" : "is")." $Guest guest".($Guest != 1 ? "s" : "")." and $Registered registered user".($Registered != 1 ? "s" : "")." online.";
?>