<?php


$installer = $this;


$installer->startSetup();

$installer->run("

		CREATE TABLE IF NOT EXISTS {$this->getTable('designer_menu')} (
			`id` int NOT NULL,
			`nome` VARCHAR(1000) NOT NULL,
			`sesso_url` VARCHAR(50) NOT NULL,
			`prodotti` INT NOT NULL,
			`Sesso` INT NOT NULL,
			`store_id` INT NOT NULL,
			`Visibile` INT NOT NULL,
			PRIMARY KEY (`id`,`store_id`,`Sesso`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabella Designer Menu';





	");



$installer->endSetup();