<?php
/**
 * Magento
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
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

if (version_compare(phpversion(), '5.3.0', '<')===true) {
    echo  '<div style="font:12px/1.35em arial, helvetica, sans-serif;">
<div style="margin:0 0 25px 0; border-bottom:1px solid #ccc;">
<h3 style="margin:0; font-size:1.7em; font-weight:normal; text-transform:none; text-align:left; color:#2f2f2f;">
Whoops, it looks like you have an invalid PHP version.</h3></div><p>Magento supports PHP 5.3.0 or newer.
<a href="http://www.magentocommerce.com/install" target="">Find out</a> how to install</a>
 Magento using PHP-CGI as a work-around.</p></div>';
    exit;
}

/**
 * Error reporting
 */
error_reporting(E_ALL | E_STRICT);

/**
 * Compilation includes configuration file
 */
define('MAGENTO_ROOT', getcwd());

$compilerConfig = MAGENTO_ROOT . '/includes/config.php';
if (file_exists($compilerConfig)) {
    include $compilerConfig;
}

$mageFilename = MAGENTO_ROOT . '/app/Mage.php';
$maintenanceFile = 'maintenance.flag';

if (!file_exists($mageFilename)) {
    if (is_dir('downloader')) {
        header("Location: downloader");
    } else {
        echo $mageFilename." was not found";
    }
    exit;
}

if (file_exists($maintenanceFile)) {
    include_once dirname(__FILE__) . '/errors/503.php';
    exit;
}

require_once $mageFilename;

#Varien_Profiler::enable();

if (isset($_SERVER['MAGE_IS_DEVELOPER_MODE'])) {
    Mage::setIsDeveloperMode(true);
}

#ini_set('display_errors', 1);

umask(0);

/* Store or website code */
$mageRunCode = isset($_SERVER['MAGE_RUN_CODE']) ? $_SERVER['MAGE_RUN_CODE'] : '';

/* Run store or run website */
$mageRunType = isset($_SERVER['MAGE_RUN_TYPE']) ? $_SERVER['MAGE_RUN_TYPE'] : 'store';

//Mage::run($mageRunCode, $mageRunType);

$ip=getenv('HTTP_X_FORWARDED_FOR');
$array_ip = explode(",", $ip);
$country_code = getCountry($array_ip[0]);

$protocol = isSecure() ? 'https://' : 'http://';

if ($country_code=="IT"){
    if ($_SERVER['REQUEST_URI'] === '/') {
        header('Location: ' . $protocol . $_SERVER['HTTP_HOST'] . '/it');
        exit;
    }

}
else if ($country_code!="IT"){
    if ($_SERVER['REQUEST_URI'] === '/') {
        header('Location: ' . $protocol . $_SERVER['HTTP_HOST'] . '/en');
        exit;
    }
}
else {
    /* Auto redirect to language store view if request is for root */
    if ($_SERVER['REQUEST_URI'] === '/') {
        header('Location: '.getStoreForLanguage()->getBaseUrl());
        exit;
    }
}

//Mage::run($mageRunCode);

umask(0);
Mage::run();

