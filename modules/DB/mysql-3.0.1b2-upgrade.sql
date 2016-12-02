# ---------------------------------------
# Post 3.0.1b2
# ---------------------------------------

CREATE INDEX ClicksSrc_URL ON Clicks (Src_URL);
DELETE FROM Temp_Mails;
DELETE FROM Temp_Texts;
ALTER TABLE Temp_Mails ADD Recipient_STRING varchar(255) DEFAULT '' NOT NULL;
ALTER TABLE Temp_Mails ADD Process_ID varchar(32) DEFAULT '' NOT NULL;
ALTER TABLE Temp_Texts ADD Recipient_STRING varchar(255) DEFAULT '' NOT NULL;
ALTER TABLE Temp_Texts ADD Process_ID varchar(32) DEFAULT '' NOT NULL;
CREATE INDEX Temp_MailsMailProfileProcessReference ON Temp_Mails (Mail_ID, Profile_ID, Process_ID, Reference_ID);
CREATE INDEX Temp_TextsTextProfileProcessReference ON Temp_Texts (Text_ID, Profile_ID, Process_ID, Reference_ID);
ALTER TABLE Mails ADD CurrentStatus_NUM smallint DEFAULT 0 NOT NULL;
ALTER TABLE Texts ADD CurrentStatus_NUM smallint DEFAULT 0 NOT NULL;
ALTER TABLE Temp_Texts DROP INDEX Temp_TextsText_ID;
ALTER TABLE Temp_Mails DROP INDEX Temp_MailsMail_ID;
ALTER TABLE Temp_Mails DROP INDEX Temp_MailsMailProfileProcess;
ALTER TABLE Temp_Texts DROP INDEX Temp_TextsTextProfileProcess;

# ---------------------------------------
# Post 3.1
# ---------------------------------------

ALTER TABLE Forms_Mails ADD LineCount_NUM smallint(4) DEFAULT '0' NOT NULL;

CREATE TABLE Forms_Mails_Details (
	Mail_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	MailComment_STRING varchar(255) NOT NULL
);
CREATE INDEX Forms_Mails_DetailsMail_ID ON Forms_Mails_Details (Mail_ID);

CREATE TABLE Forms_Mails_Special (
	Mail_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	Condition_NUM int(11) NOT NULL,
	Form_ID int(11) NOT NULL,
	FieldValue_STRING varchar(255) NOT NULL,
	Comparison_OP char(2) NOT NULL
);
CREATE INDEX Forms_Mails_SpecialMail_ID ON Forms_Mails_Special (Mail_ID);

ALTER TABLE Forms_Paths CHANGE LineCount_NUM LineCount_NUM smallint(6) DEFAULT '0' NOT NULL;
ALTER TABLE Forms_Rules CHANGE LineCount_NUM LineCount_NUM smallint(6) DEFAULT '0' NOT NULL;
ALTER TABLE Forms_Texts ADD LineCount_NUM smallint(4) DEFAULT '0' NOT NULL;
ALTER TABLE Forms_Paths_Details ADD PathDisabled_NUM smallint(4) DEFAULT '0' NOT NULL;
UPDATE Forms_Paths_Details SET PathComment_STRING = 'No Description' WHERE PathComment_STRING = '';
ALTER TABLE Forms_Views_Details ADD ViewDisabled_NUM smallint(4) DEFAULT '0' NOT NULL;
UPDATE Forms_Views_Details SET ViewComment_STRING = 'No Description' WHERE ViewComment_STRING = '';

CREATE TABLE Forms_Texts_Details (
	Text_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	TextComment_STRING varchar(255) NOT NULL
);
CREATE INDEX Forms_Texts_DetailsText_ID ON Forms_Texts_Details (Text_ID);

CREATE TABLE Forms_Texts_Special (
	Text_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	Condition_NUM int(11) NOT NULL,
	Form_ID int(11) NOT NULL,
	FieldValue_STRING varchar(255) NOT NULL,
	Comparison_OP char(2) NOT NULL
);
CREATE INDEX Forms_Texts_SpecialText_ID ON Forms_Texts_Special (Text_ID);

ALTER TABLE Forms_Views ADD LineCount_NUM smallint(4) DEFAULT '0' NOT NULL;

CREATE TABLE Forms_Views_Special (
	Item_ID int(11) NOT NULL,
	ViewDefinition_NUM int(11) NOT NULL,
	Condition_NUM int(11) NOT NULL,
	Form_ID int(11) NOT NULL,
	FieldValue_STRING varchar(255) NOT NULL,
	Comparison_OP char(2) NOT NULL
);
CREATE INDEX Forms_Views_SpecialItemView ON Forms_Views_Special (Item_ID, ViewDefinition_NUM);

