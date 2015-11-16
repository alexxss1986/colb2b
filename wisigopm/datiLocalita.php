<?php


session_cache_limiter('nocache');
session_start();


function getService()
{
    // Creates and returns the Analytics service object.

    // Load the Google API PHP Client Library.
    require_once ('Google/autoload.php');

    // Use the developers console and replace the values with your
    // service account email, and relative location of your key file.
    $service_account_email = '792504868628-uib7abirgp8al31shouif8bhmt06vo5r@developer.gserviceaccount.com';
    $key_file_location = 'ColtortiBoutique-eeaf4ef152ef.p12';

    // Create and configure a new client object.
    $client = new Google_Client();
    $client->setApplicationName("HelloAnalytics");
    $analytics = new Google_Service_Analytics($client);

    // Read the generated client_secrets.p12 key.
    $key = file_get_contents($key_file_location);
    $cred = new Google_Auth_AssertionCredentials(
        $service_account_email,
        array(Google_Service_Analytics::ANALYTICS_READONLY),
        $key
    );
    $client->setAssertionCredentials($cred);
    if($client->getAuth()->isAccessTokenExpired()) {
        $client->getAuth()->refreshTokenWithAssertion($cred);
    }

    return $analytics;
}

function getFirstprofileId(&$analytics) {
    // Get the user's first view (profile) ID.

    // Get the list of accounts for the authorized user.
    $accounts = $analytics->management_accounts->listManagementAccounts();

    if (count($accounts->getItems()) > 0) {
        $items = $accounts->getItems();
        $firstAccountId = $items[0]->getId();

        // Get the list of properties for the authorized user.
        $properties = $analytics->management_webproperties
            ->listManagementWebproperties($firstAccountId);

        if (count($properties->getItems()) > 0) {
            $items = $properties->getItems();
            $firstPropertyId = $items[0]->getId();



            // Get the list of views (profiles) for the authorized user.
            $profiles = $analytics->management_profiles
                ->listManagementProfiles($firstAccountId, $firstPropertyId);

            if (count($profiles->getItems()) > 0) {
                $items = $profiles->getItems();

                foreach ($items as $item){
                    if ($item->getName()=="Coltorti Filtri SPAM"){
                        $idAccount=$item->getId();
                    }
                }
                // Return the first view (profile) ID.
                return $idAccount;

            } else {
                throw new Exception('No views (profiles) found for this user.');
            }
        } else {
            throw new Exception('No properties found for this user.');
        }
    } else {
        throw new Exception('No accounts found for this user.');
    }
}


function getSessions($analytics, $profileId,$statsStartDate,$statsEndDate) {
    // Calls the Core Reporting API and queries for the number of sessions
    // for the last seven days.
    $optParams = array(
        'dimensions' => 'ga:country',
        'sort' => 'ga:country'
    );
    $results=$analytics->data_ga->get(
        'ga:' . $profileId,
        $statsStartDate,
        $statsEndDate,
        'ga:sessions',
        $optParams);

    $rows = $results->getRows();
    return $rows;
}

function getUsers(&$analytics, $profileId,$statsStartDate,$statsEndDate) {
    // Calls the Core Reporting API and queries for the number of sessions
    // for the last seven days.
    $optParams = array(
        'dimensions' => 'ga:country',
        'sort' => 'ga:country'
    );


    $results=$analytics->data_ga->get(
        'ga:' . $profileId,
        $statsStartDate,
        $statsEndDate,
        'ga:users',
        $optParams);

    $rows = $results->getRows();
    return $rows;
}


function getPageViews(&$analytics, $profileId,$statsStartDate,$statsEndDate) {
    // Calls the Core Reporting API and queries for the number of sessions
    // for the last seven days.
    $optParams = array(
        'dimensions' => 'ga:country',
        'sort' => 'ga:country'
    );
    $results=$analytics->data_ga->get(
        'ga:' . $profileId,
        $statsStartDate,
        $statsEndDate,
        'ga:pageviews',
        $optParams);

    $rows = $results->getRows();
    return $rows;

}

function getPageViewsSessions(&$analytics, $profileId,$statsStartDate,$statsEndDate) {
    // Calls the Core Reporting API and queries for the number of sessions
    // for the last seven days.
    $optParams = array(
        'dimensions' => 'ga:country',
        'sort' => 'ga:country'
    );
    $results=$analytics->data_ga->get(
        'ga:' . $profileId,
        $statsStartDate,
        $statsEndDate,
        'ga:pageviewsPerSession',
        $optParams);

    $rows = $results->getRows();
    return $rows;
}


