<?
//--- ENVENT Module Source Code (EMSC)
//--- First Created on Saturday, July 11 2000 by Ensight Team
//--- For ENSIGHT

//--- Project Name:

//--- Start Date:
//--- Developers:

//--- Module Name: connect.PHP
//--- Contains: None

//--- Modified by:
//--- Modified by:

//--- Description:
//--- Provides website global settings and configurations. Connects to
//--- a database if DB.php is included.

//--- Notes:

//----------------------------------------------------------------

define ("CONNECT_INCLUDED", 1);

//----------------------------------------------------------------

//--- Details of web site

//--- Instructions:
//--- ThisURL should be hardcoded, or set to
//--- "http://".GetVar ("HTTP_HOST") if dynamic -- all others must
//--- be hardcoded. Ensure that Webmaster and MailBot are valid e-mail
//--- addresses or mails won't be sent.
//--- NEW: CookieDomain allows profiling to work across sub-domains

define ("FriendlyName", "Friendly Name"); //--- Don't change manually, rather use the setup program
define ("ThisURL", "http://localhost");
define ("CookieDomain", "");

//--- Default e-mails
define ("Webmaster", "webmaster");
define ("MailBot", "mailbot");
define ("Wapmaster", "+44555555555");
define ("MobileBot", "+44555555555");

//--- SMTP server
define ("SMTPHost", "localhost");
define ("SMTPPort", "25");
//--- Uncomment these lines if authentication is required
define ("SMTPUser", "");
define ("SMTPPass", "");

//--- Proxy server
//--- Uncomment these lines if a proxy server is used to connect
//--- to external sites
define ("ProxyHost", "");
define ("ProxyPort", "");
$IgnoreProxyHosts = array ("");

//--- Content defaults
define ("DefaultLanguage", "en");
define ("DefaultCharset", "iso-8859-1");
define ("DefaultContentType", "text/html");

//--- Regional settings
define ("DefaultRegion", "uk");

//----------------------------------------------------------------

//--- SEO Friendly URLs

//--- Instructions:
//--- Web server rewrite rules must be in place before enabling this feature.
//--- Add the following to your vhost entry (apache 2.0 below, see support site for alternatives):
//--- ====================================
//--- RewriteEngine on
//--- RewriteCond %{REQUEST_URI} !^/live
//--- RewriteRule ^/c/([0-9]+)/([0-9a-z-]+)/(.*) /live/content.php?Category_ID=$1&$3 [E=REDIRECT_TARGET:/live/content.php]
//--- RewriteRule ^/c/article/([0-9]+)/([0-9a-z-]+)/(.*) /live/content.php?Item_ID=$1&$3 [E=REDIRECT_TARGET:/live/content.php]
//--- RewriteRule ^/m/([0-9]+)/([0-9a-z-]+)/(.*) /live/mobile.php?Category_ID=$1&$3 [E=REDIRECT_TARGET:/live/mobile.php]
//--- RewriteRule ^/m/article/([0-9]+)/([0-9a-z-]+)/(.*) /live/mobile.php?Item_ID=$1&$3 [E=REDIRECT_TARGET:/live/mobile.php]
//--- ====================================
//--- Standard URLs will be rewritten as follows:
//--- ...to web folder level:		http://www.domain.com/c/{id}/{description}/
//--- ...to web item: 				http://www.domain.com/c/article/{id}/{description}/
//--- ...to mobile folder level:	http://www.domain.com/m/{id}/{description}/
//--- ...to mobile item:			http://www.domain.com/m/article/{id}/{description}/
//--- You can change the URL format as required, but please change rewrite rules accordingly
//--- ====================================
//--- Use markers:
//--- {id} - the link's Category_ID or Item_ID
//--- {description} - the friendly name
//--- {template} - template file (optional)
//--- {path} - the folder path (if SEOCustomURLBase is defined)

