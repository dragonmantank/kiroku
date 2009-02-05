CREATE TABLE `biffcms`.`cms_config` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`key` VARCHAR( 255 ) NOT NULL ,
`value` VARCHAR( 255 ) NOT NULL
) ENGINE = MYISAM;

INSERT INTO `biffcms`.`cms_config` (
`id` ,
`key` ,
`value`
)
VALUES (
NULL , 'theme', 'default'
);