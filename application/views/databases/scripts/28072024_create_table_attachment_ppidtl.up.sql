CREATE TABLE `desk_attachments_ppidtl` (
  `att_id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `ticket_id` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `saved_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `real_name` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `size` int unsigned NOT NULL DEFAULT '0',
  `mode` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`att_id`),
  KEY `ticket_id` (`ticket_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;