define ("SEOFriendlyLinks", "1");
//--- Custom URLs
/*
define ("SEOCustomURLBaseFolder", "/web/content");
define ("SEOCustomURLWebCategoryLink", "/{path}{description}");
define ("SEOCustomURLWebItemLink", "/{path}{description}.html");
define ("SEOCustomURLMobileCategoryLink", "/m/{path}{description}");
define ("SEOCustomURLMobileItemLink", "/m/{path}{description}.html");
define ("SEOCustomURLExtension", ".html");
*/
//--- Standard URLs
define ("SEOFriendlyWebCategoryLink", "/c/{id}/{description}/");
define ("SEOFriendlyWebItemLink", "/c/article/{id}/{description}/");
define ("SEOFriendlyMobileCategoryLink", "/m/{id}/{description}/");
define ("SEOFriendlyMobileItemLink", "/m/article/{id}/{description}/");

//--- Do we automatically suggest canonical folder links for "Home" items inside a folder?
define ("AutoCanonicalHomeItems", "0");

//--- Do not touch these settings
if (SEOFriendlyLinks) {
	define ("COOKIE_PATH", "/");
	define ("DefaultRedirectHandler", "SEORedirectHandler");
} else {
	define ("COOKIE_PATH", "");
}

//----------------------------------------------------------------

//--- Application directories (no trailing slash)

//--- Instructions:
//--- Do not alter any settings below except DOCUMENT_ROOT
//--- which must be the same as the configured /wwwroot or /htdocs
//--- for this vhost

define ("DOCUMENT_ROOT", "");
define ("ROOT_FILES", DOCUMENT_ROOT."/live");
define ("MODULE_FILES", DOCUMENT_ROOT."/live/modules");
define ("ADMIN_FILES", DOCUMENT_ROOT."/live/admin");
//--- Secondary
define ("UPLOAD_FILES", DOCUMENT_ROOT."/upload");
define ("IMAGE_FILES", DOCUMENT_ROOT."/images");
define ("CONTENT_FILES", DOCUMENT_ROOT."/content");

//----------------------------------------------------------------

//--- Application URLs (full path - no trailing slash)

//--- Instructions:
//--- Do not alter any settings below except WWW_ROOT
//--- which must contain any path information not already covered
//--- by ThisURL (above) - this step is essential!

define ("WWW_ROOT", "");
define ("ROOT_URL", WWW_ROOT."/live");
define ("MODULE_URL", WWW_ROOT."/live/modules");
define ("ADMIN_URL", WWW_ROOT."/live/admin");
//--- Secondary
define ("UPLOAD_URL", WWW_ROOT."/upload");
define ("IMAGE_URL", WWW_ROOT."/images");
define ("CONTENT_URL", WWW_ROOT."/content");

//----------------------------------------------------------------

//--- PHP standalone (prefix/suffix)

//--- Instructions:
//--- Uncomment or comment out the lines appropriate to the operating
//--- system that Ensight is running on. PHP version 4.2.x is recommended.
//--- Note for Windows servers, PHP scripts (.php) must be associated with
//--- the application /path/to/php-cli.exe as follows:
//--- /path/to/php-cli.exe -q -d max_execution_time=0 "%1" %*
//--- The addition of "-c /path/to/" php.ini might also be necessary

define ("PHP_STANDALONE_PREFIX", "start");
define ("PHP_STANDALONE_SUFFIX", ">> error.log 2>&1 &");

//----------------------------------------------------------------

//--- Security Settings

//--- Before re-connecting a user to a previously detected session, do we verify that their 
//--- IP address has not changed? Enable this feature for maximum security.
define ("HighSecurity", "0");

//--- Enabling this feature will force every tracked link to an external website (via
//--- click.php) to require a verification parameter (v=abcdef).
define ("SecureClickTracking", "1");

//--- Disable this feature to prevent automated CAPTCHA testing in any Standalone API module
//--- that accepts form data. We recommend keeping it enabled unless there are problems.
define ("EnableCAPTCHA", "1");

