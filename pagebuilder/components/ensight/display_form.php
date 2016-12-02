<?
include_once ("modules/form.php");
include_once ("modules/reference.php");

if (!function_exists ("PrintInColumns")) {

function PrintInColumns ($HTML, $MaxColumns) {
//--- Displays a selection form field in columns

	if (($HTML == '') || ($MaxColumns <= 0)) {
		return $HTML;
	}

	$Parts = explode ("\n", $HTML);
	$Count = count ($Parts);
	$Table = array ();

	//--- Pad array with blanks
	for ($i = 0; $i < ceil ((count ($Parts) - 1) / $MaxColumns); $i++) {
		for ($j = 0; $j < $MaxColumns; $j++) {
			$Table[$i][$j] = "<td>&nbsp;</td>\n";
		}
	}

	//--- Fill array where data exists
	$r = 0; $c = 0;
	for ($k = 0; $k < count ($Parts) - 1; $k++) {
		$Table[$r][$c] = "<td>".$Parts[$k]."</td>\n";
		if ($c < ($MaxColumns - 1)) {
			$c++;
		} else {
			$r++; $c = 0;
		}
	}

	//--- Return results
	$HTML  = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\">\n";
	for ($x = 0; $x < count ($Table); $x++) {
		$HTML .= "<tr>\n".implode ($Table[$x])."</tr>\n";
	}
	$HTML .= "</table>\n";
	$HTML .= $Parts[$Count - 1]."\n";

	return $HTML;

}

}

//--- Edit these settings only
$TableHeader = "<table cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n";
$GroupHeader = "<table cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n<tr>\n<td><b>{Group}</b></td>\n</tr>\n";
$TableOneRow = "<tr valign=\"top\">\n<td>\n<b>{Caption}</b>{Mandatory}{Help}<br />{Field}</td>\n</tr>\n";
$TableSubmit = "<tr>\n<td><div align=\"center\"><br /><input type=\"submit\" value=\"Submit &gt;&gt;\" name=\"SubmitButton\" /></div></td>\n</tr>\n";
$IsMandatory = "<font color=\"red\">*</font>";
$HelpMessage = "<br /><small>{Help}</small>";
$TableFooter = "</table>\n";
$GroupFooter = "</table>\n";
$NoGroupHead = "";
$GroupSpacer = "";

//------------------------------------------------------------

global $UserStatus, $Filename;
global $Session_ID, $Profile_ID, $Item_ID, $DestItem_ID, $Reference_ID, $Errors, $Error_NUM;

if ($Parameters == false) {
	return;
}

//--- Dynamic or static?
if ($Parameters) {
	list ($_Item_ID, $Dest_URL, $Group, $Prepopulate, $ShowSection, $IgnoreActions) = explode ('~', $Parameters);
}

//--- What is our item source?
if ($_Item_ID == -1) {
	$_Item_ID = $Item_ID; if ($DestItem_ID) { $Save_ID = $_Item_ID; $_Item_ID = $DestItem_ID; }
}

if (!$Category_ID) {
	$_Category_ID = RetrieveCatalogCategoryByItemID ($_Item_ID);
} else {
	$_Category_ID = $Category_ID;
}

//--- Permission?
if (!IsAllowed ($_Category_ID, $UserStatus)) {
	echo "You do not have permission to access this story."; return;
}

if ($Reference_ID) {
	$TRef = RetrieveReference ($Reference_ID);
	if ($TRef['Item_ID'] != $_Item_ID) {
		$Reference_ID = 0;
	}
}

if ($Prepopulate) {
	if ($Reference_ID == false) {
		$GetReference = ReadFromDB (RetrieveReferenceList (None, $Profile_ID, $_Item_ID, None, None, None, None, ORDER_BY_DATE, ORDER_DESC, 0, 1));
		$Reference_ID = ($GetReference['Reference_ID']);
		$T_Profile_ID = ($GetReference['Profile_ID'] ? $GetReference['Profile_ID'] : $Profile_ID);
	} else {
		$GetReference = RetrieveReference ($Reference_ID);
		$T_Profile_ID = ($GetReference['Profile_ID'] ? $GetReference['Profile_ID'] : $Profile_ID);
	}
} else {
	$T_Profile_ID = ($Profile_ID);
}
?>
<!-- Dynamic form display component -->
<?
$FormIdent = "dynamicForm".$_Item_ID;

if (($ShowSection == 'A') || ($ShowSection == 'T') || ($ShowSection == '0') || ($ShowSection == '1')) {
?>
<form id="<? echo $FormIdent; ?>" name="<? echo $FormIdent; ?>" enctype="multipart/form-data" action="<? echo ($IncludePath ? ThisURL : "").ROOT_URL."/"; ?>FPM.php" method="<? echo ($IncludePath ? "get" : "post"); ?>"<? echo ($IncludePath ? "" : " onsubmit=\"var isOkay = __ValidateForm (this); if ((isOkay) && (this.elements['SubmitButton'])) { this.elements['SubmitButton'].disabled = true; } return isOkay\""); ?>>

<?
	if (!$IncludePath) {
?>
<script src="<? echo ROOT_URL; ?>/pagebuilder/components/ensight/form_display.js.php?Session_ID=<? echo $Session_ID; ?>" type="text/javascript"></script>
<?
	} // end if

	//--- Error
	if ($Error_NUM == 12) {
		echo "<p><a name=\"Error\"><b>Please complete all fields marked with an asterisk (*)</b></a></p>\n";
	}

} // end if ShowSection

