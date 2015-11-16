<?php

class TreC_DesignerMenu_Block_Designer extends Mage_Core_Block_Template
{

    public function getBrands(){

        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $customer = Mage::getSingleton('customer/session');
        if ($customer->isLoggedIn()){
            if ($customer->getCustomerGroupId()==4){
                $query = "select DISTINCT(id),nome,prodotti from " . $resource->getTableName("designer_menu_brand") . " order by nome";
                $brand = $readConnection->fetchAll($query);
            }
            else {
                $query = "select DISTINCT(id),nome,prodotti from " . $resource->getTableName("designer_menu_brand") . " where visibile=1 order by nome";
                $brand = $readConnection->fetchAll($query);
            }
        }
        else {

            $query = "select DISTINCT(id),nome,prodotti from " . $resource->getTableName("designer_menu_brand") . " where visibile=1 order by nome";
            $brand = $readConnection->fetchAll($query);

        }
        return $brand;


    }


    public function getBrands2(){

        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');


        $query="select DISTINCT(m.id),m.nome,m.prodotti,r.categoria from " . $resource->getTableName("designer_menu_brand") . " m,".$resource->getTableName('brand_relation')." r where m.id=r.id order by m.nome";
        $brand=$readConnection->fetchAll($query);

        return $brand;


    }


    public function getProductsBrand($brand){

        $i=0;

        $products = Mage::getModel('catalog/product')->getCollection();
        $products->addAttributeToFilter("ca_brand",$brand);
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);
        $products->getSelect()->order('RAND()');
        $products->getSelect()->limit(4);

        if (count($products)>0){
            foreach ($products as $product) {
                $prodotto[$i]=$product->getId();
                $i=$i+1;
            }
        }
        else {
            $prodotto=array();
        }

        return $prodotto;
    }


    public function getTitleBrand($brand){
        $store_id=Mage::app()->getStore()->getStoreId();
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        if ($store_id==1) {
            $stringQuery = "select testo_first as 'testo',categoria from " . $resource->getTableName('brand_relation') . " where id='" . $brand . "'";
            $result = $readConnection->fetchAll($stringQuery);
        }
        else if ($store_id==2){
            $stringQuery = "select testo_eng as 'testo',categoria from " . $resource->getTableName('brand_relation') . " where id='" . $brand . "'";
            $result = $readConnection->fetchAll($stringQuery);
        }

        return $result;
    }


    public function getBoutiquesBrand($brand){
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $stringQuery = "select boutiques from " . $resource->getTableName('boutique_brands') . " where id_brand='" . $brand . "' order by boutiques";
        $result = $readConnection->fetchAll($stringQuery);

        return $result;
    }


    public function getLastCategory($product){
        $categoryModel = Mage::getModel( 'catalog/category' );

//Get Array of Category Id's with Last as First (Reversed)
        $_categories = array_reverse( $product->getCategoryIds() );

//Load Category
        $_category = $categoryModel->load($_categories[0]);

        return $_category;
    }

}
