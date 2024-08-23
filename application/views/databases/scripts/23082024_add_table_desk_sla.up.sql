CREATE TABLE `desk_sla` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `klasifikasi_id` int(11) DEFAULT NULL,
  `subklasifikasi_id` int(11) DEFAULT NULL,
  `info` char(2) DEFAULT NULL,
  `komoditi_id` int(11) DEFAULT NULL,
  `sla` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;