<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CancelRender
 *
 * @author Roberto
 */
class Webgriffe_Tntpro_Block_Adminhtml_Consignmentno_Render_CancelRender extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Action
{

	function render(Varien_Object $row) {
		return $row->getCancelled() ? '<span style="color:#999;">'.Mage::helper('wgtntpro')->__('Cancelled').'</span>' : parent::render($row);
	}

}
