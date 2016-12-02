<?
include_once ("modules/DB.php");
include_once ("modules/connect.php");
include_once ("modules/session.php");
include_once ("modules/profile.php");
include_once ("modules/utils.php");
include_once ("modules/catalog.php");
include_once ("modules/content.php");
include_once ("modules/links.php");
include_once ("modules/shortcuts.php");

function HowManySubElements ($Category_ID) {
//--- Returns a count of valid items within the selected category

	$Items = RetrieveCatalogItems ($Category_ID, None, STATUS_VISIBLE, None, None, ORDER_BY_PREDEF, None, None, None);
	$ItemsCount = 0;

	while ($Item = ReadFromDB ($Items)) {

		if ($Item['ItemType_CHAR'] == 'C') {
			$ItemsCount += (RetrieveContentPagesCount ($Item['Item_ID'], None, None, None, None, STATUS_PUBLISHED) ? 1 : 0);
		} else
		if ($Item['ItemType_CHAR'] == 'S') {
			$ItemsCount += (RetrieveContentPagesCount (RetrieveShortcut ($Item['Item_ID']), None, None, None, None, STATUS_PUBLISHED) ? 1 : 0);
		} else
		if ($Item['ItemType_CHAR'] == 'L') {
			$ItemsCount += 1;
		}

	}

	return $ItemsCount + RetrieveCatalogCategoriesCount ($Category_ID, None, STATUS_VISIBLE);

}

function SiteMap ($Category_ID, $Level) {
//--- Build the site map

	global $Session_ID;

	$Categories = RetrieveCatalogCategories ($Category_ID, None, STATUS_VISIBLE, ORDER_BY_PREDEF, None, None, None);

	while ($Category = ReadFromDB ($Categories)) {

		echo "<img src=\"".SITEMAP_Spacer_Image."\" width=\"".($Level * SITEMAP_Spacer_Image_W)."\" height=\"".SITEMAP_Spacer_Image_H."\" align=\"absMiddle\"><a href=\"".KPE."?Category_ID=".$Category['Category_ID']."&Session_ID=$Session_ID\"><img src=\"".SITEMAP_Folder_Image."\" width=\"".SITEMAP_Folder_Image_W."\" height=\"".SITEMAP_Folder_Image_H."\" border=\"0\" align=\"absMiddle\" /></a> <a href=\"".($IncludePath ? ThisURL : "").FixQueryString (RetrieveCatalogContentURL (CATALOG_CATEGORY, KPE, $Category['Category_ID'], $Category['CategoryDescription_STRING']).(!$IncludePath ? "&Session_ID=".$Session_ID : ''), ((SEOFriendlyLinks == 1) && (!defined ("SEOCustomURLBaseFolder")) ? true : false))."\">".($Category['TeaserTitle_STRING'] ? $Category['TeaserTitle_STRING'] : $Category['CategoryDescription_STRING'])."</a><br />\n";

		$HasSubElements = HowManySubElements ($Category['Category_ID']);

		if ($HasSubElements) {
			SiteMap ($Category['Category_ID'], $Level + 1);
		}

	}

	if (IncludeItems == false) {
		return;
	}

	$Items = RetrieveCatalogItems ($Category_ID, None, STATUS_VISIBLE, None, None, ORDER_BY_PREDEF, None, None, None);

	while ($Item = ReadFromDB ($Items)) {

		switch ($Item['ItemType_CHAR']) {

			case CATALOG_SHORTCUT:
				$Content = RetrieveShortcutDetails ($Item['Item_ID']);
				$Item_ID = $Content['Shortcut_ID'];
				$LinkURL = ($IncludePath ? ThisURL : "").FixQueryString (RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $Item_ID, RetrieveCatalogItemCode ($Item_ID)).(!$IncludePath ? "&Session_ID=".$Session_ID : ''), ((SEOFriendlyLinks == 1) && (!defined ("SEOCustomURLBaseFolder")) ? true : false));
				//--- If no summary provided in shortcut, look in the item pointed to...
				if ($Content['TeaserTitle_STRING'] == '') { $Content = RetrieveContentPages ($Item_ID, None, DefaultLanguage, None, 1, STATUS_PUBLISHED, None, None, None, None); $Content = ReadFromDB ($Content); }
				break;

			case CATALOG_CONTENT:
				$Content = RetrieveContentPages ($Item['Item_ID'], None, DefaultLanguage, None, 1, STATUS_PUBLISHED, None, None, None, None);
				$Content = ReadFromDB ($Content);
				$Item_ID = $Item['Item_ID'];
				$LinkURL = ($IncludePath ? ThisURL : "").FixQueryString (RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $Item_ID, RetrieveCatalogItemCode ($Item_ID)).(!$IncludePath ? "&Session_ID=".$Session_ID : ''), ((SEOFriendlyLinks == 1) && (!defined ("SEOCustomURLBaseFolder")) ? true : false));
				break;

			case CATALOG_LINK:
				$Content = RetrieveLinkDetails ($Item['Item_ID']);
				$Item_ID = $Item['Item_ID'];
				$LinkURL = $Content['Link_URL'].($Content['LinkType_CHAR'] == LINK_INTERNAL ? (strpos ($Content['Link_URL'], '?') ? '&' : '?')."Session_ID=$Session_ID" : "");

		}

		if (!$Content) {
			continue;
		}

		echo "<img src=\"".SITEMAP_Spacer_Image."\" width=\"".($Level * SITEMAP_Spacer_Image_W)."\" height=\"".SITEMAP_Spacer_Image_H."\" align=\"absMiddle\"><a href=\"".$LinkURL."\"><img src=\"".SITEMAP_Item_Image."\" width=\"".SITEMAP_Item_Image_W."\" height=\"".SITEMAP_Item_Image_H."\" border=\"0\" align=\"absMiddle\" /></a> <a href=\"".$LinkURL."\">".($Content['TeaserTitle_STRING'] ? $Content['TeaserTitle_STRING'] : $Item['ItemCode_STRING'])."</a><br />";

	}

}

define ("SITEMAP_Start_Path", "Home/Content");
define ("IncludeItems", false);
define ("MaxLevels", 2);

define ("SITEMAP_Spacer_Image", "/live/pagebuilder/components/ensight/images/spacer.gif");
define ("SITEMAP_Spacer_Image_W", 25);
define ("SITEMAP_Spacer_Image_H", 25);
define ("SITEMAP_Folder_Image", "/live/pagebuilder/components/ensight/images/folder.gif");
define ("SITEMAP_Folder_Image_W", 25);
define ("SITEMAP_Folder_Image_H", 24);
define ("SITEMAP_Item_Image", "/live/pagebuilder/components/ensight/images/folder.gif");
define ("SITEMAP_Item_Image_W", 13);
define ("SITEMAP_Item_Image_H", 11);

global $UserStatus;
global $Session_ID, $Profile_ID;

SiteMap (RetrieveCatalogCategoryByPath (SITEMAP_Start_Path), 0);
?>