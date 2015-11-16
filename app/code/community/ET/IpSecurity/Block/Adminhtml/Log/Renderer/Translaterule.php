<?php
class ET_IpSecurity_Block_Adminhtml_Log_Renderer_Translaterule
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    /**
     * Renders grid column
     *
     * @param Varien_Object $row
     * @return mixed
     */
    public function _getValue(Varien_Object $row)
    {
        $data = parent::_getValue($row);
        return Mage::helper('etipsecurity')->__($data);
    }
}
