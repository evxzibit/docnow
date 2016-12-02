<?
include_once ("../../../modules/DB.php");
include_once ("../../../modules/connect.php");
include_once ("../../../modules/utils.php");
include_once ("../../../modules/content.php");
include_once ("../../../modules/catalog.php");
include_once ("../../../modules/fusion.php");
include_once ("../../../modules/session.php");

//--- Lookup profile
if (($Session_ID) && (!$Profile_ID)) {
	$Profile_ID = LocateSession ($Session_ID);
}

$Processor = (defined ("DefaultFusionProcessor") ? DefaultFusionProcessor : None);

//--- Get Content
$Pages = RetrieveContentPages ($Item_ID, None, $Language, $Version, None, STATUS_PUBLISHED, None, None, None, None);
$Count = RetrieveContentPagesCount ($Item_ID, None, $Language, $Version, None, STATUS_PUBLISHED);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Print this page</title>
	<style>
	body { font-family: Verdana; font-size: 12px; color: black; background: white }
	</style>
</head>

<body topmargin="2" leftmargin="2" rightmargin="2" bottommargin="2" marginwidth="2" marginheight="2" onload="window.print ()">

<?
while ($Page = ReadFromDB ($Pages)) {

	$Text = ProcessFusion (stripslashes ($Page['Full_BLOB']), array ("Item_ID" => $Item_ID, "Page" => $Page['Page_NUM'], "Origin" => urlencode ("Item ".$Item_ID), "IncludePath" => true, "ContentType" => "text/html"), $Profile_ID, $Processor);

	//--- Auto-formatting
	if ($Page['ContentType_STRING'] == CONTENT_TEXT) {
		echo PrepareContent ($Text); 
	} else {
		echo $Text;
	}

	//--- Page divider...
	echo "<p align=\"right\"><i>...Page ".$Page['Page_NUM']." of ".$Count."</i></p>\n";

} // end while
?>

</body>
</html>
