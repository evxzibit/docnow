<?
include_once ("modules/form.php");
include_once ("modules/reference.php");

if (!function_exists ("Poll_CountFormSelectedValues")) {

function Poll_CountFormSelectedValues ($Result) {
//--- Quick count of field results - see CountFormReferenceValues

	switch ($Result['FieldType_CHAR']) {
		case 'D':	
		case 'R':	//--- Drop Down, Radio Button
					$SQL = "SELECT Options.OptionValue_STRING, COUNT(Options.Option_ID) AS counter FROM Options, Profiles_Values WHERE Profiles_Values.Form_ID = '".$Result['Form_ID']."' AND Options.Form_ID = '".($Result['ValueForm_ID'] == -1 ? $Result['Form_ID'] : $Result['ValueForm_ID'])."' AND Profiles_Values.FieldValue_STRING != '' ".($Range ? "AND Profiles_Values.Reference_ID IN ($Range) " : "")."AND Profiles_Values.FieldValue_STRING = Options.Option_ID GROUP BY Options.OptionValue_STRING"; // Options.Form_ID = Profiles_Values.Form_ID AND 
					break;
		case 'F':	
		case 'G':	//--- Multi-Selects and Checkbox Groups
					$SQL = "SELECT Options.OptionValue_STRING, COUNT(Options.Option_ID) AS counter FROM Options, Profiles_Values WHERE Profiles_Values.Form_ID = '".$Result['Form_ID']."' AND Options.Form_ID = '".($Result['ValueForm_ID'] == -1 ? $Result['Form_ID'] : $Result['ValueForm_ID'])."' AND Profiles_Values.FieldValue_STRING != '' ".($Range ? "AND Profiles_Values.Reference_ID IN ($Range) " : "")."AND Profiles_Values.FieldValue_STRING LIKE ".ConcatQueryDB (ConcatQueryDB ("'%,'", "CHAR(Options.Option_ID)"), "'%'")." GROUP BY Options.OptionValue_STRING"; // Options.Form_ID = Profiles_Values.Form_ID AND 
					break;
		case 'C':	
					//--- Single Checkbox
					$SQL = "SELECT FieldValue_STRING, COUNT(FieldValue_STRING) AS counter FROM Profiles_Values WHERE Form_ID = '".$Result['Form_ID']."' AND FieldValue_STRING != '' ".($Range ? "AND Reference_ID IN ($Range) " : "")."GROUP BY FieldValue_STRING";
					break;
		case 'O':	
					//--- Country Select
					$SQL = "SELECT Countries.Description_STRING, COUNT(Countries.Domain_STRING) AS counter FROM Profiles_Values, Countries WHERE Profiles_Values.Form_ID = '".$Result['Form_ID']."' AND Profiles_Values.FieldValue_STRING = Countries.Domain_STRING AND Profiles_Values.FieldValue_STRING != '' ".($Range ? "AND Profiles_Values.Reference_ID IN ($Range) " : "")."GROUP BY Countries.Description_STRING";
					break;
		case 'V':	
					//--- Language Select
					$SQL = "SELECT Languages.Description_STRING, COUNT(Languages.Locale_ID) AS counter FROM Profiles_Values, Languages WHERE Profiles_Values.Form_ID = '".$Result['Form_ID']."' AND Profiles_Values.FieldValue_STRING = Languages.Locale_ID AND Profiles_Values.FieldValue_STRING != '' ".($Range ? "AND Profiles_Values.Reference_ID IN ($Range) " : "")."GROUP BY Languages.Description_STRING";
					break;
		default:	
					//--- All Other Fields
					$SQL = "SELECT COUNT(FieldValue_STRING) AS counter FROM Profiles_Values WHERE Form_ID = '".$Result['Form_ID']."' AND FieldValue_STRING != '' ".($Range ? "AND Reference_ID IN ($Range) " : "");
					break;
	}

	return QueryDB ($SQL);

}

} // end if function_exists

