DROP TABLE IF EXISTS volunteer;
CREATE TABLE IF NOT EXISTS volunteer(
ID bigint(20) unsigned NOT NULL auto_increment,
volunteer_firstName varchar(90) NOT NULL,
volunteer_lastName varchar(60) NOT NULL,
volunteer_login varchar(90) NOT NULL,
volunteer_email varchar(150) NOT NULL,
volunteer_pass varchar(128) NOT NULL,
volunteer_phone varchar(16) NOT NULL,
volunteer_level varchar(20) NOT NULL,
volunteer_resetKey varchar(255) DEFAULT NULL,
volunteer_resetComplete varchar(3) DEFAULT 'No',
volunteer_session varchar(255) NOT NULL,
date_registered date NOT NULL,
time_registered time NOT NULL,
PRIMARY KEY(ID)
)Engine=MyISAM;

DROP TABLE IF EXISTS files;
CREATE TABLE IF NOT EXISTS files(
fileID smallint(5) unsigned NOT NULL auto_increment,
file_title varchar(120) NOT NULL,
file_name varchar(200) NOT NULL,
file_uploaded date NOT NULL,
file_hits int(3) NOT NULL,
file_slug varchar(255) NOT NULL,
PRIMARY KEY(fileID)
)Engine=MyISAM;

DROP TABLE IF EXISTS inbox;
CREATE TABLE IF NOT EXISTS `inbox` (
  `ID` smallint(5) unsigned NOT NULL auto_increment,
  `sender` varchar(90) NOT NULL,
  `email` varchar(180) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `messages` text NOT NULL,
  `date_sent` date NOT NULL,
  `time_sent` time NOT NULL,
  PRIMARY KEY (`ID`)
)Engine=MyISAM;

DROP TABLE IF EXISTS posts;
CREATE TABLE IF NOT EXISTS `posts` (
  `postID` bigint(20) unsigned NOT NULL auto_increment,
  `post_image` varchar(512) default NULL,
  `post_author` bigint(20) unsigned NOT NULL DEFAULT '0',
  `date_created` date NOT NULL,
  `date_modified` date DEFAULT NULL,
  `post_title` varchar(255) NOT NULL,
  `post_slug` varchar(255) NOT NULL,
  `post_content` longtext NOT NULL,
  `post_status` varchar(20) NOT NULL DEFAULT 'publish',
  `post_type` varchar(120) NOT NULL DEFAULT 'blog',
  `comment_status` varchar(20) NOT NULL DEFAULT 'open',
  PRIMARY KEY (postID)
) Engine=InnoDB;

DROP TABLE IF EXISTS category;
CREATE TABLE IF NOT EXISTS `category` (
  `categoryID` bigint(20) unsigned NOT NULL auto_increment,
  `category_title` varchar(255) NOT NULL,
  `category_slug` varchar(255) NOT NULL,
  `status` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`categoryID`)
) Engine=InnoDB;

DROP TABLE IF EXISTS post_category;
CREATE TABLE IF NOT EXISTS post_category(
post_categoryID int(11) unsigned NOT NULL auto_increment,
postID bigint(20) unsigned DEFAULT NULL,
categoryID bigint(20) unsigned DEFAULT NULL,
PRIMARY KEY(post_categoryID)
)Engine=InnoDB;

DROP TABLE IF EXISTS album;
CREATE TABLE IF NOT EXISTS album(
albumID int(11) unsigned NOT NULL auto_increment PRIMARY KEY,
album_title varchar(160) NOT NULL,
album_picture varchar(255) default NULL,
album_slug varchar(255) NOT NULL,
date_created date NOT NULL,
date_modified date DEFAULT NULL
)Engine=InnoDB;

DROP TABLE IF EXISTS photo;
CREATE TABLE IF NOT EXISTS photo(
photoID int(11) unsigned NOT NULL auto_increment,
album_id int(11) unsigned NOT NULL,
photo_title varchar(200) NOT NULL,
photo_slug varchar(255) NOT NULL,
photo_desc text  default NULL,
photo_filename varchar(512) default NULL,
date_created date NOT NULL,
PRIMARY KEY(photoID)
)Engine=InnoDB;

DROP TABLE IF EXISTS event;
CREATE TABLE IF NOT EXISTS event(
event_id int(11) unsigned NOT NULL auto_increment,
sender_id bigint(20) unsigned NOT NULL,
event_image varchar(512) default NULL,
name varchar(200) NOT NULL,
slug varchar(255) NOT NULL,
description text NOT NULL,
location varchar(255) NOT NULL,
time_started varchar(50) NOT NULL,
time_ended varchar(50) DEFAULT NULL,
start_date date NOT NULL,
end_date date DEFAULT NULL,
date_created date NOT NULL,
date_modified date DEFAULT NULL,
time_created time NOT NULL,
time_modified time DEFAULT NULL,
PRIMARY KEY(event_id)
)Engine=InnoDB;

DROP TABLE IF EXISTS configuration; 
CREATE TABLE IF NOT EXISTS configuration(
 `config_id` smallint(5) unsigned NOT NULL auto_increment,
  `site_name` varchar(150) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `meta_keywords` varchar(255) NOT NULL,
  `tagline` varchar(200) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `twitter` varchar(100) DEFAULT NULL,
  `facebook` varchar(120) DEFAULT NULL,
  `logo` varchar(120) DEFAULT NULL,
  PRIMARY KEY (`config_id`)
)Engine=MyISAM;

DROP TABLE IF EXISTS menu;
CREATE TABLE IF NOT EXISTS menu(
`ID` int(11) unsigned NOT NULL auto_increment,
`title` varchar(200) NOT NULL DEFAULT '',
`link` varchar(255) NOT NULL DEFAULT '',
`parent` int(11) unsigned NOT NULL DEFAULT '0',
`sort` int(11) unsigned NOT NULL DEFAULT '0',
`slug` varchar(255) NOT NULL DEFAULT '',
PRIMARY KEY(`ID`)
)Engine=InnoDB;

DROP TABLE IF EXISTS product_flavour;
CREATE TABLE IF NOT EXISTS product_flavour(
ID int(11) unsigned NOT NULL auto_increment,
title varchar(200) NOT NULL,
slug varchar(255) NOT NULL,
PRIMARY KEY(ID)
)Engine=MyISAM;

DROP TABLE IF EXISTS products;
CREATE TABLE IF NOT EXISTS products(
ID int(11) unsigned NOT NULL auto_increment,
product_name varchar(200) NOT NULL,
product_version varchar(40) NOT NULL,
product_flavour_id int(11) unsigned NOT NULL,
product_module varchar(255) NOT NULL,
product_slug varchar(512) NOT NULL,
product_description text NOT NULL,
product_link varchar(255) DEFAULT NULL,
product_image varchar(255) DEFAULT NULL,
date_published date NOT NULL,
PRIMARY KEY(ID)
)Engine=MyISAM;