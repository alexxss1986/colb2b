<?php
session_cache_limiter('nocache');
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Dettaglio Spedizione | Wisigo Product Management</title>
    <script type="text/javascript" src="js/controlloform.js"></script>

    <!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- PLUGINS CSS -->
    <link href="assets/plugins/validator/bootstrapValidator.min.css" rel="stylesheet">
    <link href="assets/plugins/slider/slider.min.css" rel="stylesheet">

    <!-- MAIN CSS (REQUIRED ALL PAGE)-->
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">



</head>
<body class="tooltips">
<?php
function virgola($str) { #usato nella vecchia versione che voleva i prezzi con la virgola. nella pro usiamo il .
    return number_format($str,2,'.',''); #str_replace('.' , ',' , $str);
}

if (isset($_SESSION['username'])){
    if (isset($_REQUEST['order_id'])) {

        include("config/percorsoMage.php");
        require_once $MAGE;
        Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

        $orderId=$_REQUEST['order_id'];
        $_order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        $_shippingAddress = $_order->getShippingAddress();
        $_countryTo = $_shippingAddress->getCountryId();




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
        <h1 class="page-heading">DETTAGLIO SPEDIZIONE</h1>
        <!-- Form inserimento prodotto -->
        <div class="the-box">
            <form id="dettaglio_spedizione"  name="form_dettaglio_spedizione" method="post" action="config/spedisci.php" >
                <?php
                echo '<input type="hidden" name="wgtntpro[packagetype]" value="C" />';
                echo '<input type="hidden" name="order_id" value="'.$orderId.'">';
                echo '<input type="hidden" name="wgtntpro[usalo]" id="wgtntpro_usalo" value="1">';
                ?>
                <!--<h4 class="small-title">FORM PER L'INSERIMENTO DI UN NUOVO PRODOTTO</h4>-->
                <div class="form-group">
                    <label class="control-label">Colli *</label>
                    <input type="text" name="wgtntpro[totalpackages]" value="1"  class="form-control" placeholder="Colli" id="totalpackages"/>

                </div>
                <div class="form-group">
                    <label class="control-label">Peso Totale Colli (Kg) *</label>
                    <input type="text" name="wgtntpro[actualweight]" id="actualweight" value="<?php echo virgola($_order->getWeight()) ?>" class="form-control" placeholder="Peso Totale Colli (Kg)" />

                </div>
                <div class="form-group">
                    <label class="control-label">Volume Totale Colli (m3) *</label>
                    <input type="text" name="wgtntpro[actualvolume]" id="actualvolume" value="<?php echo virgola($_order->getWeight()/100.0) ?>" class="form-control" placeholder="Volume Totale Colli (m3)" />

                </div>
                <div class="form-group">
                    <label class="control-label">Descrizione</label>
                    <input type="text" name="wgtntpro[goodsdesc]" id="goodsdesc" class="form-control" placeholder="Descrizione"/>

                </div>
                <div class="form-group">
                    <label class="control-label">Riferimento Mittente *</label>
                    <input type="text" name="wgtntpro[reference]" id="reference" value="<?php echo $_order->getIncrementId() ?>" readonly class="form-control" placeholder="Riferimento Mittente"/>

                </div>
                <div class="form-group">
                    <label class="control-label">Collection Date (YYYYMMDD) *</label>
                    <input type="text" name="wgtntpro[collectiondate]" id="collectiondate" value="<?php echo date("Ymd"); ?>" class="form-control" placeholder="Collection Date (YYYYMMDD)"/>
                </div>


                <div class="form-group">
                    <label class="control-label">Magazzino di partenza *</label>
                    <select name="wgtntpro[magazzino]" class="form-control" placeholder="Categoria *" id="magazzino">
                        <option value="">Seleziona un magazzino...</option>
                        <?php foreach (Mage::getModel('wgtntpro/magazzini')->toOptionArray() as $mag): ?>
                            <option value="<?php echo $mag['value'] ?>" <?php echo $mag['extra'] ?> ><?php echo $mag['label'] ?></option>
                        <?php endforeach ?>
                    </select>

                </div>
                <div class="form-group">
                    <label class="control-label">Servizio *</label>
                    <select name="wgtntpro[product]" class="form-control" id="product">
                        <?php foreach (Mage::helper('wgtntpro')->toServiceOptionArray('C') as $mag): ?>
                            <option value="<?php echo $mag['value'] ?>" <?php echo $mag['extra'] ?> class="<?php echo $mag['class'] ?>"><?php echo $mag['label'] ?></option>
                        <?php endforeach ?>

                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label">Spedizione a carico di *</label>
                    <select name="wgtntpro[termsofpayment]" class="form-control" id="termsofpayment">
                        <option value="S">Mittente</option>
                        <option value="R">Destinatario</option>
                    </select>
                </div>



                <div class="form-group">
                    <label class="control-label">Istruzioni</label>
                    <input type="text" name="wgtntpro[specialinstructions]" id="specialinstructions" class="form-control" placeholder="Istruzioni"/>

                </div>

                <!-- SPUNTE -->
                <div class="form-group">
                    <input type="checkbox" name="wgtntpro[PrintInstrDocs]" style="margin-right:10px" id="wgtntpro_PrintInstrDocs" value="Y" checked="checked" />Full documents
                </div>
                <div class="form-group">
                    <input type="checkbox" name="wgtntpro[hazardous]" style="margin-right:10px" id="wgtntpro_hazardous" value="Y" />Hazardous
                </div>
                <div class="form-group">
                    <input type="checkbox" name="wgtntpro[highvalue]" style="margin-right:10px"  id="wgtntpro_highvalue" value="Y" />Highvalue
                </div>
                <div class="form-group">
                    <input type="checkbox" name="wgtntpro[specialgoods]" style="margin-right:10px" id="wgtntpro_specialgoods" value="Y" />Special Goods
                </div>


                <!-- INSURANCE -->
                <div class="form-group">
                    <input type="checkbox" style="margin-right:10px" name="wgtntpro[insurance]" id="wgtntpro_insurance" value="Y" />Imposta valore assicurato
                </div>
                <div class="form-group insurance">
                    <label class="control-label" for="wgtntpro[insurancevalue]">Importo Assicurato</label>
                    <input disabled type="text" name="wgtntpro[insurancevalue]" id="wgtntpro_insurancevalue" value="<?php echo virgola($_order->getGrandTotal()) ?>" class="validate-number my-number form-control" />
                </div>
                <div class="form-group insurance">
                    <label class="control-label" for="wgtntpro[insurancecurrency]">Valuta Importo Assicurato</label>
                    <!-- adminhtml/system_config_source_currency -->
                    <select disabled name="wgtntpro[insurancecurrency]" class="form-control" id="wgtntpro_insurancecurrency">
                        <?php foreach(Mage::getModel('adminhtml/system_config_source_currency')->toOptionArray(FALSE) as $cur): ?>
                            <option value="<?php echo $cur['value']?>" <?php if ($cur['value']==$_order->getOrderCurrencyCode()) {echo " SELECTED ";} ?>><?php echo $cur['label']?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group insurance">
                    <label class="control-label" for="wgtntpro[insurancecommission]">Assic. a carico di</label>
                    <select disabled name="wgtntpro[insurancecommission]" id="wgtntpro_insurancecommission" class="form-control">
                        <option value="S">Mittente</option>
                        <option value="R">Destinatario</option>
                    </select>
                </div>


                <!-- INVOICE -->
                <div class="form-group">
                    <input type="checkbox" style="margin-right:10px" name="wgtntpro[invoice]" id="wgtntpro_invoice" value="Y" />Imposta valore fattura
                </div>
                <div class="form-group invoice">
                    <label class="control-label" for="wgtntpro[invoicevalue]">Importo fattura</label>
                    <input id="wgtntpro_invoicevalue" disabled type="text" name="wgtntpro[invoicevalue]" value="<?php echo virgola($_order->getGrandTotal()) ?>" class="validate-number my-number form-control" />
                </div>
                <div class="form-group invoice">
                    <label class="control-label" for="wgtntpro[invoicecurrency]">Valuta importo fattura</label>
                    <!-- adminhtml/system_config_source_currency -->
                    <select disabled name="wgtntpro[invoicecurrency]" class="form-control" id="wgtntpro_invoicecurrency">
                        <?php foreach(Mage::getModel('adminhtml/system_config_source_currency')->toOptionArray(FALSE) as $cur): ?>
                            <option value="<?php echo $cur['value']?>" <?php if ($cur['value']==$_order->getOrderCurrencyCode()) {echo " SELECTED ";} ?>><?php echo $cur['label']?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div id="domestic" style="margin-top:40px;display:none">
                    <h3 style="margin-bottom:20px">Spedizione nazionale</h3>
                    <div class="form-group">
                        <label class="control-label" for="wgtntpro[operationaloption]">Opzioni di consenga</label>
                        <select disabled class="form-control" name="wgtntpro[operationaloption]" id="wgtntpro_operationaloption">
                            <?php foreach (Mage::helper('wgtntpro')->toOperationalOptionArray() as $opt): ?>
                                <option value="<?php echo $opt['value'] ?>" ><?php echo $opt['label'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group" id ="wgtntpro_TNTDepot_span">
                        <label class="control-label" for="wgtntpro[dropoffpoint]">Codice filiare TNT</label>
                        <input disabled class="form-control" id="wgtntpro_dropoffpoint" type="text" name="wgtntpro[dropoffpoint]" length="5"/>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" style="margin-right:10px" name="wgtntpro[cashondelivery]" value="Y" id ="wgtntpro_cashondelivery"/>Contrassengo
                    </div>
                    <div class="form-group cashondelivery" style="margin-top:15px">
                        <label class="control-label" for="wgtntpro[codcommission]">Commissioni di contrassegno a carico del</label>
                        <select disabled class="form-control" name="wgtntpro[codcommission]" id="wgtntpro_codcommission">
                            <option value="S">Mittente</option>
                            <option value="R">Destinatario</option>
                        </select>
                    </div>
                    <div class="form-group cashondelivery">
                        <label class="control-label" for="wgtntpro[codfvalue]">Importo del contrassegno</label>
                        <input class="form-control" type="text" id="wgtntpro_codfvalue" name="wgtntpro[codfvalue]" disabled value="<?php echo virgola($_order->getGrandTotal()) ?>" class="req validate-number my-number" />
                    </div>
                    <div class="form-group cashondelivery">
                        <label class="control-label" for="wgtntpro[codfcurrency]">Valuta Importo del contrassegno</label>
                        <!-- adminhtml/system_config_source_currency -->
                        <select disabled class="form-control" name="wgtntpro[codfcurrency]" id="wgtntpro_codfcurrency">
                            <?php foreach(Mage::getModel('adminhtml/system_config_source_currency')->toOptionArray(FALSE) as $cur): ?>
                                <option value="<?php echo $cur['value']?>" <?php if ($cur['value']==$_order->getOrderCurrencyCode()) {echo " SELECTED ";} ?>><?php echo $cur['label']?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div id="international" style="margin-top:40px;display:none">
                    <h3 style="margin-bottom:20px" class="icon-head head-shipping-method">Spedizione internazionale</h3>
                    <div class="form-group">
                        <label class="control-label" for="wgtntpro[articles][tarif]">Codice Doganale</label>
                        <input disabled class="form-control" type="text" id="wgtntpro_articles_tarif" name="wgtntpro[articles][tarif]" length="5"/>
                    </div>
                    <?php foreach (Mage::helper('wgtntpro')->toInternationalOptionArray() as $k=>$v): ?>
                        <div class="form-group">
                            <input disabled style="margin-right:10px" type="checkbox" name="wgtntpro[options][<?php echo $k ?>]" id="wgtntpro_options" value="<?php echo $v['value'] ?>" /><?php echo $v['label'] ?>
                        </div>
                    <?php endforeach ?>
                    <?php if (in_array ( $_order->getPayment()->getMethod(), array ('checkmo', 'cashondelivery') )): ?>
                        <span class="field-row warning-msg" style="height: 2.5em; padding-left: 3em; padding-top: 1em;">
                            WARNING: checkmoney not avaiable on crosscountry shipment</span>
                    <?php endif; ?>
                </div>


                <p style="margin-top:30px;font-size:10px;float:left;width:100%">* Campi obbligatori</p>
                <div class="form-group" style="margin-top:30px">
                    <button name="salva" class="btn btn-success active" style="height: 35px;font-size: 15px;"  type="submit">Salva</button>
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
<script src="//oss.maxcdn.com/momentjs/2.8.2/moment.min.js"></script>
<script src="assets/plugins/validator/bootstrapValidator.js"></script>



<script src="assets/plugins/validator/example.js"></script>


<!-- MAIN APPS JS -->
<script src="assets/js/apps.js"></script>

<script type="text/javascript">
    jQuery( document ).ready(function() {
        var countryTo = "<?php echo $_countryTo ?>";
        $=jQuery;

        $('#wgtntpro_TNTDepot_span').hide()
        $('#wgtntpro_operationaloption').change(function() {
            if ($('#wgtntpro_operationaloption').val()==3) {
                $('#wgtntpro_TNTDepot_span').show();
                $('#wgtntpro_dropoffpoint').prop('disabled', false);
            } else {
                $('#wgtntpro_TNTDepot_span').hide();
                $('#wgtntpro_dropoffpoint').prop('disabled', true);
            }
        });

        $('div.insurance').each( function (ele) {
            $(this).hide();
        });
        $('#wgtntpro_insurance').bind('change', function () {
            if ($('#wgtntpro_insurance').is(':checked') ==true) {
                $('div.insurance').each( function (ele) {
                    $(this).show();
                });
                $('#wgtntpro_insurancecurrency').prop('disabled', false);
                $('#wgtntpro_insurancevalue').prop('disabled', false);
                $('#wgtntpro_insurancecommission').prop('disabled', false);
            } else {
                $('div.insurance').each( function (ele) {
                    $(this).hide();
                });
                $('#wgtntpro_insurancecurrency').prop('disabled', true);
                $('#wgtntpro_insurancevalue').prop('disabled', true);
                $('#wgtntpro_insurancecommission').prop('disabled', true);
            }
        });

        $('div.invoice').each( function (ele) {
            $(this).hide();
        });
        $('#wgtntpro_invoice').bind('change', function () {
            if ($('#wgtntpro_invoice').is(':checked') ==true) {
                $('div.invoice').each( function (ele) {
                    $(this).show();
                });
                $('#wgtntpro_invoicevalue').prop('disabled', false);
                $('#wgtntpro_invoicecurrency').prop('disabled', false);
            } else {
                $('div.invoice').each( function (ele) {
                    $(this).hide();
                });
                $('#wgtntpro_invoicevalue').prop('disabled', true);
                $('#wgtntpro_invoicecurrency').prop('disabled', true);
            }
        });

        $('#magazzino').bind('change', wgtntpro_check );
        function wgtntpro_check() {
            if ('IT' == countryTo || 'SM' == countryTo || 'VA'==countryTo) { //caso speciale san marino/vatican city da italia
                $('#domestic').show();
                $('#wgtntpro_operationaloption').prop('disabled', false);
                $('#wgtntpro_codcommission').prop('disabled', true);
                $('#wgtntpro_codfvalue').prop('disabled', true);
                $('#wgtntpro_codfcurrency').prop('disabled', true);
                $('#wgtntpro_articles_tarif').prop('disabled', true);
                $('#wgtntpro_options').prop('disabled', true);
                $('#international').hide();
                var inter = $('option.INT');
                for (var x = 0; x < inter.length; x++) {
                    inter[x].setAttribute('disabled','disabled');
                    inter[x].removeAttribute('SELECTED');
                    $(inter[x]).hide();
                }
                var inter = $('option.DOM');
                for (var x = 0; x < inter.length; x++) {
                    inter[x].removeAttribute('disabled');
                    $(inter[x]).show();
                    if (x==0) {
                        inter[x].setAttribute('SELECTED','SELECTED');
                    }
                }
                $('#magazzino').val(1);
            } else {
                $('div.invoice').each( function (ele) {
                    $(this).show();
                });
                $('#domestic').hide();
                $('#international').show();
                var inter = $('option.DOM');
                for (var x = 0; x < inter.length; x++) {
                    inter[x].setAttribute('disabled','disabled');
                    inter[x].removeAttribute('SELECTED');
                    $(inter[x]).hide();
                }
                var inter = $('option.INT');
                for (var x = 0; x < inter.length; x++) {
                    inter[x].removeAttribute('disabled');
                    $(inter[x]).show();
                    if (x==0) {
                        inter[x].setAttribute('SELECTED','SELECTED');
                    }
                }
                $('#magazzino').val(2);

                $('#wgtntpro_operationaloption').prop('disabled', true);
                $('#wgtntpro_codcommission').prop('disabled', true);
                $('#wgtntpro_codfvalue').prop('disabled', true);
                $('#wgtntpro_codfcurrency').prop('disabled', true);
                $('#wgtntpro_articles_tarif').prop('disabled', false);
                $('#wgtntpro_options').prop('disabled', false);
            }
        }
        wgtntpro_check();


        $('#wgtntpro_cashondelivery').bind('change', function () {
            if ($('#wgtntpro_cashondelivery').is(':checked') ==true) {
                $('div.cashondelivery').each( function (ele) {
                    $(this).show();
                });

                $('#wgtntpro_codcommission').removeAttr("disabled");
                $('#wgtntpro_codfvalue').removeAttr("disabled");
                $('#wgtntpro_codfcurrency').removeAttr("disabled");

            } else {
                $('div.cashondelivery').each( function (ele) {
                    $(this).hide();
                });

                $('#wgtntpro_codcommission').prop('disabled', true);
                $('#wgtntpro_codfvalue').prop('disabled', true);
                $('#wgtntpro_codfcurrency').prop('disabled', true);

            }
        });
        $('div.cashondelivery').each( function (ele) {
            $(this).hide();
        });





    });




</script>
</body>
</html>

