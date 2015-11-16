<?php
/**
 * Mexbs_Urlrewrite_Block_Grid
 * class that is used for rewriting Mage_Adminhtml_Block_Review_Grid
 * to add a delete mass action
 *
 * @copyright Mexbs
 */
class Mexbs_Urlrewrite_Block_Grid extends Mage_Adminhtml_Block_Urlrewrite_Grid
{
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('url_rewrite_id');
        $this->setMassactionIdFilter('url_rewrite_id');
        $this->setMassactionIdFieldOnlyIndexValue(true);
        $this->getMassactionBlock()->setFormFieldName('url_rewrites');

        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('review')->__('Delete'),
            'url'  => $this->getUrl(
                '*/*/massDelete',
                array('ret' => Mage::registry('usePendingFilter') ? 'pending' : 'index')
            ),
            'confirm' => Mage::helper('mexbs_urlrewrite')->__('Are you sure?')
        ));
    }
}