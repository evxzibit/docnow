<?
include_once ("modules/content.php");
include_once ("modules/catalog.php");
include_once ("modules/fusion.php");
include_once ("modules/links.php");
include_once ("modules/shortcuts.php");

global $UserStatus;
global $Session_ID, $Profile_ID, $Category_ID;

if ($Parameters == false) {
	$Parameters = -1;
}

//--- Permission?
if (($ID) && (!IsAllowed (($Parameters != -1 ? $Parameters : $Category_ID), $UserStatus))) {
	echo "You do not have permission to access this area."; return;
}

//--- Get Items
$XQuery = RetrieveCatalogItems (($Parameters != -1 ? $Parameters : $Category_ID), None, STATUS_VISIBLE, None, None, ORDER_BY_PREDEF, None, None, None);

while ($Result = ReadFromDB ($XQuery)) {

	if ($Result['ItemType_CHAR'] == CATALOG_CONTENT) {

		$Language = LocateBestContentLanguage (GetVar ("HTTP_ACCEPT_LANGUAGE"), $Result['Item_ID'], STATUS_PUBLISHED);

		//--- Get Content
		$Pages = RetrieveContentPages ($Result['Item_ID'], None, $Language, None, 1, STATUS_PUBLISHED, None, None, None, None);
		$Page1 = ReadFromDB ($Pages);
		$Item_ID = $Result['Item_ID'];

	} else
	if ($Result['ItemType_CHAR'] == CATALOG_LINK) {

		$Page1 = RetrieveLinkDetails ($Result['Item_ID']);

	} else
	if ($Result['ItemType_CHAR'] == CATALOG_SHORTCUT) {

		$Page1 = RetrieveShortcutDetails ($Result['Item_ID'], SHORTCUT_TO_ITEM);
		$Item_ID = $Page1['Shortcut_ID']; $Result = RetrieveCatalogItemByItemID ($Item_ID);

	}

	if ($Page1) {

		$Text = ProcessFusion (stripslashes ($Page1['Teaser_BLOB']), array ("IncludePath" => $IncludePath, "ContentType" => "text/html"), $Profile_ID, $Processor);

		//--- Auto-formatting
		if ($Page1['ContentType_STRING'] == CONTENT_TEXT) {
			$Text = ($Text ? "<br />".PrepareContent ($Text)."<br />" : '');
		} else {
			$Text = ($Text ? "<br />".$Text."<br />" : '');
		}

		if ($Page1['TeaserTitle_STRING']) {
			$ItemsList[] = array ("Item_ID" => $Item['Item_ID'], "Teaser_PIC" => ($Page1['Teaser_PIC'] ? RetrieveImage (CONTENT_FILES."/".$Page1['Teaser_PIC'], CONTENT_URL."/".$Page1['Teaser_PIC'], 0, None, $Page1['TeaserTitle_STRING'], 5, None) : ""), "Link_URL" => (($Result['ItemType_CHAR'] == CATALOG_CONTENT) || ($Result['ItemType_CHAR'] == CATALOG_SHORTCUT) ? ($IncludePath ? ThisURL : "").FixQueryString (RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $Item_ID, $Result['ItemCode_STRING']).(!$IncludePath ? "&Session_ID=".$Session_ID : ''), ((SEOFriendlyLinks == 1) && (!defined ("SEOCustomURLBaseFolder")) ? true : false)) : ($Result['ItemType_CHAR'] == CATALOG_LINK ? $Page1['Link_URL'].(($Page1['LinkType_CHAR'] == LINK_INTERNAL) && (!$IncludePath) ? (strpos ($Page1['Link_URL'], "?") ? "&" : "?")."Session_ID=$Session_ID" : "") : "#")), "TeaserTitle_STRING" => stripslashes ($Page1['TeaserTitle_STRING']), "Teaser_BLOB" => $Text);
		}

	}

} // end while

if (!is_array ($ItemsList)) {
	return;
}
?>
<!-- Content list component -->
<table cellpadding="2" cellspacing="0" border="0">
<?
foreach ($ItemsList as $Item) {
?>
<tr>
	<td><? echo $Item['Teaser_PIC']; ?><a href="<? echo $Item['Link_URL']; ?>"><? echo $Item['TeaserTitle_STRING']; ?></a><? echo $Item['Teaser_BLOB']; ?></td>
</tr>
<?
} // end foreach
?>
</table>
