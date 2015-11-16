<?php
/**
 * NOTICE OF LICENSE
 *
 * You may not sell, sub-license, rent or lease
 * any portion of the Software or Documentation to anyone.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @category   ET
 * @package    ET_IpSecurity
 * @copyright  Copyright (c) 2012 ET Web Solutions (http://etwebsolutions.com)
 * @contacts   support@etwebsolutions.com
 * @license    http://shop.etwebsolutions.com/etws-license-free-v1/   ETWS Free License (EFL1)
 */

class ET_IpSecurity_Block_Adminhtml_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('etipsecuritylogGrid');
        $this->setDefaultSort('update_time');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('etipsecurity/ipsecuritylog')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('blocked_ip', array(
            'header' => Mage::helper('etipsecurity')->__('Blocked IP'),
            'align' => 'left',
            'width' => '150px',
            'index' => 'blocked_ip',
        ));

        $this->addColumn('qty', array(
            'header' => Mage::helper('etipsecurity')->__('Qty blocked'),
            'align' => 'left',
            'width' => '100px',
            'index' => 'qty',
            'type' => 'number',
        ));

        $this->addColumn('last_block_rule', array(
            'header' => Mage::helper('etipsecurity')->__('Last block rule'),
            'align' => 'left',
            'width' => '300px',
            'index' => 'last_block_rule',
            'renderer' => 'etipsecurity/adminhtml_log_renderer_translaterule',
            'filter' => false,
        ));

        $this->addColumn('create_time', array(
            'header' => Mage::helper('etipsecurity')->__('First block'),
            'align' => 'left',
            'width' => '160px',
            'index' => 'create_time',
            'type' => 'datetime',
        ));

        $this->addColumn('update_time', array(
            'header' => Mage::helper('etipsecurity')->__('Last block'),
            'align' => 'left',
            'width' => '160px',
            'index' => 'update_time',
            'type' => 'datetime',
        ));

        $this->addColumn('blocked_from', array(
            'header' => Mage::helper('etipsecurity')->__('Blocked from'),
            'align' => 'left',
            //'width'     => '100px',
            'index' => 'blocked_from',
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('customer')->__('CSV'));
        $this->addExportType('*/*/exportXml', Mage::helper('customer')->__('Excel XML'));

        return parent::_prepareColumns();
    }
}
