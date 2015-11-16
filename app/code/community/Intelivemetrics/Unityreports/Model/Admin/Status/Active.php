<?php

/**
 * Options for on/off selector
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */

class Intelivemetrics_Unityreports_Model_Admin_Status_Active extends Mage_Core_Model_Abstract {

    public function toOptionArray()
    {
        return array(
            array('value'=>1, 'label'=>'On'),
            array('value'=>0, 'label'=>'Off')
        );
            
    }

}

?>
