<?php
require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
$writeConnection = $resource->getConnection('core_write');

$username = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/username');
$password = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/password');
$service_url = Mage::getStoreConfig('config_ws_sezione/gruppo_cliente/endpoint');



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
                    $service_urlGet = $service_url . "/products?id=151563NCX000006-BL%20GO&lang=en";

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


                        $count_prodotti = 0;

                        if (is_object($decodedGet)) {
                            $decodedGet = get_object_vars($decodedGet);
                        }

                        var_dump($decodedGet);
                    }
                }