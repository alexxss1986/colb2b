<?php
class TreC_NewProducts_Model_Observer
{
    public function getLastInsertId($tableName, $primaryKey)
    {
        //SELECT MAX(id) FROM table
        $db = Mage::getModel('core/resource')->getConnection('core_read');
        $result = $db->raw_fetchRow("SELECT MAX(`{$primaryKey}`) as LastID FROM `{$tableName}`");
        return $result['LastID'];
    }


    public function getLastCategoryEng($product){
        $categoryModel = Mage::getModel( 'catalog/category' );

//Get Array of Category Id's with Last as First (Reversed)
        $_categories = array_reverse( $product->getCategoryIds() );

//Load Category
        $_category = $categoryModel->setStoreId(2)->load($_categories[0]);

        return $_category;
    }


    public function replace_accents($string)
    {
        return str_replace( array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'), array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y'), $string);
    }

// Download Image
    public function  getDownloadImage($type,$file,$sottoCat,$nome_brand,$nome_colore,$id){
        /*$path = str_replace("index.php","",$_SERVER["SCRIPT_FILENAME"]);
        $import_location = $path.'media/catalog/';
        if (!file_exists($import_location)){
            mkdir($import_location, 0755);
        }
        $import_location = $path.'media/catalog/'.$type.'/';
        if (!file_exists($import_location)){
            mkdir($import_location, 0755);
        }*/
        $file_path = "";
        // estensione foto

        $ext = pathinfo($file, PATHINFO_EXTENSION);

        if (strtolower($ext)=="jpg") {

            // recupero il numero della foto
            $punto = strrpos($file, ".");
            $file_new = substr($file, 0, $punto);

            // il numero dell'immagine
            $numero_img = substr($file_new, strlen($file_new) - 1, 1);
            $nome_file = $this->replace_accents($this->url_slug(strtolower($sottoCat))) . "_" . $this->replace_accents($this->url_slug(strtolower($nome_brand))) . "_" . $this->replace_accents($this->url_slug(strtolower($nome_colore))) . "_" . $this->replace_accents($this->url_slug(strtolower($id))) . "-" . $numero_img . "." . $ext;

            $import_location = "./var/images";

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

            $img = new Imagick($file_path);
            $img->setOption('jpeg:extent', '150kb');
            $img->writeImage($file_path);
        }

        return $file_path;
    }



    public function url_slug($str, $options = array()) {
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

    public function getAttributes($resource,$readConnection,$writeConnection,$username,$password,$service_url){
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
            return false;
        }
        else {
            curl_close($curlPost);
            $decodedPost = json_decode($curl_responsePost);


            if (is_object($decodedPost)) {
                $arrayPost = get_object_vars($decodedPost);
            }

            $aToken = $arrayPost["access_token"];


            $headersGet = array(
                'Authorization: Bearer ' . $aToken
            );


// recupero solo l'header della richiesta
            $service_urlGet = $service_url . "/attributes?&limit=100";
            $curlGet = curl_init($service_urlGet);
            curl_setopt($curlGet, CURLOPT_HTTPAUTH, CURLAUTH_ANY); // accetto ogni autenticazione. Il sitema utilizzerà la migliore
            curl_setopt($curlGet, CURLOPT_SSL_VERIFYPEER, false); // disabilito la verifica del certificato SSL; è necessario perchè altrimenti non esegue la chiamata rest
            curl_setopt($curlGet, CURLOPT_RETURNTRANSFER, true); // salvo l'output della richiesta in una variabile
            curl_setopt($curlGet, CURLOPT_HEADER, true);
            curl_setopt($curlGet, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curlGet, CURLOPT_NOBODY, true);
            curl_setopt($curlGet, CURLOPT_HTTPHEADER, $headersGet); // setto l'header della richiesta
            $header = curl_exec($curlGet);

            if ($header === false) {
                $infoGet = curl_getinfo($curlGet);
                curl_close($curlGet);
                return false;
            }
            else {

// costrusico un array dell'header e recupero il totale delle pagine
                $myarray = array();
                $data = explode("\n", $header);
                $myarray['status'] = $data[0];
                array_shift($data);
                foreach ($data as $part) {
                    $middle = explode(":", $part);
                    if (isset($middle[1])) {
                        $myarray[trim($middle[0])] = trim($middle[1]);
                    }
                }
                $pagine = $myarray["X-Count-Pages"];


// effettuo una chiamata al web service per ogni pagina

                for ($p = 1; $p <= $pagine; $p++) {
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
                        return false;
                    }
                    else {
                        curl_close($curlPost);
                        $decodedPost = json_decode($curl_responsePost);


                        if (is_object($decodedPost)) {
                            $arrayPost = get_object_vars($decodedPost);
                        }

                        $aToken = $arrayPost["access_token"];


                        $headersGet = array(
                            'Authorization: Bearer ' . $aToken
                        );
                        $service_urlGet = $service_url . "/attributes?limit=100&page=" . $p;
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
                            return false;
                        }
                        else {

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
                                $l = $l + 1;
                                $id_attributo = $key;
                                $valoreProdotti = $value;

                                $description = null;
                                $valoriAttributi = null;
                                foreach ($valoreProdotti as $key => $value) {
                                    // recupero campi prodotto
                                    if ($key == "description") {
                                        $nome_attributo = $value;
                                        if (is_object($nome_attributo)) {
                                            $nome_attributo = get_object_vars($nome_attributo);
                                        }
                                    }
                                    if ($key == "values") {
                                        $subattributes = $value;
                                        if (is_object($subattributes)) {
                                            $subattributes = get_object_vars($subattributes);
                                        }
                                    }
                                }


                                // controllo esistenza dell'attributo in magento
                                $stringQuery = "select id_magento from " . $resource->getTableName('wsca_attributes') . " where id_ws='" . $id_attributo . "'";
                                $id_attributoMage = $readConnection->fetchOne($stringQuery);


                                if ($id_attributoMage == null) {
                                    $model = Mage::getModel('eav/entity_setup', 'core_setup');
                                    if (count($subattributes) == 0) {
                                        $input = "text";
                                        $dbInput = "testo";
                                    } else {
                                        $input = "multiselect";
                                        $dbInput = "select";
                                    }

                                    $data =
                                        array(
                                            'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
                                            'group' => 'General',
                                            'type' => 'varchar',
                                            'backend' => '',
                                            'frontend' => '',
                                            'label' => ucfirst(strtolower($this->replace_accents($nome_attributo))),
                                            'input' => $input,
                                            'unique' => false,
                                            'required' => false,
                                            'is_configurable' => false,
                                            'searchable' => false,
                                            'visible_in_advanced_search' => false,
                                            'comparable' => false,
                                            'filterable' => false,
                                            'filterable_in_search' => false,
                                            'used_for_promo_rules' => false,
                                            'visible_on_front' => false,
                                            'used_in_product_listing' => false,
                                            'used_for_sort_by' => false,
                                            'user_defined' => true,

                                        );

                                    $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                    $model->addAttribute('catalog_product', $nome_attributoMage, $data);


                                    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                    $optionTable = $setup->getTable('eav/attribute');

                                    $id_attributoMage = $this->getLastInsertId($optionTable, 'attribute_id');

                                    $query = "insert into " . $resource->getTableName('wsca_attributes') . " (id_magento,id_ws,tipo) values('" . $id_attributoMage . "','" . $id_attributo . "','" . $dbInput . "')";
                                    $writeConnection->query($query);

                                } else {
                                    $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                }

                                // se l'attributo è il supercolore utilizzo anche l'attributo colore
                                if ($nome_attributo == "Supercolore") {


                                } else {

                                    foreach ($subattributes as $key => $value) {

                                        $id_valoreattributo = $key;
                                        $nome_valoreattributo = $value;

                                        // controllo esistenza opzione in magento per l'attributo in questione
                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subattributes') . " where id_ws='" . $id_valoreattributo . "' and id_attributes='" . $id_attributo . "'";
                                        $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);
                                        if ($id_valoreattributoMage == null) {
                                            if ($nome_valoreattributo == "" || $nome_valoreattributo == null) {

                                            } else {

                                                $attribute_model = Mage::getModel('eav/entity_attribute');
                                                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                $attribute_code = $attribute_model->getIdByCode('catalog_product', $nome_attributoMage);
                                                $attribute = $attribute_model->load($attribute_code);

                                                $attribute->setData('option', array(
                                                    'value' => array(
                                                        'option' => array(ucfirst(strtolower($nome_valoreattributo)), ucfirst(strtolower($nome_valoreattributo)))
                                                    )
                                                ));
                                                $attribute->save();

                                                $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                                $optionTable = $setup->getTable('eav/attribute_option');

                                                $id_valoreattributoMage = $this->getLastInsertId($optionTable, 'option_id');

                                                $query = "insert into " . $resource->getTableName('wsca_subattributes') . " (id_magento,id_ws, id_attributes) values('" . $id_valoreattributoMage . "','" . $id_valoreattributo . "','" . $id_attributo . "')";
                                                $writeConnection->query($query);
                                            }
                                        } else {
                                            $attr = Mage::getModel('catalog/product')->getResource()->getAttribute($nome_attributoMage);
                                            if ($attr->usesSource()) {
                                                $nomeValoreAttributoMage = $attr->getSource()->getOptionText($id_valoreattributoMage);
                                                if (strtolower($nomeValoreAttributoMage) != strtolower($nome_valoreattributo)) {
                                                    $attribute_model = Mage::getModel('eav/entity_attribute');
                                                    $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                    $attribute_code = $attribute_model->getIdByCode('catalog_product', $nome_attributoMage);
                                                    $attribute = $attribute_model->load($attribute_code);

                                                    // modifica della stagione su magento (nome dell'opzione)
                                                    $data = array();
                                                    $values = array(
                                                        $id_valoreattributoMage => array(
                                                            0 => ucfirst(strtolower($nome_valoreattributo)),
                                                            1 => ucfirst(strtolower($nome_valoreattributo))
                                                        ),

                                                    );

                                                    $data['option']['value'] = $values;
                                                    $attribute->addData($data);


                                                    $attribute->save();
                                                }
                                            }
                                        }

                                    }

                                }

                            }
                        }
                    }


                }
            }
        }

        return true;
    }





    public function import() {
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $writeConnection = $resource->getConnection('core_write');

        $username = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/username');
        $password = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/password');
        $service_url = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/endpoint');

        if (isset($username) && $username!="" && isset($password) && $password!="" && isset($service_url) && $service_url!="") {

            $filename = "catalogo";
            $logFileName = $filename . '.log';




            try {


                // controllo in che stato mi trovo dell'esecuzione dell'importazione

                $dataCorrente = date('Y-m-d', strtotime('+2 hours', strtotime(date('Y-m-d'))));
                $stringQuery = "select page_number,running,finish from " . $resource->getTableName('wsca_import_new_log') . " where dataImport='" . $dataCorrente . "'";
                $importLog = $readConnection->fetchAll($stringQuery);
                $page_number = 0;
                $finish = 0;
                $running = 0;
                foreach ($importLog as $row) {
                    $page_number = $row['page_number'];
                    $finish = $row['finish'];
                    $running = $row['running'];
                }
                $page_number=$page_number+1;

                if ($page_number != "" && $running == 0 && $finish == 0) {
                    $pCollection = Mage::getSingleton('index/indexer')->getProcessesCollection();
                    foreach ($pCollection as $process) {
                        $process->setMode(Mage_Index_Model_Process::MODE_MANUAL)->save();
                    }

                    if ($page_number == 1) {
                        // salvo la pagina in cui sono arrivato
                        $query = "insert into " . $resource->getTableName('wsca_import_new_log') . " (page_number,running,finish,dataImport) values('" . $page_number . "','1','" . $finish . "','" . $dataCorrente . "')";
                        $writeConnection->query($query);
                    } else {
                        // salvo la pagina in cui sono arrivato
                        $query = "update " . $resource->getTableName('wsca_import_new_log') . " set page_number='" . $page_number . "',running='1',finish='" . $finish . "' where dataImport='" . $dataCorrente . "'";
                        $writeConnection->query($query);
                    }

                    $controllo=$this->getAttributes($resource, $readConnection, $writeConnection, $username, $password, $service_url);

                    if ($controllo==true) {
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

                            $page_number=$page_number-1;

                            // salvo la pagina in cui sono arrivato
                            $query = "update " . $resource->getTableName('wsca_import_new_log') . " set page_number='" . $page_number . "',running='0' where dataImport='" . $dataCorrente . "'";
                            $writeConnection->query($query);

                            $readConnection->closeConnection();
                            $writeConnection->closeConnection();
                        }
                        else {
                            curl_close($curlPost);
                            $decodedPost = json_decode($curl_responsePost);


                            if (is_object($decodedPost)) {
                                $arrayPost = get_object_vars($decodedPost);
                            }

                            $aToken = $arrayPost["access_token"];


                            $headersGet = array(
                                'Authorization: Bearer ' . $aToken
                            );


                            $dataFine = $dataCorrente . "T00:00:00Z";


                            // recupero solo l'header della richiesta
                            $service_urlGet = $service_url . "/products?images=true&attributes=true&limit=100&until_updated_at=" . $dataFine;
                            Mage::log($service_urlGet, null, $logFileName);
                            //$service_urlGet = $service_url . "/products?images=true&attributes=true&limit=100&id=151431UGH000003-001S";
                            //$service_urlGet = $service_url . "/products?images=true&attributes=true&limit=100";
                            $curlGet = curl_init($service_urlGet);
                            curl_setopt($curlGet, CURLOPT_HTTPAUTH, CURLAUTH_ANY); // accetto ogni autenticazione. Il sitema utilizzerà la migliore
                            curl_setopt($curlGet, CURLOPT_SSL_VERIFYPEER, false); // disabilito la verifica del certificato SSL; è necessario perchè altrimenti non esegue la chiamata rest
                            curl_setopt($curlGet, CURLOPT_RETURNTRANSFER, true); // salvo l'output della richiesta in una variabile
                            curl_setopt($curlGet, CURLOPT_HEADER, true);
                            curl_setopt($curlGet, CURLOPT_SSL_VERIFYHOST, false);
                            curl_setopt($curlGet, CURLOPT_NOBODY, true);
                            curl_setopt($curlGet, CURLOPT_HTTPHEADER, $headersGet); // setto l'header della richiesta
                            $header = curl_exec($curlGet);

                            if ($header === false) {
                                $infoGet = curl_getinfo($curlGet);
                                curl_close($curlGet);

                                $page_number = $page_number - 1;
                                // salvo la pagina in cui sono arrivato
                                $query = "update " . $resource->getTableName('wsca_import_new_log') . " set page_number='" . $page_number . "',running='0' where dataImport='" . $dataCorrente . "'";
                                $writeConnection->query($query);

                                $readConnection->closeConnection();
                                $writeConnection->closeConnection();
                            } else {

                                // costrusico un array dell'header e recupero il totale delle pagine
                                $myarray = array();
                                $data = explode("\n", $header);
                                $myarray['status'] = $data[0];
                                array_shift($data);
                                foreach ($data as $part) {
                                    $middle = explode(":", $part);
                                    if (isset($middle[1])) {
                                        $myarray[trim($middle[0])] = trim($middle[1]);
                                    }
                                }
                                $pagine = $myarray["X-Count-Pages"];


                                // effettuo una chiamata al web service per ogni pagina

                                Mage::log("PAGINA INIZIALE " . $page_number, null, $logFileName);
                                Mage::log("PAGINA TOTALE " . $pagine, null, $logFileName);

                                for ($p = $page_number; $p <= $pagine; $p++) {
                                    Mage::log("PAGINA " . $p, null, $logFileName);
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

                                        $p = $p - 1;

                                        // salvo la pagina in cui sono arrivato
                                        $query = "update " . $resource->getTableName('wsca_import_new_log') . " set page_number='" . $p . "',running='0' where dataImport='" . $dataCorrente . "'";
                                        $writeConnection->query($query);

                                        $readConnection->closeConnection();
                                        $writeConnection->closeConnection();

                                        break;
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
                                        $service_urlGet = $service_url . "/products?images=true&attributes=true&limit=100&until_updated_at=" . $dataFine."&page=" . $p;

                                        //$service_urlGet = $service_url . "/products?images=true&attributes=true&limit=100&id=151431UGH000003-001S&page=" . $p;
                                        //$service_urlGet = $service_url . "/products?images=true&attributes=true&limit=100&page=" . $p;
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

                                            $p = $p - 1;

                                            // salvo la pagina in cui sono arrivato
                                            $query = "update " . $resource->getTableName('wsca_import_new_log') . " set page_number='" . $p . "',running='0' where dataImport='" . $dataCorrente . "'";
                                            $writeConnection->query($query);

                                            $readConnection->closeConnection();
                                            $writeConnection->closeConnection();

                                            break;
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

                                                    // controllo prodotto
                                                    $sku_configurabile = $id;
                                                    $productConfigurable = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_configurabile);
                                                    if (!$productConfigurable) {
                                                        Mage::log($id, null, $logFileName);

                                                        // recupero info in inglese
                                                        $skuUrl=$id;
                                                        $skuUrl = str_replace(" ", "%20", $skuUrl);
                                                        $nomeEng=$nome;
                                                        $descrizioneEng=$descrizione;

                                                        $service_urlPostEng = $service_url . "/user/token";
                                                        $curlPostEng = curl_init($service_urlPostEng);

                                                        $headersPostEng = array(
                                                            'Content-Type:application/json;charset=utf-8',
                                                            'Content-Length: 0', // aggiunto per evitare l'errore 413: Request Entity Too Large
                                                            'Authorization: Basic ' . base64_encode($username . ":" . $password)
                                                        );


                                                        curl_setopt($curlPostEng, CURLOPT_POST, true);  // indico che la richiesta è di tipo POST
                                                        curl_setopt($curlPostEng, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
                                                        curl_setopt($curlPostEng, CURLOPT_SSL_VERIFYPEER, false); // disabilito la verifica del certificato SSL; è necessario perchè altrimenti non esegue la chiamata rest
                                                        curl_setopt($curlPostEng, CURLOPT_RETURNTRANSFER, true);
                                                        curl_setopt($curlPostEng, CURLOPT_SSL_VERIFYHOST, false);
                                                        curl_setopt($curlPostEng, CURLOPT_HTTPHEADER, $headersPostEng);
                                                        $curl_responsePostEng = curl_exec($curlPostEng);
                                                        if ($curl_responsePostEng === false) {
                                                            $infoPostEng = curl_getinfo($curl_responsePostEng);
                                                            curl_close($curl_responsePostEng);
                                                            die('Errore nella richiesta. Informazioni addizionali: ' . var_export($infoPostEng));
                                                        }
                                                        curl_close($curlPostEng);
                                                        $decodedPostEng = json_decode($curl_responsePostEng);


                                                        if (is_object($decodedPostEng)) {
                                                            $arrayPostEng = get_object_vars($decodedPostEng);
                                                        }

                                                        $aTokenEng = $arrayPostEng["access_token"];


                                                        $headersGetEng = array(
                                                            'Authorization: Bearer ' . $aTokenEng
                                                        );

                                                        $service_urlGetEng = $service_url . "/products?lang=en&id=".$skuUrl;

                                                        //$service_urlGet = $service_url . "/products?images=true&attributes=true&limit=100&id=151431UGH000003-001S&page=" . $p;
                                                        //$service_urlGet = $service_url . "/products?images=true&attributes=true&limit=100&page=" . $p;
                                                        $curlGetEng = curl_init($service_urlGetEng);
                                                        curl_setopt($curlGetEng, CURLOPT_HTTPAUTH, CURLAUTH_ANY); // accetto ogni autenticazione. Il sitema utilizzerà la migliore
                                                        curl_setopt($curlGetEng, CURLOPT_SSL_VERIFYPEER, false); // disabilito la verifica del certificato SSL; è necessario perchè altrimenti non esegue la chiamata rest
                                                        curl_setopt($curlGetEng, CURLOPT_RETURNTRANSFER, true); // salvo l'output della richiesta in una variabile
                                                        curl_setopt($curlGetEng, CURLOPT_HTTPHEADER, $headersGetEng); // setto l'header della richiesta
                                                        curl_setopt($curlGetEng, CURLOPT_SSL_VERIFYHOST, false);
                                                        $curl_responseGetEng = curl_exec($curlGetEng);

                                                        if ($curl_responseGetEng === false) {
                                                            $infoGet = curl_getinfo($curlGetEng);
                                                            curl_close($curlGetEng);

                                                            break;
                                                        } else {
                                                            curl_close($curlGetEng);

                                                            // parse del contenuto
                                                            $decodedGetEng = json_decode($curl_responseGetEng);

                                                            if (is_object($decodedGetEng)) {
                                                                $decodedGetEng = get_object_vars($decodedGetEng);
                                                            }


                                                            foreach ($decodedGetEng as $key => $value) {
                                                                $qtaTot = 0;
                                                                $id = $key;
                                                                $valoreProdotti = $value;


                                                                $nomeEng = null;
                                                                $descrizioneEng = null;

                                                                foreach ($valoreProdotti as $key => $value) {
                                                                    // recupero campi prodotto
                                                                    if ($key == "description") {
                                                                        $descrizioneEng = $value;
                                                                    }
                                                                    if ($key == "name") {
                                                                        $nomeEng = $value;
                                                                    }
                                                                }
                                                            }
                                                        }


                                                        // recupero codice produttore
                                                        if ($alternative_ids != null && count($alternative_ids) > 1) {
                                                            $codice_produttore = $alternative_ids[1];
                                                        } else if ($alternative_ids != null && count($alternative_ids) > 0) {
                                                            $codice_produttore = $alternative_ids[0];
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
                                                                /*if (strtolower($nomeBrandMage) != strtolower($nome_brand)) {
                                                                    $attribute_model = Mage::getModel('eav/entity_attribute');
                                                                    $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                                    $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_brand");
                                                                    $attribute = $attribute_model->load($attribute_code);

                                                                    // modifica della stagione su magento (nome dell'opzione)
                                                                    $data = array();
                                                                    $values = array(
                                                                        $id_brandMage => array(
                                                                            0 => ucfirst(strtolower($nome_brand)),
                                                                            1 => ucfirst(strtolower($nome_brand))
                                                                        ),

                                                                    );

                                                                    $data['option']['value'] = $values;
                                                                    $attribute->addData($data);


                                                                    $attribute->save();
                                                                }*/
                                                            }
                                                        }

                                                        // ATTRIBUTO ANNO E STAGIONE

                                                        // recupero id magento associato (se esiste)
                                                        $stringQuery = "select anno from " . $resource->getTableName('wsca_season') . " where id_ws='" . $id_season . "'";
                                                        $nomeAnnoDB = $readConnection->fetchOne($stringQuery);

                                                        if ($nomeAnnoDB == null) {
                                                            $stringQueryAnno = "select id_magento from " . $resource->getTableName('wsca_anno') . " where LOWER(anno)='" . strtolower($nome_anno) . "'";
                                                            $id_annoMage = $readConnection->fetchOne($stringQueryAnno);

                                                            if ($id_annoMage == null) {
                                                                $attribute_model = Mage::getModel('eav/entity_attribute');
                                                                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_anno");
                                                                $attribute = $attribute_model->load($attribute_code);

                                                                $attribute->setData('option', array(
                                                                    'value' => array(
                                                                        'option' => array(ucfirst(strtolower($nome_anno)), ucfirst(strtolower($nome_anno)))
                                                                    )
                                                                ));
                                                                $attribute->save();

                                                                $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                                                $optionTable = $setup->getTable('eav/attribute_option');

                                                                $id_annoMage = $this->getLastInsertId($optionTable, 'option_id');

                                                                $query = "insert into " . $resource->getTableName('wsca_anno') . " (id_magento,anno) values('" . $id_annoMage . "','" . ucfirst(strtolower($nome_anno)) . "')";
                                                                $writeConnection->query($query);
                                                            }
                                                        } else {
                                                            $stringQueryAnno = "select id_magento from " . $resource->getTableName('wsca_anno') . " where LOWER(anno)='" . strtolower($nomeAnnoDB) . "'";
                                                            $id_annoMage = $readConnection->fetchOne($stringQueryAnno);
                                                        }


                                                        $stringQuery = "select stagione from " . $resource->getTableName('wsca_season') . " where id_ws='" . $id_season . "'";
                                                        $nomeStagioneDB = $readConnection->fetchOne($stringQuery);

                                                        if ($nomeStagioneDB == null) {
                                                            $stringQueryStagione = "select id_magento from " . $resource->getTableName('wsca_stagione') . " where LOWER(stagione)='" . strtolower($nome_stagione) . "'";
                                                            $id_stagioneMage = $readConnection->fetchOne($stringQueryStagione);

                                                            if ($id_stagioneMage == null) {
                                                                $attribute_model = Mage::getModel('eav/entity_attribute');
                                                                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_stagione");
                                                                $attribute = $attribute_model->load($attribute_code);

                                                                $attribute->setData('option', array(
                                                                    'value' => array(
                                                                        'option' => array(ucfirst(strtolower($nome_stagione)), ucfirst(strtolower($nome_stagione)))
                                                                    )
                                                                ));
                                                                $attribute->save();

                                                                $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                                                $optionTable = $setup->getTable('eav/attribute_option');

                                                                $id_stagioneMage = $this->getLastInsertId($optionTable, 'option_id');

                                                                $query = "insert into " . $resource->getTableName('wsca_stagione') . " (id_magento,stagione) values('" . $id_stagioneMage . "','" . ucfirst(strtolower($nome_stagione)) . "')";
                                                                $writeConnection->query($query);
                                                            }
                                                        } else {
                                                            $stringQueryStagione = "select id_magento from " . $resource->getTableName('wsca_stagione') . " where LOWER(stagione)='" . strtolower($nomeStagioneDB) . "'";
                                                            $id_stagioneMage = $readConnection->fetchOne($stringQueryStagione);
                                                        }


                                                        if ($nomeAnnoDB == null && $nomeStagioneDB == null) {
                                                            $query = "insert into " . $resource->getTableName('wsca_season') . " (stagione,id_ws,anno) values('" . ucfirst(strtolower($nome_stagione)) . "','" . $id_season . "','" . ucfirst(strtolower($nome_anno)) . "')";
                                                            $writeConnection->query($query);
                                                        }


                                                        // CATEGORIE

                                                        // 1° LIVELLO CATEGORIA
                                                        // recupero id magento associato (se esiste)
                                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_macro_category') . " where id_ws='" . $id_categoria . "'";
                                                        $id_categoriaMage = $readConnection->fetchOne($stringQuery);
                                                        if ($id_categoriaMage == null) {
                                                            $category = Mage::getModel('catalog/category');
                                                            $category->setStoreId(0);

                                                            $titleITA = ucfirst(strtolower($nome_categoria)) . " alta moda";

                                                            $keywords1 = ucfirst(strtolower($nome_categoria));
                                                            $keywords2 = $titleITA;
                                                            $keywords3 = "Shop online " . $titleITA;
                                                            $keywordsITA = $keywords1 . ", " . $keywords2 . ", " . $keywords3;

                                                            $descriptionITA = "Acquista online la migliore collezione di " . ucfirst(strtolower($nome_categoria)) . " dei migliori brand internazionali di alta moda. Su ColtortiBoutique spedizione express e reso garantito";


                                                            $general['meta_title'] = $titleITA;
                                                            $general['meta_keywords'] = $keywordsITA;
                                                            $general['meta_description'] = $descriptionITA;


                                                            $general['name'] = ucfirst(strtolower($nome_categoria));
                                                            $general['path'] = "1/2";
                                                            $general['is_active'] = 1;
                                                            $general['is_anchor'] = 1;

                                                            $general['url_key'] = strtolower($nome_categoria);


                                                            $category->addData($general);
                                                            $category->save();
                                                            $id_categoriaMage = $category->getId();

                                                            $query = "insert into " . $resource->getTableName('wsca_macro_category') . " (id_magento,id_ws) values('" . $id_categoriaMage . "','" . $id_categoria . "')";
                                                            $writeConnection->query($query);

                                                            $sesso = ucfirst(strtolower($nome_categoria));
                                                            $nomeCategoriaMage=$nome_categoria;
                                                        } else {
                                                            $categoria = Mage::getModel('catalog/category')->load($id_categoriaMage);
                                                            $nomeCategoriaMage = $categoria->getName();
                                                            $sesso = $nomeCategoriaMage;
                                                            /*if (strtolower($nomeCategoriaMage) != strtolower($nome_categoria)) {
                                                                $general['name'] = ucfirst(strtolower($nome_categoria));
                                                                $general['url_key'] = strtolower($nome_categoria);
                                                                $categoria->addData($general);
                                                                $categoria->save();
                                                            }*/
                                                        }

                                                        // 2° LIVELLO CATEGORIA
                                                        // recupero id magento associato (se esiste). Controllo il group associato alla macro category precedente è presente in magento
                                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_group') . " where id_ws='" . $id_sottocategoria1 . "' and id_macro_category='" . $id_categoria . "'";
                                                        $id_sottocategoria1Mage = $readConnection->fetchOne($stringQuery);
                                                        if ($id_sottocategoria1Mage == null) {

                                                            $categoryPadre = Mage::getModel('catalog/category')->load($id_categoriaMage);
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

                                                            $general['name'] = ucfirst(strtolower($nome_sottocategoria1));
                                                            $general['path'] = $path;
                                                            $general['is_active'] = 1;
                                                            $general['is_anchor'] = 1;

                                                            $general['url_key'] = strtolower($nome_sottocategoria1) . "-" . strtolower($nome_categoria);


                                                            $category->addData($general);
                                                            $category->save();
                                                            $id_sottocategoria1Mage = $category->getId();

                                                            $query = "insert into " . $resource->getTableName('wsca_group') . " (id_magento,id_ws,id_macro_category) values('" . $id_sottocategoria1Mage . "','" . $id_sottocategoria1 . "','" . $id_categoria . "')";
                                                            $writeConnection->query($query);

                                                            $nomeSottocategoria1Mage = $nome_sottocategoria1;
                                                        } else {
                                                            $sottocategoria1 = Mage::getModel('catalog/category')->load($id_sottocategoria1Mage);
                                                            $nomeSottocategoria1Mage = $sottocategoria1->getName();
                                                            /*if (strtolower($nomeSottocategoria1Mage) != strtolower($nome_sottocategoria1)) {
                                                                $general['name'] = ucfirst(strtolower($nome_sottocategoria1));
                                                                $general['url_key'] = strtolower($nome_sottocategoria1);
                                                                $sottocategoria1->addData($general);
                                                                $sottocategoria1->save();
                                                            }*/
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
                                                            /*if (strtolower($nomeSottocategoria2Mage) != strtolower($nome_sottocategoria2)) {
                                                                $general['name'] = ucfirst(strtolower($nome_sottocategoria2));
                                                                $general['url_key'] = strtolower($nome_sottocategoria2);
                                                                $sottocategoria2->addData($general);
                                                                $sottocategoria2->save();
                                                            }*/
                                                        }

                                                        // 4° LIVELLO CATEGORIA
                                                        if ($id_sottocategoria3 != null) {
                                                            // recupero id magento associato (se esiste). Controllo il group associato alla macro category precedente è presente in magento
                                                            $stringQuery = "select id_magento from " . $resource->getTableName('wsca_category') . " where id_ws='" . $id_sottocategoria3 . "' and id_subgroup='" . $id_sottocategoria2 . "' and id_group='" . $id_sottocategoria1 . "' and id_macro_category='" . $id_categoria . "'";
                                                            $id_sottocategoria3Mage = $readConnection->fetchOne($stringQuery);
                                                            if ($id_sottocategoria3Mage == null) {

                                                                $categoryPadre = Mage::getModel('catalog/category')->load($id_sottocategoria2Mage);
                                                                $path = $categoryPadre->getPath();

                                                                $category = Mage::getModel('catalog/category');
                                                                $category->setStoreId(0);


                                                                $titleITA = ucfirst(strtolower($nome_sottocategoria3)) . " " . $sesso . " alta moda";

                                                                $keywords1 = ucfirst(strtolower($nome_sottocategoria3));
                                                                $keywords2 = $titleITA;
                                                                $keywords3 = "Shop online " . $titleITA;
                                                                $keywordsITA = $keywords1 . ", " . $keywords2 . ", " . $keywords3;

                                                                $descriptionITA = "Acquista online la migliore collezione di " . ucfirst(strtolower($nome_sottocategoria3)) . " da " . $sesso . " dei migliori brand internazionali di alta moda. Su ColtortiBoutique spedizione express e reso garantito";


                                                                $general['meta_title'] = $titleITA;
                                                                $general['meta_keywords'] = $keywordsITA;
                                                                $general['meta_description'] = $descriptionITA;

                                                                $general['name'] = ucfirst(strtolower($nome_sottocategoria3));
                                                                $general['path'] = $path;
                                                                $general['is_active'] = 1;
                                                                $general['is_anchor'] = 1;

                                                                $general['url_key'] = strtolower($nome_sottocategoria3) . "-" . strtolower($nome_categoria);


                                                                $category->addData($general);
                                                                $category->save();
                                                                $id_sottocategoria3Mage = $category->getId();

                                                                $query = "insert into " . $resource->getTableName('wsca_category') . " (id_magento,id_ws,id_subgroup,id_group,id_macro_category) values('" . $id_sottocategoria3Mage . "','" . $id_sottocategoria3 . "','" . $id_sottocategoria2 . "','" . $id_sottocategoria1 . "','" . $id_categoria . "')";
                                                                $writeConnection->query($query);

                                                                $nomeSottocategoria3Mage=$nome_sottocategoria3;
                                                            } else {
                                                                $sottocategoria3 = Mage::getModel('catalog/category')->load($id_sottocategoria3Mage);
                                                                $nomeSottocategoria3Mage = $sottocategoria3->getName();
                                                                /*if (strtolower($nomeSottocategoria3Mage) != strtolower($nome_sottocategoria3)) {
                                                                    $general['name'] = ucfirst(strtolower($nome_sottocategoria3));
                                                                    $general['url_key'] = strtolower($nome_sottocategoria3);
                                                                    $sottocategoria3->addData($general);
                                                                    $sottocategoria3->save();
                                                                }*/
                                                            }
                                                        }


                                                        $array_cat = array();
                                                        $array_cat[0] = "2";
                                                        $y = 1;
                                                        // costruisco un array con tutte le categorie
                                                        if ($id_sottocategoria3 != null) {
                                                            $array_cat[$y] = $id_categoriaMage;
                                                            $y = $y + 1;
                                                            $array_cat[$y] = $id_sottocategoria1Mage;
                                                            $y = $y + 1;
                                                            $array_cat[$y] = $id_sottocategoria2Mage;
                                                            $y = $y + 1;
                                                            $array_cat[$y] = $id_sottocategoria3Mage;


                                                        } else if ($id_sottocategoria2 != "") {
                                                            $array_cat[$y] = $id_categoriaMage;
                                                            $y = $y + 1;
                                                            $array_cat[$y] = $id_sottocategoria1Mage;
                                                            $y = $y + 1;
                                                            $array_cat[$y] = $id_sottocategoria2Mage;

                                                        }

                                                        // recupero immagini
                                                        $immagini_new = array();
                                                        for ($k = 0; $k < count($immagini[1]); $k++) {
                                                            $immagini_new[$k][0] = $immagini[1][$k];

                                                            // recupero il numero della foto
                                                            $punto = strrpos($immagini_new[$k][0], ".");
                                                            $file_new = substr($immagini_new[$k][0], 0, $punto);

                                                            // il numero dell'immagine
                                                            $posizione = strrpos($file_new, "-"); // strrpos serve per trovare l'ultima occorrenza
                                                            $numero_img = substr($file_new, strlen($file_new) - 1, 1);
                                                            $immagini_new[$k][1] = $numero_img;
                                                        }


                                                        $array_cat_new = array();
                                                        // riordino l'array delle categorie
                                                        for ($o = 0; $o < count($array_cat); $o++) {
                                                            $array_cat_new[$o] = $array_cat[$o];
                                                        }


                                                        $supercomposizione = "";

                                                        // recupero attributi prodotto

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


                                                            // controllo esistenza dell'attributo in magento
                                                            $stringQuery = "select tipo from " . $resource->getTableName('wsca_attributes') . " where id_ws='" . $id_attributo . "'";
                                                            $tipoAttributoMage = $readConnection->fetchOne($stringQuery);


                                                            $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);

                                                            /*if ($id_attributoMage == null) {
                                                                /*$model = Mage::getModel('eav/entity_setup', 'core_setup');
                                                                $input = "multiselect";

                                                                $data =
                                                                    array(
                                                                        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
                                                                        'group' => 'General',
                                                                        'type' => 'varchar',
                                                                        'backend' => '',
                                                                        'frontend' => '',
                                                                        'label' => ucfirst(strtolower($this->replace_accents($nome_attributo))),
                                                                        'input' => $input,
                                                                        'unique' => false,
                                                                        'required' => false,
                                                                        'is_configurable' => false,
                                                                        'searchable' => false,
                                                                        'visible_in_advanced_search' => false,
                                                                        'comparable' => false,
                                                                        'filterable' => false,
                                                                        'filterable_in_search' => false,
                                                                        'used_for_promo_rules' => false,
                                                                        'visible_on_front' => false,
                                                                        'used_in_product_listing' => false,
                                                                        'used_for_sort_by' => false,
                                                                        'user_defined' => true,

                                                                    );

                                                                $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                                                $model->addAttribute('catalog_product', $nome_attributoMage, $data);


                                                                $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                                                $optionTable = $setup->getTable('eav/attribute');

                                                                $id_attributoMage = $this->getLastInsertId($optionTable, 'attribute_id');

                                                                $query = "insert into " . $resource->getTableName('wsca_attributes') . " (id_magento,id_ws) values('" . $id_attributoMage . "','" . $id_attributo . "')";
                                                                $writeConnection->query($query);

                                                                } else {
                                                                    $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                                                }*/

                                                            if ($tipoAttributoMage == "select") {
                                                                // se l'attributo è il supercolore utilizzo anche l'attributo colore
                                                                if ($nome_attributo == "Supercolore") {
                                                                    $stringaIdAttributo = "";
                                                                    $stringaValoreAttributo = "";

                                                                    $j = 0;
                                                                    // costruisco il colore misto separando i colori con "/"
                                                                    foreach ($subattributes as $key => $value) {

                                                                        $id_valoreattributo = $key;
                                                                        $nome_valoreattributo = $value;


                                                                        if ($j != 0) {
                                                                            $stringaValoreAttributo .= "/";
                                                                            $stringaIdAttributo .= "/";
                                                                        }
                                                                        $stringaValoreAttributo .= $nome_valoreattributo;
                                                                        $stringaIdAttributo .= $id_valoreattributo;

                                                                        $j = $j + 1;

                                                                    }

                                                                    // controllo esistenza opzione in magento per l'attributo in questione
                                                                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subattributes') . " where id_ws='" . $stringaIdAttributo . "' and id_attributes='" . $id_attributo . "'";
                                                                    $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);
                                                                    if ($id_valoreattributoMage == null) {
                                                                        $attribute_model = Mage::getModel('eav/entity_attribute');
                                                                        $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                                        $attribute_code = $attribute_model->getIdByCode('catalog_product', $nome_attributoMage);
                                                                        $attribute = $attribute_model->load($attribute_code);

                                                                        $attribute->setData('option', array(
                                                                            'value' => array(
                                                                                'option' => array(ucfirst(strtolower($stringaValoreAttributo)), ucfirst(strtolower($stringaValoreAttributo)))
                                                                            )
                                                                        ));
                                                                        $attribute->save();

                                                                        $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                                                        $optionTable = $setup->getTable('eav/attribute_option');

                                                                        $id_valoreattributoMage = $this->getLastInsertId($optionTable, 'option_id');

                                                                        $query = "insert into " . $resource->getTableName('wsca_subattributes') . " (id_magento,id_ws, id_attributes) values('" . $id_valoreattributoMage . "','" . $stringaIdAttributo . "','" . $id_attributo . "')";
                                                                        $writeConnection->query($query);


                                                                        if ($j == 1) {
                                                                            // se il prodotto ha un solo colore, salvo questo colore anche nell'attributo "ca_colore" e salvo l'associazione nella tabella corrispondente
                                                                            $attribute_model = Mage::getModel('eav/entity_attribute');
                                                                            $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                                            $attribute_code = $attribute_model->getIdByCode('catalog_product', 'ca_colore');
                                                                            $attribute = $attribute_model->load($attribute_code);

                                                                            $attribute->setData('option', array(
                                                                                'value' => array(
                                                                                    'option' => array(ucfirst(strtolower($stringaValoreAttributo)), ucfirst(strtolower($stringaValoreAttributo)))
                                                                                )
                                                                            ));
                                                                            $attribute->save();


                                                                            $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                                                            $optionTable = $setup->getTable('eav/attribute_option');

                                                                            $id_valoreattributoMage = $this->getLastInsertId($optionTable, 'option_id');

                                                                            $query = "insert into " . $resource->getTableName('wsca_colore') . " (id_magento,id_ws,nome_magento) values('" . $id_valoreattributoMage . "','" . $stringaIdAttributo . "','" . ucfirst(strtolower($stringaValoreAttributo)) . "')";
                                                                            $writeConnection->query($query);


                                                                            $attribute_model = Mage::getModel('eav/entity_attribute');
                                                                            $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                                            $attribute_code = $attribute_model->getIdByCode('catalog_product', 'ca_filtraggio_colore');
                                                                            $attribute = $attribute_model->load($attribute_code);

                                                                            $attribute->setData('option', array(
                                                                                'value' => array(
                                                                                    'option' => array(ucfirst(strtolower($stringaValoreAttributo)), ucfirst(strtolower($stringaValoreAttributo)))
                                                                                )
                                                                            ));
                                                                            $attribute->save();


                                                                            $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                                                            $optionTable = $setup->getTable('eav/attribute_option');

                                                                            $id_valoreattributoMage = $this->getLastInsertId($optionTable, 'option_id');

                                                                            $query = "insert into " . $resource->getTableName('wsca_filtraggio_colore') . " (id_magento,id_ws) values('" . $id_valoreattributoMage . "','" . $stringaIdAttributo . "')";
                                                                            $writeConnection->query($query);
                                                                        } else {
                                                                            // se il prodotto ha più colori, salverò nell'attributo colore la dicitura Colori misti (se non esiste già)
                                                                            $stringQuery = "select id_magento from " . $resource->getTableName('wsca_colore') . " where nome_magento='Colori misti'";
                                                                            $id_coloriMisti = $readConnection->fetchOne($stringQuery);

                                                                            if ($id_coloriMisti == null) {
                                                                                // Colori misti non esiste
                                                                                $attribute_model = Mage::getModel('eav/entity_attribute');
                                                                                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                                                $attribute_code = $attribute_model->getIdByCode('catalog_product', 'ca_colore');
                                                                                $attribute = $attribute_model->load($attribute_code);

                                                                                $attribute->setData('option', array(
                                                                                    'value' => array(
                                                                                        'option' => array("Colori misti", "Colori misti")
                                                                                    )
                                                                                ));
                                                                                $attribute->save();


                                                                                $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                                                                $optionTable = $setup->getTable('eav/attribute_option');

                                                                                $id_coloriMistiMage = $this->getLastInsertId($optionTable, 'option_id');

                                                                                $query = "insert into " . $resource->getTableName('wsca_colore') . " (id_magento,id_ws,nome_magento) values('" . $id_coloriMistiMage . "','0','Colori misti')";
                                                                                $writeConnection->query($query);
                                                                            }


                                                                            // se il prodotto ha più colori, salverò nell'attributo filtraggio_colore tutti i colori presenti (se non esiste già)
                                                                            $filtraggio_colori = explode("/", $stringaIdAttributo);
                                                                            $filtraggio_coloriName = explode("/", $stringaValoreAttributo);
                                                                            for ($u = 0; $u < count($filtraggio_colori); $u++) {
                                                                                $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='" . $filtraggio_colori[$u] . "'";
                                                                                $id_filtraggioColoreMage = $readConnection->fetchOne($stringQuery);

                                                                                if ($id_filtraggioColoreMage == null) {
                                                                                    // Colori misti non esiste
                                                                                    $attribute_model = Mage::getModel('eav/entity_attribute');
                                                                                    $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                                                    $attribute_code = $attribute_model->getIdByCode('catalog_product', 'ca_filtraggio_colore');
                                                                                    $attribute = $attribute_model->load($attribute_code);

                                                                                    $attribute->setData('option', array(
                                                                                        'value' => array(
                                                                                            'option' => array($filtraggio_coloriName[$u], $filtraggio_coloriName[$u])
                                                                                        )
                                                                                    ));
                                                                                    $attribute->save();


                                                                                    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                                                                    $optionTable = $setup->getTable('eav/attribute_option');

                                                                                    $id_filtraggioColoreMage = $this->getLastInsertId($optionTable, 'option_id');

                                                                                    $query = "insert into " . $resource->getTableName('wsca_filtraggio_colore') . " (id_magento,id_ws) values('" . $id_filtraggioColoreMage . "','" . $filtraggio_colori[$u] . "')";
                                                                                    $writeConnection->query($query);
                                                                                }
                                                                            }
                                                                        }


                                                                    } else {
                                                                        $attr = Mage::getModel('catalog/product')->getResource()->getAttribute($nome_attributoMage);
                                                                        if ($attr->usesSource()) {
                                                                            $nomeSuperColoreMage = $attr->getSource()->getOptionText($id_valoreattributoMage);
                                                                            if (strtolower($nomeSuperColoreMage) != strtolower($stringaValoreAttributo)) {
                                                                                $attribute_model = Mage::getModel('eav/entity_attribute');
                                                                                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                                                $attribute_code = $attribute_model->getIdByCode('catalog_product', $nome_attributoMage);
                                                                                $attribute = $attribute_model->load($attribute_code);

                                                                                // modifica della stagione su magento (nome dell'opzione)
                                                                                $data = array();
                                                                                $values = array(
                                                                                    $id_valoreattributoMage => array(
                                                                                        0 => ucfirst(strtolower($stringaValoreAttributo)),
                                                                                        1 => ucfirst(strtolower($stringaValoreAttributo))
                                                                                    ),

                                                                                );

                                                                                $data['option']['value'] = $values;
                                                                                $attribute->addData($data);


                                                                                $attribute->save();

                                                                                if ($j == 1) {
                                                                                    $attr = Mage::getModel('catalog/product')->getResource()->getAttribute("ca_colore");
                                                                                    if ($attr->usesSource()) {
                                                                                        $nomeColoreMage = $attr->getSource()->getOptionText($id_valoreattributoMage);
                                                                                        if (strtolower($nomeColoreMage) != strtolower($stringaValoreAttributo)) {
                                                                                            $attribute_model = Mage::getModel('eav/entity_attribute');
                                                                                            $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                                                            $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_colore");
                                                                                            $attribute = $attribute_model->load($attribute_code);

                                                                                            // modifica della stagione su magento (nome dell'opzione)
                                                                                            $data = array();
                                                                                            $values = array(
                                                                                                $id_valoreattributoMage => array(
                                                                                                    0 => ucfirst(strtolower($stringaValoreAttributo)),
                                                                                                    1 => ucfirst(strtolower($stringaValoreAttributo))
                                                                                                ),

                                                                                            );

                                                                                            $data['option']['value'] = $values;
                                                                                            $attribute->addData($data);


                                                                                            $attribute->save();
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }

                                                                        if ($j == 1) {
                                                                            $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='" . $stringaIdAttributo . "'";
                                                                            $id_filtraggio_coloreMage = $readConnection->fetchOne($stringQuery);

                                                                            if ($id_filtraggio_coloreMage == null) {
                                                                                $attribute_model = Mage::getModel('eav/entity_attribute');
                                                                                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                                                $attribute_code = $attribute_model->getIdByCode('catalog_product', 'ca_filtraggio_colore');
                                                                                $attribute = $attribute_model->load($attribute_code);

                                                                                $attribute->setData('option', array(
                                                                                    'value' => array(
                                                                                        'option' => array(ucfirst(strtolower($stringaValoreAttributo)), ucfirst(strtolower($stringaValoreAttributo)))
                                                                                    )
                                                                                ));
                                                                                $attribute->save();


                                                                                $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                                                                $optionTable = $setup->getTable('eav/attribute_option');

                                                                                $id_valoreattributoMage = $this->getLastInsertId($optionTable, 'option_id');

                                                                                $query = "insert into " . $resource->getTableName('wsca_filtraggio_colore') . " (id_magento,id_ws) values('" . $id_valoreattributoMage . "','" . $stringaIdAttributo . "')";
                                                                                $writeConnection->query($query);
                                                                            }
                                                                        } else {
                                                                            // se il prodotto ha più colori, salverò nell'attributo filtraggio_colore tutti i colori presenti (se non esiste già)
                                                                            $filtraggio_colori = explode("/", $stringaIdAttributo);
                                                                            $filtraggio_coloriName = explode("/", $stringaValoreAttributo);
                                                                            for ($u = 0; $u < count($filtraggio_colori); $u++) {
                                                                                $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='" . $filtraggio_colori[$u] . "'";
                                                                                $id_filtraggioColoreMage = $readConnection->fetchOne($stringQuery);

                                                                                if ($id_filtraggioColoreMage == null) {
                                                                                    // Colori misti non esiste
                                                                                    $attribute_model = Mage::getModel('eav/entity_attribute');
                                                                                    $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                                                    $attribute_code = $attribute_model->getIdByCode('catalog_product', 'ca_filtraggio_colore');
                                                                                    $attribute = $attribute_model->load($attribute_code);

                                                                                    $attribute->setData('option', array(
                                                                                        'value' => array(
                                                                                            'option' => array($filtraggio_coloriName[$u], $filtraggio_coloriName[$u])
                                                                                        )
                                                                                    ));
                                                                                    $attribute->save();


                                                                                    $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                                                                    $optionTable = $setup->getTable('eav/attribute_option');

                                                                                    $id_filtraggioColoreMage = $this->getLastInsertId($optionTable, 'option_id');

                                                                                    $query = "insert into " . $resource->getTableName('wsca_filtraggio_colore') . " (id_magento,id_ws) values('" . $id_filtraggioColoreMage . "','" . $filtraggio_colori[$u] . "')";
                                                                                    $writeConnection->query($query);
                                                                                }
                                                                            }
                                                                        }
                                                                    }

                                                                } else {

                                                                    foreach ($subattributes as $key => $value) {

                                                                        $id_valoreattributo = $key;
                                                                        $nome_valoreattributo = $value;


                                                                        // salvo la supercomposizione se c'è
                                                                        if ($nome_attributo == "Supercomposizione") {
                                                                            $supercomposizione = $nome_valoreattributo;
                                                                        }

                                                                        // controllo esistenza opzione in magento per l'attributo in questione
                                                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subattributes') . " where id_ws='" . $id_valoreattributo . "' and id_attributes='" . $id_attributo . "'";
                                                                        $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);
                                                                        if ($id_valoreattributoMage == null) {
                                                                            if ($nome_valoreattributo == "" || $nome_valoreattributo == null) {

                                                                            } else {

                                                                                $attribute_model = Mage::getModel('eav/entity_attribute');
                                                                                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                                                $attribute_code = $attribute_model->getIdByCode('catalog_product', $nome_attributoMage);
                                                                                $attribute = $attribute_model->load($attribute_code);

                                                                                $attribute->setData('option', array(
                                                                                    'value' => array(
                                                                                        'option' => array(ucfirst(strtolower($nome_valoreattributo)), ucfirst(strtolower($nome_valoreattributo)))
                                                                                    )
                                                                                ));
                                                                                $attribute->save();

                                                                                $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                                                                $optionTable = $setup->getTable('eav/attribute_option');

                                                                                $id_valoreattributoMage = $this->getLastInsertId($optionTable, 'option_id');

                                                                                $query = "insert into " . $resource->getTableName('wsca_subattributes') . " (id_magento,id_ws, id_attributes) values('" . $id_valoreattributoMage . "','" . $id_valoreattributo . "','" . $id_attributo . "')";
                                                                                $writeConnection->query($query);
                                                                            }
                                                                        } else {
                                                                            $attr = Mage::getModel('catalog/product')->getResource()->getAttribute($nome_attributoMage);
                                                                            if ($attr->usesSource()) {
                                                                                $nomeValoreAttributoMage = $attr->getSource()->getOptionText($id_valoreattributoMage);
                                                                                if (strtolower($nomeValoreAttributoMage) != strtolower($nome_valoreattributo)) {
                                                                                    $attribute_model = Mage::getModel('eav/entity_attribute');
                                                                                    $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                                                    $attribute_code = $attribute_model->getIdByCode('catalog_product', $nome_attributoMage);
                                                                                    $attribute = $attribute_model->load($attribute_code);

                                                                                    // modifica della stagione su magento (nome dell'opzione)
                                                                                    $data = array();
                                                                                    $values = array(
                                                                                        $id_valoreattributoMage => array(
                                                                                            0 => ucfirst(strtolower($nome_valoreattributo)),
                                                                                            1 => ucfirst(strtolower($nome_valoreattributo))
                                                                                        ),

                                                                                    );

                                                                                    $data['option']['value'] = $values;
                                                                                    $attribute->addData($data);


                                                                                    $attribute->save();
                                                                                }
                                                                            }
                                                                        }

                                                                    }

                                                                }
                                                            }

                                                        }

                                                        // recupero varianti
                                                        foreach ($varianti as $key => $value) {
                                                            $scalare = $key;
                                                            $misura = $value;

                                                            // controllo esistenza opzione in magento per l'attributo in questione
                                                            $stringQuery = "select id_magento from " . $resource->getTableName('wsca_misura') . " where LOWER(misura)='" . strtolower($misura) . "'";
                                                            $id_misuraMage = $readConnection->fetchOne($stringQuery);
                                                            if ($id_misuraMage == null) {
                                                                $attribute_model = Mage::getModel('eav/entity_attribute');
                                                                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_misura");
                                                                $attribute = $attribute_model->load($attribute_code);

                                                                $attribute->setData('option', array(
                                                                    'value' => array(
                                                                        'option' => array(ucfirst(strtolower($misura)), ucfirst(strtolower($misura)))
                                                                    )
                                                                ));
                                                                $attribute->save();

                                                                $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
                                                                $optionTable = $setup->getTable('eav/attribute_option');

                                                                $id_misuraMage = $this->getLastInsertId($optionTable, 'option_id');

                                                                $query = "insert into " . $resource->getTableName('wsca_misura') . " (id_magento,misura) values('" . $id_misuraMage . "','" . strtolower($misura) . "')";
                                                                $writeConnection->query($query);
                                                            } else {
                                                                $attr = Mage::getModel('catalog/product')->getResource()->getAttribute("ca_misura");
                                                                if ($attr->usesSource()) {
                                                                    $nomeMisuraMage = $attr->getSource()->getOptionText($id_misuraMage);
                                                                    if (strtolower($nomeMisuraMage) != strtolower($misura)) {
                                                                        $attribute_model = Mage::getModel('eav/entity_attribute');
                                                                        $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                                                                        $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_misura");
                                                                        $attribute = $attribute_model->load($attribute_code);

                                                                        // modifica della stagione su magento (nome dell'opzione)
                                                                        $data = array();
                                                                        $values = array(
                                                                            $id_misuraMage => array(
                                                                                0 => ucfirst(strtolower($misura)),
                                                                                1 => ucfirst(strtolower($misura))
                                                                            ),

                                                                        );

                                                                        $data['option']['value'] = $values;
                                                                        $attribute->addData($data);


                                                                        $attribute->save();
                                                                    }
                                                                }
                                                            }


                                                            // inserimento delle coppie misura-scalare per ogni macro categoria,gruppo,sottogruppo,categoria e brand

                                                            $controllo = "select count(*) from " . $resource->getTableName('wsca_misura_scalare') . " where misura='" . $misura . "' and id_brand='" . $id_brand . "' and id_category='" . $id_sottocategoria3 . "' and id_subgroup='" . $id_sottocategoria2 . "' and id_group='" . $id_sottocategoria1 . "' and id_macro_category='" . $id_categoria . "'";
                                                            $countMisuraScalare = $readConnection->fetchOne($controllo);
                                                            if ($countMisuraScalare == 0) {
                                                                $query = "insert into " . $resource->getTableName('wsca_misura_scalare') . " (misura,scalare,id_category,id_subgroup,id_group,id_macro_category,id_brand) values('" . $misura . "','" . $scalare . "','" . $id_sottocategoria3 . "','" . $id_sottocategoria2 . "','" . $id_sottocategoria1 . "','" . $id_categoria . "','" . $id_brand . "')";
                                                                $writeConnection->query($query);
                                                            } else {
                                                                $queryScalare = "select scalare from " . $resource->getTableName('wsca_misura_scalare') . " where misura='" . $misura . "' and id_brand='" . $id_brand . "' and id_category='" . $id_sottocategoria3 . "' and id_subgroup='" . $id_sottocategoria2 . "' and id_group='" . $id_sottocategoria1 . "' and id_macro_category='" . $id_categoria . "'";
                                                                $scalareMage = $readConnection->fetchOne($queryScalare);
                                                                if (strtolower($scalareMage) != strtolower($scalare)) {
                                                                    $query = "update " . $resource->getTableName('wsca_misura_scalare') . "  set scalare='" . $scalare . "' where misura='" . $misura . "' and id_brand='" . $id_brand . "' and id_category='" . $id_sottocategoria3 . "' and id_subgroup='" . $id_sottocategoria2 . "' and id_group='" . $id_sottocategoria1 . "' and id_macro_category='" . $id_categoria . "'";
                                                                    $writeConnection->query($query);
                                                                }
                                                            }


                                                            // inserimento prodotto semplice
                                                            $sku_configurabile = $id;
                                                            $sku_semplice = $id . "-" . strtolower($misura);
                                                            if ($id_sottocategoria3 != null) {
                                                                $nome_semplice = ucfirst(strtolower($nomeBrandMage . " " . $nome . " " . $misura));
                                                                $url_key_semplice = $nomeSottocategoria3Mage . "-" . $nome_brand . "-" . $sku_semplice;
                                                            } else {
                                                                $nome_semplice = ucfirst(strtolower($nomeBrandMage . " " . $nome . " " . $misura));
                                                                $url_key_semplice = $nomeSottocategoria2Mage . "-" . $nome_brand . "-" . $sku_semplice;
                                                            }


                                                            $array_sku[$countP] = $sku_semplice; //inserisco in un array tutti gli sku inseriti
                                                            $countP = $countP + 1;

                                                            $productSimple = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_semplice);
                                                            if (!$productSimple) {
                                                                $productSimple = Mage::getModel('catalog/product');
                                                                $productSimple->setSku($sku_semplice);
                                                                $productSimple->setName($nome_semplice);
                                                                $productSimple->setDescription(ucfirst(strtolower($descrizione)));
                                                                $productSimple->setShortDescription(ucfirst(strtolower($descrizione)));
                                                                $productSimple->setPrice($prezzo);
                                                                $productSimple->setTypeId('simple');
                                                                $productSimple->setAttributeSetId(4);
                                                                $productSimple->setCategoryIds($array_cat_new);
                                                                $productSimple->setWeight(1);
                                                                $productSimple->setTaxClassId(2);
                                                                $productSimple->setVisibility(1);
                                                                $productSimple->setStatus(1);
                                                                $stockData = $productSimple->getStockData();
                                                                $stockData['qty'] = 0;
                                                                $stockData['is_in_stock'] = 0;
                                                                $productSimple->setStockData($stockData);
                                                                $productSimple->setWebsiteIds(array(1, 2, 3));
                                                                $productSimple->setUrlKey($url_key_semplice);
                                                                $productSimple->setData('ca_name', $nome);
                                                                $productSimple->setData('ca_brand', $id_brandMage);
                                                                $productSimple->setData('ca_anno', $id_annoMage);
                                                                $productSimple->setData('ca_stagione', $id_stagioneMage);
                                                                $productSimple->setData('ca_misura', $id_misuraMage);
                                                                $productSimple->setData('ca_scalare', $scalare);

                                                                $queryCarryOver = "select id_brand from " . $resource->getTableName('prodotti_carryover') . " where id_prodotto='" . $sku_configurabile . "' and id_brand='" . $id_brandMage . "'";
                                                                $carryOver = $readConnection->fetchOne($queryCarryOver);
                                                                if ($carryOver == null) {
                                                                    $productSimple->setData('ca_carryover', 2503);
                                                                } else {
                                                                    $productSimple->setData('ca_carryover', 2502);
                                                                }
                                                                $productSimple->setData('ca_codice_colore_fornitore', $codice_colore_fornitore);
                                                                $productSimple->setData('ca_codice_produttore', $codice_produttore);

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

                                                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subattributes') . " where id_ws='" . $id_valoreattributo . "' and id_attributes='" . $id_attributo . "'";
                                                                        $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                                                        $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                                                        $productSimple->setData($nome_attributoMage, $id_valoreattributoMage);

                                                                        if ($j == 1) {

                                                                            foreach ($subattributes as $key => $value) {
                                                                                $id_valoreattributo = $key;
                                                                            }

                                                                            $stringQuery = "select id_magento from " . $resource->getTableName('wsca_colore') . " where id_ws='" . $id_valoreattributo . "'";
                                                                            $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                                                            $nome_attributoMage = "ca_colore";
                                                                            $productSimple->setData($nome_attributoMage, $id_valoreattributoMage);

                                                                            // recupero nome colore
                                                                            $stringQuery = "select nome_magento from " . $resource->getTableName('wsca_colore') . " where id_ws='" . $id_valoreattributo . "'";
                                                                            $nome_colore = $readConnection->fetchOne($stringQuery);

                                                                            $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='" . $id_valoreattributo . "'";
                                                                            $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                                                            $nome_attributoMage = "ca_filtraggio_colore";
                                                                            $productSimple->setData($nome_attributoMage, $id_valoreattributoMage);

                                                                        } else {
                                                                            $stringQuery = "select id_magento from " . $resource->getTableName('wsca_colore') . " where nome_magento='Colori misti'";
                                                                            $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                                                            $nome_attributoMage = "ca_colore";
                                                                            $productSimple->setData($nome_attributoMage, $id_valoreattributoMage);

                                                                            $nome_colore = "misto";

                                                                            $filtraggioColoriArray = explode("/", $id_valoreattributo);
                                                                            $stringa_valori = "";
                                                                            for ($u = 0; $u < count($filtraggioColoriArray); $u++) {
                                                                                $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='" . $filtraggioColoriArray[$u] . "'";
                                                                                $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);
                                                                                if ($u != 0) {
                                                                                    $stringa_valori .= ",";
                                                                                }
                                                                                $stringa_valori .= $id_valoreattributoMage;

                                                                            }

                                                                            $nome_attributoMage = "ca_filtraggio_colore";
                                                                            $productSimple->setData($nome_attributoMage, $stringa_valori);
                                                                        }
                                                                    } else {
                                                                        $stringa_valori = "";
                                                                        $j = 0;

                                                                        $stringQuery = "select tipo from " . $resource->getTableName('wsca_attributes') . " where id_ws='" . $id_attributo . "'";
                                                                        $tipoAttributoMage = $readConnection->fetchOne($stringQuery);

                                                                        if ($tipoAttributoMage == "testo") {
                                                                            foreach ($subattributes as $key => $value) {

                                                                                $id_valoreattributo = $key;
                                                                                $nome_valoreattributo = $value;
                                                                            }


                                                                            $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                                                            $productSimple->setData($nome_attributoMage, $nome_valoreattributo);
                                                                        } else {
                                                                            foreach ($subattributes as $key => $value) {

                                                                                $id_valoreattributo = $key;
                                                                                $nome_valoreattributo = $value;

                                                                                if ($nome_valoreattributo == "" || $nome_valoreattributo == null) {

                                                                                } else {
                                                                                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subattributes') . " where id_ws='" . $id_valoreattributo . "' and id_attributes='" . $id_attributo . "'";
                                                                                    $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                                                                    if ($j != 0) {
                                                                                        $stringa_valori .= ",";
                                                                                    }
                                                                                    $stringa_valori .= $id_valoreattributoMage;
                                                                                    $j = $j + 1;
                                                                                }
                                                                            }

                                                                            if ($nome_valoreattributo == "" || $nome_valoreattributo == null) {
                                                                            } else {
                                                                                $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                                                                $productSimple->setData($nome_attributoMage, $stringa_valori);
                                                                            }
                                                                        }
                                                                    }

                                                                }

                                                                $stringa = "<reference name=\"head\"><action method=\"setRobots\"><value>NOINDEX,NOFOLLOW</value></action></reference>";
                                                                $productSimple->setData("custom_layout_update", $stringa);
                                                                $productSimple->save();
                                                            } else {

                                                                $attributes = $productSimple->getAttributes();
                                                                foreach ($attributes as $attribute) {
                                                                    $attributeCode = $attribute->getAttributeCode();
                                                                    if (substr($attributeCode, 0, 3) == "ca_") {
                                                                        $productSimple->setData($attributeCode, "");
                                                                    }
                                                                }
                                                                $productSimple->save();

                                                                $productSimple = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_semplice);
                                                                $idProductSimple = $productSimple->getId();
                                                                $productSimple->setName($nome_semplice);
                                                                $productSimple->setSku($sku_semplice);
                                                                $productSimple->setDescription(ucfirst(strtolower($descrizione)));
                                                                $productSimple->setShortDescription(ucfirst(strtolower($descrizione)));
                                                                $productSimple->setPrice($prezzo);
                                                                $productSimple->setCategoryIds($array_cat_new);
                                                                $productSimple->setUrlKey($url_key_semplice);
                                                                $productSimple->setData('ca_name', $nome);
                                                                $productSimple->setData('ca_brand', $id_brandMage);
                                                                $productSimple->setData('ca_anno', $id_annoMage);
                                                                $productSimple->setData('ca_stagione', $id_stagioneMage);
                                                                $productSimple->setData('ca_misura', $id_misuraMage);
                                                                $productSimple->setData('ca_scalare', $scalare);
                                                                $queryCarryOver = "select id_brand from " . $resource->getTableName('prodotti_carryover') . " where id_prodotto='" . $sku_configurabile . "' and id_brand='" . $id_brandMage . "'";
                                                                $carryOver = $readConnection->fetchOne($queryCarryOver);
                                                                if ($carryOver == null) {
                                                                    $productSimple->setData('ca_carryover', 2503);
                                                                } else {
                                                                    $productSimple->setData('ca_carryover', 2502);
                                                                }
                                                                $productSimple->setData('ca_codice_colore_fornitore', $codice_colore_fornitore);
                                                                $productSimple->setData('ca_codice_produttore', $codice_produttore);

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

                                                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subattributes') . " where id_ws='" . $id_valoreattributo . "' and id_attributes='" . $id_attributo . "'";
                                                                        $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                                                        $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                                                        $productSimple->setData($nome_attributoMage, $id_valoreattributoMage);

                                                                        if ($j == 1) {

                                                                            foreach ($subattributes as $key => $value) {
                                                                                $id_valoreattributo = $key;
                                                                            }

                                                                            $stringQuery = "select id_magento from " . $resource->getTableName('wsca_colore') . " where id_ws='" . $id_valoreattributo . "'";
                                                                            $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                                                            $nome_attributoMage = "ca_colore";
                                                                            $productSimple->setData($nome_attributoMage, $id_valoreattributoMage);

                                                                            // recupero nome colore
                                                                            $stringQuery = "select nome_magento from " . $resource->getTableName('wsca_colore') . " where id_ws='" . $id_valoreattributo . "'";
                                                                            $nome_colore = $readConnection->fetchOne($stringQuery);

                                                                            $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='" . $id_valoreattributo . "'";
                                                                            $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                                                            $nome_attributoMage = "ca_filtraggio_colore";
                                                                            $productSimple->setData($nome_attributoMage, $id_valoreattributoMage);

                                                                        } else {
                                                                            $stringQuery = "select id_magento from " . $resource->getTableName('wsca_colore') . " where nome_magento='Colori misti'";
                                                                            $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                                                            $nome_attributoMage = "ca_colore";
                                                                            $productSimple->setData($nome_attributoMage, $id_valoreattributoMage);

                                                                            $nome_colore = "misto";

                                                                            $filtraggioColoriArray = explode("/", $id_valoreattributo);
                                                                            $stringa_valori = "";
                                                                            for ($u = 0; $u < count($filtraggioColoriArray); $u++) {
                                                                                $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='" . $filtraggioColoriArray[$u] . "'";
                                                                                $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);
                                                                                if ($u != 0) {
                                                                                    $stringa_valori .= ",";
                                                                                }
                                                                                $stringa_valori .= $id_valoreattributoMage;

                                                                            }

                                                                            $nome_attributoMage = "ca_filtraggio_colore";
                                                                            $productSimple->setData($nome_attributoMage, $stringa_valori);
                                                                        }
                                                                    } else {
                                                                        $stringa_valori = "";
                                                                        $j = 0;
                                                                        $stringQuery = "select tipo from " . $resource->getTableName('wsca_attributes') . " where id_ws='" . $id_attributo . "'";
                                                                        $tipoAttributoMage = $readConnection->fetchOne($stringQuery);

                                                                        if ($tipoAttributoMage == "testo") {
                                                                            foreach ($subattributes as $key => $value) {

                                                                                $id_valoreattributo = $key;
                                                                                $nome_valoreattributo = $value;
                                                                            }


                                                                            $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                                                            $productSimple->setData($nome_attributoMage, $nome_valoreattributo);
                                                                        } else {
                                                                            foreach ($subattributes as $key => $value) {

                                                                                $id_valoreattributo = $key;
                                                                                $nome_valoreattributo = $value;

                                                                                if ($nome_valoreattributo == "" || $nome_valoreattributo == null) {

                                                                                } else {

                                                                                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subattributes') . " where id_ws='" . $id_valoreattributo . "' and id_attributes='" . $id_attributo . "'";
                                                                                    $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                                                                    if ($j != 0) {
                                                                                        $stringa_valori .= ",";
                                                                                    }
                                                                                    $stringa_valori .= $id_valoreattributoMage;

                                                                                    $j = $j + 1;
                                                                                }
                                                                            }

                                                                            if ($nome_valoreattributo == "" || $nome_valoreattributo == null) {
                                                                            } else {
                                                                                $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                                                                $productSimple->setData($nome_attributoMage, $stringa_valori);
                                                                            }
                                                                        }
                                                                    }

                                                                }


                                                                $productSimple->save();


                                                                $stockItem = Mage::getModel('cataloginventory/stock_item')->loadByProduct($idProductSimple);
                                                                $idProdottoConfigurabile = Mage::getResourceSingleton('catalog/product_type_configurable')->getParentIdsByChild($idProductSimple);
                                                                $prodottoConfigurabile = Mage::getModel('catalog/product')->load($idProdottoConfigurabile[0]);
                                                                $quantita = $stockItem->getQty();
                                                                if ($quantita > 0 && $prodottoConfigurabile->getSmallImage() != null && $prodottoConfigurabile->getSmallImage() != "no_selection") {
                                                                    $stockItem->setData('is_in_stock', 1);
                                                                } else {
                                                                    $stockItem->setData('is_in_stock', 0);
                                                                }
                                                                $stockItem->save();

                                                                $qtaTot = $qtaTot + $quantita;
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


                                                        // meta prodotto configurablile
                                                        if ($id_sottocategoria3 != null) {
                                                            $numero = rand(0, 2);
                                                            if ($numero == 0) {
                                                                $stringa = "Acquista";
                                                            }
                                                            if ($numero == 1) {
                                                                $stringa = "Compra";
                                                            }
                                                            if ($numero == 2) {
                                                                $stringa = "Shop";
                                                            }

                                                            $title = ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria3)) . " da " . ucwords(strtolower($nome_categoria)) . " " . ucwords(strtolower($nome_colore));

                                                            $description = $stringa . " online su coltortiboutique.com: " . ucwords(strtolower($nome_brand)) . " " . strtolower($nome_sottocategoria3) . " da " . strtolower($nome_categoria) . " " . strtolower($nome_colore) . " della stagione " . strtolower($nome_stagione) . ". Spedizione express e reso garantito";

                                                            $keyword1 = $title;
                                                            $keyword2 = ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria3)) . " da " . ucwords(strtolower($nome_categoria));
                                                            $keyword3 = "Shop online " . ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria3)) . " da " . ucwords(strtolower($nome_categoria));

                                                            $keywords = $keyword1 . ", " . $keyword2 . ", " . $keyword3;


                                                        } else {
                                                            $numero = rand(0, 2);
                                                            if ($numero == 0) {
                                                                $stringa = "Acquista";
                                                            }
                                                            if ($numero == 1) {
                                                                $stringa = "Compra";
                                                            }
                                                            if ($numero == 2) {
                                                                $stringa = "Shop";
                                                            }

                                                            $title = ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria2)) . " da " . ucwords(strtolower($nome_categoria)) . " " . ucwords(strtolower($nome_colore));

                                                            $description = $stringa . " online su coltortiboutique.com: " . ucwords(strtolower($nome_brand)) . " " . strtolower($nome_sottocategoria2) . " da " . strtolower($nome_categoria) . " " . strtolower($nome_colore) . " della stagione " . strtolower($nome_stagione) . ". Spedizione express e reso garantito";

                                                            $keyword1 = $title;
                                                            $keyword2 = ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria2)) . " da " . ucwords(strtolower($nome_categoria));
                                                            $keyword3 = "Shop online " . ucwords(strtolower($nome_brand)) . " " . ucwords(strtolower($nome_sottocategoria2)) . " da " . ucwords(strtolower($nome_categoria));

                                                            $keywords = $keyword1 . ", " . $keyword2 . ", " . $keyword3;
                                                        }



                                                            $productConfigurable = Mage::getModel('catalog/product');
                                                            $productConfigurable->setSku($sku_configurabile);
                                                            $productConfigurable->setName($nome_configurabile);
                                                            $productConfigurable->setDescription(ucfirst(strtolower($descrizione)));
                                                            $productConfigurable->setShortDescription(ucfirst(strtolower($descrizione)));
                                                            $productConfigurable->setPrice($prezzo);
                                                            $productConfigurable->setTypeId('configurable');
                                                            $productConfigurable->setAttributeSetId(4);
                                                            $productConfigurable->setCategoryIds($array_cat_new);
                                                            $productConfigurable->setWeight(1);
                                                            $productConfigurable->setTaxClassId(2);
                                                            $productConfigurable->setMetaKeyword($keywords);
                                                            $productConfigurable->setMetaDescription($description);
                                                            $productConfigurable->setMetaTitle($title);

                                                            // controllo brand non vendibili. Se non vendibili li metto a non visibile individualmente
                                                            if (($id_brandMage==2352 ||
                                                                    $id_brandMage==2510 ||
                                                                    $id_brandMage==1069 ||
                                                                    $id_brandMage==2604 ||
                                                                    $id_brandMage==1083 ||
                                                                    $id_brandMage==982 ||
                                                                    $id_brandMage==2369 ||
                                                                    $id_brandMage==933 ||
                                                                    $id_brandMage==975 ||
                                                                    $id_brandMage==2568 ||
                                                                    $id_brandMage==2548 ||
                                                                    $id_brandMage==948 ||
                                                                    $id_brandMage==2348 ||
                                                                    $id_brandMage==2509 ||
                                                                    $id_brandMage==2474 ||
                                                                    $id_brandMage==2570 ||
                                                                    $id_brandMage==985 ||
                                                                    $id_brandMage==2480 ||
                                                                    $id_brandMage==1084 ||
                                                                    $id_brandMage==993 ||
                                                                    $id_brandMage==1070 ||
                                                                    $id_brandMage==945 ||
                                                                    $id_brandMage==2481 ||
                                                                    $id_brandMage==939 ||
                                                                    $id_brandMage==1080 ||
                                                                    $id_brandMage==955 ||
                                                                    $id_brandMage==1030 ||
                                                                    $id_brandMage==1085 ||
                                                                    $id_brandMage==958 ||
                                                                    $id_brandMage==1078 ||
                                                                    $id_brandMage==1079 ||
                                                                    $id_brandMage==949 ||
                                                                    $id_brandMage==947 ||
                                                                    $id_brandMage==951 ||
                                                                    $id_brandMage==2350 ||
                                                                    $id_brandMage==984 ||
                                                                    $id_brandMage==959 ||
                                                                    $id_brandMage==1019 ||
                                                                    $id_brandMage==981 ||
                                                                    $id_brandMage==1087 ||
                                                                    $id_brandMage==2557 ||
                                                                    $id_brandMage==1086 ||
                                                                    $id_brandMage==1018 ||
                                                                    $id_brandMage==2565 ||
                                                                    $id_brandMage==943 ||
                                                                    $id_brandMage==972 ||
                                                                    $id_brandMage==1073 ||
                                                                    $id_brandMage==1075 ||
                                                                    $id_brandMage==998 ||
                                                                    $id_brandMage==2351 ||
                                                                    $id_brandMage==2589 ||
                                                                    $id_brandMage==935 ||
                                                                    $id_brandMage==2518 ||
                                                                    $id_brandMage==950 ||
                                                                    $id_brandMage==1077 ||
                                                                    $id_brandMage==971 ||
                                                                    $id_brandMage==2363 ||
                                                                    $id_brandMage==1089 ||
                                                                    $id_brandMage==1038 ||
                                                                    $id_brandMage==1036 ||
                                                                    $id_brandMage==2501 ||
                                                                    $id_brandMage==940 ||
                                                                    $id_brandMage==2353 ||
                                                                    $id_brandMage==2477 ||
                                                                    $id_brandMage==1091 ||
                                                                    $id_brandMage==1095 ||
                                                                    $id_brandMage==2550 ||
                                                                    $id_brandMage==2580 ||
                                                                    $id_brandMage==2558 ||
                                                                    $id_brandMage==2567 ||
                                                                    $id_brandMage==2586 ||
                                                                    $id_brandMage==2611 ||
                                                                    $id_brandMage==2615 ||
                                                                    $id_brandMage==2616 ||
                                                                    $id_brandMage==2620 ||
                                                                    $id_brandMage==2621 ||
                                                                    $id_brandMage==2622 ||
                                                                    $id_brandMage==2623 ||


                                                                    (strtolower($nome_brand)=="mr & mrs italy") ||
                                                                    (strtolower($nome_brand)=="pierre louis mascia")) &&

                                                                ($id != "151405ABS000006-F0V1Z" &&
                                                                    $id != "151405ABS000006-F0KUR" &&
                                                                    $id != "151405ABS000006-F0V20" &&
                                                                    $id != "151405ABS000006-F0V21" &&
                                                                    $id != "151405ABS000007-F0V2N" &&
                                                                    $id != "151405ABS000007-F0KUR" &&
                                                                    $id != "151405ABS000007-F0V2M" &&
                                                                    $id != "151405ABS000007-F0L92" &&
                                                                    $id != "151405ABS000008-F0V1W" &&
                                                                    $id != "151405ABS000008-F0V1X" &&
                                                                    $id != "151405ABS000009-F0V8G" &&
                                                                    $id != "151405ABS000054-F0DVU" &&
                                                                    $id != "151405ABS000054-F0Z29" &&
                                                                    $id != "151405ABS000055-F0Y7W" &&
                                                                    $id != "151405ABS000056-F018C" &&
                                                                    $id != "151405ABS000056-F018B" &&
                                                                    $id != "151405ABS000057-F0GN2" &&
                                                                    $id != "151405ABS000057-F0F89" &&
                                                                    $id != "151405ABS000057-F0U52" &&
                                                                    $id != "151405ABS000057-F0W6Q" &&
                                                                    $id != "151405ABS000057-F0L17" &&
                                                                    $id != "151405ABS000057-F0V1A" &&
                                                                    $id != "151405ABS000057-F0A22" &&
                                                                    $id != "151405ABS000057-F0KUR" &&
                                                                    $id != "151405ABS000057-F0M8A" &&
                                                                    $id != "142405ABS000059-F0KUR" &&
                                                                    $id != "152405ABS000002	F0H42" &&
                                                                    $id != "152405ABS000003-F0GGC" &&
                                                                    $id != "152405ABS000004-F022Q" &&
                                                                    $id != "152405ABS000005-F034D" &&
                                                                    $id != "152405ABS000005-F034E" &&
                                                                    $id != "152405ABS000005-F034C" &&
                                                                    $id != "152405ABS000069-F0H46" &&
                                                                    $id != "152405ABS000069-F022E" &&
                                                                    $id != "152405ABS000069-F0KUR" &&
                                                                    $id != "152405ABS000069-F0NVJ" &&
                                                                    $id != "152405ABS000069-F016A" &&
                                                                    $id != "152405ABS000070-F0TMN" &&
                                                                    $id != "152405ABS000073-F0656" &&
                                                                    $id != "152405ABS000073-F065B" &&
                                                                    $id != "152405ABS000073-F0654" &&
                                                                    $id != "152405ABS000075-F0KUR" &&
                                                                    $id != "152405ABS000075-F065H" &&
                                                                    $id != "152405ABS000075-F065K" &&
                                                                    $id != "152405ABS000075-F065J" &&
                                                                    $id != "152405ABS000076-F0656" &&
                                                                    $id != "152405ABS000076-F0654" &&
                                                                    $id != "152405ABS000078-F0654" &&
                                                                    $id != "152405ABS000078-F0657" &&
                                                                    $id != "152405ABS000078-F0655" &&
                                                                    $id != "152405ABS000079-F0B1X" &&
                                                                    $id != "152405ABS000080-F065H" &&
                                                                    $id != "152405ABS000081-F066R" &&
                                                                    $id != "152405ABS000082-F0676" &&
                                                                    $id != "152405FBS000013-F0W4Q" &&
                                                                    $id != "152405FBS000030-F0R2A"
                                                                )

                                                            ) {

                                                                $productConfigurable->setVisibility(4);

                                                            } else {
                                                                $productConfigurable->setVisibility(1);
                                                            }





                                                            $productConfigurable->setStatus(1);
                                                            $stockData = $productConfigurable->getStockData();
                                                            $stockData['qty'] = 0;
                                                            $stockData['is_in_stock'] = 0;
                                                            $productConfigurable->setStockData($stockData);
                                                            $productConfigurable->setWebsiteIds(array(1, 2, 3));
                                                            $productConfigurable->setUrlKey($url_key_configurabile);

                                                            //inserimento immagini
                                                            for ($k = 0; $k < count($immagini_new); $k++) {
                                                                $image_location = $this->getDownloadImage("product", $immagini_new[$k][0], $sottoCat, $nome_brand, $nome_colore, $id);
                                                                if ($image_location != "") {
                                                                    if ($immagini_new[$k][1] == "3") {

                                                                        $ext = pathinfo($immagini_new[$k][0], PATHINFO_EXTENSION);
                                                                        $nome_fileSmall = $this->replace_accents($this->url_slug(strtolower($sottoCat))) . "_" . $this->replace_accents($this->url_slug(strtolower($nome_brand))) . "_" . $this->replace_accents($this->url_slug(strtolower($nome_colore))) . "_" . $this->replace_accents($this->url_slug(strtolower($id))) . "-3s." . $ext;
                                                                        $import_location = "./var/images";
                                                                        $file_targetSmall = $import_location . "/" . $nome_fileSmall;
                                                                        $img = new Imagick($image_location);
                                                                        $img->thumbnailImage(900 , 0);
                                                                        $img->setOption('jpeg:extent', '150kb');
                                                                        $img->writeImage($file_targetSmall);
                                                                        $productConfigurable->addImageToMediaGallery($file_targetSmall, array('small_image'), false, false);


                                                                        $nome_fileThumb = $this->replace_accents($this->url_slug(strtolower($sottoCat))) . "_" . $this->replace_accents($this->url_slug(strtolower($nome_brand))) . "_" . $this->replace_accents($this->url_slug(strtolower($nome_colore))) . "_" . $this->replace_accents($this->url_slug(strtolower($id))) . "-3t." . $ext;
                                                                        $import_location = "./var/images";
                                                                        $file_targetThumb = $import_location . "/" . $nome_fileThumb;
                                                                        $img = new Imagick($image_location);
                                                                        $img->thumbnailImage(300 , 0);
                                                                        $img->setOption('jpeg:extent', '150kb');
                                                                        $img->writeImage($file_targetThumb);
                                                                        $productConfigurable->addImageToMediaGallery($file_targetThumb, array('thumbnail'), false, false);


                                                                        $productConfigurable->addImageToMediaGallery($image_location, array('image'), false, false);

                                                                    } else if ($immagini_new[$k][1] == "1") {


                                                                    } else if ($immagini_new[$k][1] == "2") {


                                                                    } else if ($immagini_new[$k][1] == "4") {
                                                                        $ext = pathinfo($immagini_new[$k][0], PATHINFO_EXTENSION);
                                                                        $nome_fileSmall = $this->replace_accents($this->url_slug(strtolower($sottoCat))) . "_" . $this->replace_accents($this->url_slug(strtolower($nome_brand))) . "_" . $this->replace_accents($this->url_slug(strtolower($nome_colore))) . "_" . $this->replace_accents($this->url_slug(strtolower($id))) . "-4s." . $ext;
                                                                        $import_location = "./var/images";
                                                                        $file_targetSmall = $import_location . "/" . $nome_fileSmall;
                                                                        $img = new Imagick($image_location);
                                                                        $img->thumbnailImage(900 , 0);
                                                                        $img->setOption('jpeg:extent', '150kb');
                                                                        $img->writeImage($file_targetSmall);
                                                                        $productConfigurable->addImageToMediaGallery($file_targetSmall, array(''), false, false);

                                                                        $productConfigurable->addImageToMediaGallery($image_location, array(''), false, false);

                                                                    } else {
                                                                        $productConfigurable->addImageToMediaGallery($image_location, array(""), false, false);

                                                                    }

                                                                }
                                                            }


                                                            $productConfigurable->setData('ca_name', $nome);
                                                            $productConfigurable->setData('ca_brand', $id_brandMage);
                                                            $productConfigurable->setData('ca_anno', $id_annoMage);
                                                            $productConfigurable->setData('ca_stagione', $id_stagioneMage);
                                                            $queryCarryOver = "select id_brand from " . $resource->getTableName('prodotti_carryover') . " where id_prodotto='" . $sku_configurabile . "' and id_brand='" . $id_brandMage . "'";
                                                            $carryOver = $readConnection->fetchOne($queryCarryOver);
                                                            if ($carryOver == null) {
                                                                $productConfigurable->setData('ca_carryover', 2503);
                                                            } else {
                                                                $productConfigurable->setData('ca_carryover', 2502);
                                                            }
                                                            $productConfigurable->setData('ca_codice_colore_fornitore', $codice_colore_fornitore);
                                                            $productConfigurable->setData('ca_codice_produttore', $codice_produttore);

                                                            // associazione attributi custom al prodotto configurabile
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

                                                                    $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subattributes') . " where id_ws='" . $id_valoreattributo . "' and id_attributes='" . $id_attributo . "'";
                                                                    $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                                                    $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                                                    $productConfigurable->setData($nome_attributoMage, $id_valoreattributoMage);

                                                                    if ($j == 1) {

                                                                        foreach ($subattributes as $key => $value) {
                                                                            $id_valoreattributo = $key;
                                                                        }

                                                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_colore') . " where id_ws='" . $id_valoreattributo . "'";
                                                                        $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                                                        $nome_attributoMage = "ca_colore";
                                                                        $productConfigurable->setData($nome_attributoMage, $id_valoreattributoMage);

                                                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='" . $id_valoreattributo . "'";
                                                                        $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                                                        $nome_attributoMage = "ca_filtraggio_colore";
                                                                        $productConfigurable->setData($nome_attributoMage, $id_valoreattributoMage);

                                                                    } else {
                                                                        $stringQuery = "select id_magento from " . $resource->getTableName('wsca_colore') . " where nome_magento='Colori misti'";
                                                                        $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                                                        $nome_attributoMage = "ca_colore";
                                                                        $productConfigurable->setData($nome_attributoMage, $id_valoreattributoMage);

                                                                        $filtraggioColoriArray = explode("/", $id_valoreattributo);
                                                                        $stringa_valori = "";
                                                                        for ($u = 0; $u < count($filtraggioColoriArray); $u++) {
                                                                            $stringQuery = "select id_magento from " . $resource->getTableName('wsca_filtraggio_colore') . " where id_ws='" . $filtraggioColoriArray[$u] . "'";
                                                                            $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);
                                                                            if ($u != 0) {
                                                                                $stringa_valori .= ",";
                                                                            }
                                                                            $stringa_valori .= $id_valoreattributoMage;

                                                                        }

                                                                        $nome_attributoMage = "ca_filtraggio_colore";
                                                                        $productConfigurable->setData($nome_attributoMage, $stringa_valori);
                                                                    }
                                                                } else {
                                                                    $stringa_valori = "";
                                                                    $j = 0;
                                                                    $stringQuery = "select tipo from " . $resource->getTableName('wsca_attributes') . " where id_ws='" . $id_attributo . "'";
                                                                    $tipoAttributoMage = $readConnection->fetchOne($stringQuery);

                                                                    if ($tipoAttributoMage == "testo") {
                                                                        foreach ($subattributes as $key => $value) {

                                                                            $id_valoreattributo = $key;
                                                                            $nome_valoreattributo = $value;
                                                                        }


                                                                        $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                                                        $productConfigurable->setData($nome_attributoMage, $nome_valoreattributo);
                                                                    } else {
                                                                        foreach ($subattributes as $key => $value) {

                                                                            $id_valoreattributo = $key;
                                                                            $nome_valoreattributo = $value;

                                                                            if ($nome_valoreattributo == "" || $nome_valoreattributo == null) {

                                                                            } else {

                                                                                $stringQuery = "select id_magento from " . $resource->getTableName('wsca_subattributes') . " where id_ws='" . $id_valoreattributo . "' and id_attributes='" . $id_attributo . "'";
                                                                                $id_valoreattributoMage = $readConnection->fetchOne($stringQuery);

                                                                                if ($j != 0) {
                                                                                    $stringa_valori .= ",";
                                                                                }
                                                                                $stringa_valori .= $id_valoreattributoMage;
                                                                                $j = $j + 1;
                                                                            }
                                                                        }

                                                                        if ($nome_valoreattributo == "" || $nome_valoreattributo == null) {

                                                                        } else {
                                                                            $nome_attributoMage = substr("ca_" . $id_attributo, 0, 30);
                                                                            $productConfigurable->setData($nome_attributoMage, $stringa_valori);
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                            Mage::helper('bubble_api/catalog_product')->associateProducts($productConfigurable, $array_sku, array(), array());


                                                            try {
                                                                $productConfigurable->save();
                                                            } catch (Exception $e) {
                                                                Mage::log($e->getMessage());
                                                            }


                                                            if (($id_brandMage!=2352 &&
                                                                    $id_brandMage!=2510 &&
                                                                    $id_brandMage!=1069 &&
                                                                    $id_brandMage!=2604 &&
                                                                    $id_brandMage!=1083 &&
                                                                    $id_brandMage!=982 &&
                                                                    $id_brandMage!=2369 &&
                                                                    $id_brandMage!=933 &&
                                                                    $id_brandMage!=975 &&
                                                                    $id_brandMage!=2568 &&
                                                                    $id_brandMage!=2548 &&
                                                                    $id_brandMage!=948 &&
                                                                    $id_brandMage!=2348 &&
                                                                    $id_brandMage!=2509 &&
                                                                    $id_brandMage!=2474 &&
                                                                    $id_brandMage!=2570 &&
                                                                    $id_brandMage!=985 &&
                                                                    $id_brandMage!=2480 &&
                                                                    $id_brandMage!=1084 &&
                                                                    $id_brandMage!=993 &&
                                                                    $id_brandMage!=1070 &&
                                                                    $id_brandMage!=945 &&
                                                                    $id_brandMage!=2481 &&
                                                                    $id_brandMage!=939 &&
                                                                    $id_brandMage!=1080 &&
                                                                    $id_brandMage!=955 &&
                                                                    $id_brandMage!=1030 &&
                                                                    $id_brandMage!=1085 &&
                                                                    $id_brandMage!=958 &&
                                                                    $id_brandMage!=1078 &&
                                                                    $id_brandMage!=1079 &&
                                                                    $id_brandMage!=949 &&
                                                                    $id_brandMage!=947 &&
                                                                    $id_brandMage!=951 &&
                                                                    $id_brandMage!=2350 &&
                                                                    $id_brandMage!=984 &&
                                                                    $id_brandMage!=959 &&
                                                                    $id_brandMage!=1019 &&
                                                                    $id_brandMage!=981 &&
                                                                    $id_brandMage!=1087 &&
                                                                    $id_brandMage!=2557 &&
                                                                    $id_brandMage!=1086 &&
                                                                    $id_brandMage!=1018 &&
                                                                    $id_brandMage!=2565 &&
                                                                    $id_brandMage!=943 &&
                                                                    $id_brandMage!=972 &&
                                                                    $id_brandMage!=1073 &&
                                                                    $id_brandMage!=1075 &&
                                                                    $id_brandMage!=998 &&
                                                                    $id_brandMage!=2351 &&
                                                                    $id_brandMage!=2589 &&
                                                                    $id_brandMage!=935 &&
                                                                    $id_brandMage!=2518 &&
                                                                    $id_brandMage!=950 &&
                                                                    $id_brandMage!=1077 &&
                                                                    $id_brandMage!=971 &&
                                                                    $id_brandMage!=2363 &&
                                                                    $id_brandMage!=1089 &&
                                                                    $id_brandMage!=1038 &&
                                                                    $id_brandMage!=1036 &&
                                                                    $id_brandMage!=2501 &&
                                                                    $id_brandMage!=940 &&
                                                                    $id_brandMage!=2353 &&
                                                                    $id_brandMage!=2477 &&
                                                                    $id_brandMage!=1091 &&
                                                                    $id_brandMage!=1095 &&
                                                                    $id_brandMage!=2550 &&
                                                                    $id_brandMage!=2580 &&
                                                                    $id_brandMage!=2558 &&
                                                                    $id_brandMage!=2567 &&
                                                                    $id_brandMage!=2586 &&
                                                                    $id_brandMage!=2611 &&
                                                                    $id_brandMage!=2615 &&
                                                                    $id_brandMage!=2616 &&
                                                                    $id_brandMage!=2620 &&
                                                                    $id_brandMage!=2621 &&
                                                                    $id_brandMage!=2622 &&
                                                                    $id_brandMage!=2623 &&

                                                                    (strtolower($nome_brand)!="mr & mrs italy") &&
                                                                    (strtolower($nome_brand)!="pierre louis mascia")) ||

                                                                ($id == "151405ABS000006-F0V1Z" ||
                                                                    $id == "151405ABS000006-F0KUR" ||
                                                                    $id == "151405ABS000006-F0V20" ||
                                                                    $id == "151405ABS000006-F0V21" ||
                                                                    $id == "151405ABS000007-F0V2N" ||
                                                                    $id == "151405ABS000007-F0KUR" ||
                                                                    $id == "151405ABS000007-F0V2M" ||
                                                                    $id == "151405ABS000007-F0L92" ||
                                                                    $id == "151405ABS000008-F0V1W" ||
                                                                    $id == "151405ABS000008-F0V1X" ||
                                                                    $id == "151405ABS000009-F0V8G" ||
                                                                    $id == "151405ABS000054-F0DVU" ||
                                                                    $id == "151405ABS000054-F0Z29" ||
                                                                    $id == "151405ABS000055-F0Y7W" ||
                                                                    $id == "151405ABS000056-F018C" ||
                                                                    $id == "151405ABS000056-F018B" ||
                                                                    $id == "151405ABS000057-F0GN2" ||
                                                                    $id == "151405ABS000057-F0F89" ||
                                                                    $id == "151405ABS000057-F0U52" ||
                                                                    $id == "151405ABS000057-F0W6Q" ||
                                                                    $id == "151405ABS000057-F0L17" ||
                                                                    $id == "151405ABS000057-F0V1A" ||
                                                                    $id == "151405ABS000057-F0A22" ||
                                                                    $id == "151405ABS000057-F0KUR" ||
                                                                    $id == "151405ABS000057-F0M8A" ||
                                                                    $id == "142405ABS000059-F0KUR" ||
                                                                    $id == "152405ABS000002	F0H42" ||
                                                                    $id == "152405ABS000003-F0GGC" ||
                                                                    $id == "152405ABS000004-F022Q" ||
                                                                    $id == "152405ABS000005-F034D" ||
                                                                    $id == "152405ABS000005-F034E" ||
                                                                    $id == "152405ABS000005-F034C" ||
                                                                    $id == "152405ABS000069-F0H46" ||
                                                                    $id == "152405ABS000069-F022E" ||
                                                                    $id == "152405ABS000069-F0KUR" ||
                                                                    $id == "152405ABS000069-F0NVJ" ||
                                                                    $id == "152405ABS000069-F016A" ||
                                                                    $id == "152405ABS000070-F0TMN" ||
                                                                    $id == "152405ABS000073-F0656" ||
                                                                    $id == "152405ABS000073-F065B" ||
                                                                    $id == "152405ABS000073-F0654" ||
                                                                    $id == "152405ABS000075-F0KUR" ||
                                                                    $id == "152405ABS000075-F065H" ||
                                                                    $id == "152405ABS000075-F065K" ||
                                                                    $id == "152405ABS000075-F065J" ||
                                                                    $id == "152405ABS000076-F0656" ||
                                                                    $id == "152405ABS000076-F0654" ||
                                                                    $id == "152405ABS000078-F0654" ||
                                                                    $id == "152405ABS000078-F0657" ||
                                                                    $id == "152405ABS000078-F0655" ||
                                                                    $id == "152405ABS000079-F0B1X" ||
                                                                    $id == "152405ABS000080-F065H" ||
                                                                    $id == "152405ABS000081-F066R" ||
                                                                    $id == "152405ABS000082-F0676" ||
                                                                    $id == "152405FBS000013-F0W4Q" ||
                                                                    $id == "152405FBS000030-F0R2A"
                                                                )

                                                            ) {


                                                                $query = "insert into " . $resource->getTableName('am_groupcat_product') . " (rule_id,product_id) values ('3','" . $productConfigurable->getId() . "')";
                                                                $writeConnection->query($query);

                                                            }


                                                            $productEng = Mage::getModel('catalog/product')->setStoreId(2)->load($productConfigurable->getId());
                                                            $nome_brandEng = $nomeBrandMage;
                                                            $id_coloreEng = $productEng->getData("ca_colore");
                                                            $id_stagioneEng = $productEng->getData("ca_stagione");

                                                            $entityType = Mage::getModel('eav/config')->getEntityType('catalog_product');
                                                            $attributeModel = Mage::getModel('eav/entity_attribute')->loadByCode($entityType, "ca_colore");
                                                            $_collection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                                                                ->setAttributeFilter($attributeModel->getId())
                                                                ->setStoreFilter(2)
                                                                ->load();

                                                            foreach ($_collection->toOptionArray() as $option) {
                                                                if ($option['value'] == $id_coloreEng) {
                                                                    $nome_coloreEng = $option['label'];
                                                                    break;
                                                                }
                                                            }

                                                            if ($nome_coloreEng == "Mixed colours") {
                                                                $nome_coloreEng = $productEng->getData("ca_codice_colore_fornitore");
                                                            }


                                                            $entityType = Mage::getModel('eav/config')->getEntityType('catalog_product');
                                                            $attributeModel = Mage::getModel('eav/entity_attribute')->loadByCode($entityType, "ca_stagione");
                                                            $_collection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                                                                ->setAttributeFilter($attributeModel->getId())
                                                                ->setStoreFilter(2)
                                                                ->load();

                                                            foreach ($_collection->toOptionArray() as $option) {
                                                                if ($option['value'] == $id_stagioneEng) {
                                                                    $nome_stagioneEng = $option['label'];
                                                                    break;
                                                                }
                                                            }

                                                            $category = $this->getLastCategoryEng($productEng);
                                                            $nome_sottocategoriaEng = $category->getName();

                                                            $parent = $category->getParentId();
                                                            while ($parent != "2") {
                                                                $id_categoria = $parent;
                                                                $category = Mage::getModel('catalog/category')->setStoreId(2)->load($parent);
                                                                $parent = $category->getParentId();
                                                            }

                                                            $category = Mage::getModel('catalog/category')->setStoreId(2)->load($id_categoria);
                                                            $nome_categoriaEng = $category->getName();


                                                            $stringa = "Shop";


                                                            $titleEng = ucwords(strtolower($nome_categoriaEng)) . " " . ucwords(strtolower($nome_brandEng)) . " " . ucwords(strtolower($nome_sottocategoriaEng)) . " " . ucwords(strtolower($nome_coloreEng));
                                                            $descriptionEng = $stringa . " online on coltortiboutique.com: " . ucwords(strtolower($nome_categoriaEng)) . " " . ucwords(strtolower($nome_brandEng)) . " " . strtolower($nome_sottocategoriaEng) . " " . strtolower($nome_coloreEng) . " of " . strtolower($nome_stagioneEng) . ". Guaranteed express delivery and returns";

                                                            $keyword1 = $titleEng;
                                                            $keyword2 = ucwords(strtolower($nome_categoriaEng)) . " " . ucwords(strtolower($nome_brandEng)) . " " . ucwords(strtolower($nome_sottocategoriaEng));
                                                            $keyword3 = "Shop online " . ucwords(strtolower($nome_categoriaEng)) . " " . ucwords(strtolower($nome_brandEng)) . " " . ucwords(strtolower($nome_sottocategoriaEng));

                                                            $keywordsEng = $keyword1 . ", " . $keyword2 . ", " . $keyword3;

                                                            $url_keyEng = strtolower($nome_sottocategoriaEng . "-" . $nome_brandEng . "-" . $sku_configurabile);

                                                            $nomeConfigurabileEng=ucfirst(strtolower($nome_brandEng . " " . $nomeEng));


                                                            $productEng->setName($nomeConfigurabileEng);
                                                            $productEng->setDescription(ucfirst(strtolower($descrizioneEng)));
                                                            $productEng->setShortDescription(ucfirst(strtolower($descrizioneEng)));
                                                            $productEng->setCaName($nomeEng);
                                                            $productEng->setMetaKeyword($keywordsEng);
                                                            $productEng->setMetaDescription($descriptionEng);
                                                            $productEng->setMetaTitle($titleEng);
                                                            $productEng->setUrlKey($url_keyEng);
                                                            $productEng->save();

                                                            $productUsa = Mage::getModel('catalog/product')->setStoreId(3)->load($productConfigurable->getId());
                                                            $productUsa->setName($nomeConfigurabileEng);
                                                            $productUsa->setDescription(ucfirst(strtolower($descrizioneEng)));
                                                            $productUsa->setShortDescription(ucfirst(strtolower($descrizioneEng)));
                                                            $productUsa->setCaName($nomeEng);
                                                            $productUsa->setMetaKeyword($keywordsEng);
                                                            $productUsa->setMetaDescription($descriptionEng);
                                                            $productUsa->setMetaTitle($titleEng);
                                                            $productUsa->setUrlKey($url_keyEng);
                                                            $productUsa->save();






                                                        // aggiornamento immagini
                                                        $product = Mage::getModel('catalog/product');
                                                        $product->load($product->getIdBySku($sku_configurabile));
                                                        $attributes = $product->getTypeInstance(true)->getSetAttributes($product);
                                                        $gallery = $attributes['media_gallery'];
                                                        $images = $product->getMediaGalleryImages();
                                                        foreach ($images as $image) {
                                                            $path = $image->getUrl();
                                                            $file = basename($path);

                                                            $punto = strrpos($file, ".");
                                                            $file_new = substr($file, 0, $punto);

                                                            $posizione = strrpos($file_new, "-"); // strrpos serve per trovare l'ultima occorrenza
                                                            $numero_img=substr($file_new,$posizione+1,strlen($file_new)-$posizione);



                                                            $carattere=substr($numero_img,1,1);
                                                            if ($carattere=="s" || $carattere=="t"){
                                                                $numero_img=substr($numero_img,0,2);
                                                            }
                                                            else {
                                                                $numero_img=substr($numero_img,0,1);
                                                            }



                                                            if ($numero_img == "3") {
                                                                $backend = $gallery->getBackend();
                                                                $backend->updateImage(
                                                                    $product,
                                                                    $image->getFile(),
                                                                    array('disabled' => 0, 'position' => "1")
                                                                );

                                                                $product->getResource()->saveAttribute($product, 'media_gallery');
                                                                $product->save();
                                                            } else if ($numero_img == "3s" || $numero_img == "3t") {
                                                                $backend = $gallery->getBackend();
                                                                $backend->updateImage(
                                                                    $product,
                                                                    $image->getFile(),
                                                                    array('label' => '','exclude' => 1, 'position' => 1)
                                                                );

                                                                $product->getResource()->saveAttribute($product, 'media_gallery');
                                                                $product->save();
                                                            } else if ($numero_img == "4s") {
                                                                $backend = $gallery->getBackend();
                                                                $backend->updateImage(
                                                                    $product,
                                                                    $image->getFile(),
                                                                    array('label' => 'back','position' => 2)
                                                                );

                                                                $product->getResource()->saveAttribute($product, 'media_gallery');
                                                                $product->save();
                                                            } else {
                                                                $backend = $gallery->getBackend();
                                                                $backend->updateImage(
                                                                    $product,
                                                                    $image->getFile(),
                                                                    array('disabled' => 0, 'position' => ($numero_img - 2))
                                                                );

                                                                $product->getResource()->saveAttribute($product, 'media_gallery');
                                                                $product->save();
                                                            }

                                                        }

                                                        // aggiornamento immagini store ENG
                                                        /*$productEng = Mage::getModel('catalog/product');
                                                        $productEng->setStoreId(2)->load($productEng->getIdBySku($sku_configurabile));
                                                        $attributes = $productEng->getTypeInstance(true)->getSetAttributes($productEng);
                                                        $gallery = $attributes['media_gallery'];
                                                        $images = $productEng->getMediaGalleryImages();
                                                        foreach ($images as $image) {
                                                            $path = $image->getUrl();
                                                            $file = basename($path);

                                                            $punto = strrpos($file, ".");
                                                            $file_new = substr($file, 0, $punto);

                                                            $posizione = strrpos($file_new, "-"); // strrpos serve per trovare l'ultima occorrenza
                                                            $numero_img=substr($file_new,$posizione+1,strlen($file_new)-$posizione);



                                                            $carattere=substr($numero_img,1,1);
                                                            if ($carattere=="s" || $carattere=="t"){
                                                                $numero_img=substr($numero_img,0,2);
                                                            }
                                                            else {
                                                                $numero_img=substr($numero_img,0,1);
                                                            }



                                                            if ($numero_img == "3") {
                                                                $backend = $gallery->getBackend();
                                                                $backend->updateImage(
                                                                    $productEng,
                                                                    $image->getFile(),
                                                                    array('disabled' => 0, 'position' => "1")
                                                                );

                                                                $productEng->getResource()->saveAttribute($productEng, 'media_gallery');
                                                                $productEng->save();
                                                            } else if ($numero_img == "3s" || $numero_img == "3t") {
                                                                $backend = $gallery->getBackend();
                                                                $backend->updateImage(
                                                                    $productEng,
                                                                    $image->getFile(),
                                                                    array('label' => '','exclude' => 1, 'position' => 1)
                                                                );

                                                                $productEng->getResource()->saveAttribute($productEng, 'media_gallery');
                                                                $productEng->save();
                                                            } else if ($numero_img == "4s") {
                                                                $backend = $gallery->getBackend();
                                                                $backend->updateImage(
                                                                    $productEng,
                                                                    $image->getFile(),
                                                                    array('label' => 'back','position' => 2)
                                                                );

                                                                $productEng->getResource()->saveAttribute($productEng, 'media_gallery');
                                                                $productEng->save();
                                                            } else {
                                                                $backend = $gallery->getBackend();
                                                                $backend->updateImage(
                                                                    $productEng,
                                                                    $image->getFile(),
                                                                    array('disabled' => 0, 'position' => ($numero_img - 2))
                                                                );

                                                                $productEng->getResource()->saveAttribute($productEng, 'media_gallery');
                                                                $productEng->save();
                                                            }

                                                        }


                                                        // aggiornamento immagini store USA
                                                        $productUsa = Mage::getModel('catalog/product');
                                                        $productUsa->setStoreId(3)->load($productUsa->getIdBySku($sku_configurabile));
                                                        $attributes = $productUsa->getTypeInstance(true)->getSetAttributes($productUsa);
                                                        $gallery = $attributes['media_gallery'];
                                                        $images = $productUsa->getMediaGalleryImages();
                                                        foreach ($images as $image) {
                                                            $path = $image->getUrl();
                                                            $file = basename($path);

                                                            $punto = strrpos($file, ".");
                                                            $file_new = substr($file, 0, $punto);

                                                            $posizione = strrpos($file_new, "-"); // strrpos serve per trovare l'ultima occorrenza
                                                            $numero_img=substr($file_new,$posizione+1,strlen($file_new)-$posizione);



                                                            $carattere=substr($numero_img,1,1);
                                                            if ($carattere=="s" || $carattere=="t"){
                                                                $numero_img=substr($numero_img,0,2);
                                                            }
                                                            else {
                                                                $numero_img=substr($numero_img,0,1);
                                                            }



                                                            if ($numero_img == "3") {
                                                                $backend = $gallery->getBackend();
                                                                $backend->updateImage(
                                                                    $productUsa,
                                                                    $image->getFile(),
                                                                    array('disabled' => 0, 'position' => "1")
                                                                );

                                                                $productUsa->getResource()->saveAttribute($productUsa, 'media_gallery');
                                                                $productUsa->save();
                                                            } else if ($numero_img == "3s" || $numero_img == "3t") {
                                                                $backend = $gallery->getBackend();
                                                                $backend->updateImage(
                                                                    $productUsa,
                                                                    $image->getFile(),
                                                                    array('label' => '','exclude' => 1, 'position' => 1)
                                                                );

                                                                $productUsa->getResource()->saveAttribute($productUsa, 'media_gallery');
                                                                $productUsa->save();
                                                            } else if ($numero_img == "4s") {
                                                                $backend = $gallery->getBackend();
                                                                $backend->updateImage(
                                                                    $productUsa,
                                                                    $image->getFile(),
                                                                    array('label' => 'back','position' => 2)
                                                                );

                                                                $productUsa->getResource()->saveAttribute($productUsa, 'media_gallery');
                                                                $productUsa->save();
                                                            } else {
                                                                $backend = $gallery->getBackend();
                                                                $backend->updateImage(
                                                                    $productUsa,
                                                                    $image->getFile(),
                                                                    array('disabled' => 0, 'position' => ($numero_img - 2))
                                                                );

                                                                $productUsa->getResource()->saveAttribute($productUsa, 'media_gallery');
                                                                $productUsa->save();
                                                            }

                                                        }*/

                                                        $countP = 0;
                                                        $array_sku = array("");


                                                    }
                                                }


                                            }

                                            $files = glob('./var/images/*.*');
                                            foreach ($files as $file) {
                                                unlink($file);
                                            }

                                            if ($p % 10 == 0) {
                                                Mage::log("BREAK", null, $logFileName);
                                                if ($p == $pagine) {
                                                    $finish = 1;
                                                } else {
                                                    $finish = 0;
                                                }


                                                // salvo la pagina in cui sono arrivato
                                                $query = "update " . $resource->getTableName('wsca_import_new_log') . " set page_number='" . $p . "',running='0',finish='" . $finish . "' where dataImport='" . $dataCorrente . "'";
                                                $writeConnection->query($query);

                                                $query2 = "truncate " . $resource->getTableName('index_event');
                                                $writeConnection->query($query2);

                                                $query3 = "truncate " . $resource->getTableName('index_process_event');
                                                $writeConnection->query($query3);

                                                $readConnection->closeConnection();
                                                $writeConnection->closeConnection();

                                                break;

                                                $p = $pagine;
                                            } else if ($p == $pagine || $pagine == 0) {
                                                Mage::log("FINITO", null, $logFileName);
                                                $finish = 1;


                                                // salvo la pagina in cui sono arrivato
                                                $query = "update " . $resource->getTableName('wsca_import_new_log') . " set page_number='" . $p . "',running='0',finish='" . $finish . "' where dataImport='" . $dataCorrente . "'";
                                                $writeConnection->query($query);

                                                $query2 = "truncate " . $resource->getTableName('index_event');
                                                $writeConnection->query($query2);

                                                $query3 = "truncate " . $resource->getTableName('index_process_event');
                                                $writeConnection->query($query3);

                                                $readConnection->closeConnection();
                                                $writeConnection->closeConnection();

                                                $pCollection = Mage::getSingleton('index/indexer')->getProcessesCollection();
                                                foreach ($pCollection as $process) {
                                                    $process->setMode(Mage_Index_Model_Process::MODE_REAL_TIME)->save();
                                                }

                                                Mage::app()->getCacheInstance()->flush();
                                                $indexCollection = Mage::getModel('index/process')->getCollection();
                                                foreach ($indexCollection as $index) {
                                                    $index->reindexAll();
                                                }

                                            }

                                        }

                                    }
                                }
                                Mage::log("FINE IMPORT", null, $logFileName);
                                if ($pagine == 0) {
                                    Mage::log("FINITO", null, $logFileName);
                                    $finish = 1;


                                    // salvo la pagina in cui sono arrivato
                                    $query = "update " . $resource->getTableName('wsca_import_new_log') . " set page_number='" . $p . "',running='0',finish='" . $finish . "' where dataImport='" . $dataCorrente . "'";
                                    $writeConnection->query($query);

                                    $query2 = "truncate " . $resource->getTableName('index_event');
                                    $writeConnection->query($query2);

                                    $query3 = "truncate " . $resource->getTableName('index_process_event');
                                    $writeConnection->query($query3);

                                    $readConnection->closeConnection();
                                    $writeConnection->closeConnection();

                                    $pCollection = Mage::getSingleton('index/indexer')->getProcessesCollection();
                                    foreach ($pCollection as $process) {
                                        $process->setMode(Mage_Index_Model_Process::MODE_REAL_TIME)->save();
                                    }

                                    /*Mage::app()->getCacheInstance()->flush();
                                    $indexCollection = Mage::getModel('index/process')->getCollection();
                                    foreach ($indexCollection as $index) {
                                        $index->reindexAll();
                                    }*/
                                }
                            }
                        }
                    }
                    else {
                        $page_number=$page_number-1;
                        $query = "update " . $resource->getTableName('wsca_import_new_log') . " set page_number='" . $page_number . "',running='0' where dataImport='" . $dataCorrente . "'";
                        $writeConnection->query($query);

                        $readConnection->closeConnection();
                        $writeConnection->closeConnection();
                    }
                }


            }
            catch (Exception $e){
                Mage::log("ERRORE ".$e->getMessage(), null, $logFileName);
            }
        }
        else {
            Mage::log("WS Import Catalogo: Parametri non specificati");
        }
    }


}