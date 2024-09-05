CREATE TABLE `desk_alasan_keberatan` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `kode` char(2) DEFAULT NULL,
  `nama` varchar(500) DEFAULT NULL,
  `deleted` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `desk_alasan_keberatan` (`id`, `kode`, `nama`, `deleted`)
VALUES
	(1,'a','a. Permohonan Informasi Ditolak',0),
	(2,'b','b. Informasi berkala tidak disediakan',0),
	(3,'c','c. Permintaan informasi tidak ditanggapi',0),
	(4,'d','d. Permintaan informasi ditanggapi tidak sebagaimana yang diminta',0),
	(5,'e','e. Permintaan informasi tidak dipenuhi',0),
	(6,'f','f. Biaya yang dikenakan tidak wajar',0),
	(7,'g','g. Informasi disampaikan melebihi jangka waktu yang ditentukan',0);
