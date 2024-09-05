--
-- Table structure for table `playsms_gatewayWhaticket_config`
--

DROP TABLE IF EXISTS `playsms_gatewayWhaticket_config`;
CREATE TABLE `playsms_gatewayWhaticket_config` (
  `cfg_api_url` VARCHAR(250) NOT NULL,
  `cfg_token` VARCHAR(250) NOT NULL
);
LOCK TABLES `playsms_gatewayWhaticket_config` WRITE;
INSERT INTO `playsms_gatewayWhaticket_config` VALUES ('http://localhost:8080/api/messages/send', '0');
UNLOCK TABLES;

--
-- Table structure for table `playsms_gatewayWhaticket_log`
--

DROP TABLE IF EXISTS `playsms_gatewayWhaticket_log`;
CREATE TABLE `playsms_gatewayWhaticket_log` (
  `c_timestamp` BIGINT(20) NOT NULL DEFAULT '0',
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `local_smslog_id` INT(11) NOT NULL DEFAULT '0',
  `status` varchar(20) NOT NULL DEFAULT '',
  `remote_smslog_id` VARCHAR(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
