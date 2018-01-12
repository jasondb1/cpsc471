/*mysqldump.php version 1.02 */
/* Table structure for table `authteam` */
DROP TABLE IF EXISTS `authteam`;

CREATE TABLE `authteam` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `teamname` varchar(25) NOT NULL DEFAULT '',
  `teamlead` varchar(25) NOT NULL DEFAULT '',
  `status` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `teamname` (`teamname`,`teamlead`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/* dumping data for table `authteam` */
insert into `authteam` values
(1,'Ungrouped','sa','active'),
(2,'Admin','sa','active'),
(3,'Temporary','sa','active'),
(7,'Supervisor','sa','active'),
(8,'Employee','sa','active'),
(9,'Contractor','sa','active');

/* Table structure for table `authuser` */
DROP TABLE IF EXISTS `authuser`;

CREATE TABLE `authuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `uname` varchar(25) NOT NULL DEFAULT '',
  `passwd` varchar(32) NOT NULL DEFAULT '',
  `team` varchar(25) NOT NULL DEFAULT '',
  `level` int(4) NOT NULL DEFAULT '0',
  `status` varchar(10) NOT NULL DEFAULT '',
  `lastlogin` datetime DEFAULT NULL,
  `logincount` int(11) DEFAULT NULL,
  `fullname` varchar(40) NOT NULL,
  `title` varchar(30) NOT NULL,
  `email` varchar(40) DEFAULT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `street` varchar(40) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `province` varchar(15) DEFAULT NULL,
  `postal_code` varchar(7) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `company` varchar(35) DEFAULT NULL,
  `supervisor` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uname` (`uname`),
  KEY `passwd` (`passwd`)
) ENGINE=MyISAM AUTO_INCREMENT=222 DEFAULT CHARSET=latin1;

/* dumping data for table `authuser` */
insert into `authuser` values
(1,1,'sa','9df3b01c60df20d13843841ff0d4482c','Admin',1,'active','2012-10-10 18:48:29',1,'','',null,null,null,null,null,null,null,null,'',''),
(2,2,'admin','b36d504d9e2e1c629f056de4b74a60a5','Admin',1,'active','2012-11-13 13:20:56',1,'','','office@jdsservices.ca','','','','','','0000-00-00','0000-00-00','',''),
(3,3,'test','098f6bcd4621d373cade4e832627b4f6','Contractor',999,'inactive','2013-04-02 18:11:44',19,'','','','','','','','','0000-00-00','0000-00-00','',''),
(5,11814,'jdeboer','696fbf17aefa357b0a62a92a87e8652e','Admin',3,'active','2014-07-28 12:09:30',178,'Jason De Boer','','jasond@testcompany.ca','403-680-6502','','','','','0000-00-00','0000-00-00','','boss');


/* Table structure for table `aztec_jobdata` */
DROP TABLE IF EXISTS `aztec_jobdata`;

CREATE TABLE `aztec_jobdata` (
  `jobnumber` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(512) DEFAULT '',
  `location` varchar(30) DEFAULT '',
  `customer` varchar(35) DEFAULT '',
  `bill_to` varchar(35) DEFAULT '',
  `supervisor` varchar(25) DEFAULT '',
  `status` varchar(20) DEFAULT '',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `quote_number` varchar(12) DEFAULT '',
  `po_number` varchar(16) DEFAULT '',
  `notes` varchar(1024) DEFAULT '',
  `invoice_number` varchar(12) DEFAULT '',
  `contact_name` varchar(25) DEFAULT '',
  `contact_number` varchar(16) DEFAULT '',
  `opened_by` varchar(25) DEFAULT '',
  `date_opened` date DEFAULT NULL,
  `date_invoiced` date DEFAULT NULL,
  `date_closed` date DEFAULT NULL,
  `last_modified` date DEFAULT NULL,
  `require_div` tinyint(1) DEFAULT NULL,
  `require_subdiv` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`jobnumber`)
) ENGINE=MyISAM AUTO_INCREMENT=44068 DEFAULT CHARSET=latin1;

/* dumping data for table `aztec_jobdata` */
insert into `aztec_jobdata` values
(4001,'Shop','','Internal','','','Permanent',null,null,'','','','','','','',null,null,null,null,null,null),
(4003,'Meeting','','Internal','','','Permanent',null,null,'','','','','','','',null,null,null,null,null,null),
(4005,'Snow Clearing','','Internal','','','Permanent',null,null,'','','','','','','',null,null,null,null,null,null),
(4007,'Training','','Internal','','','Permanent',null,null,'','','','','','','',null,null,null,null,null,null),
(4009,'Office','','Internal','','','Permanent',null,null,'','','','','','','',null,null,null,null,null,null),
(4015,'Vacation','','Internal','','','Permanent',null,null,'','','','','','','',null,null,null,null,null,null),
(4019,'Use Banked Hours','','Internal','','','Permanent',null,null,'','','','','','','',null,null,null,null,null,null),
(4021,'Job Quoting','','Internal','','','Permanent',null,null,'','','','','','','',null,null,null,null,null,null),
(4023,'Safety Management','','Internal','','','Permanent',null,null,'','','','','','','',null,null,null,null,null,null);


/* Table structure for table `changeorder` */
DROP TABLE IF EXISTS `changeorder`;

CREATE TABLE `changeorder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobnumber` int(11) DEFAULT NULL,
  `item_number` int(11) DEFAULT NULL,
  `co_number` varchar(16) DEFAULT NULL,
  `date_entered` date DEFAULT NULL,
  `entered_by` varchar(25) DEFAULT NULL,
  `status` varchar(15) DEFAULT NULL,
  `notes` varchar(512) DEFAULT NULL,
  `date_of_completion` date DEFAULT NULL,
  `invoice` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jobnumber` (`jobnumber`),
  FULLTEXT KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=latin1;

/* Table structure for table `changeorder_items` */
DROP TABLE IF EXISTS `changeorder_items`;

CREATE TABLE `changeorder_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `co_number` varchar(16) DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `division` int(11) DEFAULT NULL,
  `sub_division` varchar(15) DEFAULT NULL,
  `accepted` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=256 DEFAULT CHARSET=latin1;

/* Table structure for table `customer` */
DROP TABLE IF EXISTS `customer`;

CREATE TABLE `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cname` varchar(35) NOT NULL DEFAULT '',
  `street` varchar(30) DEFAULT '',
  `city` varchar(20) DEFAULT '',
  `province` varchar(15) DEFAULT '',
  `postal_code` varchar(7) DEFAULT '',
  `phone` varchar(16) DEFAULT '',
  `fax` varchar(16) DEFAULT '',
  `email` varchar(35) DEFAULT '',
  `contact` varchar(20) DEFAULT '',
  `date_added` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/* Table structure for table `daily_reports` */
DROP TABLE IF EXISTS `daily_reports`;