$XQuery = RetrieveFormFields ($_Item_ID, FORM_PROFILE, ($Group != '*' ? $Group : None), FIELD_VISIBLE, ORDER_BY_GROUP, None, None, None);

$ThisGroup = false;

while ($Result = ReadFromDB ($XQuery)) {

	if ($ThisGroup == false) {
		echo ($Result['FieldGroup_STRING'] == '' ? $TableHeader : str_replace ("{Group}", $Result['FieldGroup_STRING'], $GroupHeader));
		$ThisGroup = $Result['FieldGroup_STRING'];
	} else
	if (($Group == '*') && ($Result['FieldGroup_STRING'] != $ThisGroup)) {
		echo $GroupFooter;
		echo $GroupSpacer;
		echo ($Result['FieldGroup_STRING'] == '' ? $NoGroupHead : str_replace ("{Group}", $Result['FieldGroup_STRING'], $GroupHeader));
		$ThisGroup = $Result['FieldGroup_STRING'];
	}

	$Element = RetrieveNextFormProfileElement ($Result, $T_Profile_ID, $Reference_ID);

	//--- Exclude certain fields if inside an email
	if ($IncludePath) {
		switch ($Result['FieldType_CHAR']) {
			case 'F':	
			case 'G':	
			case 'J':	break 2;
			case 'R':	$Element = str_replace ("_X\"", "\"", $Element);
						$Element = explode ("\n", $Element);
						$Element = implode ("\n", array_slice ($Element, 0, -1));
		}
	}

	switch ($Result['FieldType_CHAR']) {
		case 'G':	$Element = PrintInColumns ($Element, $Result['FieldSize_NUM']); break;
		case 'R':	$Element = PrintInColumns ($Element, $Result['FieldSize_NUM']); break;
	}

	//--- Add validation
	if (!$IncludePath) {
		switch ($Result['FieldValidationType_NUM']) {
			case '1':	$Element = InsertFieldScript ($Element, EVENT_ONKEYPRESS, "return __ValidateField (event, this.value, '1');"); break; //--- Numbers only
			case '2':	$Element = InsertFieldScript ($Element, EVENT_ONKEYPRESS, "return __ValidateField (event, this.value, '2');"); break; //--- Decimal numbers only
			case '3':	$Element = InsertFieldScript ($Element, EVENT_ONKEYPRESS, "return __ValidateField (event, this.value, '3');"); break; //--- Letters only
			case '4':	$Element = InsertFieldScript ($Element, EVENT_ONKEYPRESS, "return __ValidateField (event, this.value, '4');"); break; //--- Numbers and letters only
		}
	}

	if ((($Result['FieldValidationType_NUM']) || (($Result['FieldMandatory_NUM']) && ($Result['FieldMandatoryError_STRING']))) && (!$IncludePath)) {
		$Validations[] = RetrieveHiddenField ("Validate".$Result['Form_ID']."-".$T_Profile_ID, $Result['FieldValidationType_NUM']."|".str_replace ("\"", "&quot;", ($Result['FieldMandatory_NUM'] ? $Result['FieldMandatoryError_STRING'] : ''))."|".$Result['FieldSize_NUM']);
	}

	$TLine = $TableOneRow;
	$TLine = str_replace ("{Caption}", $Result['FieldName_STRING'], $TLine);
	$TLine = str_replace ("{Mandatory}", ($Result['FieldMandatory_NUM'] ? $IsMandatory : ""), $TLine);
	$TLine = str_replace ("{Help}", ($Result['FieldHelp_STRING'] ? str_replace ("{Help}", $Result['FieldHelp_STRING'], $HelpMessage) : ""), $TLine);
	$TLine = str_replace ("{Field}", $Element, $TLine);
	echo $TLine;

} // end while

if (($ShowSection == 'A') || ($ShowSection == 'B') || ($ShowSection == '0') || ($ShowSection == '1')) {

	echo ($TableSubmit);
	echo ($ThisGroup == '' ? $TableFooter : $GroupFooter);

	//--- Required fields
	echo RetrieveHiddenField ("Item_ID", $_Item_ID);
	echo RetrieveHiddenField ("Reference_ID", $Reference_ID);

	//--- Set source and destination paths
	if ($IncludePath) {
		echo RetrieveHiddenField ("Src_URL", "");
		echo RetrieveHiddenField ("Dest_URL", "click.php?u=".$Dest_URL."&o=".$ThisOrigin);
	} else {
		echo RetrieveHiddenField ("Src_URL", (substr ($_SERVER['PHP_SELF'], 0, strlen (ROOT_URL)) != ROOT_URL ? $_SERVER['PHP_SELF'] : $_SERVER['PHP_SELF'].($_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : '')));
		echo RetrieveHiddenField ("Dest_URL", $Dest_URL);
	}

	if ($IgnoreActions) {
		echo RetrieveHiddenField ("NoAction", 1);
	}

	//--- User profile identifier
	echo ($Session_ID ? RetrieveHiddenField ("Session_ID", $Session_ID) : "");
	echo ($T_Profile_ID != $Profile_ID ? RetrieveHiddenField ("Profile_ID", $T_Profile_ID) : "");

	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"".MaxSizeOfUploadedFiles."\" />\n";

	if (is_array ($Validations)) {
		echo implode ("", $Validations);
	}

	echo "</form>\n";

} else {

	echo ($ThisGroup == '' ? $TableFooter : $GroupFooter);

} // end if ShowSection
?>