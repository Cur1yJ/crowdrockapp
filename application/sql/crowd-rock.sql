DROP TABLE IF EXISTS `organizers`;
CREATE TABLE `organizers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255),
  `user_id` integer(8),
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `playlists`;
CREATE TABLE `playlists` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255),
  `organizer_id` int(11),
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `songs`;
CREATE TABLE `songs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `playlist_id` int(11),
  `organizer_id` int(11),
  `title` varchar(255),
  `artist` varchar(255),
  `album` varchar(255),
  `genre` varchar(255),
  `time` float,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `parties`;
CREATE TABLE `parties` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `organizer_id` int(11),
  `title` varchar(255),
  `access_token` varchar(255),
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `party_songs`;
CREATE TABLE `party_songs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `party_id` int(11),
  `song_id` int(11),
  `title` varchar(255),
  `artist` varchar(255),
  `album` varchar(255),
  `genre` varchar(255),
  `time` float,
  `time_str` varchar(10),
  `status` varchar(255),
  `up_vote_count` int(11) DEFAULT 0,
  `down_vote_count` int(11) DEFAULT 0,
  `play_request_count` int(11) DEFAULT 0,
  `rank` float DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `participant_requests`;
CREATE TABLE `participant_requests` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `party_song_id` int(11),
  `party_id` int(11),
  `song_id` int(11),
  `request_type` varchar(255),
  `user_id` int(11),
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);

#
# create default playlist for admin user
#

INSERT INTO `organizers` (`id`, `name`, `user_id`) VALUES (1,'administrator',1);
INSERT INTO `playlists` (`id`, `name`, `organizer_id`) VALUES (1,'administrator',1);

### add column for pay count
#up
ALTER TABLE `party_songs` ADD COLUMN `play_count` integer  DEFAULT 0 AFTER `rank`;
#down
#ALTER TABLE `party_songs` DROP COLUMN `play_count`

DROP TABLE IF EXISTS `points`;
CREATE TABLE `points` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `party_id` int(11),
  `participant_request_id` int(11),
  `user_id` int(11),
  `point` int(11),
  `reason` varchar(255),
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
);
ALTER TABLE `users` ADD COLUMN `total_points` integer  DEFAULT 0 AFTER `phone`;

#
# create anonymous user : up
#

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
	('2',INET_ATON('127.0.0.1'),'anonymous','7bb881fa43f1e1e7e47f069c28bb6bfd919bead6', NULL, 'anonymous@crowdrock.com','',NULL,'1268889823','1268889823','1', 'Anonymous','User','ANONYMOUS','0-0-0');
INSERT INTO `users_groups` (`user_id`, `group_id`) VALUES (2,2);
INSERT INTO `organizers` (`id`, `name`, `user_id`) VALUES (2, 'anonymous',2);
INSERT INTO `playlists` (`id`, `name`, `organizer_id`) VALUES (2, 'anonymous',2);

#
# create anonymous user : down
#
# delete from `users` where `username` = 'anonymous';
# delete from `users_groups` where `user_id` = 2;
# delete from `organizers` where `user_id` = 2;
# delete from `playlists` where `organizer_id` = 2;

--
-- Table structure for table `thirdparty_authentications`
--
DROP TABLE IF EXISTS `thirdparty_authentications`;
CREATE TABLE IF NOT EXISTS `thirdparty_authentications` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` integer(8) NOT NULL,
  `facebook_id` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `twitter_id` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
);

# #################
# up
ALTER TABLE `parties`
ADD `latitude` DOUBLE NULL AFTER `access_token`,
ADD `longitude` DOUBLE NULL AFTER `latitude`;
# down
# ALTER TABLE `parties` drop column `latitude`;
# ALTER TABLE `parties` drop column `longitude`;
# #################

# up
ALTER TABLE `parties` ADD `address` VARCHAR(255) NULL AFTER `longitude`;
# down
# ALTER TABLE `parties` drop column `address`;

# #################
# up
ALTER TABLE `parties`
ADD `status` DOUBLE NULL AFTER `address`;
# down
# ALTER TABLE `parties` drop column `status`;
# #################
