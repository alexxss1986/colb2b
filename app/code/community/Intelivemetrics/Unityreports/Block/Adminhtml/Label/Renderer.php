<?php

/**
 * Description
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */
class Intelivemetrics_Unityreports_Block_Adminhtml_Label_Renderer extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $config = $element->getData('field_config');
        $source = Mage::getModel($config->source_model);
        return $source->toHtml();
    }
}

?>
