<?
//--- ENVENT Source Code (ESC)
//--- First Created on Thursday, May 2 2002 by John Ginsberg
//--- For ENSIGHT

//--- Module Name: SMM-target-select.php
//--- Contains: None

//--- Modified by:
//--- Modified by:

//--- Description:
//--- Selects criteria for message targeting

//--- Permissions:
//--- ALLOW_USER_MGT

//----------------------------------------------------------------

if (!defined ("DB_INCLUDED"))           { include_once ("../modules/DB.php"); }
if (!defined ("CONNECT_INCLUDED"))      { include_once ("../modules/connect.php"); }
if (!defined ("UTILS_INCLUDED"))        { include_once ("../modules/utils.php"); }
if (!defined ("DATE_INCLUDED"))         { include_once ("../modules/date.php"); }
if (!defined ("CATALOG_INCLUDED"))      { include_once ("../modules/catalog.php"); }
if (!defined ("PROFILE_INCLUDED"))      { include_once ("../modules/profile.php"); }
if (!defined ("FORM_INCLUDED"))         { include_once ("../modules/form.php"); }
if (!defined ("SESSION_INCLUDED"))      { include_once ("../modules/session.php"); }
if (!defined ("RULES_INCLUDED"))        { include_once ("../modules/rules.php"); }
if (!defined ("MEDIA_INCLUDED"))        { include_once ("../modules/media.php"); }

//--- Get the user's session ID
if (($Session_ID) && (!$Profile_ID)) {
        $Profile_ID = LocateSession ($Session_ID);
}

if (!$Profile_ID) {
        Redirect ("index.php?Dest_URL=main.html?Message=".urlencode ("You have been logged out. Please login again to continue using this system"));
}

//--- Check permissions
$Allowed = CheckPermission ($Profile_ID, GROUP_DEFAULT, None, ALLOW_MEDIA_MGT, ACCESS_ON);
if (!$Allowed) {
        Redirect ("access-denied.php?Session_ID=$Session_ID&Src_URL=".GetVar ("HTTP_REFERER"));
}

$Text = RetrieveText ($Text_ID);
$TargetBy = RetrieveTextTargetingCriteria ($Text_ID);

$SelectedSegments = array ();

if ($TargetBy == 'filter') {
        $Filter = RetrieveTextTargetFilter ($Text_ID);
} else
if ($TargetBy == 'segment') {
        $Segments = RetrieveTextTargetSegments ($Text_ID);
        while ($Segment = ReadFromDB ($Segments)) {
                $SelectedSegments[] = $Segment['Rule_ID'];
        }
        $DataSource = RetrieveTextTargetSegmentDataSource ($Text_ID);
        if ($DataSource == '') {
                $DataSource = USER_PROFILE;
        }
}

$Disabled = ($Text['CurrentStatus_NUM'] > MESSAGE_STATUS_PENDING ? " disabled=\"disabled\"" : "");

//--- Set header custom definitions
$SubTitle = "Targeting Criteria";
$HasFocus = "";
$HeadScript = "";
$LoadScript = "LoadButtons (); LoadTab ()";
$NavBar = array ("Targeting Criteria", false);
$BodyAttributes = "";

//--- Include display functions
include_once (ADMIN_FILES."/display.php");
include_once (ADMIN_FILES."/header-no-borders.php");
?>

<script src="js/jquery.min.js?988025"></script>
<script src="ibuttons.js"></script>
<script>
function NewWindow (URL, Title, Width, Height, callOnClose) {
//--- Opens a new window with the specifics in the center of the screen

        top.modalWindows.openWindow (URL, Title, (parseInt (Width) + 5), (parseInt (Height) + 40), window, callOnClose);

}

//---------------------------------------------------------
//--- List requests
//---------------------------------------------------------

