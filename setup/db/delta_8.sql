ALTER TABLE `cms_user_accounts` CHANGE `user_group` `primary_group` INT( 11 ) NOT NULL DEFAULT '1'

CREATE TABLE `cms_user_groups_xref` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`userId` INT NOT NULL ,
`groupId` INT NOT NULL
) ENGINE = MYISAM ;