CREATE TABLE Mails_History (
	Mail_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	HistoryDetails_STRING varchar(255) NOT NULL,
	History_DATE datetime NOT NULL
);
CREATE INDEX Mails_HistoryMail_ID ON Mails_History (Mail_ID);
CREATE INDEX Mails_HistoryProfile_ID ON Mails_History (Profile_ID);

CREATE TABLE Texts_Bounce (
	Text_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL,
	Subject_STRING varchar(255) NOT NULL,
	ReasonCode_NUM smallint(6) NOT NULL,
	Bounced_DATE datetime NOT NULL
);
CREATE INDEX Texts_BounceText_ID ON Texts_Bounce (Text_ID);
CREATE INDEX Texts_BounceProfile_ID ON Texts_Bounce (Profile_ID);
CREATE INDEX Texts_BounceReference_ID ON Texts_Bounce (Reference_ID);

CREATE TABLE Texts_History (
	Text_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	HistoryDetails_STRING varchar(255) NOT NULL,
	History_DATE datetime NOT NULL
);
CREATE INDEX Texts_HistoryText_ID ON Texts_History (Text_ID);
CREATE INDEX Texts_HistoryProfile_ID ON Texts_History (Profile_ID);

CREATE TABLE Texts_Open (
	Text_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL,
	Opened_DATE datetime NOT NULL
);
CREATE INDEX Texts_OpenText_ID ON Texts_Open (Text_ID);
CREATE INDEX Texts_OpenProfile_ID ON Texts_Open (Profile_ID);
CREATE INDEX Texts_OpenReference_ID ON Texts_Open (Reference_ID);

ALTER TABLE Components ADD RendersText_NUM smallint(4) DEFAULT '1' NOT NULL AFTER ShowRight_NUM;
ALTER TABLE Components ADD RendersHTML_NUM smallint(4) DEFAULT '1' NOT NULL AFTER RendersText_NUM;

UPDATE Errors SET Error_STRING = 'Your license key is not valid. Please re-install and try again.' WHERE Error_NUM = 23;
UPDATE Errors SET Error_STRING = 'Your license key has expired.' WHERE Error_NUM = 24;
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES (26, 'Your license key is not valid for this domain.');
UPDATE Preferences SET KeyName_STRING = 'ADMIN_ProfileDisplayPrevious' WHERE KeyName_STRING = 'ProfileDisplayPrevious';
UPDATE Preferences SET KeyName_STRING = 'ADMIN_ItemExplorerTopDisplay' WHERE KeyName_STRING = 'ItemExplorerTopDisplay';

CREATE INDEX Profiles_Catalog_LinkProfile_ID ON Profiles_Catalog_Link (Profile_ID);
CREATE INDEX Profiles_Catalog_LinkItem_ID ON Profiles_Catalog_Link (Item_ID);

ALTER TABLE Countries CHANGE Description_STRING Description_STRING varchar(255) NOT NULL;
ALTER TABLE Errors CHANGE Error_STRING Error_STRING varchar(255) NOT NULL;
ALTER TABLE Languages CHANGE Description_STRING Description_STRING varchar(255) NOT NULL;

ALTER TABLE Forms DROP INDEX FormsItemNameGroup;
ALTER TABLE Forms CHANGE FieldName_STRING FieldName_STRING varchar(255) NOT NULL;
ALTER TABLE Forms CHANGE FieldGroup_STRING FieldGroup_STRING varchar(255) NOT NULL;
ALTER TABLE Forms CHANGE FieldDefaultValue_STRING FieldDefaultValue_STRING varchar(255) NOT NULL;
CREATE INDEX FormsItemFieldName ON Forms (Item_ID, FieldName_STRING);

UPDATE Countries SET Description_STRING = 'Great Britain' WHERE Domain_STRING = 'gb';

ALTER TABLE Profiles CHANGE Email_STRING Login_STRING varchar(40) NOT NULL;

ALTER TABLE Profiles 
	ADD LastSupportsJava_NUM smallint(4) NOT NULL AFTER LastReferer_STRING, 
	ADD LastSupportsJavascript_NUM smallint(4) NOT NULL AFTER LastSupportsJava_NUM, 
	ADD LastSupportsFlash_NUM smallint(4) NOT NULL AFTER LastSupportsJavascript_NUM, 
	ADD LastSupportsFlashVersion_NUM float NOT NULL AFTER LastSupportsFlash_NUM, 
	ADD LastScreenWidth_NUM smallint(4) NOT NULL AFTER LastSupportsFlashVersion_NUM, 
	ADD LastScreenHeight_NUM smallint(4) NOT NULL AFTER LastScreenWidth_NUM, 
	ADD LastBrowseWidth_NUM smallint(4) NOT NULL AFTER LastScreenHeight_NUM, 
	ADD LastBrowserHeight_NUM smallint(4) NOT NULL AFTER LastBrowseWidth_NUM, 
	ADD LastPixelDepth_NUM smallint(4) NOT NULL AFTER LastBrowserHeight_NUM, 
	ADD LastBrowserTimezone_NUM smallint(4) NOT NULL AFTER LastPixelDepth_NUM;

