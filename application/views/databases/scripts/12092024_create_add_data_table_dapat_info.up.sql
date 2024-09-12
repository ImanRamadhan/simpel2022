CREATE TABLE IF NOT EXISTS `desk_dapat_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

INSERT INTO `desk_dapat_info` (`id`, `name`) VALUES
	(1, 'Mengambil langsung'),
	(2, 'Kurir'),
	(3, 'Pos'),
	(4, 'Email'),
	(5, 'Faksimili');
