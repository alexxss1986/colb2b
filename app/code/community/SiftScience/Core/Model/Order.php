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
 * Non-persistent Order model for gathering observer data to be sent
 * to SiftScience API per-request.
 */

class SiftScience_Core_Model_Order extends Mage_Core_Model_Abstract
{
  /**
   * Event: sales_model_service_quote_submit_before
   *
   * This hook is called on the same request before a purchase is
   * attempted. Contains all information necessary for $create_order,
   * and most for $transaction.
   *
   * Since this is only and always called when a transaction is attempting,
   * this should dispatch $transaction to be sent.
   *
   * @param  Object $observer Event object
   * @return Object $observer
   */
  public function order_before_hook($observer)
  {
    $order = $observer->getEvent()->getOrder();

    // TODO: Refactor all this into a testable.
    $orderId = $order->getQuote()->getReservedOrderId();
    // TODO: This calculation is good for USD/Euro, but is it universal?
    $grandTotalInMicros = $order->getGrandTotal() * 100 * 10000;
    $currencyCode = $order->getQuote()->getQuoteCurrencyCode();
    $userEmail = $order->getCustomerEmail();

    $billingAddress = $observer->getEvent()->getOrder()->getBillingAddress();
    $shippingAddress = $observer->getEvent()->getOrder()->getShippingAddress();

    $items = $order->getAllVisibleItems();

    // /TODO: Refactor

    $this->setGrandTotalInMicros( $grandTotalInMicros );
    $this->setCurrencyCode( $currencyCode );
    $this->setOrderId( $orderId );
    $this->setUserEmail( $userEmail );
    $this->setBillingAddress( $billingAddress );
    $this->setItems( $items );

    if (!empty($shippingAddress)) {
      $this->setShippingAddress( $shippingAddress );
    }

    return $observer;
  }
}