ALTER TABLE Shortcuts CHANGE Item_ID Item_ID int(11) NOT NULL;
CREATE INDEX ShortcutsItem_ID ON Shortcuts (Item_ID);

CREATE TABLE Mails_Custom (
	Mail_ID int(11) NOT NULL,
	RecipientList_STRING varchar(255) NOT NULL
);
CREATE INDEX Mails_CustomMail_ID ON Mails_Custom (Mail_ID);

CREATE TABLE Texts_Custom (
	Text_ID int(11) NOT NULL,
	RecipientList_STRING varchar(255) NOT NULL
);
CREATE INDEX Texts_CustomText_ID ON Texts_Custom (Text_ID);

CREATE TABLE Countries_IP (
	IPFrom_NUM bigint(20) NOT NULL default '0',
	IPTo_NUM bigint(20) NOT NULL default '0' PRIMARY KEY,
	CountryCode_STRING varchar(2) NOT NULL default '',
	CountryName_STRING varchar(64) NOT NULL default '',
	Region_STRING varchar(128) NOT NULL default '',
	City_STRING varchar(128) NOT NULL default '',
	Latitude_NUM double default NULL,
	Longitude_NUM double default NULL
);

CREATE INDEX ActionsActionDetails ON Actions (ActionDetails_STRING(50));
CREATE INDEX Content_HistoryLanguageVersion ON Content_History (ContentLanguage_STRING, ContentVersion_NUM);

ALTER TABLE Profiles_History 
	ADD LastIPCountry_STRING varchar(10) NULL AFTER LastIP_STRING, 
	ADD LastIPRegion_STRING varchar(128) NULL AFTER LastIPCountry_STRING, 
	ADD LastIPCity_STRING varchar(128) NULL AFTER LastIPRegion_STRING, 
	ADD LastIPLatitude_NUM float NULL AFTER LastIPCountry_STRING, 
	ADD LastIPLongitude_NUM float NULL AFTER LastIPLatitude_NUM, 
	ADD LastSupportsJava_NUM smallint(4) NULL AFTER LastAcceptsCookies_NUM, 
	ADD LastSupportsJavascript_NUM smallint(4) NULL AFTER LastSupportsJava_NUM, 
	ADD LastSupportsFlash_NUM smallint(4) NULL AFTER LastSupportsJavascript_NUM, 
	ADD LastSupportsFlashVersion_NUM float NULL AFTER LastSupportsFlash_NUM, 
	ADD LastScreenWidth_NUM smallint(4) NULL AFTER LastSupportsFlashVersion_NUM, 
	ADD LastScreenHeight_NUM smallint(4) NULL AFTER LastScreenWidth_NUM, 
	ADD LastBrowseWidth_NUM smallint(4) NULL AFTER LastScreenHeight_NUM, 
	ADD LastBrowserHeight_NUM smallint(4) NULL AFTER LastBrowseWidth_NUM, 
	ADD LastPixelDepth_NUM smallint(4) NULL AFTER LastBrowserHeight_NUM, 
	ADD LastBrowserTimezone_NUM smallint(4) NULL AFTER LastPixelDepth_NUM, 
	ADD RefererSite_STRING varchar(255) NOT NULL AFTER LastReferer_STRING, 
	ADD RefererSearchTerms_STRING varchar(255) NOT NULL AFTER RefererSite_STRING, 
	ADD RefererHost_STRING varchar(255) NOT NULL AFTER RefererSearchTerms_STRING;

CREATE TABLE Rules_Definitions (
	Rule_ID int(11) NOT NULL,
	RuleDefinition_NUM int(11) NOT NULL PRIMARY KEY auto_increment,
	RuleInclExcl_NUM smallint(6) NOT NULL,
	RuleDefinitionCode_CHAR char(2) NOT NULL,
	RuleDefinitionSequence_NUM smallint(6) NOT NULL,
	RuleValues_STRING varchar(255) NOT NULL,
	Start_DATE datetime NOT NULL,
	End_DATE datetime NOT NULL,
	DateRange_NUM smallint(6) NOT NULL
);
CREATE INDEX Rules_DefinitionsRuleDefinition ON Rules_Definitions (Rule_ID, RuleDefinition_NUM);

ALTER TABLE Rules ADD RuleGroup_ID int(11) NOT NULL default '0' AFTER Rule_ID;
ALTER TABLE Rules ADD RuleDisabled_NUM smallint(4) DEFAULT '0' NOT NULL;
CREATE INDEX RulesRuleGroup_ID ON Rules (RuleGroup_ID);

