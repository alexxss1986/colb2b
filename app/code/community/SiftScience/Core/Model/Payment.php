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
 * Non-persistent Payment model for gathering observer data to be sent
 * to SiftScience API per-request.
 */

class SiftScience_Core_Model_Payment extends Mage_Core_Model_Abstract
{
  /**
   * TODO: This method is very incomplete. Needs alternate payment methods.
   *
   * @param  [type] $observer [description]
   * @return [type]           [description]
   */
  public function payment_save_after_hook($observer) {
    // See code/core/Mage/Sales/Model/Order/Payment.php for this object's methods.
    $paymentObject = $observer->getEvent()->getDataObject();

    // TODO: This logic is weak; if cc_exp_month isn't set, then it's
    // Check/Money Order, or something else.
    if ($paymentObject->getCcExpMonth() == 0) {
      $this->setPaymentType( '$third_party_processor' );
    }

    $cardLast4 = $paymentObject->getCcLast4();
    if(!empty($cardLast4)) {
      $this->setCardLast4( $cardLast4 );
      $this->setPaymentType( '$credit_card' );
      $cardAvs = $paymentObject->getCcAvsStatus();
      if (!empty($cardAvs)) {
        $this->setCardAvs($cardAvs);
      }
    }

    return $observer;
  }

  public function getEventData() {
    $data = array();
    $paymentType = $this->getPaymentType();
    $cardLast4 = $this->getCardLast4();
    $cardAvs = $this->getCardAvs();

    if (!empty($paymentType)) {
      $data['$payment_type'] = $paymentType;
    }
    if (!empty($cardLast4)) {
      $data['$card_last4'] = $cardLast4;
    }
    if (!empty($cardAvs)) {
      $data['$avs_result_code'] = $cardAvs;
    }
    return $data;
  }
}
