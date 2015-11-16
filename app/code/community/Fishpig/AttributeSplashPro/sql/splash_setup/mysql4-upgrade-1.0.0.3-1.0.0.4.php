<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

	$this->startSetup();
	
	$this->getConnection()->addColumn($this->getTable('splash_page'), 'category_filters', " TEXT NOT NULL default '' AFTER price_filters");

	$this->endSetup();
	