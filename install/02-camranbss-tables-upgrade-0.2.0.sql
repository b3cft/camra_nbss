--
-- Table structure for table `nbss_beer`
--

ALTER TABLE `nbss_beer` CHANGE `id` `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE `brewery_id` `brewery_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `name` `name` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE `notes` `notes` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
CHANGE `abv` `abv` FLOAT UNSIGNED NULL DEFAULT NULL ,
CHANGE `og` `og` INT( 4 ) UNSIGNED NULL DEFAULT NULL;

--
-- Table structure for table `nbss_brewery`
--

ALTER TABLE `nbss_brewery` CHANGE `id` `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE `name` `name` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE `location` `location` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
CHANGE `active` `active` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1' ,
ADD INDEX `active` ( `active` );

--
-- Table structure for table `nbss_pub`
--

ALTER TABLE `nbss_pub` CHANGE `id` `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE `town_id` `town_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `brewery_id` `brewery_id` INT( 11 ) UNSIGNED NULL DEFAULT NULL ,
CHANGE `name` `name` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE `notes` `notes` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE `active` `active` TINYINT( 4 ) UNSIGNED NOT NULL DEFAULT '1' ,
ADD INDEX `active` ( `active` );

--
-- Table structure for table `nbss_rating`
--

ALTER TABLE `nbss_rating` CHANGE `id` `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE `name` `name` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE `score` `score` INT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `desc` `desc` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
ADD INDEX `score` ( `score` );

--
-- Table structure for table `nbss_review`
--

ALTER TABLE `nbss_review` CHANGE `id` `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE `reviewed` `reviewed` DATE NOT NULL DEFAULT '0000-00-00',
CHANGE `reviewer_id` `reviewer_id` INT( 11 ) UNSIGNED NULL DEFAULT NULL ,
CHANGE `pub_id` `pub_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `beer_id` `beer_id` INT( 11 ) UNSIGNED NULL DEFAULT NULL ,
CHANGE `beer` `beer` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
CHANGE `nora` `nora` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
CHANGE `rating_id` `rating_id` INT( 11 ) UNSIGNED NULL DEFAULT NULL ,
CHANGE `notes` `notes` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
CHANGE `created` `created` BIGINT( 14 ) UNSIGNED NOT NULL ,
CHANGE `updated` `updated` BIGINT( 14 ) UNSIGNED NOT NULL ,
CHANGE `archived` `archived` DATE NULL DEFAULT NULL,
DROP `camra`,
DROP `reviewer`,
DROP `reviewer_email` ,
ADD INDEX `archived` ( `archived` );

--
-- Table structure for table `nbss_town`
--

ALTER TABLE `nbss_town` CHANGE `id` `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
CHANGE `name` `name` VARCHAR( 60 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE `active` `active` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1' ,
ADD INDEX `active` ( `active` );

--
-- Table structure for table `nbss_user`
--

ALTER TABLE `nbss_user` CHANGE `id` `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
CHANGE `type` `type` ENUM('reviewer','superreviewer','admin','sysadmin') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'reviewer',
CHANGE `camra_number` `camra_number` CHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
CHANGE `camra_associate` `camra_associate` CHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
ADD `camra_member` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' AFTER `camra_associate`,
CHANGE `firstname` `firstname` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
CHANGE `lastname` `lastname` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
CHANGE `postcode` `postcode` CHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
CHANGE `email` `email` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
CHANGE `lastlogin` `lastlogin` BIGINT(14) UNSIGNED NOT NULL,
ADD `verified` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' AFTER `lastlogin`,
CHANGE `active` `active` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
CHANGE `created` `created` BIGINT(14) UNSIGNED NOT NULL,
CHANGE `updated` `updated` BIGINT(14) UNSIGNED NOT NULL ,
ADD INDEX `login` ( `camra_number` , `postcode` , `active` , `verified` );