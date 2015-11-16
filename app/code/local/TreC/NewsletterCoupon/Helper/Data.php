<?php
class TreC_NewsletterCoupon_Helper_Data extends Mage_Core_Helper_Abstract {

    private function generatePromoCode($length = null) {
        $rndId = crypt(uniqid(rand(),1));
        $rndId = strip_tags(stripslashes($rndId));
        $rndId = str_replace(array(".", "$"),"",$rndId);
        $rndId = strrev(str_replace("/","",$rndId));
        if (!is_null($rndId)){
            return strtoupper(substr($rndId, 0, $length));
        }
        return strtoupper($rndId);
    }

    public function addPromoCode($email, $value,$expires) {

        $promo_name = 'Coupon di sconto per '.$email;

        $redeemed = false;
        $collection = Mage::getModel('salesrule/rule')->getResourceCollection();
        foreach ($collection as $rule) {
            if ($rule->getName() == $promo_name) {
                $redeemed = true;
            }
        }

        if ($redeemed) {

        }
        else {


            /*$actions = array(
                "1" => array(
                    'type' => 'salesrule/rule_condition_combine',
                    'aggregator' => 'all',
                    'value' => 1,
                    'new_child' => false
                ),

                "1--1" => array(
                    'type' => 'salesrule/rule_condition_combine',
                    'aggregator' => 'any',
                    'value' => 1,
                    'new_child' => false
                ),

                "1--1--1" => array(
                    'type' => 'salesrule/rule_condition_product',
                    'attribute' => 'category_ids',
                    'operator' => '!=',
                    'value' => 57
                ),

                "1--1--2" => array(
                    'type' => 'salesrule/rule_condition_product',
                    'attribute' => 'ca_brand',
                    'operator' => '!=',
                    'value' => 1095
                ),

                "1--2" => array(
                    'type' => 'salesrule/rule_condition_combine',
                    'aggregator' => 'any',
                    'value' => 1,
                    'new_child' => false
                ),

                "1--2--1" => array(
                    'type' => 'salesrule/rule_condition_product',
                    'attribute' => 'category_ids',
                    'operator' => '!=',
                    'value' => 82
                ),

                "1--2--2" => array(
                    'type' => 'salesrule/rule_condition_product',
                    'attribute' => 'ca_brand',
                    'operator' => '!=',
                    'value' => 1095
                ),

                "1--3" => array(
                    'type' => 'salesrule/rule_condition_combine',
                    'aggregator' => 'any',
                    'value' => 1,
                    'new_child' => false
                ),

                "1--3--1" => array(
                    'type' => 'salesrule/rule_condition_product',
                    'attribute' => 'category_ids',
                    'operator' => '!=',
                    'value' => 57
                ),

                "1--3--2" => array(
                    'type' => 'salesrule/rule_condition_product',
                    'attribute' => 'ca_brand',
                    'operator' => '!=',
                    'value' => 1038
                ),

                "1--4" => array(
                    'type' => 'salesrule/rule_condition_combine',
                    'aggregator' => 'any',
                    'value' => 1,
                    'new_child' => false
                ),

                "1--4--1" => array(
                    'type' => 'salesrule/rule_condition_product',
                    'attribute' => 'category_ids',
                    'operator' => '!=',
                    'value' => 82
                ),

                "1--4--2" => array(
                    'type' => 'salesrule/rule_condition_product',
                    'attribute' => 'ca_brand',
                    'operator' => '!=',
                    'value' => 1038
                ),

            );*/

            $uniqueId = $this->generatePromoCode(6);

            $customerGroups = Mage::getModel('customer/group')->getCollection();
            $groups = array();
            foreach ($customerGroups as $group){
                $groups[] = $group->getId();
            }

            $rule = Mage::getModel('salesrule/rule');
            $rule->setName($promo_name);
            $rule->setDescription('Coupon di sconto per '.$email);
            $rule->setFromDate(date('Y-m-d'));
            $rule->setToDate($expires);
            $rule->setCouponCode($uniqueId);
            $rule->setUsesPerCoupon(1);
            $rule->setUsesPerCustomer(1);
            $rule->setCustomerGroupIds($groups);
            $rule->setIsActive(1);
            $rule->setStopRulesProcessing(1);
            $rule->setIsRss(0);
            $rule->setIsAdvanced(1);
            $rule->setProductIds('');
            $rule->setSortOrder(0);
            $rule->setSimpleAction('cart_fixed');
            $rule->setDiscountAmount($value);
            $rule->setDiscountQty(0);
            $rule->setDiscountStep(0);
            $rule->setSimpleFreeShipping(0);
            $rule->setApplyToShipping(0);
            $rule->setWebsiteIds(array(1,2,3));
            $rule->setSimpleAction('by_percent');


            $rule->loadPost($rule->getData());
            $rule->setCouponType(2);

            $labels = array();
            $labels[2] = 'Newsletter';
            $labels[1] = 'Newsletter';

            $rule->setStoreLabels($labels);

            $rule->save();

            $rule2 = Mage::getModel('salesrule/rule')->load($rule->getId());
            //$rule2->setData("actions",$actions);
            $rule2->loadPost($rule2->getData());
            $rule2->save();

            return $uniqueId;
        }
    }


}