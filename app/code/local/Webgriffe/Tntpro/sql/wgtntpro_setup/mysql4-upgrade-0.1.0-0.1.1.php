<?php

$installer = $this;
$installer->startSetup();

$sql = "
ALTER TABLE `{$installer->getTable('wgtntpro/consignmentno')}` ADD COLUMN cancelled int(1) DEFAULT 0;
";

$installer->run($sql);

$installer->endSetup();