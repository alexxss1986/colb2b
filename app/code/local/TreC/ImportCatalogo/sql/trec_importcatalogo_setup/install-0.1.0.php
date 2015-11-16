<?php


$installer = $this;


$installer->startSetup();

$applyToSimple = array(
    Mage_Catalog_Model_Product_Type::TYPE_SIMPLE
);


$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'ca_family',
    array(
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'group'             => 'General',
        'type'              => 'varchar',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'Anno',
        'input'             => 'select',
        'unique'            => false,
        'required'          => true,
        'is_configurable'   => false,
        'searchable'        => true,
        'visible_in_advanced_search' => true,
        'comparable'        => true,
        'filterable'        => true,
        'filterable_in_search' => true,
        'used_for_promo_rules'=> true,
        'visible_on_front'  => true,
        'used_in_product_listing'=> true,
        'used_for_sort_by'=>false,
        'user_defined'      => true,

    )
);

$installer->updateAttribute('catalog_product', 'ca_family', 'backend_model', '');



$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'ca_line',
    array(
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'group'             => 'General',
        'type'              => 'varchar',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'Stagione',
        'input'             => 'select',
        'unique'            => false,
        'required'          => true,
        'is_configurable'   => false,
        'searchable'        => true,
        'visible_in_advanced_search' => true,
        'comparable'        => true,
        'filterable'        => true,
        'filterable_in_search' => true,
        'used_for_promo_rules'=> true,
        'visible_on_front'  => true,
        'used_in_product_listing'=> true,
        'used_for_sort_by'=>false,
        'user_defined'      => true,

    )
);

$installer->updateAttribute('catalog_product', 'ca_line', 'backend_model', '');




$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'ca_brand',
    array(
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'group'             => 'General',
        'type'              => 'varchar',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'Brand',
        'input'             => 'select',
        'unique'            => false,
        'required'          => true,
        'is_configurable'   => false,
        'searchable'        => true,
        'visible_in_advanced_search' => true,
        'comparable'        => true,
        'filterable'        => true,
        'filterable_in_search' => true,
        'used_for_promo_rules'=> true,
        'visible_on_front'  => true,
        'used_in_product_listing'=> true,
        'used_for_sort_by'=>false,
        'user_defined'      => true,

    )
);

$installer->updateAttribute('catalog_product', 'ca_brand', 'backend_model', '');



$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'ca_misura',
    array(
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'group'             => 'General',
        'type'              => 'varchar',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'Misura',
        'input'             => 'select',
        'apply_to'          => implode(',',$applyToSimple),
        'unique'            => false,
        'required'          => true,
        'is_configurable'   => true,
        'searchable'        => true,
        'visible_in_advanced_search' => true,
        'comparable'        => true,
        'filterable'        => true,
        'filterable_in_search' => true,
        'used_for_promo_rules'=> true,
        'visible_on_front'  => true,
        'used_in_product_listing'=> true,
        'used_for_sort_by'=>false,
        'user_defined'      => true,

    )
);

$installer->updateAttribute('catalog_product', 'ca_misura', 'backend_model', '');




$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'ca_scalare',
    array(
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'group'             => 'General',
        'type'              => 'varchar',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'Scalare',
        'input'             => 'text',
        'apply_to'          => implode(',',$applyToSimple),
        'unique'            => false,
        'required'          => true,
        'is_configurable'   => false,
        'searchable'        => false,
        'visible_in_advanced_search' => false,
        'comparable'        => false,
        'filterable'        => false,
        'filterable_in_search' => false,
        'used_for_promo_rules'=> false,
        'visible_on_front'  => false,
        'used_in_product_listing'=> false,
        'used_for_sort_by'=>false,
        'user_defined'      => true,
    )
);

$installer->updateAttribute('catalog_product', 'ca_scalare', 'backend_model', '');

$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'ca_colore',
    array(
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'group'             => 'General',
        'type'              => 'varchar',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'Colore',
        'input'             => 'select',
        'unique'            => false,
        'required'          => true,
        'is_configurable'   => false,
        'searchable'        => true,
        'visible_in_advanced_search' => true,
        'comparable'        => true,
        'filterable'        => true,
        'filterable_in_search' => true,
        'used_for_promo_rules'=> true,
        'visible_on_front'  => true,
        'used_in_product_listing'=> true,
        'used_for_sort_by'=>false,
        'user_defined'      => true,

    )
);