function XHTTPRequest_Lists (url, handler) {
//--- Send an HTTP request

        if (window.XMLHttpRequest) {

                req2 = new XMLHttpRequest ();
                req2.onreadystatechange = handler;
                req2.open ("GET", url, true);
                req2.send (null);

    } else if (window.ActiveXObject) {

                req2 = new ActiveXObject ("Microsoft.XMLHTTP");
                if (req2) {
                        req2.onreadystatechange = handler;
                        req2.open ("GET", url, true);
                        req2.send ();
        }

    }

}

function ClearList (whichFormElement, loadingMessage) {
//--- Clears a drop-down selection list

        while (whichFormElement[0]) {
                whichFormElement[0] = null;
        }

        //--- Initiate a new list?
        if (loadingMessage) {
                whichFormElement[0] = new Option (loadingMessage, '');
        }

}

function BuildList () {
//--- Loads the most recent posts from the server

        if (req2.readyState == 4) {

                switch (req2.status) {
                        case 200:       //--- Display response text
                                                var hasError = req2.responseXML.getElementsByTagName ("error");
                                                if (hasError.length) {
                                                        alert ('An error occured while downloading options for this form. Please click Back and try again.');
                                                } else {
                                                        var listOptions = req2.responseXML.getElementsByTagName ("list")[0].getElementsByTagName ("option");
                                                        var listElement = req2.responseXML.getElementsByTagName ("list")[0].getAttribute ("for");
                                                        var whichFormElement = document.forms[0].elements[listElement];
                                                }
                                                if (listOptions.length) {
                                                        isSelected = false;
                                                        for (i = 0; i < listOptions.length; i++) {
                                                                optionTxt = (listOptions[i].firstChild ? listOptions[i].firstChild.nodeValue.toString () : '');
                                                                optionVal = (listOptions[i].getAttribute ('value'));
                                                                optionSel = (listOptions[i].getAttribute ('selected'));
                                                                if (optionSel == 'selected') { isSelected = true; }
                                                                whichFormElement[i] = new Option (optionTxt, optionVal, false, (optionSel == 'selected'));
                                                        }
                                                        if (isSelected) {
                                                                whichFormElement.onchange ();
                                                        }
                                                }
                                                break;
                        default:        break;
                }

        }

}

//---------------------------------------------------------
//--- Count requests
//---------------------------------------------------------

function XHTTPRequest_Count (url, handler) {
//--- Send an HTTP request

        if (window.XMLHttpRequest) {

                req3 = new XMLHttpRequest ();
                req3.onreadystatechange = handler;
                req3.open ("GET", url, true);
                req3.send (null);

    } else if (window.ActiveXObject) {

                req3 = new ActiveXObject ("Microsoft.XMLHTTP");
                if (req3) {
                        req3.onreadystatechange = handler;
                        req3.open ("GET", url, true);
                        req3.send ();
        }

    }

}