CREATE TABLE Rules_Groups (
	RuleGroup_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	RuleGroupName_STRING varchar(255) NOT NULL,
	RuleGroupDescription_STRING varchar(255) NOT NULL
);

CREATE TABLE Mails_Forms_Link (
	Mail_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	ViewDefinition_NUM int(11) NOT NULL
);
CREATE INDEX Mails_Forms_LinkMail_ID ON Mails_Forms_Link (Mail_ID);
CREATE INDEX Mails_Forms_LinkItemViewDefinition ON Mails_Forms_Link (Item_ID, ViewDefinition_NUM);

CREATE TABLE Texts_Forms_Link (
	Text_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	ViewDefinition_NUM int(11) NOT NULL
);
CREATE INDEX Texts_Forms_LinkText_ID ON Texts_Forms_Link (Text_ID);
CREATE INDEX Texts_Forms_LinkItemViewDefinition ON Texts_Forms_Link (Item_ID, ViewDefinition_NUM);

ALTER TABLE Campaigns ADD CampaignType_ID smallint(6) NOT NULL default '0' AFTER CampaignCompletion_DATE;

ALTER TABLE Triggers ADD LastRun_DATE datetime NULL;

CREATE TABLE Content_Index (
	Item_ID int(11) NOT NULL,
	Revision_STRING varchar(10) NOT NULL,
	Page_NUM smallint(6) NOT NULL,
	Word_STRING varchar(255) NOT NULL,
	Position_NUM smallint(6) NOT NULL
);
CREATE INDEX Content_IndexWord ON Content_Index (Word_STRING(50));

CREATE TABLE Admin_Content_Index (
	Item_ID int(11) NOT NULL,
	Revision_STRING varchar(10) NOT NULL,
	Page_NUM smallint(6) NOT NULL,
	Word_STRING varchar(255) NOT NULL,
	Position_NUM smallint(6) NOT NULL
);
CREATE INDEX Admin_Content_IndexWord ON Admin_Content_Index (Word_STRING(50));

CREATE TABLE Admin_Message_Index (
	Message_ID int(11) NOT NULL,
	MessageType_CHAR char(1) NOT NULL,
	Word_STRING varchar(255) NOT NULL,
	Position_NUM smallint(6) NOT NULL
);
CREATE INDEX Admin_Message_IndexWord ON Admin_Message_Index (Word_STRING(50));

CREATE TABLE Rules_Groups_Permissions (
	Profile_ID int(11) NOT NULL,
	Group_ID int(11) NOT NULL,
	RuleGroup_ID int(11) NOT NULL,
	PermissionClass_NUM smallint(4) NOT NULL,
	PermissionLevel_NUM smallint(4) NOT NULL,
	Expiry_DATE datetime NOT NULL
);
CREATE INDEX Rules_Groups_PermissionsProfile_ID ON Rules_Groups_Permissions (Profile_ID);

CREATE TABLE Mails_Clicks (
	Mail_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL,
	Dest_URL varchar(255) NOT NULL,
	Click_DATE datetime NOT NULL
);
CREATE INDEX Mails_ClicksMail_ID ON Mails_Clicks (Mail_ID);
CREATE INDEX Mails_ClicksProfile_ID ON Mails_Clicks (Profile_ID);

CREATE TABLE Texts_Responses (
	Text_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL,
	Response_STRING varchar(255) NOT NULL,
	Response_DATE datetime NOT NULL
);
CREATE INDEX Texts_ResponsesText_ID ON Texts_Responses (Text_ID);
CREATE INDEX Texts_ResponsesProfile_ID ON Texts_Responses (Profile_ID);

ALTER TABLE Forms ADD FieldMandatoryError_STRING varchar(255) NOT NULL default '' AFTER FieldMandatory_NUM;
ALTER TABLE Forms ADD FieldValidationType_NUM smallint(6) NOT NULL default '0' AFTER FieldMandatoryError_STRING;
ALTER TABLE Forms ADD FieldHelp_STRING varchar(255) NOT NULL default '' AFTER FieldValidationType_NUM;

CREATE TABLE Admin_Breadcrumbs (
	Profile_ID int(11) NOT NULL default '0',
	BrowseInstance_NUM smallint(6) NOT NULL default '0',
	BrowseExpiry_DATE datetime NOT NULL,
	NavLinks_BLOB blob NOT NULL
);
CREATE INDEX Admin_BreadcrumbsProfile_ID ON Admin_Breadcrumbs (Profile_ID);

CREATE TABLE Campaigns_Types (
	CampaignType_ID smallint(6) NOT NULL PRIMARY KEY auto_increment,
	TypeDescription_STRING varchar(255) NOT NULL,
	TypeColor_STRING varchar(10) NOT NULL
);

