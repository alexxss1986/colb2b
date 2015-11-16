<?php

/*
 * Copyright (c) 2014 Sift Science
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 *
 * Extending Model_Abstract is not necessary, but helpful for testing.
 */

class SiftScience_Core_Model_Observer extends Mage_Core_Model_Abstract
{
  public function front_after_hook($observer)
  {
    $dispatcher = Mage::getSingleton('siftscience_core/dispatcher');
    $dispatcher->fireAll();
    return $observer;
  }

   /**
   * Triggers the SiftScience Event_CreateOrder model to fire.
   * @param  array $observer array( 'order'=>$order, 'quote'=>$quote )
   * @return array $observer
   */
  public function order_success_hook($observer)
  {
    $order = $observer->getEvent()->getOrder();

    $user_id = Mage::getSingleton('siftscience_core/session')->getUserId();
    if ( empty($user_id) ) {
      $user_id = 'guest_' . Mage::getSingleton('siftscience_core/session')->getSessionId();
      Mage::getSingleton('siftscience_core/session')->setUserId($user_id);
    }

    $order->setSiftscience_userid($user_id);

    // Fire event after updating user id
    //Mage::log( '[SIFT] order_success_hook firing');
    Mage::getSingleton('siftscience_core/event_createOrder')->dispatch();
    Mage::getSingleton('siftscience_core/event_transaction')->dispatch();

    if ( Mage::getStoreConfig('siftscience_options/general/comment_creation') != false )
    {
      $score = Mage::helper('siftscience_core')->getScore($user_id);
      $score = $score !== NULL ? $score : 'N/A';
      $threshold = 70; // TODO: Refactor into option
      $fraudAlert = ($score > $threshold) ? '(FRAUD RISK) ' : '';
      $fraudRiskComment =
        'SiftScience Score: ' . $fraudAlert . $score ."\n" .
        'More info: ' . Mage::helper('siftscience_core')->getScoreUrl($user_id);

      $order->addStatusHistoryComment($fraudRiskComment);
    }

    $order->save();

    return $observer;
  }

  public function add_product_hook($observer) {
    $atc = Mage::getSingleton('siftscience_core/event_addItemToCart');
    $atc->setProduct($observer->getEvent()->getProduct());
    $atc->dispatch();
  }
}