function DisplayCount () {
//--- Displays a count of message recipients

        if (req3.readyState == 4) {

                switch (req3.status) {
                        case 200:       //--- Display response text
                                                var hasError = req3.responseXML.getElementsByTagName ("error");
                                                if (hasError.length) {
                                                        alert ('An error occured while retrieving a recipient count. Please click Back and try again.');
                                                } else {
                                                        var target_by = req3.responseXML.getElementsByTagName ("count")[0].getAttribute ("target_by");
                                                        var unsent = req3.responseXML.getElementsByTagName ("count")[0].getElementsByTagName ("unsent")[0].firstChild.nodeValue.toString ();
                                                        var recipients = req3.responseXML.getElementsByTagName ("count")[0].getElementsByTagName ("recipients")[0].firstChild.nodeValue.toString ();
                                                        var unique_profiles = req3.responseXML.getElementsByTagName ("count")[0].getElementsByTagName ("unique_profiles")[0].firstChild.nodeValue.toString ();
                                                        var no_address = req3.responseXML.getElementsByTagName ("count")[0].getElementsByTagName ("no_address")[0].firstChild.nodeValue.toString ();
                                                }
                                                if (recipients) {
                                                        switch (target_by) {
                                                                case 'filter':  document.getElementById ('countResultsFilter').innerHTML = '<a href=\"#\" onclick=\"alert (\'Excludes unsubscribed and suppressed entries\'); return false\"><img src=\"images/help-quick.gif\" border=\"0\" align=\"right\" /></a><b>You are currently targeting a total of:</b> <br />' + recipients + " recipient(s)";
                                                                                                <? if ((!$ReadOnly) && (!$Disabled)) { ?>document.getElementById ('SaveFilter').disabled = false;<? } ?>
                                                                                                if (no_address == '1') {
                                                                                                        alert ('The form you are targeting has no Mobile Telephone type field, which will prevent this message from sending out properly.\n\nThis may also occur if you have a field for collecting mobile numbers that is flagged as a Text Box instead of Mobile Telephone.\n\nPlease select another form, or click \'Jump to Item\' to modify the targeted form.');
                                                                                                        document.getElementById ('SaveFilter').disabled = true;
                                                                                                }
                                                                                                break;
                                                                case 'segment': document.getElementById ('countResultsSegment').innerHTML = '<b>You are currently targeting a total of:</b><br />' + recipients + " recipient(s)";
                                                                                                <? if ((!$ReadOnly) && (!$Disabled)) { ?>document.getElementById ('SaveSegment').disabled = false;<? } ?>
                                                                                                if (no_address == '1') {
                                                                                                        alert ('The data source you are targeting has no Mobile Telephone type field, which will prevent this message from sending out properly.\n\nThis may also occur if you have a field for collecting mobile numbers that is flagged as a Text Box instead of Mobile Telephone.\n\nPlease select another form.');
                                                                                                        document.getElementById ('SaveSegment').disabled = true;
                                                                                                }
                                                                                                break;
                                                        }
                                                }
                                                break;
                        default:        break;
                }

        }

}

function LoadButtons () {
//--- Loads the button bar

        document.getElementById ('buttons').innerHTML = RetrieveMenuBar ();

}

function expandWindow () {
//--- Expands the window for segments

        if (LastTab != 'S') {
                top.modalWindows.expandWindow (0, (navigator.userAgent.toLowerCase ().indexOf ('chrome') != -1 ? 0 + 110 : 0 + 105), false); LastTab = 'S';
        }

}

function contractWindow () {
//--- Contracts the window for filters

        if (LastTab != 'F') {
                top.modalWindows.expandWindow (0, (navigator.userAgent.toLowerCase ().indexOf ('chrome') != -1 ? 0 - 110 : 0 - 105), false); LastTab = 'F';
        }

}

function SaveMultiSelect (Field) {
//--- Implodes a multi-select field as a comma-delimited set of values

        TValue = '';

        for (i = 0; i < Field.options.length; i++) {
                if (Field.options[i].selected) {
                        TValue += Field.options[i].value + ',';
                }
        }

        return (TValue ? TValue.substr (0, TValue.length - 1) : '');

}

