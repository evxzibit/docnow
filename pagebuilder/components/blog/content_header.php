<?
error_reporting(E_ALL);
/*ini_set('display_errors', 1);
include_once ("modules/catalog.php");*/
global $Item_ID,$SubCatName,$CatName,$Category_ID;
if ($Item_ID) {
	$CatalogItem = RetrieveCatalogItemByItemID ($Item_ID);
	$PageHeading = $CatalogItem['ItemCode_STRING'];
} elseif ($Category_ID) {
	$CatalogItem = RetrieveCatalogCategory ($Category_ID, $AccessLevel = None);
	$PageHeading = $CatalogItem['CategoryDescription_STRING'];
} else {
	$Category_ID = 23;
	$CatalogItem = RetrieveCatalogCategory ($Category_ID, $AccessLevel = None);
	$PageHeading = $CatalogItem['CategoryDescription_STRING'];
}?>
<!--<h1><?php echo $PageHeading;?></h1>-->