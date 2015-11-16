<?php
session_cache_limiter('nocache');
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Aggiungi Prodotto Semplice | Wisigo Product Management</title>
    <script type="text/javascript" src="js/controlloform.js"></script>

    <!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- PLUGINS CSS -->
    <link href="assets/plugins/slider/slider.min.css" rel="stylesheet">

    <!-- MAIN CSS (REQUIRED ALL PAGE)-->
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

</head>
<body class="tooltips">
<?php
if (isset($_SESSION['username'])){
    if ($_SESSION['livello']==3) {
        if (isset($_REQUEST['check']) && isset($_REQUEST['sku'])) {
            include("config/percorsoMage.php");
            require_once $MAGE;
            Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

            require('config/sorter.php');

            // recupero l'id del prodotto selezionato e lo sku inserito precedentemente
            $id_prodotto=$_REQUEST['check'];
            $sku_iniziale=$_REQUEST['sku'];


            // recupero il tipo prodotto associato a quell'id
            $product = Mage::getModel('catalog/product')->load($id_prodotto);
            $type_id=$product->getTypeId();
            if ($type_id=="configurable"){
                // se è configurabile recupero solo i campi del prodotto configurabile

                $descrizione=$product->getDescription();
                $sku=$product->getSku();
                $stagione=$product->getData('ca_stagione');
                $anno=$product->getData('ca_anno');
                $brand=$product->getData('ca_brand');
                $nome=$product->getData('ca_name');
                $prezzo=$product->getPrice();
                $prezzoIva=number_format($prezzo*1.22,2,",","");
                $codice_colore=$product->getData('ca_codice_colore_fornitore');
                $codice_produttore=$product->getData('ca_codice_produttore');
                $colore=$product->getData('ca_colore');
                $filtraggio_colore=$product->getData('ca_filtraggio_colore');
                $supercoloreDB=$product->getData('ca_000001');
                $motivoDB=$product->getData('ca_000002');
                $supercomposizioneDB=$product->getData('ca_000003');
                $made_in=$product->getData('ca_000004');
                $composizione=$product->getData('ca_000005');
                $tipoBorsaDonnaDB=$product->getData('ca_000006');
                $tipoBorsaUomoDB=$product->getData('ca_000007');
                $dimensioni_borsa_lunghezza=$product->getData('ca_000008');
                $dimensioni_borsa_altezza=$product->getData('ca_000009');
                $dimensioni_borsa_profondita=$product->getData('ca_000010');
                $dimensioni_borsa_altezza_manico=$product->getData('ca_000011');
                $dimensioni_borsa_lunghezza_tracolla=$product->getData('ca_000012');
                $tipoAccessoriDonnaDB=$product->getData('ca_000013');
                $tipoAcccessoriUomoDB=$product->getData('ca_000014');
                $cintura_lunghezza=$product->getData('ca_000015');
                $cintura_altezza=$product->getData('ca_000016');
                $dimensioni_accessorio_lunghezza=$product->getData('ca_000017');
                $dimensioni_accessorio_altezza=$product->getData('ca_000018');
                $dimensioni_accessorio_profondita=$product->getData('ca_000019');
                $dimensioni_calzatura_altezza_tacco=$product->getData('ca_000020');
                $dimensioni_calzatura_altezza_plateau=$product->getData('ca_000021');
                $dimensioni_calzatura_lunghezza_soletta=$product->getData('ca_000022');
                $tipoCalzDonnaDB=$product->getData('ca_000023');
                $tipoCalzUomoDB=$product->getData('ca_000024');
                $tipoTaccoDonnaDB=$product->getData('ca_000025');
                $tipoSuolaDB=$product->getData('ca_000026');
                $tipoPuntaDonnaDB=$product->getData('ca_000027');
                $tipoPuntaUomoDB=$product->getData('ca_000028');
                $vestibilitaAbitiDB=$product->getData('ca_000029');
                $vestibilitaTopwearDB=$product->getData('ca_000030');
                $vestibilitaGonneDB=$product->getData('ca_000031');
                $vestibilitaPantaloniDB=$product->getData('ca_000032');
                $vestibilitaCamicieUomoDB=$product->getData('ca_000033');
                $vestibilitaCamicieDonnaDB=$product->getData('ca_000034');
                $vestibilitaGiaccheDB=$product->getData('ca_000035');
                $vestibilitaCapispallaDB=$product->getData('ca_000036');



                $stagioneDesc=$product->getAttributeText('ca_stagione');
                $annoDesc=$product->getAttributeText('ca_anno');
                $brandDesc=$product->getAttributeText('ca_brand');



                $supercolore=explode(",",$supercoloreDB);
                $supercoloreDesc="";
                for ($i=0; $i<count($supercolore); $i++){
                    if ($i>0){
                        $supercoloreDesc.=", ";
                    }
                    $supercoloreDesc.=nomeValoreAttributo(306, $supercolore[$i]);
                }
                $motivo=explode(",",$motivoDB);
                $motivoDesc="";
                for ($i=0; $i<count($motivo); $i++){
                    if ($i>0){
                        $motivoDesc.=", ";
                    }
                    $motivoDesc.=nomeValoreAttributo(275, $motivo[$i]);
                }
                $supercomposizione=explode(",",$supercomposizioneDB);
                $supercomposizioneDesc="";
                for ($i=0; $i<count($supercomposizione); $i++){
                    if ($i>0){
                        $supercomposizioneDesc.=", ";
                    }
                    $supercomposizioneDesc.=nomeValoreAttributo(276, $supercomposizione[$i]);
                }

                $tipoBorsaDonna=explode(",",$tipoBorsaDonnaDB);
                $tipoBorsaDonnaDesc="";
                for ($i=0; $i<count($tipoBorsaDonna); $i++){
                    if ($i>0){
                        $tipoBorsaDonnaDesc.=", ";
                    }
                    $tipoBorsaDonnaDesc.=nomeValoreAttributo(297, $tipoBorsaDonna[$i]);
                }

                $tipoBorsaUomo=explode(",",$tipoBorsaUomoDB);
                $tipoBorsaUomoDesc="";
                for ($i=0; $i<count($tipoBorsaUomo); $i++){
                    if ($i>0){
                        $tipoBorsaUomoDesc.=", ";
                    }
                    $tipoBorsaUomoDesc.=nomeValoreAttributo(312, $tipoBorsaUomo[$i]);
                }

                $tipoAccessoriDonna=explode(",",$tipoAccessoriDonnaDB);
                $tipoAccessoriDonnaDesc="";
                for ($i=0; $i<count($tipoAccessoriDonna); $i++){
                    if ($i>0){
                        $tipoAccessoriDonnaDesc.=", ";
                    }
                    $tipoAccessoriDonnaDesc.=nomeValoreAttributo(307, $tipoAccessoriDonna[$i]);
                }

                $tipoAcccessoriUomo=explode(",",$tipoAcccessoriUomoDB);
                $tipoAcccessoriUomoDesc="";
                for ($i=0; $i<count($tipoAcccessoriUomo); $i++){
                    if ($i>0){
                        $tipoAcccessoriUomoDesc.=", ";
                    }
                    $tipoAcccessoriUomoDesc.=nomeValoreAttributo(304, $tipoAcccessoriUomo[$i]);
                }

                $tipoCalzDonna=explode(",",$tipoCalzDonnaDB);
                $tipoCalzDonnaDesc="";
                for ($i=0; $i<count($tipoCalzDonna); $i++){
                    if ($i>0){
                        $tipoCalzDonnaDesc.=", ";
                    }
                    $tipoCalzDonnaDesc.=nomeValoreAttributo(287, $tipoCalzDonna[$i]);
                }

                $tipoCalzUomo=explode(",",$tipoCalzUomoDB);
                $tipoCalzUomoDesc="";
                for ($i=0; $i<count($tipoCalzUomo); $i++){
                    if ($i>0){
                        $tipoCalzUomoDesc.=", ";
                    }
                    $tipoCalzUomoDesc.=nomeValoreAttributo(302, $tipoCalzUomo[$i]);
                }

                $tipoTaccoDonna=explode(",",$tipoTaccoDonnaDB);
                $tipoTaccoDonnaDesc="";
                for ($i=0; $i<count($tipoTaccoDonna); $i++){
                    if ($i>0){
                        $tipoTaccoDonnaDesc.=", ";
                    }
                    $tipoTaccoDonnaDesc.=nomeValoreAttributo(283, $tipoTaccoDonna[$i]);
                }

                $tipoSuola=explode(",",$tipoSuolaDB);
                $tipoSuolaDesc="";
                for ($i=0; $i<count($tipoSuola); $i++){
                    if ($i>0){
                        $tipoSuolaDesc.=", ";
                    }
                    $tipoSuolaDesc.=nomeValoreAttributo(284, $tipoSuola[$i]);
                }

                $tipoPuntaDonna=explode(",",$tipoPuntaDonnaDB);
                $tipoPuntaDonnaDesc="";
                for ($i=0; $i<count($tipoPuntaDonna); $i++){
                    if ($i>0){
                        $tipoPuntaDonnaDesc.=", ";
                    }
                    $tipoPuntaDonnaDesc.=nomeValoreAttributo(285, $tipoPuntaDonna[$i]);
                }

                $tipoPuntaUomo=explode(",",$tipoPuntaUomoDB);
                $tipoPuntaUomoDesc="";
                for ($i=0; $i<count($tipoPuntaUomo); $i++){
                    if ($i>0){
                        $tipoPuntaUomoDesc.=", ";
                    }
                    $tipoPuntaUomoDesc.=nomeValoreAttributo(286, $tipoPuntaUomo[$i]);
                }

                $vestibilitaAbiti=explode(",",$vestibilitaAbitiDB);
                $vestibilitaAbitiDesc="";
                for ($i=0; $i<count($vestibilitaAbiti); $i++){
                    if ($i>0){
                        $vestibilitaAbitiDesc.=", ";
                    }
                    $vestibilitaAbitiDesc.=nomeValoreAttributo(281, $vestibilitaAbiti[$i]);
                }

                $vestibilitaTopwear=explode(",",$vestibilitaTopwearDB);
                $vestibilitaTopwearDesc="";
                for ($i=0; $i<count($vestibilitaTopwear); $i++){
                    if ($i>0){
                        $vestibilitaTopwearDesc.=", ";
                    }
                    $vestibilitaTopwearDesc.=nomeValoreAttributo(279, $vestibilitaTopwear[$i]);
                }

                $vestibilitaGonne=explode(",",$vestibilitaGonneDB);
                $vestibilitaGonneDesc="";
                for ($i=0; $i<count($vestibilitaGonne); $i++){
                    if ($i>0){
                        $vestibilitaGonneDesc.=", ";
                    }
                    $vestibilitaGonneDesc.=nomeValoreAttributo(292, $vestibilitaGonne[$i]);
                }

                $vestibilitaPantaloni=explode(",",$vestibilitaPantaloniDB);
                $vestibilitaPantaloniDesc="";
                for ($i=0; $i<count($vestibilitaPantaloni); $i++){
                    if ($i>0){
                        $vestibilitaPantaloniDesc.=", ";
                    }
                    $vestibilitaPantaloniDesc.=nomeValoreAttributo(291, $vestibilitaPantaloni[$i]);
                }

                $vestibilitaCamicieUomo=explode(",",$vestibilitaCamicieUomoDB);
                $vestibilitaCamicieUomoDesc="";
                for ($i=0; $i<count($vestibilitaCamicieUomo); $i++){
                    if ($i>0){
                        $vestibilitaCamicieUomoDesc.=", ";
                    }
                    $vestibilitaCamicieUomoDesc.=nomeValoreAttributo(289, $vestibilitaCamicieUomo[$i]);
                }

                $vestibilitaCamicieDonna=explode(",",$vestibilitaCamicieDonnaDB);
                $vestibilitaCamicieDonnaDesc="";
                for ($i=0; $i<count($vestibilitaCamicieDonna); $i++){
                    if ($i>0){
                        $vestibilitaCamicieDonnaDesc.=", ";
                    }
                    $vestibilitaCamicieDonnaDesc.=nomeValoreAttributo(280, $vestibilitaCamicieDonna[$i]);
                }

                $vestibilitaGiacche=explode(",",$vestibilitaGiaccheDB);
                $vestibilitaGiaccheDesc="";
                for ($i=0; $i<count($vestibilitaGiacche); $i++){
                    if ($i>0){
                        $vestibilitaGiaccheDesc.=", ";
                    }
                    $vestibilitaGiaccheDesc.=nomeValoreAttributo(288, $vestibilitaGiacche[$i]);
                }

                $vestibilitaCapispalla=explode(",",$vestibilitaCapispallaDB);
                $vestibilitaCapispallaDesc="";
                for ($i=0; $i<count($vestibilitaCapispalla); $i++){
                    if ($i>0){
                        $vestibilitaCapispallaDesc.=", ";
                    }
                    $vestibilitaCapispallaDesc.=nomeValoreAttributo(290, $vestibilitaCapispalla[$i]);
                }


                $cats = $product->getCategoryIds();
                for ($i=1; $i<count($cats); $i++){
                    $cat = Mage::getModel('catalog/category')->load($cats[$i]);
                    $k=$cat->getLevel()-1;
                    if ($k==1){
                        $categoriaDB=$cats[$i];
                    }
                    else {
                        $k=$k-1;
                        ${'sottoCategoriaDB'.$k}=$cats[$i];
                    }
                }


                $cat = Mage::getModel('catalog/category')->load(2);
                $subcats = $cat->getChildren();
                $k=0;
                while ($subcats!=""){
                    $i=0;
                    foreach(explode(',',$subcats) as $subCatid)
                    {
                        $parentCategory = Mage::getModel('catalog/category')->load($subCatid);
                        if ($k==0){
                            $categoria[$i][0]=$subCatid;
                            $categoria[$i][1]=$parentCategory->getName();
                            if ($subCatid==$categoriaDB){
                                $sottoCat=$subCatid;
                                $categoriaDescDB=$categoria[$i][1];
                            }
                            $i=$i+1;
                        }
                        else {
                            ${'sottoCategoria'.$k}[$i][0]=$subCatid;
                            ${'sottoCategoria'.$k}[$i][1]=$parentCategory->getName();
                            if ($subCatid==${'sottoCategoriaDB'.$k}){
                                $sottoCat=$subCatid;
                                ${'sottoCategoriaDescDB'.$k}=${'sottoCategoria'.$k}[$i][1];
                            }
                            $i=$i+1;
                        }


                    }
                    $k=$k+1;
                    $cat = Mage::getModel('catalog/category')->load($sottoCat);
                    $subcats = $cat->getChildren();
                }


// recupero le opzioni dell'attributo misura
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', "ca_misura");
                $attribute = $attribute_model->load($attribute_code);

                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);

                $i=0;
                foreach($options as $option) {
                    // i colori misti non vengono recuperati
                    if (strpos($option['label'],"/")==false){
                        $taglia[$i][0]=$option['value'];
                        $taglia[$i][1]=$option['label'];
                        $i=$i+1;
                    }
                }

                // ordinamento
                $oSorter = new ArraySorter();
                $oSorter->setArray($taglia);
                $taglia=$oSorter->sort(1, ArraySorter::DIRECTION_ASC);

                // azzero le variabili di sessione
                unset($_SESSION['taglie_s']);
                unset($_SESSION['scalari_s']);
                unset($_SESSION['taglia']);
                unset($_SESSION['scalare']);
                unset($_SESSION['qta']);
                unset($_SESSION['taglie_scelte']);
                unset($_SESSION['scalari_scelti']);

                // setto tutte le taglie e i colori presenti in magento in due variabili di sessione
                $_SESSION['taglia']=$taglia;
            }
            else {
                echo "<script>alert('Errore nella visualizzazione della pagina!');window.location='index.php'</script>";
            }

            ?>
            <!--
                ===========================================================
                BEGIN PAGE
                ===========================================================
                -->
            <div class="wrapper">
            <!-- BEGIN TOP NAV -->
            <?php
            include("config/top.php");
            ?>
            <!-- END TOP NAV -->



            <!-- BEGIN SIDEBAR LEFT -->
            <?php
            include("config/left.php");
            ?>

            <!-- END SIDEBAR LEFT -->




            <!-- BEGIN PAGE CONTENT -->
            <div class="page-content">
            <div class="container-fluid">

            <!-- Begin page heading -->
            <h1 class="page-heading">AGGIUNGI PRODOTTO SEMPLICE<!--<small>Sub heading here</small>--></h1>
            <!-- Form inserimento prodotto -->
            <div class="the-box" style="float:left;width:100%">
            <div class="alert alert-danger fade in alert-dismissable" id="riga_errore" style="display:none">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <p id="errore"></p>
            </div>
            <div class="progress no-rounded progress-striped active" id="loading" style="width:100%;display:none">
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                    <span class="sr-only">40% Complete (success)</span>
                </div>
            </div>
            <form name="form_prodotto" method="post" action="config/aggiungi-prodotto-semplice.php">
                <!-- definisco dei campi hidden per ogni elemento del prodotto configurabile; questo perchè i campi sono disable e quindi non vengono recuperati dalla pagina php "aggiungi-prodotto-semplice.php" -->
                <input type="hidden" name="id_prodotto" value="<?php  echo $id_prodotto ?>" />
                <input type="hidden" name="nome" value="<?php  echo $nome ?>" />
                <input type="hidden" name="sku" value="<?php  echo $sku ?>" />
                <input type="hidden" name="descrizione" value="<?php  echo $descrizione ?>" />
                <input type="hidden" name="categoria" value="<?php echo $categoriaDB ?>" />
                <input type="hidden" name="sottocategoria1" value="<?php  echo $sottoCategoriaDB1 ?>" />
                <?php
                $m=2;
                $parent="";
                while (isset(${'sottoCategoriaDB'.$m})){
                    $valoreCat=${'sottoCategoriaDB'.$m};
                    echo "<input type=\"hidden\" name=\"sottocategoria".$m."\" value=\"".$valoreCat."\" />";
                    $m=$m+1;
                }
                ?>
                <input type="hidden" name="brand" value="<?php  echo $brand ?>" />
                <input type="hidden" name="prezzo" value="<?php  echo $prezzo ?>" />
                <input type="hidden" name="stagione" value="<?php  echo $stagione ?>" />
                <input type="hidden" name="anno" value="<?php  echo $anno ?>" />
                <input type="hidden" name="codice_colore" value="<?php  echo $codice_colore ?>" />
                <input type="hidden" name="codice_produttore" value="<?php  echo $codice_produttore ?>" />
                <input type="hidden" name="motivo" value="<?php  echo $motivoDB ?>" />
                <input type="hidden" name="supercomposizione" value="<?php  echo $supercomposizioneDB ?>" />
                <input type="hidden" name="made_in" value="<?php  echo $made_in ?>" />
                <input type="hidden" name="composizione" value="<?php  echo $composizione ?>" />
                <input type="hidden" name="supercolore" value="<?php  echo $supercoloreDB ?>" />
                <input type="hidden" name="colore" value="<?php  echo $colore ?>" />
                <input type="hidden" name="filtraggio_colore" value="<?php  echo $filtraggio_colore ?>" />




                <div class="form-group">
                    <label class="control-label">Nome *</label>
                    <input type="text" disabled name="nome" value="<?php echo $nome ?>" class="form-control" placeholder="Nome" id="nome"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Descrizione *</label>
                    <textarea name="descrizione" disabled id="descrizione" class="form-control" placeholder="Descrizione" style="height:100px"><?php echo $descrizione ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">Id *</label>
                    <input type="text" disabled value="<?php echo $sku ?>" name="id" id="id" class="form-control" placeholder="Id Prodotto"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Codice colore fornitore</label>
                    <input type="text" disabled value="<?php echo $codice_colore ?>" name="codice_colore" id="codice_colore" class="form-control" placeholder="Codice colore fornitore"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Codice produttore</label>
                    <input type="text" disabled name="codice_produttore" value="<?php echo $codice_produttore ?>" id="codice_produttore" class="form-control" placeholder="Codice produttore"/>
                </div>

                <div class="form-group">
                    <label class="control-label">Categoria *</label>
                    <input type="text" disabled disabled value="<?php  echo $categoriaDescDB ?>" name="categoria" disabled class="form-control" id="categoria" />
                </div>
                <div class="form-group">
                    <label class="control-label">Sotto categoria 1 *</label>
                    <input type="text" disabled disabled value="<?php  echo $sottoCategoriaDescDB1 ?>" name="sottocategoria" disabled class="form-control" id="sottocategoria" />
                </div>
                <?php
                $m=2;
                $parent="";
                while (isset(${'sottoCategoriaDescDB'.$m})){
                    echo "<div class=\"form-group\" style=\"margin-top:8px\">
                <label class=\"control-label\">Sotto categoria ".$m." *</label>
                <input type=\"text\" disabled value=\"".${'sottoCategoriaDescDB'.$m}."\" name=\"sottocategoria".$m."\" disabled class=\"form-control\" id=\"sottocategoria".$m."\" />
            </div>";



                    $m=$m+1;
                }
                ?>
                <div class="form-group">
                    <label class="control-label">Prezzo *</label>
                    <input type="text" disabled value="<?php echo $prezzoIva ?>" name="prezzo" id="prezzo" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="control-label">Brand *</label>
                    <input type="text" disabled value="<?php echo $brandDesc ?>" name="brand" id="prezzo" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="control-label">Stagione *</label>
                    <input type="text" disabled value="<?php echo $stagioneDesc ?>" name="stagione" id="prezzo" class="form-control" />
                </div>
                <div class="form-group">
                    <label class="control-label">Anno *</label>
                    <input type="text" disabled value="<?php echo $annoDesc ?>"  class="form-control" />
                </div>
                <div class="form-group">
                    <label class="control-label">Immagini</label>
                    <input type="file" name="immagini[]" multiple class="multi form-control"/>
                </div>
                <div class="form-group">
                    <label class="control-label">Super colore</label>
                    <input type="text" disabled value="<?php echo $supercoloreDesc ?>"  class="form-control" />
                </div>
                <div class="form-group">
                    <label class="control-label">Motivo</label>
                    <input type="text" disabled value="<?php echo $motivoDesc ?>"  class="form-control" />
                </div>
                <div class="form-group">
                    <label class="control-label">Supercomposizione</label>
                    <input type="text" disabled value="<?php echo $supercomposizioneDesc ?>"  class="form-control" />
                </div>
                <div class="form-group">
                    <label class="control-label">Made In</label>
                    <input type="text" disabled value="<?php echo $made_in ?>"  class="form-control" />
                </div>
                <div class="form-group">
                    <label class="control-label">Composizione</label>
                    <input type="text" disabled value="<?php echo $composizione ?>"  class="form-control" />
                </div>
                <?php
                    $cats = $product->getCategoryIds();
                    for ($i=1; $i<count($cats); $i++) {
                        $cat = Mage::getModel('catalog/category')->load($cats[$i]);
                        $nomeCat=$cat->getName();
                        if ($cat->getName()=="Borse donna"){
                            ?>
                            <input type="hidden" name="tipborsadonna" value="<?php  echo $tipoBorsaDonnaDB ?>" />
                            <input type="hidden" name="dimensioni_borsa_lunghezza" value="<?php  echo $dimensioni_borsa_lunghezza ?>" />
                            <input type="hidden" name="dimensioni_borsa_altezza" value="<?php  echo $dimensioni_borsa_altezza ?>" />
                            <input type="hidden" name="dimensioni_borsa_profondita" value="<?php  echo $dimensioni_borsa_profondita ?>" />
                            <input type="hidden" name="dimensioni_borsa_altezza_manico" value="<?php  echo $dimensioni_borsa_profondita ?>" />
                            <input type="hidden" name="dimensioni_borsa_lunghezza_tracolla" value="<?php  echo $dimensioni_borsa_lunghezza_tracolla ?>" />

                            <div class="form-group">
                                <label class="control-label">Tipologia borsa donna</label>
                                <input type="text" disabled value="<?php echo $tipoBorsaDonnaDesc ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni borsa lunghezza</label>
                                <input type="text" disabled value="<?php echo $dimensioni_borsa_lunghezza ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni borsa altezza</label>
                                <input type="text" disabled value="<?php echo $dimensioni_borsa_altezza ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni borsa profondità</label>
                                <input type="text" disabled value="<?php echo $dimensioni_borsa_profondita ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni borsa altezza manico</label>
                                <input type="text" disabled value="<?php echo $dimensioni_borsa_altezza_manico ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni borsa lunghezza tracolla</label>
                                <input type="text" disabled value="<?php echo $dimensioni_borsa_lunghezza_tracolla ?>"  class="form-control" />
                            </div>
                            <?php
                        }
                        if ($cat->getName()=="Borse uomo"){
                            ?>
                            <input type="hidden" name="tipborsauomo" value="<?php  echo $tipoBorsaUomoDB ?>" />
                            <input type="hidden" name="dimensioni_borsa_lunghezza" value="<?php  echo $dimensioni_borsa_lunghezza ?>" />
                            <input type="hidden" name="dimensioni_borsa_altezza" value="<?php  echo $dimensioni_borsa_altezza ?>" />
                            <input type="hidden" name="dimensioni_borsa_profondita" value="<?php  echo $dimensioni_borsa_profondita ?>" />
                            <input type="hidden" name="dimensioni_borsa_altezza_manico" value="<?php  echo $dimensioni_borsa_profondita ?>" />
                            <input type="hidden" name="dimensioni_borsa_lunghezza_tracolla" value="<?php  echo $dimensioni_borsa_lunghezza_tracolla ?>" />

                            <div class="form-group">
                                <label class="control-label">Tipologia borsa uomo</label>
                                <input type="text" disabled value="<?php echo $tipoBorsaUomoDesc ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni borsa lunghezza</label>
                                <input type="text" disabled value="<?php echo $dimensioni_borsa_lunghezza ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni borsa altezza</label>
                                <input type="text" disabled value="<?php echo $dimensioni_borsa_altezza ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni borsa profondità</label>
                                <input type="text" disabled value="<?php echo $dimensioni_borsa_profondita ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni borsa altezza manico</label>
                                <input type="text" disabled value="<?php echo $dimensioni_borsa_altezza_manico ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni borsa lunghezza tracolla</label>
                                <input type="text" disabled value="<?php echo $dimensioni_borsa_lunghezza_tracolla ?>"  class="form-control" />
                            </div>
                            <?php
                        }
                        if ($cat->getName()=="Accessori donna"){
                            ?>
                            <input type="hidden" name="tipaccessoridonna" value="<?php  echo $tipoAccessoriDonnaDB ?>" />
                            <input type="hidden" name="dimensioni_accessorio_lunghezza" value="<?php  echo $dimensioni_accessorio_lunghezza ?>" />
                            <input type="hidden" name="dimensioni_accessorio_altezza" value="<?php  echo $dimensioni_accessorio_altezza ?>" />
                            <input type="hidden" name="dimensioni_accessorio_profondita" value="<?php  echo $dimensioni_accessorio_profondita ?>" />

                            <div class="form-group">
                                <label class="control-label">Tipologia accessori donna</label>
                                <input type="text" disabled value="<?php echo $tipoAccessoriDonnaDesc ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni accessorio lunghezza</label>
                                <input type="text" disabled value="<?php echo $dimensioni_accessorio_lunghezza ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni accessorio altezza</label>
                                <input type="text" disabled value="<?php echo $dimensioni_accessorio_altezza ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni accessorio profondità</label>
                                <input type="text" disabled value="<?php echo $dimensioni_accessorio_profondita ?>"  class="form-control" />
                            </div>
                            <?php
                        }
                        if ($cat->getName()=="Accessori uomo"){
                            ?>
                            <input type="hidden" name="tipaccessoriuomo" value="<?php  echo $tipoAcccessoriUomoDB ?>" />
                            <input type="hidden" name="dimensioni_accessorio_lunghezza" value="<?php  echo $dimensioni_accessorio_lunghezza ?>" />
                            <input type="hidden" name="dimensioni_accessorio_altezza" value="<?php  echo $dimensioni_accessorio_altezza ?>" />
                            <input type="hidden" name="dimensioni_accessorio_profondita" value="<?php  echo $dimensioni_accessorio_profondita ?>" />

                            <div class="form-group">
                                <label class="control-label">Tipologia accessori uomo</label>
                                <input type="text" disabled value="<?php echo $tipoAcccessoriUomoDesc ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni accessorio lunghezza</label>
                                <input type="text" disabled value="<?php echo $dimensioni_accessorio_lunghezza ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni accessorio altezza</label>
                                <input type="text" disabled value="<?php echo $dimensioni_accessorio_altezza ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni accessorio profondità</label>
                                <input type="text" disabled value="<?php echo $dimensioni_accessorio_profondita ?>"  class="form-control" />
                            </div>
                        <?php
                        }
                        if ($cat->getName()=="Calzature donna"){
                            ?>
                            <input type="hidden" name="tipcalzdonna" value="<?php  echo $tipoCalzDonnaDB ?>" />
                            <input type="hidden" name="dimensioni_calzatura_altezza_tacco" value="<?php  echo $dimensioni_calzatura_altezza_tacco ?>" />
                            <input type="hidden" name="dimensioni_calzatura_altezza_plateau" value="<?php  echo $dimensioni_calzatura_altezza_plateau ?>" />
                            <input type="hidden" name="dimensioni_calzatura_lunghezza_soletta" value="<?php  echo $dimensioni_calzatura_lunghezza_soletta ?>" />
                            <input type="hidden" name="tipotaccodonna" value="<?php  echo $tipoTaccoDonnaDB ?>" />
                            <input type="hidden" name="tiposuola" value="<?php  echo $tipoSuolaDB ?>" />
                            <input type="hidden" name="tipopuntadonna" value="<?php  echo $tipoPuntaDonnaDB ?>" />

                            <div class="form-group">
                                <label class="control-label">Tipologia calzature donna</label>
                                <input type="text" disabled value="<?php echo $tipoCalzDonnaDesc ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni calzatura altezza tacco</label>
                                <input type="text" disabled value="<?php echo $dimensioni_calzatura_altezza_tacco ?>"   class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni calzatura altezza plateau</label>
                                <input type="text" disabled value="<?php echo $dimensioni_calzatura_altezza_plateau ?>"   class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni calzatura lunghezza soletta</label>
                                <input type="text" disabled value="<?php echo $dimensioni_calzatura_lunghezza_soletta ?>"   class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Tipo tacco donna</label>
                                <input type="text" disabled value="<?php echo $tipoTaccoDonnaDesc ?>"   class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Tipo suola</label>
                                <input type="text" disabled value="<?php echo $tipoSuolaDesc ?>"   class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Tipo punta donna</label>
                                <input type="text" disabled value="<?php echo $tipoPuntaDonnaDesc ?>"   class="form-control" />
                            </div>
                            <?php
                        }
                        if ($cat->getName()=="Calzature uomo"){
                            ?>
                            <input type="hidden" name="tipcalzuomo" value="<?php  echo $tipoCalzUomoDB ?>" />
                            <input type="hidden" name="dimensioni_calzatura_altezza_tacco" value="<?php  echo $dimensioni_calzatura_altezza_tacco ?>" />
                            <input type="hidden" name="dimensioni_calzatura_altezza_plateau" value="<?php  echo $dimensioni_calzatura_altezza_plateau ?>" />
                            <input type="hidden" name="dimensioni_calzatura_lunghezza_soletta" value="<?php  echo $dimensioni_calzatura_lunghezza_soletta ?>" />
                            <input type="hidden" name="tiposuola" value="<?php  echo $tipoSuolaDB ?>" />
                            <input type="hidden" name="tipopuntauomo" value="<?php  echo $tipoPuntaUomoDB ?>" />

                            <div class="form-group">
                                <label class="control-label">Tipologia calzature uomo</label>
                                <input type="text" disabled value="<?php echo $tipoCalzUomoDesc ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni calzatura altezza tacco</label>
                                <input type="text" disabled value="<?php echo $dimensioni_calzatura_altezza_tacco ?>"   class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni calzatura altezza plateau</label>
                                <input type="text" disabled value="<?php echo $dimensioni_calzatura_altezza_plateau ?>"   class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Dimensioni calzatura lunghezza soletta</label>
                                <input type="text" disabled value="<?php echo $dimensioni_calzatura_lunghezza_soletta ?>"   class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Tipo suola</label>
                                <input type="text" disabled value="<?php echo $tipoSuolaDesc ?>"   class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Tipo punta uomo</label>
                                <input type="text" disabled value="<?php echo $tipoPuntaUomoDesc ?>"   class="form-control" />
                            </div>
                            <?php
                        }
                        if ($cat->getName()=="Abiti"){
                            ?>
                            <input type="hidden" name="vestibilitaabiti" value="<?php  echo $vestibilitaAbitiDB ?>" />
                            <div class="form-group">
                                <label class="control-label">Vestibilità abiti</label>
                                <input type="text" disabled value="<?php echo $vestibilitaAbitiDesc ?>"   class="form-control" />
                            </div>

                        <?php
                        }
                        if ($cat->getName()=="Topwear"){
                            ?>
                            <input type="hidden" name="vestibilitatopwear" value="<?php  echo $vestibilitaTopwearDB ?>" />
                            <div class="form-group">
                                <label class="control-label">Vestibilità topwear</label>
                                <input type="text" disabled value="<?php echo $vestibilitaTopwearDesc ?>"   class="form-control" />
                            </div>
                            <?php
                        }
                        if ($cat->getName()=="Pantaloni"){
                            ?>
                            <input type="hidden" name="vestibilitapantaloni" value="<?php  echo $vestibilitaPantaloniDB ?>" />

                            <div class="form-group">
                                <label class="control-label">Vestibilità pantaloni e jeans</label>
                                <input type="text" disabled value="<?php echo $vestibilitaPantaloniDesc ?>"  class="form-control" />
                            </div>

                        <?php
                        }
                        if ($cat->getName()=="Jeans"){
                            ?>
                            <input type="hidden" name="vestibilitapantaloni" value="<?php  echo $vestibilitaPantaloni ?>" />

                            <div class="form-group">
                                <label class="control-label">Vestibilità pantaloni e jeans</label>
                                <input type="text" disabled value="<?php echo $vestibilitaPantaloniDesc ?>"  class="form-control" />
                            </div>

                            <?php
                        }
                        if ($cat->getName()=="Gonne"){
                            ?>
                            <input type="hidden" name="vestibilitagonne" value="<?php  echo $vestibilitaGonneDB ?>" />

                            <div class="form-group">
                                <label class="control-label">Vestibilità gonne</label>
                                <input type="text" disabled value="<?php echo $vestibilitaGonneDesc ?>"   class="form-control" />
                            </div>

                            <?php
                        }
                        if (($cat->getName()=="Camicie" && $cat->getId()==9)){
                            ?>
                            <input type="hidden" name="vestcamiciedonna" value="<?php  echo $vestibilitaCamicieDonnaDB ?>" />
                            <div class="form-group">
                                <label class="control-label">Vestibilità camicie donna</label>
                                <input type="text" disabled value="<?php echo $vestibilitaCamicieDonnaDesc ?>"   class="form-control" />
                            </div>
                           <?php
                        }
                        if ($cat->getName()=="Camicie" && $cat->getId()==22){
                            ?>
                            <input type="hidden" name="vestcamicieuomo" value="<?php  echo $vestibilitaCamicieUomoDB ?>" />
                            <div class="form-group">
                                <label class="control-label">Vestibilità camicie uomo</label>
                                <input type="text" disabled value="<?php echo $vestibilitaCamicieUomoDesc ?>"  class="form-control" />
                            </div>

                            <?php
                        }
                        if ($cat->getName()=="Capispalla"){
                            ?>
                            <input type="hidden" name="vestibilitacapispalla" value="<?php  echo $vestibilitaCapispallaDB ?>" />

                            <div class="form-group">
                                <label class="control-label">Vestibilità capispalla</label>
                                <input type="text" disabled value="<?php echo $vestibilitaCapispallaDesc ?>"   class="form-control" />
                            </div>
                            <?php
                        }
                        if ($cat->getName()=="Giacche/blazer" || $cat->getName()=="Blazer"){
                            ?>
                            <input type="hidden" name="vestibilitagiacche" value="<?php  echo $vestibilitaGiaccheDB ?>" />

                            <div class="form-group">
                                <label class="control-label">Vestibilità giacche</label>
                                <input type="text" disabled value="<?php echo $vestibilitaGiaccheDesc ?>"   class="form-control" />
                            </div>
                            <?php
                        }
                        if ($cat->getName()=="Cinture"){
                            ?>
                            <input type="hidden" name="cintura_lunghezza" value="<?php  echo $cintura_lunghezza ?>" />
                            <input type="hidden" name="cintura_altezza" value="<?php  echo $cintura_altezza ?>" />

                            <div class="form-group">
                                <label class="control-label">Cintura lunghezza</label>
                                <input type="text" disabled value="<?php echo $cintura_lunghezza ?>"  class="form-control" />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Cintura altezza</label>
                                <input type="text" disabled value="<?php echo $cintura_altezza ?>"  class="form-control" />
                            </div>

                        <?php
                        }
                    }
                ?>





                <!-- Bottone per inserire le taglie e i colori -->
                <div class="form-group" style="margin-top:20px">
                    <input type="button" name="inserisci_taglia_scalare" value="Inserisci taglie e scalari" class="btn btn-success active" style="height: 35px;font-size: 15px;" onclick="window.open('taglia-scalare.php','Inserisci Taglie e Scalari','scrollbars=1,width=900,height=630');"/>
                </div>
                                <p style="margin-top:30px;font-size:12px;float:left;width:100%">* Campi obbligatori</p>
                                <div class="form-group" style="margin-top:30px">

                                    <button name="indietro" class="btn btn-danger active" style="height: 35px;font-size: 15px;"  type="button"  style="height: 35px;font-size: 15px;margin-top:30px" onclick="<?php  echo "window.location='aggiorna-prodotto.php?sku=$sku_iniziale'"; ?>" />Indietro</button>
                                    <button name="salva" class="btn btn-success active" style="height: 35px;font-size: 15px;float:right"  type="button"  onclick="controlloFormAggiungiProdottoSemplice()"/>Salva</button>

                                </div>



            </form>
            </div>
            </div>
            <?php include("config/footer.php") ?>
            </div>
            </div>
        <?php
        }
        else {
            echo "<script>alert('Errore nella visualizzazione della pagina!');window.location='index.php'</script>";
        }
    }
    else {
        echo "<script>alert('Non è possibile visualizzare questa pagina!');window.location='index.php'</script>";
    }
}
else {
    echo "<script>alert('Non è possibile visualizzare questa pagina! Effettua prima il login!');window.location='index.php'</script>";
}

?>




<!--
===========================================================
END PAGE
===========================================================
-->

<!--
===========================================================
Placed at the end of the document so the pages load faster
===========================================================
-->
<!-- MAIN JAVASRCIPT (REQUIRED ALL PAGE)-->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/plugins/retina/retina.min.js"></script>
<script src="assets/plugins/nicescroll/jquery.nicescroll.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/backstretch/jquery.backstretch.min.js"></script>

<!-- PLUGINS -->
<script src="assets/plugins/slider/bootstrap-slider.js"></script>



<!-- MAIN APPS JS -->
<script src="assets/js/apps.js"></script>

</body>
</html>

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