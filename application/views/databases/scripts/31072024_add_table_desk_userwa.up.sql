CREATE TABLE `desk_userwa` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `phone` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `webhook_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `user_code` varchar(100) DEFAULT NULL,
  `created_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `is_auto_gdrive` tinyint DEFAULT '0',
  `gdrive_folder` varchar(100) DEFAULT NULL,
  `file_pattern` varchar(100) DEFAULT NULL,
  `is_saved` tinyint DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0;

INSERT INTO desk_userwa (phone, status, webhook_url, user_code, created_on, is_auto_gdrive, gdrive_folder, file_pattern, is_saved) VALUES('083892110929', '1', NULL, '1', '2024-07-30 08:57:49', 0, NULL, NULL, 1);
