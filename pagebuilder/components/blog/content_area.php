<style>
.blog-list-ul {
	margin:0px;
	overflow:hidden;
	position:relative;
	padding-top: 25px !important;
}
.blog-list-ul>li {
	list-style:none !important;
	float:left;
}
.blog-list-item {
	list-style:none !important;
}
.swPage {
}
.swControls{
	position: absolute;
    top: -4px;
    right: 0px;
}
a.swShowPage{

	/* The links that initiate the page slide */

	background-color:#444444;
	float:left;
	height:15px;
	margin:4px 3px;
	text-indent:-9999px;
	width:15px;
	/*border:1px solid #ccc;*/

	/* CSS3 rounded corners */

	-moz-border-radius:7px;
	-webkit-border-radius:7px;
	border-radius:7px;
}

a.swShowPage:hover,
a.swShowPage.active{
	background-color:#2993dd;

	/*	CSS3 inner shadow */

	-moz-box-shadow:0 0 7px #1e435d inset;
	/*-webkit-box-shadow:0 0 7px #1e435d inset;*/
	box-shadow:0 0 7px #1e435d inset;
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
       <li class="blog-list-item">
      <div class="row" style="margin-left: 0px;">
                    <div class="span2" style="float: left;">
                      <!-- BEGIN CAROUSEL -->
                      <img src="/content/<?php echo $Page1['Teaser_PIC'];?>" alt="<?php echo $ItemDetails['ItemCode_STRING'];?>" width="150" class="blog-image" />
                      <!-- END CAROUSEL -->             
                    </div>
                    <div class="span5">
                      <h3><a href="<? echo RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $Page1['Item_ID'], $ItemDetails['ItemCode_STRING']);?>" class=""><?php echo stripslashes ($ItemDetails['ItemCode_STRING']); ?></a></h3>
                      <div class="blog-info">
                        <span><i class="fa fa-calendar"></i> <?php echo date("j F, Y",strtotime($_LAST_MODIFIED));?></span>
                        <span><i class="fa fa-pencil"></i> by <?php echo $_PAGE_OWNER;?></span>
                      </div>
                      <p><?php echo stripslashes($Page1['Teaser_BLOB']);?></p>
                      <a href="<?=RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $Page1['Item_ID'], $ItemDetails['ItemCode_STRING']);?>" class="more">Read more <i class="icon-angle-right"></i></a>
                    </div>
                  </div>
                  <hr class="blog-post-sep">
                  </li>
    <?php } } }?>
    </ul>
<script language="javascript" src="/live/pagebuilder/components/blog/pagination.js?v=17" type="text/javascript"></script>
<script language="javascript">
$(document).ready(function(){
	/* The following code is executed once the DOM is loaded */

	// Calling the jQuery plugin and splitting the
	// #holder UL into pages of 3 LIs each:

	$('.blog-list-ul').sweetPages({perPage:10});

	// The default behaviour of the plugin is to insert the
	// page links in the ul, but we need them in the main container:

	var controls = $('.swControls').detach();
	controls.appendTo('.blog-list-ul');

});
</script>
<!--<div><a href="#" class="loadMore" rel="<?php echo $Start+$ItemsPerPage;?>">Load more posts</a></div>-->