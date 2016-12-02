CREATE TABLE Streams (
	Post_ID bigint NOT NULL PRIMARY KEY auto_increment,
	Posted_DATE datetime NOT NULL,
	PostedByProfile_ID bigint NULL,
	PostedMessage_BLOB blob NULL,
	Service_ID tinyint NULL,
	ServiceAssigned_ID varchar(255) NOT NULL,
	InReplyToServiceAssigned_ID varchar(255) NOT NULL,
	User_ID bigint NOT NULL,
	Media_URL varchar(150) NULL,
	MediaShort_URL varchar(50) NULL,
	MediaType_CHAR char(2) NULL,
	StoryTitle_STRING varchar(255) NULL,
	StorySnippet_STRING varchar(255) NULL,
	StoryThumbnail_STRING varchar(255) NULL,
	Status_NUM tinyint NOT NULL
);
CREATE INDEX StreamsPostedByProfile_ID ON Streams (PostedByProfile_ID);
CREATE INDEX StreamsServiceAssigned_ID ON Streams (ServiceAssigned_ID, Service_ID);
CREATE INDEX StreamsServiceInReplyToServiceAssigned_ID ON Streams (InReplyToServiceAssigned_ID, Service_ID);
CREATE INDEX StreamsUser_ID ON Streams (User_ID);

CREATE TABLE Streams_Users (
	User_ID bigint NOT NULL PRIMARY KEY auto_increment,
	ServiceUser_ID varchar(64) NULL,
	ServiceUserPhoto_STRING varchar(100) NULL,
	ScreenName_STRING varchar(100) NOT NULL,
	FullName_STRING varchar(150) NOT NULL,
	FollowerSince_DATE datetime NULL,
	Location_STRING varchar(255) NULL,
	Bio_STRING varchar(255) NULL,
	LastFollowers_NUM bigint NULL,
	LastListAppearances_NUM bigint NULL,
	LastInfluenced_NUM float NULL,
	LastModified_DATE datetime NULL
);
CREATE INDEX Streams_UsersServiceUser_ID ON Streams_Users (ServiceUser_ID);
CREATE INDEX Streams_UsersScreenName ON Streams_Users (ScreenName_STRING);

CREATE TABLE Streams_Users_Tags (
	User_ID bigint NOT NULL,
	Tag_ID bigint NOT NULL
);

CREATE TABLE Streams_Tags (
	Tag_ID bigint NOT NULL PRIMARY KEY auto_increment,
	Tag_STRING varchar(100)
);
CREATE INDEX Streams_TagsTag ON Streams_Tags (Tag_STRING);

CREATE TABLE Streams_Clicks (
	Post_ID bigint NOT NULL,
	Profile_ID bigint NOT NULL,
	Click_DATE datetime NULL,
	Dest_URL varchar(255) NOT NULL
);