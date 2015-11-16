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

class SiftScience_Core_Model_Event_Transaction extends SiftScience_Core_Model_Event_Abstract
{

  protected $_eventType = '$transaction';

  public function getEventType() {
    return $this->_eventType;
  }

  public function getEventData() {
    $siftOrder = Mage::getSingleton('siftscience_core/order');
    $siftPayment = Mage::getSingleton('siftscience_core/payment');
    $siftSession = Mage::getSingleton('siftscience_core/session');

    $data = $this->baseData($siftSession);

    $data['$transaction_type'] = '$sale';
    $data['$transaction_status'] = '$success';
    $data['$amount'] = $siftOrder->getGrandTotalInMicros();
    $data['$currency_code'] = $siftOrder->getCurrencyCode();

    $data['$billing_address'] = $this->_parseAddress($siftOrder->getBillingAddress());
    $shippingAddress = $siftOrder->getShippingAddress();
    if (!empty($shippingAddress)) {
      $data['$shipping_address'] = $this->_parseAddress($shippingAddress);
    }

    $data['$order_id'] = $siftOrder->getOrderId();
    $data['$payment_method'] = $siftPayment->getEventData();

    return $data;
  }
}
