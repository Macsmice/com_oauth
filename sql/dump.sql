CREATE TABLE IF NOT EXISTS `jos_oauth_sites` (
  `site_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(1000) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `consumer_key` varchar(255) NOT NULL,
  `consumer_secret` varchar(255) NOT NULL,
  `created_by` int(11) unsigned NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_by` int(11) unsigned NOT NULL,
  `modified_on` datetime NOT NULL,
  `enabled` binary(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`site_id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `jos_oauth_tokens` (
  `token_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `oauth_token` varchar(200) NOT NULL,
  `oauth_token_secret` varchar(100) NOT NULL,
  `userid` int(11) NOT NULL,
  `service` varchar(50) NOT NULL,
  `service_username` varchar(50) NOT NULL,
  `service_id` varchar(50) NOT NULL,
  `service_avatar` varchar(350) NOT NULL,
  `created_by` int(11) unsigned NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_by` int(11) unsigned NOT NULL,
  `modified_on` datetime NOT NULL,
  PRIMARY KEY (`token_id`),
  UNIQUE KEY `userid` (`userid`,`service`,`service_username`,`service_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `jos_oauth_sites` (`site_id`, `title`, `slug`, `consumer_key`, `consumer_secret`, `created_by`, `created_on`, `modified_by`, `modified_on`, `enabled`) VALUES
(1, 'Twitter', 'twitter', 'YOUR_CONSUMER_KEY', 'YOUR_CONSUMER_SECRET', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', '1'),
(2, 'Facebook', 'facebook', 'YOUR_CONSUMER_KEY', 'YOUR_CONSUMER_SECRET', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', '0'),
(3, 'Google Contacts', 'googlecontact', 'anonymous', 'anonymous', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', '1'),
(4, 'LinkedIn', 'linkedin', 'YOUR_CONSUMER_KEY', 'YOUR_CONSUMER_SECRET', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', '1'),
(5, 'Yahoo', 'yahoo', 'YOUR_CONSUMER_KEY', 'YOUR_CONSUMER_SECRET', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', '0'),
(6, 'Subscrin', 'subscrin', 'YOUR_CONSUMER_KEY', 'YOUR_CONSUMER_SECRET', 0, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 1);
