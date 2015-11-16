<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */
class Amasty_Groupcat_Adminhtml_ProductsController extends Mage_Adminhtml_Controller_Action
{
    public function productsAction()
    {
        $grid = $this->getLayout()->createBlock('amgroupcat/adminhtml_rules_edit_tab_productaccess')
            ->setSelectedProducts($this->getRequest()->getPost('selected_products', null));

        // get serializer block html if needed
        $serializerHtml = '';
        if ($this->firstTimeDisplayedBlock()){
            $serializer = $this->getLayout()->createBlock('adminhtml/widget_grid_serializer');
            $serializer->initSerializerBlock($grid, 'getSavedProducts', 'selected_products', 'selected_products');
            $serializerHtml = $serializer->toHtml();
        }

        $this->getResponse()->setBody(
            $grid->toHtml() . $serializerHtml
        );
    }

    private function firstTimeDisplayedBlock()
    {
        $res = true;

        $params = $this->getRequest()->getParams();
        $keys   = array('sort', 'filter', 'limit', 'page');

        foreach($keys as $k){
            if (array_key_exists($k, $params))
                $res = false;
        }

        return $res;
    }

}