<?php
/**
 * @category    Fishpig
 * @package     Fishpig_AttributeSplash
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_AttributeSplashPro_Lib_Varien_Data_Form_Element_Select extends Varien_Data_Form_Element_Select
{
	/**
	 * Ensures that that both '' and '0' don't get set as selected at the same time
	 *
	 * @param $option
	 * @param array $selected
	 * @return string
	 */
	protected function _optionToHtml($option, $selected)
	{
		if (count($selected)  > 0 && trim($selected[0]) === '') {
			$selected = array();
		}

		return parent::_optionToHtml($option, $selected);
	}
}