if (!function_exists ("Poll_DisplayFormSelectedValues")) {

function Poll_DisplayFormSelectedValues ($Container, $Fields, $Total) {
//--- Formatting of field results

	$HTML  = "<table cellpadding=\"2\" cellspacing=\"0\" border=\"0\">\n";

	$i = 0;
	while ($Field = ReadFromDB ($Fields)) {

		//--- Optional step to get the text value to display
		switch ($Field['FieldType_CHAR']) {
			case 'D':	
			case 'R':	
			case 'F':	
			case 'G':	
			case 'O':	
			case 'V':	
			case 'C':	$Vals = Poll_CountFormSelectedValues ($Field);
						$HTML = $HTML.($i ? "<tr><td>&nbsp;</td></tr>\n" : "")."<tr><td><b>".$Field['FieldName_STRING']."</b></td></tr>\n"; $i++;
						while ($Result = ReadFromDB ($Vals)) {
							$HTML .= "<tr><td>".($Result['FieldType_CHAR'] == 'C' ? (!$Result['OptionValue_STRING'] ? 'No' : 'Yes') : $Result['OptionValue_STRING'])."<br /><img src=\"pagebuilder/components/ensight/images/chartspacer.gif\" align=\"absMiddle\" height=\"13\" width=\"".round ($Result['counter'] / $Total * 100)."\" alt=\"".number_format ($Result['counter'], 0)." vote(s)\" /> ".number_format (($Result['counter'] / $Total * 100), 0)."%</td></tr>\n";
						}
						break;
			default: 	continue;
		}

	}

	$HTML .= "</table><br />\n";
	$HTML .= "<center><b>Total Votes: $Total</b></center>\n";

	return $HTML;

}

} // end if function_exists

if (!function_exists ("Poll_DisplayFormPoll")) {

function Poll_DisplayFormPoll ($Container, $Fields, $Item_ID) {
//--- Formats the poll questions

	global $UserStatus, $Filename, $IncludePath;
	global $Profile_ID, $Reference_ID;

	$HTML  = "<form action=\"".($IncludePath ? ThisURL : "")."FPM.php\" method=\"post\" onsubmit=\"checkField = null; for (i = 0; i < this.elements.length; i++) { if ((this.elements[i].type == 'hidden') && (this.elements[i].name.substr (0, 4) == 'Form')) { checkField = this.elements[i]; } } if ((checkField) && (checkField.value == '')) { alert ('Please vote on your favourite option.'); return false; }\">\n";

	//--- Display fields
	$i = 0;
	while ($Field = ReadFromDB ($Fields)) {
		//--- Optional step to get the text value to display
		switch ($Field['FieldType_CHAR']) {
			case 'D':	
			case 'R':	
			case 'F':	
			case 'G':	
			case 'O':	
			case 'V':	
			case 'C':	$HTML .= ($i ? "<br />" : "")."<b>".$Field['FieldName_STRING']."</b><br />\n".nl2br (RetrieveNextFormProfileElement ($Field, $Profile_ID, $Reference_ID)); $i++;
						break;
		}
	}

	$HTML .= "<center><br /><input type=\"submit\" value=\"Vote\" /><br /></center>\n";
	if ($IncludePath) {
		$HTML .= RetrieveHiddenField ("Src_URL", "");
		$HTML .= RetrieveHiddenField ("Dest_URL", ($Filename ? ROOT_URL."/".$Filename : CPE));
	} else {
		$HTML .= RetrieveHiddenField ("Src_URL", ($Filename ? ROOT_URL."/".$Filename : CPE));
		$HTML .= RetrieveHiddenField ("Dest_URL", ($Filename ? ROOT_URL."/".$Filename : CPE));
	}
	$HTML .= RetrieveHiddenField ("Item_ID", $Item_ID);
	$HTML .= RetrieveHiddenField ("Reference_ID", $Reference_ID);
	$HTML .= RetrieveHiddenField ("Profile_ID", $Profile_ID);
	$HTML .= "</form>\n";

	return $HTML;

}

} // end if function_exists

global $UserStatus, $Filename;
global $Session_ID, $Profile_ID;

if (!$Parameters) {
	$Parameters = $_REQUEST['Item_ID'];
}

//--- Check if I've filled this form in already
$References = RetrieveReferenceList (None, $Profile_ID, $Parameters, None, REFERENCE_RESERVED, None, None, None, None, 0, 1);
$ReferenceCount = RetrieveReferenceCount (None, None, $Parameters, None, REFERENCE_RESERVED, None, None);
$Reference1 = ReadFromDB ($References);
$Reference_ID = $Reference1['Reference_ID'];

//--- Get list of fields
$Fields = RetrieveFormFields ($Parameters, FORM_PROFILE, None, STATUS_VISIBLE, ORDER_BY_PREDEF, None, None, None);

if (!$Reference_ID) {
	$Value = Poll_DisplayFormPoll ($Container, $Fields, $Parameters);
} else {
	$Value = Poll_DisplayFormSelectedValues ($Container, $Fields, $ReferenceCount);
}
?>
<!-- Voting booth component -->
<?
echo $Value;
?>
