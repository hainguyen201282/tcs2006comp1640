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

ALTER TABLE `tbl_users` ADD `gender` VARCHAR(20) NOT NULL DEFAULT 'Male' AFTER `name`;

CREATE TABLE `document_file_info` 
  ( 
     `fileid`      INT NOT NULL auto_increment, 
     `caption`     TEXT NOT NULL, 
     `size`        BIGINT(13) NOT NULL, 
     `filekey`     TEXT NOT NULL, 
     `filetype`    VARCHAR(128) NOT NULL, 
     `downloadurl` TEXT NOT NULL, 
     `createdDtm`  INT NOT NULL, 
     PRIMARY KEY (`fileid`) 
  ) 
engine = innodb; 

ALTER TABLE `tbl_document_file_info` ADD `vendorId` INT NOT NULL AFTER `downloadUrl`, ADD `vendorRole` INT NOT NULL AFTER `vendorId`;
ALTER TABLE `tbl_document_file_info` ADD `realFilename` TEXT NOT NULL AFTER `caption`;

CREATE TABLE `tbl_document` 
  ( 
     `documentid`  INT NOT NULL auto_increment, 
     `topic`       TEXT NOT NULL, 
     `vendorid`    INT NOT NULL, 
     `vendorrole`  INT NOT NULL, 
     `updateddate` DATETIME NOT NULL, 
     `createddate` DATETIME NOT NULL, 
     PRIMARY KEY (`documentid`) 
  ) 
engine = innodb; 

ALTER TABLE `tbl_document_file_info` ADD `documentId` INT NULL AFTER `fileId`;

CREATE TABLE `tbl_document_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(256) DEFAULT NULL,
  `status` varchar(128) NOT NULL,
  `userId` int(11) NOT NULL,
  `userRole` int(11) NOT NULL,
  `documentId` int(11) NOT NULL,
  `updatedDate` datetime NOT NULL,
  `createdDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;