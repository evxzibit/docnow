# From 3.0.0

CREATE INDEX CacheCacheURL ON Cache (Cache_URL);
CREATE INDEX Campaigns_PermissionsProfile_ID ON Campaigns_Permissions (Profile_ID);
CREATE INDEX CatalogItemStatus_NUM ON Catalog (ItemStatus_NUM);
CREATE INDEX Catalog_Rules_LinkRule_ID ON Catalog_Rules_Link (Rule_ID);
CREATE INDEX ClicksProfile_ID ON Clicks (Profile_ID);
CREATE INDEX ContainersContainerPosition_CHAR ON Containers (ContainerPosition_CHAR);
CREATE INDEX Containers_ModeContainer_ID ON Containers_Mode (Container_ID);
CREATE INDEX ContentItem_ID ON Content (Item_ID);
CREATE INDEX ContentLanguageVersion ON Content (ContentLanguage_STRING, ContentVersion_NUM);
CREATE INDEX ContentOwnerProfile_ID ON Content (OwnerProfile_ID);
CREATE INDEX ContentStatus_NUM ON Content (Status_NUM);
CREATE INDEX Content_HistoryItem_ID ON Content_History (Item_ID);
ALTER TABLE Keywords RENAME Content_Keywords;
CREATE INDEX Content_KeywordsItemRevision ON Content_Keywords (Item_ID, Revision_STRING);
CREATE INDEX Content_ModeContent_ID ON Content_Mode (Content_ID);
CREATE INDEX ErrorsError_NUM ON Errors (Error_NUM);
CREATE INDEX FavoritesProfile_ID ON Favorites (Profile_ID);
CREATE INDEX FormsItem_ID ON Forms (Item_ID);
CREATE INDEX FormsItemNameGroup ON Forms (Item_ID, FieldName_STRING);
CREATE INDEX Forms_MailsMail_ID ON Forms_Mails (Mail_ID);
CREATE INDEX Forms_PathsItem_ID ON Forms_Paths (Item_ID);
CREATE INDEX Forms_Paths_ActionsItemPath ON Forms_Paths_Actions (Item_ID, PathDefinition_NUM);
CREATE INDEX Forms_Paths_DetailsItemPath ON Forms_Paths_Details (Item_ID, PathDefinition_NUM);
CREATE INDEX Forms_Paths_SpecialItemPath ON Forms_Paths_Special (Item_ID, PathDefinition_NUM);
CREATE INDEX Forms_RulesRule_ID ON Forms_Rules (Rule_ID);
CREATE INDEX Forms_Rules_DetailsRuleDefinition ON Forms_Rules_Details (Rule_ID, RuleDefinition_NUM);
CREATE INDEX Forms_Rules_SpecialRuleDefinition ON Forms_Rules_Special (Rule_ID, RuleDefinition_NUM);
CREATE INDEX Forms_TextsText_ID ON Forms_Texts (Text_ID);
CREATE INDEX Forms_ViewsItemView ON Forms_Views (Item_ID, ViewDefinition_NUM);
CREATE INDEX Forms_Views_DetailsItemView ON Forms_Views_Details (Item_ID, ViewDefinition_NUM);
CREATE INDEX ImpressionsProfile_ID ON Impressions (Profile_ID);
CREATE INDEX ImpressionsItem_ID ON Impressions (Item_ID);
CREATE INDEX InstanceItem_ID ON Instance (Item_ID);
ALTER TABLE Keywords_Admin RENAME Admin_Keywords;
CREATE INDEX LanguagesLocale_ID ON Languages (Locale_ID);
CREATE INDEX LinksItem_ID ON Links (Item_ID);

# ---------------------------------------
# Table 'Mails_Bounce'
# ---------------------------------------

CREATE TABLE Mails_Bounce (
	Mail_ID int NOT NULL,
	Profile_ID int NOT NULL,
	Reference_ID int NOT NULL,
	Subject_STRING varchar(255) NOT NULL,
	ReasonCode_NUM smallint NOT NULL,
	Bounced_DATE datetime NOT NULL
);
CREATE INDEX Mails_BounceMail_ID ON Mails_Bounce (Mail_ID);
CREATE INDEX Mails_BounceProfile_ID ON Mails_Bounce (Profile_ID);
CREATE INDEX Mails_BounceReference_ID ON Mails_Bounce (Reference_ID);

# ---------------------------------------
# Table 'Mails_Open'
# ---------------------------------------

