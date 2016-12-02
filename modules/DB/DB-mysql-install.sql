# --------------------------------------------------------
# DB schema 4.1
# --------------------------------------------------------
# --------------------------------------------------------

# ---------------------------------------
# Table 'Actions'
# ---------------------------------------

CREATE TABLE Actions (
	Profile_ID int(11) NOT NULL,
	ActionType_NUM smallint(4) NOT NULL,
	ActionDetails_STRING varchar(255) NOT NULL,
	Action_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX ActionsProfile_ID ON Actions (Profile_ID);
CREATE INDEX ActionsActionTypeDetails ON Actions (ActionType_NUM, ActionDetails_STRING(50));


# ---------------------------------------
# Table 'Admin_Breadcrumbs'
# ---------------------------------------

CREATE TABLE Admin_Breadcrumbs (
	Profile_ID int(11) NOT NULL default '0',
	BrowseInstance_NUM smallint(6) NOT NULL default '0',
	BrowseExpiry_DATE datetime NOT NULL,
	NavLinks_BLOB blob NOT NULL
);
CREATE INDEX Admin_BreadcrumbsProfile_ID ON Admin_Breadcrumbs (Profile_ID);


# ---------------------------------------
# Table 'Admin_Content_Index'
# ---------------------------------------

CREATE TABLE Admin_Content_Index (
	Item_ID int(11) NOT NULL,
	Revision_STRING varchar(10) NOT NULL,
	Page_NUM smallint(6) NOT NULL,
	Word_STRING varchar(255) NOT NULL,
	Position_NUM smallint(6) NOT NULL
);
CREATE INDEX Admin_Content_IndexWord ON Admin_Content_Index (Word_STRING(50));
CREATE INDEX Admin_Content_IndexItemRevisionPage ON Admin_Content_Index (Item_ID, Revision_STRING, Page_NUM);


# ---------------------------------------
# Table 'Admin_Keywords'
# ---------------------------------------

CREATE TABLE Admin_Keywords (
	KeyName_STRING varchar(50) NOT NULL,
	KeyValue_STRING varchar(50) NOT NULL,
	Word_STRING varchar(255) NOT NULL,
	Score_NUM float(10,2) DEFAULT '0.00' NOT NULL
);
CREATE INDEX Admin_KeywordsKeyName_STRING ON Admin_Keywords (KeyName_STRING);
CREATE INDEX Admin_KeywordsKeyValue_STRING ON Admin_Keywords (KeyValue_STRING);


# ---------------------------------------
# Table 'Admin_Message_Index'
# ---------------------------------------

CREATE TABLE Admin_Message_Index (
	Message_ID int(11) NOT NULL,
	MessageType_CHAR char(1) NOT NULL,
	Word_STRING varchar(255) NOT NULL,
	Position_NUM smallint(6) NOT NULL
);
CREATE INDEX Admin_Message_IndexWord ON Admin_Message_Index (Word_STRING(50));
CREATE INDEX Admin_Message_IndexMessageType ON Admin_Message_Index (Message_ID, MessageType_CHAR);


# ---------------------------------------
# Table 'BigTable'
# ---------------------------------------

CREATE TABLE BigTable (
	UniqueKey_STRING varchar(32) NOT NULL, 
	Data_BLOB blob NULL
) ENGINE = myisam;
CREATE UNIQUE INDEX BigTableUniqueKey ON BigTable (UniqueKey_STRING);


# ---------------------------------------
# Table 'Cache'
# ---------------------------------------

CREATE TABLE Cache (
	Cache_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	ContentType_STRING varchar(50) NOT NULL,
	Cache_URL varchar(255) NOT NULL,
	Cache_BLOB blob NULL,
	LastModified_DATE datetime NOT NULL
);
CREATE INDEX CacheCacheURL ON Cache (Cache_URL);


# ---------------------------------------
# Table 'Campaigns'
# ---------------------------------------

CREATE TABLE Campaigns (
	Campaign_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	CampaignTitle_STRING varchar(255) NOT NULL,
	CampaignDescription_STRING varchar(255) NOT NULL,
	CampaignStart_DATE datetime NOT NULL,
	CampaignCompletion_DATE datetime NOT NULL,
	CampaignType_ID smallint(6) NOT NULL default '0',
	OwnerProfile_ID int(11) DEFAULT '1' NOT NULL
);


# ---------------------------------------
# Table 'Campaigns_Permissions'
# ---------------------------------------

CREATE TABLE Campaigns_Permissions (
	Profile_ID int(11) NOT NULL,
	Group_ID int(11) NOT NULL,
	Campaign_ID int(11) NOT NULL,
	PermissionClass_NUM smallint(4) NOT NULL,
	PermissionLevel_NUM smallint(4) NOT NULL,
	Expiry_DATE datetime NOT NULL
);
CREATE INDEX Campaigns_PermissionsProfile_ID ON Campaigns_Permissions (Profile_ID);


# ---------------------------------------
# Table 'Campaigns_Rules_Link'
# ---------------------------------------

CREATE TABLE Campaigns_Rules_Link (
	Campaign_ID int(11) NOT NULL,
	Rule_ID int(11) NOT NULL,
	RuleRank_NUM int(11) NOT NULL
);


# ---------------------------------------
# Table 'Campaigns_Types'
# ---------------------------------------

CREATE TABLE Campaigns_Types (
	CampaignType_ID smallint(6) NOT NULL PRIMARY KEY auto_increment,
	TypeDescription_STRING varchar(255) NOT NULL,
	TypeColor_STRING varchar(10) NOT NULL
);


# ---------------------------------------
# Table 'Catalog'
# ---------------------------------------

CREATE TABLE Catalog (
	Item_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	Category_ID int(11) NOT NULL,
	ItemCode_STRING varchar(255) NOT NULL,
	ItemType_CHAR char(1) NOT NULL,
	ItemStatus_NUM smallint(4) NOT NULL,
	ItemOrder_NUM int(11) NULL,
	ItemViewed_NUM int(11) NOT NULL,
	Entry_DATE datetime NOT NULL,
	LastModified_DATE datetime NOT NULL,
	Expiry_DATE datetime NOT NULL,
	PathSuffix_STRING varchar(100) NULL
);
CREATE INDEX CatalogCategory_ID ON Catalog (Category_ID);
CREATE INDEX CatalogItemCode_STRING ON Catalog (ItemCode_STRING);
CREATE INDEX CatalogItemStatus_NUM ON Catalog (ItemStatus_NUM);
CREATE INDEX CatalogPathSuffix ON Catalog (PathSuffix_STRING);


# ---------------------------------------
# Table 'Catalog_Rules_Link'
# ---------------------------------------

CREATE TABLE Catalog_Rules_Link (
	Item_ID int(11) NOT NULL,
	Rule_ID int(11) NOT NULL,
	RuleType_CHAR char(1) DEFAULT 'T' NOT NULL,
	RuleRank_NUM int(11) NOT NULL
);
CREATE INDEX Catalog_Rules_LinkRule_ID ON Catalog_Rules_Link (Rule_ID);


# ---------------------------------------
# Table 'Catalog_Templates_Link'
# ---------------------------------------

CREATE TABLE Catalog_Templates_Link (
	Item_ID int(11) NOT NULL,
	Template_ID int(11) NOT NULL
);


# ---------------------------------------
# Table 'Catalog_Values'
# ---------------------------------------

CREATE TABLE Catalog_Values (
	Form_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	Instance_ID int(11) NOT NULL,
	FieldValue_STRING varchar(255) NOT NULL
);
CREATE INDEX Catalog_ValuesForm_ID ON Catalog_Values (Form_ID);
CREATE INDEX Catalog_ValuesItem_ID ON Catalog_Values (Item_ID);
CREATE INDEX Catalog_ValuesInstance_ID ON Catalog_Values (Instance_ID);


# ---------------------------------------
# Table 'Catalog_Values_Extra'
# ---------------------------------------

CREATE TABLE Catalog_Values_Extra (
	Form_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	Instance_ID int(11) NOT NULL,
	FieldValueExtra_BLOB blob NULL
);
CREATE INDEX Catalog_Values_ExtraForm_ID ON Catalog_Values_Extra (Form_ID);
CREATE INDEX Catalog_Values_ExtraItem_ID ON Catalog_Values_Extra (Item_ID);
CREATE INDEX Catalog_Values_ExtraInstance_ID ON Catalog_Values_Extra (Instance_ID);


# ---------------------------------------
# Table 'Catalog_Variables'
# ---------------------------------------

CREATE TABLE Catalog_Variables (
	Catalog_ID int(11) NOT NULL,
	CatalogType_CHAR char(1) NOT NULL,
	KeyName_STRING varchar(255) NOT NULL,
	KeyValue_STRING varchar(255) NOT NULL
);
CREATE INDEX Catalog_VariablesCatalog_ID ON Catalog_Variables (Catalog_ID);


# ---------------------------------------
# Table 'Categories'
# ---------------------------------------

CREATE TABLE Categories (
	Category_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	BaseCategory_ID int(11) NOT NULL,
	CategoryDescription_STRING varchar(255) NOT NULL,
	CategoryStatus_NUM smallint(4) NOT NULL,
	CategoryOrder_NUM int(11) NOT NULL,
	TeaserTitle_STRING varchar(255) NOT NULL,
	Teaser_PIC varchar(255) NOT NULL,
	Teaser_BLOB blob NULL,
	FullTitle_STRING varchar(255) NOT NULL,
	Full_PIC varchar(255) NOT NULL,
	Full_BLOB blob NULL,
	AccessLevel_NUM smallint(4) DEFAULT '-2' NOT NULL,
	OwnerProfile_ID int(11) DEFAULT '1' NULL,
	Path_STRING varchar(255) NULL
);
CREATE INDEX CategoriesBaseCategory_ID ON Categories (BaseCategory_ID);
CREATE INDEX CategoriesPath ON Categories (Path_STRING);


# ---------------------------------------
# Table 'Clicks'
# ---------------------------------------

CREATE TABLE Clicks (
	Profile_ID int(11) NOT NULL,
	Src_URL varchar(255) NOT NULL,
	Dest_URL varchar(255) NOT NULL,
	Click_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX ClicksProfile_ID ON Clicks (Profile_ID);
CREATE INDEX ClicksSrc_URL ON Clicks (Src_URL(50));


# ---------------------------------------
# Table 'Components'
# ---------------------------------------

CREATE TABLE Components (
	IncludeFile_STRING varchar(100) NOT NULL,
	ComponentName_STRING varchar(100) NOT NULL,
	ComponentDescription_STRING varchar(255) NOT NULL,
	ComponentClass_STRING varchar(255) NOT NULL,
	ShowTop_NUM smallint(4) NOT NULL,
	ShowBottom_NUM smallint(4) NOT NULL,
	ShowLeft_NUM smallint(4) NOT NULL,
	ShowMiddle_NUM smallint(4) NOT NULL,
	ShowRight_NUM smallint(4) NOT NULL,
	RendersText_NUM smallint(4) DEFAULT '1' NOT NULL,
	RendersHTML_NUM smallint(4) DEFAULT '1' NOT NULL,
	ConfigFile_STRING varchar(100) NOT NULL,
	ConfigWidth_NUM smallint(4) NOT NULL,
	ConfigHeight_NUM smallint(4) NOT NULL
);
CREATE INDEX ComponentsIncludeFile_STRING ON Components (IncludeFile_STRING);
CREATE INDEX ComponentsComponentClass_STRING ON Components (ComponentClass_STRING);


# ---------------------------------------
# Table 'Containers'
# ---------------------------------------

CREATE TABLE Containers (
	Container_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	Page_STRING varchar(255) NOT NULL,
	ContainerType_NUM smallint(4) NOT NULL,
	ContainerPosition_CHAR char(2) NOT NULL,
	ContainerOrder_NUM smallint(4) NOT NULL,
	ContentTitle_STRING varchar(255) NOT NULL,
	Content_BLOB blob NULL,
	ContentFooter_STRING varchar(255) NOT NULL
);
CREATE INDEX ContainersPage_STRING ON Containers (Page_STRING);
CREATE INDEX ContainersContainerPosition_CHAR ON Containers (ContainerPosition_CHAR);


# ---------------------------------------
# Table 'Containers_Mode'
# ---------------------------------------

CREATE TABLE Containers_Mode (
	Container_ID int(11) NOT NULL,
	PageMode_NUM smallint(4) NULL,
	PageStyleSheet_STRING varchar(255) NOT NULL default ''
);
CREATE INDEX Containers_ModeContainer_ID ON Containers_Mode (Container_ID);


# ---------------------------------------
# Table 'Containers_Parameters'
# ---------------------------------------

CREATE TABLE Containers_Parameters (
	Container_ID int(11) NOT NULL,
	Parameters_STRING varchar(255) NOT NULL
);
CREATE INDEX Containers_ParametersContainer_ID ON Containers_Parameters (Container_ID);


# ---------------------------------------
# Table 'Content'
# ---------------------------------------

CREATE TABLE Content (
	Content_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	Item_ID int(11) NOT NULL,
	ContentLanguage_STRING varchar(50) NOT NULL,
	ContentVersion_NUM smallint(4) NOT NULL,
	ContentType_STRING varchar(50) NOT NULL,
	ContentCharset_STRING varchar(50) NOT NULL,
	TeaserTitle_STRING varchar(255) NOT NULL,
	Teaser_PIC varchar(255) NOT NULL,
	Teaser_BLOB blob NULL,
	FullTitle_STRING varchar(255) NOT NULL,
	Full_PIC varchar(255) NOT NULL,
	Full_BLOB blob NULL,
	Page_NUM smallint(4) DEFAULT '1' NOT NULL,
	OwnerProfile_ID int(11) DEFAULT '1' NOT NULL,
	Status_NUM smallint(4) NOT NULL,
	LastModified_DATE datetime NULL,
	Display_DATE datetime NULL default '1999-01-01 00:00:00'
);
CREATE INDEX ContentItem_ID ON Content (Item_ID);
CREATE INDEX ContentLanguageVersion ON Content (ContentLanguage_STRING, ContentVersion_NUM);
CREATE INDEX ContentOwnerProfile_ID ON Content (OwnerProfile_ID);
CREATE INDEX ContentStatus_NUM ON Content (Status_NUM);


# ---------------------------------------
# Table 'Content_Forward'
# ---------------------------------------

CREATE TABLE Content_Forward (
	Profile_ID int NOT NULL,
	Item_ID int NOT NULL,
	Revision_STRING varchar(10) NOT NULL,
	ForwardedTo_STRING varchar(255) NOT NULL,
	Forward_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX Content_ForwardProfile_ID ON Content_Forward (Profile_ID);
CREATE INDEX Content_ForwardItem_ID ON Content_Forward (Item_ID);


# ---------------------------------------
# Table 'Content_History'
# ---------------------------------------

CREATE TABLE Content_History (
	Item_ID int(11) NOT NULL,
	ContentLanguage_STRING varchar(50) NOT NULL,
	ContentVersion_NUM smallint(4) NOT NULL,
	OwnerProfile_ID int(11) NOT NULL,
	HistoryDetails_STRING varchar(255) NOT NULL,
	History_DATE datetime NOT NULL
);
CREATE INDEX Content_HistoryItem_ID ON Content_History (Item_ID);
CREATE INDEX Content_HistoryLanguageVersion ON Content_History (ContentLanguage_STRING, ContentVersion_NUM);


# ---------------------------------------
# Table 'Content_Impressions'
# ---------------------------------------

CREATE TABLE Content_Impressions (
	Profile_ID int NOT NULL,
	Item_ID int NOT NULL,
	Revision_STRING varchar(10) NOT NULL,
	Impression_DATE datetime NOT NULL,
	ImpressionExpiry_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX Content_ImpressionsProfile_ID ON Content_Impressions (Profile_ID);
CREATE INDEX Content_ImpressionsItem_ID ON Content_Impressions (Item_ID);


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


# ---------------------------------------
# Table 'Content_Index'
# ---------------------------------------

CREATE TABLE Content_Index (
	Item_ID int(11) NOT NULL,
	Revision_STRING varchar(10) NOT NULL,
	Page_NUM smallint(6) NOT NULL,
	Word_STRING varchar(255) NOT NULL,
	Position_NUM smallint(6) NOT NULL
) ENGINE = innodb;
CREATE INDEX Content_IndexWord ON Content_Index (Word_STRING(50));
CREATE INDEX Content_IndexItemRevisionPage ON Content_Index (Item_ID, Revision_STRING, Page_NUM);


# ---------------------------------------
# Table 'Content_Keywords'
# ---------------------------------------

CREATE TABLE Content_Keywords (
	Item_ID int(11) NOT NULL,
	Revision_STRING varchar(25) NOT NULL,
	Word_STRING varchar(255) NOT NULL,
	Score_NUM float(10,2) DEFAULT '0.00' NOT NULL
) ENGINE = innodb;
CREATE INDEX Content_KeywordsItemRevision ON Content_Keywords (Item_ID, Revision_STRING);


# ---------------------------------------
# Table 'Content_Mode'
# ---------------------------------------

CREATE TABLE Content_Mode (
	Content_ID int(11) NOT NULL,
	PageMode_NUM smallint(4) NULL,
	PageStyleSheet_STRING varchar(255) NOT NULL default ''
);
CREATE INDEX Content_ModeContent_ID ON Content_Mode (Content_ID);


# ---------------------------------------
# Table 'Content_SEO'
# ---------------------------------------

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


# ---------------------------------------
# Table 'Content_Tags'
# ---------------------------------------

CREATE TABLE Content_Tags (
	Item_ID int(11) NOT NULL default '0',
	Revision_STRING varchar(10) NOT NULL default '',
	Page_NUM smallint(6) NOT NULL default '0',
	Word_STRING varchar(255) NOT NULL default ''
);
CREATE INDEX Content_TagsWord ON Content_Tags (Word_STRING(50));
CREATE INDEX Content_TagsItemRevisionPage ON Content_Tags (Item_ID,Revision_STRING,Page_NUM);


# ---------------------------------------
# Table 'Countries'
# ---------------------------------------

CREATE TABLE Countries (
	Domain_STRING char(10) NOT NULL PRIMARY KEY,
	Description_STRING varchar(255) NOT NULL
);


# ---------------------------------------
# Table 'Countries_IP'
# ---------------------------------------

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


# ---------------------------------------
# Table 'Countries_IP_List'
# ---------------------------------------

CREATE TABLE Countries_IP_List (
	CountryCode_STRING varchar(2) NOT NULL default '',
	CountryName_STRING varchar(64) NOT NULL default '',
	Region_STRING varchar(128) NOT NULL default '',
	City_STRING varchar(128) NOT NULL default ''
);
CREATE INDEX Countries_IP_ListCountryCode ON Countries_IP_List (CountryCode_STRING);
CREATE INDEX Countries_IP_ListRegion ON Countries_IP_List (Region_STRING);


# ---------------------------------------
# Table 'Errors'
# ---------------------------------------

CREATE TABLE Errors (
	Error_NUM int(11) NOT NULL PRIMARY KEY,
	Error_STRING varchar(255) NOT NULL
);


# ---------------------------------------
# Table 'Favorites'
# ---------------------------------------

CREATE TABLE Favorites (
	Favorite_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	Profile_ID int(11) NOT NULL,
	Category_ID int(11) NOT NULL,
	FavoriteType_NUM smallint(4) NOT NULL,
	FavoriteValue_STRING varchar(255) NOT NULL,
	Comment_STRING varchar(255) NOT NULL,
	Visits_NUM smallint(4) NOT NULL,
	LastVisit_DATE datetime NOT NULL,
	Expiry_DATE datetime NOT NULL
);
CREATE INDEX FavoritesProfile_ID ON Favorites (Profile_ID);


# ---------------------------------------
# Table 'Forms'
# ---------------------------------------

CREATE TABLE Forms (
	Form_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	Item_ID int(11) NOT NULL,
	FieldName_STRING varchar(255) NOT NULL,
	FieldGroup_STRING varchar(255) NOT NULL,
	FieldDefaultValue_STRING varchar(255) NOT NULL,
	FieldType_CHAR char(1) NOT NULL,
	FieldSize_NUM char(30) NOT NULL,
	FieldStatus_NUM smallint(4) NOT NULL,
	FieldOrder_NUM smallint(4) NOT NULL,
	FieldMandatory_NUM smallint(4) NOT NULL,
	FieldMandatoryError_STRING varchar(255) NOT NULL default '',
	FieldValidationType_NUM smallint(6) NOT NULL default '0',
	FieldHelp_STRING varchar(255) NOT NULL default '',
	FieldIdentifier_STRING varchar(50) NULL,
	ValueForm_ID int(11) DEFAULT '-1' NOT NULL
);
CREATE INDEX FormsValueForm_ID ON Forms (ValueForm_ID);
CREATE INDEX FormsItem_ID ON Forms (Item_ID);
CREATE INDEX FormsItemFieldName ON Forms (Item_ID, FieldName_STRING);


# ---------------------------------------
# Table 'Forms_Mails'
# ---------------------------------------

CREATE TABLE Forms_Mails (
	Mail_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	Form_ID int(11) NOT NULL,
	FieldValue_STRING varchar(255) NOT NULL,
	LineCount_NUM smallint(4) DEFAULT '0' NOT NULL
);
CREATE INDEX Forms_MailsMail_ID ON Forms_Mails (Mail_ID);


# ---------------------------------------
# Table 'Forms_Mails_Details'
# ---------------------------------------

CREATE TABLE Forms_Mails_Details (
	Mail_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	MailComment_STRING varchar(255) NOT NULL
);
CREATE INDEX Forms_Mails_DetailsMail_ID ON Forms_Mails_Details (Mail_ID);


# ---------------------------------------
# Table 'Forms_Mails_Special'
# ---------------------------------------

CREATE TABLE Forms_Mails_Special (
	Mail_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	Condition_NUM int(11) NOT NULL,
	Form_ID int(11) NOT NULL,
	FieldValue_STRING varchar(255) NOT NULL,
	Comparison_OP char(2) NOT NULL
);
CREATE INDEX Forms_Mails_SpecialMail_ID ON Forms_Mails_Special (Mail_ID);


# ---------------------------------------
# Table 'Forms_Paths'
# ---------------------------------------

CREATE TABLE Forms_Paths (
	Item_ID int(11) NOT NULL,
	PathDefinition_NUM int(11) NOT NULL,
	Form_ID int(11) NOT NULL,
	FieldValue_STRING varchar(255) NOT NULL,
	LineCount_NUM smallint(4) DEFAULT '0' NOT NULL
);
CREATE INDEX Forms_PathsItem_ID ON Forms_Paths (Item_ID);


# ---------------------------------------
# Table 'Forms_Paths_Actions'
# ---------------------------------------

CREATE TABLE Forms_Paths_Actions (
	Item_ID int(11) NOT NULL,
	PathDefinition_NUM int(11) NOT NULL,
	Action_NUM smallint(4) NOT NULL,
	Action_STRING varchar(255) NOT NULL,
	RunSchedule_STRING varchar(255) NOT NULL,
	RunOnce_CHAR char(1) NOT NULL
);
CREATE INDEX Forms_Paths_ActionsItemPath ON Forms_Paths_Actions (Item_ID, PathDefinition_NUM);


# ---------------------------------------
# Table 'Forms_Paths_Details'
# ---------------------------------------

CREATE TABLE Forms_Paths_Details (
	Item_ID int(11) NOT NULL,
	PathDefinition_NUM int(11) NOT NULL,
	PathDefinitionSequence_NUM smallint DEFAULT '0' NOT NULL,
	PathComment_STRING varchar(255) DEFAULT 'No Description' NOT NULL,
	PathDisabled_NUM smallint(4) DEFAULT '0' NOT NULL
);
CREATE INDEX Forms_Paths_DetailsItemPath ON Forms_Paths_Details (Item_ID, PathDefinition_NUM);


# ---------------------------------------
# Table 'Forms_Paths_Special'
# ---------------------------------------

CREATE TABLE Forms_Paths_Special (
	Item_ID int(11) NOT NULL,
	PathDefinition_NUM int(11) NOT NULL,
	Condition_NUM int(11) NOT NULL,
	Form_ID int(11) NOT NULL,
	FieldValue_STRING varchar(255) NOT NULL,
	Comparison_OP char(2) NOT NULL
);
CREATE INDEX Forms_Paths_SpecialItemPath ON Forms_Paths_Special (Item_ID, PathDefinition_NUM);


# ---------------------------------------
# Table 'Forms_Rules'
# ---------------------------------------

CREATE TABLE Forms_Rules (
	Rule_ID int(11) NOT NULL,
	RuleDefinition_NUM int(11) NOT NULL,
	Form_ID int(11) NOT NULL,
	FieldValue_STRING varchar(255) NOT NULL,
	LineCount_NUM smallint(4) DEFAULT '0' NOT NULL
);
CREATE INDEX Forms_RulesRule_ID ON Forms_Rules (Rule_ID);


# ---------------------------------------
# Table 'Forms_Rules_Details'
# ---------------------------------------

CREATE TABLE Forms_Rules_Details (
	Rule_ID int(11) NOT NULL,
	RuleDefinition_NUM int(11) NOT NULL,
	RuleComment_STRING varchar(255) NOT NULL
);
CREATE INDEX Forms_Rules_DetailsRuleDefinition ON Forms_Rules_Details (Rule_ID, RuleDefinition_NUM);


# ---------------------------------------
# Table 'Forms_Rules_Special'
# ---------------------------------------

CREATE TABLE Forms_Rules_Special (
	Rule_ID int(11) NOT NULL,
	RuleDefinition_NUM int(11) NOT NULL,
	Condition_NUM int(11) NOT NULL,
	Form_ID int(11) NOT NULL,
	FieldValue_STRING varchar(255) NOT NULL,
	Comparison_OP char(2) NOT NULL
);
CREATE INDEX Forms_Rules_SpecialRuleDefinition ON Forms_Rules_Special (Rule_ID, RuleDefinition_NUM);


# ---------------------------------------
# Table 'Forms_Texts'
# ---------------------------------------

CREATE TABLE Forms_Texts (
	Text_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	Form_ID int(11) NOT NULL,
	FieldValue_STRING varchar(255) NOT NULL,
	LineCount_NUM smallint(4) DEFAULT '0' NOT NULL
);
CREATE INDEX Forms_TextsText_ID ON Forms_Texts (Text_ID);


# ---------------------------------------
# Table 'Forms_Texts_Details'
# ---------------------------------------

CREATE TABLE Forms_Texts_Details (
	Text_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	TextComment_STRING varchar(255) NOT NULL
);
CREATE INDEX Forms_Texts_DetailsText_ID ON Forms_Texts_Details (Text_ID);


# ---------------------------------------
# Table 'Forms_Texts_Special'
# ---------------------------------------

CREATE TABLE Forms_Texts_Special (
	Text_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	Condition_NUM int(11) NOT NULL,
	Form_ID int(11) NOT NULL,
	FieldValue_STRING varchar(255) NOT NULL,
	Comparison_OP char(2) NOT NULL
);
CREATE INDEX Forms_Texts_SpecialText_ID ON Forms_Texts_Special (Text_ID);


# ---------------------------------------
# Table 'Forms_Views'
# ---------------------------------------

CREATE TABLE Forms_Views (
	Item_ID int(11) NOT NULL,
	ViewDefinition_NUM int(11) NOT NULL,
	Form_ID int(11) NOT NULL,
	FieldValue_STRING varchar(255) NOT NULL,
	LineCount_NUM smallint(4) DEFAULT '0' NOT NULL
);
CREATE INDEX Forms_ViewsItemView ON Forms_Views (Item_ID, ViewDefinition_NUM);


# ---------------------------------------
# Table 'Forms_Views_Definitions'
# ---------------------------------------

CREATE TABLE Forms_Views_Definitions (
	Item_ID int(11) NOT NULL,
	ViewDefinition_NUM int(11) NOT NULL,
	ViewInclExcl_NUM smallint(6) NOT NULL,
	ViewDefinitionCode_CHAR varchar(2) NOT NULL,
	ViewDefinitionSequence_NUM smallint(6) NOT NULL,
	ViewValues_STRING varchar(255) NOT NULL,
	Start_DATE datetime DEFAULT NULL,
	End_DATE datetime DEFAULT NULL,
	DateRange_NUM smallint(6) NOT NULL 
);
CREATE INDEX Forms_Views_Definitions_CriteriaViewDefinition ON Forms_Views_Definitions (Item_ID, ViewDefinition_NUM);


# ---------------------------------------
# Table 'Forms_Views_Details'
# ---------------------------------------

CREATE TABLE Forms_Views_Details (
	Item_ID int(11) NOT NULL,
	ViewDefinition_NUM int(11) NOT NULL,
	ViewComment_STRING varchar(255) DEFAULT 'No Description' NOT NULL,
	ViewDisabled_NUM smallint(4) DEFAULT '0' NOT NULL
);
CREATE INDEX Forms_Views_DetailsItemView ON Forms_Views_Details (Item_ID, ViewDefinition_NUM);


# ---------------------------------------
# Table 'Forms_Views_Special'
# ---------------------------------------

CREATE TABLE Forms_Views_Special (
	Item_ID int(11) NOT NULL,
	ViewDefinition_NUM int(11) NOT NULL,
	Condition_NUM int(11) NOT NULL,
	Form_ID int(11) NOT NULL,
	FieldValue_STRING varchar(255) NOT NULL,
	Comparison_OP char(2) NOT NULL
);
CREATE INDEX Forms_Views_SpecialItemView ON Forms_Views_Special (Item_ID, ViewDefinition_NUM);


# ---------------------------------------
# Table 'Impressions'
# ---------------------------------------

CREATE TABLE Impressions (
	Profile_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	Impression_DATE datetime NOT NULL,
	ImpressionExpiry_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX ImpressionsProfile_ID ON Impressions (Profile_ID);
CREATE INDEX ImpressionsItem_ID ON Impressions (Item_ID);


# ---------------------------------------
# Table 'Instance'
# ---------------------------------------

CREATE TABLE Instance (
	Instance_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	InstanceCode_STRING varchar(255) NOT NULL,
	Item_ID int(11) NOT NULL,
	FieldGroup_STRING varchar(255) NOT NULL,
	Status_NUM int(11) NOT NULL,
	Entry_DATE datetime NOT NULL,
	LastModified_DATE datetime NOT NULL,
	Expiry_DATE datetime NOT NULL
);
CREATE INDEX InstanceItem_ID ON Instance (Item_ID);


# ---------------------------------------
# Table 'Languages'
# ---------------------------------------

CREATE TABLE Languages (
	Locale_ID char(10) NOT NULL PRIMARY KEY,
	Description_STRING varchar(255) NOT NULL
);


# ---------------------------------------
# Table 'Links'
# ---------------------------------------

CREATE TABLE Links (
	Item_ID int(11) NOT NULL,
	LinkType_CHAR char(1) NOT NULL,
	Link_URL varchar(255) NOT NULL,
	TeaserTitle_STRING varchar(255) NOT NULL,
	Teaser_PIC varchar(255) NOT NULL,
	Teaser_BLOB blob NULL
);
CREATE INDEX LinksItem_ID ON Links (Item_ID);


# ---------------------------------------
# Table 'Mails'
# ---------------------------------------

CREATE TABLE Mails (
	Mail_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	Campaign_ID int(11) NOT NULL,
	From_STRING varchar(255) NULL,
	FromEmail_STRING varchar(255) NULL,
	CCTo_STRING varchar(255) NULL,
	ReplyTo_STRING varchar(255) NULL,
	MailSubject_STRING varchar(255) NOT NULL,
	MailText_STRING blob NULL,
	MailHTML_STRING blob NULL,
	OfflineImages_NUM smallint(4) NOT NULL,
	Priority_NUM smallint(4) NOT NULL,
	AttachedFile1_STRING varchar(255) NOT NULL,
	AttachedFile2_STRING varchar(255) NOT NULL,
	AttachedFile3_STRING varchar(255) NOT NULL,
	AttachedFile4_STRING varchar(255) NOT NULL,
	AttachedFile5_STRING varchar(255) NOT NULL,
	AttachedType1_STRING varchar(255) NOT NULL,
	AttachedType2_STRING varchar(255) NOT NULL,
	AttachedType3_STRING varchar(255) NOT NULL,
	AttachedType4_STRING varchar(255) NOT NULL,
	AttachedType5_STRING varchar(255) NOT NULL,
	AttachedID1_STRING varchar(255) NOT NULL,
	AttachedID2_STRING varchar(255) NOT NULL,
	AttachedID3_STRING varchar(255) NOT NULL,
	AttachedID4_STRING varchar(255) NOT NULL,
	AttachedID5_STRING varchar(255) NOT NULL,
	SendStarted_DATE datetime NOT NULL,
	SendCompleted_DATE datetime NOT NULL,
	SendVolume_NUM int(11) NOT NULL,
	OpenVolume_NUM int(11) NOT NULL,
	BouncedVolume_NUM int(11) NOT NULL,
	CurrentStatus_NUM smallint(4) NOT NULL
);
CREATE INDEX MailsCampaign_ID ON Mails (Campaign_ID);


# ---------------------------------------
# Table 'Mails_Bounce'
# ---------------------------------------

CREATE TABLE Mails_Bounce (
	Mail_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL,
	Subject_STRING varchar(255) NOT NULL,
	ReasonCode_NUM smallint(4) NOT NULL,
	Bounced_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX Mails_BounceMail_ID ON Mails_Bounce (Mail_ID);
CREATE INDEX Mails_BounceProfile_ID ON Mails_Bounce (Profile_ID);
CREATE INDEX Mails_BounceReference_ID ON Mails_Bounce (Reference_ID);


# ---------------------------------------
# Table 'Mails_Clicks'
# ---------------------------------------

CREATE TABLE Mails_Clicks (
	Mail_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL,
	Dest_URL varchar(255) NOT NULL,
	Click_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX Mails_ClicksMail_ID ON Mails_Clicks (Mail_ID);
CREATE INDEX Mails_ClicksProfile_ID ON Mails_Clicks (Profile_ID);
CREATE INDEX Mails_ClicksReference_ID ON Mails_Clicks (Reference_ID);
CREATE INDEX Mails_ClicksDest_URL ON Mails_Clicks (Dest_URL(50));


# ---------------------------------------
# Table 'Mails_Custom'
# ---------------------------------------

CREATE TABLE Mails_Custom (
	Mail_ID int(11) NOT NULL,
	RecipientList_STRING varchar(255) NOT NULL
);
CREATE INDEX Mails_CustomMail_ID ON Mails_Custom (Mail_ID);


# ---------------------------------------
# Table 'Mails_Forms_Link'
# ---------------------------------------

CREATE TABLE Mails_Forms_Link (
	Mail_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	ViewDefinition_NUM int(11) NOT NULL
);
CREATE INDEX Mails_Forms_LinkMail_ID ON Mails_Forms_Link (Mail_ID);
CREATE INDEX Mails_Forms_LinkItemViewDefinition ON Mails_Forms_Link (Item_ID, ViewDefinition_NUM);


# ---------------------------------------
# Table 'Mails_Forward'
# ---------------------------------------

CREATE TABLE Mails_Forward (
	Mail_ID int NOT NULL,
	Profile_ID int NOT NULL,
	Reference_ID int NOT NULL,
	ForwardedTo_STRING varchar(255) NOT NULL,
	Forward_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX Mails_ForwardMail_ID ON Mails_Forward (Mail_ID);
CREATE INDEX Mails_ForwardProfile_ID ON Mails_Forward (Profile_ID);
CREATE INDEX Mails_ForwardReference_ID ON Mails_Forward (Reference_ID);


# ---------------------------------------
# Table 'Mails_History'
# ---------------------------------------

CREATE TABLE Mails_History (
	Mail_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	HistoryDetails_STRING varchar(255) NOT NULL,
	History_DATE datetime NOT NULL
);
CREATE INDEX Mails_HistoryMail_ID ON Mails_History (Mail_ID);
CREATE INDEX Mails_HistoryProfile_ID ON Mails_History (Profile_ID);


# ---------------------------------------
# Table 'Mails_Mode'
# ---------------------------------------

CREATE TABLE Mails_Mode (
	Mail_ID int(11) NOT NULL,
	PageMode_NUM smallint(4) NOT NULL,
	PageStyleSheet_STRING varchar(255) NOT NULL default ''
);
CREATE INDEX Mails_ModeMail_ID ON Mails_Mode (Mail_ID);


# ---------------------------------------
# Table 'Mails_Open'
# ---------------------------------------

CREATE TABLE Mails_Open (
	Mail_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL,
	Opened_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX Mails_OpenMail_ID ON Mails_Open (Mail_ID);
CREATE INDEX Mails_OpenProfile_ID ON Mails_Open (Profile_ID);
CREATE INDEX Mails_OpenReference_ID ON Mails_Open (Reference_ID);


# ---------------------------------------
# Table 'Mails_Rules_DataSource'
# ---------------------------------------

CREATE TABLE Mails_Rules_DataSource (
	Mail_ID int NOT NULL,
	Item_ID int NOT NULL
);
CREATE INDEX Mails_Rules_DataSourceMail_ID ON Mails_Rules_DataSource (Mail_ID);


# ---------------------------------------
# Table 'Mails_Rules_Link'
# ---------------------------------------

CREATE TABLE Mails_Rules_Link (
	Mail_ID int(11) NOT NULL,
	Rule_ID int(11) NOT NULL,
	RuleRank_NUM int(11) NOT NULL
);
CREATE INDEX Mails_Rules_LinkMail_ID ON Mails_Rules_Link (Mail_ID);


# ---------------------------------------
# Table 'Mails_Sent'
# ---------------------------------------

CREATE TABLE Mails_Sent (
	Mail_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL,
	Recipient_STRING varchar(255) NOT NULL,
	Subject_STRING varchar(255) NOT NULL,
	Sent_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX Mails_SentMail_ID ON Mails_Sent (Mail_ID);
CREATE INDEX Mails_SentProfile_ID ON Mails_Sent (Profile_ID);
CREATE INDEX Mails_SentReference_ID ON Mails_Sent (Reference_ID);


# ---------------------------------------
# Table 'Mails_SQL'
# ---------------------------------------

CREATE TABLE Mails_SQL (
	Mail_ID int(11) NOT NULL,
	SQL_STRING blob NULL
);
CREATE INDEX Mails_SQLMail_ID ON Mails_SQL (Mail_ID);


# ---------------------------------------
# Table 'Mails_Unsubscribe'
# ---------------------------------------

CREATE TABLE Mails_Unsubscribe (
	Mail_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL,
	Unsubscribed_DATE datetime NOT NULL,
	UnsubscribedReason_STRING varchar(255) NOT NULL,
	Comment_BLOB text NULL
) ENGINE = innodb;
CREATE INDEX Mails_UnsubscribeMail_ID ON Mails_Unsubscribe (Mail_ID);
CREATE INDEX Mails_UnsubscribeProfile_ID ON Mails_Unsubscribe (Profile_ID);
CREATE INDEX Mails_UnsubscribeReference_ID ON Mails_Unsubscribe (Reference_ID);


# ---------------------------------------
# Table 'Media'
# ---------------------------------------

CREATE TABLE Media (
	Media_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	Campaign_ID int(11) NOT NULL,
	MediaDescription_STRING varchar(255) NOT NULL,
	MediaType_CHAR char(1) NOT NULL,
	MediaStatus_NUM smallint(4) NOT NULL,
	Filename_STRING varchar(255) NOT NULL,
	ContentType_STRING varchar(255) NOT NULL,
	AltText_STRING varchar(255) NOT NULL,
	Link_URL varchar(255) NOT NULL,
	HPosition_NUM smallint(4) NOT NULL,
	VPosition_NUM smallint(4) NOT NULL,
	WSize_NUM smallint(4) NOT NULL,
	HSize_NUM smallint(4) NOT NULL,
	TargetSection_STRING varchar(255) NOT NULL,
	TargetArea_STRING varchar(255) NOT NULL,
	TargetTimeSpace_STRING varchar(255) NOT NULL,
	DisplayFrequency_NUM int(11) NOT NULL,
	DisplayCount_NUM int(11) NOT NULL
);
CREATE INDEX MediaCampaign_ID ON Media (Campaign_ID);


# ---------------------------------------
# Table 'Messages'
# ---------------------------------------

CREATE TABLE Messages (
	Message_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	BaseMessage_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	Folder_ID int(11) NOT NULL,
	FromProfile_ID int(11) NOT NULL,
	ToProfile_ID int(11) NOT NULL,
	MessageSubject_STRING varchar(255) NOT NULL,
	MessageBody_STRING blob NULL,
	MessageStatus_NUM smallint(4) NOT NULL,
	MessageViewed_NUM int(11) NOT NULL,
	MessageSent_DATE datetime NOT NULL,
	MessageRead_DATE datetime NOT NULL,
	Expiry_DATE datetime NOT NULL
);
CREATE INDEX MessagesBaseMessage_ID ON Messages (BaseMessage_ID);
CREATE INDEX MessagesItem_ID ON Messages (Item_ID);


# ---------------------------------------
# Table 'OAuth'
# ---------------------------------------

CREATE TABLE OAuth (
	Application_STRING varchar(50) NOT NULL default '',
	Token_STRING varchar(255) NOT NULL,
	TokenSecret_STRING varchar(50) NOT NULL,
	ServiceType_NUM tinyint(4) NOT NULL,
	ServiceName_STRING varchar(100) NULL,
	ServiceAssigned_ID varchar(50) NULL,
	Parameters_STRING varchar(255) NULL,
	LastSynch_DATE datetime NULL
);
CREATE INDEX OAuthApplication ON OAuth (Application_STRING);
CREATE INDEX OAuthService ON OAuth (ServiceType_NUM, ServiceAssigned_ID);


# ---------------------------------------
# Table 'OAuth_Permissions'
# ---------------------------------------

CREATE TABLE OAuth_Permissions (
	Profile_ID int(11) NOT NULL DEFAULT '0',
	Group_ID int(11) NOT NULL DEFAULT '0',
	Service_ID varchar(255) NOT NULL DEFAULT '0',
	PermissionClass_NUM smallint(4) NOT NULL DEFAULT '0',
	PermissionLevel_NUM smallint(4) NOT NULL DEFAULT '0',
	Expiry_DATE datetime NULL
);
CREATE INDEX OAuth_PermissionsProfile_ID ON OAuth_Permissions (Profile_ID);
CREATE INDEX OAuth_PermissionsService_ID ON OAuth_Permissions (Service_ID(50));


# ---------------------------------------
# Table 'Options'
# ---------------------------------------

CREATE TABLE Options (
	Option_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	Form_ID int(11) NOT NULL,
	OptionValue_STRING varchar(255) NOT NULL,
	OptionOrder_NUM smallint(4) NOT NULL,
	Action_STRING varchar(255) NOT NULL
);
CREATE INDEX OptionsForm_ID ON Options (Form_ID);


# ---------------------------------------
# Table 'Pages'
# ---------------------------------------

CREATE TABLE Pages (
	Page_STRING varchar(255) NOT NULL,
	PageTitle_STRING varchar(255) NOT NULL,
	PageKeywords_STRING varchar(255) NOT NULL,
	PageDescription_STRING varchar(255) NOT NULL,
	PageType_CHAR char(1) NOT NULL,
	PageTheme_STRING varchar(255) NOT NULL,
	AccessLevel_NUM smallint(4) DEFAULT '-2' NOT NULL,
	ShowTop_NUM smallint(4) NOT NULL,
	ShowBottom_NUM smallint(4) NOT NULL,
	ShowLeft_NUM smallint(4) NOT NULL,
	ShowRight_NUM smallint(4) NOT NULL,
	HeaderFile_STRING varchar(255) NOT NULL,
	FooterFile_STRING varchar(255) NOT NULL
);
CREATE INDEX PagesPage_STRING ON Pages (Page_STRING);


# ---------------------------------------
# Table 'Pages_Keywords'
# ---------------------------------------

CREATE TABLE Pages_Keywords (
	Page_STRING varchar(255) NOT NULL,
	Word_STRING varchar(255) NOT NULL,
	Score_NUM float(10,2) DEFAULT '0.00' NOT NULL
);
CREATE INDEX Pages_KeywordsPage_STRING ON Pages_Keywords (Page_STRING);


# ---------------------------------------
# Table 'Permissions'
# ---------------------------------------

CREATE TABLE Permissions (
	Profile_ID int(11) NOT NULL,
	Group_ID int(11) NOT NULL,
	Category_ID int(11) DEFAULT '-99' NOT NULL,
	PermissionClass_NUM smallint(4) NOT NULL,
	PermissionLevel_NUM smallint(4) NOT NULL,
	Expiry_DATE datetime NOT NULL
);
CREATE INDEX PermissionsProfile_ID ON Permissions (Profile_ID);


# ---------------------------------------
# Table 'Preferences'
# ---------------------------------------

CREATE TABLE Preferences (
	Profile_ID int(11) NOT NULL,
	Category_ID int(11) NOT NULL,
	KeyName_STRING varchar(255) NOT NULL,
	KeyValue_STRING varchar(255) NOT NULL,
	Expiry_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX PreferencesProfile_ID ON Preferences (Profile_ID);


# ---------------------------------------
# Table 'Profiles'
# ---------------------------------------

CREATE TABLE Profiles (
	Profile_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	Login_STRING varchar(40) NOT NULL,
	Password_STRING varchar(24) NOT NULL,
	UserName_STRING varchar(25) NOT NULL,
	KeepAlive_NUM smallint(4) NOT NULL,
	FirstVisit_DATE datetime NOT NULL,
	PreviousVisit_DATE datetime NULL,
	LastVisit_DATE datetime NOT NULL,
	Expiry_DATE datetime NOT NULL,
	Visits_NUM int(11) NOT NULL,
	Status_NUM smallint(4) NOT NULL,
	LoyaltyPoints_NUM int(11) NOT NULL,
	LastIP_STRING varchar(32) NOT NULL,
	LastAgent_STRING varchar(255) NOT NULL,
	LastLanguage_STRING varchar(255) NOT NULL,
	LastReferer_STRING varchar(255) NOT NULL,
	LastSupportsJava_NUM smallint(4) NOT NULL,
	LastSupportsJavascript_NUM smallint(4) NOT NULL,
	LastSupportsFlash_NUM smallint(4) NOT NULL,
	LastSupportsFlashVersion_NUM float NOT NULL,
	LastScreenWidth_NUM smallint(4) NOT NULL,
	LastScreenHeight_NUM smallint(4) NOT NULL,
	LastBrowserWidth_NUM smallint(4) NOT NULL,
	LastBrowserHeight_NUM smallint(4) NOT NULL,
	LastPixelDepth_NUM smallint(4) NOT NULL,
	LastBrowserTimezone_NUM smallint(4) NOT NULL
) ENGINE = innodb;
CREATE INDEX ProfilesProfileLogin ON Profiles (Profile_ID, Login_STRING);
CREATE INDEX ProfilesLoginPasswordStatus ON Profiles (Login_STRING, Password_STRING, Status_NUM);
CREATE INDEX ProfilesStatus ON Profiles (Status_NUM);
CREATE INDEX ProfilesLastIP ON Profiles (LastIP_STRING);
CREATE INDEX ProfilesLastLanguage ON Profiles (LastLanguage_STRING(10));


# ---------------------------------------
# Table 'Profiles_Catalog_Link'
# ---------------------------------------

CREATE TABLE Profiles_Catalog_Link (
	Profile_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	Score_NUM float(10,2) DEFAULT '0.00' NOT NULL
);
CREATE INDEX Profiles_Catalog_LinkProfile_ID ON Profiles_Catalog_Link (Profile_ID);
CREATE INDEX Profiles_Catalog_LinkItem_ID ON Profiles_Catalog_Link (Item_ID);


# ---------------------------------------
# Table 'Profiles_History'
# ---------------------------------------

CREATE TABLE Profiles_History (
	Profile_ID int(11) NOT NULL,
	LastIP_STRING varchar(32) NOT NULL,
	LastIPCountry_STRING varchar(10) NULL,
	LastIPRegion_STRING varchar(128) NULL,
	LastIPCity_STRING varchar(128) NULL,
	LastIPLatitude_NUM float NULL,
	LastIPLongitude_NUM float NULL,
	LastBrowser_STRING varchar(255) NOT NULL,
	LastBrowserVersion_STRING varchar(255) NOT NULL,
	LastBrowserSub_STRING varchar(255) NOT NULL,
	LastPlatform_STRING varchar(255) NOT NULL,
	LastPlatformVersion_STRING varchar(255) NOT NULL,
	LastPlatformSub_STRING varchar(255) NOT NULL,
	LastDevice_STRING varchar(255) NOT NULL DEFAULT 'Desktop PC',
	LastDeviceVersion_STRING varchar(255) NOT NULL,
	LastAgent_STRING varchar(255) NOT NULL,
	LastLanguage_STRING varchar(255) NOT NULL,
	LastReferer_STRING varchar(255) NOT NULL,
	RefererSite_STRING varchar(255) NOT NULL,
	RefererType_NUM smallint(4) NOT NULL,
	RefererSearchTerms_STRING varchar(255) NOT NULL,
	RefererHost_STRING varchar(255) NOT NULL,
	LastAcceptsCookies_NUM smallint(4) NOT NULL,
	LastSupportsJava_NUM smallint(4) NULL,
	LastSupportsJavascript_NUM smallint(4) NULL,
	LastSupportsFlash_NUM smallint(4) NULL,
	LastSupportsFlashVersion_NUM float NULL,
	LastScreenWidth_NUM smallint(4) NULL,
	LastScreenHeight_NUM smallint(4) NULL,
	LastBrowserWidth_NUM smallint(4) NULL,
	LastBrowserHeight_NUM smallint(4) NULL,
	LastPixelDepth_NUM smallint(4) NULL,
	LastBrowserTimezone_NUM smallint(4) NULL,
	Visit_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX Profiles_HistoryProfile_ID ON Profiles_History (Profile_ID);
CREATE INDEX Profiles_HistoryRefererSiteSearchTerms ON Profiles_History (RefererSite_STRING(30), RefererSearchTerms_STRING(30));
CREATE INDEX Profiles_HistoryRefererHost ON Profiles_History (RefererHost_STRING(30));
CREATE INDEX Profiles_HistoryRefererSearchTermsSite ON Profiles_History (RefererSearchTerms_STRING(30), RefererSite_STRING(30));
CREATE INDEX Profiles_HistoryRefererTypeSite ON Profiles_History (RefererType_NUM, RefererSite_STRING(30));
CREATE INDEX Profiles_HistoryLastIPCountryRegionCity ON Profiles_History (LastIPCountry_STRING(3), LastIPRegion_STRING(30), LastIPCity_STRING(30));
CREATE INDEX Profiles_HistoryLastDevice ON Profiles_History (LastDevice_STRING(20));
CREATE INDEX Profiles_HistoryLastIP ON Profiles_History (LastIP_STRING(20));
CREATE INDEX Profiles_HistoryLastLanguage ON Profiles_History (LastLanguage_STRING(10));


# ---------------------------------------
# Table 'Profiles_Rules_Link'
# ---------------------------------------

CREATE TABLE Profiles_Rules_Link (
	Profile_ID int(11) NOT NULL,
	Rule_ID int(11) NOT NULL,
	RuleRank_NUM int(11) NOT NULL
);
CREATE INDEX Profiles_Rules_LinkProfile_ID ON Profiles_Rules_Link (Profile_ID);


# ---------------------------------------
# Table 'Profiles_Values'
# ---------------------------------------

CREATE TABLE Profiles_Values (
	Form_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL,
	FieldValue_STRING varchar(255) NOT NULL
) ENGINE = innodb;
CREATE INDEX Profiles_ValuesForm_ID ON Profiles_Values (Form_ID);
CREATE INDEX Profiles_ValuesProfile_ID ON Profiles_Values (Profile_ID);
CREATE INDEX Profiles_ValuesReference_ID ON Profiles_Values (Reference_ID);


# ---------------------------------------
# Table 'Profiles_Values_Extra'
# ---------------------------------------

CREATE TABLE Profiles_Values_Extra (
	Form_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL,
	FieldValueExtra_BLOB blob NULL
) ENGINE = innodb;
CREATE INDEX Profiles_Values_ExtraForm_ID ON Profiles_Values_Extra (Form_ID);
CREATE INDEX Profiles_Values_ExtraProfile_ID ON Profiles_Values_Extra (Profile_ID);
CREATE INDEX Profiles_Values_ExtraReference_ID ON Profiles_Values_Extra (Reference_ID);


# ---------------------------------------
# Table 'Redirects'
# ---------------------------------------

CREATE TABLE Redirects (
	Redirect_ID int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	RedirectPath_STRING varchar(255) NOT NULL,
	Prev_URL varchar(255) NOT NULL,
	Next_URL varchar(255) NOT NULL,
	RedirectDisabled_NUM smallint(6) NOT NULL DEFAULT '0'
);
CREATE INDEX RedirectsRedirectPath ON Redirects (RedirectPath_STRING(30));


# ---------------------------------------
# Table 'Reference'
# ---------------------------------------

CREATE TABLE Reference (
	Reference_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	ReferenceCode_STRING varchar(255) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	FieldGroup_STRING varchar(255) NOT NULL,
	Status_NUM int(11) NOT NULL,
	Entry_DATE datetime NOT NULL,
	LastModified_DATE datetime NOT NULL,
	Expiry_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX ReferenceProfile_ID ON Reference (Profile_ID);
CREATE INDEX ReferenceItem_ID ON Reference (Item_ID);
CREATE INDEX ReferenceStatusItem ON Reference (Status_NUM, Item_ID);


# ---------------------------------------
# Table 'Reference_Relationships'
# ---------------------------------------

CREATE TABLE Reference_Relationships (
	KeyItem_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	Score_NUM float(10,2) DEFAULT '0.00' NOT NULL
);
CREATE INDEX Reference_RelationshipsKeyItem_ID ON Reference_Relationships (KeyItem_ID);


# ---------------------------------------
# Table 'Responses'
# ---------------------------------------

CREATE TABLE Responses (
	Profile_ID int(11) NOT NULL,
	Campaign_ID int(11) NOT NULL,
	Media_ID int(11) NOT NULL,
	ResponseType_CHAR char(1) NOT NULL,
	Response_DATE datetime NOT NULL
);
CREATE INDEX ResponsesProfile_ID ON Responses (Profile_ID);


# ---------------------------------------
# Table 'Rules'
# ---------------------------------------

CREATE TABLE Rules (
	Rule_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	RuleGroup_ID int(11) NOT NULL default '0',
	RuleDescription_STRING varchar(255) NOT NULL,
	RuleDisabled_NUM smallint(4) DEFAULT '0' NOT NULL,
	LastCached_DATE datetime DEFAULT '1970-01-01 00:00:00' NOT NULL
);
CREATE INDEX RulesRuleGroup_ID ON Rules (RuleGroup_ID);


# ---------------------------------------
# Table 'Rules_Definitions'
# ---------------------------------------

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


# ---------------------------------------
# Table 'Rules_Groups'
# ---------------------------------------

CREATE TABLE Rules_Groups (
	RuleGroup_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	RuleGroupName_STRING varchar(255) NOT NULL,
	RuleGroupDescription_STRING varchar(255) NOT NULL
);


# ---------------------------------------
# Table 'Rules_Groups_Permissions'
# ---------------------------------------

CREATE TABLE Rules_Groups_Permissions (
	Profile_ID int(11) NOT NULL,
	Group_ID int(11) NOT NULL,
	RuleGroup_ID int(11) NOT NULL,
	PermissionClass_NUM smallint(4) NOT NULL,
	PermissionLevel_NUM smallint(4) NOT NULL,
	Expiry_DATE datetime NOT NULL
);
CREATE INDEX Rules_Groups_PermissionsProfile_ID ON Rules_Groups_Permissions (Profile_ID);


# ---------------------------------------
# Table 'Sessions'
# ---------------------------------------

CREATE TABLE Sessions (
	Session_ID varchar(32) NOT NULL PRIMARY KEY,
	Profile_ID int(11) NOT NULL,
	SessionExpiry_DATE datetime NOT NULL,
	Last_URL varchar(255) NOT NULL,
	SmartMatchKey_STRING varchar(32) NOT NULL default ''
) ENGINE = innodb;
CREATE INDEX SessionsProfile_ID ON Sessions (Profile_ID);
CREATE INDEX SessionsSmartMatchKey ON Sessions (SmartMatchKey_STRING);


# ---------------------------------------
# Table 'Shortcuts'
# ---------------------------------------

CREATE TABLE Shortcuts (
	Item_ID int(11) NOT NULL,
	Shortcut_ID int(11) NOT NULL,
	ShortcutType_CHAR char(1) NOT NULL,
	TeaserTitle_STRING varchar(255) NOT NULL,
	Teaser_PIC varchar(255) NOT NULL,
	Teaser_BLOB blob NULL
);
CREATE INDEX ShortcutsItem_ID ON Shortcuts (Item_ID);
CREATE INDEX ShortcutsShortcut_ID ON Shortcuts (Shortcut_ID);


# ---------------------------------------
# Table 'ShortURLs'
# ---------------------------------------

CREATE TABLE ShortURLs (
	Unique_STRING varchar(10) NOT NULL,
	URL_STRING varchar(255) NOT NULL
);
CREATE INDEX ShortURLsUnique ON ShortURLs (Unique_STRING);


# ---------------------------------------
# Table 'Spider_History'
# ---------------------------------------

CREATE TABLE Spider_History (
	Request_URL varchar(255) NOT NULL, 
	LastIP_STRING varchar(50) NOT NULL, 
	LastAgent_STRING varchar(255) NOT NULL, 
	Visit_DATE datetime NOT NULL
);
CREATE INDEX Spider_HistoryRequest ON Spider_History (Request_URL(50));
CREATE INDEX Spider_HistoryLastAgent ON Spider_History (LastAgent_STRING(20));


# ---------------------------------------
# Table 'Streams'
# ---------------------------------------

CREATE TABLE Streams (
	Post_ID bigint NOT NULL PRIMARY KEY auto_increment,
	Posted_DATE datetime NULL,
	PostedByProfile_ID bigint DEFAULT NULL,
	PostedMessage_BLOB text,
	ServiceType_NUM tinyint DEFAULT NULL,
	ServiceAssigned_ID varchar(255) NOT NULL,
	InReplyToServiceAssigned_ID varchar(255) NOT NULL,
	TimeSequence_ID bigint NOT NULL,
	User_ID bigint NOT NULL,
	Media_URL varchar(150) DEFAULT NULL,
	MediaShort_URL varchar(50) DEFAULT NULL,
	MediaType_CHAR char(2) DEFAULT NULL,
	StoryTitle_STRING varchar(255) DEFAULT NULL,
	StorySnippet_STRING varchar(255) DEFAULT NULL,
	StoryThumbnail_STRING varchar(255) DEFAULT NULL,
	ApprovalCount_NUM int NOT NULL,
	IsApproved_NUM tinyint NULL,
	FromService_ID varchar(255) NOT NULL,
	Status_NUM tinyint NOT NULL
) ENGINE = innodb;
CREATE UNIQUE INDEX StreamsTimeSequence_ID ON Streams (TimeSequence_ID);
CREATE INDEX StreamsPostedByProfile_ID ON Streams (PostedByProfile_ID);
CREATE INDEX StreamsUser_ID ON Streams (User_ID);
CREATE INDEX StreamsServiceAssigned_ID ON Streams (ServiceAssigned_ID, ServiceType_NUM);
CREATE INDEX StreamsServiceInReplyToServiceAssigned_ID ON Streams (InReplyToServiceAssigned_ID, ServiceType_NUM);
CREATE INDEX StreamsFromService ON Streams (FromService_ID(50));


# ---------------------------------------
# Table 'Streams_Clicks'
# ---------------------------------------

CREATE TABLE Streams_Clicks (
	Post_ID bigint NOT NULL,
	Profile_ID bigint NOT NULL,
	Click_DATE datetime DEFAULT NULL,
	Dest_URL varchar(255) NOT NULL
) ENGINE = innodb;
CREATE INDEX Streams_ClicksPost_ID ON Streams_Clicks (Post_ID);


# ---------------------------------------
# Table 'Streams_Retweets'
# ---------------------------------------

CREATE TABLE Streams_Retweets (
	Post_ID bigint NOT NULL,
	User_ID bigint NOT NULL,
	Retweet_DATE datetime NULL
) ENGINE = innodb;
CREATE INDEX Streams_RetweetsPost_ID ON Streams_Retweets (Post_ID);


# ---------------------------------------
# Table 'Streams_Tags'
# ---------------------------------------

CREATE TABLE Streams_Tags (
	Tag_ID bigint NOT NULL PRIMARY KEY auto_increment,
	Tag_STRING varchar(100) DEFAULT NULL
);
CREATE INDEX Streams_TagsTag ON Streams_Tags (Tag_STRING);


# ---------------------------------------
# Table 'Streams_Users'
# ---------------------------------------

CREATE TABLE Streams_Users (
	User_ID bigint NOT NULL PRIMARY KEY auto_increment,
	ServiceType_NUM tinyint NOT NULL,
	ServiceUser_ID varchar(64) DEFAULT NULL,
	ServiceUserPhoto_STRING varchar(100) DEFAULT NULL,
	ScreenName_STRING varchar(100) NOT NULL,
	FullName_STRING varchar(150) NOT NULL,
	FollowerSince_DATE datetime DEFAULT NULL,
	Location_STRING varchar(255) DEFAULT NULL,
	Bio_STRING varchar(255) DEFAULT NULL,
	LastFollowers_NUM bigint DEFAULT NULL,
	LastListAppearances_NUM bigint DEFAULT NULL,
	LastInfluenced_NUM float DEFAULT NULL,
	LastInfluencedCache_DATE datetime NULL,
	LastModified_DATE datetime DEFAULT NULL,
	FromService_ID varchar(255) NOT NULL
) ENGINE = innodb;
CREATE INDEX Streams_UsersScreenName ON Streams_Users (ScreenName_STRING);
CREATE INDEX Streams_UsersServiceUser_ID ON Streams_Users (ServiceType_NUM,ServiceUser_ID);


# ---------------------------------------
# Table 'Streams_Users_Tags'
# ---------------------------------------

CREATE TABLE Streams_Users_Tags (
	User_ID bigint NOT NULL,
	Tag_ID bigint NOT NULL
);
CREATE INDEX Streams_Users_Tags_Tag_ID ON Streams_Users_Tags (Tag_ID);
CREATE INDEX Streams_Users_TagsUser_ID ON Streams_Users_Tags (User_ID);


# ---------------------------------------
# Table 'Streams_Views'
# ---------------------------------------

CREATE TABLE Streams_Views (
	Profile_ID bigint NOT NULL,
	Service_ID varchar(255) NOT NULL
);
CREATE INDEX Streams_ViewsProfile_ID ON Streams_Views (Profile_ID);


# ---------------------------------------
# Table 'Suppression'
# ---------------------------------------

CREATE TABLE Suppression (
   Suppress_ID int NOT NULL PRIMARY KEY auto_increment,
   Lookup_STRING varchar(100) NOT NULL,
   LookupType_CHAR char(1) NOT NULL
) ENGINE = innodb;
CREATE INDEX SuppressionLookupType ON Suppression (Lookup_STRING, LookupType_CHAR);


# ---------------------------------------
# Table 'Temp_Admin_Keywords'
# ---------------------------------------

CREATE TABLE Temp_Admin_Keywords (
	KeyName_STRING varchar(50) NOT NULL,
	KeyValue_STRING varchar(50) NOT NULL,
	Word_STRING varchar(255) NOT NULL
);


# ---------------------------------------
# Table 'Temp_Content_Keywords'
# ---------------------------------------

CREATE TABLE Temp_Content_Keywords (
	Item_ID int(11) NOT NULL,
	Revision_STRING varchar(25) NOT NULL,
	Word_STRING varchar(255) NOT NULL
);


# ---------------------------------------
# Table 'Temp_Mails'
# ---------------------------------------

CREATE TABLE Temp_Mails (
	Mail_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL,
	Recipient_STRING varchar(255) DEFAULT '' NOT NULL,
	Process_ID varchar(32) DEFAULT '' NOT NULL
);
CREATE INDEX Temp_MailsMailProfileProcessReference ON Temp_Mails (Mail_ID, Profile_ID, Process_ID, Reference_ID);


# ---------------------------------------
# Table 'Temp_Mails_Samples'
# ---------------------------------------

CREATE TABLE Temp_Mails_Samples (
	Mail_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL
);
CREATE INDEX Temp_Mails_SamplesMail_ID ON Temp_Mails_Samples (Mail_ID);


# ---------------------------------------
# Table 'Temp_Pages_Keywords'
# ---------------------------------------

CREATE TABLE Temp_Pages_Keywords (
	Page_STRING varchar(255) NOT NULL,
	Word_STRING varchar(255) NOT NULL
);


# ---------------------------------------
# Table 'Temp_Profiles_Catalog_Link'
# ---------------------------------------

CREATE TABLE Temp_Profiles_Catalog_Link (
	Profile_ID int(11) NOT NULL,
	Score_NUM float(10,2) DEFAULT '0.00' NOT NULL,
	RuleRank_NUM int(11) NOT NULL
);


# ---------------------------------------
# Table 'Temp_Texts'
# ---------------------------------------

CREATE TABLE Temp_Texts (
	Text_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL,
	Recipient_STRING varchar(255) DEFAULT '' NOT NULL,
	Process_ID varchar(32) DEFAULT '' NOT NULL
);
CREATE INDEX Temp_TextsTextProfileProcessReference ON Temp_Texts (Text_ID, Profile_ID, Process_ID, Reference_ID);


# ---------------------------------------
# Table 'Temp_Texts_Samples'
# ---------------------------------------

CREATE TABLE Temp_Texts_Samples (
	Text_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL
);
CREATE INDEX Temp_Texts_SamplesText_ID ON Temp_Texts_Samples (Text_ID);


# ---------------------------------------
# Table 'Templates'
# ---------------------------------------

CREATE TABLE Templates (
	Template_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	LinkItem_ID int(11) NOT NULL,
	TemplateDescription_STRING varchar(255) NOT NULL,
	TemplateType_NUM smallint(4) NOT NULL,
	ThumbnailImage_STRING varchar(255) NOT NULL,
	Filename_STRING varchar(255) NOT NULL,
	ContentType_STRING varchar(255) NOT NULL
);


# ---------------------------------------
# Table 'Terms'
# ---------------------------------------

CREATE TABLE Terms (
	Profile_ID int(11) NOT NULL,
	SearchTerm_STRING varchar(255) NOT NULL,
	Search_DATE datetime NOT NULL,
	SearchExpiry_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX TermsProfile_ID ON Terms (Profile_ID);
CREATE INDEX TermsSearchTerm ON Terms (SearchTerm_STRING(50));


# ---------------------------------------
# Table 'Texts'
# ---------------------------------------

CREATE TABLE Texts (
	Text_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	Campaign_ID int(11) NOT NULL,
	From_STRING varchar(255) NOT NULL,
	FromMobile_STRING varchar(255) NOT NULL,
	TextSubject_STRING varchar(255) NOT NULL,
	TextBody_STRING blob NULL,
	SendStarted_DATE datetime NOT NULL,
	SendCompleted_DATE datetime NOT NULL,
	SendVolume_NUM int(11) NOT NULL,
	OpenVolume_NUM int(11) NOT NULL,
	BouncedVolume_NUM int(11) NOT NULL,
	CurrentStatus_NUM smallint(4) NOT NULL
);
CREATE INDEX TextsCampaign_ID ON Texts (Campaign_ID);


# ---------------------------------------
# Table 'Texts_Bounce'
# ---------------------------------------

CREATE TABLE Texts_Bounce (
	Text_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL,
	Subject_STRING varchar(255) NOT NULL,
	ReasonCode_NUM smallint(4) NOT NULL,
	Bounced_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX Texts_BounceText_ID ON Texts_Bounce (Text_ID);
CREATE INDEX Texts_BounceProfile_ID ON Texts_Bounce (Profile_ID);
CREATE INDEX Texts_BounceReference_ID ON Texts_Bounce (Reference_ID);


# ---------------------------------------
# Table 'Texts_Custom'
# ---------------------------------------

CREATE TABLE Texts_Custom (
	Text_ID int(11) NOT NULL,
	RecipientList_STRING varchar(255) NOT NULL
);
CREATE INDEX Texts_CustomText_ID ON Texts_Custom (Text_ID);


# ---------------------------------------
# Table 'Texts_Forms_Link'
# ---------------------------------------

CREATE TABLE Texts_Forms_Link (
	Text_ID int(11) NOT NULL,
	Item_ID int(11) NOT NULL,
	ViewDefinition_NUM int(11) NOT NULL
);
CREATE INDEX Texts_Forms_LinkText_ID ON Texts_Forms_Link (Text_ID);
CREATE INDEX Texts_Forms_LinkItemViewDefinition ON Texts_Forms_Link (Item_ID, ViewDefinition_NUM);


# ---------------------------------------
# Table 'Texts_Forward'
# ---------------------------------------

CREATE TABLE Texts_Forward (
	Text_ID int NOT NULL,
	Profile_ID int NOT NULL,
	Reference_ID int NOT NULL,
	ForwardedTo_STRING varchar(255) NOT NULL,
	Forward_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX Texts_ForwardText_ID ON Texts_Forward (Text_ID);
CREATE INDEX Texts_ForwardProfile_ID ON Texts_Forward (Profile_ID);
CREATE INDEX Texts_ForwardReference_ID ON Texts_Forward (Reference_ID);


# ---------------------------------------
# Table 'Texts_History'
# ---------------------------------------

CREATE TABLE Texts_History (
	Text_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	HistoryDetails_STRING varchar(255) NOT NULL,
	History_DATE datetime NOT NULL
);
CREATE INDEX Texts_HistoryText_ID ON Texts_History (Text_ID);
CREATE INDEX Texts_HistoryProfile_ID ON Texts_History (Profile_ID);


# ---------------------------------------
# Table 'Texts_Open'
# ---------------------------------------

CREATE TABLE Texts_Open (
	Text_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL,
	Opened_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX Texts_OpenText_ID ON Texts_Open (Text_ID);
CREATE INDEX Texts_OpenProfile_ID ON Texts_Open (Profile_ID);
CREATE INDEX Texts_OpenReference_ID ON Texts_Open (Reference_ID);


# ---------------------------------------
# Table 'Texts_Responses'
# ---------------------------------------

CREATE TABLE Texts_Responses (
	Text_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL,
	Response_STRING varchar(255) NOT NULL,
	Response_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX Texts_ResponsesText_ID ON Texts_Responses (Text_ID);
CREATE INDEX Texts_ResponsesProfile_ID ON Texts_Responses (Profile_ID);
CREATE INDEX Texts_ResponsesReference_ID ON Texts_Responses (Reference_ID);
CREATE INDEX Texts_ResponsesResponse ON Texts_Responses (Response_STRING(50));


# ---------------------------------------
# Table 'Texts_Rules_DataSource'
# ---------------------------------------

CREATE TABLE Texts_Rules_DataSource (
	Text_ID int NOT NULL,
	Item_ID int NOT NULL
);
CREATE INDEX Texts_Rules_DataSourceText_ID ON Texts_Rules_DataSource (Text_ID);


# ---------------------------------------
# Table 'Texts_Rules_Link'
# ---------------------------------------

CREATE TABLE Texts_Rules_Link (
	Text_ID int(11) NOT NULL,
	Rule_ID int(11) NOT NULL,
	RuleRank_NUM int(11) NOT NULL
);
CREATE INDEX Texts_Rules_LinkText_ID ON Texts_Rules_Link (Text_ID);


# ---------------------------------------
# Table 'Texts_Sent'
# ---------------------------------------

CREATE TABLE Texts_Sent (
	Text_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL,
	Recipient_STRING varchar(255) NOT NULL,
	Subject_STRING varchar(255) NOT NULL,
	Sent_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX Texts_SentText_ID ON Texts_Sent (Text_ID);
CREATE INDEX Texts_SentProfile_ID ON Texts_Sent (Profile_ID);
CREATE INDEX Texts_SentReference_ID ON Texts_Sent (Reference_ID);


# ---------------------------------------
# Table 'Texts_SQL'
# ---------------------------------------

CREATE TABLE Texts_SQL (
	Text_ID int(11) NOT NULL,
	SQL_STRING blob NULL
);
CREATE INDEX Texts_SQLText_ID ON Texts_SQL (Text_ID);


# ---------------------------------------
# Table 'Texts_Unsubscribe'
# ---------------------------------------

CREATE TABLE Texts_Unsubscribe (
	Text_ID int(11) NOT NULL,
	Profile_ID int(11) NOT NULL,
	Reference_ID int(11) NOT NULL,
	Unsubscribed_DATE datetime NOT NULL,
	UnsubscribedReason_STRING varchar(255) NOT NULL,
	Comment_BLOB text NULL
) ENGINE = innodb;
CREATE INDEX Texts_UnsubscribeText_ID ON Texts_Unsubscribe (Text_ID);
CREATE INDEX Texts_UnsubscribeProfile_ID ON Texts_Unsubscribe (Profile_ID);
CREATE INDEX Texts_UnsubscribeReference_ID ON Texts_Unsubscribe (Reference_ID);


# ---------------------------------------
# Table 'Themes'
# ---------------------------------------

CREATE TABLE Themes (
	ThemeName_STRING varchar(255) NOT NULL,
	Theme_STRING varchar(255) NOT NULL,
	ThemeCopyright_STRING varchar(255) NOT NULL,
	ThemeDescription_STRING varchar(255) NOT NULL
);
CREATE INDEX ThemesTheme_STRING ON Themes (Theme_STRING);


# ---------------------------------------
# Table 'Transactions'
# ---------------------------------------

CREATE TABLE Transactions (
	Profile_ID int NOT NULL,
	PurchaseCategory_STRING varchar(255) NOT NULL,
	PurchaseCurrency_STRING varchar(3) NOT NULL,
	PurchasePrice_NUM float NOT NULL,
	Transaction_DATE datetime NOT NULL
) ENGINE = innodb;
CREATE INDEX TransactionsProfile_ID ON Transactions (Profile_ID);
CREATE INDEX TransactionsPurchaseCategoryCurrencyPrice ON Transactions (PurchaseCategory_STRING(50), PurchaseCurrency_STRING(3), PurchasePrice_NUM);


# ---------------------------------------
# Table 'Triggers' -- deprecated
# ---------------------------------------

CREATE TABLE Triggers (
	Trigger_ID int(11) NOT NULL PRIMARY KEY auto_increment,
	TriggerYear_STRING varchar(4) NOT NULL,
	TriggerMonth_STRING char(2) NOT NULL,
	TriggerDay_STRING char(2) NOT NULL,
	TriggerHour_STRING char(2) NOT NULL,
	TriggerMinute_STRING char(2) NOT NULL,
	TriggerWeekday_STRING char(2) NOT NULL,
	RemoveAfter_NUM smallint(4) NOT NULL,
	Action_STRING varchar(255) NOT NULL,
	LastRun_DATE datetime NULL
);


# ---------------------------------------
# Table 'URL_Impressions'
# ---------------------------------------

CREATE TABLE URL_Impressions (
	Profile_ID int NOT NULL, 
	URL_STRING varchar(255) NOT NULL, 
	Impression_DATE datetime NULL, 
	ImpressionExpiry_DATE datetime NULL
) ENGINE = innodb;
CREATE INDEX URL_ImpressionsProfile_ID ON URL_Impressions (Profile_ID);
CREATE INDEX URL_ImpressionsURL_STRING ON URL_Impressions (URL_STRING);


# ---------------------------------------
# Table 'WURFL'
# ---------------------------------------

CREATE TABLE WURFL (
	UserAgent_STRING varchar(255) NOT NULL,
	DeviceID_STRING varchar(50) NOT NULL,
	RootDeviceID_STRING varchar(50) NOT NULL,
	Capabilities_BLOB text NULL
);
CREATE INDEX WURFL_UserAgent ON WURFL (UserAgent_STRING(50));
CREATE INDEX WURFL_DeviceID ON WURFL (DeviceID_STRING(50));
CREATE INDEX WURFL_RootDeviceID ON WURFL (RootDeviceID_STRING(50));


# --------------------------------------------------------
# --------------------------------------------------------

INSERT INTO Categories (Category_ID, BaseCategory_ID, CategoryDescription_STRING, CategoryStatus_NUM, CategoryOrder_NUM, TeaserTitle_STRING, Teaser_PIC, Teaser_BLOB, FullTitle_STRING, Full_PIC, Full_BLOB, AccessLevel_NUM, OwnerProfile_ID) VALUES ('1', '-1', 'Web', '1', '0', '', '', '', '', '', '', '-2', '1');
INSERT INTO Categories (Category_ID, BaseCategory_ID, CategoryDescription_STRING, CategoryStatus_NUM, CategoryOrder_NUM, TeaserTitle_STRING, Teaser_PIC, Teaser_BLOB, FullTitle_STRING, Full_PIC, Full_BLOB, AccessLevel_NUM, OwnerProfile_ID, Path_STRING) VALUES ('2', '-1', 'Facebook', '0', '1000', 'Facebook', '', '', 'Facebook', '', '', '-2', '1', '/facebook');
INSERT INTO Categories (Category_ID, BaseCategory_ID, CategoryDescription_STRING, CategoryStatus_NUM, CategoryOrder_NUM, TeaserTitle_STRING, Teaser_PIC, Teaser_BLOB, FullTitle_STRING, Full_PIC, Full_BLOB, AccessLevel_NUM, OwnerProfile_ID, Path_STRING) VALUES ('3', '-1', 'Forms', '0', '1001', 'Forms', '', 'Forms', '', '-2', '1', '/forms');
INSERT INTO Categories (Category_ID, BaseCategory_ID, CategoryDescription_STRING, CategoryStatus_NUM, CategoryOrder_NUM, TeaserTitle_STRING, Teaser_PIC, Teaser_BLOB, FullTitle_STRING, Full_PIC, Full_BLOB, AccessLevel_NUM, OwnerProfile_ID, Path_STRING) VALUES ('4', '-1', 'Landing Pages', '0', '1002', 'Landing Pages', '', 'Landing Pages', '', '-2', '1', '/landing-pages');
UPDATE Categories SET Category_ID = 0 WHERE Category_ID = 1;

# --------------------------------------------------------
# --------------------------------------------------------

INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('0', 'No error');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('1', 'The e-mail or password you entered was incorrect');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('2', 'Your session has expired. Please login again to continue using this site.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('3', 'The e-mail address you supplied was already in use. Please choose another address.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('4', 'The item you selected was not available. Please return to the previous screen to select another item.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('5', 'The category you selected was not available. Please return to the previous screen to select another category.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('6', 'Please register to access this part of the web site.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('7', 'We had trouble sending you a confirmation e-mail. Please contact us for assistance.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('8', 'Please enter an e-mail address to continue.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('9', 'Please enter a password to continue.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('10', 'The passwords you entered do not match. Please try again.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('11', 'Your profile does not seem to exist. Please register to continue.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('12', 'Please complete all mandatory fields before submitting this form.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('13', 'We had trouble creating a profile for you. Please contact us for assistance.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('14', 'The password you typed was either too long or too short. Please enter a password with between 4 and 8 characters.'); 
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('15', 'The user name is already in use. Please select another.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('16', 'One of the user names you entered didn''t exist. Please try again.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('17', 'Please complete all fields marked as mandatory.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('18', 'Please select a user name in your profile to make use of this facility.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('19', 'Please complete this form in order to access this part of the web site.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('20', 'Please select a message.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('21', 'You are already logged in.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('22', 'Too many administrators are logged in concurrently.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('23', 'Your license key is not valid. Please re-install and try again.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('24', 'Your license key has expired.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('25', 'The password reminder feature is not available on this site.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('26', 'Your license key is not valid for this domain.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('27', 'The authorisation test was unsuccessful, please try again.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('28', 'You must be registered to complete the User Profile form.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('29', 'You have already completed the User Profile form. Please login to edit your details.');
INSERT INTO Errors (Error_NUM, Error_STRING) VALUES ('30', 'You are not authorised to edit these details.');

# --------------------------------------------------------
# --------------------------------------------------------

INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ac', 'Ascension Island');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ad', 'Andorra');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ae', 'United Arab Emirates');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('af', 'Afghanistan');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ag', 'Antigua and Barbuda');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ai', 'Anguilla');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('al', 'Albania');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('am', 'Armenia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('an', 'Netherlands Antilles');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ao', 'Angola');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('aq', 'Antarctica');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ar', 'Argentina');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('as', 'American Samoa');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('at', 'Austria');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('au', 'Australia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('aw', 'Aruba');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('az', 'Azerbaijan');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ba', 'Bosnia and Herzogovina');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('bb', 'Barbados');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('bd', 'Bangladesh');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('be', 'Belgium');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('bf', 'Burkina Faso');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('bg', 'Bulgaria');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('bh', 'Bahrain');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('bi', 'Burundi');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('bj', 'Benin');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('bm', 'Bermuda');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('bn', 'Brunei Darussalam');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('bo', 'Bolivia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('br', 'Brazil');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('bs', 'Bahamas');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('bt', 'Bhutan');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('bv', 'Bouvet Island');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('bw', 'Botswana');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('by', 'Belarus');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('bz', 'Belize');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ca', 'Canada');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('cc', 'Cocos (Keeling) Islands');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('cd', 'Congo, Democratic Republic of the');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('cf', 'Central African Republic');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('cg', 'Congo, Republic of');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ch', 'Switzerland');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ci', 'Côte d''Ivoire');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ck', 'Cook Islands');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('cl', 'Chile');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('cm', 'Cameroon');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('cn', 'China');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('co', 'Colombia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('cr', 'Costa Rica');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('cs', 'Czechoslovakia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('cu', 'Cuba');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('cv', 'Cape Verde');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('cx', 'Christmas Island');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('cy', 'Cyprus');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('cz', 'Czech Republic');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('de', 'Germany');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('dj', 'Djibouti');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('dk', 'Denmark');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('dm', 'Dominica');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('do', 'Dominican Republic');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('dz', 'Algeria');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ec', 'Ecuador');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ee', 'Estonia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('eg', 'Egypt');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('eh', 'Western Sahara');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('er', 'Eritrea');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('es', 'Spain');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('et', 'Ethopia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('fi', 'Finland');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('fj', 'Fiji');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('fk', 'Falkland Islands');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('fm', 'Micronesia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('fo', 'Faroe Islands');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('fr', 'France');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ga', 'Gabon');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('gb', 'Great Britain');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('gd', 'Grenada');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ge', 'Georgia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('gf', 'French Guiana');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('gg', 'Guernsey');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('gh', 'Ghana');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('gi', 'Gibraltar');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('gl', 'Greenland');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('gm', 'Gambia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('gn', 'Guinea');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('gp', 'Guadeloupe');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('gq', 'Equatorial Guinea');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('gr', 'Greece');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('gs', 'South Georgia/South Sandwich Islands');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('gt', 'Guatemala');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('gu', 'Guam');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('gw', 'Guinea-Bissau');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('gy', 'Guyana');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('hk', 'Hong Kong');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('hm', 'Heard and McDonald Islands');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('hn', 'Honduras');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('hr', 'Croatia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ht', 'Haiti');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('hu', 'Hungary');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('id', 'Indonesia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ie', 'Ireland');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('il', 'Israel');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('im', 'Isle of Man');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('in', 'India');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('io', 'British Indian Ocean Territory');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('iq', 'Iraq');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ir', 'Iran');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('is', 'Iceland');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('it', 'Italy');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('je', 'Jersey');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('jm', 'Jamaica');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('jo', 'Jordan');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('jp', 'Japan');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ke', 'Kenya');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('kg', 'Kyrgyzstan');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('kh', 'Cambodia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ki', 'Kiribati');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('km', 'Comoros');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('kn', 'Saint Kitts and Nevis');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('kp', 'Korea, Democratic People''s Republic of');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('kr', 'Republic of Korea');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('kw', 'Kuwait');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ky', 'Cayman Islands');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('kz', 'Kazakhstan');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('la', 'Lao People''s Democratic Republic');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('lb', 'Lebanon');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('lc', 'Saint Lucia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('li', 'Liechtenstein');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('lk', 'Sri Lanka');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('lr', 'Liberia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ls', 'Lesotho');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('lt', 'Lithuania');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('lu', 'Luxembourg');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('lv', 'Latvia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ly', 'Libyan Arab Jamahiriya');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ma', 'Morocco');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('mc', 'Monaco');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('md', 'Moldova');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('mg', 'Madagascar');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('mh', 'Marshall Islands');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('mk', 'Macedonia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ml', 'Mali');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('mm', 'Myanmar');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('mn', 'Mongolia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('mo', 'Macau');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('mp', 'Northern Mariana Islands');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('mq', 'Martinique');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('mr', 'Mauritania');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ms', 'Montserrat');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('mt', 'Malta');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('mu', 'Mauritius');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('mv', 'Maldives');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('mw', 'Malawi');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('mx', 'Mexico');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('my', 'Malaysia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('mz', 'Mozambique');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('na', 'Namibia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('nc', 'New Caledonia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ne', 'Niger');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('nf', 'Norfolk Island');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ng', 'Nigeria');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ni', 'Nicaragua');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('nl', 'Netherlands');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('no', 'Norway');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('np', 'Nepal');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('nr', 'Nauru');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('nu', 'Niue');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('nz', 'New Zealand');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('om', 'Oman');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('pa', 'Panama');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('pe', 'Peru');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('pf', 'French Polynesia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('pg', 'Papua New Guinea');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ph', 'Philippines');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('pk', 'Pakistan');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('pl', 'Poland');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('pm', 'St. Pierre and Miquelon');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('pn', 'Pitcairn');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('pr', 'Puerto Rico');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ps', 'Palestine');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('pt', 'Portugal');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('pw', 'Palau');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('py', 'Paraguay');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('qa', 'Qatar');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('re', 'Reunion');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ro', 'Romania');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ru', 'Russian Federation');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('rw', 'Rwanda');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('sa', 'Saudi Arabia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('sb', 'Solomon Islands');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('sc', 'Seychelles');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('sd', 'Sudan');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('se', 'Sweden');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('sg', 'Singapore');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('sh', 'St. Helena');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('si', 'Slovenia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('sj', 'Svalbard and Jan Mayen Islands');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('sk', 'Slovakia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('sl', 'Sierra Leone');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('sm', 'San Marino');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('sn', 'Senegal');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('so', 'Somalia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('sr', 'Surinam');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('st', 'Saint Tome and Principe');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('su', 'The Former USSR');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('sv', 'El Salvador');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('sy', 'Syria');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('sz', 'Swaziland');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('tc', 'The Turks and Caicos Islands');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('td', 'Chad');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('tf', 'French Southern Territories');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('tg', 'Togo');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('th', 'Thailand');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('tj', 'Tajikistan');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('tk', 'Tokelau');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('tm', 'Turkmenistan');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('tn', 'Tunisia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('to', 'Tonga');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('tp', 'East Timor');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('tr', 'Turkey');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('tt', 'Trinidad and Tobago');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('tv', 'Tuvalu');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('tw', 'Taiwan');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('tz', 'Tanzania');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ua', 'Ukraine');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ug', 'Uganda');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('uk', 'United Kingdom');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('um', 'United States Minor Outlying Islands');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('us', 'United States');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('uy', 'Uruguay');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('uz', 'Uzbekistan');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('va', 'Vatican City State');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('vc', 'Saint Vincent and the Grenadines');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ve', 'Venezuela');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('vg', 'Virgin Islands (Br.)');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('vi', 'Virgin Islands (US)');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('vn', 'Vietnam');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('vu', 'Vanuatu');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('wf', 'Wallis and Futuna Islands');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ws', 'Samoa');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('ye', 'Yemen');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('yt', 'Mayotte');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('yu', 'Yugoslavia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('za', 'South Africa');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('zm', 'Zambia');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('zr', 'Zaire');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('zw', 'Zimbabwe');
INSERT INTO Countries (Domain_STRING, Description_STRING) VALUES ('**', 'Other');

# --------------------------------------------------------
# --------------------------------------------------------

INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('af', 'Afrikaans');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('af-za', 'Afrikaans - South Africa');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('sq', 'Albanian');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ar', 'Arabic');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ar-dz', 'Arabic - Algeria');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ar-bh', 'Arabic - Bahrain');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ar-eg', 'Arabic - Egypt');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ar-iq', 'Arabic - Iraq');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ar-jo', 'Arabic - Jordan');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ar-kw', 'Arabic - Kuwait');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ar-lb', 'Arabic - Lebanon');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ar-ly', 'Arabic - Libya');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ar-ma', 'Arabic - Morocco');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ar-om', 'Arabic - Oman');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ar-qa', 'Arabic - Qatar');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ar-sa', 'Arabic - Saudi Arabia');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ar-sy', 'Arabic - Syria');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ar-tn', 'Arabic - Tunisia');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ar-ae', 'Arabic - United Arab Emirates');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ar-ye', 'Arabic - Yemen');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('hy', 'Armenian');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('az', 'Azeri');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('az-az', 'Azeri');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('eu', 'Basque');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('be', 'Belarusian');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('bg', 'Bulgarian');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ca', 'Catalan');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('zh', 'Chinese');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('zh-cn', 'Chinese - China');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('zh-hk', 'Chinese - Hong Kong S.A.R.');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('zh-mo', 'Chinese - Macau S.A.R');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('zh-sg', 'Chinese - Singapore');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('zh-tw', 'Chinese - Taiwan');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('hr', 'Croatian');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('cs', 'Czech');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('da', 'Danish');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('nl', 'Dutch');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('nl-nl', 'Dutch - The Netherlands');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('nl-be', 'Dutch - Belgium');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('en', 'English');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('en-au', 'English - Australia');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('en-bz', 'English - Belize');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('en-ca', 'English - Canada');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('en-cb', 'English - Carribbean');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('en-ie', 'English - Ireland');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('en-jm', 'English - Jamaica');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('en-nz', 'English - New Zealand');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('en-ph', 'English - Phillippines');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('en-za', 'English - South Africa');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('en-tt', 'English - Trinidad');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('en-gb', 'English - United Kingdom');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('en-us', 'English - United States');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('et', 'Estonian');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('fa', 'Farsi');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('fi', 'Finnish');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('fo', 'Faroese');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('fr', 'French');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('fr-fr', 'French - France');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('fr-be', 'French - Belgium');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('fr-ca', 'French - Canada');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('fr-lu', 'French - Luxembourg');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('fr-ch', 'French - Switzerland');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('gd-ie', 'Gaelic - Ireland');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('gd', 'Gaelic - Scotland');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('de', 'German');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('de-de', 'German - Germany');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('de-at', 'German - Austria');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('de-li', 'German - Liechtenstein');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('de-lu', 'German - Luxembourg');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('de-ch', 'German - Switzerland');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('el', 'Greek');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('he', 'Hebrew');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('hi', 'Hindi');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('hu', 'Hungarian');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('is', 'Icelandic');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('id', 'Indonesian');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('it', 'Italian');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('it-it', 'Italian - Italy');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('it-ch', 'Italian - Switzerland');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ja', 'Japanese');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ko', 'Korean');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('lv', 'Latvian');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('lt', 'Lithuanian');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('mk', 'FYRO Macedonian');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ms', 'Malay');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ms-my', 'Malay - Malaysia');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ms-bn', 'Malay - Brunei');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('mt', 'Maltese');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('mr', 'Marathi');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('nb-no', 'Norwegian - Bokmll');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('nn-no', 'Norwegian - Nynorsk');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('pl', 'Polish');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('pt', 'Portuguese');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('pt-pt', 'Portuguese - Portugal');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('pt-br', 'Portuguese - Brazil');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('rm', 'Raeto-Romance');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ro', 'Romanian - Romania');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ro-mo', 'Romanian - Moldova');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ru', 'Russian');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ru-mo', 'Russian - Moldova');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('sa', 'Sanskrit');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('sr', 'Serbian');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('sr-sp', 'Serbian');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('tn', 'Setsuana');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('sl', 'Slovenian');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('sk', 'Slovak');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('sb', 'Sorbian');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es', 'Spanish');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-es', 'Spanish - Spain');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-ar', 'Spanish - Argentina');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-bo', 'Spanish - Bolivia');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-cl', 'Spanish - Chile');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-co', 'Spanish - Colombia');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-cr', 'Spanish - Costa Rica');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-do', 'Spanish - Dominican Republic');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-ec', 'Spanish - Ecuador');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-gt', 'Spanish - Guatemala');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-hn', 'Spanish - Honduras');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-mx', 'Spanish - Mexico');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-ni', 'Spanish - Nicaragua');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-pa', 'Spanish - Panama');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-pe', 'Spanish - Peruv');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-pr', 'Spanish - Puerto Rico');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-py', 'Spanish - Paraguay');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-sv', 'Spanish - El Salvador');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-uy', 'Spanish - Uruguay');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('es-ve', 'Spanish - Venezuela');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('sx', 'Sutu');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('sw', 'Swahili');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('sv', 'Swedish');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('sv-se', 'Swedish - Sweden');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('sv-fi', 'Swedish - Finland');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ta', 'Tamil');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('tt', 'Tatar');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('th', 'Thai');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('tr', 'Turkish');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ts', 'Tsonga');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('uk', 'Ukrainian');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('ur', 'Urdu');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('uz', 'Uzbek');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('uz-uz', 'Uzbek');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('vi', 'Vietnamese');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('xh', 'Xhosa');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('yi', 'Yiddish');
INSERT INTO Languages (Locale_ID, Description_STRING) VALUES ('zu', 'Zulu');

# --------------------------------------------------------
# --------------------------------------------------------

INSERT INTO Profiles (Profile_ID, Login_STRING, Password_STRING, UserName_STRING, KeepAlive_NUM, FirstVisit_DATE, PreviousVisit_DATE, LastVisit_DATE, Expiry_DATE, Visits_NUM, Status_NUM, LoyaltyPoints_NUM, LastIP_STRING, LastAgent_STRING, LastLanguage_STRING, LastReferer_STRING) VALUES (1, 'root.user', '12345678', 'Webmaster', '0', '', '', '', '', '0', 100, '0', '', '', '', '');
INSERT INTO Permissions (Profile_ID, Group_ID, Category_ID, PermissionClass_NUM, PermissionLevel_NUM, Expiry_DATE) VALUES (1, 0, 0, 6, 1, 0);
INSERT INTO Permissions (Profile_ID, Group_ID, Category_ID, PermissionClass_NUM, PermissionLevel_NUM, Expiry_DATE) VALUES (1, 0, 2, 6, 1, 0);
INSERT INTO Permissions (Profile_ID, Group_ID, Category_ID, PermissionClass_NUM, PermissionLevel_NUM, Expiry_DATE) VALUES (1, 0, 3, 6, 1, 0);
INSERT INTO Permissions (Profile_ID, Group_ID, Category_ID, PermissionClass_NUM, PermissionLevel_NUM, Expiry_DATE) VALUES (1, 0, 4, 6, 1, 0);
INSERT INTO Campaigns_Permissions (Profile_ID, Group_ID, Campaign_ID, PermissionClass_NUM, PermissionLevel_NUM, Expiry_DATE) VALUES (1, 0, 0, 1, 1, 0);

# --------------------------------------------------------
# --------------------------------------------------------

# --------------------------------------------------------
#
# Form Template - User Profile
#

INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('1', '0', 'CONTROL', '', '', '%', '0', '0', '0', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('2', '0', 'Title', '', '', 'D', '0', '0', '0', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('3', '0', 'First Name', '', '', 'T', '15', '0', '1', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('4', '0', 'Initials', '', '', 'T', '3', '0', '2', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('5', '0', 'Surname', '', '', 'T', '15', '0', '3', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('6', '0', 'Company', '', '', 'T', '20', '0', '4', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('7', '0', 'Position', '', '', 'T', '10', '0', '5', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('8', '0', 'Work Phone', '', '', 'T', '10', '0', '6', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('9', '0', 'Home Phone', '', '', 'T', '10', '0', '7', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('10', '0', 'Address', '', '', 'A', '22', '0', '8', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('11', '0', 'City', '', '', 'T', '10', '0', '9', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('12', '0', 'State/Province', '', '', 'T', '10', '0', '10', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('13', '0', 'Country', '', '', 'O', '0', '0', '11', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('14', '0', 'Postal Code', '', '', 'T', '4', '0', '12', '0', '-1');
INSERT INTO Options (Option_ID, Form_ID, OptionValue_STRING, OptionOrder_NUM, Action_STRING) VALUES ('1', '2', 'Mr.', '0', '');
INSERT INTO Options (Option_ID, Form_ID, OptionValue_STRING, OptionOrder_NUM, Action_STRING) VALUES ('2', '2', 'Miss', '1', '');
INSERT INTO Options (Option_ID, Form_ID, OptionValue_STRING, OptionOrder_NUM, Action_STRING) VALUES ('3', '2', 'Mrs.', '2', '');
INSERT INTO Options (Option_ID, Form_ID, OptionValue_STRING, OptionOrder_NUM, Action_STRING) VALUES ('4', '2', 'Ms.', '3', '');
INSERT INTO Options (Option_ID, Form_ID, OptionValue_STRING, OptionOrder_NUM, Action_STRING) VALUES ('5', '2', 'Dr.', '4', '');

# --------------------------------------------------------
#
# MetaData Template - Document
#

INSERT INTO Catalog (Item_ID, Category_ID, ItemCode_STRING, ItemType_CHAR, ItemStatus_NUM, ItemOrder_NUM, ItemViewed_NUM, Entry_DATE, LastModified_DATE, Expiry_DATE) VALUES ('1', '0', 'Document', 'T', '0', '0', '0', '', '', '');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('15', '1', 'CONTROL', '', '', '%', '0', '0', '0', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('16', '1', 'Publishing Date', '', '', 'K', '10', '0', '0', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('17', '1', 'Author', '', '', 'S', '10', '0', '1', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('18', '1', 'Author E-mail', '', '', 'S', '30', '0', '2', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('19', '1', 'Keywords', '', '', 'S', '30', '0', '3', '0', '-1');

# --------------------------------------------------------
#
# MetaData Template - Products
#

INSERT INTO Catalog (Item_ID, Category_ID, ItemCode_STRING, ItemType_CHAR, ItemStatus_NUM, ItemOrder_NUM, ItemViewed_NUM, Entry_DATE, LastModified_DATE, Expiry_DATE) VALUES ('2', '0', 'Product', 'T', '1', '0', '0', '', '', '');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('20', '2', 'CONTROL', '', '', '%', '0', '0', '0', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('21', '2', 'Currency', '', '', 'M', '0', '0', '0', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('22', '2', 'Default Price', '', '', 'S', '10', '0', '1', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('23', '2', 'Special Price', '', '', 'S', '10', '0', '2', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('24', '2', 'Quantity', '', '', 'S', '10', '0', '3', '0', '-1');
INSERT INTO Forms (Form_ID, Item_ID, FieldName_STRING, FieldGroup_STRING, FieldDefaultValue_STRING, FieldType_CHAR, FieldSize_NUM, FieldStatus_NUM, FieldOrder_NUM, FieldMandatory_NUM, ValueForm_ID) VALUES ('25', '2', 'Shipping Details', '', '', 'X', '30', '0', '4', '0', '-1');
INSERT INTO Options (Option_ID, Form_ID, OptionValue_STRING, OptionOrder_NUM, Action_STRING) VALUES( '6', '21', 'United States Dollar (USD)', '0', '');
INSERT INTO Options (Option_ID, Form_ID, OptionValue_STRING, OptionOrder_NUM, Action_STRING) VALUES( '7', '21', 'Pound Sterling (GBP)', '1', '');
INSERT INTO Options (Option_ID, Form_ID, OptionValue_STRING, OptionOrder_NUM, Action_STRING) VALUES( '8', '21', 'Australian Dollar (AUD)', '2', '');
INSERT INTO Options (Option_ID, Form_ID, OptionValue_STRING, OptionOrder_NUM, Action_STRING) VALUES( '9', '21', 'Canadian Dollar (CAD)', '3', '');
INSERT INTO Options (Option_ID, Form_ID, OptionValue_STRING, OptionOrder_NUM, Action_STRING) VALUES( '10', '21', 'South African Rand (ZAR)', '4', '');

# --------------------------------------------------------
# --------------------------------------------------------

# --------------------------------------------------------
#
# Components
#

INSERT INTO Components VALUES ('ensight/content_item.php', 'Content from item', 'Displays content from an item specified by the user when they click on a link or complete a form.', 'Content', '1', '1', '1', '1', '1', '0', '1', 'ensight/content_item-config.php', '257', '195');
INSERT INTO Components VALUES ('ensight/content_toolbox.php', 'Standard content toolbox', 'Displays links to standard content functions including E-mail (to a friend) and Print this page.', 'Content', '0', '0', '1', '0', '1', '0', '1', '', '0', '0');
INSERT INTO Components VALUES ('ensight/display_form.php', 'Standard form', 'Displays the selected form in a standard layout, and redirects users to the specified page upon successful completion. Included for backward compatibility only. Rather use the form icon within the editor toolbar', 'Content', '1', '1', '1', '1', '1', '0', '1', 'ensight/display_form-config.php', '280', '435');
INSERT INTO Components VALUES ('ensight/table_of_contents.php', 'Table of contents', 'Displays a list of pages within the specified item. If summary text is provided, it is displayed below the page title.', 'Content', '1', '1', '1', '1', '1', '0', '1', '', '0', '0');
INSERT INTO Components VALUES ('ensight/ie_toolbox.php', 'Internet Explorer toolbox', 'Displays two options specifically for Internet Explorer users - Add to Favorites, and Make this my Homepage.', 'Content', '0', '0', '1', '0', '1', '0', '1', '', '0', '0');
INSERT INTO Components VALUES ('ensight/content_list.php', 'Content listing from folder', 'Displays a list of content, links and shortcuts from the selected folder. [Summary Information]', 'Navigation', '1', '1', '1', '1', '1', '0', '1', 'ensight/content_list-config.php', '257', '195');
INSERT INTO Components VALUES ('ensight/folders_list.php', 'List of folders', 'Displays a list of sub-folders within the selected folder. [Summary Information, or folder names if none provided]', 'Navigation', '1', '1', '1', '1', '1', '0', '1', 'ensight/folders_list-config.php', '257', '195');
INSERT INTO Components VALUES ('ensight/breadcrumbs.php', 'Breadcrumbs', 'Displays a breadcrumbs bar, with clickable links to parent folders which are visible and accessible by the user. [Summary Information, or folder names if none provided]', 'Navigation', '1', '1', '1', '1', '1', '0', '1', 'ensight/breadcrumbs-config.php', '257', '195');
INSERT INTO Components VALUES ('ensight/personalized_items.php', 'Personalized items list', 'Displays a list of personalised content for the user based on their preferences and User Profile (registration) form settings. [Summary Information]', 'Navigation', '1', '1', '1', '1', '1', '0', '1', '', '0', '0');
INSERT INTO Components VALUES ('ensight/rss.php', 'RSS news feed', 'An RSS download and rendering agent for inclusion in e-mails and embedding within content.', 'Navigation', '1', '1', '1', '1', '1', '0', '1', 'ensight/rss-config.php', '238', '160');
INSERT INTO Components VALUES ('ensight/back_to_home_page.php', 'Back to home/personalised home page', 'Displays a quick link back to the user''s personalized home page (if signed in) or the front page.', 'Navigation', '1', '1', '1', '1', '1', '0', '1', '', '0', '0');
INSERT INTO Components VALUES ('ensight/login.php', 'Standard login form', 'Displays a login form for guest users, and a ''You are signed in'' message for logged in users.', 'Login/Registration', '0', '0', '1', '0', '1', '0', '1', '', '0', '0');
INSERT INTO Components VALUES ('ensight/login_registration_1.php', 'Login/registration combo form (step 1)', 'Displays a combined login/registration form, with options for the user to either sign-in to their account or sign-up for a new one.', 'Login/Registration', '1', '1', '0', '1', '0', '0', '1', '', '0', '0');
INSERT INTO Components VALUES ('ensight/login_registration_2.php', 'Login/registration combo form (step 2)', 'Displays part 2 in the combined login/registration process, where the user is asked to create their new account by entering an e-mail address and password.', 'Login/Registration', '1', '1', '0', '1', '0', '0', '1', '', '0', '0');
INSERT INTO Components VALUES ('ensight/password_reminder.php', 'Password reminder', 'Displays a form where users can request an e-mail reminder of their password.', 'Login/Registration', '1', '1', '0', '1', '0', '0', '1', '', '0', '0');
INSERT INTO Components VALUES ('ensight/update_login.php', 'Update login', 'Provides a utility for users to update their account login and password.', 'Login/Registration', '1', '1', '0', '1', '0', '0', '1', '', '0', '0');
INSERT INTO Components VALUES ('ensight/registration.php', 'Standard registration form', 'Displays the standard registration form, or a Sign out button if the user is currently logged in.', 'Login/Registration', '0', '0', '1', '0', '1', '0', '1', '', '0', '0');
INSERT INTO Components VALUES ('ensight/user_profile.php', 'User profile questionnaire', 'Displays the predefined User Profile form created within Ensight''s Profile Manager.', 'Login/Registration', '1', '1', '0', '1', '0', '0', '1', '', '0', '0');
INSERT INTO Components VALUES ('ensight/search_box.php', 'Standard content search box', 'Provides a search form for the user to enter their search criteria. Include the Content search results component to display results from the search.', 'Search', '0', '0', '1', '0', '1', '0', '1', '', '0', '0');
INSERT INTO Components VALUES ('ensight/search_results.php', 'Content search results', 'Displays results from the search query submitted through the Content search or Advanced content search facilities.', 'Search', '1', '1', '0', '1', '0', '0', '1', '', '0', '0');
INSERT INTO Components VALUES ('ensight/online.php', 'Who''s online?', 'Displays the number of guests and registered users currently online.', 'Community', '0', '0', '1', '0', '1', '1', '1', '', '0', '0');
INSERT INTO Components VALUES ('ensight/poll.php', 'Quick (straw) poll', 'Displays a quick poll using a form generated through Ensight - only selection fields are displayed.', 'Community', '0', '0', '1', '0', '1', '0', '1', 'ensight/poll-config.php', '250', '160');
INSERT INTO Components VALUES ('ensight/mobile_content_item.php', 'Mobile content from item', 'Displays mobile content from an item specified by the user when they click on a link or complete a form.', 'Content', '1', '1', '1', '1', '1', '0', '1', 'ensight/content_item-config.php', '257', '195');
INSERT INTO Components VALUES ('ensight/switch.php', 'Switch', 'A switch component replaces the Content from Item component in a content layout, allowing developers to customise the content area based on Action= keywords.', 'Content', '1', '1', '1', '1', '1', '1', '1', '', '0', '0');
INSERT INTO Components VALUES ('ensight/tag_cloud.php', 'Tag Cloud', 'Displays a tag cloud with links to appropriate search results.', 'Navigation', '1', '1', '1', '1', '1', '1', '1', '', '0', '0');
INSERT INTO Components VALUES ('media/video.php', 'Display video', 'Displays a streaming video within a content page', 'Multimedia', '0', '0', '1', '0', '1', '0', '1', 'media/video-config.php', '300', '305');
INSERT INTO Components VALUES ('flash/flash.php', 'Display Flash/Shockwave movie', 'Displays the code that renders an Adobe Flash/Shockwave compatible file to the screen.', 'Multimedia', '0', '0', '1', '0', '1', '0', '1', 'flash/flash-config.php', '300', '220');

# --------------------------------------------------------
# --------------------------------------------------------

# --------------------------------------------------------
#
# Themes
#

INSERT INTO Themes VALUES ('Boring', 'boring', 'Ensight', 'Plain and simple - nothing fancy.');
INSERT INTO Themes VALUES ('Dune', 'dune', 'Ensight', 'Silver and gold mixed with a tiny bit of orange give this theme a coat of desert magic.');
INSERT INTO Themes VALUES ('Night Shade', 'night-shade', 'Ensight', 'Dark blues and yellows on a black background. An excellent choice for a mysterious look.');
INSERT INTO Themes VALUES ('Standard', 'standard', 'Ensight', 'The default theme for PageBuilder.');
INSERT INTO Themes VALUES ('Rainbow', 'rainbow', 'Ensight', 'Multi-colored boxes for general purposes.');
INSERT INTO Themes VALUES ('Tupperware', 'tupperware', 'Ensight', 'A simple example of how to enhance the container designs with images.');
INSERT INTO Themes VALUES ('Cool Blue', 'cool-blue', 'Ensight', 'A relaxing, cool breeze drifts over the page, giving it a touch of color with a dash of sophistication.');
INSERT INTO Themes VALUES ('Ugly Duckling', 'ugly-duckling', 'Ensight', 'A wierd mixture of primary colors which is so ugly, it''s almost nice.');
INSERT INTO Themes VALUES ('Love', 'love', 'Ensight', 'A wonderful merger of pinks and light-blues for the romantic in you!');
INSERT INTO Themes VALUES ('Sleuth', 'sleuth', 'Ensight', 'Deep shades of green/brown give a mysterious Sherlock Holmes-type ambiance.');
INSERT INTO Themes VALUES ('Grays & Blues', 'grays-and-blues', 'Ensight', 'Shades of gray mixed with deep blue and dark black borders provides a strong, yet classy look.');
INSERT INTO Themes VALUES ('Silver Sands', 'silver-sands', 'Ensight', 'With a small amount of silver on mostly white, you''d be surprised as to how effective it could be.');
INSERT INTO Themes VALUES ('ComputerCops', 'computer-cops', 'ComputerCops.com', 'A sample theme ripped from ComputerCops.com');

# --------------------------------------------------------
# --------------------------------------------------------

INSERT INTO Rules_Groups (RuleGroup_ID, RuleGroupName_STRING, RuleGroupDescription_STRING) VALUES ('1', 'Default Group', 'This is the default audience group.');

# --------------------------------------------------------
# --------------------------------------------------------

INSERT INTO Campaigns_Types (TypeDescription_STRING, TypeColor_STRING) VALUES ('Ad-hoc Messages', '#66FF00');
INSERT INTO Campaigns_Types (TypeDescription_STRING, TypeColor_STRING) VALUES ('Competitions & Promotions', '#330099');
INSERT INTO Campaigns_Types (TypeDescription_STRING, TypeColor_STRING) VALUES ('Newsletters', '#FF0000');
