<?
include_once ("modules/form.php");
include_once ("modules/reference.php");
include_once ("modules/db-functions.php");

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
	for ($i = 0; $i < ceil ((count ($Parts) - 3) / $MaxColumns); $i++) {
		for ($j = 0; $j < $MaxColumns; $j++) {
			$Table[$i][$j] = "<td>&nbsp;</td>\n";
		}
	}

	//--- Fill array where data exists
	$r = 0; $c = 0;
	for ($k = 1; $k < count ($Parts) - 2; $k++) {
		$Table[$r][$c] = "<td>".$Parts[$k]."</td>\n";
		if ($c < ($MaxColumns - 1)) {
			$c++;
		} else {
			$r++; $c = 0;
		}
	}

	//--- Return results
	$HTML  = $Parts[0]."\n";
	$HTML .= "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
	for ($x = 0; $x < count ($Table); $x++) {
		$HTML .= "<tr>\n".implode ($Table[$x])."</tr>\n";
	}
	$HTML .= "</table>\n";
	$HTML .= $Parts[$Count - 2]."\n";
	$HTML .= $Parts[$Count - 1]."\n";

	return $HTML;

}

}

if (!function_exists ("RetrieveTemplateRepeater")) {

function RetrieveTemplateRepeater ($Template, $RepeaterStart, $RepeaterEnd) {
//--- Finds and returns the named repeater

	$LevelSta = strpos ($Template, $RepeaterStart);
	$LevelEnd = strpos ($Template, $RepeaterEnd, $LevelSta);
	if (($LevelSta !== false) && ($LevelEnd !== false)) {
		$Repeater = trim (substr ($Template, $LevelSta + strlen ($RepeaterStart), $LevelEnd - $LevelSta - strlen ($RepeaterStart)));
	} else {
		$Repeater = '';
	}

	return $Repeater;

}

}

//--- Globals
global $UserStatus, $Filename;
global $Session_ID, $Profile_ID, $Item_ID, $DestItem_ID, $Reference_ID, $Errors, $Error_NUM;

//--- Pull remaining attributes from tag
$_Item_ID		= $Tag['attributes']['item'];
$Style			= $Tag['attributes']['style'];
$Dest_URL		= $Tag['attributes']['thank-you'];
$ThankYouMsg	= $Tag['attributes']['thank-you-message'];
$ThankYouShare	= $Tag['attributes']['thank-you-sharing'];
$Group			= $Tag['attributes']['group'];
$Prepopulate	= $Tag['attributes']['prepopulate'];
$ProfileProtect	= $Tag['attributes']['protection'];
$CAPTCHATest	= $Tag['attributes']['captcha'];
$ShowSection	= $Tag['attributes']['display-as'];

list ($Style, $StyleTemplate) = explode (':', $Style);

if (!$ShowSection) {
	$ShowSection = 'complete';
}

//--- What is our item source?
if ($_Item_ID == -1) {
	$_Item_ID = $Item_ID;
	if ($DestItem_ID) {
		$Save_ID = $_Item_ID; $_Item_ID = $DestItem_ID;
	}
}
if (!$Category_ID) {
	$_Category_ID = RetrieveCatalogCategoryByItemID ($_Item_ID);
} else {
	$_Category_ID = $Category_ID;
}

//--- Permission?
if (($ID) && (!IsAllowed ($Category_ID, $UserStatus))) {
	echo "You do not have permission to access this form."; return;
}

if ($Reference_ID) {
	$TRef = RetrieveReference ($Reference_ID);
	if ($TRef['Item_ID'] != $_Item_ID) {
		$Reference_ID = 0;
	}
	if (($ProfileProtect == 'yes') && ($TRef['Profile_ID'] != $Profile_ID)) {
		$Reference_ID = 0;
	}
}

