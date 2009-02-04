--
-- Table structure for table `nbss_beer`
--

DROP TABLE IF EXISTS `nbss_beer`;
CREATE TABLE `nbss_beer` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `brewery_id` int(11) unsigned NOT NULL default '0',
  `name` varchar(60) collate utf8_unicode_ci NOT NULL,
  `notes` text collate utf8_unicode_ci,
  `abv` float unsigned default NULL,
  `og` int(4) unsigned default NULL,
  PRIMARY KEY  (`id`),
  KEY `brewery_id` (`brewery_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nbss_brewery`
--

DROP TABLE IF EXISTS `nbss_brewery`;
CREATE TABLE `nbss_brewery` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(60) collate utf8_unicode_ci NOT NULL,
  `location` varchar(100) collate utf8_unicode_ci default NULL,
  `active` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `active` (`active`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nbss_pub`
--

DROP TABLE IF EXISTS `nbss_pub`;
CREATE TABLE `nbss_pub` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `town_id` int(11) unsigned NOT NULL default '0',
  `brewery_id` int(11) unsigned default NULL,
  `name` varchar(60) collate utf8_unicode_ci NOT NULL,
  `notes` text collate utf8_unicode_ci NOT NULL,
  `active` tinyint(4) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `town_id` (`town_id`),
  KEY `brewery_id` (`brewery_id`),
  KEY `active` (`active`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nbss_rating`
--

DROP TABLE IF EXISTS `nbss_rating`;
CREATE TABLE `nbss_rating` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(60) collate utf8_unicode_ci NOT NULL,
  `score` int(1) unsigned NOT NULL default '0',
  `desc` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `score` (`score`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


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
  `id` int(11) unsigned NOT NULL auto_increment,
  `reviewed` date NOT NULL default '0000-00-00',
  `reviewer_id` int(11) unsigned default NULL,
  `pub_id` int(11) unsigned NOT NULL default '0',
  `beer_id` int(11) unsigned default NULL,
  `beer` varchar(60) collate utf8_unicode_ci default NULL,
  `nora` tinyint(1) unsigned NOT NULL default '0',
  `rating_id` int(11) unsigned default NULL,
  `notes` text collate utf8_unicode_ci,
  `created` bigint(14) unsigned NOT NULL,
  `updated` bigint(14) unsigned NOT NULL,
  `archived` date default NULL,
  PRIMARY KEY  (`id`),
  KEY `pub_id` (`pub_id`),
  KEY `beer_id` (`beer_id`),
  KEY `rating_id` (`rating_id`),
  KEY `reviewer_id` (`reviewer_id`),
  KEY `archived` (`archived`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nbss_town`
--

DROP TABLE IF EXISTS `nbss_town`;
CREATE TABLE `nbss_town` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(60) collate utf8_unicode_ci NOT NULL,
  `active` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `active` (`active`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nbss_user`
--

DROP TABLE IF EXISTS `nbss_user`;
CREATE TABLE `nbss_user` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` enum('reviewer','superreviewer','admin','sysadmin') collate utf8_unicode_ci NOT NULL default 'reviewer',
  `camra_number` char(32) collate utf8_unicode_ci NOT NULL,
  `camra_associate` char(32) collate utf8_unicode_ci default NULL,
  `camra_member` tinyint(1) unsigned NOT NULL default '1',
  `firstname` varchar(100) collate utf8_unicode_ci NOT NULL,
  `lastname` varchar(100) collate utf8_unicode_ci NOT NULL,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL,
  `postcode` char(32) collate utf8_unicode_ci NOT NULL,
  `email` varchar(100) collate utf8_unicode_ci default NULL,
  `lastlogin` bigint(14) unsigned NOT NULL,
  `verified` tinyint(1) unsigned NOT NULL default '1',
  `active` tinyint(1) unsigned NOT NULL default '1',
  `created` bigint(14) unsigned NOT NULL,
  `updated` bigint(14) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `login` (`camra_number`,`postcode`,`active`,`verified`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
