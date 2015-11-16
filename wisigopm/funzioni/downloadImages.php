<?php

require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

$username = "prova1";
$password = "prova1";
$service_url = "https://api.orderlink.it/v1";

$resource = Mage::getSingleton('core/resource');
$readConnection = $resource->getConnection('core_read');
$writeConnection = $resource->getConnection('core_write');

if (isset($username) && $username!="" && isset($password) && $password!="" && isset($service_url) && $service_url!="") {


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
        die('Errore nella richiesta. Informazioni addizionali: ' . var_export($infoPost));
    }
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
    $service_urlGet = $service_url . "/products?images=true&attributes=true&limit=100";
    $curlGet = curl_init($service_urlGet);
    curl_setopt($curlGet, CURLOPT_HTTPAUTH, CURLAUTH_ANY); // accetto ogni autenticazione. Il sitema utilizzerà la migliore
    curl_setopt($curlGet, CURLOPT_SSL_VERIFYPEER, false); // disabilito la verifica del certificato SSL; è necessario perchè altrimenti non esegue la chiamata rest
    curl_setopt($curlGet, CURLOPT_RETURNTRANSFER, true); // salvo l'output della richiesta in una variabile
    curl_setopt($curlGet, CURLOPT_HEADER, true);
    curl_setopt($curlGet, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curlGet , CURLOPT_NOBODY, true);
    curl_setopt($curlGet, CURLOPT_HTTPHEADER, $headersGet); // setto l'header della richiesta
    $header = curl_exec($curlGet);

    if ($header === false) {
        $infoGet = curl_getinfo($curlGet);
        curl_close($curlGet);
        die('Errore nella richiesta. Informazioni addizionali: ' . var_export($infoGet));
    }

    // costrusico un array dell'header e recupero il totale delle pagine
    $myarray=array();
    $data=explode("\n",$header);
    $myarray['status']=$data[0];
    array_shift($data);
    foreach($data as $part){
        $middle=explode(":",$part);
        if (isset($middle[1])){
            $myarray[trim($middle[0])] = trim($middle[1]);
        }
    }
    $pagine=$myarray["X-Count-Pages"];



    // effettuo una chiamata al web service per ogni pagina
    Mage::log("TOT PAGINE ".$pagine);
    for ($p=1; $p<=$pagine; $p++) {
        Mage::log("pagina ".$p);
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
            die('Errore nella richiesta. Informazioni addizionali: ' . var_export($infoPost));
        }
        curl_close($curlPost);
        $decodedPost = json_decode($curl_responsePost);


        if (is_object($decodedPost)) {
            $arrayPost = get_object_vars($decodedPost);
        }

        $aToken = $arrayPost["access_token"];


        $headersGet = array(
            'Authorization: Bearer ' . $aToken
        );
        $service_urlGet = $service_url . "/products?images=true&attributes=true&limit=100&page=".$p;
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
            die('Errore nella richiesta. Informazioni addizionali: ' . var_export($infoGet));
        }

        curl_close($curlGet);

        // parse del contenuto
        $decodedGet = json_decode($curl_responseGet);


        if (is_object($decodedGet)) {
            $decodedGet = get_object_vars($decodedGet);
        }

        $l=1;
        foreach ($decodedGet as $key => $value) {
            Mage::log($l);
            $l=$l+1;
            $id = $key;
            $valoreProdotti = $value;


            $immagini=null;

            foreach ($valoreProdotti as $key => $value) {
                // recupero campi prodotto

                if ($key=="images") {
                    $immagini = $value;
                    if (is_object($immagini)) {
                        $immagini = get_object_vars($immagini);
                    }
                }




            }
            if (count($immagini)>0) {


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




                $sku_configurabile = $id;


                    //inserimento immagini
                    for ($k = 0; $k < count($immagini_new); $k++) {
                        // recupero il numero della foto
                        $punto = strrpos($immagini_new[$k][0], ".");
                        $file_new = substr($immagini_new[$k][0], 0, $punto);
                        $numero_img = substr($file_new, strlen($file_new) - 1, 1);
                        if ($numero_img!="1" && $numero_img!="2") {
                            $image_location = getDownloadImage("product", $immagini_new[$k][0], $id);
                        }
                    }

            }


        }



    }

    Mage::log("FINE");
}
else {
    Mage::log("WS Import Catalogo: Parametri non specificati");
}


// Download Image
function  getDownloadImage($type,$file,$id){
    // estensione foto
    $ext = pathinfo($file, PATHINFO_EXTENSION);

    // recupero il numero della foto
    $punto = strrpos($file, ".");
    $file_new = substr($file, 0, $punto);

    // il numero dell'immagine
    $numero_img = substr($file_new, strlen($file_new) - 1, 1);

    $nome_file=$id."-".$numero_img.".".$ext;

    $import_location="../../var/images";

    $file_source = Mage::getStoreConfig('oscommerceimportconf/oscconfiguration/conf_imageurl',Mage::app()->getStore()).$file;
    $file_target = $import_location."/".$nome_file;

    $file_source=str_replace(" ","%20",$file_source);

    $file_path = "";
    if (($file != '') and (!file_exists($file_target))){
        $rh = fopen($file_source, 'rb');
        $wh = fopen($file_target, 'wb');
        if ($rh===false || $wh===false) {
            // error reading or opening file
            $file_path = "";
        }
        else {
            while (!feof($rh)) {
                if (fwrite($wh, fread($rh, 1024)) === FALSE) {
                    $file_path = $file_target;
                }
            }
        }
        fclose($rh);
        fclose($wh);
    }
    if (file_exists($file_target)){
        if ($type == 'category'){
            $file_path = $file;
        }else{
            $file_path = $file_target;
        }
    }

    return $file_path;
}




