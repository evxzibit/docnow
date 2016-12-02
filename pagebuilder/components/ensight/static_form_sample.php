<?
include_once ("modules/DB.php");
include_once ("modules/connect.php");
include_once ("modules/utils.php");
include_once ("modules/form.php");
include_once ("modules/reference.php");

global $UserStatus, $Filename;
global $Session_ID, $Profile_ID, $Reference_ID, $Errors, $Error_NUM;

//--- $Item_ID must be the appropriate Item #
$Item_ID = USER_PROFILE;
$Category_ID = RetrieveCatalogCategoryByItemID ($Item_ID);

//--- Link to item: CPE."?DestItem_ID=X"
//--- Link to page: "thank-you-page.php"
$Dest_URL = CPE."?DestItem_ID=X";

//--- Uncomment if you wish to prepopulate the form
//$ReferenceList = RetrieveReferenceList (None, $Profile_ID, $Item_ID, None, None, None, None, ORDER_BY_DATE, ORDER_DESC, 0, 1);
//$Reference = ReadFromDB ($ReferenceList);
//if ($Reference) {
//	$Reference_ID = $Reference['Reference_ID'];
//}
?>
<!-- Static form display component -->
<form enctype="multipart/form-data" action="<? if ($IncludePath) { echo ThisURL.ROOT_URL."/"; } ?>FPM.php" method="<? echo ($IncludePath ? "get" : "post"); ?>">
<?
//--- Error
if ($Error_NUM == 12) {
	echo "<p><a name=\"Error\"><b>Please complete all fields marked with an asterisk (*)</b></a></p>\n";
}
?>

<table cellpadding="2" cellspacing="0" border="0">
<tr>
	<td>What is your first name? </td>
	<td><? echo RetrieveFormProfileElementByName ("First Name", "", $Item_ID, $Profile_ID, $Reference_ID); ?></td>
</tr>
<tr>
	<td>What is your last name? </td>
	<td><? echo RetrieveFormProfileElementByName ("Last Name", "", $Item_ID, $Profile_ID, $Reference_ID); ?></td>
</tr>
<tr>
	<td>Which newsletters would you like to receive? </td>
	<td><? echo PrepareContent (RetrieveFormProfileElementByName ("Newsletter Preferences", "Preferences", $Item_ID, $Profile_ID, $Reference_ID)); ?></td>
</tr>
<tr>
	<td colspan="2" align="center"><br /><input type="submit" value="Submit &gt;&ht;" /></td>
</tr>
</table>

<?
//--- Required fields
echo RetrieveHiddenField ("Item_ID", $Item_ID);
echo RetrieveHiddenField ("Reference_ID", $Reference_ID);

//--- Set source and destination paths
if ($IncludePath) {
	echo RetrieveHiddenField ("Src_URL", "");
	echo RetrieveHiddenField ("Dest_URL", "monster.php?URL=".$Dest_URL."&Src=".$ThisOrigin);
} else {
	echo RetrieveHiddenField ("Src_URL", ($Filename ? ROOT_URL."/".$Filename : CPE)."?Item_ID=$Item_ID");
	echo RetrieveHiddenField ("Dest_URL", $Dest_URL);
}

//--- User profile identifier
if ($Session_ID) {
	echo RetrieveHiddenField ("Session_ID", $Session_ID);
} else {
	echo RetrieveHiddenField ("Profile_ID", $Profile_ID);
}

echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"".MaxSizeOfUploadedFiles."\" />\n";
?>
</form>