<?
include_once ("modules/catalog.php");

//--- Settings (these should be configurable)
$RequireHomeItem = false;
$LinkClass = "";
$Separator = " &raquo; ";

global $UserStatus;
global $Session_ID, $Profile_ID, $Category_ID, $Item_ID;

if ($Parameters == false) {
	$Parameters = -1;
}

$ItemDetails = RetrieveCatalogItemByItemID ($Item_ID);

/*echo "<pre>";print_r($ItemDetails);echo "</pre>";
*/
$dash = RetrieveCatalogCategory (19, $UserStatus);
if($ItemDetails['ItemCode_STRING'] == 'Home'){

	if (($Parameters == -1) && ($Item_ID) && (!$Category_ID)) { $_Category_ID = RetrieveCatalogCategoryByItemID ($Item_ID); } else { $_Category_ID = $Category_ID; }

	$ThisCategory = ($Parameters != -1 ? $Parameters : $_Category_ID);
	$ThisCategory = ($ThisCategory === false ? CATALOG_ROOT : $ThisCategory);
	$TBreadcrumbs = "";

	$ResultOG = RetrieveCatalogCategory ($Category_ID, $UserStatus);
	/*
	$ResultOG = RetrieveCatalogItemByName ("Home", $ThisCategory);

	$Status = STATUS_PUBLISHED;
	list ($Language, $Version) = explode ('/', LocateBestContentLanguage (GetVar ("HTTP_ACCEPT_LANGUAGE"), $_Item_ID, STATUS_PUBLISHED)."/".None);
	//--- Get Content
	$Pages = RetrieveContentPages ($_Item_ID, None, $Language, $Version, $_Start + 1, $Status, None, None, None, None);
	$Page1 = ReadFromDB ($Pages);*/

	while ($ThisCategory != CATALOG_ROOT) {

		$Result = RetrieveCatalogCategory ($ThisCategory, $UserStatus);

		//--- Check for Base link
		if ($Result['BaseCategory_ID'] == CATALOG_ROOT) {
			$URL_O  = ($IncludePath ? ThisURL : "").HOME;
		} else {
			$URL_O  = ($IncludePath ? ThisURL : "").RetrieveCatalogContentURL (CATALOG_CATEGORY, KPE, $ThisCategory, $Result['CategoryDescription_STRING']);
		}
		if (!$IncludePath) {
			$URL_O .= "&Session_ID=".$Session_ID;
		}

		//--- Fix and complete
		if ((($RequireHomeItem) && (RetrieveCatalogItemCountByName ("Home", $ThisCategory))) || (!$RequireHomeItem)) {
			$URL_O = "<li><a href=\"".FixQueryString ($URL_O, ((SEOFriendlyLinks == 1) && (!defined ("SEOCustomURLBaseFolder")) ? true : false))."\"".($LinkClass ? " class=\"$LinkClass\"" : "").">";
			$URL_C = "</a></li>";
		} else {
			$URL_O = "";
			$URL_C = "";
		}

		//--- On to the next...
		$ThisCategory = $Result['BaseCategory_ID'];

		if (($Result['CategoryStatus_NUM'] == STATUS_VISIBLE) && (($Result['TeaserTitle_STRING']) || ($Result['CategoryDescription_STRING']))) {
			$TBreadcrumbs = $URL_O.($Result['TeaserTitle_STRING'] ? stripslashes ($Result['TeaserTitle_STRING']) : stripslashes ($Result['CategoryDescription_STRING'])).$URL_C.$TBreadcrumbs;
		}

	}
	?>
	<!-- Breadcrumbs component -->
	<?
	//echo "<b>You are here:</b>".$TBreadcrumbs;
	?>

	<div class="col-xs-12">

		<h1><?=$ResultOG['CategoryDescription_STRING']?></h1>
		<ol class="tg-breadcrumb">
			<li><a href="/">Home</a></li>
			<?=$TBreadcrumbs?>

			<?php
			$showsubmenu = array(18,19);
			if(in_array($Category_ID,$showsubmenu)){

			?>
				<li class="dropdown">
					<i class="fa fa-bars" aria-hidden="true" data-toggle="dropdown"></i>
					<ul class="dropdown-menu">
						<li><a href="<?=RetrieveCatalogContentURL (CATALOG_CATEGORY, KPE, 19, $dash['CategoryDescription_STRING'])."&Session_ID=".$Session_ID; ?>" style="color:#062e4c">Dashboard</a></li>
						<li><a href="doctor-gridview2.html" style="color:#062e4c">Doctors</a></li>
						<li><a href="/patients/past-appointments-and-reviews.html?Session_ID=<?=$Session_ID;?>" style="color:#062e4c">Past Appointments</a></li>
						<li><a href="<?php echo RetrieveCatalogContentURL (CATALOG_ITEM, CPE, 35, RetrieveCatalogItemCode(35), DEVICE_PC)."&Session_ID=".$Session_ID; ?>" style="color:#062e4c">Setting</a></li>
						<li><a href="/live/logout.php?s=<?php echo $Session_ID; ?>&next=/about-us/&prev=<?php echo ThisURL.$_SERVER['REQUEST_URI']; ?>" style="color:#062e4c">Sign Out</a></li>
					</ul>
				</li>
			<?php
			}

			$doctorCategories = array(14,15);
			if ($Category_ID == 14 ) {
				echo 'Settings';
			}
			if(in_array($Category_ID, $doctorCategories)){

			?>
				<li class="dropdown">
					<i class="fa fa-bars" aria-hidden="true" data-toggle="dropdown"></i>
					<ul class="dropdown-menu">
						<li><a href="reviews-doctor.html" style="color:#062e4c">Past Appointments</a></li>
						<li><a href="<?php echo RetrieveCatalogContentURL (CATALOG_ITEM, CPE, 37, RetrieveCatalogItemCode(37), DEVICE_PC)."&Session_ID=".$Session_ID; ?>" style="color:#062e4c">Settings</a></li>
						<li><a href="/live/logout.php?s=<?php echo $Session_ID; ?>&next=/about-us/&prev=<?php echo ThisURL.$_SERVER['REQUEST_URI']; ?>" style="color:#062e4c">Sign Out</a></li>
					</ul>
				</li>
			<?php
			}

			?>
		</ol>
	</div>
<?

}else{

$ResultOG = RetrieveCatalogCategory ($Category_ID, $UserStatus);

$Status = STATUS_PUBLISHED;
list ($Language, $Version) = explode ('/', LocateBestContentLanguage (GetVar ("HTTP_ACCEPT_LANGUAGE"), $_Item_ID, STATUS_PUBLISHED)."/".None);
//--- Get Content
$Pages = RetrieveContentPages ($Item_ID, None, $Language, $Version, $_Start + 1, $Status, None, None, None, None);
$Page1 = ReadFromDB ($Pages);


?>
	<div class="col-xs-12">

		<h1><?=$ResultOG['CategoryDescription_STRING']?></h1>
		<ol class="tg-breadcrumb">
			<li><a href="/">Home</a></li>
			<li><a href="<?=RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $Item_ID, RetrieveCatalogItemCode($Item_ID), DEVICE_PC)."&Session_ID=".$Session_ID; ?>"><?=$Page1['FullTitle_STRING'] ?></a></li>
			<?php
			$showsubmenu = array(18,19);
			if(in_array($Category_ID,$showsubmenu)){

			?>
				<li class="dropdown">
					<i class="fa fa-bars" aria-hidden="true" data-toggle="dropdown"></i>
					<ul class="dropdown-menu">
						<li><a href="<?=RetrieveCatalogContentURL (CATALOG_CATEGORY, KPE, 19, $dash['CategoryDescription_STRING'])."&Session_ID=".$Session_ID; ?>" style="color:#062e4c">Dashboard</a></li>
						<li><a href="doctor-gridview2.html" style="color:#062e4c">Doctors</a></li>
						<li><a href="/patients/past-appointments-and-reviews.html?Session_ID=<?=$Session_ID;?>" style="color:#062e4c">Past Appointments</a></li>
						<li><a href="<?php echo RetrieveCatalogContentURL (CATALOG_ITEM, CPE, 35, RetrieveCatalogItemCode(35), DEVICE_PC)."&Session_ID=".$Session_ID; ?>" style="color:#062e4c">Setting</a></li>
						<li><a href="/live/logout.php?s=<?php echo $Session_ID; ?>&next=/about-us/&prev=<?php echo ThisURL.$_SERVER['REQUEST_URI']; ?>" style="color:#062e4c">Sign Out</a></li>
					</ul>
				</li>
			<?php
			}
			$doctorCategories = array(14,15);
			if ($Category_ID == 14 ) {
				echo 'Settings';
			}
			if(in_array($Category_ID, $doctorCategories)){

			?>
				<li class="dropdown">
					<i class="fa fa-bars" aria-hidden="true" data-toggle="dropdown"></i>
					<ul class="dropdown-menu">
						<li><a href="reviews-doctor.html" style="color:#062e4c">Past Appointments</a></li>
						<li><a href="<?php echo RetrieveCatalogContentURL (CATALOG_ITEM, CPE, 37, RetrieveCatalogItemCode(37), DEVICE_PC)."&Session_ID=".$Session_ID; ?>" style="color:#062e4c">Settings</a></li>
						<li><a href="/live/logout.php?s=<?php echo $Session_ID; ?>&next=/about-us/&prev=<?php echo ThisURL.$_SERVER['REQUEST_URI']; ?>" style="color:#062e4c">Sign Out</a></li>
					</ul>
				</li>
			<?php
			}
			echo "</ol></div>";
}
?>