CREATE TABLE Mails_Open (
	Mail_ID int NOT NULL,
	Profile_ID int NOT NULL,
	Reference_ID int NOT NULL,
	Opened_DATE datetime NOT NULL
);
CREATE INDEX Mails_OpenMail_ID ON Mails_Open (Mail_ID);
CREATE INDEX Mails_OpenProfile_ID ON Mails_Open (Profile_ID);
CREATE INDEX Mails_OpenReference_ID ON Mails_Open (Reference_ID);

CREATE INDEX Mails_Rules_LinkMail_ID ON Mails_Rules_Link (Mail_ID);

# ---------------------------------------
# Table 'Mails_Sent'
# ---------------------------------------

CREATE TABLE Mails_Sent (
	Mail_ID int NOT NULL,
	Profile_ID int NOT NULL,
	Reference_ID int NOT NULL,
	Recipient_STRING varchar(255) NOT NULL,
	Subject_STRING varchar(255) NOT NULL,
	Sent_DATE datetime NOT NULL
);
CREATE INDEX Mails_SentMail_ID ON Mails_Sent (Mail_ID);
CREATE INDEX Mails_SentProfile_ID ON Mails_Sent (Profile_ID);
CREATE INDEX Mails_SentReference_ID ON Mails_Sent (Reference_ID);

CREATE INDEX Mails_SQLMail_ID ON Mails_SQL (Mail_ID);
CREATE INDEX MediaCampaign_ID ON Media (Campaign_ID);
CREATE INDEX MessagesBaseMessage_ID ON Messages (BaseMessage_ID);
CREATE INDEX MessagesItem_ID ON Messages (Item_ID);
CREATE INDEX OptionsForm_ID ON Options (Form_ID);
CREATE INDEX PagesPage_STRING ON Pages (Page_STRING);

# ---------------------------------------
# Table 'Pages_Keywords'
# ---------------------------------------

CREATE TABLE Pages_Keywords (
	Page_STRING varchar(255) NOT NULL,
	Word_STRING varchar(255) NOT NULL,
	Score_NUM float DEFAULT '0.00' NOT NULL
);
CREATE INDEX Pages_KeywordsPage_STRING ON Pages_Keywords (Page_STRING);

CREATE INDEX PermissionsProfile_ID ON Permissions (Profile_ID);
CREATE INDEX PreferencesProfile_ID ON Preferences (Profile_ID);
CREATE INDEX Profiles_HistoryProfile_ID ON Profiles_History (Profile_ID);
CREATE INDEX Profiles_ValuesForm_ID ON Profiles_Values (Form_ID);
CREATE INDEX Profiles_ValuesProfile_ID ON Profiles_Values (Profile_ID);
CREATE INDEX ReferenceProfile_ID ON Reference (Profile_ID);
CREATE INDEX ReferenceItem_ID ON Reference (Item_ID);
CREATE INDEX ResponsesProfile_ID ON Responses (Profile_ID);
CREATE INDEX SessionsProfile_ID ON Sessions (Profile_ID);
ALTER TABLE Temp_Keywords_Admin RENAME Temp_Admin_Keywords;
ALTER TABLE Temp_Keywords RENAME Temp_Content_Keywords;
CREATE INDEX Temp_Mails_SamplesMail_ID ON Temp_Mails_Samples (Mail_ID);

# ---------------------------------------
# Table 'Temp_Pages_Keywords'
# ---------------------------------------

CREATE TABLE Temp_Pages_Keywords (
	Page_STRING varchar(255) NOT NULL,
	Word_STRING varchar(255) NOT NULL
);

CREATE INDEX Temp_Texts_SamplesText_ID ON Temp_Texts_Samples (Text_ID);
CREATE INDEX TermsProfile_ID ON Terms (Profile_ID);
CREATE INDEX TextsCampaign_ID ON Texts (Campaign_ID);
CREATE INDEX Texts_Rules_LinkText_ID ON Texts_Rules_Link (Text_ID);

# ---------------------------------------
# Table 'Texts_Sent'
# ---------------------------------------

CREATE TABLE Texts_Sent (
	Text_ID int NOT NULL,
	Profile_ID int NOT NULL,
	Reference_ID int NOT NULL,
	Recipient_STRING varchar(255) NOT NULL,
	Subject_STRING varchar(255) NOT NULL,
	Sent_DATE datetime NOT NULL
);
CREATE INDEX Texts_SentText_ID ON Texts_Sent (Text_ID);
CREATE INDEX Texts_SentProfile_ID ON Texts_Sent (Profile_ID);
CREATE INDEX Texts_SentReference_ID ON Texts_Sent (Reference_ID);

CREATE INDEX Texts_SQLText_ID ON Texts_SQL (Text_ID);