<?php
require_once '../../app/Mage.php';
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);

Mage::app()->removeCache('catalog_rules_dirty');

echo "FINE";