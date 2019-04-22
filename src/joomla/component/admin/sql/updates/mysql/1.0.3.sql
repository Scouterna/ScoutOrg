CREATE TABLE `#__scoutorg_userprofilefields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  `ordering` int(11) NOT NULL,
  `access` int(11) NOT NULL DEFAULT '0',
  `fieldtype` varchar(45) NOT NULL,
  `fieldcode` text NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
