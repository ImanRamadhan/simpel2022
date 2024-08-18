ALTER TABLE `desk_tickets` CHANGE `info` `info` ENUM('P','I','IK','S')  CHARACTER SET latin1  COLLATE latin1_swedish_ci  NOT NULL  DEFAULT 'P';
ALTER TABLE `desk_drafts` CHANGE `info` `info` ENUM('P','I','IK','S')  CHARACTER SET latin1  COLLATE latin1_swedish_ci  NOT NULL  DEFAULT 'P';
