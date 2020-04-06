CREATE TABLE `tbl_student_last_login` (
  `id` bigint(20) NOT NULL,
  `userId` bigint(20) NOT NULL,
  `sessionData` varchar(2048) NOT NULL,
  `machineIp` varchar(1024) NOT NULL,
  `userAgent` varchar(128) NOT NULL,
  `agentString` varchar(1024) NOT NULL,
  `platform` varchar(128) NOT NULL,
  `createdDtm` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `tbl_student` ADD `password` VARCHAR(128) NOT NULL AFTER `email`;
UPDATE `tbl_student` SET `password` = '$2y$10$cCiK6U8d89BeWFoNwocK/u1hUqPgbz8wF1FjG0/Az82tpvL7owjj.' WHERE 1;

ALTER TABLE `tbl_reset_password` ADD `isMailSent` TINYINT(4) NOT NULL DEFAULT '0' AFTER `client_ip`;
ALTER TABLE `tbl_reset_password` ADD `role` ENUM('staff_tutor','student') NOT NULL AFTER `email`;