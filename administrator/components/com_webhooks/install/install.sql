
CREATE TABLE IF NOT EXISTS `jos_webhooks_webhooks` (
  `uuid` varchar(36) NOT NULL,
  `url` varchar(512) NOT NULL default '',
  `enabled` tinyint(1) NOT NULL default 1,
  `created_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `created_by` bigint(20) NOT NULL default 0,
  `modified_on` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified_by` bigint(20) NOT NULL default 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
