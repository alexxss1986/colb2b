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

class SiftScience_Core_Helper_Data extends Mage_Core_Helper_Abstract
{
  public function userId()
  {
    return Mage::getSingleton('siftscience_core/session')->getEmail();
  }

  public function sessionId()
  {
    return Mage::getSingleton('siftscience_core/session')->getSessionId();
  }

  public function getScore($sift_userId)
  {
    $sift_apiKey = Mage::getStoreConfig('siftscience_options/general/rest_api_key');

    $ctx = stream_context_create(
      array('http'=>
          array(
              'timeout' => 2 // 2 seconds
          )
      )
    );
    $siftJson = file_get_contents("https://api.siftscience.com/v203/score/$sift_userId/?api_key=$sift_apiKey");
    $siftData = json_decode($siftJson, true);

    $score = null;
    if( array_key_exists('score', $siftData) ) {
      $score = round( $siftData['score'] * 100, 1 );
    }

    return $score;
  }

  public function getScoreUrl($sift_userId)
  {
    return 'https://siftscience.com/console/users/' . urlencode($sift_userId);
  }
}
