CREATE TABLE IF NOT EXISTS `desk_perolehan_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `desk_perolehan_info` (`id`, `name`) VALUES
	(1, 'Melihat/membaca/mendengarkan/mencatat'),
	(2, 'Mendapatkan salinan informasi ');
