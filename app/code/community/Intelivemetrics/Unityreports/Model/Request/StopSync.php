<?php

/**
 * Stop sync action
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */

class Intelivemetrics_Unityreports_Model_Request_StopSync extends Intelivemetrics_Unityreports_Model_Request_Base implements Intelivemetrics_Unityreports_Model_Request_Interface {

    public function execute($settings=array()) {
        Intelivemetrics_Unityreports_Model_Utils::log(__METHOD__);

        //stop syncing
        $this->stopSync();

        //alles gut
        return $out = array(
            'done' => true,
        );
    }

}

?>
