<?php
require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

    $resource = Mage::getSingleton('core/resource');
    $readConnection = $resource->getConnection('core_read');
    $writeConnection = $resource->getConnection('core_write');

    $username = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/username');
    $password = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/password');
    $service_url = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/endpoint');

$i=0;
$j=0;
    $z=0;
$arrP1=array("");
$arrP2=array("");
$arrP3=array("");

    if (isset($username) && $username != "" && isset($password) && $password != "" && isset($service_url) && $service_url != "") {

        $filename = "prodotti";
        $logFileName = $filename . '.log';



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

                    // recupero solo l'header della richiesta
                    $service_urlGet = $service_url . "/products?limit=100";
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
                        $page_number=1;

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
                                $service_urlGet = $service_url . "/products?limit=100&page=" . $p;

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


                                        }
                                        if (count($attributi) > 0 && count($immagini) > 0 && $id_brand != null && $id_season != null && $id_categoria != null && $id_sottocategoria1 != null && $id_sottocategoria2 != null && $prezzo != 0) {

                                        } else {
                                            if (count($attributi) == 0 || $id_brand == null || $id_season == null || $id_categoria == null || $id_sottocategoria1 == null || $id_sottocategoria2 == null || $prezzo == 0) {
                                                $arrP[$i][0]=$id;
                                                $i=$i+1;
                                            }
                                            if (count($immagini[1]) == 0) {
                                                $arrP2[$j][0]=$id;
                                                $j=$j+1;
                                            }
                                            else {
                                                $immagini_new = array();
                                                $controllo=false;
                                                for ($k = 0; $k < count($immagini[1]); $k++) {

                                                    // recupero il numero della foto
                                                    $punto = strrpos($immagini[1][$k], ".");
                                                    $file_new = substr($immagini[1][$k], 0, $punto);

                                                    // il numero dell'immagine
                                                    $numero_img = substr($file_new, strlen($file_new) - 1, 1);

                                                    if ($numero_img=="3"){
                                                        $controllo=true;
                                                        break;
                                                    }
                                                }

                                                if ($controllo==false){
                                                    $arrP3[$z][0]=$id;
                                                    $z=$z+1;
                                                }

                                            }
                                        }
                                    }
                                }
                            }

                        }
                    }
            }
        } catch (Exception $e) {
        }
    }

$fp = fopen("../../var/export/prodottiSenzaAttributi.csv", 'w');
foreach ($arrP as $dati) {
    fputcsv($fp, $dati);
}
fclose($fp);


$fp2 = fopen("../../var/export/prodottiSenzaImmagini.csv", 'w');
foreach ($arrP2 as $dati2) {
    fputcsv($fp2, $dati2);
}
fclose($fp2);


$fp3 = fopen("../../var/export/prodottiSenzaImmaginePrincipale.csv", 'w');
foreach ($arrP3 as $dati3) {
    fputcsv($fp3, $dati3);
}
fclose($fp3);