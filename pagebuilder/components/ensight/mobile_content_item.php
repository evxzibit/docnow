<?
include_once ("modules/fusion.php");
include_once ("modules/shortcuts.php");
include_once ("modules/links.php");
include_once ("modules/db-functions.php");

if (!function_exists ("MobileFusionProcessor")) {

function MobileFusionProcessor ($Tag) {
//--- Redefine the Fusion processor for mobile devices

	global $Capabilities;

	if (($Tag['attributes']['file'] != '') && ($Capabilities['display']['max_image_width']) && ($Capabilities['display']['max_image_height'])) {
		//--- Handle images differently
		$Save = stripslashes ($Tag['attributes']['saved-in']);
		if ($Save == '') {
			$SaveDir = CONTENT_FILES;
			$SaveURL = CONTENT_URL;
		} else {
			$SaveDir = DOCUMENT_ROOT.$Save;
			$SaveURL = $Save;
		}
		$Tag['content'] = CreateImageResized ($SaveDir.'/'.$Tag['attributes']['file'], $SaveURL.'/'.$Tag['attributes']['file'], $Capabilities['display']['max_image_width'], $Capabilities['display']['max_image_height'], $Tag['attributes']['border'], $Tag['attributes']['align'], $Tag['attributes']['alt'], $Tag['attributes']['hspace'], $Tag['attributes']['vspace'], $Tag['attributes']['width'], $Tag['attributes']['height'], ($Capabilities['image_format']['jpg'] == 'true' ? 'jpg' : ($Capabilities['image_format']['png'] == 'true' ? 'png' : ($Capabilities['image_format']['gif'] == 'true' ? 'gif' : 'wbmp'))));
	}

	//--- Define default handler... 
	$Handler = (defined ("DefaultFusionProcessor") ? DefaultFusionProcessor : None);
 
 	//--- Continue processing as normal...
	return $Handler ($Tag);

}

}

global $UserStatus, $Filename;
global $Session_ID, $Profile_ID, $Item_ID, $DestItem_ID, $Category_ID, $Revision, $Start;
global $Reference_ID;
global $_PAGE_TITLE;
global $_PAGE_SUMMARY_TITLE;
global $_PAGE_DESCRIPTION;
global $_PAGE_KEYWORDS;
global $_PAGE_ROBOTS;
global $_PAGE_CANONICAL;

$ItemsPerPage = 1;

if ($Parameters == false) {
	$Parameters = -1;
}

//--- What is our item source?
if ($Parameters == -1) {
	$_Item_ID = $Item_ID; if ($DestItem_ID) { $Save_ID = $_Item_ID; $_Item_ID = $DestItem_ID; }
} else {
	list ($_Item_ID, $_Start) = explode ('~', $Parameters);
}

//--- The Home item is shown if only a Category_ID is sent through
if ((!$_Item_ID) && ($Category_ID)) {
	$Item = RetrieveCatalogItemByName ("Home", $Category_ID);
	if ($Item) {
		if ($Item['ItemType_CHAR'] == CATALOG_SHORTCUT) {
			$Item = RetrieveShortcutDetails ($Item['Item_ID'], SHORTCUT_TO_ITEM);
			$_Item_ID = $Item['Shortcut_ID'];
		} else {
			$_Item_ID = $Item['Item_ID'];
		}
	} else {
		return;
	}
}