CREATE TABLE `daily_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobnumber` int(11) NOT NULL,
  `report_date` date NOT NULL,
  `report_number` varchar(25) NOT NULL,
  `filename` varchar(256) NOT NULL,
  `uid` varchar(40) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/* Table structure for table `dashboard_project` */
DROP TABLE IF EXISTS `dashboard_project`;

CREATE TABLE `dashboard_project` (
  `jobnumber` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(512) NOT NULL,
  `owner` varchar(256) NOT NULL,
  `location` varchar(30) NOT NULL,
  `bill_to` varchar(35) NOT NULL,
  `supervisor` varchar(25) NOT NULL,
  `status` varchar(20) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `contract_number` varchar(12) NOT NULL,
  `po_number` varchar(16) NOT NULL,
  `notes` varchar(1024) NOT NULL,
  `invoice_number` varchar(12) NOT NULL,
  `contact_name` varchar(25) NOT NULL,
  `contact_number` varchar(16) NOT NULL,
  `opened_by` varchar(25) NOT NULL,
  `date_opened` date NOT NULL,
  `date_invoiced` date NOT NULL,
  `date_closed` date NOT NULL,
  `last_modified` date NOT NULL,
  `require_div` tinyint(4) NOT NULL,
  `require_subdiv` tinyint(4) NOT NULL,
  PRIMARY KEY (`jobnumber`)
) ENGINE=MyISAM AUTO_INCREMENT=12305 DEFAULT CHARSET=latin1;

/* Table structure for table `employee_info` */
DROP TABLE IF EXISTS `employee_info`;

CREATE TABLE `employee_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(35) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `ft_pt` varchar(10) DEFAULT NULL,
  `hourly_salary` varchar(10) DEFAULT NULL,
  `compensation` float DEFAULT NULL,
  `position` varchar(35) DEFAULT NULL,
  `division` varchar(35) DEFAULT NULL,
  `pay_increase_date` date DEFAULT NULL,
  `sin` int(11) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `td1` float DEFAULT NULL,
  `td1ab` float DEFAULT NULL,
  `home_phone` varchar(25) DEFAULT NULL,
  `home_cell` varchar(25) DEFAULT NULL,
  `home_email` varchar(40) DEFAULT NULL,
  `street` varchar(35) DEFAULT NULL,
  `city` varchar(25) DEFAULT NULL,
  `province` varchar(25) DEFAULT NULL,
  `postal_code` varchar(16) DEFAULT NULL,
  `work_email` varchar(40) DEFAULT NULL,
  `work_phone` varchar(25) DEFAULT NULL,
  `work_cell` varchar(25) DEFAULT NULL,
  `drivers_license` varchar(25) DEFAULT NULL,
  `expiry` date DEFAULT NULL,
  `emergency_contact` varchar(35) DEFAULT NULL,
  `emerg_number` varchar(25) DEFAULT NULL,
  `notes` text,
  `status` varchar(25) DEFAULT NULL,
  `supervisor` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;

/* Table structure for table `equipment` */
DROP TABLE IF EXISTS `equipment`;

CREATE TABLE `equipment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(32) DEFAULT NULL,
  `category` varchar(32) DEFAULT NULL,
  `class` varchar(32) DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  `manufacturer` varchar(32) DEFAULT NULL,
  `model` varchar(32) DEFAULT NULL,
  `serial` varchar(32) DEFAULT NULL,
  `license` varchar(12) DEFAULT NULL,
  `vin` varchar(32) DEFAULT NULL,
  `unit_number` varchar(16) DEFAULT NULL,
  `notes` varchar(512) DEFAULT NULL,
  `location` varchar(32) DEFAULT NULL,
  `daily_rate` float DEFAULT NULL,
  `weekly_rate` float DEFAULT NULL,
  `monthly_rate` float DEFAULT NULL,
  `value` float DEFAULT NULL,
  `signed_out_by` varchar(32) DEFAULT NULL,
  `sign_out_date` date DEFAULT NULL,
  `signed_in_by` varchar(32) DEFAULT NULL,
  `sign_in_date` date DEFAULT NULL,
  `status` varchar(24) DEFAULT NULL,
  `job` int(11) DEFAULT NULL,
  `owner` varchar(32) DEFAULT NULL,
  `next_scheduled_maintenance` date DEFAULT NULL,
  `date_purchased` date DEFAULT NULL,
  `times_used` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=443 DEFAULT CHARSET=latin1;

/* Table structure for table `equipment_log` */
DROP TABLE IF EXISTS `equipment_log`;

CREATE TABLE `equipment_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `job` int(11) DEFAULT NULL,
  `employee` varchar(24) DEFAULT NULL,
  `activity` varchar(32) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `tool_id` int(11) DEFAULT NULL,
  `last_sign_out` date DEFAULT NULL,
  `months_out` int(11) NOT NULL,
  `days_out` int(11) NOT NULL,
  `weeks_out` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=83 DEFAULT CHARSET=latin1;

/* Table structure for table `equipment_temp` */
DROP TABLE IF EXISTS `equipment_temp`;

CREATE TABLE `equipment_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL,
  `user` varchar(25) NOT NULL,
  `tool_id` varchar(32) NOT NULL,
  `description` varchar(128) NOT NULL,
  `manufacturer` varchar(25) NOT NULL,
  `model` varchar(25) NOT NULL,
  `serial` varchar(25) NOT NULL,
  `unit_number` varchar(25) NOT NULL,
  `location` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;

/* Table structure for table `fc_jobdata` */
DROP TABLE IF EXISTS `fc_jobdata`;

CREATE TABLE `fc_jobdata` (
  `jobnumber` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(512) DEFAULT '',
  `location` varchar(30) DEFAULT '',
  `customer` varchar(35) DEFAULT '',
  `bill_to` varchar(35) DEFAULT '',
  `supervisor` varchar(25) DEFAULT '',
  `status` varchar(20) DEFAULT '',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `quote_number` varchar(12) DEFAULT '',
  `po_number` varchar(16) DEFAULT '',
  `notes` varchar(1024) DEFAULT '',
  `invoice_number` varchar(12) DEFAULT '',
  `contact_name` varchar(25) DEFAULT '',
  `contact_number` varchar(16) DEFAULT '',
  `opened_by` varchar(25) DEFAULT '',
  `date_opened` date DEFAULT NULL,
  `date_invoiced` date DEFAULT NULL,
  `date_closed` date DEFAULT NULL,
  `last_modified` date DEFAULT NULL,
  `require_div` tinyint(1) DEFAULT NULL,
  `require_subdiv` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`jobnumber`)
) ENGINE=MyISAM AUTO_INCREMENT=33042 DEFAULT CHARSET=latin1;

/* Table structure for table `filedata` */
DROP TABLE IF EXISTS `filedata`;

