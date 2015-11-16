<?php
class TreC_PopupWindow_Block_Popup extends Mage_Core_Block_Template
{
    public function getResult()
    {

        $ip = getenv('HTTP_X_FORWARDED_FOR');
        $array_ip = explode(",", $ip);
        $country_code = getCountry($array_ip[0]);
        $storeCode = Mage::app()->getStore()->getCode();


        if (strtolower($country_code) != strtolower($storeCode)) {
            return $country_code;
        } else {
            return false;
        }
    }


    public function getStoreForLanguage()
    {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {

            foreach (explode(",", strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE'])) as $accept) {
                if (preg_match("!([a-z-]+)(;q=([0-9.]+))?!", trim($accept), $found)) {
                    $langs[] = $found[1];
                    $quality[] = (isset($found[3]) ? (float)$found[3] : 1.0);
                }
            }
            // Order the codes by quality
            array_multisort($quality, SORT_NUMERIC, SORT_DESC, $langs);
            // get list of stores and use the store code for the key
            $stores = Mage::app()->getStores(false, true);
            // iterate through languages found in the accept-language header
            foreach ($langs as $lang) {
                $lang = substr($lang, 0, 2);
                if (isset($stores[$lang]) && $stores[$lang]->getIsActive()) return $stores[$lang];
            }
        }
        return Mage::app()->getStore();
    }


    public function getCountry($ipAddress)
    {

        // get the country of the IP from the MAXMIND
        $country = "";

        include_once("geoip.inc.php");
        // read GeoIP database
        $handle = geoip_open("GeoIP.dat", GEOIP_STANDARD);


        // map IP to country
        $country = geoip_country_code_by_addr($handle, $ipAddress);

        // close database handler
        geoip_close($handle);

        if ($country == "" || empty($country)) {

            $country = "Unknown";
        }

        return $country;

    }
}