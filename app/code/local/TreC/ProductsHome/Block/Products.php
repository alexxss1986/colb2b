<?php

class TreC_ProductsHome_Block_Products extends Mage_Core_Block_Template
{


    public function getProducts(){

        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $id_store=Mage::app()->getStore()->getStoreId();

        $stringQuery = "select title,post_image,identifier,short_content from " . $resource->getTableName('shopshark_blog') . " b,".$resource->getTableName('shopshark_blog_store')." s where store_id='".$id_store."' and b.post_id=s.post_id order by RAND() LIMIT 3";
        $result = $readConnection->fetchAll($stringQuery);


        return $result;
    }



}