$installer->updateAttribute('catalog_product', 'ca_colore', 'backend_model', '');

$installer->run("

		CREATE TABLE IF NOT EXISTS {$this->getTable('wsca_brand')} (
			`id_magento` int NOT NULL,
			`id_ws` VARCHAR(50) NOT NULL,
			PRIMARY KEY (`id_ws`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabella brand';


        CREATE TABLE IF NOT EXISTS {$this->getTable('wsca_line')} (
			`id_magento` int NOT NULL,
			`id_ws` VARCHAR(50) NOT NULL,
			PRIMARY KEY (`id_ws`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabella stagione';


		CREATE TABLE IF NOT EXISTS {$this->getTable('wsca_family')} (
			`id_magento` int NOT NULL,
			`id_ws` VARCHAR(50) NOT NULL,
			PRIMARY KEY (`id_ws`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabella anno';


		CREATE TABLE IF NOT EXISTS {$this->getTable('wsca_misura')} (
			`misura` VARCHAR(50) NOT NULL,
			`id_magento` int NOT NULL,
			PRIMARY KEY (`id_magento`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabella misura';


		CREATE TABLE IF NOT EXISTS {$this->getTable('wsca_macro_category')} (
			`id_magento` int NOT NULL,
			`id_ws` VARCHAR(50) NOT NULL,
			PRIMARY KEY (`id_ws`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabella categoria';


		CREATE TABLE IF NOT EXISTS {$this->getTable('wsca_group')} (
			`id_magento` int NOT NULL,
			`id_ws` VARCHAR(50) NOT NULL,
			`id_macro_category` VARCHAR(50) NOT NULL,
			PRIMARY KEY (`id_macro_category`,`id_ws`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabella sottocategoria livello 1';

		CREATE TABLE IF NOT EXISTS {$this->getTable('wsca_subgroup')} (
			`id_magento` int NOT NULL,
			`id_ws` VARCHAR(50) NOT NULL,
			`id_macro_category` VARCHAR(50) NOT NULL,
			`id_group` VARCHAR(50) NOT NULL,
			PRIMARY KEY (`id_macro_category`,`id_group`,`id_ws`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabella sottocategoria livello 2';

		CREATE TABLE IF NOT EXISTS {$this->getTable('wsca_category')} (
			`id_magento` int NOT NULL,
			`id_ws` VARCHAR(50) NOT NULL,
			`id_macro_category` VARCHAR(50) NOT NULL,
			`id_group` VARCHAR(50) NOT NULL,
			`id_subgroup` VARCHAR(50) NOT NULL,
			PRIMARY KEY (`id_macro_category`,`id_group`,`id_subgroup`,`id_ws`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabella sottocategoria livello 3';



        CREATE TABLE IF NOT EXISTS {$this->getTable('wsca_attributes')} (
			`id_magento` int NOT NULL,
			`id_ws` VARCHAR(50) NOT NULL,
			PRIMARY KEY (`id_ws`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabella attributi';



		CREATE TABLE IF NOT EXISTS {$this->getTable('wsca_subattributes')} (
			`id_magento` int NOT NULL,
			`id_ws` VARCHAR(50) NOT NULL,
			`id_attributes` VARCHAR(50) NOT NULL,
			PRIMARY KEY (`id_ws`,`id_attributes`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabella valori attributi';



		CREATE TABLE IF NOT EXISTS {$this->getTable('wsca_misura_scalare')} (
			`misura` VARCHAR(50) NOT NULL,
			`scalare` int NOT NULL,
			`id_macro_category` VARCHAR(50) NOT NULL,
			`id_group` VARCHAR(50) NOT NULL,
			`id_subgroup` VARCHAR(50) NOT NULL,
			`id_category` VARCHAR(50) NOT NULL,
			`id_brand` VARCHAR(50) NOT NULL,
			PRIMARY KEY (`misura`,`id_macro_category`,`id_group`,`id_subgroup`,`id_category`,`id_brand`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabella misura-scalare';


        CREATE TABLE IF NOT EXISTS {$this->getTable('wsca_colore')} (
			`id_magento` int NOT NULL,
			`id_ws` VARCHAR(50) NOT NULL,
			`Nome_magento` VARCHAR(100) NOT NULL,
			PRIMARY KEY (`id_ws`,`id_magento`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabella colori';




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