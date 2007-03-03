CREATE TABLE `{DB_PREFIX}module_text` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`page_id` INT NOT NULL ,
`text` TEXT NOT NULL
) TYPE = MYISAM ;

INSERT INTO `{DB_PREFIX}modules` ( `id` , `name` , `description` , `active` )
VALUES (
NULL , 'text', 'Allows basic text input for a page.', '1'
);
