CREATE TABLE `desk_messagewa_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_communication_provider_id` bigint NOT NULL,
  `target` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sequence_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `message_histories_user_communication_provider_id_index` (`user_communication_provider_id`),
  KEY `message_histories_target_index` (`target`),
  KEY `message_histories_type_index` (`type`),
  KEY `message_histories_created_at_IDX` (`created_at`) USING BTREE,
  KEY `message_histories_sequence_id_IDX` (`sequence_id`,`target`) USING BTREE
) ENGINE=InnoDB;