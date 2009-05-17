-- phpMyAdmin SQL Dump
-- version 2.11.8.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 28, 2008 at 12:27 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `Kiroku`
--

-- --------------------------------------------------------

--
-- Table structure for table `cms_modules`
--

CREATE TABLE IF NOT EXISTS `cms_modules` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `description` mediumtext NOT NULL,
  `active` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cms_modules`
--

INSERT INTO `cms_modules` (`id`, `name`, `description`, `active`) VALUES
(1, 'text', 'Allows unfiltered text input', 1),
(2, 'cmslink', 'Link to a section of the CMS', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cms_modules_config`
--

CREATE TABLE IF NOT EXISTS `cms_modules_config` (
  `id` int(11) NOT NULL auto_increment,
  `module_id` int(11) NOT NULL default '0',
  `var` varchar(255) NOT NULL default '',
  `data` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `cms_modules_config`
--


-- --------------------------------------------------------

--
-- Table structure for table `cms_module_cmslink`
--

CREATE TABLE IF NOT EXISTS `cms_module_cmslink` (
  `id` int(11) NOT NULL auto_increment,
  `pageId` int(11) NOT NULL,
  `linkName` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cms_module_cmslink`
--

INSERT INTO `cms_module_cmslink` (`id`, `pageId`, `linkName`, `module`, `controller`, `action`) VALUES
(2, 3, 'Admin', 'admin', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `cms_module_text`
--

CREATE TABLE IF NOT EXISTS `cms_module_text` (
  `id` int(11) NOT NULL auto_increment,
  `page_id` int(11) NOT NULL default '0',
  `text` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cms_module_text`
--

INSERT INTO `cms_module_text` (`id`, `page_id`, `text`) VALUES
(1, 1, 'This is the homepage!');

-- --------------------------------------------------------

--
-- Table structure for table `cms_pages`
--

CREATE TABLE IF NOT EXISTS `cms_pages` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `link_name` varchar(255) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `description` mediumtext NOT NULL,
  `parent_page` int(11) NOT NULL default '0',
  `module` int(11) NOT NULL default '0',
  `active` int(11) NOT NULL default '0',
  `homepage` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cms_pages`
--

INSERT INTO `cms_pages` (`id`, `name`, `link_name`, `title`, `description`, `parent_page`, `module`, `active`, `homepage`) VALUES
(1, 'Home', 'Home', 'Home', 'Homepage', 0, 1, 1, 1),
(3, 'Admin', 'Admin', 'Admin', 'Admin', 0, 2, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cms_site_info`
--

CREATE TABLE IF NOT EXISTS `cms_site_info` (
  `id` int(11) NOT NULL auto_increment,
  `site_name` varchar(255) NOT NULL default '',
  `site_tagline` varchar(255) NOT NULL default '',
  `admin_name` varchar(255) NOT NULL default '',
  `admin_email` varchar(255) NOT NULL default '',
  `current_theme` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cms_site_info`
--

INSERT INTO `cms_site_info` (`id`, `site_name`, `site_tagline`, `admin_name`, `admin_email`, `current_theme`) VALUES
(1, 'Kiroku', '', 'root', 'root@mydomain.com', 'layout');

-- --------------------------------------------------------

--
-- Table structure for table `cms_user_accounts`
--

CREATE TABLE IF NOT EXISTS `cms_user_accounts` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(50) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  `user_group` int(11) NOT NULL default '0',
  `first_name` varchar(50) NOT NULL default '',
  `last_name` varchar(50) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `active` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `cms_user_accounts`
--

INSERT INTO `cms_user_accounts` (`id`, `username`, `password`, `user_group`, `first_name`, `last_name`, `email`, `active`) VALUES
(1, 'root', 'bef3452591febf751a9333de927f2d9c', 1, 'Root', 'User', 'root@mydomain.com', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cms_user_groups`
--

CREATE TABLE IF NOT EXISTS `cms_user_groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `comments` varchar(255) NOT NULL default '',
  `active` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `cms_user_groups`
--

INSERT INTO `cms_user_groups` (`id`, `name`, `comments`, `active`) VALUES
(1, 'Super Users', 'This group contains root access', 1),
(2, 'Administrators', 'Site Administrators', 1),
(3, 'Users', 'Regular site users', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Cron_Jobs`
--

CREATE TABLE IF NOT EXISTS `Cron_Jobs` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `shortName` varchar(255) NOT NULL,
  `scheduleStart` varchar(50) NOT NULL,
  `scheduleStop` varchar(50) NOT NULL,
  `interval` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `Cron_Jobs`
--

