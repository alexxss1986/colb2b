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
 */

class SiftScience_Core_Model_Event_CreateOrder extends SiftScience_Core_Model_Event_Abstract
{
    protected $_eventType = '$create_order';

    public function getEventType()
    {
      return $this->_eventType;
    }

    public function getEventData()
    {
      $siftOrder = Mage::getSingleton('siftscience_core/order');
      $siftSession = Mage::getSingleton('siftscience_core/session');
      $siftPayment = Mage::getSingleton('siftscience_core/payment');

      return $this->setUpData($siftOrder, $siftSession, $siftPayment);
    }

    protected function setUpData($siftOrder, $siftSession, $siftPayment)
    {
      $data = array();
      $data['$type']          = $this->_eventType;
      $data['$api_key']       = $this->_apiKey;

      if( $siftSession->getUserId() ) {
        $data['$user_id']     = $siftSession->getUserId();
      }

      $data['$order_id']      = $siftOrder->getOrderId();

      if( $siftOrder->getUserEmail() ) {
        $data['$user_email']  = $siftOrder->getUserEmail();
      }

      $data['$amount']        = $siftOrder->getGrandTotalInMicros();
      $data['$currency_code'] = $siftOrder->getCurrencyCode();

      if ( $siftSession->getSessionId() ) {
        $data['$session_id']  = $siftSession->getSessionId();
      }

      $data['$billing_address'] = $this->_parseAddress($siftOrder->getBillingAddress());

      $shipping_address = $siftOrder->getShippingAddress();
      if( !empty( $shipping_address ) ) {
        $data['$shipping_address'] = $this->_parseAddress( $shipping_address );
      }

      $data['$items'] = array();

      foreach ($siftOrder->getItems() as $item) {
        $data['$items'][] = $this->_parseItem( $item, $siftOrder->getCurrencyCode() );
      }

      // TODO: Add more payment information
      $paymentMethod = $siftPayment->getEventData();
      if (!empty($paymentMethod)) {
        $data['$payment_methods'] = array();
        $data['$payment_methods'][] = $paymentMethod;
      }

      return $data;
    }

    private function _parseItem($item, $currencyCode)
    {
      $siftItem = array();

      $siftItem['$item_id'] = $item->getId();
      $siftItem['$product_title'] = $item->getName();
      $siftItem['$price'] = $item->getPrice() * 100 * 10000;
      $siftItem['$currency_code'] = $currencyCode;

      return $siftItem;
    }
}
