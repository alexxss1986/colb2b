<?php
$installer = $this;

$installer->startSetup();
Mage::log('Running installer '.__FILE__);

$installer->run("
SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";
SET AUTOCOMMIT = 0;
START TRANSACTION;


DROP TABLE IF EXISTS `{$this->getTable('unityreports/abcarts')}`;
CREATE TABLE IF NOT EXISTS `{$this->getTable('unityreports/abcarts')}` (
  `entity_id` int(11) NOT NULL,
  `sents` int(11) NOT NULL DEFAULT '0',
  `synced` tinyint(1) NOT NULL DEFAULT '0',
  `last_sent_at` datetime NOT NULL,
  `synced_at` datetime NOT NULL,
  PRIMARY KEY (`entity_id`),
  KEY `sents` (`sents`),
  KEY `synced` (`synced`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    
DROP TABLE IF EXISTS `{$this->getTable('unityreports/campaigns')}`;
CREATE TABLE IF NOT EXISTS `{$this->getTable('unityreports/campaigns')}` (
	`id` INT(11) NOT NULL,
	`type` ENUM('order','customer') NOT NULL DEFAULT 'order',
	`source` VARCHAR(100) NULL DEFAULT '',
	`medium` VARCHAR(100) NULL DEFAULT '',
	`content` VARCHAR(100) NULL DEFAULT '',
	`campaign` VARCHAR(100) NULL DEFAULT '',
	PRIMARY KEY (`type`, `id`)
)
COLLATE='utf8_general_ci'
ENGINE=InnoDB;


DROP TABLE IF EXISTS `{$this->getTable('unityreports/creditnotes')}`;
CREATE TABLE IF NOT EXISTS `{$this->getTable('unityreports/creditnotes')}` (
  `increment_id` varchar(50) NOT NULL,
  `sents` int(11) NOT NULL DEFAULT '0',
  `synced` tinyint(1) NOT NULL DEFAULT '0',
  `last_sent_at` datetime NOT NULL,
  `synced_at` datetime NOT NULL,
  PRIMARY KEY (`increment_id`),
  KEY `sents` (`sents`),
  KEY `synced` (`synced`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `{$this->getTable('unityreports/customers')}`;
CREATE TABLE IF NOT EXISTS `{$this->getTable('unityreports/customers')}` (
  `customer_id` int(11) NOT NULL,
  `sents` int(11) NOT NULL DEFAULT '0',
  `synced` tinyint(1) NOT NULL DEFAULT '0',
  `last_sent_at` datetime NOT NULL,
  `synced_at` datetime NOT NULL,
  PRIMARY KEY (`customer_id`),
  KEY `sents` (`sents`),
  KEY `synced` (`synced`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$this->getTable('unityreports/customer_actions')}`;
CREATE TABLE `{$this->getTable('unityreports/customer_actions')}` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`customer_id` INT(11) NOT NULL,
	`action_code` VARCHAR(50) NOT NULL,
	`action_desc` VARCHAR(50) NOT NULL,
	`action_date` DATE NOT NULL,
	`action_time` TIME NULL DEFAULT NULL,
	`sents` TINYINT(4) NULL DEFAULT '0',
	`synced` TINYINT(4) NULL DEFAULT '0',
	`last_sent_at` DATETIME NOT NULL,
	`synced_at` DATETIME NOT NULL,
	PRIMARY KEY (`customer_id`, `action_code`, `action_desc`, `action_date`),
	INDEX `synced` (`synced`),
	INDEX `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$this->getTable('unityreports/invoices')}`;
CREATE TABLE IF NOT EXISTS `{$this->getTable('unityreports/invoices')}` (
  `increment_id` varchar(50) NOT NULL,
  `sents` int(11) NOT NULL DEFAULT '0',
  `synced` tinyint(1) NOT NULL DEFAULT '0',
  `last_sent_at` datetime NOT NULL,
  `synced_at` datetime NOT NULL,
  PRIMARY KEY (`increment_id`),
  KEY `sents` (`sents`),
  KEY `synced` (`synced`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `{$this->getTable('unityreports/orders')}`;
CREATE TABLE IF NOT EXISTS `{$this->getTable('unityreports/orders')}` (
  `increment_id` varchar(50) NOT NULL,
  `sents` int(11) NOT NULL DEFAULT '0',
  `synced` tinyint(1) NOT NULL DEFAULT '0',
  `last_sent_at` datetime NOT NULL,
  `synced_at` datetime NOT NULL,
  PRIMARY KEY (`increment_id`),
  KEY `sents` (`sents`),
  KEY `synced` (`synced`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `{$this->getTable('unityreports/products')}`;
CREATE TABLE IF NOT EXISTS `{$this->getTable('unityreports/products')}` (
  `product_id` int(11) NOT NULL,
  `sents` int(11) NOT NULL DEFAULT '0',
  `synced` tinyint(1) NOT NULL DEFAULT '0',
  `last_sent_at` datetime NOT NULL,
  `synced_at` datetime NOT NULL,
  PRIMARY KEY (`product_id`),
  KEY `sents` (`sents`),
  KEY `synced` (`synced`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `{$this->getTable('unityreports/product_counters')}`;
CREATE TABLE IF NOT EXISTS `{$this->getTable('unityreports/product_counters')}` (
  `product_id` int(11) NOT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `unique_views` int(11) DEFAULT '0',
  `addtocarts` int(11) NOT NULL DEFAULT '0',
  `last_sent_at` DATETIME NULL,
  `last_updated_at` DATETIME NULL,
  PRIMARY KEY (`product_id`),
  INDEX `last_sent` (`last_sent_at`),
  INDEX `updated_at` (`last_updated_at`)
) ENGINE=InnoDb DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `{$this->getTable('unityreports/settings')}`;
CREATE TABLE IF NOT EXISTS `{$this->getTable('unityreports/settings')}` (
  `key` varchar(100) NOT NULL,
  `val` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `{$this->getTable('unityreports/settings')}` (`key`, `val`) VALUES
('max_items_per_sync', '10');

COMMIT;
");


$installer->endSetup();

