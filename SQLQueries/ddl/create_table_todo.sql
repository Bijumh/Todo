CREATE TABLE IF NOT EXISTS `todo` (
  `todo_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `todo_name` varchar(100) NOT NULL,
  `todo_desc` varchar(1000) NULL,
  `create_ts` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,  
  PRIMARY KEY (`todo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;