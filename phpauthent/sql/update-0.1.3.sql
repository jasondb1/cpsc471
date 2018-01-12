-- --------------------------------------------------------
-- $Author: vincentarn $
-- $Date: 2005/04/08 12:54:36 $
-- $Id: update-0.1.3.sql,v 1.2 2005/04/08 12:54:36 vincentarn Exp $
-- $Revision: 1.2 $
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
-- phpAuthent Database Update
-- 
-- RELEASE NOTE
-- This script should be run ONLY if you're upgrading from version 0.1.2 to 0.1.3.
-- If you're migrating from an older version, please apply successive updates scripts
-- from the older to the newer.
-- 
-- phpAuthent PHP Security Module : http://phpauth.sf.net

ALTER TABLE `phpauthent_users` CHANGE `password` `password` VARCHAR( 64 ) DEFAULT NULL ;