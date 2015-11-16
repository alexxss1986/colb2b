<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */
class Amasty_Groupcat_Block_Adminhtml_Rules_Grid_Renderer_CustGroups extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $res = '';
        $data = explode(',',trim($row->getData($this->getColumn()->getIndex()),','));
        $options = Mage::getResourceModel('customer/group_collection')->load()->toOptionHash();
        foreach($data as $id){
            if (is_numeric($id)){
                $res .= $options[$id].', ';
            }
        }
        $res = trim($res,', ');


        return $res;
    }
}