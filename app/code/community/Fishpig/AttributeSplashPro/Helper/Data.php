<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Ensure legacy hack has been applied
	 *
	 */
	public function __construct()
	{
		$this->_applyLegacyHacks();
	}
	
	/**
	 * Apply legacy hacks if on a system that requires it
	 *
	 * @return $this
	 */
	protected function _applyLegacyHacks()
	{
		$key = 'splashpro_legacy_hacks';
		
		if (Mage::registry($key) !== true) {
			Mage::register($key, true);

			$resourceDbFile = Mage::getModuleDir('', 'Mage_Core') . DS . implode(DS, array('Model', 'Resource', 'Db', 'Abstract.php'));

			if (!is_file($resourceDbFile)) {
				Mage::helper('splash/legacyHacks');
			}
		}
		
		return $this;
	}
}
