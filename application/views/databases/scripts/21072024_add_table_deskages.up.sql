CREATE TABLE `desk_ages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `range` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1

INSERT INTO desk_ages (`range`) VALUES('=< 20');
INSERT INTO desk_ages (`range`) VALUES('21 - 30');
INSERT INTO desk_ages (`range`) VALUES('31 - 40');
INSERT INTO desk_ages (`range`) VALUES('41 - 50');
INSERT INTO desk_ages (`range`) VALUES('51 - 60');
INSERT INTO desk_ages (`range`) VALUES('>= 61');