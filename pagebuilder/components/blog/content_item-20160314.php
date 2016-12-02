<?
include_once ("modules/fusion.php");
include_once ("modules/shortcuts.php");
include_once ("modules/links.php");
include_once ("modules/catalog.php");
include_once ("modules/content.php");

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
		CaptureImpression ($Profile_ID, $_Item_ID, ConvertDateFromDB (RetrieveSessionExpiry ($Session_ID)));
		//CaptureContentImpression ($Profile_ID, $_Item_ID, $Language.'/'.$Version, ConvertDateFromDB (RetrieveSessionExpiry ($Session_ID)));
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

/*$ownerSQL = "SELECT Value FROM Profiles_Advanced WHERE Profile_ID = '".$Page1['OwnerProfile_ID']."' AND Field = 'GooglePlus'";
			$ownerXQuery = QueryDB ($ownerSQL);
			$ownerResult = ReadFromDB ($ownerXQuery, DB_QUERY_);
		  	
			if ($ownerResult['Value']) {
		  		$_PAGE_OWNER = "<a href=\"".$ownerResult['Value']."?rel=author\" target=\"_blank\">".stripslashes (RetrieveUserName($Page1['OwnerProfile_ID']))."</a>";
			} else {
				$_PAGE_OWNER = stripslashes (RetrieveUserName($Page1['OwnerProfile_ID']));
			}*/
$_PAGE_OWNER = stripslashes (RetrieveUserName($Page1['OwnerProfile_ID']));

$_LAST_MODIFIED = stripslashes (($Page1['Display_DATE']!=="1999-01-01 00:00:00") ? $Page1['Display_DATE'] : $Page1['LastModified_DATE']);
$ItemDetails = RetrieveCatalogItemByItemID ($_Item_ID);
?>
<!-- Content item component -->
<style>
.pageBar {
	margin: 10px 0px 5px 0px;
}
.pageBar .prevPage, .pageBar .nextPage, .pageBar .linkPage {
	border: 1px solid #000000;
	padding: 5px;
}
.pageBar .thisPage {
	border: 1px solid #000000;
	padding: 5px;
	background-color: #000000;
	color: #ffffff;
}
</style>
<? if ($_Item_ID!=1023){?>

<h1><?php echo $_PAGE_TITLE;?></h1>
<div class="authorBlock">
  <ul class="blog-info">
    <li><i class="fa fa-user"></i> By <?php echo $_PAGE_OWNER;?></li>
    <li><i class="fa fa-calendar"></i> <?php echo date("j F, Y",strtotime($_LAST_MODIFIED));?></li>
    <li style="float:right"><div class="shareContainer">
  <?php require_once($_SERVER['DOCUMENT_ROOT']."/live/pagebuilder/components/shared/social.php");?>
</div></li>
  </ul>
</div>
<br class="clearFloat" />
<img src="/content/<?php echo $Page1['Full_PIC'];?>" alt="<?php echo $ItemDetails['ItemCode_STRING'];?>" class="indexListImage" />
<? }
$Text = ProcessFusion (stripslashes ($Page1['Full_BLOB']), array ("Item_ID" => ($Save_ID ? $Save_ID : $_Item_ID), "Page" => $_Start + 1, "Origin" => urlencode ("Item ".($Save_ID ? $Save_ID : $_Item_ID)), "Reference_ID" => $Reference_ID, "IncludePath" => $IncludePath, "ContentType" => "text/html"), $Profile_ID, (defined ("DefaultFusionProcessor") ? DefaultFusionProcessor : None));

//--- Auto-formatting
if ($Page1['ContentType_STRING'] == CONTENT_TEXT) {
	echo PrepareContent ($Text);
} else {
	echo $Text;
} 
if ($_Item_ID!=1023){
//echo "<br class=\"clearFloat\" /></div>";
?>
<div class="authorBlock">
  <ul class="blog-info">
    <li><i class="fa fa-user"></i> By <?php echo $_PAGE_OWNER;?></li>
    <li><i class="fa fa-calendar"></i> <?php echo date("j F, Y",strtotime($_LAST_MODIFIED));?></li>
    <li style="float:right"><div class="shareContainer">
  <?php require_once($_SERVER['DOCUMENT_ROOT']."/live/pagebuilder/components/shared/social.php");?>
</div></li>
  </ul>
</div>
<? }
//--- Print page bar
$ItemCount = RetrieveContentPagesCount ($_Item_ID, None, $Language, $Version, None, $Status);
if ($ItemCount > $ItemsPerPage) {
	echo "<div align=\"center\" class=\"pageBar\">Pages ".RetrieveCatalogSmartNumbers (($IncludePath ? ThisURL : "").RetrieveCatalogContentURL (CATALOG_ITEM, ($Filename ? ROOT_URL."/".$Filename : CPE), $_Item_ID, RetrieveCatalogItemCode ($_Item_ID)).($Revision ? "&Revision=$Revision" : ""), None, $Session_ID, $_Start, $ItemCount, $ItemsPerPage, 5, " ", "Start", ((SEOFriendlyLinks == 1) && (!defined ("SEOCustomURLBaseFolder")) ? true : false))."</div>";
}
?>