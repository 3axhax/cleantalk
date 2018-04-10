ALTER TABLE `articles` ADD `article_status` ENUM('PUBLIC','DRAFT') NOT NULL DEFAULT 'DRAFT' AFTER `description`;

CREATE TABLE `articles_keywords` ( 
	`keyword_id` SMALLINT NOT NULL AUTO_INCREMENT , 
	`keyword` VARCHAR(128) NOT NULL , 
	`created` DATETIME NOT NULL , 
	PRIMARY KEY (`keyword_id`))
	ENGINE=MyISAM DEFAULT CHARSET=utf8;
	
UPDATE `articles` SET `article_status` = 'PUBLIC' WHERE `articles`.`article_id` <> 0;