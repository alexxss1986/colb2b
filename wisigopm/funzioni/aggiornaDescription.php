<?php

function getLastCategory($product){
    $categoryModel = Mage::getModel( 'catalog/category' );

//Get Array of Category Id's with Last as First (Reversed)
    $_categories = array_reverse( $product->getCategoryIds() );

//Load Category
    $_category = $categoryModel->setStoreId(2)->load($_categories[0]);

    return $_category;
}

$i=0;
$array[$i]="152711DAB000003-B EME";
$i=$i+1;
$array[$i]="152711DAB000002-G EME"; $i=$i+1;
$array[$i]="152017DTS000001-950 0"; $i=$i+1;
$array[$i]="152017DTS000001-301 0"; $i=$i+1;
$array[$i]="152017DMA000009-950 0"; $i=$i+1;
$array[$i]="152017DMA000009-301 0"; $i=$i+1;
$array[$i]="152017DMA000008-950 0"; $i=$i+1;
$array[$i]="152017DMA000008-301 0"; $i=$i+1;
$array[$i]="152017DMA000007-103 0"; $i=$i+1;
$array[$i]="152017DMA000006-103 0"; $i=$i+1;
$array[$i]="152017DMA000005-950 0"; $i=$i+1;
$array[$i]="152017DMA000005-543 0"; $i=$i+1;
$array[$i]="152017DMA000005-249 0"; $i=$i+1;
$array[$i]="152017DMA000004-800 B"; $i=$i+1;
$array[$i]="152017DGN000001-950 0"; $i=$i+1;
$array[$i]="152017DGN000001-605 0"; $i=$i+1;
$array[$i]="152017DCD000005-950 0"; $i=$i+1;
$array[$i]="152017DCD000005-103 0"; $i=$i+1;
$array[$i]="152017DCD000001-950 0"; $i=$i+1;


require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        $username = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/username');
        $password = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/password');
        $service_url = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/endpoint');

        if (isset($username) && $username!="" && isset($password) && $password!="" && isset($service_url) && $service_url!="") {

            $filename = "catalogo2";
            $logFileName = $filename . '.log';



            try {

                $l = 1;
                for ($j=0; $j<count($array); $j++) {

                    Mage::log($array[$j], null, $logFileName);
                    $sku_configurabile=$array[$j];
                    $skuUrl = $array[$j];
                    $skuUrl = str_replace(" ", "%20", $skuUrl);
                    // controllo in che stato mi trovo dell'esecuzione dell'importazione
                    $dataCorrente = date('Y-m-d');
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

                        $headersGetEng = array(
                            'Authorization: Bearer ' . $aToken
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
                                $descrizione = null;

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


                        $productConfigurable = Mage::getModel('catalog/product')->loadByAttribute('sku', $array[$j]);
                        $productEng = Mage::getModel('catalog/product')->setStoreId(2)->load($productConfigurable->getId());
                        $nome_brandEng = $productEng->getAttributeText("ca_brand");
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


                        $category = getLastCategory($productEng);
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
                        $productEng->getResource()->saveAttribute($productEng, 'name');
                        $productEng->setDescription(ucfirst(strtolower($descrizioneEng)));
                        $productEng->getResource()->saveAttribute($productEng, 'description');
                        $productEng->setShortDescription(ucfirst(strtolower($descrizioneEng)));
                        $productEng->getResource()->saveAttribute($productEng, 'short_description');
                        $productEng->setCaName($nomeEng);
                        $productEng->getResource()->saveAttribute($productEng, 'ca_name');
                        $productEng->setMetaKeyword($keywordsEng);
                        $productEng->getResource()->saveAttribute($productEng, 'meta_keyword');
                        $productEng->setMetaDescription($descriptionEng);
                        $productEng->getResource()->saveAttribute($productEng, 'meta_description');
                        $productEng->setMetaTitle($titleEng);
                        $productEng->getResource()->saveAttribute($productEng, 'meta_title');
                        $productEng->setUrlKey($url_keyEng);
                        $productEng->getResource()->saveAttribute($productEng, 'url_key');

                        $productUsa = Mage::getModel('catalog/product')->setStoreId(3)->load($productConfigurable->getId());
                        $productUsa->setName($nomeConfigurabileEng);
                        $productUsa->getResource()->saveAttribute($productUsa, 'name');
                        $productUsa->setDescription(ucfirst(strtolower($descrizioneEng)));
                        $productUsa->getResource()->saveAttribute($productUsa, 'description');
                        $productUsa->setShortDescription(ucfirst(strtolower($descrizioneEng)));
                        $productUsa->getResource()->saveAttribute($productUsa, 'short_description');
                        $productUsa->setCaName($nomeEng);
                        $productUsa->getResource()->saveAttribute($productUsa, 'ca_name');
                        $productUsa->setMetaKeyword($keywordsEng);
                        $productUsa->getResource()->saveAttribute($productUsa, 'meta_keyword');
                        $productUsa->setMetaDescription($descriptionEng);
                        $productUsa->getResource()->saveAttribute($productUsa, 'meta_description');
                        $productUsa->setMetaTitle($titleEng);
                        $productUsa->getResource()->saveAttribute($productUsa, 'meta_title');
                        $productUsa->setUrlKey($url_keyEng);
                        $productUsa->getResource()->saveAttribute($productUsa, 'url_key');


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
