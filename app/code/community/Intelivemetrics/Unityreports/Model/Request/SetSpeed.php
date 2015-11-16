<?php

/**
 * Set sync speed action
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */

class Intelivemetrics_Unityreports_Model_Request_SetSpeed extends Intelivemetrics_Unityreports_Model_Request_Base implements Intelivemetrics_Unityreports_Model_Request_Interface {

    private $_speeds = array(
        'very_slow' => 10,
        'slow' => 20,
        'normal' => 50,
        'fast' => 100,
        'very_fast' => 200
    );
   

    public function execute($settings=array()) {
        $speed = $settings[0];
        Intelivemetrics_Unityreports_Model_Utils::setConfig('max_items_per_sync', $this->_speeds[$speed]);
        
        return $out = array(
            'done' => true,
        );
    }

}

?>
