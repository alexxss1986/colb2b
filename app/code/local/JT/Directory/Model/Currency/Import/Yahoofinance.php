<?php
/**
 * JT
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   JT
 * @package    JT_Directory
 * @copyright  Copyright (c) 2009 JT
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * All credits to Magento Core Development Team who's Webservicex code I liberally borrowed
 * as well as Mark Alexander Bain's idea and code as displayed on the following url, accessed Feb 16th 2009
 * http://ajax-programming.suite101.com/article.cfm/currency_conversion_with_yahoo_finance
 */

/**
 * Currency rate import model (From quote.yahoo.com)
 *
 * @category   JT
 * @package    JT_Directory
 * @author      Magento Enthusiast <J.T.>
 */
class JT_Directory_Model_Currency_Import_Yahoofinance extends Mage_Directory_Model_Currency_Import_Abstract
{
    protected $_url = 'http://quote.yahoo.com/d/quotes.csv?s={{CURRENCY_FROM}}{{CURRENCY_TO}}=X&f=l1&e=.csv';
    protected $_messages = array();

    protected function _convert($currencyFrom, $currencyTo, $retry=0)
    {

        $url = str_replace('{{CURRENCY_FROM}}', $currencyFrom, $this->_url);
        $url = str_replace('{{CURRENCY_TO}}', $currencyTo, $url);

        try {
            sleep(1); //Be nice to Yahoo, they don't have a lot of hi-spec servers

            $handle = fopen($url, "r");

            $exchange_rate = fread($handle, 2000);

            fclose($handle);

            if( !$exchange_rate ) {
                $this->_messages[] = Mage::helper('directory')->__('Cannot retrieve rate from %s', $url);
                return null;
            }
            return (float) $exchange_rate * 1.0; // change 1.0 to influence rate;
        }
        catch (Exception $e) {
            if( $retry == 0 ) {
                $this->_convert($currencyFrom, $currencyTo, 1);
            } else {
                $this->_messages[] = Mage::helper('directory')->__('Cannot retrieve rate from %s', $url);
            }
        }
    }
}