function loadFilters (Item_ID, Reload) {
//--- Loads or reloads the filter drop down list

        //--- Save last selection (or selected filter)
        lastFilterSelected = ((typeof (top.modalWindows.windowArguments) == 'object') && (Reload) && (top.modalWindows.windowArguments[0] > 0) ? top.modalWindows.windowArguments[0] : lastFilter[0].options[lastFilter[0].options.selectedIndex].value);
        //--- Clear the filter
        while (lastFilter[0].options[0]) {
                lastFilter[0].options[0] = null;
        }
        var request = $.ajax ({
                url: 'EMM-target-select-list-xml.php?Profile_ID=<? echo $Profile_ID; ?>&Select=' + lastFilterSelected + '&ReturnList=filters&Field=' + lastFilter[0].name + '&Item_ID=' + Item_ID + '&Session_ID=<? echo $Session_ID; ?>',
                complete: function (jqXHR, textStatus) {
                        var listOptions = jqXHR.responseXML.getElementsByTagName ("list")[0].getElementsByTagName ("option");
                        var listElement = jqXHR.responseXML.getElementsByTagName ("list")[0].getAttribute ("for");
                        var whichFormElement = document.forms[0].elements[listElement];
                        if (listOptions.length) {
                                for (i = 0; i < listOptions.length; i++) {
                                        optionTxt = (listOptions[i].firstChild ? listOptions[i].firstChild.nodeValue.toString () : '');
                                        optionVal = (listOptions[i].getAttribute ('value'));
                                        optionSel = (listOptions[i].getAttribute ('selected'));
                                        isSelected = (lastFilterSelected ? (optionVal == lastFilterSelected) : (optionSel == 'selected'));
                                        whichFormElement[i] = new Option (optionTxt, optionVal, false, isSelected);
                                }
                                whichFormElement.onchange ();
                        }
                }
        });

}

var lastFilter = null;
var lastFilterSelected = null;

<?
switch ($LoadAs) {
        case 'F':       echo "CurrentTab = 0;\n"; break;
        case 'S':       echo "CurrentTab = 1;\n"; break;
}
?>
LastTab = 'F'; // default to 'F' so that only 'S' triggers
buttonImages[0] = CreateButton ('images/icon_target_form.gif', 'images/icon_target_form_selected.gif', 'Target to people who have completed a form', 24, 23, 'contractWindow');
buttonImages[1] = CreateButton ('images/icon_target_segment.gif', 'images/icon_target_segment_selected.gif', 'Target to people by segment', 24, 23, 'expandWindow');

top.modalWindows.attachKeyHandler (document); // assign ESCape key
top.modalWindows.switchFocus (self); // focus on self
</script>

<form action="SMM-target-complete.php" method="post" onsubmit="if (this.SaveAs.value == 'S') { this.Rule_ID.value = SaveMultiSelect (this.RulesList); }">

