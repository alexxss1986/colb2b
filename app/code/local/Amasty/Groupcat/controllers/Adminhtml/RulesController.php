<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */ 
class Amasty_Groupcat_Adminhtml_RulesController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('catalog/amgroupcat');
        $this->_addContent($this->getLayout()->createBlock('amgroupcat/adminhtml_rules'));
        $this->renderLayout();
    }


    public function newAction()
    {
        $this->editAction();
    }

    public function editAction()
    {
        $id       = (int) $this->getRequest()->getParam('id');
        $model    = Mage::getModel('amgroupcat/rules')->load($id);
        $postData = Mage::registry('amgroupcat_rules_saved_data');

        if ($id == 0 && $postData){
            $model    = Mage::getModel('amgroupcat/rules')->load($id);
        } else if ($id && !$model->getId()) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amgroupcat')->__('Record does not exist'));
            $this->_redirect('*/*/');
            return;
        }

        /*
         * save form data for all children tabs forms
         */
        Mage::register('amgroupcat_rules', $model);

        /*
         * add tabs on page
         */
        $this->loadLayout();
        $this->_setActiveMenu('catalog/amgroupcat/rules');
        $this->_title($this->__($id?'Edit rule':'New rule'));
        $this->_addContent($this->getLayout()->createBlock('amgroupcat/adminhtml_rules_edit'));
        $this->_addLeft($this->getLayout()->createBlock('amgroupcat/adminhtml_rules_edit_tabs'));

        /*
         * add ExtJS library for "Category Access Restriction" tab with hierarchical visual output categories
         */
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true)->setCanLoadRulesJs(true);

        $this->renderLayout();
    }


    /*
     * Action for getting Category Tree (JS tree view) for tab `Access Category Restriction`
     * which is build with AJAX requests calling this action
     */
    public function categoriesJsonAction()
    {
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('amgroupcat/adminhtml_rules_edit_tab_categoryaccess')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }


    public function saveAction()
    {
        $id     = $this->getRequest()->getParam('id');
        $model  = Mage::getModel('amgroupcat/rules');

        $data = $this->getRequest()->getPost();
        if (!$data) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amgroupcat')->__('Unable to find an option to save'));
            $this->_redirect('*/adminhtml_filter/');
        } else {
            try{
                /*
                 * save main "rule" row into database
                 */
                $model->setData($data)->setId($id);

                /*
                 * remove duplicates && clean the string
                 */
                $categories = array_unique(explode(',', $data['categories']));
                asort($categories);
                if ($categories[0] == '') {
                    unset($categories[0]);
                }
                $count = count($categories);
                $model->setData('cats_count', $count);
                $categories = $categories ? ',' . implode(',', $categories) . ',' : '';
                $model->setData('categories', $categories);

                if (is_array($data['cust_groups'])){
                    $cust_groups = ','.implode(',',$data['cust_groups']).',';
                    $cust_groups = preg_replace("/(,)\\1+/", "$1", $cust_groups);
                    $model->setData('cust_groups', $cust_groups);
                }
                if (is_array($data['stores'])){
                    $model->setData('stores', ','.implode(',',$data['stores']).',');
                }
                $model->save();
                $id = $model->getId();

                /*
                 * save each "Rule->Product" relation in database
                 */
                $modelProduct  = Mage::getModel('amgroupcat/product');
                $product_ids = $model->getSelectedProducts();
                if (!is_null($product_ids)){
                    $product_ids = Mage::helper('adminhtml/js')->decodeGridSerializedInput($product_ids);
                    $model->setData('prods_count', count($product_ids));
                    $model->save();
                    $modelProduct->assignProducts($product_ids, $id);
                }

                /*
                 * delete saved data from registry
                 * add success messages and redirect user to the next page
                 */
                $cache = Mage::app()->getCacheInstance()->invalidateType('block_html');
                Mage::unregister('amgroupcat_rules_save_data');
                $msg = Mage::helper('amgroupcat')->__('Rule has been successfully saved');
                Mage::getSingleton('adminhtml/session')->addSuccess($msg);
                if ($this->getRequest()->getParam('continue')){
                    $this->_redirect('*/*/edit', array('id' => $model->getRuleId()));
                }
                else {
                    $this->_redirect('*/*');
                }
            }
            catch (Exception $e) {
                /*
                 * save data for "Edit page"
                 * (coz we redirect on it, but POST is null after redirect)
                 */
                Mage::register('amgroupcat_rules_saved_data', $data);
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $id));
            }

            return;
        }
    }


    public function deleteAction()
    {
        $id     = (int) $this->getRequest()->getParam('id');
        $modelRule  = Mage::getModel('amgroupcat/rules')->load($id);

        if ($id && !$modelRule->getId()) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Record does not exist'));
            $this->_redirect('*/*/');
            return;
        }

        try {
            $modelRule->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess(
                $this->__($this->_title . ' has been successfully deleted'));
        }
        catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/');
    }


    public function massDeleteAction()
    {
        $ids = $this->getRequest()->getParam('ids');
        if (!is_array($ids)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('amgroupcat')->__('Please select records'));
            $this->_redirect('*/*/');
            return;
        }
        try {
            foreach ($ids as $id) {
                $modelRule = Mage::getModel('amgroupcat/rules')->load($id);
                $modelRule->delete();
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('adminhtml')->__(
                    'Total of %d record(s) were successfully deleted', count($ids)
                )
            );
        }
        catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }
}
