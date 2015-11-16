<?php

function nomeValoreAttributo($id_attributo,$valore_opzione){
    $int_attr_id = $id_attributo; // or any given id.
    $int_attr_value = $valore_opzione; // or any given attribute value id.

    $attr = Mage::getModel('catalog/resource_eav_attribute')->load($int_attr_id);

    $value="";
    if ($attr->usesSource()) {
        $value = $attr->getSource()->getOptionText($int_attr_value);
    }

    return $value;
}

function getLastInsertId($tableName, $primaryKey)
{
    //SELECT MAX(id) FROM table
    $db = Mage::getModel('core/resource')->getConnection('core_read');
    $result = $db->raw_fetchRow("SELECT MAX(`{$primaryKey}`) as LastID FROM `{$tableName}`");
    return $result['LastID'];
}


session_cache_limiter('nocache');
session_start();
if (isset($_SESSION['username'])){
    if ($_SESSION['livello']==3) {
        if (isset($_REQUEST['nome']) &&
            isset($_REQUEST['descrizione']) &&
            isset($_REQUEST['sku']) &&
            isset($_REQUEST['id_prodotto']) &&
            isset($_REQUEST['brand']) &&
            isset($_REQUEST['stagione']) &&
            isset($_REQUEST['anno']) &&
            isset($_REQUEST['prezzo']) &&
            isset($_REQUEST['categoria']) &&
            isset($_REQUEST['sottocategoria1']) &&
            isset($_REQUEST['sottocategoria2']) &&
            isset($_SESSION['qta']) &&
            isset($_SESSION['taglie_s']) &&
            isset($_SESSION['scalari_s']))
        {

            include("percorsoMage.php");
            require_once "../".$MAGE;
            Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

            include("iva.php");

            $resource = Mage::getSingleton('core/resource');
            $readConnection = $resource->getConnection('core_read');
            $writeConnection = $resource->getConnection('core_write');

            $id_prodotto=$_REQUEST['id_prodotto'];
            $nome=$_REQUEST['nome'];
            $descrizione=$_REQUEST['descrizione'];
            $sku=$_REQUEST['sku'];
            $codice_colore=$_REQUEST['codice_colore'];
            $codice_produttore=$_REQUEST['codice_produttore'];
            $id_categoria=$_REQUEST['categoria'];
            $id_sottocategoria1=$_REQUEST['sottocategoria1'];
            $brand=$_REQUEST['brand'];
            $stagione=$_REQUEST['stagione'];
            $anno=$_REQUEST['anno'];
            $prezzo=$_REQUEST['prezzo'];

            $filtraggioColore=array("");
            if (isset($_REQUEST['filtraggio_colore'])){
                $filtraggioColore=$_REQUEST['filtraggio_colore'];
            }

            $colore="";
            if (isset($_REQUEST['colore'])){
                $colore=$_REQUEST['colore'];
            }

            $motivo=array("");
            if (isset($_REQUEST['motivo'])){
                $motivo=$_REQUEST['motivo'];
            }

            $supercomposizione=array("");
            if (isset($_REQUEST['supercomposizione'])){
                $supercomposizione=$_REQUEST['supercomposizione'];
            }

            $made_in=$_REQUEST['made_in'];
            $composizione=$_REQUEST['composizione'];

            $supercolore=array("");
            if (isset($_REQUEST['supercolore'])){
                $supercolore=$_REQUEST['supercolore'];
            }

            $tipborsadonna=array("");
            if (isset($_REQUEST['tipborsadonna'])){
                $tipborsadonna=$_REQUEST['tipborsadonna'];
            }

            $tipborsauomo=array("");
            if (isset($_REQUEST['tipborsauomo'])){
                $tipborsauomo=$_REQUEST['tipborsauomo'];
            }

            $dimensioni_borsa_lunghezza=array("");
            if (isset($_REQUEST['dimensioni_borsa_lunghezza'])){
                $dimensioni_borsa_lunghezza=$_REQUEST['dimensioni_borsa_lunghezza'];
            }

            $dimensioni_borsa_altezza="";
            if (isset($_REQUEST['dimensioni_borsa_altezza'])){
                $dimensioni_borsa_altezza=$_REQUEST['dimensioni_borsa_altezza'];
            }

            $dimensioni_borsa_profondita="";
            if (isset($_REQUEST['dimensioni_borsa_profondita'])){
                $dimensioni_borsa_profondita=$_REQUEST['dimensioni_borsa_profondita'];
            }

            $dimensioni_borsa_altezza_manico="";
            if (isset($_REQUEST['dimensioni_borsa_altezza_manico'])){
                $dimensioni_borsa_altezza_manico=$_REQUEST['dimensioni_borsa_altezza_manico'];
            }

            $dimensioni_borsa_lunghezza_tracolla="";
            if (isset($_REQUEST['dimensioni_borsa_lunghezza_tracolla'])){
                $dimensioni_borsa_lunghezza_tracolla=$_REQUEST['dimensioni_borsa_lunghezza_tracolla'];
            }

            $tipaccessoridonna=array("");
            if (isset($_REQUEST['tipaccessoridonna'])){
                $tipaccessoridonna=$_REQUEST['tipaccessoridonna'];
            }

            $tipaccessoriuomo=array("");
            if (isset($_REQUEST['tipaccessoriuomo'])){
                $tipaccessoriuomo=$_REQUEST['tipaccessoriuomo'];
            }

            $cintura_lunghezza="";
            if (isset($_REQUEST['cintura_lunghezza'])){
                $cintura_lunghezza=$_REQUEST['cintura_lunghezza'];
            }

            $cintura_altezza="";
            if (isset($_REQUEST['cintura_altezza'])){
                $cintura_altezza=$_REQUEST['cintura_altezza'];
            }

            $dimensioni_accessorio_lunghezza="";
            if (isset($_REQUEST['dimensioni_accessorio_lunghezza'])){
                $dimensioni_accessorio_lunghezza=$_REQUEST['dimensioni_accessorio_lunghezza'];
            }

            $dimensioni_accessorio_altezza="";
            if (isset($_REQUEST['dimensioni_accessorio_altezza'])){
                $dimensioni_accessorio_altezza=$_REQUEST['dimensioni_accessorio_altezza'];
            }

            $dimensioni_accessorio_profondita="";
            if (isset($_REQUEST['dimensioni_accessorio_profondita'])){
                $dimensioni_accessorio_profondita=$_REQUEST['dimensioni_accessorio_profondita'];
            }

            $tipcalzdonna=array("");
            if (isset($_REQUEST['tipcalzdonna'])){
                $tipcalzdonna=$_REQUEST['tipcalzdonna'];
            }

            $tipcalzuomo=array("");
            if (isset($_REQUEST['tipcalzuomo'])){
                $tipcalzuomo=$_REQUEST['tipcalzuomo'];
            }

            $dimensioni_calzatura_altezza_tacco="";
            if (isset($_REQUEST['dimensioni_calzatura_altezza_tacco'])){
                $dimensioni_calzatura_altezza_tacco=$_REQUEST['dimensioni_calzatura_altezza_tacco'];
            }

            $dimensioni_calzatura_altezza_plateau="";
            if (isset($_REQUEST['dimensioni_calzatura_altezza_plateau'])){
                $dimensioni_calzatura_altezza_plateau=$_REQUEST['dimensioni_calzatura_altezza_plateau'];
            }

            $dimensioni_calzatura_lunghezza_soletta="";
            if (isset($_REQUEST['dimensioni_calzatura_lunghezza_soletta'])){
                $dimensioni_calzatura_lunghezza_soletta=$_REQUEST['dimensioni_calzatura_lunghezza_soletta'];
            }

            $tipotaccodonna=array("");
            if (isset($_REQUEST['tipotaccodonna'])){
                $tipotaccodonna=$_REQUEST['tipotaccodonna'];
            }

            $tiposuola=array("");
            if (isset($tiposuola['tiposuola'])){
                $tiposuola=$_REQUEST['tiposuola'];
            }

            $tipopuntadonna=array("");
            if (isset($_REQUEST['tipopuntadonna'])){
                $tipopuntadonna=$_REQUEST['tipopuntadonna'];
            }

            $tipopuntauomo=array("");
            if (isset($_REQUEST['tipopuntauomo'])){
                $tipopuntauomo=$_REQUEST['tipopuntauomo'];
            }

            $vestibilitaabiti=array("");
            if (isset($_REQUEST['vestibilitaabiti'])){
                $vestibilitaabiti=$_REQUEST['vestibilitaabiti'];
            }

            $vestibilitatopwear=array("");
            if (isset($_REQUEST['vestibilitatopwear'])){
                $vestibilitatopwear=$_REQUEST['vestibilitatopwear'];
            }

            $vestibilitagonne=array("");
            if (isset($_REQUEST['vestibilitagonne'])){
                $vestibilitagonne=$_REQUEST['vestibilitagonne'];
            }

            $vestibilitapantaloni=array("");
            if (isset($_REQUEST['vestibilitapantaloni'])){
                $vestibilitapantaloni=$_REQUEST['vestibilitapantaloni'];
            }

            $vestcamicieuomo=array("");
            if (isset($_REQUEST['vestcamicieuomo'])){
                $vestcamicieuomo=$_REQUEST['vestcamicieuomo'];
            }

            $vestcamiciedonna=array("");
            if (isset($_REQUEST['vestcamiciedonna'])){
                $vestcamiciedonna=$_REQUEST['vestcamiciedonna'];
            }

            $vestibilitagiacche=array("");
            if (isset($_REQUEST['vestibilitagiacche'])){
                $vestibilitagiacche=$_REQUEST['vestibilitagiacche'];
            }

            $vestibilitacapispalla=array("");
            if (isset($_REQUEST['vestibilitacapispalla'])){
                $vestibilitacapispalla=$_REQUEST['vestibilitacapispalla'];
            }

            $qta=$_SESSION['qta'];
            $taglie=$_SESSION['taglie_s'];
            $scalari=$_SESSION['scalari_s'];

            $pCollection = Mage::getSingleton('index/indexer')->getProcessesCollection();
            foreach ($pCollection as $process) {
                $process->setMode(Mage_Index_Model_Process::MODE_MANUAL)->save();
            }



$p=0;
            $k = 0;



                // prodotto configurabile non esistente
                $l = 0;
                $cat[$l] = 2;
                $l = $l + 1;
                $cat[$l] = $id_categoria;
                $l = $l + 1;
                $cat[$l] = $id_sottocategoria1;
                $l = $l + 1;

                $id_sottoC = "sottocategoria2";
                while (isset($_REQUEST[$id_sottoC])) {
                    $id_sc = $_REQUEST[$id_sottoC];
                    $cat[$l] = $id_sc;

                    $id_sottoC = "sottocategoria" . $l;


                    $l = $l + 1;

                }


                $nome_brand = nomeValoreAttributo(134, $brand);


                // recupero il nome della stagione
                $entityType = Mage::getModel('eav/config')->getEntityType('catalog_product');
                $attributeModel = Mage::getModel('eav/entity_attribute')->loadByCode($entityType, "ca_stagione");
                $_collection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                    ->setAttributeFilter($attributeModel->getId())
                    ->setStoreFilter(3)
                    ->load();

                foreach ($_collection->toOptionArray() as $option) {
                    if ($option['value'] == $stagione) {
                        $nome_stagione = $option['label'];
                        break;
                    }
                }


                // recupero l'ultima categoria e la prima categoria
                $ultimo = count($cat) - 1;
                $lastCategory = $cat[$ultimo];
                while ($lastCategory == "") {
                    $ultimo = $ultimo - 1;
                    $lastCategory = $cat[$ultimo];
                }
                $firstCategory = $cat[1];

                $category = Mage::getModel('catalog/category')->setStoreId(3)->load($firstCategory);
                $firstCategortyDesc = $category->getName();

                $category = Mage::getModel('catalog/category')->setStoreId(3)->load($lastCategory);
                $lastCategortyDesc = $category->getName();


                $flag = true;

                $id_sottocategoria2 = $cat[3];
                $id_sottocategoria3 = "";
                if (isset($cat[4])) {
                    $id_sottocategoria3 = $cat[4];
                }


            $array_sku=array();
                for ($i = 0; $i < count($taglie); $i++) {
                    $pos = strpos($taglie[$i], "\\");
                    $id_taglia = substr($taglie[$i], 0, $pos);
                    $nome_taglia = substr($taglie[$i], $pos + 1, strlen($taglie[$i]) - $pos);


                    $sku_semplice = $sku . "-" . strtolower($nome_taglia);
                    $nome_semplice = ucfirst(strtolower($lastCategortyDesc . " " . $nome_brand . " " . $nome_taglia));
                    $url_key_semplice = $lastCategortyDesc . "-" . $nome_brand . "-" . $sku_semplice;


                    // controllo esistenza prodotto semplice
                    $productSimple = Mage::getModel('catalog/product')->loadByAttribute('sku', $sku_semplice);
                    if (!$productSimple) {

                        $controllo = "select count(*) from " . $resource->getTableName('wsca_misura_scalare') . " where misura='" . $nome_taglia . "' and id_brand='" . $brand . "' and id_category='" . $id_sottocategoria3 . "' and id_subgroup='" . $id_sottocategoria2 . "' and id_group='" . $id_sottocategoria1 . "' and id_macro_category='" . $id_categoria . "'";
                        $countMisuraScalare = $readConnection->fetchOne($controllo);
                        if ($countMisuraScalare == 0) {
                            $query = "insert into " . $resource->getTableName('wsca_misura_scalare') . " (misura,scalare,id_category,id_subgroup,id_group,id_macro_category,id_brand) values('" . $nome_taglia . "','" . $scalari[$i] . "','" . $id_sottocategoria3 . "','" . $id_sottocategoria2 . "','" . $id_sottocategoria1 . "','" . $id_categoria . "','" . $brand . "')";
                            $writeConnection->query($query);
                        } else {
                            $queryScalare = "select scalare from " . $resource->getTableName('wsca_misura_scalare') . " where misura='" . $nome_taglia . "' and id_brand='" . $brand . "' and id_category='" . $id_sottocategoria3 . "' and id_subgroup='" . $id_sottocategoria2 . "' and id_group='" . $id_sottocategoria1 . "' and id_macro_category='" . $id_categoria . "'";
                            $scalareMage = $readConnection->fetchOne($queryScalare);
                            if (strtolower($scalareMage) != strtolower($scalari[$i])) {
                                $query = "update " . $resource->getTableName('wsca_misura_scalare') . "  set scalare='" . $scalari[$i] . "' where misura='" . $nome_taglia . "' and id_brand='" . $brand . "' and id_category='" . $id_sottocategoria3 . "' and id_subgroup='" . $id_sottocategoria2 . "' and id_group='" . $id_sottocategoria1 . "' and id_macro_category='" . $id_categoria . "'";
                                $writeConnection->query($query);
                            }
                        }

                        $productSimple = Mage::getModel('catalog/product');
                        $productSimple->setSku($sku_semplice);
                        $productSimple->setName($nome_semplice);
                        $productSimple->setDescription(ucfirst(strtolower($descrizione)));
                        $productSimple->setShortDescription(ucfirst(strtolower($descrizione)));
                        $productSimple->setPrice($prezzo);
                        $productSimple->setTypeId('simple');
                        $productSimple->setAttributeSetId(4);
                        $productSimple->setCategoryIds($cat);
                        $productSimple->setWeight(1);
                        $productSimple->setTaxClassId(2);
                        $productSimple->setVisibility(1);
                        $productSimple->setStatus(1);
                        $stockData = $productSimple->getStockData();
                        $stockData['qty'] = $qta[$i];
                        if ($qta[$i] > 0) {
                            $stockData['is_in_stock'] = 1;
                        } else {
                            $stockData['is_in_stock'] = 0;
                        }

                        $productSimple->setStockData($stockData);
                        $productSimple->setWebsiteIds(array(2));
                        $productSimple->setUrlKey($url_key_semplice);
                        $productSimple->setData('ca_name', $nome);
                        $productSimple->setData('ca_brand', $brand);
                        $productSimple->setData('ca_anno', $anno);
                        $productSimple->setData('ca_stagione', $stagione);
                        $productSimple->setData('ca_misura', $id_taglia);
                        $productSimple->setData('ca_scalare', $scalari[$i]);
                        $productSimple->setData('ca_colore', $colore);
                        $productSimple->setData('ca_filtraggio_colore', $filtraggioColore);

                        $queryCarryOver = "select id_brand from " . $resource->getTableName('prodotti_carryover') . " where id_prodotto='" . $sku . "' and id_brand='" . $brand . "'";
                        $carryOver = $readConnection->fetchOne($queryCarryOver);
                        if ($carryOver == null) {
                            $productSimple->setData('ca_carryover', 2503);
                        } else {
                            $productSimple->setData('ca_carryover', 2502);
                        }
                        $productSimple->setData('ca_codice_colore_fornitore', $codice_colore);
                        $productSimple->setData('ca_codice_produttore', $codice_produttore);
                        $productSimple->setData('ca_000001', $supercolore);
                        $productSimple->setData('ca_000002', $motivo);
                        $productSimple->setData('ca_000003', $supercomposizione);
                        $productSimple->setData('ca_000004', $made_in);
                        $productSimple->setData('ca_000005', $composizione);
                        $productSimple->setData('ca_000006', $tipborsadonna);
                        $productSimple->setData('ca_000007', $tipborsadonna);
                        $productSimple->setData('ca_000008', $dimensioni_borsa_lunghezza);
                        $productSimple->setData('ca_000009', $dimensioni_borsa_altezza);
                        $productSimple->setData('ca_000010', $dimensioni_borsa_profondita);
                        $productSimple->setData('ca_000011', $dimensioni_borsa_altezza_manico);
                        $productSimple->setData('ca_000012', $dimensioni_borsa_lunghezza_tracolla);
                        $productSimple->setData('ca_000013', $tipaccessoridonna);
                        $productSimple->setData('ca_000014', $tipaccessoriuomo);
                        $productSimple->setData('ca_000015', $cintura_lunghezza);
                        $productSimple->setData('ca_000016', $cintura_altezza);
                        $productSimple->setData('ca_000017', $dimensioni_accessorio_lunghezza);
                        $productSimple->setData('ca_000018', $dimensioni_accessorio_altezza);
                        $productSimple->setData('ca_000019', $dimensioni_accessorio_profondita);
                        $productSimple->setData('ca_000020', $dimensioni_calzatura_altezza_tacco);
                        $productSimple->setData('ca_000021', $dimensioni_calzatura_altezza_plateau);
                        $productSimple->setData('ca_000022', $dimensioni_calzatura_lunghezza_soletta);
                        $productSimple->setData('ca_000023', $tipcalzdonna);
                        $productSimple->setData('ca_000024', $tipcalzuomo);
                        $productSimple->setData('ca_000025', $tipotaccodonna);
                        $productSimple->setData('ca_000026', $tiposuola);
                        $productSimple->setData('ca_000027', $tipopuntadonna);
                        $productSimple->setData('ca_000028', $tipopuntauomo);
                        $productSimple->setData('ca_000029', $vestibilitaabiti);
                        $productSimple->setData('ca_000030', $vestibilitatopwear);
                        $productSimple->setData('ca_000031', $vestibilitagonne);
                        $productSimple->setData('ca_000032', $vestibilitapantaloni);
                        $productSimple->setData('ca_000033', $vestcamicieuomo);
                        $productSimple->setData('ca_000034', $vestcamiciedonna);
                        $productSimple->setData('ca_000035', $vestibilitagiacche);
                        $productSimple->setData('ca_000036', $vestibilitacapispalla);


                        $stringa = "<reference name=\"head\"><action method=\"setRobots\"><value>NOINDEX,NOFOLLOW</value></action></reference>";
                        $productSimple->setData("custom_layout_update", $stringa);
                        $productSimple->save();


                        $array_sku[$k] = $sku_semplice;
                        $k=$k+1;

                    }

                    else {
                        $prodottiPresenti[$p]=$sku_semplice;
                        $p=$p+1;
                    }

                }

            $productConfig=Mage::getModel('catalog/product')->load($id_prodotto);
            $ids = $productConfig->getTypeInstance()->getUsedProductIds();
            foreach ( $ids as $id ) {
                $product = Mage::getModel('catalog/product')->load($id);
                $sku_ass=$product->getSku();
                $array_sku[$k]=$sku_ass;
                $k=$k+1;
            }


            Mage::helper('bubble_api/catalog_product')->associateProducts($productConfig, $array_sku,array(), array());
            $productConfig->save();

            $stringa="";
            if (isset($prodottiPresenti)){
                $stringa.="I seguenti prodotti non sono stati importati perchè già presenti in magazzino:\n\n";
                for ($z=0; $z<count($prodottiPresenti); $z++){
                    $stringa.="Sku: ".$prodottiPresenti[$z]."\n";
                }
            }

            if ($k>0){
                //alcuni prodotti inseriti correttamente e altri non inseriti perchè già presenti oppure tutti sono stati inseriti
                if ($stringa!=""){
                    $messaggio="Prodotto salvato con successo!\n\n".$stringa;
                }
                else {
                    $messaggio="Prodotto salvato con successo!";
                }
            }
            else if ($k==0){
                //nessun prodotto inserito perchè erano già tutti presenti
                $messaggio=$stringa;
            }


            if ($flag==true){
                echo "<form name=\"genera\" method=\"post\" action=\"../index.php\">";
                echo "<input type=\"hidden\" id=\"messaggio\" value=\"$messaggio\" />";
                echo "</form>";
                echo "<script>messaggio=document.getElementById('messaggio').value;if (confirm(messaggio)){document.forms['genera'].submit();}else {location.replace('../index.php');}</script>";
            }
            else {
                echo "<script>alert('Si è verificato un errore nel salvataggio');location.replace('../aggiungi-taglie.php')</script>";
            }
        }
        else {
            echo "<script>alert('Errore nella visualizzazione della pagina');location.replace('../index.php')</script>";
        }
    }
    else {
        echo "<script>alert('Non è possibile visualizzare questa pagina!');location.replace('../index.php')</script>";
    }
}
else {
    echo "<script>alert('Non è possibile visualizzare questa pagina! Effettua prima il login!');location.replace('../index.php')</script>";
}
?>