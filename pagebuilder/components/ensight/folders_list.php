<?
include_once ("modules/content.php");
include_once ("modules/catalog.php");
include_once ("modules/fusion.php");

global $UserStatus;
global $Session_ID, $Profile_ID, $Category_ID;

if ($Parameters == false) {
	$Parameters = -1;
}

//--- Permission?
if (($ID) && (!IsAllowed (($Parameters != -1 ? $Parameters : $Category_ID), $UserStatus))) {
	echo "You do not have permission to access this area."; return;
}

//--- Get Folders
$XQuery = RetrieveCatalogCategories (($Parameters != -1 ? $Parameters : $Category_ID), None, STATUS_VISIBLE, ORDER_BY_PREDEF, None, None, None);

while ($Result = ReadFromDB ($XQuery)) {

	$Text = ProcessFusion (stripslashes ($Result['Teaser_BLOB']), array ("IncludePath" => $IncludePath, "ContentType" => "text/html"), $Profile_ID, $Processor);
	$Text = ($Text ? "<br />".$Text."<br />" : '');

	$FoldersList[] = array ("Category_ID" => $Result['Category_ID'], "Teaser_PIC" => ($Result['Teaser_PIC'] ? RetrieveImage (CONTENT_FILES."/".$Result['Teaser_PIC'], CONTENT_URL."/".$Result['Teaser_PIC'], 0, None, $Result['TeaserTitle_STRING'], 5, None) : ""), "Link_URL" => ($IncludePath ? ThisURL : "").FixQueryString (RetrieveCatalogContentURL (CATALOG_CATEGORY, KPE, $Result['Category_ID'], $Result['CategoryDescription_STRING']).(!$IncludePath ? "&Session_ID=".$Session_ID : ''), ((SEOFriendlyLinks == 1) && (!defined ("SEOCustomURLBaseFolder")) ? true : false)), "TeaserTitle_STRING" => stripslashes (($Result['TeaserTitle_STRING'] ? $Result['TeaserTitle_STRING'] : $Result['CategoryDescription_STRING'])), "Teaser_BLOB" => $Text);

} // end while

if (!is_array ($FoldersList)) {
	return;
}
?>
<!-- Folder list component -->
<table cellpadding="2" cellspacing="0" border="0">
<?
foreach ($FoldersList as $Folder) {
?>
<tr>
	<td><? echo $Folder['Teaser_PIC']; ?><a href="<? echo $Folder['Link_URL']; ?>"><? echo $Folder['TeaserTitle_STRING']; ?></a><? echo $Folder['Teaser_BLOB']; ?></td>
</tr>
<?
} // end foreach
?>
</table>
