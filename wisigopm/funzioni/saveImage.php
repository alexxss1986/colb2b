<?php

function replace_accents($string)
{
    return str_replace( array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'), array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y'), $string);
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
        'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
        'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
        'ß' => 'ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
        'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
        'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
        'ÿ' => 'y',

        // Latin symbols
        '©' => '(c)',

        // Greek
        'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
        'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
        'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
        'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
        'Ϋ' => 'Y',
        'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
        'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
        'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
        'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
        'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',

        // Turkish
        'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
        'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',

        // Russian
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
        'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
        'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
        'Я' => 'Ya',
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
        'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
        'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
        'я' => 'ya',

        // Ukrainian
        'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
        'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',

        // Czech
        'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
        'Ž' => 'Z',
        'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
        'ž' => 'z',

        // Polish
        'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
        'Ż' => 'Z',
        'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
        'ż' => 'z',

        // Latvian
        'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
        'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
        'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
        'š' => 's', 'ū' => 'u', 'ž' => 'z'
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
// Download Image
function getDownloadImage($type,$file,$sottoCat,$nome_brand,$nome_colore,$id){

        $file_path = "";
        // estensione foto

        $ext = pathinfo($file, PATHINFO_EXTENSION);

        if (strtolower($ext)=="jpg") {

            // recupero il numero della foto
            $punto = strrpos($file, ".");
            $file_new = substr($file, 0, $punto);

            // il numero dell'immagine
            $numero_img = substr($file_new, strlen($file_new) - 1, 1);
            $nome_file = replace_accents(url_slug(strtolower($sottoCat))) . "_" . replace_accents(url_slug(strtolower($nome_brand))) . "_" . replace_accents(url_slug(strtolower($nome_colore))) . "_" . replace_accents(url_slug(strtolower($id))) . "-" . $numero_img . "." . $ext;

            $import_location = "../../var/images";

            $file_source = Mage::getStoreConfig('oscommerceimportconf/oscconfiguration/conf_imageurl', Mage::app()->getStore()) . $file;
            $file_target = $import_location . "/" . $nome_file;

            $file_source = str_replace(" ", "%20", $file_source);


            if (($file != '') and (!file_exists($file_target))) {
                $rh = fopen($file_source, 'rb');
                $wh = fopen($file_target, 'wb');
                if ($rh === false || $wh === false) {
                    // error reading or opening file
                    $file_path = "";
                } else {
                    while (!feof($rh)) {
                        if (fwrite($wh, fread($rh, 1024)) === FALSE) {
                            $file_path = $file_target;
                        }
                    }
                }
                fclose($rh);
                fclose($wh);
            }
            if (file_exists($file_target)) {
                if ($type == 'category') {
                    $file_path = $file;
                } else {
                    $file_path = $file_target;
                }
            }

            if (filesize($file_path)>0) {
                $img = new Imagick();
                $img->clear();
                $img->readImage($file_path);
                $img->setOption('jpeg:extent', '180kb');
                $img->writeImage($file_path);
            }
        }

        return $file_path;
    }


