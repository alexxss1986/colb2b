<?php

class TreC_CategoriesFilter_Block_Categories extends Mage_Core_Block_Template
{

    public function getCategories($categoryCurrent)
    {

        $categories = $categoryCurrent->getCollection()
            ->addAttributeToFilter('is_active', 1)
            ->addIdFilter($categoryCurrent->getChildren())
        ->addAttributeToSort('name', 'ASC');

        $i=0;
        $cat=array();
        foreach ($categories as $category){
            $categoria = Mage::getModel('catalog/category')->load($category->getId());
            $collection = Mage::getResourceModel('catalog/product_collection')->addCategoryFilter($categoria);
            Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
            Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
            Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);

            if ($collection->count() > 0) {
                $cat[$i][0]=$categoria->getUrl();
                $cat[$i][1]=$categoria->getName();

                $i=$i+1;
            }
        }

        return $cat;

    }
}