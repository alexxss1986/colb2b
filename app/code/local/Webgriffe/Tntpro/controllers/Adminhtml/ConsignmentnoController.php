<?php

class Webgriffe_Tntpro_Adminhtml_ConsignmentnoController extends Mage_Adminhtml_Controller_Action {

    protected function _initAction() {
        $this->loadLayout()
                ->_setActiveMenu('sales/wgtntpro/consignmentno')
                ->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));
        return $this;
    }

    public function indexAction() {
        $this->_title($this->__('Sales'))
                ->_title($this->__('Tnt Pro'))
                ->_title($this->__('Consigment Number'));

        $this->_initAction();

        $this->renderLayout();
    }

    public function editAction() {
        $itemId = $this->getRequest()->getParam('id');
        $item = Mage::getModel('wgtntpro/consignmentno')->load($itemId);
        if ($item->getId() || $itemId == 0) {
            Mage::register('wgtntpro_data', $item);
            $this->_initAction();

            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

            $this->_addContent($this->getLayout()->createBlock('wgtntpro/adminhtml_consignmentno_edit'));
            #$this->_addLeft($this->getLayout()->createBlock('wgtntpro/adminhtml_consignmentno_edit_tabs'));

            $this->renderLayout();
        } else {
            Mage::getSingleton('core/session')->addError(Mage::helper('wgtntpro')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction() {
        //$this->_forward('edit');
        $this->_redirect('*/*/edit');
    }

    public function cancelAction() {
        $item = Mage::getModel('wgtntpro/consignmentno');
        $request = $this->getRequest();
        $params = $request->getParams();
        $item->load($params['consignmentno']);
        if ($item->getId()) {
            try {
                $xml = Mage::helper('wgtntpro')->cancelXML($item);
                if (($msg=Mage::helper('wgtntpro')->soapCall($xml))===true) {
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('wgtntpro')->__('Elemento annullato con successo'));
                    $item->setCancelled(true);
                    $item->save();
                } else {
                    Mage::getSingleton('core/session')->addError(Mage::helper('wgtntpro')->__('Elemento NON annullato').": ".$msg);
                }

                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('core/session')->addError($e->getMessage());
                $this->_redirect('*/*/');
            }
        }

        $this->_redirect('*/*/');
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/wgtntpro/consignmentno');
    }

    public function requestManifestAction() {
        $params = $this->getRequest()->getParams();
        $csv = Mage::helper('wgtntpro')->generateManifestCSV($params["consignmentno"]);
        
        if ( Mage::helper('wgtntpro')->sendMail($csv) ) {
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('wgtntpro')->__('An Email was send to TNT'));
        } else {
            Mage::getSingleton('core/session')->addError(Mage::helper('wgtntpro')->__('There was an error'));
        }

        $this->_redirect('*/*/');
    }

}