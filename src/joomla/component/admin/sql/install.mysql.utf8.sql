DROP TABLE IF EXISTS `#__scoutorg_troops`;
DROP TABLE IF EXISTS `#__scoutorg_branches`;

CREATE TABLE `#__scoutorg_branches` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `#__scoutorg_troops` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `troop` int(11) unsigned NOT NULL,
  `branch` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `#__scoutorg_troops_branch_idx` (`branch`),
  CONSTRAINT `#__scoutorg_troops_branch` FOREIGN KEY (`branch`) REFERENCES `#__scoutorg_branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `#__scoutorg_branches` (`name`)
VALUES ('Spårare'), ('Upptäckare'), ('Äventyrare'), ('Utmanare');