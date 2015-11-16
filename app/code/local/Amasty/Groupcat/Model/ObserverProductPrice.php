<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2015 Amasty (https://www.amasty.com)
 * @package Amasty_Groupcat
 */
class Amasty_Groupcat_Model_ObserverProductPrice
{

    const FORBIDDEN_ACTION_404      = '1';
    const FORBIDDEN_ACTION_REDIRECT = '2';


    /*
     * check restrictions for "hide_price"
     */
    public function hideProductsPrice(Varien_Event_Observer $observer)
    {
        if (Mage::getStoreConfig('amgroupcat/general/disable')) {
            return false;
        }

        $replace = false; /* flag about was replaced content or not */

        /*
         * check restrictions on product list
         * remove "add to cart" buttons
         */
        if ($observer->getBlock() instanceof Mage_Catalog_Block_Product_List) {
            $output = $observer->getTransport()->getHtml();
            if ($category = Mage::registry('current_category')) {
                $categoryId = Mage::registry('current_category')->getId();
            } else {
                $categoryId = -1;
            }
            $activeRules = Mage::helper('amgroupcat')->getActiveRules(array('hide_price = 1'));
            if (empty($activeRules)) {
                return false;
            }

            /*
             * replace with text: "price-box" container
             * if any rule have revrite set
             */
            foreach ($activeRules as $rule) {
                if (in_array($categoryId, explode(',', trim($rule['categories'], ',')))) {
                    if ($rule['price_on_product_list']){
                        $replace = Mage::helper('cms')->getPageTemplateProcessor()->filter(Mage::getModel('cms/block')->load($rule['price_on_product_list'])->getContent());
                        $output = $this->replaceAllMatches($output, '<div class="price-box', '</div>', $replace);
                    }
                }
            }

            /*
             * only if there were any rules for replace block
             */
            if ($replace) {
                $output = $this->replaceCommons($output);   /* direct replace all `commons` entries on the page */
            }

            /*
             *
             */

            /*
             * save result
             */
            $observer->getTransport()->setHtml($output);
        } else

            /*
             * check restrictions on product page
             */
            if ($observer->getBlock() instanceof Mage_Catalog_Block_Product_Price || $observer->getBlock() instanceof Mage_Catalog_Block_Product_View) {
                $replace = false;
                $output  = $observer->getTransport()->getHtml();
                try {
                    $productId  = $observer->getBlock()->getProduct()->getId();
                    $categories = $observer->getBlock()->getProduct()->getCategoryCollection();
                } catch (Exception $e) {
                    return false;
                }
                $activeRules = Mage::helper('amgroupcat')->getActiveRulesForProductPrice($productId);

                /*
                 * apply rules for product
                 */
                if (!empty($activeRules)) {
                    foreach ($activeRules as $rule) {
                        if ($observer->getBlock() instanceof Mage_Catalog_Block_Product_View && $rule['price_on_product_view']) {
                            $replace = Mage::helper('cms')->getPageTemplateProcessor()->filter(Mage::getModel('cms/block')->load($rule['price_on_product_view'])->getContent());
                            $output  = $this->replaceAllMatches($output, '<div class="price-box', '</div>', $replace);
                        } elseif ($observer->getBlock() instanceof Mage_Catalog_Block_Product_Price && $rule['price_on_product_list']) {
                            $replace = Mage::helper('cms')->getPageTemplateProcessor()->filter(Mage::getModel('cms/block')->load($rule['price_on_product_list'])->getContent());
                            $output  = $this->addCommonsReplaceID($output, $productId, $replace);
                        }
                    }
                    $replace = true;
                }

                /*
                 * apply rules for category
                 */
                $categoryId = array();
                foreach ($categories as $category) {
                    $categoryId[] = $category->getId();
                }
                $activeRules = Mage::helper('amgroupcat')->getActiveRules(array('hide_price = 1'));
                if (is_array($activeRules) && count($activeRules) > 0) {
                    foreach ($activeRules as $rule) {
                        $categories = explode(',', trim($rule['categories'], ','));
                        foreach ($categoryId as $catId) {
                            if (in_array($catId, $categories)) {
                                if ($observer->getBlock() instanceof Mage_Catalog_Block_Product_View && $rule['price_on_product_view']) {
                                    $replace = Mage::helper('cms')->getPageTemplateProcessor()->filter(Mage::getModel('cms/block')->load($rule['price_on_product_view'])->getContent());
                                    $output  = $this->replaceAllMatches($output, '<div class="price-box', '</div>', $replace);
                                } else if ($rule['price_on_product_list']){
                                    $replace = Mage::helper('cms')->getPageTemplateProcessor()->filter(Mage::getModel('cms/block')->load($rule['price_on_product_list'])->getContent());
                                    $output  = $this->replaceCommons($output);
                                    $output  = $this->addCommonsReplace($output, $replace);
                                    $replace = '';
                                }
                            }
                        }
                    }
                }

                /*
                 * hide price boxes and add to cart buttons
                 */
                if ($replace) {
                    $output = $this->replaceCommons($output);
                    $output = $this->addCommonsHideId($output, $productId);
                }

                /*
                 * save result
                 */
                $observer->getTransport()->setHtml('<div class="amgroupcat">' . $output . '</div>');

            } else

                /*
                 * check restrictions for Category Hideng from menu
                 * fix specially for Infortis Themes
                 */
                if ($observer->getBlock() instanceof Infortis_UltraMegamenu_Block_Navigation || $observer->getBlock() instanceof Mage_Page_Block_Html_Topmenu) {
                    $output      = $observer->getTransport()->getHtml();
                    $categoryIds = array();
                    $activeRules = Mage::helper('amgroupcat')->getActiveRules(array('remove_category_links = 1'));/* active rules which have "remove_category_links" flag */
                    if (!empty($activeRules)) {
                        foreach ($activeRules as $rule) {
                            $ids         = explode(',', $rule['categories']);
                            $categoryIds = array_merge($ids, $categoryIds);
                        }

                        $categoryIds = array_unique($categoryIds);
                    }


                    $storeId = Mage::app()->getStore()->getStoreId();
                    foreach ($categoryIds as $id) {
                        $category = Mage::getModel('catalog/category')->setStoreId($storeId)->load($id);
                        $catName  = $category->getName();
                        $catUrl   = $category->getUrl();
                        $output   = $this->replaceAllMatches($output, '<a href="' . $catUrl . '"', '</a>', $replace);
                    }
                    $observer->getTransport()->setHtml($output);
                }

        return $this;
    }


    /*
     * replace string part which starts with "$start..." symbols and ends "...$end" symbols
     * with $replace text.
     * e.g.:
     *  setReplace('<p class=text>','</p>','<span>New Strings</span>')
     *  before: some <p class="text">TEXT</p> need to be replaced here
     *  after : some <span>New Strings</span> need to be replaced here
     *
     * @var return string
     */

    private function replaceAllMatches($text, $start, $end, $replace)
    {
        $i = 0;
        while (($x = strpos($text, $start)) !== false && $i < 1000) { /* "$i" is just for sure that it's not a infinite loop */
            $text = $this->replaceText($text, $start, $end, $replace);
            $i++;
        }

        return $text;
    }

    /*
     * replaces ALL matches of text in given string
     */

    private function replaceText($text, $start, $end, $replace)
    {
        $start  = stripos($text, $start);
        $end    = strlen($end) + stripos($text, $end, $start);
        $length = mb_strlen($text);

        return substr($text, 0, $start) . $replace . substr($text, $end, $length);
    }


    /*
     * Replaces common blocks that exist in all blocks with price
     *
     * hide button : Add To Cart
     * hide button : Add to Compare
     * hide on flag: "price-box" container
     */

    private function replaceCommons($output)
    {
        $output = $this->replaceAllMatches($output, '<button type="button" title="Add to Cart"', '</button>', ' ');
        $output = str_replace("add-to-cart", "add-to-cart amhide", $output);
        $output = str_replace("add-to-links", "add-to-links amhide", $output);
        $output = str_replace("add-to-box", "add-to-box amhide", $output);
        $output = str_replace("tier-prices", "tier-prices amhide", $output);
        $output = str_replace("price-box", "price-box amhide", $output);

        return $output;
    }

    /*
     * Adds JS to hide item 'actions' block with specified Id
     */

    private function addCommonsReplaceId($output, $id, $replace)
    {
        $replace = addslashes(nl2br($replace));
        $replace = str_replace(array("\r", "\n"), array('', ''), $replace);
        $script = "
            <script type='text/javascript'>
                Event.observe(window, 'load', function() {
                    try {
                       $('product-price-$id').up('div.amgroupcat').next('div.actions').update('$replace').addClassName('amgroupcat').removeClassName('actions');
                    } catch(err) {
                        console.log('Groupcat: cannot find element to hide and replace');
                    }
                    try {
                       $('product-price-$id').up('div.amgroupcat').next('p').down('button.button.btn-cart').update('$replace').addClassName('amgroupcat');
                    } catch(err) {
                        console.log('Groupcat: cannot find button to hide and replace');
                    }
                 });
            </script>
        ";

        $output .= $script;

        return $output;
    }

    /*
     * Adds JS to replace item 'actions' block with specified Id
     */

    private function addCommonsReplace($output, $replace)
    {
        $replace = addslashes(nl2br($replace));
        $replace = str_replace(array("\r", "\n"), array('', ''), $replace);
        $script  = "
            <script type='text/javascript'>
                Event.observe(window, 'load', function() {
                    try {
                        $$('.price-box.amhide').each(function (element) {
                            element.up('div.amgroupcat').next('div.actions').update('$replace').addClassName('amgroupcat').removeClassName('actions');
                        });
                    } catch(err) {
                        console.log('Groupcat: cannot find element to hide');
                    }
                    try {
                        $$('.price-box.amhide').each(function (element) {
                            element.up('div.amgroupcat').next('p').down('button.button.btn-cart').update('$replace').addClassName('amgroupcat');
                        });
                    } catch(err) {
                        console.log('Groupcat: cannot find button to hide');
                    }
                 });
            </script>
        ";

        $output .= $script;

        return $output;
    }

    /*
     * Adds JS to replace item 'actions' block with specified previously group for hiding
     */

    private function addCommonsHideId($output, $id)
    {
        $script = "
            <script type='text/javascript'>
                Event.observe(window, 'load', function() {
                    try {
                        $('product-price-$id').up('div.amgroupcat').next('div.actions').hide();
                    } catch(err) {
                        console.log('Groupcat: cannot find element to hide');
                    }
                    try {
                        $('product-price-$id').up('div.amgroupcat').next('p').down('button.button.btn-cart').hide();
                    } catch(err) {
                        console.log('Groupcat: cannot find button to hide');
                    }
                 });
            </script>
        ";

        $output .= $script;

        return $output;
    }

}