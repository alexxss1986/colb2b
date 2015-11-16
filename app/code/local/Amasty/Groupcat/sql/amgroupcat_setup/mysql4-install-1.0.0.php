<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */
$this->startSetup();

$this->run("
DROP TABLE IF EXISTS {$this->getTable('amgroupcat/rules')};
CREATE TABLE `{$this->getTable('amgroupcat/rules')}` (
  `rule_id`          mediumint(8) unsigned NOT NULL auto_increment,
  `rule_name`        varchar(255) NOT NULL default '',
  `enable`           tinyint(1) unsigned default '0',

  `cats_count`       mediumint(5) unsigned default '0',
  `prods_count`      mediumint(5) unsigned default '0',

  `categories`       varchar(255) NOT NULL default '',
  `cust_groups`      varchar(255) NOT NULL default '',
  `stores`           varchar(255) NOT NULL default '',

  `forbidden_action` tinyint(1) unsigned default '0',
  `cms_page`         tinyint(1) unsigned default '0',
                                                       
  `allow_direct_links`     tinyint(1) unsigned default '0',
                                                       
  `remove_product_links`   tinyint(1) unsigned default '0',
  `remove_category_links`  tinyint(1) unsigned default '0',
  
  `hide_price`  tinyint(1) unsigned default '0',
  `price_on_product_view`  tinyint(1) unsigned default '0',
  `price_on_product_list`  tinyint(1) unsigned default '0',
  
  
  PRIMARY KEY  (`rule_id`)  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS {$this->getTable('amgroupcat/product')};
CREATE TABLE `{$this->getTable('amgroupcat/product')}` (
  `id`             int(10) unsigned NOT NULL auto_increment,
  `rule_id`        mediumint(8) unsigned NOT NULL,
  `product_id`     int(10) unsigned NOT NULL,

  PRIMARY KEY  (`id`),

  KEY `rule_id`    (`rule_id`),
  KEY `product_id` (`product_id`),
  KEY `combined`   (`rule_id`, `product_id`),

  CONSTRAINT FOREIGN KEY (`rule_id`) REFERENCES {$this->getTable('amgroupcat/rules')} (`rule_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


");

$this->endSetup();
