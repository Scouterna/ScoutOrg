ALTER TABLE `#__scoutorg_branches` 
RENAME TO  `#__scoutorg_scoutnet_branches` ;
ALTER TABLE `#__scoutorg_troops` 
RENAME TO  `#__scoutorg_scoutnet_troops` ;

CREATE TABLE `#__scoutorg_composite_branches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8mb4_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `#__scoutorg_composite_troops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `branch` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `#__scoutorg_composite_troops_branch_idx` (`branch`),
  CONSTRAINT `#__scoutorg_composite_troops_branch` FOREIGN KEY (`branch`) REFERENCES `#__scoutorg_composite_branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `#__scoutorg_composite_troops_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `troop` int(11) NOT NULL,
  `member` int(11) NOT NULL,
  `role` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `#__scoutorg_composite_troops_members_troop_idx` (`troop`),
  CONSTRAINT `#__scoutorg_composite_troops_members_troop` FOREIGN KEY (`troop`) REFERENCES `#__scoutorg_composite_troops` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `#__scoutorg_composite_patrols` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `troop` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `#__scoutorg_composite_patrols_troop_idx` (`troop`),
  CONSTRAINT `#__scoutorg_composite_patrols_troop` FOREIGN KEY (`troop`) REFERENCES `#__scoutorg_composite_troops` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `#__scoutorg_composite_patrols_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patrol` int(11) NOT NULL,
  `member` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `#__scoutorg_composite_patrols_members_patrol_idx` (`patrol`),
  CONSTRAINT `#__scoutorg_composite_patrols_members_patrol` FOREIGN KEY (`patrol`) REFERENCES `#__scoutorg_composite_patrols` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `#__scoutorg_composite_rolegroups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `#__scoutorg_composite_rolegroups_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rolegroup` int(11) NOT NULL,
  `member` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `#__scoutorg_composite_rolegroups_members_rolegroup_idx` (`rolegroup`),
  CONSTRAINT `#__scoutorg_composite_rolegroups_members_rolegroup` FOREIGN KEY (`rolegroup`) REFERENCES `#__scoutorg_composite_rolegroups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;