if ($Prepopulate == 'yes') {
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

$FormIdent = "dynamicForm".$_Item_ID;

//--- Set display style properties
switch ($Style) {
	case '1':	$TableHeader = "<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\" class=\"FormOutline\" id=\"".$FormIdent."Struct\">\n";
				$GroupHeader = "<tr class=\"FormHeader\">\n\t<td colspan=\"2\"><b>{Group}</b></td>\n</tr>\n";
				$TableOneRow = "<tr class=\"FormOneRow\" valign=\"top\">\n\t<td style=\"padding-top: 6px\"><b>{Caption}</b>{Mandatory}<small>{Help}</small></td>\n\t<td>{Element}</td>\n</tr>\n";
				$TableNoHelp = "<tr class=\"FormOneRow\" valign=\"top\">\n\t<td style=\"padding-top: 6px\"><b>{Caption}</b>{Mandatory}<small>{Help}</small></td>\n\t<td>{Element}</td>\n</tr>\n";
				$IsMandatory = "<span style=\"color: red\">*</span>";
				$TableSubmit = "<tr class=\"FormSubmit\">\n\t<td colspan=\"2\" align=\"center\"><br /><input name=\"SubmitButton\" class=\"SubmitButton\" type=\"submit\" value=\"Submit &gt;&gt;\" /></td>\n</tr>\n";
				$CAPTCHALine = "<tr class=\"CAPTCHARow\">\n\t<td>&nbsp;</td>\n\t<td><br /><b>Please enter the 6-letter code displayed in this image:</b><br />{CAPTCHA}</td>\n</tr>\n";
				$NoGroupHead = "<tr class=\"FormSpacer\">\n\t<td colspan=\"2\">&nbsp;</td>\n</tr>\n";
				$GroupSpacer = "<tr class=\"FormSpacer\">\n\t<td colspan=\"2\">&nbsp;</td>\n</tr>\n";
				$GroupFooter = "";
				$TableFooter = "</table>\n";
				$CaptionLine = "<tr class=\"CaptionRow\">\n\t<td colspan=\"2\">{Element}</td>\n</tr>\n";
				break;
	case '2':	$TableHeader = "<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\" class=\"FormOutline\" id=\"".$FormIdent."Struct\">\n";
				$GroupHeader = "<tr class=\"FormHeader\">\n\t<td><b>{Group}</b></td>\n</tr>\n";
				$TableOneRow = "<tr class=\"FormOneRow\" valign=\"top\">\n\t<td style=\"padding-top: 6px\"><b>{Caption}</b>{Mandatory}<small>{Help}</small><br />{Element}</td>\n</tr>\n";
				$TableNoHelp = "<tr class=\"FormOneRow\" valign=\"top\">\n\t<td style=\"padding-top: 6px\"><b>{Caption}</b>{Mandatory}<small>{Help}</small><br />{Element}</td>\n</tr>\n";
				$IsMandatory = "<span style=\"color: red\">*</span>";
				$TableSubmit = "<tr class=\"FormSubmit\">\n\t<td align=\"center\"><br /><input name=\"SubmitButton\" class=\"SubmitButton\" type=\"submit\" value=\"Submit &gt;&gt;\" /></td>\n</tr>\n";
				$CAPTCHALine = "<tr class=\"CAPTCHARow\">\n\t<td><br /><b>Please enter the 6-letter code displayed in this image:</b><br />{CAPTCHA}</td>\n</tr>\n";
				$NoGroupHead = "<tr class=\"FormSpacer\">\n\t<td>&nbsp;</td>\n</tr>\n";
				$GroupSpacer = "<tr class=\"FormSpacer\">\n\t<td>&nbsp;</td>\n</tr>\n";
				$GroupFooter = "";
				$TableFooter = "</table>\n";
				$CaptionLine = "<tr class=\"CaptionRow\">\n\t<td>{Element}</td>\n</tr>\n";
				break;
	case '3':	$TableHeader = "<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\" class=\"FormOutline\" id=\"".$FormIdent."Struct\">\n";
				$GroupHeader = "<tr class=\"FormHeader\">\n<td colspan=\"2\"><b>{Group}</b></td>\n</tr>\n";
				$TableOneRow = "<tr class=\"FormOneRow\" valign=\"top\">\n\t<td align=\"right\" style=\"padding-top: 6px\"><b>{Caption}</b>{Mandatory}<small>{Help}</small></td>\n\t<td>{Element}</td>\n</tr>\n";
				$TableNoHelp = "<tr class=\"FormOneRow\" valign=\"top\">\n\t<td align=\"right\" style=\"padding-top: 6px\"><b>{Caption}</b>{Mandatory}</td>\n\t<td>{Element}</td>\n</tr>\n";
				$IsMandatory = "<span style=\"color: red\">*</span>";
				$TableSubmit = "<tr class=\"FormSubmit\">\n\t<td colspan=\"2\" align=\"center\"><br /><input name=\"SubmitButton\" class=\"SubmitButton\" type=\"submit\" value=\"Submit &gt;&gt;\" /></td>\n</tr>\n";
				$CAPTCHALine = "<tr class=\"CAPTCHARow\">\n\t<td>&nbsp;</td>\n\t<td><br /><b>Please enter the 6-letter code displayed in this image:</b><br />{CAPTCHA}</td>\n</tr>\n";
				$NoGroupHead = "<tr class=\"FormSpacer\">\n\t<td colspan=\"2\">&nbsp;</td>\n</tr>\n";
				$GroupSpacer = "<tr class=\"FormSpacer\">\n\t<td colspan=\"2\">&nbsp;</td>\n</tr>\n";
				$GroupFooter = "";
				$TableFooter = "</table>\n";
				$CaptionLine = "<tr class=\"CaptionRow\">\n\t<td colspan=\"2\">{Element}</td>\n</tr>\n";
				break;
	case '4':	$TableHeader = "<table border=\"0\" cellpadding=\"2\" cellspacing=\"2\" class=\"FormOutline\" id=\"".$FormIdent."Struct\">\n";
				$GroupHeader = "<tr class=\"FormHeader\">\n\t<td colspan=\"2\"><b>{Group}</b></td>\n</tr>\n";
				$TableOneRow = "<tr class=\"FormOneRow\" valign=\"top\">\n\t<td style=\"padding-top: 6px\"><b>{Caption}</b>{Mandatory}<small>{Help}</small></td>\n\t<td>{Element}</td>\n\t<td>&nbsp;<img src=\"".ADMIN_URL."/images/help-quick.gif\" width=\"15\" height=\"16\" align=\"absmiddle\" title=\"{Help}\" /></td>\n</tr>\n";
				$TableNoHelp = "<tr class=\"FormOneRow\" valign=\"top\">\n\t<td style=\"padding-top: 6px\"><b>{Caption}</b>{Mandatory}<small>{Help}</small></td>\n\t<td colspan=\"2\">{Element}</td>\n</tr>\n";
				$IsMandatory = "<span style=\"color: red\">*</span>";
				$TableSubmit = "<tr class=\"FormSubmit\">\n\t<td align=\"center\" colspan=\"2\"><br /><input name=\"SubmitButton\" class=\"SubmitButton\" type=\"submit\" value=\"Submit &gt;&gt;\" /></td>\n</tr>\n";
				$CAPTCHALine = "<tr class=\"CAPTCHARow\">\n\t<td colspan=\"2\"><br /><b>Please enter the 6-letter code displayed in this image:</b><br />{CAPTCHA}</td>\n</tr>\n";
				$NoGroupHead = "<tr class=\"FormSpacer\">\n\t<td colspan=\"2\">&nbsp;</td>\n</tr>\n";
				$GroupSpacer = "<tr class=\"FormSpacer\">\n\t<td colspan=\"2\">&nbsp;</td>\n</tr>\n";
				$GroupFooter = "";
				$TableFooter = "</table>\n";
				$CaptionLine = "<tr class=\"CaptionRow\">\n\t<td colspan=\"2\">{Element}</td>\n</tr>\n";
				break;
	case '5':	switch (substr ($StyleTemplate, strrpos ($StyleTemplate, ".") + 1)) {
					case 'tpl':	$Template = ImportFile (ROOT_FILES."/pagebuilder/components/ensight/templates/".$StyleTemplate);
								if (($Repeater = RetrieveTemplateRepeater ($Template, "<repeater>", "</repeater>")) && ($Repeater != '')) {
									$TableHeader = "<div class=\"FormOutline\" id=\"".$FormIdent."Struct\">\n".RetrieveTemplateRepeater ($Repeater, "<tableheader>", "</tableheader>")."\n</div>";
									$GroupHeader = RetrieveTemplateRepeater ($Repeater, "<groupheader>", "</groupheader>");
									$TableOneRow = RetrieveTemplateRepeater ($Repeater, "<row>", "</row>");
									$TableNoHelp = RetrieveTemplateRepeater ($Repeater, "<rownohelp>", "</rownohelp>");
									$IsMandatory = RetrieveTemplateRepeater ($Repeater, "<mandatory>", "</mandatory>");
									$TableSubmit = RetrieveTemplateRepeater ($Repeater, "<submit>", "</submit>");
									$CAPTCHALine = RetrieveTemplateRepeater ($Repeater, "<captcha>", "</captcha>");
									$NoGroupHead = RetrieveTemplateRepeater ($Repeater, "<nogrouphead>", "</nogrouphead>");
									$GroupSpacer = RetrieveTemplateRepeater ($Repeater, "<groupspacer>", "</groupspacer>");
									$GroupFooter = RetrieveTemplateRepeater ($Repeater, "<groupfooter>", "</groupfooter>");
									$TableFooter = RetrieveTemplateRepeater ($Repeater, "<tablefooter>", "</tablefooter>");
									$CaptionLine = RetrieveTemplateRepeater ($Repeater, "<caption>", "</caption>");
									$Template = "";
								} else {
									$TableHeader = "";
									$GroupHeader = "";
									$TableOneRow = "";
									$TableNoHelp = "";
									$IsMandatory = "<span style=\"color: red\">*</span>";
									$TableSubmit = "";
									$CAPTCHALine = "";
									$NoGroupHead = "";
									$GroupSpacer = "";
									$GroupFooter = "";
									$TableFooter = "";
									$CaptionLine = "";
									$Template = "<div class=\"FormOutline\" id=\"".$FormIdent."Struct\">\n".$Template."\n</div>";
								}
								break;
					case 'css':	$TableHeader = "<div class=\"FormOutline\" id=\"".$FormIdent."Struct\">\n";
								$GroupHeader = "<div class=\"FormHeader\">\n{Group}\n</div>\n";
								$TableOneRow = "<div class=\"FormOneRow\">\n<div class=\"FormCaption\" id=\"{ID}_caption\">{Caption}</div>\n<div class=\"FormMandatory\" id=\"{ID}_mandatory\">{Mandatory}</div>\n<div class=\"FormHelp\" id=\"{ID}_help\">{Help}</div>\n<div class=\"FormElement\">{Element}</div>\n</div>\n";
								$TableNoHelp = "<div class=\"FormOneRow\">\n<div class=\"FormCaption\" id=\"{ID}_caption\">{Caption}</div>\n<div class=\"FormMandatory\" id=\"{ID}_mandatory\">{Mandatory}</div>\n<div class=\"FormElement\">{Element}</div>\n</div>\n";
								$IsMandatory = "<span style=\"color: red\">*</span>";
								$TableSubmit = "<div class=\"FormSubmit\">\n<input name=\"SubmitButton\" class=\"SubmitButton\" type=\"submit\" value=\"Submit &gt;&gt;\" />\n</div>\n";
								$CAPTCHALine = "<div class=\"CAPTCHARow\">\n{CAPTCHA}\n</div>\n";
								$NoGroupHead = "";
								$GroupSpacer = "";
								$GroupFooter = "";
								$CaptionLine = "<div class=\"CaptionRow\">\n{Element}\n</div>\n";
								$TableFooter = "</div>\n";
								echo "<link rel=\"stylesheet\" href=\"".($IncludePath ? (defined ("ContentDeliveryURL") ? ContentDeliveryURL : ThisURL) : '').ROOT_URL."/pagebuilder/components/ensight/templates/".$StyleTemplate."\" type=\"text/css\" />\n";
								break;
					default:	$Template = "<div class=\"FormOutline\" id=\"".$FormIdent."Struct\">\n".$Tag['content']."\n</div>";
								$TableHeader = "";
								$GroupHeader = "";
								$TableOneRow = "";
								$TableNoHelp = "";
								$IsMandatory = "<span style=\"color: red\">*</span>";
								$TableSubmit = "";
								$CAPTCHALine = "";
								$NoGroupHead = "";
								$GroupSpacer = "";
								$GroupFooter = "";
								$TableFooter = "";
								$CaptionLine = "";
								break;
				}
				break;
}

if (($ShowSection == 'complete') || ($ShowSection == 'top')) {
?>
<link rel="stylesheet" href="<? echo ($IncludePath ? (defined ("ContentDeliveryURL") ? (($Tag['secure_urls']) || ($_SERVER['HTTPS']) ? str_replace ('http:', 'https:', ContentDeliveryURL) : ContentDeliveryURL) : ThisURL) : '').ROOT_URL; ?>/pagebuilder/components/ensight/form_display.css" type="text/css" media="screen" />
<form id="<? echo $FormIdent; ?>" name="<? echo $FormIdent; ?>" enctype="multipart/form-data" action="<? echo ($IncludePath ? ThisURL : "").ROOT_URL."/"; ?>FPM.php" method="post" onsubmit="var isOkay = __ValidateForm (this); if ((isOkay) && (this.elements['SubmitButton'])) { this.elements['SubmitButton'].disabled = true; } return isOkay"<? echo ($ThankYouMsg ? " target=\"".$FormIdent."Frame\"" : ""); ?>>

<script src="<? echo ($IncludePath ? (defined ("ContentDeliveryURL") ? (($Tag['secure_urls']) || ($_SERVER['HTTPS']) ? str_replace ('http:', 'https:', ContentDeliveryURL) : ContentDeliveryURL) : ThisURL) : '').ROOT_URL; ?>/pagebuilder/components/ensight/form_display.js.php<? echo ($IncludePath ? '?3457246782' : "?Session_ID=".$Session_ID); ?>" type="text/javascript"></script>
<?
	//--- Error
	switch ($Error_NUM) {
		case  2:	echo "<p><a name=\"Error\"><b>Your session has timed out, please try again.</b></a></p>\n"; break;
		case 12:	echo "<p><a name=\"Error\"><b>Please complete all fields marked with an asterisk (".$IsMandatory.")</b></a></p>\n"; break;
		case 27:	echo "<p><a name=\"Error\"><b>The 6-letter code you entered was incorrect, please try again.</b></a></p>\n"; break;
		default:	if (is_numeric ($Error_NUM)) {
						echo "<p><a name=\"Error\"><b>".RetrieveError ($Error_NUM)."</b></a></p>\n";
					}
					break;
	}

} // end if

$XQuery = RetrieveFormFields ($_Item_ID, FORM_PROFILE, ($Group != '*' ? $Group : None), FIELD_VISIBLE, ORDER_BY_GROUP, None, None, None);

$ThisGroup = false;

while ($Result = ReadFromDB ($XQuery)) {

	if ($ThisGroup === false) {
		echo ($Result['FieldGroup_STRING'] == '' ? $TableHeader : $TableHeader.str_replace ("{Group}", $Result['FieldGroup_STRING'], $GroupHeader));
		$ThisGroup = $Result['FieldGroup_STRING'];
	} else
	if (($Group == '*') && ($Result['FieldGroup_STRING'] != $ThisGroup)) {
		echo $GroupFooter;
		echo $GroupSpacer;
		echo ($Result['FieldGroup_STRING'] == '' ? $NoGroupHead : str_replace ("{Group}", $Result['FieldGroup_STRING'], $GroupHeader));
		$ThisGroup = $Result['FieldGroup_STRING'];
	}

	$Element = RetrieveNextFormProfileElement ($Result, $T_Profile_ID, $Reference_ID);
	if ((defined ("EnableProfiling2_0")) || ($IncludePath)) {
		$Element = str_replace ('Form'.$Result['Form_ID'].'-'.$T_Profile_ID, $Result['FieldIdentifier_STRING'], $Element);
	}

	switch ($Result['FieldType_CHAR']) {
		case 'G':	$Element = PrintInColumns ($Element, $Result['FieldSize_NUM']); break;
		case 'R':	$Element = PrintInColumns ($Element, $Result['FieldSize_NUM']); break;
		case 'C':	if ((defined ("DeprecateFormFields")) && ((defined ("EnableProfiling2_0")) || ($IncludePath))) {
						$Element = "<input type=\"checkbox\" name=\"".$Result['FieldIdentifier_STRING']."\" id=\"".$Result['FieldIdentifier_STRING']."\"".($Result['FieldDefaultValue_STRING'] ? " checked=\"checked\"" : "")." value=\"1\" />".($Result['FieldSize_NUM'] != '' ? "<label for=\"".$Result['FieldIdentifier_STRING']."\">".$Result['FieldSize_NUM']."</label>" : "")."\n<input type=\"hidden\" name=\"".$Result['FieldIdentifier_STRING']."_isset\" value=\"1\" />";
					}
					break;
	}

	//--- Add validation
	switch ($Result['FieldValidationType_NUM']) {
		case '1':	$Element = InsertFieldScript ($Element, EVENT_ONKEYPRESS, "return __ValidateField (event, this.value, '1');"); break; //--- Numbers only
		case '2':	$Element = InsertFieldScript ($Element, EVENT_ONKEYPRESS, "return __ValidateField (event, this.value, '2');"); break; //--- Decimal numbers only
		case '3':	$Element = InsertFieldScript ($Element, EVENT_ONKEYPRESS, "return __ValidateField (event, this.value, '3');"); break; //--- Letters only
		case '4':	$Element = InsertFieldScript ($Element, EVENT_ONKEYPRESS, "return __ValidateField (event, this.value, '4');"); break; //--- Numbers and letters only
	}

	$TLine = (($Result['FieldType_CHAR'] == '2') || ($Result['FieldType_CHAR'] == 'C') ? $CaptionLine : ($Result['FieldHelp_STRING'] ? $TableOneRow : $TableNoHelp));
	if (!$Template) {
		$TLine = str_replace ("{Element}", $Element, $TLine);
		$TLine = str_replace ("{Caption}", $Result['FieldName_STRING'], $TLine);
		$TLine = str_replace ("{Mandatory}", ($Result['FieldMandatory_NUM'] ? $IsMandatory : ""), $TLine);
		$TLine = str_replace ("{Help}", ($Result['FieldHelp_STRING'] ? '<br />'.$Result['FieldHelp_STRING'] : ''), $TLine);
		$TLine = str_replace ("{ID}", $Result['FieldIdentifier_STRING'], $TLine);
		echo $TLine;
	} else {
		$Template = str_replace ("{".($Result['FieldGroup_STRING'] != '' ? $Result['FieldGroup_STRING']."/" : "").$Result['FieldName_STRING']."}", $Element, $Template); // old method
		$Template = str_replace ("#(".$Result['FieldIdentifier_STRING'].").element", $Element, $Template); // new method
		$Template = str_replace ("#(".$Result['FieldIdentifier_STRING'].").caption", $Result['FieldName_STRING'], $Template);
		$Template = str_replace ("#(".$Result['FieldIdentifier_STRING'].").mandatory", ($Result['FieldMandatory_NUM'] ? $IsMandatory : ""), $Template);
		$Template = str_replace ("#(".$Result['FieldIdentifier_STRING'].").help", ($Result['FieldHelp_STRING'] ? '<br />'.$Result['FieldHelp_STRING'] : ''), $Template);
	}

	if ((($Result['FieldValidationType_NUM']) || (($Result['FieldMandatory_NUM']) && ($Result['FieldMandatoryError_STRING'])) || (($Result['FieldStatus_NUM'] == STATUS_VISIBLE) && ($Result['FieldSize_NUM'] != '')))) {
		$Validations[] = RetrieveHiddenField ("Validate".((defined ("EnableProfiling2_0")) || ($IncludePath) ? $Result['FieldIdentifier_STRING'] : $Result['Form_ID']."-".$T_Profile_ID), $Result['FieldValidationType_NUM']."|".str_replace ("\"", "&quot;", str_replace ("\n", "&#10;", str_replace ("\r", "&#13;", ($Result['FieldMandatory_NUM'] ? $Result['FieldMandatoryError_STRING'] : ''))))."|".$Result['FieldSize_NUM']);
	}

} // end while

if ($Template) {
	$TProfile = RetrieveProfileDetails ($T_Profile_ID);
	$Template = str_replace ("{*form_id*}", $FormIdent, $Template);
	$Template = str_replace ("{*session*}", $Session_ID, $Template);
	$Template = str_replace ("{*profile*}", $T_Profile_ID, $Template);
	$Template = str_replace ("{*login*}", $TProfile['Login_STRING'], $Template);
	$Template = str_replace ("{*username*}", $TProfile['UserName_STRING'], $Template);
	$Template = str_replace ("{*form*}", $FormIdent, $Template);
	$Template = str_replace ("{*captcha*}", (($CAPTCHATest == 'yes') || (defined ("EnableCAPTCHA")) ? "<img id=\"CAPTCHA_Image\" src=\"".ROOT_URL."/pagebuilder/components/ensight/load-captcha.php?Key=".$_Item_ID."&Session_ID=".$Session_ID."&Rand=".GetRandom (999999)."\" width=\"150\" height=\"70\" border=\"1\" alt=\"\" /><br /><a href=\"#\" onclick=\"document.getElementById ('CAPTCHA_Image').src = '".ROOT_URL."/pagebuilder/components/ensight/load-captcha.php?Key=".$_Item_ID."&Session_ID=".$Session_ID."&Rand=' + (Math.random () * 999999); return false\"><small>Generate another random image</small></a><br /><input type=\"text\" name=\"CAPTCHA\" style=\"width: 150px\" />".(EnableCAPTCHA != '1' ? "<input type=\"hidden\" name=\"v\" value=\"1\" />" : "") : ""), $Template);
	echo $Template;
} else
if (($CAPTCHATest == 'yes') || (EnableCAPTCHA == '1')) {
	echo str_replace ("{CAPTCHA}", "<img id=\"CAPTCHA_Image\" src=\"".ROOT_URL."/pagebuilder/components/ensight/load-captcha.php?Key=".$_Item_ID."&Session_ID=".$Session_ID."&Rand=".GetRandom (999999)."\" width=\"150\" height=\"70\" border=\"1\" alt=\"\" /><br /><a href=\"#\" onclick=\"document.getElementById ('CAPTCHA_Image').src = '".ROOT_URL."/pagebuilder/components/ensight/load-captcha.php?Key=".$_Item_ID."&Session_ID=".$Session_ID."&Rand=' + (Math.random () * 999999); return false\"><small>Generate another random image</small></a><br /><input type=\"text\" name=\"CAPTCHA\" style=\"width: 150px\" />".(EnableCAPTCHA != '1' ? "<input type=\"hidden\" name=\"v\" value=\"1\" />" : ""), $CAPTCHALine);
}

if (($ShowSection == 'complete') || ($ShowSection == 'bottom')) {

	echo ($TableSubmit);
	echo ($ThisGroup == '' ? $TableFooter : $GroupFooter.$TableFooter);

	//--- Required fields
	echo RetrieveHiddenField ("i", $_Item_ID);
	echo RetrieveHiddenField ("r", $Reference_ID);

	if ($CAPTCHATest == 'yes') {
		echo RetrieveHiddenField ("v", '1');
	}

	//--- Set source and destination paths
	if ($IncludePath) {
		echo RetrieveHiddenField ("prev", "");
		echo RetrieveHiddenField ("next", $Dest_URL);
	} else {
		echo RetrieveHiddenField ("prev", preg_replace ("/(\?|&)Item_ID=/", "$1DestItem_ID=", (substr ($_SERVER['PHP_SELF'], 0, strlen (ROOT_URL)) != ROOT_URL ? $_SERVER['PHP_SELF'] : $_SERVER['PHP_SELF'].($_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : ''))));
		echo RetrieveHiddenField ("next", $Dest_URL);
	}

	if ($ThankYouMsg) {
		echo RetrieveHiddenField ("callback", "__ShowThanks");
		echo RetrieveHiddenField ("format", "iframe");
	}

	//--- User profile identifier
	echo (($Session_ID) && (!$IncludePath) ? RetrieveHiddenField ("s", $Session_ID) : "");
	echo (($T_Profile_ID != $Profile_ID) || ($IncludePath) ? RetrieveHiddenField ("p", ($IncludePath ? false : $T_Profile_ID)) : "");

	echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"".MaxSizeOfUploadedFiles."\" />\n";

	if (is_array ($Validations)) {
		echo implode ("", $Validations);
	}

	echo "</form>\n";
	echo "<script>try { __InsertMsgReferer ('".$FormIdent."'); } catch (e) {}\n</script>\n";

} else {

	echo ($ThisGroup == '' ? $TableFooter : $GroupFooter);

}

if ($ThankYouMsg) {
	echo "<div style=\"display: none\" class=\"FormThanks\" id=\"".$FormIdent."Thanks\" src=\"about:blank\"><span>".str_replace ("&#13;", "<br />\n", urldecode ($ThankYouMsg))."</span>".($ThankYouShare == 'yes' ? "<p><!-- AddThis Button BEGIN --><div class=\"addthis_toolbox addthis_default_style\"><a class=\"addthis_button_facebook_like\" fb:like:layout=\"button_count\"></a><a class=\"addthis_button_tweet\"></a><a class=\"addthis_button_google_plusone\" g:plusone:size=\"medium\"></a></div><script type=\"text/javascript\" src=\"//s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4fa18868640e923b\"></script><!-- AddThis Button END --></p>" : "")."</div>";
	echo "<iframe style=\"display: none\" name=\"".$FormIdent."Frame\" id=\"".$FormIdent."Frame\" src=\"about:blank\"></iframe>";
}
?>