-- Replace {BA_DB_PREFIX} if using SQL outside of the Template system

-- 
-- Table structure for table `user_accounts`
-- 

CREATE TABLE `{BA_DB_PREFIX}user_accounts` (
  `id` int(11) NOT NULL auto_increment,
  `username` varchar(50) NOT NULL default '',
  `password` varchar(255) NOT NULL default '',
  `user_group` int(11) NOT NULL default '0',
  `first_name` varchar(50) NOT NULL default '',
  `last_name` varchar(50) NOT NULL default '',
  `email` varchar(100) NOT NULL default '',
  `active` int(11) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `user_accounts`
-- 

INSERT INTO `{BA_DB_PREFIX}user_accounts` VALUES (1, 'root', 'bef3452591febf751a9333de927f2d9c', 1, 'Root', 'User', 'email@isp.com', 1);

-- --------------------------------------------------------

-- 
-- Table structure for table `user_groups`
-- 

CREATE TABLE `{BA_DB_PREFIX}user_groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `comments` varchar(255) NOT NULL default '',
  `active` int(11) NOT NULL default '0',
  KEY `id` (`id`)
) TYPE=MyISAM AUTO_INCREMENT=4 ;

-- 
-- Dumping data for table `user_groups`
-- 

INSERT INTO `{BA_DB_PREFIX}user_groups` VALUES (1, 'Super Users', 'This group contains root access', 1);
INSERT INTO `{BA_DB_PREFIX}user_groups` VALUES (2, 'Administrators', 'Site Administrators', 1);
INSERT INTO `{BA_DB_PREFIX}user_groups` VALUES (3, 'Users', 'Regular site users', 1);