function getAvgSessionDuration(&$analytics, $profileId,$statsStartDate,$statsEndDate) {
    // Calls the Core Reporting API and queries for the number of sessions
    // for the last seven days.
    $optParams = array(
        'dimensions' => 'ga:country',
        'sort' => 'ga:country'
    );
    $results=$analytics->data_ga->get(
        'ga:' . $profileId,
        $statsStartDate,
        $statsEndDate,
        'ga:avgSessionDuration',
        $optParams);

    $rows = $results->getRows();
    return $rows;
}


function getBounceRate(&$analytics, $profileId,$statsStartDate,$statsEndDate) {
    // Calls the Core Reporting API and queries for the number of sessions
    // for the last seven days.
    $optParams = array(
        'dimensions' => 'ga:country',
        'sort' => 'ga:country'
    );
    $results=$analytics->data_ga->get(
        'ga:' . $profileId,
        $statsStartDate,
        $statsEndDate,
        'ga:bounceRate',
        $optParams);

    $rows = $results->getRows();
    return $rows;
}

function getPercentNewSessions(&$analytics, $profileId,$statsStartDate,$statsEndDate) {
    // Calls the Core Reporting API and queries for the number of sessions
    // for the last seven days.
    $optParams = array(
        'dimensions' => 'ga:country',
        'sort' => 'ga:country'
    );
    $results=$analytics->data_ga->get(
        'ga:' . $profileId,
        $statsStartDate,
        $statsEndDate,
        'ga:percentNewSessions',
        $optParams);

    $rows = $results->getRows();
    return $rows;
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="it">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Dati per località | Wisigo Product Management</title>
    <script type="text/javascript" src="js/controlloform.js"></script>
    <script type="text/javascript">

        function stopRKey(evt) {
            var evt = (evt) ? evt : ((event) ? event : null);
            var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
            if ((evt.keyCode == 13) && (node.type=="text") && (node.id=="sku"))  { recuperaProdottiAggiorna(); return false;}
        }

        document.onkeypress = stopRKey;

    </script>

    <!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- PLUGINS CSS -->
    <link href="assets/plugins/icheck/skins/all.css" rel="stylesheet">
    <link href="assets/plugins/datepicker/datepicker.min.css" rel="stylesheet">
    <link href="assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="assets/plugins/datatable/css/bootstrap.datatable.min.css" rel="stylesheet">
    <link href="assets/plugins/slider/slider.min.css" rel="stylesheet">

    <!-- MAIN CSS (REQUIRED ALL PAGE)-->
    <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

</head>
<body class="tooltips">
<?php
if (isset($_SESSION['username'])){
    include("config/percorsoMage.php");
    require_once $MAGE;
    Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

    if (isset($_REQUEST['data_da']) && isset($_REQUEST['data_a'])) {


        $dataI=$_REQUEST['data_da'];
        $dataF=$_REQUEST['data_a'];


        $datainizio=substr($_REQUEST['data_da'],0,10);
        $rsl = explode ('/',$datainizio);
        $rsl = array_reverse($rsl);
        $datainizio=implode($rsl,'-');

        $statsStartDate = $datainizio;


        $datafine=substr($_REQUEST['data_a'],0,10);
        $rsl = explode ('/',$datafine);
        $rsl = array_reverse($rsl);
        $datafine=implode($rsl,'-');

        $statsEndDate   = $datafine;

        $analytics = getService();
        $profile = getFirstProfileId($analytics);
        $sessioni=getSessions($analytics, $profile,$statsStartDate,$statsEndDate);
        $utenti=getUsers($analytics, $profile,$statsStartDate,$statsEndDate);
        $visualizzazioni=getPageViews($analytics, $profile,$statsStartDate,$statsEndDate);
        $pagsess=getPageViewsSessions($analytics, $profile,$statsStartDate,$statsEndDate);
        $duratasessmedia=getAvgSessionDuration($analytics, $profile,$statsStartDate,$statsEndDate);
        $bounce=getBounceRate($analytics, $profile,$statsStartDate,$statsEndDate);
        $nuovesess=getPercentNewSessions($analytics, $profile,$statsStartDate,$statsEndDate);




    }
    else {

        $Date = date("Y-m-d");
        $dataF=date('Y-m-d', strtotime($Date. ' - 1 days'));
        $dataI=date('Y-m-d', strtotime($dataF. ' - 1 months'));

        $dataI=substr($dataI,0,10);
        $rsl = explode ('-',$dataI);
        $rsl = array_reverse($rsl);
        $dataI=implode($rsl,'/');

        $dataF=substr($dataF,0,10);
        $rsl = explode ('-',$dataF);
        $rsl = array_reverse($rsl);
        $dataF=implode($rsl,'/');
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
                <h1 class="page-heading">Località<!--<small>Sub heading here</small>--></h1>
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
                    <form name="form_accessi" id="form_accessi" method="post" action="datiLocalita.php">
                        <div id="form" style="width:100%">
                            <div class="form-group" style="float:left">
                                <label class="control-label" style="float:left;line-height:34px">Dal</label>
                                <input type="text" style="float:left;margin-left:20px;width:150px" class="form-control" data-date-format="dd/mm/yyyy" placeholder="dd/mm/yyyy" name="data_da" id="datepicker3" value="<?php echo $dataI ?>" >
                                <label class="control-label" style="float:left;line-height:34px;margin-left:50px">Al</label>
                                <input type="text" style="float:left;margin-left:20px;width:150px" class="form-control" data-date-format="dd/mm/yyyy" placeholder="dd/mm/yyyy" name="data_a" id="datepicker4" value="<?php echo $dataF ?>" >
                                <button name="conferma" class="btn btn-success active" style="height: 35px;font-size: 15px;float:left;margin-left:50px"  type="button" onclick="controlloFormAccessi()"/>Invia</button>
                            </div>

                        </div>
                    </form>
                    <?php if (isset($_REQUEST['data_da']) && isset($_REQUEST['data_a'])) { ?>
                        <div style="float:left;width:100%;margin-top:10px;">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-th-block" id="datatable-dati">
                                    <thead class="the-box dark full">
                                    <tr>
                                        <th>Paese</th>
                                        <th>Sessioni</th>
                                        <th>Utenti</th>
                                        <th>Visualizzazioni di pagina</th>
                                        <th>Pagine / sessione</th>
                                        <th>Durata sessione media</th>
                                        <th>Frequenza di rimbalzo</th>
                                        <th>% Nuove sessioni</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php


                                    for ($i=0; $i<count($sessioni); $i++){

                                        $hours = floor($duratasessmedia[$i][1] / 3600);
                                        $mins = floor(($duratasessmedia[$i][1] - ($hours*3600)) / 60);
                                        $secs = floor($duratasessmedia[$i][1] % 60);
                                        if (strlen($hours)==1){
                                            $hours="0".$hours;
                                        }
                                        if (strlen($mins)==1){
                                            $mins="0".$mins;
                                        }
                                        if (strlen($secs)==1){
                                            $secs="0".$secs;
                                        }
                                        $time=$hours.":".$mins.":".$secs;



                                        echo "<tr>";
                                        echo "<td>";
                                        echo $sessioni[$i][0];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $sessioni[$i][1];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $utenti[$i][1];
                                        echo "</td>";
                                        echo "<td>";
                                        echo $visualizzazioni[$i][1];
                                        echo "</td>";
                                        echo "<td>";
                                        echo number_format($pagsess[$i][1],"2",",",".");
                                        echo "</td>";
                                        echo "<td>";
                                        echo $time;
                                        echo "</td>";
                                        echo "<td>";
                                        echo number_format($bounce[$i][1],"2",",",".");
                                        echo "</td>";
                                        echo "<td>";
                                        echo number_format($nuovesess[$i][1],"2",",",".");
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div><!-- /.table-responsive -->

                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php include("config/footer.php") ?>
        </div>
    </div>

<?php
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
<script src="assets/plugins/icheck/icheck.min.js"></script>
<script src="assets/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="assets/plugins/timepicker/bootstrap-timepicker.js"></script>
<script src="assets/plugins/slider/bootstrap-slider.js"></script>
<script src="assets/plugins/datatable/js/jquery.dataTables.js"></script>
<script src="assets/plugins/datatable/js/bootstrap.datatable.js"></script>



<!-- MAIN APPS JS -->
<script src="assets/js/tabella.js"></script>




<!-- MAIN APPS JS -->
<script src="assets/js/apps.js"></script>
</body>
</html>