<?php


$installer = $this;


$installer->startSetup();

$installer->run("

		CREATE TABLE IF NOT EXISTS {$this->getTable('categorie_menu')} (
			`id` int NOT NULL,
			`nome` VARCHAR(1000) NOT NULL,
			`url` VARCHAR(1000) NOT NULL,
			`sesso` INT NOT NULL,
			`parent` INT NOT NULL,
			`posizione` INT NOT NULL,
			`store_id` INT NOT NULL,
			PRIMARY KEY (`id`,`store_id`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabella Categorie Menu';





	");



$installer->endSetup();