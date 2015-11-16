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

class SiftScience_Core_Model_Event_AddItemToCart extends SiftScience_Core_Model_Event_Abstract
{
  protected $_eventType = '$add_item_to_cart';

  public function getEventType() {
    return $this->_eventType;
  }

  public function getEventData() {
    $siftSession = Mage::getSingleton('siftscience_core/session');
    $data = $this->baseData($siftSession);
    $p = $this->getProduct();

    $item = $this->_buildItem($p);
    $data['$item'] = $item;
    return $data;
  }

  private function _buildItem($p) {
    $item = array();
    $this->_addIfPresent($item, '$item_id', $p->getId());
    $this->_addIfPresent($item, '$product_title', $p->getName());
    $this->_addIfPresent($item, '$sku', $p->getSku());
    $this->_addIfPresent($item, '$manufacturer', $p->getAttributeText('manufacturer'));
    $this->_addIfPresent($item, '$color', $p->getAttributeText('color'));
    $this->_addIfPresent($item, '$quantity', $p->getQty());

    $price = $p->getPrice();
    if (!empty($price)) {
      $item['$price'] = $price * 100 * 10000;
      $item['$currency_code'] = Mage::app()->getStore()->getCurrentCurrencyCode();
    }
    return $item;
  }

  private function _addIfPresent(&$arr, $key, $val) {
    if (!empty($val)) {
      $arr[$key] = $val;
    }
  }
}
