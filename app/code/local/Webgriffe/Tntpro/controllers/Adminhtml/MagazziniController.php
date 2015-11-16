<?php

class Webgriffe_Tntpro_Adminhtml_MagazziniController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
                ->_setActiveMenu('sales/wgtntpro/magazzini')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        return $this;
    }

    public function indexAction()
    {
        $this->_title($this->__('Sales'))
                ->_title($this->__('Tnt Pro'))
                ->_title($this->__('Magazzini'));

        $this->_initAction();

        $this->renderLayout();
    }

    public function editAction()
    {
        $itemId = $this->getRequest()->getParam('id');
        $item = Mage::getModel('wgtntpro/magazzini')->load($itemId);
        if ($item->getId() || $itemId == 0) {
            Mage::register('wgtntpro_data', $item);
            $this->_initAction();

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('wgtntpro/adminhtml_magazzini_edit'))
                    ->_addLeft($this->getLayout()->createBlock('wgtntpro/adminhtml_magazzini_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('core/session')->addError(Mage::helper('wgtntpro')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        //$this->_forward('edit');
        $this->_redirect('*/*/edit');
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $item = Mage::getModel('wgtntpro/magazzini');
                $item->setId($this->getRequest()->getParam('id'))
                        ->delete();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Elemento cancellato con successo'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('core/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }

        $this->_redirect('*/*/');
    }

    public function saveAction($item = null, $postData = null)
    {
        if (is_null($postData)) {
            $postData = $this->getRequest()->getParams();
        }

        if ($postData) {
            try {
                if (is_null($item)) {
                    $item = Mage::getModel('wgtntpro/magazzini');
                }

                if ($postData['default']) {
                    $item->getResource()->resetDefaults();
                }

                $item->setIsSaving(TRUE) // needed to avoid getting the associated object id
                        ->setMagazzinoId($postData['id']);

                foreach ($postData as $key => $value) {
                    $item->setData($key, $value);
                }

                $errors = $item->validate();
                if (empty($errors)) {
                    $item->save();

                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Salvato con successo'));
                    Mage::getSingleton('adminhtml/session')->setData(false);
                    $this->_redirect('*/*/');
                    return;
                } else {
                    foreach ($errors as $error) {
                        // Error messages must be set in core/session
                        Mage::getSingleton('core/session')->addError($error);
                    }
                    Mage::getSingleton('adminhtml/session')->setData($postData);
                    $this->_redirect('*/*/edit', array('id' => $postData['id']));
                    return;
                }
            } catch (Exception $e) {
                Mage::getSingleton('core/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setData($postData);
                $this->_redirect('*/*/edit', array('id' => $postData['id']));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/wgtntpro/magazzini');
    }

}