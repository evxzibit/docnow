<?
global $Item_ID;
global $Dest_URL, $DestItem_ID, $Group, $Prepopulate, $ShowSection, $IgnoreActions, $SaveChanges;
global $Session_ID;

if ($Session_ID) {
	$Profile_ID = LocateSession ($Session_ID);
}

//--- Setup
$TIncludeFile = "ensight/display_form-config.php";
$Container_ID = $Container['Container_ID'];

if ($DestItem_ID) {
	$Dest_URL = CPE."?DestItem_ID=".$DestItem_ID;
}

if ($SaveChanges) {
	UpdateContainerParameters ($Container_ID, $Item_ID."~".$Dest_URL."~".$Group."~".$Prepopulate."~".$ShowSection."~".$IgnoreActions); die ("<html><body><script>window.close ()</script></body></html>");
} else {
	if (($Parameters) && (!isset ($Item_ID))) {
		list ($Item_ID, $Dest_URL, $Group, $Prepopulate, $ShowSection, $IgnoreActions) = explode ('~', $Parameters);
	}

	$Group = stripslashes ($Group);
	$Dest_URL = stripslashes ($Dest_URL);
?>
<form action="pagebuilder-container-settings.php" method="post" onsubmit="this.SaveChanges.value = 1">

<table border="0" cellpadding="0" cellspacing="0">
<tr valign="top">
	<td><input type="radio" name="Source"<? if ($Item_ID != -1) { echo " checked=\"checked\""; } ?> /></td>
	<td style="padding-top: 2px">
	Select Item:<br />
	<input type="text" name="ItemCode" value="<? echo ($Item_ID != USER_PROFILE ? RetrieveCatalogItemCode ($Item_ID) : ($Item_ID != -1 ? 'User Profile' : '')); ?>" size="18" readonly="readonly" />&nbsp;
	<input type="button" value="Browse" onclick="this.form.Source[0].checked = true; window.open ('../admin/IMM-select.php?Session_ID=<? echo $Session_ID; ?>&Category_ID=<? echo RetrieveCatalogCategoryByItemID ($Item_ID); ?>&ShowUserProfile=1&Type=<? echo CATALOG_CONTENT.",".CATALOG_FORM; ?>&Dest_URL=' + escape ('pagebuilder-container-settings.php?IncludeFile=<? echo $TIncludeFile; ?>&Container_ID=<? echo $Container_ID; ?>&Group=' + this.form.Group.options[this.form.Group.selectedIndex].value + '&Prepopulate=' + this.form.Prepopulate.options[this.form.Prepopulate.selectedIndex].value + '&Dest_URL=' + this.form.Dest_URL.value + '&DestItem_ID=' + this.form.DestItem_ID.value) + '&Item_ID=' + this.form.Item_ID.value, 'popup<? echo GetRandom (1000); ?>', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,alwaysRaised=yes,titlebar=no,width=325,height=315');" />
	<input type="hidden" name="Item_ID" value="<? echo $Item_ID; ?>" /><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>or</b><br />
	</td>
</tr>
<tr valign="top">
	<td><input type="radio" name="Source"<? if ($Item_ID == -1) { echo " checked=\"checked\""; } ?> onclick="this.form.Item_ID.value = '-1'; this.form.ItemCode.value = ''" /></td>
	<td style="padding-top: 2px">
	Item Selected by User
	</td>
</tr>
<tr>
	<td colspan="2"><hr size="1" noshade="noshade" /></td>
</tr>
<tr valign="top">
	<td>&nbsp;</td>
	<td style="padding-top: 2px">
	Display Fields In:<br />
	<select name="Group" style="width: 123px">
	<?
	$FieldGroups = RetrieveFormFieldGroups ($Item_ID, FORM_PROFILE, None, ORDER_BY_GROUP, None, None, None);

	while ($NewGroup = ReadFromDB ($FieldGroups)) {
		echo RetrieveDropDownOption ($NewGroup['FieldGroup_STRING'], $Group, ($NewGroup['FieldGroup_STRING'] == '' ? "Ungrouped Fields" : $NewGroup['FieldGroup_STRING']));
	}

	echo RetrieveDropDownOption ("*", $Group, "All Groups");
	?>
	</select><p />
	</td>
</tr>
<tr valign="top">
	<td>&nbsp;</td>
	<td style="padding-top: 2px">
	Prepopulate form with values if already filled in?<br />
	<select name="Prepopulate">
	<?
	echo RetrieveDropDownOption ("0", $Prepopulate, "No");
	echo RetrieveDropDownOption ("1", $Prepopulate, "Yes");
	?>
	</select><br />
	Ignore action paths?<br />
	<select name="IgnoreActions">
	<?
	echo RetrieveDropDownOption ("0", $IgnoreActions, "No");
	echo RetrieveDropDownOption ("1", $IgnoreActions, "Yes");
	?>
	</select><br />
	Display as which section of the form?<br />
	<select name="ShowSection">
	<?
	echo RetrieveDropDownOption ("A", $ShowSection, "Complete Form");
	echo RetrieveDropDownOption ("T", $ShowSection, "Top");
	echo RetrieveDropDownOption ("M", $ShowSection, "Middle");
	echo RetrieveDropDownOption ("B", $ShowSection, "Bottom");
	?>
	</select> [ <a href="#" onclick="alert ('Select Complete Form to display the complete form, with all required markup at the top and bottom. Alternatively, select either Top (only once), Middle (as often as required) or Bottom (only once) to create a multi-part form.'); return false">?</a> ]<br />
	</td>
</tr>
<tr>
	<td colspan="2"><hr size="1" noshade="noshade" /></td>
</tr>
<tr valign="top">
	<td>&nbsp;</td>
	<td style="padding-top : 2px">
	Landing Page:<br />
	<input type="text" name="Dest_URL" value="<? echo $Dest_URL; ?>" size="18" onchange="this.form.DestItem_ID.value = ''" />&nbsp;<input type="button" value="Browse" onclick="this.form.DestItem_ID.value = ''; window.open ('../admin/IMM-select.php?Session_ID=<? echo $Session_ID; ?>&Category_ID=&Type=<? echo CATALOG_CONTENT; ?>&ItemField=DestItem_ID&CodeField=Dest_URL', 'popup<? echo GetRandom (1000); ?>', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,alwaysRaised=yes,titlebar=no,width=320,height=290');" /><input type="hidden" name="DestItem_ID" value="" />
	</td>
</tr>
</table>
<br />

<p align="right"><input type="submit" value="Save" />&nbsp;&nbsp;<input type="button" Value="Close Window" onclick="window.close ()" /></p>
<?
echo RetrieveHiddenField ("IncludeFile", $TIncludeFile);
echo RetrieveHiddenField ("Container_ID", $Container_ID);
echo RetrieveHiddenField ("Session_ID", $Session_ID);
echo RetrieveHiddenField ("SaveChanges", 0);
?>
</form>
<?
} // end if
?>