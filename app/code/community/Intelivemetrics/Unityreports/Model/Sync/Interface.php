<?php

/**
 * Interface for all sync classes
 *
 * @category  Unityreports
 * @package   Intelivemetrics_Unityreports
 * @copyright Copyright (c) 2014 Intelive Metrics Srl
 * @author    Eduard Gabriel Dumitrescu (balaur@gmail.com)
 */
interface Intelivemetrics_Unityreports_Model_Sync_Interface {

    public function saveSyncedItems($response);

    public function markSentItems(array $items);
}

?>
