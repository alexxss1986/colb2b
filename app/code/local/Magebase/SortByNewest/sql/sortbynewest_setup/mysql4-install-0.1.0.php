<?php
/**
 * Magebase
 *
 * @category    Magebase
 * @package     Magebase_SortByNewest
 * @copyright   Copyright (c) 2011 Magebase (http://magebase.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

$installer = $this;

$installer->startSetup();

$productEntityTypeId = $installer->getEntityTypeId('catalog_product');
$installer->updateAttribute($productEntityTypeId, 'created_at', 'frontend_label', 'Newest');
$installer->updateAttribute($productEntityTypeId, 'created_at', 'used_for_sort_by', 1);

$installer->endSetup();