//--- Protect the Standalone API modules from use by unauthorised third party websites. Only 
//--- those sites listed in $ValidRefererDomains or with a valid API key can submit requests.
define ("ValidateAPIReferer", "1");

//--- if ValidateAPIReferer is set to 1, add a list of domains that may submit requests to the  
//--- Standalone API. Domain names should begin with "http://".
$ValidRefererDomains = array (
	ThisURL
);

//--- Add a list of valid API authorisation codes, and IP addresses that can submit them. The key
//--- can be any string, but a secure version generated by GenerateAPIKey () is recommended.
$ValidAPIKeys = array (
	'127.0.0.1' => 'f0e8212b6bda3ced017c4659bd6ea90b'
);

//----------------------------------------------------------------

//--- Global Flags

//--- Instructions:
//--- Change the following flags as required - 1 means Yes and 0 
//--- means No

define ("TrackActions", "1");		// Do we enable activity logging on the site?
define ("RememberMe", "1");
define ("SmartMatching", "0");		// Do we try to identify guests without cookies?
define ("KeepAlive", "0");			// Do we maintain cookies after users login?
define ("ForceLogout", "0");		// Do we force users to logout before re-logging in?
define ("SendReminders", "1");		// Do we e-mail password reminders to site users?
define ("SendWelcome", "1");		// Do we send a welcome message to the user?
define ("SendAdmin", "1");			// Do we send a welcome message to the site admin?
define ("SessionCookies", "1");		// Do we use cookies for guest session identifiers?
define ("EmailMessage", "1");		// Do we e-mail a copy of messages to recipients?
define ("EmailAdmin", "1");			// Do we e-mail a copy of messages to the site admin?
define ("EmailDirect", "1");		// Do we send the body of the message too?
define ("CopyUserProfile", "0");	// Do we copy the user profile if it exists in both profiles?
define ("ValidationLevel", "1");	// Do we secure profile activation?

define ("EnableChannel", "1");
define ("EnableCaching", "1");

//--- Set "E" for E-mail or "M" for Mobile Telephone
define ("LoginFlag", "E");

//--- Comment out this feature if security is an issue, otherwise we recommend
//--- leaving it in for more accurate tracking of unique user profiles
define ("SmartMatchNoCookieProfiles", "1");

//--- Uncomment these lines if you want to use <META> tags instead of
//--- header redirects on pages specified below. MetaRedirect solves a known
//--- setcookie bug found in IIS 3, 4 and 5
//define ("UseMetaRedirect", "1");
//$MetaRedirectPages = array ("monster.php", "LSM.php", "CPPM.php", "logout.php");

//--- Admin Flags

//--- Do we enable content version control in admin?
define ("VersionControl", 1);
//--- Which item copying method is pre-selected?
define ("DefaultItemCopyingMethod", "3");
//--- How is word wrapping handled in text area fields?
define ("DefaultWordWrapping", "virtual");
//--- Do we display the full item code in the explorer?
define ("DisplayFullItemCode", 0);
//--- Do we allow users to edit published content?
define ("AllowEditingOfPublishedContent", 1);
//--- Bind templates to the items that point to them?
define ("BindTemplates", 1);
//--- Bind shortcuts to the items they point to?
define ("BindShortcuts", 1);
//--- What is the maximum file size that can be uploaded?
define ("MaxSizeOfUploadedFiles", 1000000);

//----------------------------------------------------------------

//--- User and admin timeouts (in seconds)

define ("PROFILE_TIMEOUT", 3600*24*365); 	// How long a KeepAlive user account stays active (default = 1 year)
define ("GUEST_TIMEOUT", 3600*24*365); 		// How long a guest account stays active (default = 1 year)
define ("SESSION_TIMEOUT", 3600*4); 		// How long a non-admin session stays active (default = 4 hours)
define ("ADMIN_TIMEOUT", 3600*8); 			// How long an admin session stays active (default = 8 hours - change for security)

//--- Default object timeouts (in seconds)

