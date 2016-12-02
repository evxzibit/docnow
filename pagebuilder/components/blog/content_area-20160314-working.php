<style>
.blog-list-ul {
	margin:0px !important;
}
.blog-list-ul li {
	list-style:none !important;
}
</style>
<?
include_once ("modules/catalog.php");

global $Category_ID;
global $Start;
$ItemsPerPage = 10;
if (!$Start) {
	$Start=0;
}
$CatalogItems = RetrieveCatalogItems ($Category_ID, CATALOG_CONTENT, STATUS_VISIBLE, None, None, ORDER_BY_DATE, ORDER_ASC, None, None);

function date_compare($a, $b)
{
	$t1 = strtotime($a['orderDate']);
	$t2 = strtotime($b['orderDate']);
    return $t2 - $t1;
}    
while ($CatalogItem = ReadFromDB ($CatalogItems)) {
	$all_rows[] = $CatalogItem;
}
$all_rows = array_reverse($all_rows); 
for ($r=$Start; $r<sizeof($all_rows); $r++) {
	list ($Language, $Version) = explode ('/', LocateBestContentLanguage (GetVar ("HTTP_ACCEPT_LANGUAGE"),$all_rows[$r]['Item_ID'], STATUS_PUBLISHED)."/".None);
    $PagesRetrieved = RetrieveContentPages ($all_rows[$r]['Item_ID'], None, DefaultLanguage, $Version, 1, STATUS_PUBLISHED, None, None, None, None);
    $Page1Retrieved = ReadFromDB ($PagesRetrieved); 
	$all_rows[$r]['orderDate'] = stripslashes (($Page1Retrieved['Display_DATE']!=="1999-01-01 00:00:00") ? $Page1Retrieved['Display_DATE'] : $Page1Retrieved['LastModified_DATE']);
}
usort($all_rows, 'date_compare');
?>
<!--<div class="recentPopular"><span class="active">Recent posts</span> | <a href="#">Popular</a><div class="pageNumbers"><?php 
//$Range = RetrieveCatalogPageRange ($Start, sizeof($all_rows), $ItemsPerPage);
//echo RetrieveCatalogPageNumbers ("", None, $Session_ID, $Start, sizeof($all_rows), $ItemsPerPage, 10, " "); ?>Displaying <?php echo sizeof($all_rows);?> results<!--<? //echo (sizeof($all_rows) ? $Range[0] : 0); ?>1 to <?php //echo $Range[1]; ?> of <?php //echo sizeof($all_rows); ?></div></div>-->
  
       <ul class="blog-list-ul"><?php for ($i=$Start; $i<sizeof($all_rows); $i++) {
	  $CatalogItem = $all_rows[$i];
	  if ($CatalogItem) {
	  list ($Language, $Version) = explode ('/', LocateBestContentLanguage (GetVar ("HTTP_ACCEPT_LANGUAGE"),$CatalogItem['Item_ID'], STATUS_PUBLISHED)."/".None);
        $Pages = RetrieveContentPages ($CatalogItem['Item_ID'], None, DefaultLanguage, $Version, 1, STATUS_PUBLISHED, None, None, None, None);
        $Page1 = ReadFromDB ($Pages); 
        $Item_ID = $Page1['Item_ID']; 
        $ItemDetails = RetrieveCatalogItemByItemID ($Item_ID);
		  
		//$ownerSQL = "SELECT Value FROM Profiles_Advanced WHERE Profile_ID = '".$Page1['OwnerProfile_ID']."' AND Field = 'GooglePlus'";
		//$ownerXQuery = QueryDB ($ownerSQL);
		//$ownerResult = ReadFromDB ($ownerXQuery, DB_QUERY_);
		  
		//$_PAGE_OWNER = "<a href=\"".$ownerResult['Value']."?rel=author\" target=\"_blank\">".stripslashes (RetrieveUserName($Page1['OwnerProfile_ID']))."</a>";
		$_PAGE_OWNER = stripslashes (RetrieveUserName($Page1['OwnerProfile_ID']));
		$_LAST_MODIFIED = stripslashes (($Page1['Display_DATE']!=="1999-01-01 00:00:00") ? $Page1['Display_DATE'] : $Page1['LastModified_DATE']);?>
       <?php if ($Page1['Teaser_PIC']) {?>
       <li>
      <div class="row">
                    <div class="span2">
                      <!-- BEGIN CAROUSEL -->
                      <img src="/content/<?php echo $Page1['Teaser_PIC'];?>" alt="<?php echo $ItemDetails['ItemCode_STRING'];?>" width="150" class="blog-image" />
                      <!-- END CAROUSEL -->             
                    </div>
                    <div class="span4">
                      <h2><a href="<? echo RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $Page1['Item_ID'], $ItemDetails['ItemCode_STRING']);?>" class=""><?php echo stripslashes ($ItemDetails['ItemCode_STRING']); ?></a></h2>
                      <ul class="blog-info">
                        <li><i class="fa fa-calendar"></i> <?php echo date("j F, Y",strtotime($_LAST_MODIFIED));?></li>
                        <li><i class="fa fa-pencil"></i> by <?php echo $_PAGE_OWNER;?></li>
                      </ul>
                      <p><?php echo stripslashes($Page1['Teaser_BLOB']);?></p>
                      <a href="<?=RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $Page1['Item_ID'], $ItemDetails['ItemCode_STRING']);?>" class="more">Read more <i class="icon-angle-right"></i></a>
                    </div>
                  </div>
                  <hr class="blog-post-sep">
                  </li>
    <?php } } }?>
    </ul>
<!--<div><a href="#" class="loadMore" rel="<?php echo $Start+$ItemsPerPage;?>">Load more posts</a></div>-->