CREATE TABLE `members` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `faculty_number` varchar(6) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `faculty_number` (`faculty_number`);

ALTER TABLE `members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

CREATE TABLE `presentationslots` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `start_time` timestamp NOT NULL,
  `end_time` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `presentationslots`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `start_time` (`start_time`),
  ADD UNIQUE KEY `end_time` (`end_time`);

CREATE TABLE `presentationslots_members` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `member_id` int(10) UNSIGNED NOT NULL,
  `presentationslot_id` int(10) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `presentationslots_members`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `members` ADD `is_admin` BOOLEAN NOT NULL DEFAULT FALSE AFTER `faculty_number`;

ALTER TABLE `presentationslots` ADD `is_locked` BOOLEAN NOT NULL DEFAULT FALSE AFTER `end_time`;

CREATE TABLE `fmi`.`notes` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `score` INT NOT NULL ,
  `description` TEXT NOT NULL , 
  `start_time` VARCHAR(20) NOT NULL ,
  `end_time` VARCHAR(20) NOT NULL ,
  `duration` VARCHAR(20) NOT NULL ,
  PRIMARY KEY  (`id`)
) ENGINE = InnoDB;

ALTER TABLE `notes` ADD `presentationslots_members` INT NOT NULL AFTER `duration`;

​ALTER TABLE `notes` ADD `created` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL AFTER `presentationslots_members`;