if (!$_Start) {
	$_Start = ($Start ? $Start : 0);
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

if (!$Category_ID) {
	$_Category_ID = RetrieveCatalogCategoryByItemID ($_Item_ID);
} else {
	$_Category_ID = $Category_ID;
}

//--- Permission?
if (!IsAllowed ($_Category_ID, $UserStatus)) {
	echo "You do not have permission to access this story."; return;
}

$Capabilities = RetrieveDeviceCapabilitiesByAgent (($_REQUEST['UA'] ? $_REQUEST['UA'] : $_SERVER['HTTP_USER_AGENT']));
if ($Capabilities['display'] == false) {
	$Capabilities = RetrieveDeviceCapabilitiesByDeviceID ($Capabilities['root_id']);
}

if (is_array ($Capabilities)) {

	if ((strpos ($_SERVER['HTTP_ACCEPT'], 'wml') !== false) || (strpos ($_SERVER['HTTP_ACCEPT'], 'wap') !== false)) {
		$Capabilities['can_support_wml'] = 'true';
	}
	if ((strpos ($_SERVER['HTTP_ACCEPT'], 'xhtml') !== false) || ($Capabilities['markup']['html_wi_w3_xhtmlbasic'])) {
		$Capabilities['can_support_xhtml'] = 'true';
	}

}

//--- Get Content
$Pages = RetrieveContentPages ($_Item_ID, None, $Language, $Version, $_Start + 1, $Status, None, None, None, None);
$Page1 = ReadFromDB ($Pages);

//--- Capture the page impression
if (($Page1) && ($Profile_ID > 0) && ($_Item_ID) && ($_Start == 0)) {
	CaptureContentImpression ($Profile_ID, $_Item_ID, $Language.'/'.$Version, ConvertDateFromDB (RetrieveSessionExpiry ($Session_ID)));
}

//--- Prepare meta tags
$_PAGE_TITLE = stripslashes ($Page1['FullTitle_STRING']);
$_PAGE_SUMMARY_TITLE = stripslashes ($Page1['TeaserTitle_STRING']);
$_PAGE_DESCRIPTION = stripslashes ($Page1['Teaser_BLOB']);
$_PAGE_KEYWORDS = RetrieveContentTags ($_Item_ID, $Page1['ContentLanguage_STRING']."/".$Page1['ContentVersion_NUM'], $Page1['Page_NUM']);
list ($_PAGE_ROBOTS, $_PAGE_CANONICAL) = RetrieveContentSEOTags ($_Item_ID, $Page1['ContentLanguage_STRING']."/".$Page1['ContentVersion_NUM'], $Page1['Page_NUM']);
if ((defined ("AutoCanonicalHomeItems")) && (AutoCanonicalHomeItems == 1) && (!$_PAGE_CANONICAL) && (RetrieveCatalogItemCode ($_Item_ID) == 'Home')) {
	$_PAGE_CANONICAL = RetrieveCatalogContentURL (CATALOG_CATEGORY, KPE, $_Category_ID, RetrieveCatalogCategoryDescription ($_Category_ID)); 
}
?>
<!-- Mobile content item component -->
<style>
.pageBar {
	margin: 10px 0px 5px 0px;
}
.pageBar .prevPage, .pageBar .nextPage, .pageBar .linkPage {
	border: 1px solid #000000; padding: 5px;
}
.pageBar .thisPage {
	border: 1px solid #000000; padding: 5px; background-color: #000000; color: #ffffff;
}
</style>
<?
$Text = ProcessFusion (stripslashes ($Page1['Full_BLOB']), array ("Item_ID" => ($Save_ID ? $Save_ID : $_Item_ID), "Page" => $_Start + 1, "Origin" => urlencode ("Item ".($Save_ID ? $Save_ID : $_Item_ID)), "Reference_ID" => $Reference_ID, "IncludePath" => $IncludePath, "ContentType" => "text/html", "Device" => DEVICE_MOBILE), $Profile_ID, (defined ("DefaultFusionProcessor") ? DefaultFusionProcessor : None));

//--- Strip tags where not supported
if (($Capabilities['wml_ui']['table_support'] == 'false') || ($Capabilities['wml_ui']['xhtml_table_support'] == 'false')) {
	$Text = strip_tags ($Text, "<br><p><a><div><span><hr><img><h1><h2><h3><h4><ul><ol><li><pre><blockquote><form><input><select>");
} else {
	$Text = strip_tags ($Text, "<br><p><a><div><span><hr><img><h1><h2><h3><h4><ul><ol><li><pre><blockquote><form><input><select><table><tbody><th><tr><td>");
}

//--- Allow support for telephone calls where possible
if ($Capabilities['xhtml_ui']['xhtml_make_phone_call_string']) {
	$Text = str_replace ("tel:", $Capabilities['xhtml_ui']['xhtml_make_phone_call_string'], $Text);
}

//--- Add headers if not added elsewhere...
if ($Capabilities['can_support_xhtml'] == 'true') {
	if ((!headers_sent ()) && (!$_REQUEST['UA']) && (!$_REQUEST['_EM']) && (!$_REQUEST['_PM'])) { /* header ("Content-Type: application/vnd.wap.xhtml+xml"); */ }
} else {
	if ((!headers_sent ()) && (!$_REQUEST['UA']) && (!$_REQUEST['_EM']) && (!$_REQUEST['_PM'])) { /* header ("Content-Type: text/vnd.wap.wml"); */ }
}

//--- Auto-formatting
if ($Page1['ContentType_STRING'] == CONTENT_TEXT) {
	echo PrepareContent ($Text);
} else {
	echo $Text;
}

//--- Print page bar
$ItemCount = RetrieveContentPagesCount ($_Item_ID, None, $Language, $Version, None, $Status);
if ($ItemCount > $ItemsPerPage) {
	echo "<div align=\"center\" class=\"pageBar\">Pages ".RetrieveCatalogSmartNumbers (($IncludePath ? ThisURL : "").RetrieveCatalogContentURL (CATALOG_ITEM, ($Filename ? ROOT_URL."/".$Filename : MPE), $_Item_ID, RetrieveCatalogItemCode ($_Item_ID), DEVICE_MOBILE).($Revision ? "&Revision=$Revision" : ""), None, $Session_ID, $_Start, $ItemCount, $ItemsPerPage, 5, " ", "Start", ((SEOFriendlyLinks == 1) && (!defined ("SEOCustomURLBaseFolder")) ? true : false))."</div>";
}
?>