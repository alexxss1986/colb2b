<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */
class Amasty_Groupcat_Block_Adminhtml_Rules_Edit_Tab_Restriction extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $hlp = Mage::helper('amgroupcat');
        
        $fldProduct = $form->addFieldset('product', array('legend'=> $hlp->__('Links')));
        $fldProduct->addField('remove_product_links', 'select', array(
            'label'     => $hlp->__('Remove Product Links'),
            'title'     => $hlp->__('Remove Product Links'),
            'name'      => 'remove_product_links',
            'options'   => array(
                '0' => $this->__('No'),
                '1' => $this->__('Yes'),
            ),
        ));
        
        $fldProduct->addField('remove_category_links', 'select', array(
            'label'     => $hlp->__('Remove Category Links'),
            'title'     => $hlp->__('Remove Category Links'),
            'name'      => 'remove_category_links',
            'options'   => array(
                '0' => $this->__('No'),
                '1' => $this->__('Yes'),
            ),
        ));


        $fldRestrictAction = $form->addFieldset('general', array('legend'=> $hlp->__('Restriction Action')));

        $fldRestrictAction->addField('allow_direct_links', 'select', array(
            'label'     => $hlp->__('Allow Direct Links'),
            'title'     => $hlp->__('Allow Direct Links'),
            'name'      => 'allow_direct_links',
            'options'   => array(
                '0' => $this->__('No'),
                '1' => $this->__('Yes'),
            ),
        ));

        $fldRestrictAction->addField('forbidden_action', 'select', array(
            'label'     => $hlp->__('Forbidden Action'),
            'title'     => $hlp->__('Forbidden Action'),
            'name'      => 'forbidden_action',
            'options'   =>  array(
                '1' => $this->__('404 Page'),
                '2' => $this->__('Redirect to CMS page'),
            )
        ));
        
        $fldRestrictAction->addField('cms_page', 'select', array(
            'label'     => $hlp->__('CMS Page'),
            'title'     => $hlp->__('CMS Page'),
            'name'      => 'cms_page',
            'options'   => $hlp->getCmsPages(),
            'after_element_html'   => $hlp->__('<br/><small>* only if "Forbidden Action" is set to "Redirect to CMS page"</small>'),
        ));
        
        $fldPrice = $form->addFieldset('price', array('legend'=> $hlp->__('Price')));
        $fldPrice->addField('hide_price', 'select', array(
            'label'     => $hlp->__('Hide Price and Add To Cart'),
            'title'     => $hlp->__('Hide Price and Add To Cart'),
            'name'      => 'hide_price',
            'options'   => array(
                '0' => $this->__('No'),
                '1' => $this->__('Yes'),
            ),
        ));
        
        $fldPrice->addField('price_on_product_view', 'select', array(
            'label'     => $hlp->__('Replace with CMS Block on Product View'),
            'title'     => $hlp->__('Replace with CMS Block on Product View'),
            'name'      => 'price_on_product_view',
            'options'   => $hlp->getCmsBlocks(),
        ));
        
        $fldPrice->addField('price_on_product_list', 'select', array(
            'label'     => $hlp->__('Replace with CMS Block on Product List'),
            'title'     => $hlp->__('Replace with CMS Block on Product List'),
            'name'      => 'price_on_product_list',
            'options'   => $hlp->getCmsBlocks(),
        ));
        
        $data = Mage::registry('amgroupcat_rules')->getData();

        /*
         * set "default" values
         */
        if (!$data) {
            $data['hide_price'] = 1;
        }

        $form->setValues($data);
        
        return parent::_prepareForm();
    }

}
