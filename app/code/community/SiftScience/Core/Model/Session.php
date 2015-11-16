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
 * Non-persistent model to temporarily store data that will be sent to
 * SiftScience.
 */

class SiftScience_Core_Model_Session extends Mage_Core_Model_Abstract
{
  /**
   * Called before the layout is rendred. Gathers session information.
   * For testing, considered a controller.
   *
   * @param  Object $observer
   * @return Object $observer
   */
  public function front_init_hook($observer)
  {
    // Get the real session, set it to this model
    $siftSession = Mage::getSingleton('core/session', array('name' => 'siftscience'));
    $sessionId = $siftSession->getSiftSession();

    // TODO: This is testable. Refactor
    if ( empty($sessionId) ) {
      // getSessionId will generate a unique ID if session is truely empty
      $siftSession->setSiftSession( $this->getSessionId() );
    } else {
      $this->setSessionId( $sessionId );
    }

    $email = null;
    // $user_id = null;
    if ( Mage::getSingleton('customer/session')->isLoggedIn() ) {
      $email = Mage::getSingleton('customer/session')->getCustomer()->getEmail();
      // $user_id = Mage::getSingleton('customer/session')->getCustomer()->getId();
    }
    $this->setEmail( $email );
    $this->setUserId( $email );
    return $observer;
  }

  /**
   * Returns stored session, or generates a new unique id, saving and
   * returning it.
   * @return string Session ID
   */
  public function getSessionId()
  {
    $session_id = $this->getData( 'session_id' );

    if ( $session_id === null ) {
      $this->setData( 'session_id', uniqid() );
      $session_id = $this->getData( 'session_id' );
    }

    return $session_id;
  }

  /**
   * Debugging use only
   * @param  [type] $observer [description]
   * @return [type]           [description]
   */
  public function front_after_hook($observer)
  {
    // Mage::log( '[SIFT_DUMP] Post layout load.');
    // Mage::log( var_export($this, TRUE) );
  }
}