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

/**
 * Simple model to generate a unique id for SiftScience named session scope.
 *
 * TODO: DEPRECATED. Refactoring controller-specific logic
 * This needs to be moved to a per-request observer, check for
 * ('siftscience_core/session')->getSessionId(), and set if empty.
 * Allows for better test coverage.
 */
class SiftScience_Core_Model_SessionId extends Varien_Object
{
  public function getSessionId() {
    /*
      We need to maintain a session ID for guests that is
      persisted when a guest decides to log in before checking out.
      SiftScience will properly track a user when their _userId changes
      from guest (something like 'guest_SID54321') to a real ID
      ('danschuman@gmail.com'), so long as the sessionId is the same
      throughout navigation.

      Magento's core/session with a named scope seems to work properly--it
      persists after guest->user login transition, and is destroyed when
      a user logs out.
    */

    $siftSession = Mage::getSingleton('core/session', array('name' => 'siftscience'));

    $sessionId = $siftSession->getSiftSession();

    if( empty($sessionId) ) {
      $sessionId = uniqid();
      $siftSession->setSiftSession( $sessionId );
    }

    return $sessionId;
  }
}