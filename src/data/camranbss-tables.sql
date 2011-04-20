--
-- Table structure for table `nbss_beer`
--

DROP TABLE IF EXISTS `nbss_beer`;
CREATE TABLE `nbss_beer` (
  `id` int(11) NOT NULL ,
  `brewery_id` int(11) NOT NULL default '0',
  `name` varchar(60) NOT NULL,
  `notes` text,
  `abv` float default NULL,
  `og` int(4) default NULL,
  PRIMARY KEY  (`id`)
);
ALTER TABLE `nbss_beer` ADD INDEX `brewery_id`;

-- --------------------------------------------------------

--
-- Table structure for table `nbss_brewery`
--

DROP TABLE IF EXISTS `nbss_brewery`;
CREATE TABLE `nbss_brewery` (
  `id` int(11) NOT NULL ,
  `name` varchar(60) NOT NULL,
  `location` varchar(100) default NULL,
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
);
ALTER TABLE `nbss_brewery` ADD INDEX `active`,

-- --------------------------------------------------------

--
-- Table structure for table `nbss_pub`
--

DROP TABLE IF EXISTS `nbss_pub`;
CREATE TABLE `nbss_pub` (
  `id` int(11) NOT NULL ,
  `town_id` int(11) NOT NULL default '0',
  `brewery_id` int(11) default NULL,
  `name` varchar(60) NOT NULL,
  `notes` text NOT NULL,
  `active` tinyint(4) NOT NULL default '1',
  PRIMARY KEY  (`id`)
);
ALTER TABLE `nbss_pub` ADD INDEX `town_id`;
ALTER TABLE `nbss_pub` ADD INDEX `brewery_id`;
ALTER TABLE `nbss_pub` ADD INDEX `active`;

-- --------------------------------------------------------

--
-- Table structure for table `nbss_rating`
--

DROP TABLE IF EXISTS `nbss_rating`;
CREATE TABLE `nbss_rating` (
  `id` int(11) NOT NULL ,
  `name` varchar(60) NOT NULL,
  `score` int(1) NOT NULL default '0',
  `desc` text NOT NULL,
  PRIMARY KEY  (`id`)
);
ALTER TABLE `nbss_rating` ADD INDEX `score`;

INSERT INTO `nbss_rating` VALUES (1, '0 Undrinkable', 0, 'No cask ale available or so poor you have to take it back or can''t finish it.');
INSERT INTO `nbss_rating` VALUES (2, '1 Poor', 1, 'Beer that is anything from barely drinkable to drinkable with considerable resentment.');
INSERT INTO `nbss_rating` VALUES (3, '2 Average', 2, 'Competently kept, drinkable pint but doesn''t inspire in any way, not worth moving to another pub but you drink the beer without really noticing.');
INSERT INTO `nbss_rating` VALUES (4, '3 Good', 3, 'Good beer in good form. You cancel plans to move to the next pub. You want to stay for another pint and  seek out the beer again.');
INSERT INTO `nbss_rating` VALUES (5, '4 Very Good', 4, 'Excellent beer in excellent condition.');
INSERT INTO `nbss_rating` VALUES (6, '5 Perfect', 5, 'Probably the best you are ever likely to find. A seasoned drinker will award this score very rarely.');

-- --------------------------------------------------------

--
-- Table structure for table `nbss_review`
--

DROP TABLE IF EXISTS `nbss_review`;
CREATE TABLE `nbss_review` (
  `id` int(11) NOT NULL ,
  `reviewed` date NOT NULL default '0000-00-00',
  `reviewer_id` int(11) default NULL,
  `pub_id` int(11) NOT NULL default '0',
  `beer_id` int(11) default NULL,
  `beer` varchar(60) default NULL,
  `nora` tinyint(1) NOT NULL default '0',
  `rating_id` int(11) default NULL,
  `notes` text,
  `created` bigint(14) NOT NULL,
  `updated` bigint(14) NOT NULL,
  `archived` date default NULL,
  PRIMARY (`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `nbss_town`
--

DROP TABLE IF EXISTS `nbss_town`;
CREATE TABLE `nbss_town` (
  `id` int(11) NOT NULL ,
  `name` varchar(60) NOT NULL,
  `active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`)
);
ALTER TABLE `nbss_town` ADD INDEX `active`;
-- --------------------------------------------------------

--
-- Table structure for table `nbss_user`
--

DROP TABLE IF EXISTS `nbss_user`;
CREATE TABLE `nbss_user` (
  `id` int(10) NOT NULL ,
  `type` enum('reviewer','superreviewer','admin','sysadmin') NOT NULL default 'reviewer',
  `camra_number` char(32) NOT NULL,
  `camra_associate` char(32) default NULL,
  `camra_member` tinyint(1) NOT NULL default '1',
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `postcode` char(32) NOT NULL,
  `email` varchar(100) default NULL,
  `lastlogin` bigint(14) NOT NULL,
  `verified` tinyint(1) NOT NULL default '1',
  `active` tinyint(1) NOT NULL default '1',
  `created` bigint(14) NOT NULL,
  `updated` bigint(14) NOT NULL,
  PRIMARY KEY  (`id`)
);
ALTER TABLE `nbss_user` ADD INDEX (`camra_number`,`postcode`,`active`,`verified`);