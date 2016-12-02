<?
include_once ("modules/content.php");
include_once ("modules/catalog.php");
include_once ("modules/fusion.php");

global $UserStatus, $Filename;
global $Session_ID, $Profile_ID, $Item_ID, $DestItem_ID, $Revision, $Start;

//--- What is our item source?
$_Item_ID = $Item_ID;

//--- Saves the destination item
if ($DestItem_ID) {
	$Save_ID = $_Item_ID; $_Item_ID = $DestItem_ID;
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

$Processor = (defined ("DefaultFusionProcessor") ? DefaultFusionProcessor : None);

//--- Get Content
$Pages = RetrieveContentPages ($_Item_ID, None, $Language, $Version, None, $Status, ORDER_BY_PAGE, None, None, None);
?>
<!-- Content pages component -->
<table cellpadding="2" cellspacing="0" border="0">
<?
while ($Page = ReadFromDB ($Pages)) {

	$Text = ProcessFusion (stripslashes ($Page['Teaser_BLOB']), array ("Item_ID" => ($Save_ID ? $Save_ID : $_Item_ID), "Category_ID" => $Category_ID, "Page" => $Page['Page_NUM'], "IncludePath" => $IncludePath, "ContentType" => "text/html"), $Profile_ID, $Processor);

	//--- Auto-formatting
	if ($Page['ContentType_STRING'] == CONTENT_TEXT) {
		$Text = ($Text ? "<br />".PrepareContent ($Text)."<br />" : '');
	} else {
		$Text = ($Text ? "<br />".$Text."<br />" : '');
	}

	echo "<tr valign=\"top\">\n";
	echo "<td>".$Page['Page_NUM'].".</td><td>".($Start != ($Page['Page_NUM'] - 1) ? "<a href=\"".($IncludePath ? ThisURL : "").FixQueryString (RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $Item_ID, RetrieveCatalogItemCode ($Item_ID)).($Page['Page_NUM'] - 1 ? "&Start=".($Page['Page_NUM'] - 1) : '').($Revision ? "&Revision=$Revision" : "").(!$IncludePath ? "&Session_ID=".$Session_ID : ''), ((SEOFriendlyLinks == 1) && (!defined ("SEOCustomURLBaseFolder")) ? true : false))."\">".stripslashes ($Page['TeaserTitle_STRING'])."</a>" : "<b>".stripslashes ($Page['TeaserTitle_STRING'])."</b>")."$Text</td>\n";
	echo "</tr>\n";

} // end while
?>
</table>