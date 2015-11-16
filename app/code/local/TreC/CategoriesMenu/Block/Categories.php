<?php

class TreC_CategoriesMenu_Block_Categories extends Mage_Core_Block_Template
{

    public function getCategories($sesso){


        $storeId=Mage::app()->getStore()->getStoreId();
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $customer = Mage::getSingleton('customer/session');
        if ($customer->isLoggedIn()){
            if ($customer->getCustomerGroupId()==4){
                $query="select * from " . $resource->getTableName("categorie_menu") . " where sesso='".$sesso."' and store_id='".$storeId."' order by posizione,parent,nome";
                $array=$readConnection->fetchAll($query);
            }
            else {
                $query="select * from " . $resource->getTableName("categorie_menu") . " where sesso='".$sesso."' and store_id='".$storeId."' and visibile=1 order by posizione,parent,nome";
                $array=$readConnection->fetchAll($query);
            }
        }
        else {
            $query="select * from " . $resource->getTableName("categorie_menu") . " where sesso='".$sesso."' and store_id='".$storeId."' and visibile=1 order by posizione,parent,nome";
            $array=$readConnection->fetchAll($query);
        }



        return $array;

    }



    public function getDesigners($sesso){

        $storeId=Mage::app()->getStore()->getStoreId();
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $customer = Mage::getSingleton('customer/session');
        if ($customer->isLoggedIn()){
            if ($customer->getCustomerGroupId()==4){
                $query="select * from " . $resource->getTableName("designer_menu") . " where sesso='".$sesso."' and store_id='".$storeId."' order by nome";
                $brand=$readConnection->fetchAll($query);
            }
            else {
                $query="select * from " . $resource->getTableName("designer_menu") . " where sesso='".$sesso."' and store_id='".$storeId."' and visibile=1 order by nome";
                $brand=$readConnection->fetchAll($query);
            }
        }
        else {
            $query="select * from " . $resource->getTableName("designer_menu") . " where sesso='".$sesso."' and store_id='".$storeId."' and visibile=1 order by nome";
            $brand=$readConnection->fetchAll($query);
        }




        return $brand;


    }
}
