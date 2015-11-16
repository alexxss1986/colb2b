<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */
class Amasty_Groupcat_Block_Adminhtml_Rules extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_rules';
        $this->_blockGroup = 'amgroupcat';
        $this->_headerText = Mage::helper('amgroupcat')->__('Rules');
        $this->_addButtonLabel = Mage::helper('amgroupcat')->__('Add Rule');
        
        parent::__construct();
    }
}