define ("CATALOG_TIMEOUT", 3600*24*365); 	// Default expiration for an item
define ("REFERENCE_TIMEOUT", 3600*24*365); 	// Default expiration for a reference
define ("INSTANCE_TIMEOUT", 3600*24*365); 	// Default expiration for an instance
define ("MESSAGE_TIMEOUT", 3600*24*365); 	// Default expiration for a message
define ("PREFERENCES_TIMEOUT", 3600*24*365);// Default expiration for a preference
define ("FAVORITES_TIMEOUT", 3600*24*365);	// Default expiration for a favorite

//--- Mail and text sending timeouts (in seconds)

define ("NUMBER_OF_PROCESSES", 5);			// The number of processes to start in sequence
define ("WAIT_TIMEOUT_AT", 20); 			// How many messages to send before taking a breather
define ("WAIT_TIMEOUT", 10); 				// The number of seconds to wait at each rest point

//----------------------------------------------------------------

//--- Password information

define ("PASSWORD_MIN", 4);		// Minimum number of characters
define ("PASSWORD_MAX", 12);	// Maximum number of characters

//----------------------------------------------------------------

//--- Miscellaneous application definitions

define ("HOME", "");	// Default Home Page (Main)
define ("SRCH", "");	// Search Results Page
define ("CPCM", "");	// Create Profile Capture Module
define ("CPVM", "");	// Create Profile Verification Module
define ("CPP", "");		// Content Preview Page
define ("CPE", "");		// Content Publishing Engine
define ("KPE", "");		// Catalog Publishing Engine
define ("MHP", "");		// Mobile Home Page
define ("MPE", "");		// Mobile Content Publishing Engine
define ("MKP", "");		// Mobile Catalog Publishing Engine
define ("RLM", "");		// Registration/Login Module
define ("PRP", "");		// Password Request Page
define ("PHP", "");		// Personal Home Page
define ("PEM", "");		// Profile Editing Module
define ("MBX", "");		// Personal Mail Box
define ("MDP", "");		// Message Display Page (Inbox)
define ("PAB", "");		// Personal Address Book

//--- Optional debug and error pages
define ("DEBUG_PAGE", "");
define ("ERROR_PAGE", "");

$CustomFontStyles = array ("default", "H1", "H2", "H3", "H4", "H5", "ADDRESS", "PRE");

//----------------------------------------------------------------

//--- Mobile Settings

//--- Switch to mobile friendly template if user is accessing 
//--- via a mobile device instead of desktop. Note that this only
//--- happens during profiling 
define ("AutoDetectMobileDevices", "0");

//--- Comment out the appropriate rows, and modify as necessary.
//--- The 'web' array contains the URLs to test for (perl regexp is fully   
//--- supported), while the 'mobile' array contains the converted URLs
$AutoConvertWebURLs = array (
	'web' => array (
		"/index.php/", 
		"/\/c\/([0-9]+)\/([-0-9a-z]+)\//",  
		"/\/c\/article\/([0-9]+)\/([-0-9a-z]+)\//",
//		"/^\/(?!(c|m)\/)([-0-9a-z\/.]+)/",
	), 
	'mobile' => array (
		"mobile-index.php",  
		"/m/$1/$2/", 
		"/m/article/$1/$2/",
//		"/m/$2",
	)
);

//----------------------------------------------------------------

//--- PageBuilder Settings

//--- Page
define ("TMargin", "10");		//--- Top margin
define ("LMargin", "10");		//--- Left-hand margin
define ("BMargin", "0");		//--- Bottom margin
define ("RMargin", "10");		//--- Right-hand margin
define ("MarginSpace", "0");	//--- Add space on left/right of main row
define ("BottomSpace", "1");	//--- Add space below bottom container
define ("SpacerW", "10");		//--- Spacer width
define ("SpacerH", "10");		//--- Spacer height

//--- Defaults
define ("DefaultBullet", "&#149;");
define ("DefaultTheme", "standard");
define ("IncHeader", "");
define ("IncFooter", "");
define ("XHTML_Strict_Mode", true);

