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
