<?php

class TreC_NoIndex_Model_Observer extends Mage_Core_Model_Abstract
{
    /* Our function to change the META robots tag on Parameter based category pages */

    public function changeRobots($observer)
    {

        if ($observer->getEvent()->getAction()->getFullActionName() == 'catalog_category_view') {
            $uri = $observer->getEvent()->getAction()->getRequest()->getRequestUri();
            if (stristr($uri, "?")):
                $layout = $observer->getEvent()->getLayout();
                $product_info = $layout->getBlock('head');
                $layout->getUpdate()->addUpdate('<reference name="head"><action method="setRobots"><value>NOINDEX,FOLLOW</value></action></reference>');
                $layout->generateXml();
            endif;
        }

        if ($observer->getEvent()->getAction()->getFullActionName() == 'cms_page_view') {
            $uri = $observer->getEvent()->getAction()->getRequest()->getRequestUri();
            if (stristr($uri, "?")):
                $layout = $observer->getEvent()->getLayout();
                $product_info = $layout->getBlock('head');
                $layout->getUpdate()->addUpdate('<reference name="head"><action method="setRobots"><value>NOINDEX,FOLLOW</value></action></reference>');
                $layout->generateXml();
            endif;
        }
    }
}