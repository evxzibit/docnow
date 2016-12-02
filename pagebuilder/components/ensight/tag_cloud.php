<?
global $Session_ID;

$MaxValue = 0;
$Tags = array ();

$SQL = "SELECT Content_Tags.Word_STRING, COUNT(*) AS counter FROM Catalog, Content_Tags, Content WHERE Catalog.Item_ID = Content_Tags.Item_ID AND Content_Tags.Item_ID = Content.Item_ID AND Content_Tags.Revision_STRING = ".ConcatQueryDB (ConcatQueryDB ("Content.ContentLanguage_STRING", "'/'"), "Content.ContentVersion_NUM")." AND Catalog.ItemStatus_NUM = '".STATUS_VISIBLE."' AND Content.Status_NUM = '".STATUS_PUBLISHED."'".($LimitCategory ? " AND Catalog.Category_ID IN (".RetrieveCatalogCategoriesIn ($LimitCategory, None, None).")" : "")." GROUP BY Word_STRING";
$XQuery = QueryDB ($SQL);
while ($Result = ReadFromDB ($XQuery)) {
	$Tags[$Result['Word_STRING']] = $Result['counter'];
	if ($Result['counter'] > $MaxValue) {
		$MaxValue = $Result['counter'];
	}
}

if ($MaxValue == 0) {
	$MaxValue = 1;
}

while (list ($Word, $Count) = each ($Tags)) {
	$CountPercent = ($Count / $MaxValue) * 100;
	$TagSize = "";
	$TagSize = (($CountPercent >=  0) && ($CountPercent <  20) ? "smallest" : $TagSize);
	$TagSize = (($CountPercent >= 20) && ($CountPercent <  40) ? "small"    : $TagSize);
	$TagSize = (($CountPercent >= 40) && ($CountPercent <  60) ? "medium"   : $TagSize);
	$TagSize = (($CountPercent >= 60) && ($CountPercent <  80) ? "large"    : $TagSize);
	$TagSize = (($CountPercent >= 80) && ($CountPercent < 100) ? "largest"  : $TagSize);
	$NewTags[] = "<a href=\"".SRCH."?Query=".htmlspecialchars (urlencode ($Word))."&Session_ID=$Session_ID\" class=\"".$TagSize."\">".$Word."</a>";
}
?>
<!-- Tag cloud component -->
<style>
.smallest { font-size: 10px; }
.small    { font-size: 12px; }
.medium   { font-size: 14px; }
.large    { font-size: 16px; }
.largest  { font-size: 18px; }
</style>
<?
echo implode ("&nbsp;&nbsp;", $NewTags);
?>