
/**
create database VotingSystem;

create user jPerson@'localhost' identified by 'jacobsRulz';
grant all privileges on VotingSystem.* to 'jPerson'@'localhost' IDENTIFIED BY 'jacobsRulz';
**/

DROP TABLE IF EXISTS `poll`;
CREATE TABLE `poll`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `type` VARCHAR(64) NOT NULL,
  `num_options` INT NOT NUlL,
  `num_values` INT NOT NULL,
  `title` VARCHAR(256) NOT NULL,
  `subtitle` VARCHAR(1024) NOT NULL,
  `timestamp` INT NOT NULL,
  `opening_time` INT NOT NULL,
  `closing_time` INT NOT NULL
) ENGINE = MYISAM ;

DROP TABLE IF EXISTS `options`;
CREATE TABLE `options`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `poll_id` INT NOT NULL,
  `option_name` VARCHAR(128) NOT NULL,
  `value` INT NOT NULL DEFAULT '0'
) ENGINE = MYISAM ;

DROP TABLE IF EXISTS `votes`;
CREATE TABLE `votes`(
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `poll_id` INT NOT NULL
) ENGINE = MYISAM ;