ALTER TABLE Content_Mode ADD PageStyleSheet_STRING varchar(255) NOT NULL default '';
ALTER TABLE Mails_Mode ADD PageStyleSheet_STRING varchar(255) NOT NULL default '';
ALTER TABLE Containers_Mode ADD PageStyleSheet_STRING varchar(255) NOT NULL default '';

CREATE TABLE Countries_IP_List (
	CountryCode_STRING varchar(2) NOT NULL default '',
	CountryName_STRING varchar(64) NOT NULL default '',
	Region_STRING varchar(128) NOT NULL default '',
	City_STRING varchar(128) NOT NULL default ''
);
CREATE INDEX Countries_IP_ListCountryCode ON Countries_IP_List (CountryCode_STRING);
CREATE INDEX Countries_IP_ListRegion ON Countries_IP_List (Region_STRING);

# ---------------------------------------
# Post 4.0.0beta
# ---------------------------------------

CREATE TABLE Content_Tags (
	Item_ID int(11) NOT NULL default '0',
	Revision_STRING varchar(10) NOT NULL default '',
	Page_NUM smallint(6) NOT NULL default '0',
	Word_STRING varchar(255) NOT NULL default ''
);
CREATE INDEX Content_TagsWord ON Content_Tags (Word_STRING(50));
CREATE INDEX Content_TagsItemRevisionPage ON Content_Tags (Item_ID, Revision_STRING, Page_NUM);

ALTER TABLE Sessions ADD SmartMatchKey_STRING varchar(32) NOT NULL default '';
CREATE INDEX SessionsSmartMatchKey ON Sessions (SmartMatchKey_STRING);

CREATE TABLE Mails_Forward (
	Mail_ID int NOT NULL,
	Profile_ID int NOT NULL,
	Reference_ID int NOT NULL,
	ForwardedTo_STRING varchar(255) NOT NULL,
	Forward_DATE datetime NOT NULL
);
CREATE INDEX Mails_ForwardMail_ID ON Mails_Forward (Mail_ID);
CREATE INDEX Mails_ForwardProfile_ID ON Mails_Forward (Profile_ID);
CREATE INDEX Mails_ForwardReference_ID ON Mails_Forward (Reference_ID);

CREATE TABLE Texts_Forward (
	Text_ID int NOT NULL,
	Profile_ID int NOT NULL,
	Reference_ID int NOT NULL,
	ForwardedTo_STRING varchar(255) NOT NULL,
	Forward_DATE datetime NOT NULL
);
CREATE INDEX Texts_ForwardText_ID ON Texts_Forward (Text_ID);
CREATE INDEX Texts_ForwardProfile_ID ON Texts_Forward (Profile_ID);
CREATE INDEX Texts_ForwardReference_ID ON Texts_Forward (Reference_ID);

CREATE TABLE Content_Impressions (
	Profile_ID int NOT NULL,
	Item_ID int NOT NULL,
	Revision_STRING varchar(10) NOT NULL,
	Impression_DATE datetime NOT NULL,
	ImpressionExpiry_DATE datetime NOT NULL
);
CREATE INDEX Content_ImpressionsProfile_ID ON Content_Impressions (Profile_ID);
CREATE INDEX Content_ImpressionsItem_ID ON Content_Impressions (Item_ID);
INSERT INTO Content_Impressions (Profile_ID, Item_ID, Impression_DATE, ImpressionExpiry_DATE) SELECT Profile_ID, Item_ID, Impression_DATE, ImpressionExpiry_DATE FROM Impressions;

CREATE TABLE Content_Forward (
	Profile_ID int NOT NULL,
	Item_ID int NOT NULL,
	Revision_STRING varchar(10) NOT NULL,
	ForwardedTo_STRING varchar(255) NOT NULL,
	Forward_DATE datetime NOT NULL
);
CREATE INDEX Content_ForwardProfile_ID ON Content_Forward (Profile_ID);
CREATE INDEX Content_ForwardItem_ID ON Content_Forward (Item_ID);

# ---------------------------------------
# Post 4.0.1
# ---------------------------------------

CREATE TABLE Spider_History (
	Request_URL varchar(255) NOT NULL, 
	LastIP_STRING varchar(50) NOT NULL, 
	LastAgent_STRING varchar(255) NOT NULL, 
	Visit_DATE datetime NOT NULL
);
CREATE INDEX Spider_HistoryRequest ON Spider_History (Request_URL(50));

