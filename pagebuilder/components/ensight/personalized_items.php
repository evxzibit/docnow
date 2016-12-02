<?
include_once ("modules/content.php");
include_once ("modules/catalog.php");
include_once ("modules/rules.php");
include_once ("modules/fusion.php");

global $Session_ID, $Profile_ID;

//--- Retrieve segments list
$Rules = RetrieveProfileRules ($Profile_ID);

while ($Rule = ReadFromDB ($Rules)) {
	$RulesList[] = $Rule['Rule_ID'];
}

if ((is_array ($RulesList)) && (count ($RulesList))) {

	$SQL = "SELECT DISTINCT Catalog_Rules_Link.Item_ID, Catalog.ItemCode_STRING FROM Catalog_Rules_Link, Catalog WHERE Catalog_Rules_Link.Item_ID = Catalog.Item_ID AND (".implode (" OR ", $RulesList).")";
	$XQuery = QueryDB ($SQL);

} else {

	$SQL = "SELECT DISTINCT Catalog_Rules_Link.Item_ID FROM Catalog_Rules_Link WHERE Rule_ID = 0";
	$XQuery = QueryDB ($SQL);

}

while ($Result = ReadFromDB ($XQuery)) {

	$Item_ID = $Result['Item_ID'];
	$TmpItem = RetrieveCatalogItemByItemID ($Result['Item_ID']);

	$Language = LocateBestContentLanguage (GetVar ("HTTP_ACCEPT_LANGUAGE"), $Item_ID, STATUS_PUBLISHED);

	//--- Get Content
	$Pages = RetrieveContentPages ($Item_ID, None, $Language, None, 1, STATUS_PUBLISHED, None, None, None, None);
	$Page1 = ReadFromDB ($Pages);

	if ($Page1) {

		$Text = ProcessFusion (stripslashes ($Page1['Teaser_BLOB']), array ("IncludePath" => $IncludePath, "ContentType" => "text/html"), $Profile_ID, $Processor);

		//--- Auto-formatting
		if ($Page1['ContentType_STRING'] == CONTENT_TEXT) {
			$Text = ($Text ? "<br />".PrepareContent ($Text)."<br />" : '');
		} else {
			$Text = ($Text ? "<br />".$Text."<br />" : '');
		}

		if ($Page1['TeaserTitle_STRING']) {
			$ItemsList[] = array ("Item_ID" => $Result['Item_ID'], "Teaser_PIC" => ($Page1['Teaser_PIC'] ? RetrieveImage (CONTENT_FILES."/".$Page1['Teaser_PIC'], CONTENT_URL."/".$Page1['Teaser_PIC'], 0, None, $Page1['TeaserTitle_STRING'], 5, None) : ""), "Link_URL" => (($TmpItem['ItemType_CHAR'] == CATALOG_CONTENT) || ($TmpItem['ItemType_CHAR'] == CATALOG_SHORTCUT) ? ($IncludePath ? ThisURL : "").FixQueryString (RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $Item_ID, $TmpItem['ItemCode_STRING']).(!$IncludePath ? "&Session_ID=".$Session_ID : ''), ((SEOFriendlyLinks == 1) && (!defined ("SEOCustomURLBaseFolder")) ? true : false)) : ($TmpItem['ItemType_CHAR'] == CATALOG_LINK ? $Page1['Link_URL'].(($Page1['LinkType_CHAR'] == LINK_INTERNAL) && (!$IncludePath) ? (strpos ($Page1['Link_URL'], "?") ? "&" : "?")."Session_ID=$Session_ID" : "") : "#")), "TeaserTitle_STRING" => stripslashes ($Page1['TeaserTitle_STRING']), "Teaser_BLOB" => $Text);
		}

	}

} // end while

if (!is_array ($ItemsList)) {
	echo "Sorry! There aren't any items recommended for you."; return;
}
?>
<!-- Personalized items component -->
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
