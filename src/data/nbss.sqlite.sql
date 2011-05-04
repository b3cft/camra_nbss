--
-- Table structure for table `nbss_beer`
--

DROP TABLE IF EXISTS `nbss_beer`;
CREATE  TABLE `nbss_beer` ( 
 `id` INTEGER PRIMARY  KEY AUTOINCREMENT  NOT NULL  UNIQUE,
 `brewery_id` INTEGER NOT NULL DEFAULT '0', 
 `name` VARCHAR NOT NULL,
 `notes` TEXT,
 `abv` FLOAT,
 `og` INTEGER
);
CREATE INDEX `nbss_beer_brewery_id` ON `nbss_beer` (`brewery_id`);

-- --------------------------------------------------------

--
-- Table structure for table `nbss_brewery`
--

DROP TABLE IF EXISTS `nbss_brewery`;
CREATE TABLE `nbss_brewery` (
  `id` INTEGER PRIMARY  KEY AUTOINCREMENT  NOT NULL  UNIQUE,
  `name` VARCHAR NOT NULL,
  `location` VARCHAR DEFAULT NULL,
  `active` INTEGER NOT NULL DEFAULT '1'
);
CREATE INDEX `nbss_brewery_active` on `nbss_brewery` (`active`);

-- --------------------------------------------------------

--
-- Table structure for table `nbss_pub`
--

DROP TABLE IF EXISTS `nbss_pub`;
CREATE TABLE `nbss_pub` (
  `id` INTEGER PRIMARY  KEY AUTOINCREMENT  NOT NULL  UNIQUE,
  `town_id` INTEGER NOT NULL DEFAULT '0',
  `brewery_id` INTEGER DEFAULT NULL,
  `name` VARCHAR NOT NULL,
  `notes` TEXT DEFAULT NULL,
  `active` INTEGER NOT NULL DEFAULT '1'
);
CREATE INDEX `nbss_pub_town_id` on`nbss_pub` (`town_id`);
CREATE INDEX `nbss_pub_brewery_id` on`nbss_pub` (`brewery_id`);
CREATE INDEX `nbss_pub_active` on`nbss_pub` (`active`);

-- --------------------------------------------------------

--
-- Table structure for table `nbss_rating`
--

DROP TABLE IF EXISTS `nbss_rating`;
CREATE TABLE `nbss_rating` (
  `id` INTEGER PRIMARY  KEY AUTOINCREMENT  NOT NULL  UNIQUE,
  `name` VARCHAR NOT NULL,
  `score` INTEGER NOT NULL DEFAULT '0',
  `desc` TEXT NOT NULL
);
CREATE INDEX `nbss_rating_score` ON `nbss_rating` (`score`);

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
  `id` INTEGER PRIMARY  KEY AUTOINCREMENT  NOT NULL  UNIQUE,
  `reviewed` DATE NOT NULL DEFAULT '0000-00-00',
  `reviewer_id` INTEGER NOT NULL,
  `pub_id` INTEGER NOT NULL,
  `beer_id` INTEGER DEFAULT NULL,
  `beer` VARCHAR DEFAULT NULL,
  `nora` INTEGER NOT NULL DEFAULT '0',
  `rating_id` INTEGER DEFAULT NULL,
  `notes` TEXT,
  `created` INTEGER NOT NULL,
  `updated` INTEGER NOT NULL,
  `archived` DATE DEFAULT NULL
);
-- @todo needs indexes
-- --------------------------------------------------------

--
-- Table structure for table `nbss_town`
--

DROP TABLE IF EXISTS `nbss_town`;
CREATE TABLE `nbss_town` (
  `id` INTEGER PRIMARY  KEY AUTOINCREMENT  NOT NULL  UNIQUE,
  `name` VARCHAR NOT NULL,
  `active` INTEGER NOT NULL DEFAULT '1'
);
CREATE INDEX `nbss_town_active` ON `nbss_town` (`active`);
-- --------------------------------------------------------

--
-- Table structure for table `nbss_user`
--

DROP TABLE IF EXISTS `nbss_user`;
CREATE TABLE `nbss_user` (
  `id` INTEGER PRIMARY  KEY AUTOINCREMENT  NOT NULL  UNIQUE,
  `type` VARCHAR NOT NULL DEFAULT 'reviewer',
  `camra_number` VARCHAR NOT NULL,
  `camra_associate` VARCHAR DEFAULT NULL,
  `camra_member` INTEGER NOT NULL DEFAULT '1',
  `firstname` VARCHAR NOT NULL,
  `lastname` VARCHAR NOT NULL,
  `name` VARCHAR NOT NULL,
  `postcode` VARCHAR NOT NULL,
  `email` VARCHAR DEFAULT NULL,
  `lastlogin` INTEGER NOT NULL,
  `verified` INTEGER NOT NULL DEFAULT '1',
  `active` INTEGER NOT NULL DEFAULT '1',
  `created` INTEGER NOT NULL,
  `updated` INTEGER NOT NULL
);
CREATE INDEX `nbss_user_login` on `nbss_user` (`camra_number`,`postcode`,`active`,`verified`);