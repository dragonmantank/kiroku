-- phpMyAdmin SQL Dump
-- version 2.9.0.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Mar 04, 2007 at 12:02 AM
-- Server version: 4.1.21
-- PHP Version: 4.4.2
-- 
-- Database: `sbiffcms`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `sd_module_link_to_page`
-- 

CREATE TABLE `sd_module_link_to_page` (
  `id` int(11) NOT NULL auto_increment,
  `page_id` int(11) NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `url` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Dumping data for table `sd_module_link_to_page`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `sd_module_text`
-- 

CREATE TABLE `sd_module_text` (
  `id` int(11) NOT NULL auto_increment,
  `page_id` int(11) NOT NULL default '0',
  `text` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- 
-- Dumping data for table `sd_module_text`
-- 

INSERT INTO `sd_module_text` VALUES (1, 102, '');

-- --------------------------------------------------------

-- 
-- Table structure for table `sd_modules`
-- 

CREATE TABLE `sd_modules` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `description` mediumtext NOT NULL,
  `active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- 
-- Dumping data for table `sd_modules`
-- 

INSERT INTO `sd_modules` VALUES (7, 'text', 'Allows unfiltered text input', 1);
INSERT INTO `sd_modules` VALUES (3, 'link_to_page', 'Sends the user to another page', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `sd_modules_config`
-- 

CREATE TABLE `sd_modules_config` (
  `id` int(11) NOT NULL auto_increment,
  `module_id` int(11) NOT NULL default '0',
  `var` varchar(255) NOT NULL default '',
  `data` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

-- 
-- Dumping data for table `sd_modules_config`
-- 

INSERT INTO `sd_modules_config` VALUES (8, 7, 'editor', 'basic');
INSERT INTO `sd_modules_config` VALUES (6, 3, 'redirect_message', 'You will be redirected shortly');
INSERT INTO `sd_modules_config` VALUES (5, 3, 'show_redirect_message', 'no');
INSERT INTO `sd_modules_config` VALUES (7, 3, 'redirect_timeout', '5');

-- --------------------------------------------------------

-- 
-- Table structure for table `sd_pages`
-- 

CREATE TABLE `sd_pages` (
  `id` int(11) NOT NULL auto_increment,
  `site_id` int(11) NOT NULL default '1',
  `name` varchar(255) NOT NULL default '',
  `link_name` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `description` mediumtext NOT NULL,
  `parent_page` int(11) NOT NULL default '0',
  `module` int(11) NOT NULL default '0',
  `active` int(11) NOT NULL default '0',
  `homepage` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=113 ;

-- 
-- Dumping data for table `sd_pages`
-- 

INSERT INTO `sd_pages` VALUES (102, 1, 'Home', 'Home', 'Home', 'Home', 0, 7, 1, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `sd_site_info`
-- 

CREATE TABLE `sd_site_info` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(100) NOT NULL default '',
  `server_path` varchar(255) NOT NULL default '',
  `site_name` varchar(255) NOT NULL default '',
  `site_tagline` varchar(255) NOT NULL default '',
  `admin_name` varchar(255) NOT NULL default '',
  `admin_email` varchar(255) NOT NULL default '',
  `current_theme` varchar(255) NOT NULL default '',
  `default` smallint(6) NOT NULL default '0',
  `active` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `sd_site_info`
-- 

INSERT INTO `sd_site_info` VALUES (1, 'yourdomain.com', '/var/www/', 'BiffCMS', '', 'Your Name', 'admin@yourdomain.com', 'working', 1, 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `sd_user_accounts`
-- 

CREATE TABLE `sd_user_accounts` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(50) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  `user_group` varchar(255) NOT NULL default '0',
  `first_name` varchar(50) NOT NULL default '',
  `last_name` varchar(50) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `active` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- 
-- Dumping data for table `sd_user_accounts`
-- 

INSERT INTO `sd_user_accounts` VALUES (1, 'root', 'bef3452591febf751a9333de927f2d9c', '1', 'Root', 'User', 'admin@yourdomain.com', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `sd_user_groups`
-- 

CREATE TABLE `sd_user_groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `comments` varchar(255) NOT NULL default '',
  `active` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- 
-- Dumping data for table `sd_user_groups`
-- 

INSERT INTO `sd_user_groups` VALUES (1, 'CMS Super Users', 'This group contains root access', 1);
INSERT INTO `sd_user_groups` VALUES (2, 'CMS Administrators', 'Site Administrators', 1);
INSERT INTO `sd_user_groups` VALUES (3, 'CMS Users', 'Regular site users', 1);
INSERT INTO `sd_user_groups` VALUES (4, 'CMS Page Admin', 'Can edit pages', 1);
INSERT INTO `sd_user_groups` VALUES (5, 'CMS Module Admin', 'Can install or remove Modules', 1);
INSERT INTO `sd_user_groups` VALUES (6, 'CMS Site Admin', 'Can alter site settings', 1);
INSERT INTO `sd_user_groups` VALUES (7, 'CMS Domain Admin', 'Can add, edit, or delete domains', 1);
INSERT INTO `sd_user_groups` VALUES (8, 'CMS User Admin', 'Can add, edit, or delete users', 1);
