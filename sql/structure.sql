-- --------------------------------------------------------

--
-- Table structure for table `gmonkeyaccesses`
--

CREATE TABLE IF NOT EXISTS `gmonkeyaccesses` (
  `problem_id` smallint(5) unsigned NOT NULL,
  `accesses_ro` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `accesses_en` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `accesses_ru` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `accesses_es` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `accesses_kr` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`problem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `tag_id` smallint(5) unsigned NOT NULL,
  `tag_name` varchar(50) CHARACTER SET utf8 COLLATE utf8_romanian_ci NOT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tag_mappings`
--

CREATE TABLE IF NOT EXISTS `tag_mappings` (
  `tag_id` smallint(5) unsigned NOT NULL,
  `problem_id` smallint(5) unsigned NOT NULL,
  UNIQUE KEY `tag_id` (`tag_id`,`problem_id`),
  KEY `problem_id` (`problem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE IF NOT EXISTS `translations` (
  `problem_id` smallint(5) unsigned NOT NULL,
  `title_english` varchar(200) CHARACTER SET utf8 NOT NULL,
  `title_romanian` varchar(200) CHARACTER SET utf8 COLLATE utf8_romanian_ci DEFAULT NULL,
  `text_english` text CHARACTER SET utf8 NOT NULL,
  `text_romanian` text CHARACTER SET utf8 COLLATE utf8_romanian_ci,
  `publish_date` varchar(20) CHARACTER SET ascii NOT NULL,
  `last_main_update` datetime NOT NULL,
  `is_translated` tinyint(1) NOT NULL,
  `hits` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`problem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tag_mappings`
--
ALTER TABLE `tag_mappings`
  ADD CONSTRAINT `tag_mappings_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`tag_id`),
  ADD CONSTRAINT `tag_mappings_ibfk_2` FOREIGN KEY (`problem_id`) REFERENCES `translations` (`problem_id`);