ALTER TABLE Mails ADD AttachedFile4_STRING varchar(255) NOT NULL default '' AFTER AttachedFile3_STRING;
ALTER TABLE Mails ADD AttachedFile5_STRING varchar(255) NOT NULL default '' AFTER AttachedFile4_STRING;
ALTER TABLE Mails ADD AttachedType4_STRING varchar(255) NOT NULL default '' AFTER AttachedType3_STRING;
ALTER TABLE Mails ADD AttachedType5_STRING varchar(255) NOT NULL default '' AFTER AttachedType4_STRING;
ALTER TABLE Mails ADD AttachedID4_STRING varchar(255) NOT NULL default '' AFTER AttachedID3_STRING;
ALTER TABLE Mails ADD AttachedID5_STRING varchar(255) NOT NULL default '' AFTER AttachedID4_STRING;

INSERT INTO Errors (Error_NUM, Error_STRING) VALUES (27, 'The authorisation test was unsuccessful, please try again.');

# ---------------------------------------
# Post 4.0.2
# ---------------------------------------

ALTER TABLE Profiles CHANGE LastBrowseWidth_NUM LastBrowserWidth_NUM smallint(4) DEFAULT '0' NOT NULL;
ALTER TABLE Profiles_History CHANGE LastBrowseWidth_NUM LastBrowserWidth_NUM smallint(4) DEFAULT '0' NOT NULL;

CREATE INDEX Spider_HistoryLastAgent ON Spider_History (LastAgent_STRING(20));

# ---------------------------------------
# Post 4.1.0
# ---------------------------------------

CREATE INDEX ProfilesStatus ON Profiles (Status_NUM);
CREATE INDEX ProfilesLastIP ON Profiles (LastIP_STRING);
CREATE INDEX ProfilesLastLanguage ON Profiles (LastLanguage_STRING(30));

ALTER TABLE Profiles_History
	ADD RefererType_NUM smallint(4) NOT NULL DEFAULT '0' AFTER RefererSite_STRING,
	ADD LastDevice_STRING varchar(255) NOT NULL DEFAULT 'Desktop PC' AFTER LastPlatformSub_STRING,
	ADD LastDeviceVersion_STRING varchar(255) NOT NULL DEFAULT '' AFTER LastDevice_STRING;

UPDATE Profiles_History SET RefererType_NUM = '2' WHERE RefererSite_STRING != 'Unknown' AND RefererSite_STRING != '';
UPDATE Profiles_History SET RefererType_NUM = '1' WHERE RefererHost_STRING != '' AND (RefererSite_STRING = 'Unknown' OR RefererSite_STRING  = '');

CREATE INDEX Profiles_HistoryRefererSiteSearchTerms ON Profiles_History (RefererSite_STRING, RefererSearchTerms_STRING);
CREATE INDEX Profiles_HistoryRefererHost ON Profiles_History (RefererHost_STRING);
CREATE INDEX Profiles_HistoryRefererSearchTermsSite ON Profiles_History (RefererSearchTerms_STRING, RefererSite_STRING);
CREATE INDEX Profiles_HistoryRefererTypeSite ON Profiles_History (RefererType_NUM, RefererSite_STRING);
CREATE INDEX Profiles_HistoryLastIPCountryRegionCity ON Profiles_History (LastIPCountry_STRING, LastIPRegion_STRING, LastIPCity_STRING);
CREATE INDEX Profiles_HistoryLastDevice ON Profiles_History (LastDevice_STRING);
CREATE INDEX Profiles_HistoryLastIP ON Profiles_History (LastIP_STRING);
CREATE INDEX Profiles_HistoryLastLanguage ON Profiles_History (LastLanguage_STRING);

DROP INDEX ActionsActionTypeDate ON Actions;
DROP INDEX ActionsActionDetails ON Actions;
CREATE INDEX ActionsActionTypeDetails ON Actions (ActionType_NUM, ActionDetails_STRING(50));

CREATE INDEX TermsSearchTerm ON Terms (SearchTerm_STRING(50));

CREATE INDEX Mails_ClicksDest_URL ON Mails_Clicks (Dest_URL(50));
CREATE INDEX Texts_ResponsesResponse ON Texts_Responses (Response_STRING(50));

ALTER TABLE Forms ADD FieldIdentifier_STRING varchar(50) NULL DEFAULT '' AFTER FieldHelp_STRING;
UPDATE Forms SET FieldIdentifier_STRING = CONCAT('form', Form_ID);

CREATE INDEX Admin_Content_IndexItemRevisionPage ON Admin_Content_Index (Item_ID, Revision_STRING, Page_NUM);
CREATE INDEX Admin_Message_IndexMessageType ON Admin_Message_Index (Message_ID, MessageType_CHAR);
CREATE INDEX Content_IndexItemRevisionPage ON Content_Index (Item_ID, Revision_STRING, Page_NUM);

