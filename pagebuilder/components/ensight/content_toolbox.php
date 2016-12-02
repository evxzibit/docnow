<?
include_once ("modules/content.php");
include_once ("modules/utils.php");

global $UserStatus;
global $Session_ID, $Profile_ID, $Item_ID, $DestItem_ID, $Revision, $Start;

if ($Parameters == false) {
	$Parameters = -1;
}

//--- What is our item source?
if ($Parameters == -1) {
	$_Item_ID = $Item_ID; if ($DestItem_ID) { $Save_ID = $_Item_ID; $_Item_ID = $DestItem_ID; }
} else {
	$_Item_ID = $Parameters;
}

//--- Set version control defaults
if (($UserStatus == PROFILE_ADMINISTRATOR) && ($Revision)) {
	//--- Preview mode
	$Status = None;
	list ($Language, $Version) = explode ('/', $Revision);
} else {
	$Status = STATUS_PUBLISHED;
	list ($Language, $Version) = explode ('/', LocateBestContentLanguage (GetVar ("HTTP_ACCEPT_LANGUAGE"), $_Item_ID, STATUS_PUBLISHED)."/".None);
}
?>
<!-- Content toolbox component -->
<table border="0" cellpadding="0" cellspacing="2">
<tr>
	<td><a href="#" onclick="window.open ('<? echo ($IncludePath ? ThisURL.ROOT_URL."/" : ROOT_URL."/"); ?>pagebuilder/components/ensight/content_toolbox_email.php?Item_ID=<? echo $_Item_ID; ?>&Language=<? echo $Language; ?>&Version=<? echo $Version; ?>&Category_ID=<? echo $Category_ID; ?>&Session_ID=<? echo $Session_ID; ?>', 'sendWindow', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width=600,height=380'); return false"><img src="<? echo ($IncludePath ? ThisURL.ROOT_URL."/" : ROOT_URL."/"); ?>pagebuilder/components/ensight/images/mail.gif" width="16" height="16" border="0" align="middle" /></a>&nbsp;</td>
	<td><a href="#" onclick="window.open ('<? echo ($IncludePath ? ThisURL.ROOT_URL."/" : ROOT_URL."/"); ?>pagebuilder/components/ensight/content_toolbox_email.php?Item_ID=<? echo $_Item_ID; ?>&Language=<? echo $Language; ?>&Version=<? echo $Version; ?>&Category_ID=<? echo $Category_ID; ?>&Session_ID=<? echo $Session_ID; ?>', 'sendWindow', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width=600,height=380'); return false">E-mail this page to...</a></td>
</tr>
<tr>
	<td><a href="#" onclick="window.open ('<? echo ($IncludePath ? ThisURL.ROOT_URL."/" : ROOT_URL."/"); ?>pagebuilder/components/ensight/content_toolbox_print.php?Item_ID=<? echo $_Item_ID; ?>&Language=<? echo $Language; ?>&Version=<? echo $Version; ?>&Category_ID=<? echo $Category_ID; ?>&Session_ID=<? echo $Session_ID; ?>', 'printWindow', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width=590,height=400'); return false"><img src="<? echo ($IncludePath ? ThisURL.ROOT_URL."/" : ROOT_URL."/"); ?>pagebuilder/components/ensight/images/print.gif" width="16" height="16" border="0" align="middle"></a>&nbsp;</td>
	<td><a href="#" onclick="window.open ('<? echo ($IncludePath ? ThisURL.ROOT_URL."/" : ROOT_URL."/"); ?>pagebuilder/components/ensight/content_toolbox_print.php?Item_ID=<? echo $_Item_ID; ?>&Language=<? echo $Language; ?>&Version=<? echo $Version; ?>&Category_ID=<? echo $Category_ID; ?>&Session_ID=<? echo $Session_ID; ?>', 'printWindow', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no,copyhistory=no,width=590,height=400'); return false">Print this page</a></td>
</tr>
</table>