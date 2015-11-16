<?php

class TreC_BoutiquesPage_Block_Boutiques extends Mage_Core_Block_Template
{

    public function getBoutiques(){

        $i=0;
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $query = "select name from " . $resource->getTableName('wg_warehouse') . " where id <= 4 or id=8 order by position";
        $boutiques = $readConnection->fetchAll($query);

        foreach ($boutiques as $boutique){
            $magazzini[$i]=str_replace("Boutique ","",$boutique["name"]);
            $i=$i+1;
        }

        return $magazzini;


    }

    public function getListaBrands($boutique){

        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $query="select id_brand,nome_brand from " . $resource->getTableName('boutique_brands') . " where boutiques='".$boutique."' order by nome_brand";
        $brands = $readConnection->fetchAll($query);

        $arrayBrand=array();
        $i=0;
        foreach ($brands as $brand) {
            $id_brand = $brand["id_brand"];
            $slug_brand = $brand["nome_brand"];


            $productModel = Mage::getModel('catalog/product');
            $attr = $productModel->getResource()->getAttribute("ca_brand");
            if ($attr->usesSource()) {
                $nome_brand = $attr->getSource()->getOptionText($id_brand);
            }

            $prodotti = Mage::getModel('catalog/product')->getCollection()
                ->addFieldToFilter(array(
                    array('attribute' => 'ca_brand', 'eq' => $id_brand)));
            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($prodotti);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($prodotti);


            if (count($prodotti) > 0) {

                $arrayBrand[$i][0] = $id_brand;
                $arrayBrand[$i][1] = $nome_brand;
                $arrayBrand[$i][2] = $slug_brand;
                $arrayBrand[$i][3] = "1";
                $i = $i + 1;
            } else {
                $arrayBrand[$i][0] = $id_brand;
                $arrayBrand[$i][1] = $nome_brand;
                $arrayBrand[$i][2] = $slug_brand;
                $arrayBrand[$i][3] = "0";
                $i = $i + 1;
            }
        }

            return $arrayBrand;

    }

}
