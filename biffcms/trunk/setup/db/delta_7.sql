ALTER TABLE `cms_sections` ADD PRIMARY KEY(`id`);
ALTER TABLE `cms_sections` CHANGE `id` `id` INT( 11 ) NOT NULL AUTO_INCREMENT ;
ALTER TABLE `cms_sections` ADD `description` VARCHAR( 255 ) NOT NULL ;

INSERT INTO 
	`cms_sections` (
		`id` ,
		`name` ,
		`status`,
		`description`
	)
VALUES 
	(NULL , 'auth', '1', 'Default Kiroku DB-backed authentication mechanism'),
	(NULL, 'admin', '1', 'Kiroku Administration System (Mandatory)'),
	(NULL, 'default', '1', 'Kiroku Page System')
;
