<?
include_once ("modules/DB.php");
include_once ("modules/connect.php");
include_once ("modules/utils.php");
include_once ("modules/profile.php");
include_once ("modules/session.php");
include_once ("modules/catalog.php");

global $Item_ID;
global $Session_ID;

//--- Setup
$TIncludeFile = "ensight/poll-config.php";
$Container_ID = $Container['Container_ID'];

if ($Item_ID) {
	//--- Save to database or into form field
	if ($Container_ID != 0) {
		UpdateContainerParameters ($Container_ID, $Item_ID); die ("<html><body><script>window.close ()</script></body></html>");
	} else {
		die ("<html><body><script>if (opener.document.forms[0].Parameters) { opener.document.forms[0].Parameters.value = '$Item_ID'; } window.close ()</script></body></html>");
	}
} else {
?>
<FORM ACTION="pagebuilder-container-settings.php" METHOD="post">
Select Item:<br>
<INPUT TYPE="TEXT" NAME="ItemCode" VALUE="<? echo RetrieveCatalogItemCode ($Parameters); ?>" SIZE="18" READONLY>&nbsp;<INPUT TYPE="BUTTON" VALUE="Browse" onClick="JavaScript:window.open ('../admin/IMM-select.php?Session_ID=<? echo $Session_ID; ?>&Category_ID=<? echo RetrieveCatalogCategoryByItemID ($Parameters); ?>&Type=<? echo CATALOG_CONTENT; ?>', 'popup<? echo GetRandom (1000); ?>', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,alwaysRaised=yes,titlebar=no,width=320,height=290');"><INPUT TYPE="HIDDEN" NAME="Item_ID" VALUE="<? echo $Parameters; ?>"><br><br>
<P ALIGN="right"><INPUT TYPE="SUBMIT" VALUE="Save">&nbsp;&nbsp;<INPUT TYPE="BUTTON" VALUE="Close Window" onClick="window.close ()"></P>
<?
echo RetrieveHiddenField ("IncludeFile", $TIncludeFile);
echo RetrieveHiddenField ("Container_ID", $Container_ID);
echo RetrieveHiddenField ("Session_ID", $Session_ID);
?>
</FORM>
<?
} // end if
?>
