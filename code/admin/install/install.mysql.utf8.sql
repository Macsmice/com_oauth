
CREATE TABLE IF NOT EXISTS `jos_error_errors` (
  `error_error_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `referer` varchar(1000) NOT NULL,
  `trace` mediumtext NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) unsigned NOT NULL,
  PRIMARY KEY (`error_error_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_error_filters` (
  `error_filter_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `message` varchar(150) NOT NULL,
  `url` varchar(182) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` int(11) unsigned NOT NULL,
  `modified_on` datetime NOT NULL,
  `modified_by` int(11) unsigned NOT NULL,
  PRIMARY KEY (`error_filter_id`),
  UNIQUE KEY `message` (`message`,`url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
