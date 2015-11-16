<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

	$this->startSetup();
	
	$this->getConnection()->addColumn($this->getTable('splash_page'), 'layout_update_xml', " TEXT NOT NULL default '' AFTER price_filters");

	$this->endSetup();
	