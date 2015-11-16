<?php

$installer = $this;
$installer->startSetup();

$sql = "
DROP TABLE IF EXISTS `{$installer->getTable('wgtntpro/consignmentno')}`;
CREATE TABLE IF NOT EXISTS `{$installer->getTable('wgtntpro/consignmentno')}` (
        `consignmentno` varchar(15) NOT NULL,
        `ok` int(1) default 1,
        `track` text,
        `created_at` datetime NOT NULL,
        `post` text,
        `xml_response` text,
        `binary_document` mediumblob,
        `domestic` int(1) default 1,
        PRIMARY KEY  (`consignmentno`),
        `fk_magazzino_id` int(11),
        `fk_parent_id` int(11)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{$installer->getTable('wgtntpro/magazzini')}`;
CREATE TABLE IF NOT EXISTS `{$installer->getTable('wgtntpro/magazzini')}` (
        `magazzino_id` int(11) NOT NULL auto_increment,

        `sender_acc_id` text,
        `larose_depot` text,
        `name` text,
        `vatno` text,

        `addrline1` text,
        `town` text,
        `province` text,
        `postcode` text,
        `country` text,

        `contactname` text,
        `phone1` text COMMENT 'Prefisso',
        `phone2` text COMMENT 'Numero senza prefisso',
        `fax1` text COMMENT 'Prefisso Fax',
        `fax2` text COMMENT 'Numero senza prefisso Fax',
        `email` text,

        `default` INT(1) default 0,
        PRIMARY KEY  (`magazzino_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";

$installer->run($sql);

$installer->endSetup();