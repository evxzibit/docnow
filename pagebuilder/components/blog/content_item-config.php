<?
global $Item_ID;
global $Session_ID;

//--- Setup
$TIncludeFile = "blog/content_item-config.php";
$Container_ID = $Container['Container_ID'];

if (isset ($Item_ID)) {
	UpdateContainerParameters ($Container_ID, $Item_ID); die ("<html><body><script>window.close ()</script></body></html>");
} else {
?>
<form action="pagebuilder-container-settings.php" method="post">

<table border="0" cellpadding="0" cellspacing="0">
<tr valign="top">
	<td><input type="radio" name="Source"<? if ($Parameters != -1) { echo " checked=\"checked\""; } ?> /></td>
	<td style="padding-top: 2px">
	Select Item:<br />
	<input type="text" name="ItemCode" value="<? echo ($Parameters != USER_PROFILE ? RetrieveCatalogItemCode ($Parameters) : ''); ?>" size="18" readonly />&nbsp;<input type="button" value="Browse" onclick="this.form.Source[0].checked = true; window.open ('../admin/IMM-select.php?Session_ID=<? echo $Session_ID; ?>&Category_ID=<? echo RetrieveCatalogCategoryByItemID ($Parameters); ?>&Type=<? echo CATALOG_CONTENT; ?>', 'popup<? echo GetRandom (1000); ?>', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,alwaysRaised=yes,titlebar=no,width=325,height=315');" /><input type="hidden" name="Item_ID" value="<? echo $Parameters; ?>" /><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>or</b><br />
	</td>
</tr>
<tr valign="top">
	<td><input type="radio" name="Source"<? if ($Parameters == -1) { echo " checked=\"checked\""; } ?> onclick="this.form.Item_ID.value = '-1'; this.form.ItemCode.value = ''" /></td>
	<td style="padding-top: 2px">
	Item Selected by User
	</td>
</tr>
</table>
<br />
<p align="right"><input type="submit" value="Save" />&nbsp;&nbsp;<input type="button" value="Close Window" onclick="window.close ()" /></p>
<?
echo RetrieveHiddenField ("IncludeFile", $TIncludeFile);
echo RetrieveHiddenField ("Container_ID", $Container_ID);
echo RetrieveHiddenField ("Session_ID", $Session_ID);
?>
</form>
<?
} // end if
?>