CREATE TABLE Transactions (
	Profile_ID int NOT NULL,
	PurchaseCategory_STRING varchar(255) NOT NULL,
	PurchaseCurrency_STRING varchar(3) NOT NULL,
	PurchasePrice_NUM float NOT NULL,
	Transaction_DATE datetime NOT NULL
);
CREATE INDEX TransactionsProfile_ID ON Transactions (Profile_ID);
CREATE INDEX TransactionsPurchaseCategoryCurrencyPrice ON Transactions (PurchaseCategory_STRING(50), PurchaseCurrency_STRING(3), PurchasePrice_NUM);

CREATE TABLE Mails_Rules_DataSource (
	Mail_ID int NOT NULL,
	Item_ID int NOT NULL
);
CREATE INDEX Mails_Rules_DataSourceMail_ID ON Mails_Rules_DataSource (Mail_ID);

CREATE TABLE Texts_Rules_DataSource (
	Text_ID int NOT NULL,
	Item_ID int NOT NULL
);
CREATE INDEX Texts_Rules_DataSourceText_ID ON Texts_Rules_DataSource (Text_ID);

ALTER TABLE Rules ADD LastCached_DATE datetime NOT NULL DEFAULT '1970-01-01 00:00:00';

CREATE TABLE ShortURLs (
	Unique_STRING varchar(10) NOT NULL,
	URL_STRING varchar(255) NOT NULL
);
CREATE INDEX ShortURLsUnique ON ShortURLs (Unique_STRING);

ALTER TABLE Actions TYPE = innodb;
ALTER TABLE Clicks TYPE = innodb;
ALTER TABLE Content_Forward TYPE = innodb;
ALTER TABLE Content_Impressions TYPE = innodb;
ALTER TABLE Content_Index TYPE = innodb;
ALTER TABLE Content_Keywords TYPE = innodb;
ALTER TABLE Impressions TYPE = innodb;
ALTER TABLE Mails_Bounce TYPE = innodb;
ALTER TABLE Mails_Clicks TYPE = innodb;
ALTER TABLE Mails_Forward TYPE = innodb;
ALTER TABLE Mails_Open TYPE = innodb;
ALTER TABLE Mails_Sent TYPE = innodb;
ALTER TABLE Preferences TYPE = innodb;
ALTER TABLE Profiles TYPE = innodb;
ALTER TABLE Profiles_History TYPE = innodb;
ALTER TABLE Profiles_Values TYPE = innodb;
ALTER TABLE Profiles_Values_Extra TYPE = innodb;
ALTER TABLE Reference TYPE = innodb;
ALTER TABLE Sessions TYPE = innodb;
ALTER TABLE Terms TYPE = innodb;
ALTER TABLE Texts_Bounce TYPE = innodb;
ALTER TABLE Texts_Forward TYPE = innodb;
ALTER TABLE Texts_Open TYPE = innodb;
ALTER TABLE Texts_Responses TYPE = innodb;
ALTER TABLE Texts_Sent TYPE = innodb;
ALTER TABLE Transactions TYPE = innodb;

CREATE INDEX Mails_ClicksReference_ID ON Mails_Clicks (Reference_ID);
CREATE INDEX Texts_ResponsesReference_ID ON Texts_Responses (Reference_ID);

ALTER TABLE Forms_Paths_Details ADD PathDefinitionSequence_NUM smallint(4) DEFAULT '0' NOT NULL;

# ---------------------------------------
# Table 'Content_Impressions_Paths'
# ---------------------------------------

CREATE TABLE Content_Impressions_Paths (
	Item_ID int NOT NULL,
	Profile_ID int NOT NULL,
	Impression_DATE datetime NULL,
	PrevItem_ID int NOT NULL,
	PathToItem_STRING varchar(255) NULL,
	TimeSincePrev_NUM float NULL,
	Sequence_NUM smallint(4) NULL,
	SequenceFlag_CHAR char(1) NULL
);
CREATE INDEX Content_Impressions_PathsPathToItem ON Content_Impressions_Paths (PathToItem_STRING);
CREATE INDEX Content_Impressions_PathsProfile_ID ON Content_Impressions_Paths (Profile_ID);
CREATE INDEX Content_Impressions_PathsSequenceFlag ON Content_Impressions_Paths (SequenceFlag_CHAR);
CREATE INDEX Content_Impressions_PathsItem_ID ON Content_Impressions_Paths (Item_ID);
CREATE INDEX Content_Impressions_PathsPrevItem_ID ON Content_Impressions_Paths (PrevItem_ID);

CREATE TABLE WURFL (
	UserAgent_STRING varchar(255) NOT NULL,
	DeviceID_STRING varchar(50) NOT NULL,
	RootDeviceID_STRING varchar(50) NOT NULL,
	Capabilities_BLOB text NULL
);
CREATE INDEX WURFL_UserAgent ON WURFL (UserAgent_STRING(50));
CREATE INDEX WURFL_DeviceID ON WURFL (DeviceID_STRING(50));
CREATE INDEX WURFL_RootDeviceID ON WURFL (RootDeviceID_STRING(50));

