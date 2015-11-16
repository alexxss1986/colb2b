<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Block_Adminhtml_Page extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	/**
	 * Set the block options
	 *
	 * @return void
	 */
	public function __construct()
	{	
		parent::__construct();
		
		$this->_controller = 'adminhtml_page';
		$this->_blockGroup = 'splash';
		$this->_headerText = $this->__('Splash:') . ' ' . $this->__('Manage Pages');
		$this->_addButtonLabel = $this->__('Add New Page');
	}
}