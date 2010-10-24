CREATE TABLE IF NOT EXISTS `feed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `itemsNumber` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `feed`
--

INSERT INTO `feed` (`id`, `name`, `url`, `itemsNumber`) VALUES
(1, 'Anis berejeb', 'http://feeds.feedburner.com/AnisBerejeb', 5),
(2, 'Houssem Bensalem', 'http://www.hbensalem.com/feed', 4);