function getStoreForLanguage()
{
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {

        foreach (explode(",", strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE'])) as $accept) {
            if (preg_match("!([a-z-]+)(;q=([0-9.]+))?!", trim($accept), $found)) {
                $langs[] = $found[1];
                $quality[] = (isset($found[3]) ? (float) $found[3] : 1.0);
            }
        }
        // Order the codes by quality
        array_multisort($quality, SORT_NUMERIC, SORT_DESC, $langs);
        // get list of stores and use the store code for the key
        $stores = Mage::app()->getStores(false, true);
        // iterate through languages found in the accept-language header
        foreach ($langs as $lang) {
            $lang = substr($lang,0,2);
            if (isset($stores[$lang]) && $stores[$lang]->getIsActive()) return $stores[$lang];
        }
    }
    return Mage::app()->getStore();
}


function getCountry($ipAddress)
{

    // get the country of the IP from the MAXMIND
    $country="";

    include_once("geoip.inc.php");
    // read GeoIP database
    $handle = geoip_open("GeoIP.dat", GEOIP_STANDARD);


    // map IP to country
    $country = geoip_country_code_by_addr($handle, $ipAddress);

    // close database handler
    geoip_close($handle);

    if ($country==""|| empty($country))
    {

        $country="Unknown";
    }

    return $country;

}

function isSecure() {
  return
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || $_SERVER['SERVER_PORT'] == 443;
}

function url_slug($str, $options = array()) {
    // Make sure string is in UTF-8 and strip invalid UTF-8 characters
    $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());

    $defaults = array(
        'delimiter' => '-',
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(),
        'transliterate' => false,
    );

    // Merge options
    $options = array_merge($defaults, $options);

    $char_map = array(
        // Latin
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
        'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
        'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'O' => 'O',
        'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'U' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
        'ß' => 'ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
        'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'o' => 'o',
        'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'u' => 'u', 'ý' => 'y', 'þ' => 'th',
        'ÿ' => 'y',

        // Latin symbols
        '©' => '(c)',

        // Greek
        '?' => 'A', '?' => 'B', 'G' => 'G', '?' => 'D', '?' => 'E', '?' => 'Z', '?' => 'H', 'T' => '8',
        '?' => 'I', '?' => 'K', '?' => 'L', '?' => 'M', '?' => 'N', '?' => '3', '?' => 'O', '?' => 'P',
        '?' => 'R', 'S' => 'S', '?' => 'T', '?' => 'Y', 'F' => 'F', '?' => 'X', '?' => 'PS', 'O' => 'W',
        '?' => 'A', '?' => 'E', '?' => 'I', '?' => 'O', '?' => 'Y', '?' => 'H', '?' => 'W', '?' => 'I',
        '?' => 'Y',
        'a' => 'a', 'ß' => 'b', '?' => 'g', 'd' => 'd', 'e' => 'e', '?' => 'z', '?' => 'h', '?' => '8',
        '?' => 'i', '?' => 'k', '?' => 'l', 'µ' => 'm', '?' => 'n', '?' => '3', '?' => 'o', 'p' => 'p',
        '?' => 'r', 's' => 's', 't' => 't', '?' => 'y', 'f' => 'f', '?' => 'x', '?' => 'ps', '?' => 'w',
        '?' => 'a', '?' => 'e', '?' => 'i', '?' => 'o', '?' => 'y', '?' => 'h', '?' => 'w', '?' => 's',
        '?' => 'i', '?' => 'y', '?' => 'y', '?' => 'i',

        // Turkish
        'S' => 'S', 'I' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'G' => 'G',
        's' => 's', 'i' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'g' => 'g',

        // Russian
        '?' => 'A', '?' => 'B', '?' => 'V', '?' => 'G', '?' => 'D', '?' => 'E', '?' => 'Yo', '?' => 'Zh',
        '?' => 'Z', '?' => 'I', '?' => 'J', '?' => 'K', '?' => 'L', '?' => 'M', '?' => 'N', '?' => 'O',
        '?' => 'P', '?' => 'R', '?' => 'S', '?' => 'T', '?' => 'U', '?' => 'F', '?' => 'H', '?' => 'C',
        '?' => 'Ch', '?' => 'Sh', '?' => 'Sh', '?' => '', '?' => 'Y', '?' => '', '?' => 'E', '?' => 'Yu',
        '?' => 'Ya',
        '?' => 'a', '?' => 'b', '?' => 'v', '?' => 'g', '?' => 'd', '?' => 'e', '?' => 'yo', '?' => 'zh',
        '?' => 'z', '?' => 'i', '?' => 'j', '?' => 'k', '?' => 'l', '?' => 'm', '?' => 'n', '?' => 'o',
        '?' => 'p', '?' => 'r', '?' => 's', '?' => 't', '?' => 'u', '?' => 'f', '?' => 'h', '?' => 'c',
        '?' => 'ch', '?' => 'sh', '?' => 'sh', '?' => '', '?' => 'y', '?' => '', '?' => 'e', '?' => 'yu',
        '?' => 'ya',

        // Ukrainian
        '?' => 'Ye', '?' => 'I', '?' => 'Yi', '?' => 'G',
        '?' => 'ye', '?' => 'i', '?' => 'yi', '?' => 'g',

        // Czech
        'C' => 'C', 'D' => 'D', 'E' => 'E', 'N' => 'N', 'R' => 'R', 'Š' => 'S', 'T' => 'T', 'U' => 'U',
        'Ž' => 'Z',
        'c' => 'c', 'd' => 'd', 'e' => 'e', 'n' => 'n', 'r' => 'r', 'š' => 's', 't' => 't', 'u' => 'u',
        'ž' => 'z',

        // Polish
        'A' => 'A', 'C' => 'C', 'E' => 'e', 'L' => 'L', 'N' => 'N', 'Ó' => 'o', 'S' => 'S', 'Z' => 'Z',
        'Z' => 'Z',
        'a' => 'a', 'c' => 'c', 'e' => 'e', 'l' => 'l', 'n' => 'n', 'ó' => 'o', 's' => 's', 'z' => 'z',
        'z' => 'z',

        // Latvian
        'A' => 'A', 'C' => 'C', 'E' => 'E', 'G' => 'G', 'I' => 'i', 'K' => 'k', 'L' => 'L', 'N' => 'N',
        'Š' => 'S', 'U' => 'u', 'Ž' => 'Z',
        'a' => 'a', 'c' => 'c', 'e' => 'e', 'g' => 'g', 'i' => 'i', 'k' => 'k', 'l' => 'l', 'n' => 'n',
        'š' => 's', 'u' => 'u', 'ž' => 'z'
    );

    // Make custom replacements
    $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);

    // Transliterate characters to ASCII
    if ($options['transliterate']) {
        $str = str_replace(array_keys($char_map), $char_map, $str);
    }

    // Replace non-alphanumeric characters with our delimiter
    $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

    // Remove duplicate delimiters
    $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

    // Truncate slug to max. characters
    $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

    // Remove delimiter from ends
    $str = trim($str, $options['delimiter']);

    return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}