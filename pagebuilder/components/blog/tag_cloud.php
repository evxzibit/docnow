<?
global $Session_ID;

$MaxValue = 0;
$Tags = array ();
$LimitCategory = 51;

$SQL = "SELECT Content_Tags.Word_STRING, COUNT(*) AS counter FROM Catalog, Content_Tags, Content WHERE Catalog.Item_ID = Content_Tags.Item_ID AND Content_Tags.Item_ID = Content.Item_ID AND Content_Tags.Revision_STRING = ".ConcatQueryDB (ConcatQueryDB ("Content.ContentLanguage_STRING", "'/'"), "Content.ContentVersion_NUM")." AND Catalog.ItemStatus_NUM = '".STATUS_VISIBLE."' AND Content.Status_NUM = '".STATUS_PUBLISHED."'".($LimitCategory ? " AND Catalog.Category_ID = '".$LimitCategory."'" : "")." GROUP BY Word_STRING ORDER BY counter LIMIT 20";
echo "<div style='display:none'>".$SQL."</div>";
$XQuery = QueryDB ($SQL);
while ($Result = ReadFromDB ($XQuery)) {
	$Tags[$Result['Word_STRING']] = $Result['counter'];
	if ($Result['counter'] > $MaxValue) {
		$MaxValue = $Result['counter'];
	}
}
while (list ($Word, $Count) = each ($Tags)) {
	$NewTags[] = "<li><a href=\"/search/?Query=".htmlspecialchars (urlencode ($Word))."&LimitCategory=$LimitCategory&Search=combinedSearch&Session_ID=$Session_ID\"><i class=\"fa fa-play\"></i>".$Word."</a></li>";
}
?>
<!--<h2>Popular Topics</h2>-->
<ul>
<?php echo implode ("", $NewTags);?>
</ul>
<!--<a href="#">See more tags</a>-->