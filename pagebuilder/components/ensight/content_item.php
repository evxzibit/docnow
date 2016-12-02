<?
include_once ("modules/fusion.php");
include_once ("modules/shortcuts.php");
include_once ("modules/links.php");

global $UserStatus, $Filename;
global $Session_ID, $Profile_ID, $Item_ID, $DestItem_ID, $Category_ID, $Revision, $Start;
global $Reference_ID;
global $_PAGE_TITLE;
global $_PAGE_SUMMARY_TITLE;
global $_PAGE_DESCRIPTION;
global $_PAGE_IMAGE;
global $_PAGE_URL;
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

//--- Get Content
$Pages = RetrieveContentPages ($_Item_ID, None, $Language, $Version, $_Start + 1, $Status, None, None, None, None);
$Page1 = ReadFromDB ($Pages);

//--- Capture the page impression
if (($Page1) && ($_Item_ID) && ($_Start == 0)) {
	if (defined ("EnableProfiling2_0")) {
		$GLOBALS['_ITEM_IMPRESSION'] = $_Item_ID.':'.$Language.($Version != -99 ? '/'.$Version : '');
	} else
	if ($Profile_ID > 0) { 
		CaptureContentImpression ($Profile_ID, $_Item_ID, $Language.'/'.$Version, ConvertDateFromDB (RetrieveSessionExpiry ($Session_ID)));
	}
}

//--- Prepare meta tags
$_PAGE_TITLE = stripslashes ($Page1['FullTitle_STRING']);
$_PAGE_SUMMARY_TITLE = stripslashes ($Page1['TeaserTitle_STRING']);
$_PAGE_DESCRIPTION = stripslashes ($Page1['Teaser_BLOB']);
$_PAGE_IMAGE = ($Page1['Teaser_PIC'] ? ThisURL.($Page1['Teaser_PIC'][0] != '/' ? CONTENT_URL.'/' : '').stripslashes ($Page1['Teaser_PIC']) : '');
$_PAGE_URL = ThisURL.RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $_Item_ID, RetrieveCatalogItemCode ($_Item_ID), DEVICE_PC);
$_PAGE_KEYWORDS = RetrieveContentTags ($_Item_ID, $Page1['ContentLanguage_STRING']."/".$Page1['ContentVersion_NUM'], $Page1['Page_NUM']);
list ($_PAGE_ROBOTS, $_PAGE_CANONICAL) = RetrieveContentSEOTags ($_Item_ID, $Page1['ContentLanguage_STRING']."/".$Page1['ContentVersion_NUM'], $Page1['Page_NUM']);
if ((defined ("AutoCanonicalHomeItems")) && (AutoCanonicalHomeItems == 1) && (!$_PAGE_CANONICAL) && (RetrieveCatalogItemCode ($_Item_ID) == 'Home')) {
	$_PAGE_CANONICAL = RetrieveCatalogContentURL (CATALOG_CATEGORY, KPE, $_Category_ID, RetrieveCatalogCategoryDescription ($_Category_ID)); 
}
$foldersNotToShow = array('14','18','19', '21');
?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-right">
<?
if(!in_array($Category_ID, $foldersNotToShow)){
?>
<!-- Content item component -->
<!-- <style>
.pageBar {
	margin: 10px 0px 5px 0px;
}
.pageBar .prevPage, .pageBar .nextPage, .pageBar .linkPage {
	border: 1px solid #000000; padding: 5px;
}
.pageBar .thisPage {
	border: 1px solid #000000; padding: 5px; background-color: #000000; color: #ffffff;
}
</style> -->


<div class="tg-heading-border">
    <h2><?php echo $_PAGE_TITLE; ?></h2>
</div>
<?
}
$Text = ProcessFusion (stripslashes ($Page1['Full_BLOB']), array ("Item_ID" => ($Save_ID ? $Save_ID : $_Item_ID), "Page" => $_Start + 1, "Origin" => urlencode ("Item ".($Save_ID ? $Save_ID : $_Item_ID)), "Reference_ID" => $Reference_ID, "IncludePath" => $IncludePath, "ContentType" => "text/html"), $Profile_ID, (defined ("DefaultFusionProcessor") ? DefaultFusionProcessor : None));

//--- Auto-formatting
if ($Page1['ContentType_STRING'] == CONTENT_TEXT) {
	echo PrepareContent ($Text);
} else {
	echo $Text;
}

//--- Print page bar
$ItemCount = RetrieveContentPagesCount ($_Item_ID, None, $Language, $Version, None, $Status);
if ($ItemCount > $ItemsPerPage) {
	echo "<div align=\"center\" class=\"pageBar\">Pages ".RetrieveCatalogSmartNumbers (($IncludePath ? ThisURL : "").RetrieveCatalogContentURL (CATALOG_ITEM, ($Filename ? ROOT_URL."/".$Filename : CPE), $_Item_ID, RetrieveCatalogItemCode ($_Item_ID)).($Revision ? "&Revision=$Revision" : ""), None, $Session_ID, $_Start, $ItemCount, $ItemsPerPage, 5, " ", "Start", ((SEOFriendlyLinks == 1) && (!defined ("SEOCustomURLBaseFolder")) ? true : false))."</div>";
}
?>
</div>