CREATE TABLE `filedata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobnumber` int(11) NOT NULL,
  `path` varchar(256) NOT NULL,
  `filename` varchar(128) NOT NULL,
  `description` varchar(256) NOT NULL,
  `division` int(11) NOT NULL,
  `co_number` int(11) NOT NULL,
  `revision` varchar(12) NOT NULL,
  `count_download` int(11) NOT NULL,
  `owner` varchar(35) NOT NULL,
  `vendor` varchar(64) NOT NULL,
  `directory` varchar(64) NOT NULL,
  `upload_date` date NOT NULL,
  `document_date` date NOT NULL,
  `uid` varchar(35) NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/* Table structure for table `folder_access` */
DROP TABLE IF EXISTS `folder_access`;

CREATE TABLE `folder_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(25) DEFAULT NULL,
  `folder_id` varchar(128) DEFAULT NULL,
  `read` tinyint(1) DEFAULT '1',
  `write` tinyint(1) DEFAULT NULL,
  `delete` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=473 DEFAULT CHARSET=latin1;

/* Table structure for table `folder_list` */
DROP TABLE IF EXISTS `folder_list`;

CREATE TABLE `folder_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `foldername` varchar(128) DEFAULT NULL,
  `path` varchar(256) DEFAULT NULL,
  `status` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/* Table structure for table `g7dev_jobdata` */
DROP TABLE IF EXISTS `g7dev_jobdata`;

CREATE TABLE `g7dev_jobdata` (
  `jobnumber` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(512) DEFAULT '',
  `location` varchar(30) DEFAULT '',
  `customer` varchar(35) DEFAULT '',
  `bill_to` varchar(35) DEFAULT '',
  `supervisor` varchar(25) DEFAULT '',
  `status` varchar(20) DEFAULT '',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `quote_number` varchar(12) DEFAULT '',
  `po_number` varchar(16) DEFAULT '',
  `notes` varchar(1024) DEFAULT '',
  `invoice_number` varchar(12) DEFAULT '',
  `contact_name` varchar(25) DEFAULT '',
  `contact_number` varchar(16) DEFAULT '',
  `opened_by` varchar(25) DEFAULT '',
  `date_opened` date DEFAULT NULL,
  `date_invoiced` date DEFAULT NULL,
  `date_closed` date DEFAULT NULL,
  `last_modified` date DEFAULT NULL,
  `require_div` tinyint(1) DEFAULT NULL,
  `require_subdiv` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`jobnumber`)
) ENGINE=MyISAM AUTO_INCREMENT=22003 DEFAULT CHARSET=latin1;

/* Table structure for table `invitation_to_bid` */
DROP TABLE IF EXISTS `invitation_to_bid`;

CREATE TABLE `invitation_to_bid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(32) NOT NULL,
  `jobnumber` int(11) NOT NULL,
  `vendor` varchar(32) NOT NULL,
  `date_entered` date NOT NULL,
  `date_closing` date NOT NULL,
  `date_awarded` date NOT NULL,
  `notes` text NOT NULL,
  `filename` varchar(256) NOT NULL,
  `awarded` tinyint(4) NOT NULL,
  `division` int(11) NOT NULL,
  `sub_division` int(11) NOT NULL,
  `description` varchar(256) NOT NULL,
  `status` varchar(24) NOT NULL,
  `contact_name` varchar(35) NOT NULL,
  `email` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/* Table structure for table `jobdata` */
DROP TABLE IF EXISTS `jobdata`;

CREATE TABLE `jobdata` (
  `jobnumber` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(512) DEFAULT '',
  `location` varchar(30) DEFAULT '',
  `customer` varchar(35) DEFAULT '',
  `bill_to` varchar(35) DEFAULT '',
  `supervisor` varchar(25) DEFAULT '',
  `status` varchar(20) DEFAULT '',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `quote_number` varchar(12) DEFAULT '',
  `po_number` varchar(16) DEFAULT '',
  `notes` varchar(1024) DEFAULT '',
  `invoice_number` varchar(12) DEFAULT '',
  `contact_name` varchar(25) DEFAULT '',
  `contact_number` varchar(16) DEFAULT '',
  `opened_by` varchar(25) DEFAULT '',
  `date_opened` date DEFAULT NULL,
  `date_invoiced` date DEFAULT NULL,
  `date_closed` date DEFAULT NULL,
  `last_modified` date DEFAULT NULL,
  `require_div` tinyint(1) DEFAULT NULL,
  `require_subdiv` tinyint(1) DEFAULT NULL,
  `department` varchar(35) DEFAULT NULL,
  PRIMARY KEY (`jobnumber`),
  KEY `description` (`description`),
  KEY `location` (`location`),
  KEY `status` (`status`),
  KEY `supervisor` (`supervisor`)
) ENGINE=MyISAM AUTO_INCREMENT=13268 DEFAULT CHARSET=latin1;

/* dumping data for table `jobdata` */
insert into `jobdata` values
(1001,'Shop','','Internal','','jdeboer','Permanent','0000-00-00','0000-00-00','','','','','','','','0000-00-00','0000-00-00','0000-00-00','0000-00-00',0,0,null),
(1003,'Meeting','','Internal','','jdeboer','Permanent','0000-00-00','0000-00-00','','','','','','','','0000-00-00','0000-00-00','0000-00-00','0000-00-00',0,0,null),
(1005,'Snow Clearing','','Internal','','jdeboer','Permanent','0000-00-00','0000-00-00','','','','','','','','0000-00-00','0000-00-00','0000-00-00','0000-00-00',0,0,null),
(1007,'Training','','Internal','','jdeboer','Permanent','0000-00-00','0000-00-00','','','','','','','','0000-00-00','0000-00-00','0000-00-00','0000-00-00',0,0,null),
(1009,'Office','','Internal','','maheide','Permanent','0000-00-00','0000-00-00','','','','','','','','0000-00-00','0000-00-00','0000-00-00','0000-00-00',0,0,null),
(1015,'Vacation','','Internal','','jdeboer','Permanent','0000-00-00','0000-00-00','','','','','','','','0000-00-00','0000-00-00','0000-00-00','0000-00-00',0,0,null),
(1019,'Use Banked Hours','','Internal','','jdeboer','Permanent','0000-00-00','0000-00-00','','','','','','','','0000-00-00','0000-00-00','0000-00-00','0000-00-00',0,0,null),
(1021,'Job Quoting','','Internal','','jdeboer','Permanent','0000-00-00','0000-00-00','','','','','','','','0000-00-00','0000-00-00','0000-00-00','0000-00-00',0,0,null),
(1023,'Safety Management','','Internal','','jdeboer','Permanent','0000-00-00','0000-00-00','','','','','','','','0000-00-00','0000-00-00','0000-00-00','0000-00-00',0,0,null),
(1024,'Cleaning','Internal','Internal','Internal','jdeboer','Permanent','0000-00-00','0000-00-00','','','','','','','','0000-00-00','0000-00-00','0000-00-00','0000-00-00',0,0,null),
(1025,'Quality Control','Internal','Internal','Internal','jdeboer','Permanent','0000-00-00','0000-00-00','','','','','','','','0000-00-00','0000-00-00','0000-00-00','0000-00-00',0,0,null);


/* Table structure for table `log` */
DROP TABLE IF EXISTS `log`;

CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(35) DEFAULT NULL,
  `event` varchar(128) DEFAULT NULL,
  `details` varchar(512) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `affected` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8810 DEFAULT CHARSET=latin1;

/* Table structure for table `oc_activity` */
DROP TABLE IF EXISTS `oc_activity`;

CREATE TABLE `oc_activity` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `priority` int(11) NOT NULL DEFAULT '0',
  `type` varchar(255) COLLATE utf8_bin NOT NULL,
  `user` varchar(64) COLLATE utf8_bin NOT NULL,
  `affecteduser` varchar(64) COLLATE utf8_bin NOT NULL,
  `app` varchar(255) COLLATE utf8_bin NOT NULL,
  `subject` varchar(255) COLLATE utf8_bin NOT NULL,
  `subjectparams` varchar(255) COLLATE utf8_bin NOT NULL,
  `message` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `messageparams` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `file` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `link` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`activity_id`),
  KEY `activity_user_time` (`affecteduser`,`timestamp`),
  KEY `activity_filter_by` (`affecteduser`,`user`,`timestamp`),
  KEY `activity_filter_app` (`affecteduser`,`app`,`timestamp`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_activity_mq` */