//--- Jump to Personalized Home Page first if logged in?
define ("PersonalizeFrontPage", "0");

//--- Where to look for new components
define ("ComponentInstallationDirectory", ROOT_FILES."/pagebuilder/components");

//----------------------------------------------------------------

//--- SmartSender Settings

//define ("EnableInboxMonitor", "0");
//define ("EnableCampaignPreview", "1");
//define ("InboxMonitorSeedList", MODULE_FILES."/dictionaries/imsl.txt");
//define ("CampaignPreviewSampleAddress", "test@smartsender.co.uk");

//----------------------------------------------------------------

//--- Twitter Settings

define ("DisableTwitterPublishing", "0");		// Prevent Ensight from posting to your Twitter account
define ("TwitterDefaultPost", "Breaking News! {title}... {link}"); // Default message text with {title} and {link} markers
define ("TwitterURLShortener", "bit.ly");		// Only "bit.ly" or "other" (none) supported for now
define ("TwitterURLShortenerLogin", "");		// Pnly if you want your own stats
define ("TwitterURLShortenerAPI", ""); 			// Get this from http://bit.ly/account/your_api_key

//----------------------------------------------------------------

//--- Other Output Settings

//--- Font (deprecated)
define ("DefaultFont", "<font face=\"Verdana, Arial, Helvetica\" size=\"-1\">");
define ("OtherFont", "<font face=\"Verdana, Arial, Helvetica\" size=\"-2\">");

//--- Generic message headers and footers
define ("HTMLMailHeader", "<html>\n<head>\n\t<title>{title}</title>\n\t<link href=\"{stylesheet}\" rel=\"stylesheet\" type=\"text/css\" />\n</head>\n<body>");
define ("HTMLMailFooter", "</body></html>");
define ("TextMailHeader", "");
define ("TextMailFooter", "");
define ("MobileHeader", "");
define ("MobileFooter", "");
define ("MailHeader", ""); // deprecated
define ("MailFooter", ""); // deprecated

//--- Welcome messages (see CPPM.php)
define ("WelcomeUseHTML", "0"); // Do we use HTML?
define ("AdminWelcomeMessage", "Dear Admin\n\nThis is an automated message. A new user has registered on <?ECHO var=\"friendly\"></?>!\n\nTheir login details are as follows:\n\nE-mail address: <?ECHO var=\"e-mail\">Unknown</?>\nPassword: <?ECHO var=\"password\">Unknown</?>\n\nTheir assigned profile # is <?ECHO var=\"profile_id\">Unknown</?>. To adjust the user\'s login details, please use the web-based administration tool.\n\nWebmaster, <?ECHO var=\"friendly\"></?>");
define ("AdminWelcomeSubject", "New registration on <?ECHO var=\"friendly\"></?>");
define ("WelcomeMessage", "Hello,\n\nThanks for registering with <?ECHO var=\"friendly\"></?>!!\n\nPlease click on the following link to activate your profile:\n\n<?ECHO var=\"validate_url\"></?>\n\nTo login, use this e-mail address and the password \'<?ECHO var=\"password\"></?>\'.\n\nOnce again, thanks for visiting. Feel free to stop by anytime at <?ECHO var=\"this_url\"></?>\n\nWebmaster, <?ECHO var=\"friendly\"></?>");
define ("WelcomeSubject", "Welcome to <?ECHO var=\"friendly\"></?>!");

//--- Password reminder message (see reminder.php)
define ("ReminderMessage", "Hello,\n\nAs requested, here is your password for <?ECHO var=\"friendly\"></?>.\nPassword: <?ECHO var=\"password\">Sorry, we couldn\'t find your password</?>\n\nRegards\n\nWebmaster\n<?ECHO var=\"friendly\"></?>");
define ("ReminderSubject", "Your password reminder from <?ECHO var=\"friendly\"></?>");

