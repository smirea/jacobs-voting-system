
/**
create database VotingSystem;

create user jPerson@'localhost' identified by 'jacobsRulz';
grant all privileges on VotingSystem.* to 'jPerson'@'localhost' IDENTIFIED BY 'jacobsRulz';
**/

DROP TABLE IF EXISTS `poll`;
CREATE TABLE `poll`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `type` VARCHAR(64) NOT NULL
) ENGINE = MYISAM ;

DROP TABLE IF EXISTS `options`;
CREATE TABLE `options`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `poll_id` INT NOT NULL,
  `option` VARCHAR(128) NOT NULL,
  `value` INT NOT NULL DEFAULT '0'
) ENGINE = MYISAM ;

DROP TABLE IF EXISTS `votes`;
CREATE TABLE `votes`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `poll_id` INT NOT NULL
) ENGINE = MYISAM ;

