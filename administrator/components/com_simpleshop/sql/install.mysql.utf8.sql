DROP TABLE IF EXISTS `#__simpleshop`;
DROP TABLE IF EXISTS `#__simpleshop_usercart`;
DROP TABLE IF EXISTS `#__simpleshop_orders`;

CREATE TABLE `#__simpleshop` (
	`id`       INT(11)     NOT NULL AUTO_INCREMENT,
	`catid`	    int(11)    NOT NULL DEFAULT '0',
  `alias` VARCHAR(255) NOT NULL,
	`produkt_titel` VARCHAR(255) NOT NULL,
	`produkt_beschreibung` TEXT,
	`produkt_preis` VARCHAR(255) NOT NULL,
	`produkt_bild` VARCHAR(255) NOT NULL,
  `produkt_steuer` VARCHAR(255) NOT NULL,
	`produkt_art` VARCHAR(255) NOT NULL,
  `produkt_eigenschaften` MEDIUMTEXT,
  `produkt_eigenschaften_preis` VARCHAR(255) NOT NULL,
  `author` int(25),
	PRIMARY KEY (`id`)
)
	ENGINE =MyISAM
	AUTO_INCREMENT =0
	DEFAULT CHARSET =utf8;
	
CREATE TABLE `#__simpleshop_usercart` (
`id` INT(11)     NOT NULL AUTO_INCREMENT,
`produkt_id` INT(25) NOT NULL,
`user_id` INT(25) NOT NULL,
PRIMARY KEY (`id`)
)
ENGINE =MyISAM
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8;

CREATE TABLE `#__simpleshop_orders` (
`id` INT(11)     NOT NULL AUTO_INCREMENT,
`userid` INT(11)     NOT NULL,
`name` VARCHAR(255) NOT NULL,
`email` VARCHAR(255) NOT NULL,
`orders` MEDIUMTEXT NOT NULL,
`adresse` MEDIUMTEXT NOT NULL,
`created` DATETIME NOT NULL,
PRIMARY KEY (`id`)
)
ENGINE =MyISAM
AUTO_INCREMENT =0
DEFAULT CHARSET =utf8;

