
--create database VotingSystem;

--create user jPerson@'localhost' identified by 'jacobsRulz';
--grant all privileges on VotingSystem.* to 'jPerson'@'localhost' IDENTIFIED BY 'jacobsRulz';

DROP TABLE IF EXISTS `Elections`;
CREATE TABLE `Elections`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `label` VARCHAR(128) NOT NULL,
  `vote` INT NOT NULL,
  INDEX `user_id_index` (user_id),
  INDEX `label_index` (label),
  INDEX `vote_index` (vote)
) ENGINE = MYISAM ;

CREATE TABLE `poll`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `type` VARCHAR(64) NOT NULL
) ENGINE = MYISAM ;

CREATE TABLE `options`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `poll_id` INT NOT NULL,
  `option` VARCHAR(128) NOT NULL,
  `value` INT NOT NULL DEFAULT '0'
) ENGINE = MYISAM ;

CREATE TABLE `votes`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `poll_id` INT NOT NULL
) ENGINE = MYISAM ;

