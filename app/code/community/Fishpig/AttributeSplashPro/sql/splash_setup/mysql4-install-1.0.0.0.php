<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

	$this->startSetup();
	
	$this->run("
		CREATE TABLE IF NOT EXISTS {$this->getTable('splash_page')} (
			`page_id` int(11) unsigned NOT NULL auto_increment,
			`name` varchar(255) NOT NULL default '',
			`short_description` TEXT NOT NULL default '',
			`description` TEXT NOT NULL default '',
			`url_key` varchar(180) NOT NULL default '',
			`option_filters` TEXT NOT NULL default '',
			`price_filters` TEXT NOT NULL default '',
			`page_title` varchar(255) NOT NULL default '',
			`meta_description` varchar(255) NOT NULL default '',
			`meta_keywords` varchar(255) NOT NULL default '',
			`status` int(1) unsigned NOT NULL default 1,
			`created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
			`updated_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
			PRIMARY KEY (`page_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='SplashPro: Page';
		
		CREATE TABLE IF NOT EXISTS {$this->getTable('splash_page_store')} (
			`page_id` int(11) unsigned NOT NULL auto_increment,
			`store_id` smallint(5) unsigned NOT NULL default 0,
			PRIMARY KEY(`page_id`, `store_id`),
			KEY `FK_PAGE_ID_SPLASH_PRO_PAGE_STORE` (`page_id`),
			CONSTRAINT `FK_PAGE_ID_SPLASH_PRO_PAGE_STORE` FOREIGN KEY (`page_id`) REFERENCES `{$this->getTable('splash_page')}` (`page_id`) ON DELETE CASCADE ON UPDATE CASCADE,
			KEY `FK_STORE_ID_SPLASH_PRO_STORE` (`store_id`),
			CONSTRAINT `FK_STORE_ID_SPLASH_PRO_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core_store')}` (`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
		)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='SplashPro: Page Store Links';

	");

	$this->endSetup();
	