DROP TABLE IF EXISTS `oc_activity_mq`;

CREATE TABLE `oc_activity_mq` (
  `mail_id` int(11) NOT NULL AUTO_INCREMENT,
  `amq_timestamp` int(11) NOT NULL DEFAULT '0',
  `amq_latest_send` int(11) NOT NULL DEFAULT '0',
  `amq_type` varchar(255) COLLATE utf8_bin NOT NULL,
  `amq_affecteduser` varchar(64) COLLATE utf8_bin NOT NULL,
  `amq_appid` varchar(255) COLLATE utf8_bin NOT NULL,
  `amq_subject` varchar(255) COLLATE utf8_bin NOT NULL,
  `amq_subjectparams` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`mail_id`),
  KEY `amp_user` (`amq_affecteduser`),
  KEY `amp_latest_send_time` (`amq_latest_send`),
  KEY `amp_timestamp_time` (`amq_timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_appconfig` */
DROP TABLE IF EXISTS `oc_appconfig`;

CREATE TABLE `oc_appconfig` (
  `appid` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `configkey` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `configvalue` longtext COLLATE utf8_bin,
  PRIMARY KEY (`appid`,`configkey`),
  KEY `appconfig_config_key_index` (`configkey`),
  KEY `appconfig_appid_key` (`appid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_clndr_calendars` */
DROP TABLE IF EXISTS `oc_clndr_calendars`;

CREATE TABLE `oc_clndr_calendars` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `displayname` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT '1',
  `ctag` int(10) unsigned NOT NULL DEFAULT '0',
  `calendarorder` int(10) unsigned NOT NULL DEFAULT '0',
  `calendarcolor` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `timezone` longtext COLLATE utf8_bin,
  `components` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_clndr_objects` */
DROP TABLE IF EXISTS `oc_clndr_objects`;

CREATE TABLE `oc_clndr_objects` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `calendarid` int(10) unsigned NOT NULL DEFAULT '0',
  `objecttype` varchar(40) COLLATE utf8_bin NOT NULL DEFAULT '',
  `startdate` datetime DEFAULT '1970-01-01 00:00:00',
  `enddate` datetime DEFAULT '1970-01-01 00:00:00',
  `repeating` int(11) DEFAULT '0',
  `summary` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `calendardata` longtext COLLATE utf8_bin,
  `uri` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `lastmodified` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_clndr_repeat` */
DROP TABLE IF EXISTS `oc_clndr_repeat`;

CREATE TABLE `oc_clndr_repeat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `eventid` int(10) unsigned NOT NULL DEFAULT '0',
  `calid` int(10) unsigned NOT NULL DEFAULT '0',
  `startdate` datetime DEFAULT '1970-01-01 00:00:00',
  `enddate` datetime DEFAULT '1970-01-01 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_clndr_share_calendar` */
DROP TABLE IF EXISTS `oc_clndr_share_calendar`;

CREATE TABLE `oc_clndr_share_calendar` (
  `owner` varchar(255) COLLATE utf8_bin NOT NULL,
  `share` varchar(255) COLLATE utf8_bin NOT NULL,
  `sharetype` varchar(6) COLLATE utf8_bin NOT NULL,
  `calendarid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `permissions` smallint(6) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_clndr_share_event` */
DROP TABLE IF EXISTS `oc_clndr_share_event`;

CREATE TABLE `oc_clndr_share_event` (
  `owner` varchar(255) COLLATE utf8_bin NOT NULL,
  `share` varchar(255) COLLATE utf8_bin NOT NULL,
  `sharetype` varchar(6) COLLATE utf8_bin NOT NULL,
  `eventid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `permissions` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_collaboration_comment` */
DROP TABLE IF EXISTS `oc_collaboration_comment`;

CREATE TABLE `oc_collaboration_comment` (
  `comment_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `content` varchar(3000) COLLATE utf8_bin NOT NULL,
  `creator` varchar(64) COLLATE utf8_bin NOT NULL,
  `post_id` bigint(20) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`comment_id`),
  UNIQUE KEY `pk_collaboration_comment` (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_collaboration_notification` */
DROP TABLE IF EXISTS `oc_collaboration_notification`;

CREATE TABLE `oc_collaboration_notification` (
  `post_id` bigint(20) unsigned NOT NULL,
  `visible_to` varchar(64) COLLATE utf8_bin NOT NULL,
  UNIQUE KEY `pk_collaboration_notification` (`post_id`,`visible_to`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_collaboration_post` */
DROP TABLE IF EXISTS `oc_collaboration_post`;

CREATE TABLE `oc_collaboration_post` (
  `post_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_bin NOT NULL,
  `content` varchar(3000) COLLATE utf8_bin NOT NULL,
  `creator` varchar(64) COLLATE utf8_bin DEFAULT 'NULL',
  `pid` int(10) unsigned DEFAULT '0',
  `tid` int(10) unsigned DEFAULT '0',
  `type` varchar(35) COLLATE utf8_bin NOT NULL,
  `time` datetime NOT NULL,
  `post_to_all` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`post_id`),
  UNIQUE KEY `pk_collaboration_post` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_collaboration_project` */
DROP TABLE IF EXISTS `oc_collaboration_project`;

CREATE TABLE `oc_collaboration_project` (
  `pid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_bin NOT NULL,
  `description` varchar(3000) COLLATE utf8_bin NOT NULL,
  `starting_date` datetime NOT NULL,
  `ending_date` datetime NOT NULL,
  `last_updated` datetime NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `calendar_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`pid`),
  UNIQUE KEY `uk_collaboration_project` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_collaboration_skill` */
DROP TABLE IF EXISTS `oc_collaboration_skill`;

CREATE TABLE `oc_collaboration_skill` (
  `member` varchar(64) COLLATE utf8_bin NOT NULL,
  `skill` varchar(30) COLLATE utf8_bin NOT NULL,
  `experience` smallint(6) NOT NULL DEFAULT '0',
  `exp_on_date` date NOT NULL,
  `expertise` smallint(6) NOT NULL DEFAULT '0',
  UNIQUE KEY `pk_collaboration_skill` (`member`,`skill`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_collaboration_task` */
DROP TABLE IF EXISTS `oc_collaboration_task`;

CREATE TABLE `oc_collaboration_task` (
  `tid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_bin NOT NULL,
  `description` varchar(3000) COLLATE utf8_bin NOT NULL,
  `creator` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `pid` int(10) unsigned NOT NULL,
  `priority` smallint(5) unsigned NOT NULL,
  `starting_time` datetime NOT NULL,
  `ending_time` datetime NOT NULL,
  `event_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`tid`),
  UNIQUE KEY `pk_collaboration_task` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_collaboration_task_status` */
DROP TABLE IF EXISTS `oc_collaboration_task_status`;

CREATE TABLE `oc_collaboration_task_status` (
  `tid` int(10) unsigned NOT NULL,
  `status` varchar(30) COLLATE utf8_bin NOT NULL,
  `last_updated_time` datetime NOT NULL,
  `member` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `reason` varchar(300) COLLATE utf8_bin DEFAULT NULL,
  UNIQUE KEY `pk_collaboration_task_status` (`tid`,`status`,`last_updated_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_collaboration_works_on` */
DROP TABLE IF EXISTS `oc_collaboration_works_on`;

CREATE TABLE `oc_collaboration_works_on` (
  `pid` int(10) unsigned NOT NULL,
  `member` varchar(64) COLLATE utf8_bin NOT NULL,
  `role` varchar(30) COLLATE utf8_bin NOT NULL,
  UNIQUE KEY `pk_collaboration_works_on` (`pid`,`member`,`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_contacts_addressbooks` */
DROP TABLE IF EXISTS `oc_contacts_addressbooks`;

CREATE TABLE `oc_contacts_addressbooks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `displayname` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `uri` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `ctag` int(10) unsigned NOT NULL DEFAULT '1',
  `active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `c_addressbook_userid_index` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_contacts_cards` */
DROP TABLE IF EXISTS `oc_contacts_cards`;

CREATE TABLE `oc_contacts_cards` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `addressbookid` int(10) unsigned NOT NULL DEFAULT '0',
  `fullname` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `carddata` longtext COLLATE utf8_bin,
  `uri` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `lastmodified` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `c_addressbookid_index` (`addressbookid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_contacts_cards_properties` */
DROP TABLE IF EXISTS `oc_contacts_cards_properties`;

CREATE TABLE `oc_contacts_cards_properties` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `contactid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `value` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `preferred` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `cp_contactid_index` (`contactid`),
  KEY `cp_name_index` (`name`),
  KEY `cp_value_index` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_documents_invite` */
DROP TABLE IF EXISTS `oc_documents_invite`;

CREATE TABLE `oc_documents_invite` (
  `es_id` varchar(64) COLLATE utf8_bin NOT NULL COMMENT 'Related editing session id',
  `uid` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `status` smallint(6) DEFAULT '0',
  `sent_on` int(10) unsigned DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_documents_member` */
DROP TABLE IF EXISTS `oc_documents_member`;

CREATE TABLE `oc_documents_member` (
  `member_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique per user and session',
  `es_id` varchar(64) COLLATE utf8_bin NOT NULL COMMENT 'Related editing session id',
  `uid` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `color` varchar(32) COLLATE utf8_bin DEFAULT NULL,
  `last_activity` int(10) unsigned DEFAULT NULL,
  `is_guest` smallint(5) unsigned NOT NULL DEFAULT '0',
  `token` varchar(32) COLLATE utf8_bin DEFAULT NULL,
  `status` smallint(5) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_documents_op` */
DROP TABLE IF EXISTS `oc_documents_op`;

CREATE TABLE `oc_documents_op` (
  `seq` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Sequence number',
  `es_id` varchar(64) COLLATE utf8_bin NOT NULL COMMENT 'Editing session id',
  `member` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'User and time specific',
  `opspec` longtext COLLATE utf8_bin COMMENT 'json-string',
  PRIMARY KEY (`seq`),
  UNIQUE KEY `documents_op_eis_idx` (`es_id`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_documents_revisions` */
DROP TABLE IF EXISTS `oc_documents_revisions`;

CREATE TABLE `oc_documents_revisions` (
  `es_id` varchar(64) COLLATE utf8_bin NOT NULL COMMENT 'Related editing session id',
  `seq_head` int(10) unsigned NOT NULL COMMENT 'Sequence head number',
  `member_id` int(10) unsigned NOT NULL COMMENT 'the member that saved the revision',
  `file_id` varchar(512) COLLATE utf8_bin DEFAULT NULL COMMENT 'Relative to storage e.g. /welcome.odt',
  `save_hash` varchar(128) COLLATE utf8_bin NOT NULL COMMENT 'used to lookup revision in documents folder of member, eg hash.odt',
  UNIQUE KEY `documents_rev_eis_idx` (`es_id`,`seq_head`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_documents_session` */
DROP TABLE IF EXISTS `oc_documents_session`;

CREATE TABLE `oc_documents_session` (
  `es_id` varchar(64) COLLATE utf8_bin NOT NULL COMMENT 'Editing session id',
  `genesis_url` varchar(512) COLLATE utf8_bin DEFAULT NULL COMMENT 'Relative to owner documents storage /welcome.odt',
  `genesis_hash` varchar(128) COLLATE utf8_bin NOT NULL COMMENT 'To be sure the genesis did not change',
  `file_id` varchar(512) COLLATE utf8_bin DEFAULT NULL COMMENT 'Relative to storage e.g. /welcome.odt',
  `owner` varchar(64) COLLATE utf8_bin NOT NULL COMMENT 'oC user who created the session',
  PRIMARY KEY (`es_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_file_map` */
DROP TABLE IF EXISTS `oc_file_map`;

CREATE TABLE `oc_file_map` (
  `logic_path` varchar(512) COLLATE utf8_bin NOT NULL DEFAULT '',
  `logic_path_hash` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `physic_path` varchar(512) COLLATE utf8_bin NOT NULL DEFAULT '',
  `physic_path_hash` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`logic_path_hash`),
  UNIQUE KEY `file_map_pp_index` (`physic_path_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_filecache` */
DROP TABLE IF EXISTS `oc_filecache`;

CREATE TABLE `oc_filecache` (
  `fileid` int(11) NOT NULL AUTO_INCREMENT,
  `storage` int(11) NOT NULL DEFAULT '0',
  `path` varchar(4000) COLLATE utf8_bin DEFAULT NULL,
  `path_hash` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `parent` int(11) NOT NULL DEFAULT '0',
  `name` varchar(250) COLLATE utf8_bin DEFAULT NULL,
  `mimetype` int(11) NOT NULL DEFAULT '0',
  `mimepart` int(11) NOT NULL DEFAULT '0',
  `size` bigint(20) NOT NULL DEFAULT '0',
  `mtime` int(11) NOT NULL DEFAULT '0',
  `storage_mtime` int(11) NOT NULL DEFAULT '0',
  `encrypted` int(11) NOT NULL DEFAULT '0',
  `unencrypted_size` bigint(20) NOT NULL DEFAULT '0',
  `etag` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `permissions` int(11) DEFAULT '0',
  PRIMARY KEY (`fileid`),
  UNIQUE KEY `fs_storage_path_hash` (`storage`,`path_hash`),
  KEY `fs_parent_name_hash` (`parent`,`name`),
  KEY `fs_storage_mimetype` (`storage`,`mimetype`),
  KEY `fs_storage_mimepart` (`storage`,`mimepart`),
  KEY `fs_storage_size` (`storage`,`size`,`fileid`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_files_trash` */
DROP TABLE IF EXISTS `oc_files_trash`;

CREATE TABLE `oc_files_trash` (
  `auto_id` int(11) NOT NULL AUTO_INCREMENT,
  `id` varchar(250) COLLATE utf8_bin NOT NULL DEFAULT '',
  `user` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `timestamp` varchar(12) COLLATE utf8_bin NOT NULL DEFAULT '',
  `location` varchar(512) COLLATE utf8_bin NOT NULL DEFAULT '',
  `type` varchar(4) COLLATE utf8_bin DEFAULT NULL,
  `mime` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`auto_id`),
  KEY `id_index` (`id`),
  KEY `timestamp_index` (`timestamp`),
  KEY `user_index` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_gallery_sharing` */
DROP TABLE IF EXISTS `oc_gallery_sharing`;

CREATE TABLE `oc_gallery_sharing` (
  `token` varchar(64) COLLATE utf8_bin NOT NULL,
  `gallery_id` int(11) NOT NULL DEFAULT '0',
  `recursive` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_group_admin` */
DROP TABLE IF EXISTS `oc_group_admin`;

CREATE TABLE `oc_group_admin` (
  `gid` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `uid` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`gid`,`uid`),
  KEY `group_admin_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_group_user` */
DROP TABLE IF EXISTS `oc_group_user`;

CREATE TABLE `oc_group_user` (
  `gid` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `uid` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`gid`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_groups` */
DROP TABLE IF EXISTS `oc_groups`;

CREATE TABLE `oc_groups` (
  `gid` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_jobs` */
DROP TABLE IF EXISTS `oc_jobs`;

CREATE TABLE `oc_jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `argument` varchar(256) COLLATE utf8_bin NOT NULL DEFAULT '',
  `last_run` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `job_class_index` (`class`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_locks` */
DROP TABLE IF EXISTS `oc_locks`;

CREATE TABLE `oc_locks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `owner` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `timeout` int(10) unsigned DEFAULT NULL,
  `created` bigint(20) DEFAULT NULL,
  `token` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `scope` smallint(6) DEFAULT NULL,
  `depth` smallint(6) DEFAULT NULL,
  `uri` longtext COLLATE utf8_bin,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_lucene_status` */
DROP TABLE IF EXISTS `oc_lucene_status`;

CREATE TABLE `oc_lucene_status` (
  `fileid` int(11) NOT NULL DEFAULT '0',
  `status` varchar(1) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`fileid`),
  KEY `status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_mimetypes` */
DROP TABLE IF EXISTS `oc_mimetypes`;

CREATE TABLE `oc_mimetypes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mimetype` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mimetype_id_index` (`mimetype`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_ocDashboard_usedHashs` */
DROP TABLE IF EXISTS `oc_ocDashboard_usedHashs`;

CREATE TABLE `oc_ocDashboard_usedHashs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usedHash` varchar(64) COLLATE utf8_bin NOT NULL,
  `widget` varchar(100) COLLATE utf8_bin NOT NULL,
  `user` varchar(100) COLLATE utf8_bin NOT NULL,
  `timestamp` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_pictures_images_cache` */
DROP TABLE IF EXISTS `oc_pictures_images_cache`;

CREATE TABLE `oc_pictures_images_cache` (
  `uid_owner` varchar(64) COLLATE utf8_bin NOT NULL,
  `path` varchar(256) COLLATE utf8_bin NOT NULL,
  `width` int(11) NOT NULL,
  `height` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_preferences` */
DROP TABLE IF EXISTS `oc_preferences`;

CREATE TABLE `oc_preferences` (
  `userid` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `appid` varchar(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `configkey` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `configvalue` longtext COLLATE utf8_bin,
  PRIMARY KEY (`userid`,`appid`,`configkey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_privatedata` */
DROP TABLE IF EXISTS `oc_privatedata`;

CREATE TABLE `oc_privatedata` (
  `keyid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `app` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `key` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `value` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`keyid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_properties` */
DROP TABLE IF EXISTS `oc_properties`;

CREATE TABLE `oc_properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `propertypath` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `propertyname` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  `propertyvalue` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `property_index` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_roundcube` */
DROP TABLE IF EXISTS `oc_roundcube`;

CREATE TABLE `oc_roundcube` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `oc_user` varchar(4096) COLLATE utf8_bin NOT NULL DEFAULT '',
  `mail_user` varchar(4096) COLLATE utf8_bin NOT NULL DEFAULT '',
  `mail_password` varchar(4096) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_share` */
DROP TABLE IF EXISTS `oc_share`;

CREATE TABLE `oc_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `share_type` smallint(6) NOT NULL DEFAULT '0',
  `share_with` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `uid_owner` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `parent` int(11) DEFAULT NULL,
  `item_type` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `item_source` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `item_target` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `file_source` int(11) DEFAULT NULL,
  `file_target` varchar(512) COLLATE utf8_bin DEFAULT NULL,
  `permissions` smallint(6) NOT NULL DEFAULT '0',
  `stime` bigint(20) NOT NULL DEFAULT '0',
  `accepted` smallint(6) NOT NULL DEFAULT '0',
  `expiration` datetime DEFAULT NULL,
  `token` varchar(32) COLLATE utf8_bin DEFAULT NULL,
  `mail_send` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `item_share_type_index` (`item_type`,`share_type`),
  KEY `file_source_index` (`file_source`),
  KEY `token_index` (`token`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_share_external` */
DROP TABLE IF EXISTS `oc_share_external`;

CREATE TABLE `oc_share_external` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `remote` varchar(512) COLLATE utf8_bin NOT NULL COMMENT 'Url of the remove owncloud instance',
  `share_token` varchar(64) COLLATE utf8_bin NOT NULL COMMENT 'Public share token',
  `password` varchar(64) COLLATE utf8_bin NOT NULL COMMENT 'Optional password for the public share',
  `name` varchar(64) COLLATE utf8_bin NOT NULL COMMENT 'Original name on the remote server',
  `owner` varchar(64) COLLATE utf8_bin NOT NULL COMMENT 'User that owns the public share on the remote server',
  `user` varchar(64) COLLATE utf8_bin NOT NULL COMMENT 'Local user which added the external share',
  `mountpoint` varchar(4000) COLLATE utf8_bin NOT NULL COMMENT 'Full path where the share is mounted',
  `mountpoint_hash` varchar(32) COLLATE utf8_bin NOT NULL COMMENT 'md5 hash of the mountpoint',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sh_external_mp` (`user`,`mountpoint_hash`),
  KEY `sh_external_user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_storages` */
DROP TABLE IF EXISTS `oc_storages`;

CREATE TABLE `oc_storages` (
  `id` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `numeric_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`numeric_id`),
  UNIQUE KEY `storages_id_index` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_users` */
DROP TABLE IF EXISTS `oc_users`;

CREATE TABLE `oc_users` (
  `uid` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `displayname` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_users_external` */
DROP TABLE IF EXISTS `oc_users_external`;

CREATE TABLE `oc_users_external` (
  `backend` varchar(128) COLLATE utf8_bin NOT NULL DEFAULT '',
  `uid` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `displayname` varchar(64) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`uid`,`backend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_vcategory` */
DROP TABLE IF EXISTS `oc_vcategory`;

CREATE TABLE `oc_vcategory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `type` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `category` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `uid_index` (`uid`),
  KEY `type_index` (`type`),
  KEY `category_index` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `oc_vcategory_to_object` */
DROP TABLE IF EXISTS `oc_vcategory_to_object`;

CREATE TABLE `oc_vcategory_to_object` (
  `objid` int(10) unsigned NOT NULL DEFAULT '0',
  `categoryid` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`categoryid`,`objid`,`type`),
  KEY `vcategory_objectd_index` (`objid`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/* Table structure for table `omega_jobdata` */
DROP TABLE IF EXISTS `omega_jobdata`;

CREATE TABLE `omega_jobdata` (
  `jobnumber` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(512) DEFAULT '',
  `location` varchar(30) DEFAULT '',
  `customer` varchar(35) DEFAULT '',
  `bill_to` varchar(35) DEFAULT '',
  `supervisor` varchar(25) DEFAULT '',
  `status` varchar(20) DEFAULT '',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `quote_number` varchar(12) DEFAULT '',
  `po_number` varchar(16) DEFAULT '',
  `notes` varchar(1024) DEFAULT '',
  `invoice_number` varchar(12) DEFAULT '',
  `contact_name` varchar(25) DEFAULT '',
  `contact_number` varchar(16) DEFAULT '',
  `opened_by` varchar(25) DEFAULT '',
  `date_opened` date DEFAULT NULL,
  `date_invoiced` date DEFAULT NULL,
  `date_closed` date DEFAULT NULL,
  `last_modified` date DEFAULT NULL,
  `require_div` tinyint(1) DEFAULT NULL,
  `require_subdiv` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`jobnumber`)
) ENGINE=MyISAM AUTO_INCREMENT=22041 DEFAULT CHARSET=latin1;

/* Table structure for table `phpauthent_groups` */
DROP TABLE IF EXISTS `phpauthent_groups`;

CREATE TABLE `phpauthent_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL DEFAULT '',
  `description` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

/* Table structure for table `phpauthent_relation` */
DROP TABLE IF EXISTS `phpauthent_relation`;

CREATE TABLE `phpauthent_relation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=361 DEFAULT CHARSET=latin1;

/* Table structure for table `phpauthent_users` */
DROP TABLE IF EXISTS `phpauthent_users`;

CREATE TABLE `phpauthent_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '',
  `password` varchar(64) DEFAULT NULL,
  `realname` varchar(80) DEFAULT NULL,
  `email` varchar(120) DEFAULT NULL,
  `lastlogin` datetime DEFAULT NULL,
  `creation` date DEFAULT NULL,
  `numlogins` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;

/* Table structure for table `purchase_order` */
DROP TABLE IF EXISTS `purchase_order`;

CREATE TABLE `purchase_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobnumber` int(11) NOT NULL,
  `po_number` varchar(15) DEFAULT NULL,
  `date_entered` date DEFAULT NULL,
  `vendor` varchar(25) DEFAULT NULL,
  `payment_method` varchar(15) DEFAULT NULL,
  `card_number` varchar(18) DEFAULT NULL,
  `notes` varchar(1024) DEFAULT '',
  `date_required` date DEFAULT NULL,
  `invoice` varchar(12) DEFAULT NULL,
  `date_received` date DEFAULT NULL,
  `approved` tinyint(1) DEFAULT NULL,
  `email_to` varchar(40) DEFAULT NULL,
  `ship_street` varchar(40) DEFAULT NULL,
  `ship_city` varchar(25) DEFAULT NULL,
  `ship_province` varchar(25) DEFAULT NULL,
  `ship_postal_code` varchar(10) DEFAULT NULL,
  `fob` varchar(25) DEFAULT NULL,
  `ship_via` varchar(25) DEFAULT NULL,
  `status` varchar(15) DEFAULT NULL,
  `entered_by` varchar(25) NOT NULL,
  `division` int(11) NOT NULL,
  `changeorder` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `vendor` (`vendor`)
) ENGINE=MyISAM AUTO_INCREMENT=4571 DEFAULT CHARSET=latin1;

/* Table structure for table `purchase_order_items` */
DROP TABLE IF EXISTS `purchase_order_items`;

CREATE TABLE `purchase_order_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `po_number` varchar(15) DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `division` int(11) DEFAULT NULL,
  `sub_division` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7711 DEFAULT CHARSET=latin1;

/* Table structure for table `quote` */
DROP TABLE IF EXISTS `quote`;

CREATE TABLE `quote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote_number` varchar(16) DEFAULT NULL,
  `name` varchar(25) DEFAULT NULL,
  `company` varchar(35) DEFAULT NULL,
  `street` varchar(45) DEFAULT NULL,
  `city` varchar(25) DEFAULT NULL,
  `province` varchar(20) DEFAULT NULL,
  `postal_code` varchar(7) DEFAULT NULL,
  `phone` varchar(16) DEFAULT NULL,
  `fax` varchar(16) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `terms` varchar(15) DEFAULT NULL,
  `valid` varchar(12) DEFAULT NULL,
  `entered_by` varchar(25) DEFAULT NULL,
  `filename` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=165 DEFAULT CHARSET=latin1;

/* Table structure for table `quote_items` */
DROP TABLE IF EXISTS `quote_items`;

CREATE TABLE `quote_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `quote_number` varchar(16) DEFAULT NULL,
  `item` int(11) DEFAULT NULL,
  `description` varchar(512) DEFAULT NULL,
  `quantity` float DEFAULT NULL,
  `rate` float DEFAULT NULL,
  `total` float DEFAULT NULL,
  `accepted` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1034 DEFAULT CHARSET=latin1;

/* Table structure for table `request_for_information` */
DROP TABLE IF EXISTS `request_for_information`;

CREATE TABLE `request_for_information` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobnumber` int(11) DEFAULT NULL,
  `rfi_number` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `description` varchar(128) DEFAULT NULL,
  `concern` text,
  `response` text,
  `entered_by` varchar(25) DEFAULT NULL,
  `rfi_from` varchar(512) DEFAULT NULL,
  `rfi_to` varchar(512) DEFAULT NULL,
  `rfi_cc` varchar(512) DEFAULT NULL,
  `filename` varchar(256) DEFAULT NULL,
  `sent_on` date DEFAULT NULL,
  `returned_files` varchar(512) DEFAULT NULL,
  `status` varchar(25) DEFAULT NULL,
  `forwarded_to_consultant` date DEFAULT NULL,
  `received_from_consultant` date DEFAULT NULL,
  `returned_to_contractor` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=latin1;

/* Table structure for table `shop_drawings` */
DROP TABLE IF EXISTS `shop_drawings`;

CREATE TABLE `shop_drawings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobnumber` int(11) NOT NULL,
  `vendor` varchar(35) NOT NULL,
  `filename` varchar(128) NOT NULL,
  `description` varchar(256) NOT NULL,
  `status` varchar(25) NOT NULL,
  `date_received` date NOT NULL,
  `date_approved` date NOT NULL,
  `notes` text NOT NULL,
  `path` varchar(256) NOT NULL,
  `uid` varchar(35) NOT NULL,
  `division` int(11) NOT NULL,
  `co_number` int(11) NOT NULL,
  `revision` varchar(12) NOT NULL,
  `owner` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/* Table structure for table `site_instructions` */
DROP TABLE IF EXISTS `site_instructions`;

CREATE TABLE `site_instructions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobnumber` int(11) NOT NULL,
  `si_number` int(11) NOT NULL,
  `si_date` date NOT NULL,
  `date_entered` date NOT NULL,
  `description` varchar(128) NOT NULL,
  `notes` varchar(256) NOT NULL,
  `status` varchar(20) NOT NULL,
  `uid` varchar(25) NOT NULL,
  `received_from` varchar(30) NOT NULL,
  `filename` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobnumber` (`jobnumber`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/* Table structure for table `snowlog` */
DROP TABLE IF EXISTS `snowlog`;

CREATE TABLE `snowlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `location` varchar(35) DEFAULT NULL,
  `employee` varchar(25) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `hours` float DEFAULT NULL,
  `activity` varchar(15) DEFAULT NULL,
  `salt_bags` float DEFAULT NULL,
  `loads_hauled` float DEFAULT NULL,
  `approved` tinyint(1) DEFAULT NULL,
  `timelog_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7656 DEFAULT CHARSET=latin1;

/* Table structure for table `timelog` */
DROP TABLE IF EXISTS `timelog`;

CREATE TABLE `timelog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobnumber` int(11) DEFAULT NULL,
  `employee` varchar(25) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `hours` float DEFAULT NULL,
  `division` varchar(5) DEFAULT NULL,
  `sub_division` varchar(6) DEFAULT NULL,
  `comment` text,
  `approved` tinyint(1) DEFAULT NULL,
  `change_order` int(1) DEFAULT NULL,
  `uid` varchar(35) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobnumber` (`jobnumber`),
  KEY `employee` (`employee`)
) ENGINE=MyISAM AUTO_INCREMENT=61369 DEFAULT CHARSET=latin1;

/* Table structure for table `vendor_contracts` */
DROP TABLE IF EXISTS `vendor_contracts`;

CREATE TABLE `vendor_contracts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobnumber` int(11) NOT NULL,
  `contract_number` varchar(25) NOT NULL,
  `division` int(11) NOT NULL,
  `subdivision` int(11) NOT NULL,
  `co_number` varchar(16) NOT NULL,
  `vendor` varchar(35) NOT NULL,
  `contract_date` date NOT NULL,
  `date_entered` date NOT NULL,
  `description` varchar(1024) NOT NULL,
  `notes` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `amount` float NOT NULL,
  `filename` varchar(128) NOT NULL,
  `uid` varchar(40) NOT NULL,
  `sent_to` varchar(35) NOT NULL,
  `approved_by` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/* Table structure for table `vendor_data` */
DROP TABLE IF EXISTS `vendor_data`;

CREATE TABLE `vendor_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Vendor` varchar(35) NOT NULL,
  `Contact` varchar(35) NOT NULL,
  `Number` varchar(16) NOT NULL,
  `Email` varchar(48) NOT NULL,
  `Street` varchar(48) NOT NULL,
  `City` varchar(35) NOT NULL,
  `Prov` varchar(25) NOT NULL,
  `Postal` varchar(12) NOT NULL,
  `Discount` float NOT NULL,
  `Notes` text NOT NULL,
  `date_entered` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

/* Table structure for table `vendor_invoices` */
DROP TABLE IF EXISTS `vendor_invoices`;

CREATE TABLE `vendor_invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jobnumber` int(11) NOT NULL,
  `invoice_number` varchar(25) NOT NULL,
  `division` int(11) NOT NULL,
  `subdivision` int(11) NOT NULL,
  `po_number` varchar(16) NOT NULL,
  `vendor` varchar(35) NOT NULL,
  `invoice_date` date NOT NULL,
  `date_entered` date NOT NULL,
  `date_approved` date NOT NULL,
  `date_rejected` date NOT NULL,
  `notes` text NOT NULL,
  `status` varchar(20) NOT NULL,
  `amount` float NOT NULL,
  `filename` varchar(128) NOT NULL,
  `uid` varchar(40) NOT NULL,
  `sent_to` varchar(35) NOT NULL,
  `approved_rejected_by` varchar(35) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=154 DEFAULT CHARSET=latin1;

/* Table structure for table `workorder` */
DROP TABLE IF EXISTS `workorder`;

CREATE TABLE `workorder` (
  `jobnumber` int(11) NOT NULL,
  `ready_to_invoice` tinyint(1) DEFAULT NULL,
  `quote` tinyint(1) DEFAULT NULL,
  `solution` varchar(1024) DEFAULT '',
  `comments` varchar(1024) DEFAULT '',
  `expense_1` varchar(20) DEFAULT '',
  `expense_2` varchar(20) DEFAULT '',
  `expense_3` varchar(20) DEFAULT '',
  `expense_4` varchar(20) DEFAULT '',
  `cost_1` float DEFAULT NULL,
  `cost_2` float DEFAULT NULL,
  `cost_3` float DEFAULT NULL,
  `cost_4` float DEFAULT NULL,
  PRIMARY KEY (`jobnumber`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

