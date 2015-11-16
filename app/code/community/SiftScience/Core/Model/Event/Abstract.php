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

abstract class SiftScience_Core_Model_Event_Abstract extends Mage_Core_Model_Abstract
{
  abstract protected function getEventType();
  abstract protected function getEventData();

  protected $_dispatched = false;

  protected $_apiKey;

  public function _construct()
  {
    parent::_construct();
    $this->_apiKey = Mage::getStoreConfig('siftscience_options/general/rest_api_key');
  }
  public function dispatch()
  {
    $dispatcher = Mage::getSingleton('siftscience_core/dispatcher');
    $this->_dispatched = $dispatcher->dispatch($this);
  }

  public function isDispatched()
  {
    return $this->_dispatched ? true : false;
  }

  protected function baseData($siftSession)
  {
    $data = array();
    $data['$api_key'] = $this->_apiKey;
    $data['$type'] = $this->getEventType();
    if ( $siftSession->getUserId() ) {
      $data['$user_id']     = $siftSession->getUserId();
    }
    if ( $siftSession->getSessionId() ) {
      $data['$session_id']  = $siftSession->getSessionId();
    }
    return $data;
  }

  protected function _parseAddress($address)
  {
    $siftAddress = array();

    $siftAddress['$name'] = $address->getFirstname() . ' ' . $address->getLastname();
    $siftAddress['$phone'] = $address->getTelephone();

    $streets = $address->getStreet();
    $siftAddress['$address_1'] = $streets[0];
    if ( sizeof( $streets ) > 1 ) {
      $siftAddress['$address_2'] = $streets[1];
    }

    $siftAddress['$city'] = $address->getCity();
    $siftAddress['$region'] = $address->getRegion();
    $siftAddress['$country'] = $address->getCountry();
    $siftAddress['$zipcode'] = $address->getPostcode();

    return $siftAddress;
  }
}
