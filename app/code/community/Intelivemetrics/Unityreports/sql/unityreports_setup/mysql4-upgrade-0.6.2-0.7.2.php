<?php
$installer = $this;

$installer->startSetup();
Mage::log('Running installer '.__FILE__);

$installer->run("
    ALTER TABLE `{$this->getTable('unityreports/product_counters')}`
	ADD COLUMN `last_sent_at` DATETIME NULL AFTER `addtocarts`,
	ADD COLUMN `last_updated_at` DATETIME NULL AFTER `last_sent_at`;

    ALTER TABLE `{$this->getTable('unityreports/product_counters')}`
	ADD INDEX `last_sent` (`last_sent_at`),
	ADD INDEX `updated_at` (`last_updated_at`);    
");


$installer->endSetup();