<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

	// Declare helper class
	class Fishpig_AttributeSplashPro_Helper_LegacyHacks extends Mage_Core_Helper_Abstract {}
	
	$wpLegacyHacksFile = Mage::getBaseDir() . DS . implode(DS, array('app', 'code', 'community', 'Fishpig', 'Wordpress', 'Helper', 'LegacyHacks.php'));
	
	// Check whether WP Integration is installed
	$modules = (array)Mage::getConfig()->getNode('modules')->children();
	$wpModule = isset($modules['Fishpig_Wordpress']) ? (array)$modules['Fishpig_Wordpress'] : false;
	
	if ($wpModule !== false && (string)$wpModule['active'] === 'true' && is_file($wpLegacyHacksFile)) {
		Mage::helper('wordpress/legacyHacks');
	}
	else {
		// Declare legacy hack classes
		abstract class Mage_Core_Model_Resource_Db_Abstract extends Mage_Core_Model_Mysql4_Abstract {}
		abstract class Mage_Core_Model_Resource_Db_Collection_Abstract extends Mage_Core_Model_Mysql4_Collection_Abstract {}
	}