require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $writeConnection = $resource->getConnection('core_write');

        $username = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/username');
        $password = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/password');
        $service_url = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/endpoint');

        if (isset($username) && $username!="" && isset($password) && $password!="" && isset($service_url) && $service_url!="") {

            $filename = "immagini";
            $logFileName = $filename . '.log';

            $filename2 = "immaginiErrore";
            $logFileName2 = $filename2 . '.log';


            try {


                $service_urlPost = $service_url . "/user/token";
                $curlPost = curl_init($service_urlPost);

                $headersPost = array(
                    'Content-Type:application/json;charset=utf-8',
                    'Content-Length: 0', // aggiunto per evitare l'errore 413: Request Entity Too Large
                    'Authorization: Basic ' . base64_encode($username . ":" . $password)
                );


                curl_setopt($curlPost, CURLOPT_POST, true);  // indico che la richiesta è di tipo POST
                curl_setopt($curlPost, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                curl_setopt($curlPost, CURLOPT_SSL_VERIFYPEER, false); // disabilito la verifica del certificato SSL; è necessario perchè altrimenti non esegue la chiamata rest
                curl_setopt($curlPost, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curlPost, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curlPost, CURLOPT_HTTPHEADER, $headersPost);
                $curl_responsePost = curl_exec($curlPost);
                if ($curl_responsePost === false) {
                    $infoPost = curl_getinfo($curlPost);
                    curl_close($curlPost);
                } else {
                    curl_close($curlPost);
                    $decodedPost = json_decode($curl_responsePost);


                    if (is_object($decodedPost)) {
                        $arrayPost = get_object_vars($decodedPost);
                    }

                    $aToken = $arrayPost["access_token"];


                    $headersGet = array(
                        'Authorization: Bearer ' . $aToken
                    );


                    $service_urlGet = $service_url . "/products?images=true&attributes=true&id=152123ABS000016-B999";

                    $curlGet = curl_init($service_urlGet);
                    curl_setopt($curlGet, CURLOPT_HTTPAUTH, CURLAUTH_ANY); // accetto ogni autenticazione. Il sitema utilizzerà la migliore
                    curl_setopt($curlGet, CURLOPT_SSL_VERIFYPEER, false); // disabilito la verifica del certificato SSL; è necessario perchè altrimenti non esegue la chiamata rest
                    curl_setopt($curlGet, CURLOPT_RETURNTRANSFER, true); // salvo l'output della richiesta in una variabile
                    curl_setopt($curlGet, CURLOPT_HTTPHEADER, $headersGet); // setto l'header della richiesta
                    curl_setopt($curlGet, CURLOPT_SSL_VERIFYHOST, false);
                    $curl_responseGet = curl_exec($curlGet);

                    if ($curl_responseGet === false) {
                        $infoGet = curl_getinfo($curlGet);
                        curl_close($curlGet);


                    } else {

                        curl_close($curlGet);

                        // parse del contenuto
                        $decodedGet = json_decode($curl_responseGet);


                        $countP = 0;
                        $array_sku = array("");

                        if (is_object($decodedGet)) {
                            $decodedGet = get_object_vars($decodedGet);
                        }

                        $l = 1;
                        foreach ($decodedGet as $key => $value) {
                            $qtaTot = 0;
                            Mage::log($l, null, $logFileName);
                            $l = $l + 1;
                            $id = $key;
                            Mage::log($id, null, $logFileName);
                            $valoreProdotti = $value;

                            $variant = null;
                            $name = null;
                            $codice_colore_fornitore = null;
                            $alternative_ids = null;
                            $codice_produttore = null;
                            $product_id = null;
                            $descrizione = null;
                            $prezzo = null;
                            $id_brand = null;
                            $nome_brand = null;
                            $nome_anno = null;
                            $nome_stagione = null;
                            $id_season = null;
                            $id_categoria = null;
                            $nome_categoria = null;
                            $id_sottocategoria1 = null;
                            $nome_sottocategoria1 = null;
                            $id_sottocategoria2 = null;
                            $nome_sottocategoria2 = null;
                            $id_sottocategoria3 = null;
                            $nome_sottocategoria3 = null;
                            $attributi = null;
                            $immagini = null;
                            $varianti = null;

                            foreach ($valoreProdotti as $key => $value) {
                                // recupero campi prodotto
                                if ($key == "variant") {
                                    $variant = $value;
                                    if (is_object($variant)) {
                                        $variant = get_object_vars($variant);
                                    }
                                    foreach ($variant as $key => $value) {
                                        $codice_colore_fornitore = $value;
                                    }
                                }
                                if ($key == "alternative_ids") {
                                    $alternative_ids = $value;
                                    if (is_object($alternative_ids)) {
                                        $alternative_ids = get_object_vars($alternative_ids);
                                    }
                                }
                                if ($key == "product_id") {
                                    $product_id = $value;
                                }
                                if ($key == "description") {
                                    $descrizione = $value;
                                }
                                if ($key == "name") {
                                    $nome = $value;
                                }
                                if ($key == "price") {
                                    $prezzo = $value;
                                    $prezzo = ($prezzo * 100) / (22 + 100);  // scorporo dell'iva
                                }
                                if ($key == "brand") {
                                    $brand = $value;
                                    if (is_object($brand)) {
                                        $brand = get_object_vars($brand);
                                    }
                                    foreach ($brand as $key => $value) {
                                        $id_brand = $key;
                                        $nome_brand = $value;
                                    }
                                }
                                if ($key == "season") {
                                    $season = $value;
                                    if (is_object($season)) {
                                        $season = get_object_vars($season);
                                    }
                                    foreach ($season as $key => $value) {
                                        $id_season = $key;
                                        $nome_season = $value;
                                        $array_season = explode("/", $nome_season);
                                        $nome_anno = trim($array_season[0]);
                                        $nome_stagione = trim($array_season[1]);
                                    }

                                }
                                if ($key == "macro_category") {
                                    $categoria = $value;
                                    if (is_object($categoria)) {
                                        $categoria = get_object_vars($categoria);
                                    }
                                    foreach ($categoria as $key => $value) {
                                        $id_categoria = $key;
                                        $nome_categoria = $value;
                                    }
                                }
                                if ($key == "group") {
                                    $sottocategoria1 = $value;
                                    if (is_object($sottocategoria1)) {
                                        $sottocategoria1 = get_object_vars($sottocategoria1);
                                    }
                                    foreach ($sottocategoria1 as $key => $value) {
                                        $id_sottocategoria1 = $key;
                                        $nome_sottocategoria1 = $value;
                                    }
                                }
                                if ($key == "subgroup") {
                                    $sottocategoria2 = $value;
                                    if (is_object($sottocategoria2)) {
                                        $sottocategoria2 = get_object_vars($sottocategoria2);
                                    }
                                    foreach ($sottocategoria2 as $key => $value) {
                                        $id_sottocategoria2 = $key;
                                        $nome_sottocategoria2 = $value;
                                    }
                                }
                                if ($key == "category") {
                                    $sottocategoria3 = $value;
                                    if (is_object($sottocategoria3)) {
                                        $sottocategoria3 = get_object_vars($sottocategoria3);
                                    }
                                    foreach ($sottocategoria3 as $key => $value) {
                                        $id_sottocategoria3 = $key;
                                        $nome_sottocategoria3 = $value;
                                    }
                                }
                                if ($key == "images") {
                                    $immagini = $value;
                                    if (is_object($immagini)) {
                                        $immagini = get_object_vars($immagini);
                                    }
                                }

                                if ($key == "attributes") {
                                    $attributi = $value;
                                    if (is_object($attributi)) {
                                        $attributi = get_object_vars($attributi);
                                    }
                                }

                                if ($key == "scalars") {
                                    $varianti = $value;
                                    if (is_object($varianti)) {
                                        $varianti = get_object_vars($varianti);
                                    }
                                }


                            }
                            if (count($attributi) > 0 && count($immagini) > 0 && $id_brand != null && $id_season != null && $id_categoria != null && $id_sottocategoria1 != null && $id_sottocategoria2 != null && $prezzo != 0) {
                                // recupero immagini
                                $immagini_new = array();
                                for ($k = 0; $k < count($immagini); $k++) {
                                    $immagini_new[$k][0] = $immagini[$k];

                                    // recupero il numero della foto
                                    $punto = strrpos($immagini_new[$k][0], ".");
                                    $file_new = substr($immagini_new[$k][0], 0, $punto);

                                    // il numero dell'immagine
                                    $posizione = strrpos($file_new, "-"); // strrpos serve per trovare l'ultima occorrenza
                                    $numero_img = substr($file_new, strlen($file_new) - 1, 1);
                                    $immagini_new[$k][1] = $numero_img;
                                }

                                // ATTRIBUTO BRAND

                                // recupero id magento associato (se esiste)
                                $stringQuery = "select id_magento from " . $resource->getTableName('wsca_brand') . " where id_ws='" . $id_brand . "'";
                                $id_brandMage = $readConnection->fetchOne($stringQuery);

                                if ($id_brandMage == null) {
                                    $attribute_model = Mage::getModel('eav/entity_attribute');
                                    $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                    $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_brand");
                                    $attribute = $attribute_model->load($attribute_code);

                                    $attribute->setData('option', array(
                                        'value' => array(
                                            'option' => array(ucfirst(strtolower($nome_brand)), ucfirst(strtolower($nome_brand)))
                                        )
                                    ));
                                    $attribute->save();

                                    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                    $optionTable = $setup->getTable('eav/attribute_option');

                                    $id_brandMage = $this->getLastInsertId($optionTable, 'option_id');


                                    $query = "insert into " . $resource->getTableName('wsca_brand') . " (id_magento,id_ws) values('" . $id_brandMage . "','" . $id_brand . "')";
                                    $writeConnection->query($query);

                                    //SPLASH
                                    $array["ca_brand"]["value"][0] = $id_brandMage;
                                    $array["ca_brand"]["operator"] = "OR";
                                    $array["ca_brand"]["apply_to"] = 0;
                                    $array["ca_brand"]["include_in_layered_nav"] = 0;

                                    $opzioniP = serialize($array);

                                    $opzioni = "a:0:{}";

                                    $url_key = $this->url_slug(ucfirst(strtolower($nome_brand)));
                                    $url_key = $this->replace_accents($url_key);
                                    $status = "1";
                                    $data = date('Y-m-d H:i:s');

                                    $queryPage = "select max(page_id) from  " . $resource->getTableName('splash_page');
                                    $page_id = $readConnection->fetchOne($queryPage);
                                    $page_id = $page_id + 1;

                                    $query = "insert into  " . $resource->getTableName('splash_page') . "  (page_id,name,short_description,description,url_key,option_filters,price_filters,category_filters,status,created_at,updated_at) values ('" . $page_id . "','" . ucfirst(strtolower(addslashes($nome_brand))) . "','','','" . $url_key . "','" . $opzioniP . "','" . $opzioni . "','" . $opzioni . "','" . $status . "','" . $data . "','" . $data . "')";
                                    $writeConnection->query($query);

                                    $query2 = "insert into " . $resource->getTableName('splash_page_store') . " (page_id,store_id) values ('" . $page_id . "','1')";
                                    $writeConnection->query($query2);

                                    $queryPage = "select max(page_id) from  " . $resource->getTableName('splash_page');
                                    $page_idEng = $readConnection->fetchOne($queryPage);
                                    $page_idEng = $page_idEng + 1;

                                    $queryEng = "insert into " . $resource->getTableName('splash_page') . " (page_id,name,short_description,description,url_key,option_filters,price_filters,category_filters,status,created_at,updated_at) values ('" . $page_idEng . "','" . ucfirst(strtolower(addslashes($nome_brand))) . "','','','" . $url_key . "','" . $opzioniP . "','" . $opzioni . "','" . $opzioni . "','" . $status . "','" . $data . "','" . $data . "')";
                                    $writeConnection->query($queryEng);

                                    $query2Eng = "insert into " . $resource->getTableName('splash_page_store') . " (page_id,store_id) values ('" . $page_idEng . "','2')";
                                    $writeConnection->query($query2Eng);


                                    $queryPage = "select max(page_id) from  " . $resource->getTableName('splash_page');
                                    $page_idUsa = $readConnection->fetchOne($queryPage);
                                    $page_idUsa = $page_idUsa + 1;

                                    $queryUsa = "insert into " . $resource->getTableName('splash_page') . " (page_id,name,short_description,description,url_key,option_filters,price_filters,category_filters,status,created_at,updated_at) values ('" . $page_idUsa . "','" . ucfirst(strtolower(addslashes($nome_brand))) . "','','','" . $url_key . "','" . $opzioniP . "','" . $opzioni . "','" . $opzioni . "','" . $status . "','" . $data . "','" . $data . "')";
                                    $writeConnection->query($queryUsa);

                                    $query2Usa = "insert into " . $resource->getTableName('splash_page_store') . " (page_id,store_id) values ('" . $page_idUsa . "','3')";
                                    $writeConnection->query($query2Usa);

                                    $nomeBrandMage=$nome_brand;
                                } else {
                                    $attr = Mage::getModel('catalog/product')->getResource()->getAttribute("ca_brand");
                                    if ($attr->usesSource()) {
                                        $nomeBrandMage = $attr->getSource()->getOptionText($id_brandMage);
                                        if ($nomeBrandMage==""){
                                            $nomeBrandMage=$nome_brand;
                                        }
                                    }
                                }

// 3° LIVELLO CATEGORIA
                                // recupero id magento associato (se esiste). Controllo il group associato alla macro category precedente è presente in magento
                                $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subgroup') . " where id_ws='" . $id_sottocategoria2 . "' and id_group='" . $id_sottocategoria1 . "' and id_macro_category='" . $id_categoria . "'";
                                $id_sottocategoria2Mage = $readConnection->fetchOne($stringQuery);
                                if ($id_sottocategoria2Mage == null) {

                                    $categoryPadre = Mage::getModel('catalog/category')->load($id_sottocategoria1Mage);
                                    $path = $categoryPadre->getPath();

                                    $category = Mage::getModel('catalog/category');
                                    $category->setStoreId(0);


                                    $titleITA = ucfirst(strtolower($nome_sottocategoria1)) . " " . $sesso . " alta moda";

                                    $keywords1 = ucfirst(strtolower($nome_sottocategoria1));
                                    $keywords2 = $titleITA;
                                    $keywords3 = "Shop online " . $titleITA;
                                    $keywordsITA = $keywords1 . ", " . $keywords2 . ", " . $keywords3;

                                    $descriptionITA = "Acquista online la migliore collezione di " . ucfirst(strtolower($nome_sottocategoria1)) . " da " . $sesso . " dei migliori brand internazionali di alta moda. Su ColtortiBoutique spedizione express e reso garantito";


                                    $general['meta_title'] = $titleITA;
                                    $general['meta_keywords'] = $keywordsITA;
                                    $general['meta_description'] = $descriptionITA;

                                    $general['name'] = ucfirst(strtolower($nome_sottocategoria2));
                                    $general['path'] = $path;
                                    $general['is_active'] = 1;
                                    $general['is_anchor'] = 1;

                                    $general['url_key'] = strtolower($nome_sottocategoria2) . "-" . strtolower($nome_categoria);


                                    $category->addData($general);
                                    $category->save();
                                    $id_sottocategoria2Mage = $category->getId();

                                    $query = "insert into " . $resource->getTableName('wsca_subgroup') . " (id_magento,id_ws,id_group,id_macro_category) values('" . $id_sottocategoria2Mage . "','" . $id_sottocategoria2 . "','" . $id_sottocategoria1 . "','" . $id_categoria . "')";
                                    $writeConnection->query($query);

                                    $nomeSottocategoria2Mage = $nome_sottocategoria2;
                                } else {
                                    $sottocategoria2 = Mage::getModel('catalog/category')->load($id_sottocategoria2Mage);
                                    $nomeSottocategoria2Mage = $sottocategoria2->getName();
                                }
                                // 4° LIVELLO CATEGORIA
                                if ($id_sottocategoria3 != null) {
                                    // recupero id magento associato (se esiste). Controllo il group associato alla macro category precedente è presente in magento
                                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_category') . " where id_ws='" . $id_sottocategoria3 . "' and id_subgroup='" . $id_sottocategoria2 . "' and id_group='" . $id_sottocategoria1 . "' and id_macro_category='" . $id_categoria . "'";
                                    $id_sottocategoria3Mage = $readConnection->fetchOne($stringQuery);

                                        $sottocategoria3 = Mage::getModel('catalog/category')->load($id_sottocategoria3Mage);
                                        $nomeSottocategoria3Mage = $sottocategoria3->getName();

                                }

                                // associazione attributi custom al prodotto semplice
                                foreach ($attributi as $key => $value) {
                                    $id_attributo = $key;
                                    $valoreAttributi = $value;

                                    foreach ($valoreAttributi as $key => $value) {
                                        if ($key == "description") {
                                            $nome_attributo = $value;
                                        }
                                        if ($key == "values") {
                                            $subattributes = $value;
                                        }
                                    }

                                    if ($nome_attributo == "Supercolore") {
                                        $id_valoreattributo = "";
                                        $j = 0;
                                        foreach ($subattributes as $key => $value) {
                                            if ($j != 0) {
                                                $id_valoreattributo .= "/";
                                            }
                                            $id_valoreattributo = $key;
                                            $j = $j + 1;
                                        }


                                        if ($j == 1) {

                                            foreach ($subattributes as $key => $value) {
                                                $id_valoreattributo = $key;
                                            }

                                            // recupero nome colore
                                            $stringQuery = "select nome_magento from " . $resource->getTableName('wsca_colore') . " where id_ws='" . $id_valoreattributo . "'";
                                            $nome_colore = $readConnection->fetchOne($stringQuery);


                                        } else {

                                            $nome_colore = "misto";


                                        }
                                    }
                                }


                                // inserimento prodotto configurabile
                                $sku_configurabile = $id;
                                if ($id_sottocategoria3 != null) {
                                    $nome_configurabile = ucfirst(strtolower($nomeBrandMage . " " . $nome));
                                    $url_key_configurabile = $nomeSottocategoria3Mage . "-" . $nome_brand . "-" . $sku_configurabile;
                                    $sottoCat = $nome_sottocategoria3;
                                } else {
                                    $nome_configurabile = ucfirst(strtolower($nomeBrandMage . " " . $nome));
                                    $url_key_configurabile = $nomeSottocategoria2Mage . "-" . $nome_brand . "-" . $sku_configurabile;
                                    $sottoCat = $nome_sottocategoria2;
                                }


                                $productConfigurable = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_configurabile);


                                // eliminare immagini
                                $mediaApi = Mage::getModel("catalog/product_attribute_media_api");
                                $items = $mediaApi->items($productConfigurable->getId());
                                foreach ($items as $item) {
                                    $mediaApi->remove($productConfigurable->getId(), $item['file']);
                                    unlink(Mage::getBaseDir('media') . '/catalog/product' . $item['file']);
                                }


                                $productConfigurable->save();


                                $productConfigurable = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_configurabile);


                                //inserimento immagini
                                for ($k = 0; $k < count($immagini_new); $k++) {
                                    $image_location = getDownloadImage("product", $immagini_new[$k][0], $sottoCat, $nome_brand, $nome_colore, $id);
                                    if ($image_location != "") {
                                        if ($immagini_new[$k][1] == "3") {

                                            if (filesize($image_location) > 0) {
                                                $ext = pathinfo($immagini_new[$k][0], PATHINFO_EXTENSION);
                                                $nome_fileSmall = replace_accents(url_slug(strtolower($sottoCat))) . "_" . replace_accents(url_slug(strtolower($nome_brand))) . "_" . replace_accents(url_slug(strtolower($nome_colore))) . "_" . replace_accents(url_slug(strtolower($id))) . "-3s." . $ext;
                                                $import_location = "../../var/images";
                                                $file_targetSmall = $import_location . "/" . $nome_fileSmall;
                                                $img = new Imagick();
                                                $img->clear();
                                                $img->readImage($image_location);
                                                $img->thumbnailImage(900, 0);
                                                $img->setOption('jpeg:extent', '180kb');
                                                $img->writeImage($file_targetSmall);
                                                $productConfigurable->addImageToMediaGallery($file_targetSmall, array('small_image'), false, false);


                                                $nome_fileThumb = replace_accents(url_slug(strtolower($sottoCat))) . "_" . replace_accents(url_slug(strtolower($nome_brand))) . "_" . replace_accents(url_slug(strtolower($nome_colore))) . "_" . replace_accents(url_slug(strtolower($id))) . "-3t." . $ext;
                                                $import_location = "../../var/images";
                                                $file_targetThumb = $import_location . "/" . $nome_fileThumb;
                                                $img = new Imagick();
                                                $img->clear();
                                                $img->readImage($image_location);
                                                $img->thumbnailImage(300, 0);
                                                $img->setOption('jpeg:extent', '180kb');
                                                $img->writeImage($file_targetThumb);
                                                $productConfigurable->addImageToMediaGallery($file_targetThumb, array('thumbnail'), false, false);

                                                $productConfigurable->addImageToMediaGallery($image_location, array('image'), false, false);
                                            } else {
                                                $productConfigurable->addImageToMediaGallery($image_location, array('image', 'small_image', 'thumbnail'), false, false);
                                            }


                                        } else if ($immagini_new[$k][1] == "1") {


                                        } else if ($immagini_new[$k][1] == "2") {


                                        } else if ($immagini_new[$k][1] == "4") {
                                            if (filesize($image_location) > 0) {
                                                $ext = pathinfo($immagini_new[$k][0], PATHINFO_EXTENSION);
                                                $nome_fileSmall = replace_accents(url_slug(strtolower($sottoCat))) . "_" . replace_accents(url_slug(strtolower($nome_brand))) . "_" . replace_accents(url_slug(strtolower($nome_colore))) . "_" . replace_accents(url_slug(strtolower($id))) . "-4s." . $ext;
                                                $import_location = "../../var/images";
                                                $file_targetSmall = $import_location . "/" . $nome_fileSmall;
                                                $img = new Imagick();
                                                $img->clear();
                                                $img->readImage($image_location);
                                                $img->thumbnailImage(900, 0);
                                                $img->setOption('jpeg:extent', '180kb');
                                                $img->writeImage($file_targetSmall);
                                                $productConfigurable->addImageToMediaGallery($file_targetSmall, array(''), false, false);
                                            }
                                            $productConfigurable->addImageToMediaGallery($image_location, array(''), false, false);

                                        } else {
                                            $productConfigurable->addImageToMediaGallery($image_location, array(""), false, false);

                                        }
                                    }
                                }


                                try {
                                    $productConfigurable->save();
                                } catch (Exception $e) {
                                    Mage::log($e->getMessage());
                                }


                                // aggiornamento immagini
                                $product = Mage::getModel('catalog/product');
                                $product->load($product->getIdBySku($sku_configurabile));
                                $stringQuery = "select value_id,value from " . $resource->getTableName('catalog_product_entity_media_gallery') . " where entity_id='" . $product->getId() . "'";
                                $immagine = $readConnection->fetchAll($stringQuery);
                                foreach ($immagine as $image) {
                                    $path = $image["value"];
                                    $file = basename($path);

                                    $punto = strrpos($file, ".");
                                    $file_new = substr($file, 0, $punto);

                                    $posizione = strrpos($file_new, "-"); // strrpos serve per trovare l'ultima occorrenza
                                    $numero_img = substr($file_new, $posizione + 1, strlen($file_new) - $posizione);


                                    $carattere = substr($numero_img, 1, 1);
                                    if ($carattere == "s" || $carattere == "t") {
                                        $numero_img = substr($numero_img, 0, 2);
                                    } else {
                                        $numero_img = substr($numero_img, 0, 1);
                                    }


                                    $stringQuery = "select value_id from " . $resource->getTableName('catalog_product_entity_media_gallery') . " where entity_id='" . $product->getId() . "' and value='" . $path . "'";
                                    $immagine = $readConnection->fetchOne($stringQuery);


                                    if ($numero_img == "3") {
                                        $query = 'update ' . $resource->getTableName("catalog_product_entity_media_gallery_value") . ' set disabled=0, position=1 where value_id="' . $immagine . '"';
                                        $writeConnection->query($query);
                                    } else if ($numero_img == "3s" || $numero_img == "3t") {
                                        $query = 'update ' . $resource->getTableName("catalog_product_entity_media_gallery_value") . ' set disabled=1, position=1 where value_id="' . $immagine . '"';
                                        $writeConnection->query($query);
                                    } else if ($numero_img == "4s") {
                                        $query = 'update ' . $resource->getTableName("catalog_product_entity_media_gallery_value") . ' set position=2,label="back" where value_id="' . $immagine . '"';
                                        $writeConnection->query($query);
                                    } else {
                                        $query = 'update ' . $resource->getTableName("catalog_product_entity_media_gallery_value") . ' set position="' . ($numero_img - 2) . '" where value_id="' . $immagine . '"';
                                        $writeConnection->query($query);
                                    }
                                }


                            }


                        }

                        $files = glob('../../var/images/*.*');
                        foreach ($files as $file) {
                            unlink($file);
                        }
                    }

                }
                Mage::log("FINE IMPORT", null, $logFileName);
            } catch (Exception $e) {
                Mage::log("ERRORE GENERALE ID " . $product_id . " " . $e->getMessage());
            }
        }