//--- Mail a friend message (see sendmail.php)
define ("MailSubject", "A special message from <?ECHO var=\"friendly\"></?>");
define ("MailDefault", "The following link was sent to you from <?ECHO var=\"friendly\"></?>:\n");

//--- Private and public message boards (see MPM.php and MMM.php) -- no fusion tags supported
define ("AdminMessageSubject", "There\'s a message waiting for approval on My Website");
define ("AdminMessageHeader", "Dear Admin,\n\nThis is an automated message. A message has been posted on My Website with the subject \'\$Subject\'.");
define ("AdminMessageFooter", "To approve the message, please visit the Message Center at http://www.domain.com/live/admin.\n\nWebmaster, My Website");
define ("AdminMessageDefaultBody", "");
define ("AdminMessageApprovalsTo", Webmaster);
define ("MessageHeader", "Hello,\n\nA message has been sent to you on My Website");
define ("MessageFooter", "Webmaster, My Website\nhttp://www.domain.com");
define ("MessageSubject", "There\'s a message waiting for you on My Website");
define ("MessageDefault", "To read this message, please login to the site at http://www.domain.com/live/message.php");

//--- General windows (deprecated)
define ("WindowHeader", "<html><body><font face=\"Verdana\" size=\"-1\">");
define ("WindowFooter", "</font></body></html>");
define ("HeaderBG", "#C0C0C0");
define ("CellBG", "#FFFFFF");
define ("CellSpace", "2");
define ("CellPad", "2");
define ("TableBorder", "0");

//--- Date/time formatting specifics

define ("FORMAT_LONG", "l, d F Y h:ia (T)");
define ("FORMAT_SHORT", "l, d F Y");
define ("FORMAT_HALF", "d/m/y H:i");
define ("FORMAT_DATE", "d/m/Y");
define ("FORMAT_BRIEF", "d/m H:i");
define ("FORMAT_FULL", "d/m/Y H:i:s");

//----------------------------------------------------------------

//--- Custom definitions

@include_once (MODULE_FILES."/utils.php");
@include_once (MODULE_FILES."/globals.php");
@include_once (MODULE_FILES."/sms.php");

//----------------------------------------------------------------

//--- Define default processors
define ("DefaultDBErrorHandler", "MyErrors");
define ("DefaultEventHandler", "MyEvents");
define ("DefaultFusionProcessor", "MyFusion");
define ("DefaultSMSHandler", "MySMS");

//--- Optional definitions
//define ("SynchronizeFileUpload", "MyFileUploads");
define ("IgnoreFileUploadStripSlashes", "1");
//define ("AutoUnsubscribeAfterXBounces", "3");

//--- Uncomment these definitions to deprecate functionality broken in previous releases...
//define ("DeprecateContentKeywords", "1");
//define ("DeprecateDefaultEmailAddress", "1");
//define ("DeprecateFormFields", "1");
//define ("DeprecateLogin", "1");
//define ("DeprecateLongTextAreaFields", "1");
//define ("DeprecateMIMEstripslashes", "1");
//define ("DeprecateMonster", "1");

//--- Fix PHP settings (from php5.3 onwards)
PHP_INI_FIX ();

function obHandler ($Buffer) {
//--- Enables output buffering of all content

	$Replay = $Buffer;

	//--- Add custom code here
	//$Replay = str_replace ("Lookup", "Replace", $Replay); // example only

	return $Replay;

}

if (!$IgnoreOBHandler) {
	ob_start ("obHandler");
}

//--- Optional redirect to holding page
if (DEBUG_PAGE != '') {
	header ("Location: ".DEBUG_PAGE); exit;
}

//--- Correct default content type
if ((defined ("DefaultContentType")) && (defined ("DefaultCharset"))) {
	header ("Content-Type: ".DefaultContentType."; charset=".DefaultCharset);
}

//--- Connects to the selected database
if (defined ("DB_INCLUDED")) {
	ConnectToDB ("127.0.0.1", None, "database", "login", "password");
}
?>