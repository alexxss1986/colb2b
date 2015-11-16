<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */
class Amasty_Groupcat_Block_Adminhtml_Rules_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        /* @var $hlp Amasty_Groupcat_Helper_Data */
        $hlp = Mage::helper('amgroupcat');

        /* @var $model Mage_Cms_Model_Page */
        $model = Mage::getModel('cms/page');

        $fldInfo = $form->addFieldset('general', array('legend'=> $hlp->__('General')));

        $fldInfo->addField('enable', 'select', array(
            'label'     => $hlp->__('Enable'),
            'title'     => $hlp->__('Enable'),
            'name'      => 'enable',
            'required'  => true,
            'options'   => array(
                '0' => $this->__('No'),
                '1' => $this->__('Yes'),
            ),
        ));

        $fldInfo->addField('rule_name', 'text', array(
            'label'     => $hlp->__('Name'),
            'required'  => true,
            'name'      => 'rule_name',
        ));
        
        $fldInfo->addField('cust_groups', 'multiselect', array(
            'label'     => $hlp->__('Customer Groups'),
            'name'      => 'cust_groups[]',
            'values'    => $hlp->getCustomerGroups(), 
        ));

        $field = $fldInfo->addField('store_id', 'multiselect', array(
            'name' => 'stores[]',
            'label' => Mage::helper('cms')->__('Store View'),
            'title' => Mage::helper('cms')->__('Store View'),
            'required' => true,
            'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
        ));
        $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
        $field->setRenderer($renderer);
        
        $data = Mage::registry('amgroupcat_rules')->getData();

        /*
         * normalize data from database stored in string into array
         */
        if ($data){
            $data['store_id']    = $data['stores'] = explode(',', $data['stores']);
            $data['cust_groups'] = explode(',', $data['cust_groups']);
        } else{
            $data['store_id'] = array(0);
            $data['cust_groups'] = array(0);
        }

        if(!Mage::registry('amgroupcat_rules')->getId()) {
           $data['enable'] = '1';
        }

        /*
         * map data to form
         */
        $form->setValues($data);

        $this->setTitle(Mage::helper('amgroupcat')->__('Edit'));

        return parent::_prepareForm();
    }
}
