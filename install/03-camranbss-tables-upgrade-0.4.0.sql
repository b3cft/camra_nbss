ALTER TABLE `nbss_beer` 
ADD FULLTEXT INDEX `name` (`name`);

ALTER TABLE `nbss_brewery` 
ADD FULLTEXT INDEX `name` (`name`);

ALTER TABLE `nbss_pub` 
ADD FULLTEXT INDEX `name` (`name`);

ALTER TABLE `nbss_town` 
ADD FULLTEXT INDEX `name` (`name`);
