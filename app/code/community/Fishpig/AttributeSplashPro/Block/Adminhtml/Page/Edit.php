<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Block_Adminhtml_Page_Edit  extends Mage_Adminhtml_Block_Widget_Form_Container
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
		$this->_headerText = $this->_getHeaderText();
		
		$this->_addButton('save_and_edit_button', array(
			'label' => Mage::helper('catalog')->__('Save and Continue Edit'),
			'onclick' => 'editForm.submit(\''. $this->getUrl('*/*/save', array('_current' => true, 'back' => 'edit')) .'\')',
			'class' => 'save',
		));
		
		$this->_removeButton('reset');
	}

	/**
	 * Enable WYSIWYG editor
	 *
	 */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        
        return $this;
    }
    
    /**
     * Retrieve the header text
     * If splash page exists, use name
     *
     * @return string
     */
	protected function _getHeaderText()
	{
		if (($page = Mage::registry('splash_page')) !== null) {
			return $this->__("Edit Page '%s'", $page->getName());
		}
	
		return $this->__('New Page');
	}
}
