<?php

class Webgriffe_Tntpro_Block_Adminhtml_Magazzini_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $fieldsetBasic = $form->addFieldset('wgtntpro_form_general', array('legend' => Mage::helper('wgtntpro')->__('General')));

        //
        // Account Number fornito da tnt
        //		
        $fieldsetBasic->addField('sender_acc_id', 'text', array(
            'label' => Mage::helper('wgtntpro')->__('Account'),
            'required' => true,
            'class' => 'required-entry',
            'name' => 'sender_acc_id',
        ));


        //
        // Nome
        //
        $fieldsetBasic->addField('name', 'text', array(
            'name' => 'name',
            'label' => Mage::helper('wgtntpro')->__('Nome'),
            'class' => 'required-entry',
            'required' => true,
        ));

        //
        // Nome
        //
        $fieldsetBasic->addField('vatno', 'text', array(
            'name' => 'vatno',
            'label' => Mage::helper('wgtntpro')->__('VAT number'),            
            'required' => false,
        ));

        
        $fieldsetBasic->addField('addrline1', 'text', array(
            'label' => Mage::helper('wgtntpro')->__('Indirizzo'),
            'required' => true,
            'class' => 'required-entry',
            'name' => 'addrline1',
        ));

        $fieldsetBasic->addField('town', 'text', array(
            'label' => Mage::helper('wgtntpro')->__('Città'),
            'required' => true,
            'class' => 'required-entry',
            'name' => 'town',
        ));

        $fieldsetBasic->addField('province', 'text', array(
            'label' => Mage::helper('wgtntpro')->__('Provincia'),
            'required' => true,
            'class' => 'required-entry',
            'name' => 'province',
        ));

        $fieldsetBasic->addField('postcode', 'text', array(
            'label' => Mage::helper('wgtntpro')->__('CAP'),
            'required' => true,
            'class' => 'required-entry',
            'name' => 'postcode',
        ));

        $countries = Mage::getModel('adminhtml/system_config_source_country')
                ->toOptionArray();
        #unset($countries[0]);

        $fieldsetBasic->addField('country', 'select', array(
            'label' => Mage::helper('wgtntpro')->__('Nazione'),
            'required' => true,
            'class' => 'required-entry',
            'name' => 'country',
            'values' => $countries,
        ));

        $fieldsetBasic->addField('contactname', 'text', array(
            'label' => Mage::helper('wgtntpro')->__('Alla attenzione di'),
            'required' => false,
            //'class' => 'required-entry',
            'name' => 'contactname',
        ));

        $fieldsetBasic->addField('phone1', 'text', array(
            'label' => Mage::helper('wgtntpro')->__('Prefisso Telefono'),
            'required' => true,
            'class' => 'required-entry',
            'name' => 'phone1',
        ));
        $fieldsetBasic->addField('phone2', 'text', array(
            'label' => Mage::helper('wgtntpro')->__('Numero Telefono'),
            'required' => true,
            'class' => 'required-entry',
            'name' => 'phone2',
        ));

        $fieldsetBasic->addField('fax1', 'text', array(
            'label' => Mage::helper('wgtntpro')->__('Prefisso Fax'),
            'name' => 'fax1',
        ));
        $fieldsetBasic->addField('fax2', 'text', array(
            'label' => Mage::helper('wgtntpro')->__('Numero Fax'),
            'name' => 'fax2',
        ));
        
        $fieldsetBasic->addField('email', 'text', array(
            'label' => Mage::helper('wgtntpro')->__('Email'),
            'required' => true,
            'class' => 'required-entry',
            'name' => 'email',
        ));
        
        //
        // Default
        // 
        $fieldsetBasic->addField('default', 'select', array(
            'name' => 'default',
            'label' => Mage::helper('wgtntpro')->__('Magazzino di default'),
            'values' => array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('wgtntpro')->__('Sì'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('wgtntpro')->__('No'),
                ),
            ),
        ));

        if (Mage::getSingleton('adminhtml/session')->getCustomMenuData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getCustomMenuData());
            Mage::getSingleton('adminhtml/session')->setCustomMenuData(null);
        } elseif (Mage::registry('wgtntpro_data')) {
            $form->setValues(Mage::registry('wgtntpro_data')->getData());
        }

        return parent::_prepareForm();
    }

}