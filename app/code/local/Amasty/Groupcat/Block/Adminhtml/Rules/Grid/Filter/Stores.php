<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */
class Amasty_Groupcat_Block_Adminhtml_Rules_Grid_Filter_Stores extends Mage_Adminhtml_Block_Widget_Grid_Column_Filter_Store
{
    public function getCondition()
    {
        $value = $this->getValue();
        if (is_null($value)) {
            return null;
        }

        return array('or'=>array('like' => '%,' . $value . ',%'));
    }
}