UPDATE Components SET ConfigWidth_NUM = '257', ConfigHeight_NUM = '195' WHERE IncludeFile_STRING = 'ensight/content_item.php';
UPDATE Components SET ConfigWidth_NUM = '280', ConfigHeight_NUM = '435' WHERE IncludeFile_STRING = 'ensight/display_form.php';
UPDATE Components SET ConfigWidth_NUM = '257', ConfigHeight_NUM = '195' WHERE IncludeFile_STRING = 'ensight/content_list.php';
UPDATE Components SET ConfigWidth_NUM = '257', ConfigHeight_NUM = '195' WHERE IncludeFile_STRING = 'ensight/folders_list.php';
UPDATE Components SET ConfigWidth_NUM = '257', ConfigHeight_NUM = '195' WHERE IncludeFile_STRING = 'ensight/breadcrumbs.php';
UPDATE Components SET ConfigWidth_NUM = '238', ConfigHeight_NUM = '160' WHERE IncludeFile_STRING = 'ensight/rss.php';
UPDATE Components SET ConfigWidth_NUM = '250', ConfigHeight_NUM = '160' WHERE IncludeFile_STRING = 'ensight/poll.php';
UPDATE Components SET ConfigWidth_NUM = '300', ConfigHeight_NUM = '150' WHERE IncludeFile_STRING = 'ensight/popup_menu.php';
UPDATE Components SET ConfigWidth_NUM = '257', ConfigHeight_NUM = '195' WHERE IncludeFile_STRING = 'ensight/mobile_content_item.php';
DELETE FROM Components WHERE IncludeFile_STRING = 'ensight/mobile_content_item.php';
DELETE FROM Components WHERE IncludeFile_STRING = 'ensight/switch.php';
DELETE FROM Components WHERE IncludeFile_STRING = 'ensight/tag_cloud.php';
DELETE FROM Components WHERE IncludeFile_STRING = 'media/video.php';
DELETE FROM Components WHERE IncludeFile_STRING = 'flash/flash.php';
INSERT INTO Components VALUES ('ensight/mobile_content_item.php', 'Mobile content from item', 'Displays mobile content from an item specified by the user when they click on a link or complete a form.', 'Content', '1', '1', '1', '1', '1', '0', '1', 'ensight/content_item-config.php', '257', '195');
INSERT INTO Components VALUES ('ensight/switch.php', 'Switch', 'A switch component replaces the Content from Item component in a content layout, allowing developers to customise the content area based on Action= keywords.', 'Content', '1', '1', '1', '1', '1', '1', '1', '', '0', '0');
INSERT INTO Components VALUES ('ensight/tag_cloud.php', 'Tag Cloud', 'Displays a tag cloud with links to appropriate search results.', 'Navigation', '1', '1', '1', '1', '1', '1', '1', '', '0', '0');
INSERT INTO Components VALUES ('media/video.php', 'Display video', 'Displays a streaming video within a content page', 'Multimedia', '0', '0', '1', '0', '1', '0', '1', 'media/video-config.php', '300', '305');
INSERT INTO Components VALUES ('flash/flash.php', 'Display Flash/Shockwave movie', 'Displays the code that renders an Adobe Flash/Shockwave compatible file to the screen.', 'Multimedia', '0', '0', '1', '0', '1', '0', '1', 'flash/flash-config.php', '300', '220');

UPDATE Errors SET Error_STRING = 'The authorisation test was unsuccessful, please try again.' WHERE Error_NUM = 27;

CREATE TABLE Content_SEO (
	Item_ID int(11) NOT NULL default '0',
	Revision_STRING varchar(10) NOT NULL default '',
	Page_NUM smallint(6) NOT NULL default '0',
	NoIndex_CHAR char(1) NOT NULL default '0',
	NoArchive_CHAR char(1) NOT NULL default '0',
	NoFollow_CHAR char(1) NOT NULL default '0',
	CanonicalURL_STRING varchar(255) NOT NULL default ''   
);
CREATE INDEX Content_SEOItemRevisionPage ON Content_SEO (Item_ID,Revision_STRING,Page_NUM);

INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('28', 'You must be registered to complete the User Profile form.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('29', 'You have already completed the User Profile form. Please login to edit your details.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('30', 'You are not authorised to edit these details.');

# ---------------------------------------
# Post 4.2.0
# ---------------------------------------

CREATE TABLE Suppression (
   Suppress_ID int(11) NOT NULL PRIMARY KEY auto_increment,
   Lookup_STRING varchar(100) NOT NULL,
   LookupType_CHAR char(1) NOT NULL
) ENGINE = innodb;
CREATE INDEX SuppressionLookupType ON Suppression (Lookup_STRING, LookupType_CHAR);
