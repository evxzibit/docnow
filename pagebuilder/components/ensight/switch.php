<?
//--- Extensible Switch Component
//--- Uses Ensight's ability to include one component within another to allow one layout to take on numerous forms
//--- Include this component into your content page in place of content_item.php and call via the URL
//--- /live/content.php?Action=<keyword>
global $Action;
global $_PAGE_TITLE, $_PAGE_DESCRIPTION, $_PAGE_KEYWORDS;

switch ($Action) {
/*
	case 'login':
			include_once ("pagebuilder/components/ensight/login.php");
			$_PAGE_TITLE = "Ensight";
			$_PAGE_DESCRIPTION = "";
			$_PAGE_KEYWORDS = "";
			break;

	case 'register':
			include_once ("pagebuilder/components/ensight/register.php");
			$_PAGE_TITLE = "Ensight";
			$_PAGE_DESCRIPTION = "";
			$_PAGE_KEYWORDS = "";
			break;

*/
	default:
			include_once ("pagebuilder/components/ensight/content_item.php");
			break;
}
?>