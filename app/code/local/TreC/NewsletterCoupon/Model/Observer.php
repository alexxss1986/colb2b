<?php
class TreC_NewsletterCoupon_Model_Observer
{
    public function createCoupon($observer){

        $helper = Mage::helper('newslettercoupon');
        $subscriber = $observer->getEvent()->getSubscriber();
        $email = $subscriber->getEmail();

        $promo_value = 10;

        $time = strtotime(date("Y-m-d"));
        $expires = date("Y-m-d", strtotime("+1 month", $time));


        $helper->addPromoCode($email, $promo_value,$expires);

        return $this;

    }



}

