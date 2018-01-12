-- --------------------------------------------------------
-- $Author: vincentarn $
-- $Date: 2005/04/08 12:54:36 $
-- $Id: phpauthent.sql,v 1.3 2005/04/08 12:54:36 vincentarn Exp $
-- $Revision: 1.3 $
-- --------------------------------------------------------

-- phpAuthent - A security module for PHP enabled web sites
-- Copyright (C) 2005 Arnaud Vincent

-- This file is part of phpAuthent.

-- phpAuthent is free software; you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation; either version 2 of the License, or
-- (at your option) any later version.

-- phpAuthent is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.

-- You should have received a copy of the GNU General Public License
-- along with this program; if not, write to the Free Software
-- Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

-- Contact author at vincentarn@users.sourceforge.net

-- --------------------------------------------------------
-- phpAuthent Database Structure
-- 
-- INSTALLATION
-- A. Run this script if you're operating a full installation or replacing completely
--    an existing release.
-- B. Tables are not automatically dropped, if you're replacing existing tables, you
--    should manually drop the following tables :
--    - phpauthent_groups
--    - phpauthent_users
--    - phpauthent_relation
-- 1. For a full installation, choose or create a database to host phpAuthent tables
-- 2. Execute this script on your MySQL server using either command line options or
--    a tool such as phpMyAdmin.
-- 
-- phpAuthent PHP Security Module : http://phpauth.sf.net

-- 
-- Groups table structure
--
CREATE TABLE `phpauthent_groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `description` varchar(80) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Relations table structure
--
CREATE TABLE `phpauthent_relation` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `group_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- 
-- Users table structure
--
CREATE TABLE `phpauthent_users` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(32) NOT NULL default '',
  `password` varchar(64) default NULL,
  `realname` varchar(80) default NULL,
  `email` varchar(120) default NULL,
  `lastlogin` DATETIME,
  `creation` DATE,
  `numlogins` INT DEFAULT '0' NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

-- Initialisation
-- An administrator (name 'administrator', password 'phpauthent') is created at engine initialization
-- A user who belongs to the 'admin' group, and is necessary to access the administration interface.
INSERT INTO `phpauthent_groups` ( `id` , `name` ) VALUES ('1', 'admin');
INSERT INTO `phpauthent_relation` ( `id` , `user_id` , `group_id` ) VALUES ('1', '1', '1');
INSERT INTO `phpauthent_users` ( `id` , `username` , `password` ) VALUES ('1', 'administrator', 'cad6c0c3e5a9e1afc4de' );