<table border="0" cellpadding="5" cellspacing="1" bgcolor="#F9F8F7" width="100%" height="100%" cols="2">
<tr valign="top">
        <td bgcolor="#DBD8D1" width=30 align="center">
        <span id="buttons"></span>
        </td>
        <td>
        <div id="tab0" style="visibility: hidden; position: absolute">

        <!-- Form filter -->
        <table border="0" cellpadding="0" cellspacing="0" class="FormBox" width="100%">
        <tr>
                <td class="FormTxt" width="105" height="27"><b>Select a Form:</b>&nbsp;</td>
                <td style="padding-top: 6px"><input type="text" name="ItemCode"<? echo $Disabled; ?> size="27" value="<? echo ($Filter ? RetrieveCatalogItemCode ($Filter['Item_ID']) : ""); ?>" readonly="readonly" onchange="if (this.value != '') { document.getElementById ('SaveFilter').disabled = true; ClearList (this.form.ViewDefinition, 'Loading...'); XHTTPRequest_Lists ('SMM-target-select-list-xml.php?Profile_ID=<? echo $Profile_ID; ?>&Select=' + this.form.ViewDefinition_saved.value + '&ReturnList=filters&Field=ViewDefinition&Item_ID=' + this.form.Item_ID.value, BuildList); document.getElementById ('countResultsFilter').innerHTML = '<img src=\'images/indicator.gif\' width=\'16\' height=\'16\' border=\'0\' align=\'absmiddle\' /> Calculating... '; XHTTPRequest_Count ('SMM-target-count-xml.php?Profile_ID=<? echo $Profile_ID; ?>&Text_ID=<? echo $Text_ID; ?>&ReturnAs=target&RunTestOn=filter&Item_ID=' + this.form.Item_ID.value, DisplayCount); } else { document.getElementById ('SaveFilter').disabled = true; ClearList (this.form.ViewDefinition, '...'); }" />&nbsp;<input type="button" value="Browse" onclick="NewWindow ('IMM-select.php?Session_ID=<? echo $Session_ID; ?>&Item_ID=' + this.form.Item_ID.value + '&Category_ID=<? echo RetrieveCatalogCategoryByItemID ($Filter['Item_ID']); ?>&Type=<? echo CATALOG_CONTENT.",".CATALOG_FORM; ?>&ShowUserProfile=1&ItemField=Item_ID&CodeField=ItemCode&ChangeField=ItemCode', 'Select an Item', 325, 315, function () {}); if (this.form.Item_ID.value != '') { document.getElementById ('JumpToButton').style.visibility = 'visible'; } return false" /><input type="hidden" name="Item_ID" value="<? echo $Filter['Item_ID']; ?>" /><span id="JumpToButton" style="<? echo (isset ($Filter['Item_ID']) ? "" : "visibility: hidden"); ?>">&nbsp;&nbsp;<input type="button" value="Jump To Item" onclick="top.Main.document.location = 'IMM-update.php?Item_ID=' + document.forms[0].elements['Item_ID'].value + '&Session_ID=<? echo $Session_ID; ?>'; top.modalWindows.closeWindow (); return false" /></span></td>
        </tr>
        <tr valign="top">
                <td class="FormTxt" style="padding-top: 8px" height="27"><b>Filter By:</b>&nbsp;</td>
                <td>

                <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                        <td><select name="ViewDefinition" id="ViewDefinition" style="width: 315px"<? echo $Disabled; ?> onchange="document.getElementById ('SaveFilter').disabled = true; document.getElementById ('countResultsFilter').innerHTML = '<img src=\'images/indicator.gif\' width=\'16\' height=\'16\' border=\'0\' align=\'absmiddle\' /> Calculating... '; XHTTPRequest_Count ('SMM-target-count-xml.php?Profile_ID=<? echo $Profile_ID; ?>&Text_ID=<? echo $Text_ID; ?>&ReturnAs=target&RunTestOn=filter&Item_ID=' + this.form.Item_ID.value + '&ViewDefinition=' + this.form.ViewDefinition.options[this.form.ViewDefinition.selectedIndex].value, DisplayCount)"><option value="0">-- No filter --</option></select>&nbsp;</td>
                        <td><a href="#" onclick="if (document.forms[0].elements['Item_ID'].value) { lastFilter = $('#ViewDefinition'); NewWindow ('FMM-filter.php?Item_ID=' + document.forms[0].elements['Item_ID'].value + '&Session_ID=<? echo $Session_ID; ?>', 'Create or Modify Filters', 900, 500, function () { loadFilters (document.forms[0].elements['Item_ID'].value, true); }); } else { alert ('Please select an item first.'); } return false;"><img src="images/filter.png" width="16" height="16" align="absmiddle" border="0" /></a>&nbsp;</td>
                        <td style="padding-top: 2px"><a href="#" onclick="if (document.forms[0].elements['Item_ID'].value) { lastFilter = $('#ViewDefinition'); NewWindow ('FMM-filter.php?Item_ID=' + document.forms[0].elements['Item_ID'].value + '&Session_ID=<? echo $Session_ID; ?>', 'Create or Modify Filters', 900, 500, function () { loadFilters (document.forms[0].elements['Item_ID'].value, true); }); } else { alert ('Please select an item first.'); } return false;">Modify filters</a></td>
                </tr>
                </table>

                </td>
        </tr>
        <tr>
                <td style="padding-top: 10px; padding-bottom: 7px" align="right">&nbsp;</td>
                <td style="padding-top: 10px; padding-bottom: 7px">

                <table border="0" cellspacing="5" cellpadding="1" class="customBox" width="350" height="45">
                <tr>
                        <td>
                        <span id="countResultsFilter">This box will display the number of people currently being targeted by your selection.</span>
                        </td>
                </tr>
                </table>

                </td>
        </tr>
        <tr>
                <td class="FormTxt">&nbsp;</td>
                <td><input class="FormButtons" type="submit" id="SaveFilter" value="Save Changes" disabled="disabled" onclick="this.form.SaveAs.value = 'F'" />&nbsp;&nbsp;<input class="FormButtons" type="button" value="Cancel" onclick="top.modalWindows.closeWindow ()" /></td>
        </tr>
        </table>
        <!-- Form filter -->

        </div>

        <div id="tab1" style="visibility: hidden; position: absolute">

        <!-- Select segment -->
        <table border="0" cellpadding="0" cellspacing="0" class="FormBox" width="100%">
        <tr>
                <td class="FormTxt" width="105" height="27"><b>Select a Group:</b>&nbsp;</td>
                <td>

                <?
                if (!$ReadOnly) {
                ?>
                <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                        <td>
                        <?
                        $SegmentGroups = RetrieveProfileSegmentGroupsWithPermission ($Profile_ID, ORDER_BY_PREDEF, ORDER_ASC, None, None);
                        ?>
                        <select name="RuleGroup_ID"<? echo $Disabled; ?> onchange="if (this.options[this.selectedIndex].value != '') { document.getElementById ('SaveSegment').disabled = true; document.getElementById ('countResultsSegment').innerHTML = 'This box will display the number of people currently being targeted by your selection.'; ClearList (this.form.RulesList, 'Loading...'); XHTTPRequest_Lists ('SMM-target-select-list-xml.php?Profile_ID=<? echo $Profile_ID; ?>&Select=' + this.form.Rule_ID_saved.value + '&ReturnList=segments&Field=RulesList&RuleGroup_ID=' + this.options[this.selectedIndex].value, BuildList); } else { document.getElementById ('SaveSegment').disabled = true; ClearList (this.form.RulesList, '...'); }">
                        <option value="">-- Select a segmentation group --</option>
                        <?
                        for ($i = 0; $i < count ($SegmentGroups); $i++) {
                                echo RetrieveDropDownOption ($SegmentGroups[$i]['RuleGroup_ID'], RetrieveSegmentGroupByRuleID ($SelectedSegments[0]), $SegmentGroups[$i]['RuleGroupName_STRING']);
                        }
                        ?>
                        </select>&nbsp;
                        </td>
                        <td><a href="#" onclick="top.Main.document.location = (document.forms[0].elements['RuleGroup_ID'].options[document.forms[0].elements['RuleGroup_ID'].selectedIndex].value ? 'ZMM-segment-rules.php?RuleGroup_ID=' + document.forms[0].elements['RuleGroup_ID'].options[document.forms[0].elements['RuleGroup_ID'].selectedIndex].value + '&Session_ID=<? echo $Session_ID; ?>' : 'ZMM-segment-groups.php?Session_ID=<? echo $Session_ID; ?>'); top.modalWindows.closeWindow (); return false"><img src="images/fff_segment.gif" width="16" height="16" align="absmiddle" border="0" /></a>&nbsp;</td>
                        <td style="padding-top: 4px"><a href="#" onclick="top.Main.document.location = (document.forms[0].elements['RuleGroup_ID'].options[document.forms[0].elements['RuleGroup_ID'].selectedIndex].value ? 'ZMM-segment-rules.php?RuleGroup_ID=' + document.forms[0].elements['RuleGroup_ID'].options[document.forms[0].elements['RuleGroup_ID'].selectedIndex].value + '&Session_ID=<? echo $Session_ID; ?>' : 'ZMM-segment-groups.php?Session_ID=<? echo $Session_ID; ?>'); top.modalWindows.closeWindow (); return false">Modify Segments in Group</a></td>
                </tr>
                </table>
                <?
                } else {
                ?>
                <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                        <td>
                        <?
                        $SegmentName = RetrieveSegmentGroupName (RetrieveSegmentGroupByRuleID ($SelectedSegments[0]));
                        ?>
                        <select name="RuleGroup_ID" disabled="disabled">
                        <option value=""><? echo $SegmentName; ?></option>
                        </select>&nbsp;
                        </td>
                        <td><a href="#" onclick="top.Main.document.location = (document.forms[0].elements['RuleGroup_ID'].options[document.forms[0].elements['RuleGroup_ID'].selectedIndex].value ? 'ZMM-segment-rules.php?RuleGroup_ID=' + document.forms[0].elements['RuleGroup_ID'].options[document.forms[0].elements['RuleGroup_ID'].selectedIndex].value + '&Session_ID=<? echo $Session_ID; ?>' : 'ZMM-segment-groups.php?Session_ID=<? echo $Session_ID; ?>'); top.modalWindows.closeWindow (); return false"><img src="images/fff_segment.gif" width="16" height="16" align="absmiddle" border="0" /></a>&nbsp;</td>
                        <td style="padding-top: 4px"><a href="#" onclick="top.Main.document.location = (document.forms[0].elements['RuleGroup_ID'].options[document.forms[0].elements['RuleGroup_ID'].selectedIndex].value ? 'ZMM-segment-rules.php?RuleGroup_ID=' + document.forms[0].elements['RuleGroup_ID'].options[document.forms[0].elements['RuleGroup_ID'].selectedIndex].value + '&Session_ID=<? echo $Session_ID; ?>' : 'ZMM-segment-groups.php?Session_ID=<? echo $Session_ID; ?>'); top.modalWindows.closeWindow (); return false">Modify Segments in Group</a></td>
                </tr>
                </table>
                <?
                } // end if
                ?>

                </td>
        </tr>
        <tr valign="top">
                <td class="FormTxt" style="padding-top: 8px" height="27"><b>Targeting:</b>&nbsp;</td>
                <td>

                <?
                if (!$ReadOnly) {
                ?>
                <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                        <td>
                        <select name="RulesList"<? echo $Disabled; ?> style="width: 410px" size="5" multiple="multiple" onchange="document.getElementById ('SaveSegment').disabled = true; if (this.selectedIndex != -1) { document.getElementById ('countResultsSegment').innerHTML = '<img src=\'images/indicator.gif\' width=\'16\' height=\'16\' border=\'0\' align=\'absmiddle\' /> Calculating... '; XHTTPRequest_Count ('SMM-target-count-xml.php?Profile_ID=<? echo $Profile_ID; ?>&Text_ID=<? echo $Text_ID; ?>&ReturnAs=target&RunTestOn=segment&Rule_ID=' + SaveMultiSelect (this.form.RulesList) + '&Item_ID=' + this.form.DS_Item_ID.value, DisplayCount); }"><option value="">...</option></select>
                        </td>
                </tr>
                </table>
                <?
                } else {
                ?>
                <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                        <td>
                        <select name="RulesList" disabled="disabled" style="width: 410px">
                        <?
                        for ($i = 0; $i < count ($SelectedSegments); $i++) {
                        ?>
                        <option value=""><? echo RetrieveRuleDescription ($SelectedSegments[$i]); ?></option>
                        <?
                        } // end for
                        ?>
                        </select>
                        </td>
                </tr>
                </table>
                <?
                } // end if
                ?>

                </td>
        </tr>
        <tr>
                <td>&nbsp;</td>
                <td><script>document.write ("<hr size=\"1\" noshade=\"noshade\" color=\"#cccccc\" style=\"width: 410px" + "" + (document.all ? "" : "; margin-top: 8px; margin-bottom: 8px") + "\" align=\"left\" />");</script></td>
        </tr>
        <tr valign="top">
                <td class="FormTxt" height="27">&nbsp;</td>
                <td><b>Data Source:</b> (optional)<br /><input type="text" name="DS_ItemCode"<? echo $Disabled; ?> size="27" value="<? echo RetrieveCatalogItemCode ($DataSource); ?>" readonly="readonly" onchange="if ((this.value != '') && (this.form.RulesList.selectedIndex != -1)) { document.getElementById ('SaveSegment').disabled = true; document.getElementById ('countResultsSegment').innerHTML = '<img src=\'images/indicator.gif\' width=\'16\' height=\'16\' border=\'0\' align=\'absmiddle\' /> Calculating... '; XHTTPRequest_Count ('SMM-target-count-xml.php?Profile_ID=<? echo $Profile_ID; ?>&Text_ID=<? echo $Text_ID; ?>&ReturnAs=target&RunTestOn=segment&Rule_ID=' + SaveMultiSelect (this.form.RulesList) + '&Item_ID=' + this.form.DS_Item_ID.value, DisplayCount); } else { document.getElementById ('SaveSegment').disabled = true; }" />&nbsp;<input type="button" value="Browse"<? echo $Disabled; ?> onclick="NewWindow ('IMM-select.php?Session_ID=<? echo $Session_ID; ?>&Item_ID=' + this.form.DS_Item_ID.value + '&Category_ID=<? echo RetrieveCatalogCategoryByItemID ($DataSource); ?>&Type=<? echo CATALOG_CONTENT.",".CATALOG_FORM; ?>&ShowUserProfile=1&ItemField=DS_Item_ID&CodeField=DS_ItemCode&ChangeField=DS_ItemCode', 'Select an Item', 325, 315, function () {}); return false" /><input type="hidden" name="DS_Item_ID" value="<? echo $DataSource; ?>" />&nbsp;<a href="#" onclick="alert ('The data source tells Ensight where to find the mailing list data (including target address). If not specified, the User Profile (Registration) form is assumed.'); return false"><img src="images/help-quick.gif" width="15" height="16" border="0" title="Click for more information" align="absmiddle" /></a></td>
        </tr>
        <tr>
                <td style="padding-top: 10px; padding-bottom: 7px" align="right">&nbsp;</td>
                <td style="padding-top: 10px; padding-bottom: 7px">

                <table border="0" cellspacing="5" cellpadding="1" class="customBox" width="350" height="45">
                <tr>
                        <td>
                        <span id="countResultsSegment">This box will display the number of people currently being targeted by your selection.</span>
                        </td>
                </tr>
                </table>

                </td>
        </tr>
        <tr>
                <td class="FormTxt">&nbsp;</td>
                <td><input class="FormButtons" type="submit" id="SaveSegment" value="Save Changes" disabled="disabled" onclick="this.form.SaveAs.value = 'S'" />&nbsp;&nbsp;<input class="FormButtons" type="button" value="Cancel" onclick="top.modalWindows.closeWindow ()" /></td>
        </tr>
        </table>
        <!-- Select segment -->

        </div>

        </td>
</tr>
</table>

<?
PrintHiddenField ("ViewDefinition_saved", $Filter['ViewDefinition_NUM']);
PrintHiddenField ("Rule_ID_saved", implode (",", $SelectedSegments));
PrintHiddenField ("Text_ID", $Text_ID);
PrintHiddenField ("Rule_ID", ""); // temp placeholder
PrintHiddenField ("Session_ID", $Session_ID);
PrintHiddenField ("SaveAs", "");

if (($Filter) && (!$ReadOnly)) {
?>
<script>
document.forms[0].ItemCode.onchange ();
</script>
<?
} else
if (($TargetBy == 'segment') && (!$ReadOnly)) {
?>
<script>
document.forms[0].RuleGroup_ID.onchange ();
</script>
<?
}
?>
</form>

<?
//--- Include display functions
include_once (ADMIN_FILES."/footer-simple.php");
?>
