<?php

class Webgriffe_Tntpro_Block_Adminhtml_Consignmentno_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('ConsignmentnoGrid');
        $this->setDefaultSort('consignmentno');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        //$model = Mage::getModel('wgtntpro/magazzini');
        //$collection = $model->getCollection();
        $collection = Mage::getResourceModel('wgtntpro/consignmentno_collection');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('consignmentno', array(
            'header' => Mage::helper('wgtntpro')->__('Consignment Number'),
            'align' => 'left',
            'width' => '20px',
            'index' => 'consignmentno',
        ));

        $this->addColumn('ok', array(
            'header' => Mage::helper('wgtntpro')->__('Ok'),
            'align' => 'left',
            'width' => '50px',
            'index' => 'ok',
            'type' => 'options',
            'options' => array(
                0 => Mage::helper('wgtntpro')->__('No'),
                1 => Mage::helper('wgtntpro')->__('Sì')
            ),
        ));

        $this->addColumn('track', array(
            'header' => Mage::helper('wgtntpro')->__('Track'),
            'align' => 'left',
            //'width' => '20px',
            'index' => 'track',
        ));

        $this->addColumn('created_at', array(
            'header' => Mage::helper('wgtntpro')->__('Created At'),
            'align' => 'left',
            'width' => '100px',
            'index' => 'created_at',
        ));

        $this->addColumn('domestic', array(
            'header' => Mage::helper('wgtntpro')->__('Domestic'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'domestic',
            'type' => 'options',
            'options' => array(
                0 => Mage::helper('wgtntpro')->__('international'),
                1 => Mage::helper('wgtntpro')->__('domestic')
            ),
        ));

        $this->addColumn('fk_magazzino_id', array(
            'header' => Mage::helper('wgtntpro')->__('Id Magazzino'),
            'align' => 'right',
            'width' => '50px',
            'index' => 'fk_magazzino_id',
        ));

        $this->addColumn('cancel',
            array(
                'header'    => Mage::helper('wgtntpro')->__('Cancel'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getConsignmentno',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('wgtntpro')->__('Cancel'),
                        'url'     => array('base'=>'*/*/cancel',),
                        'field'   => 'consignmentno',
                        'confirm' => Mage::helper('wgtntpro')->__('CANCEL: Are you sure ?'),
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true,
                'renderer' => 'Webgriffe_Tntpro_Block_Adminhtml_Consignmentno_Render_CancelRender',
        ));

        $this->addColumn('toshipment',
            array(
                'header'    => Mage::helper('wgtntpro')->__('Goto shipment'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getFkParentId',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('wgtntpro')->__('Goto shipment'),
                        'url'     => array('base'=>'adminhtml/sales_shipment/view',),
                        'field'   => 'shipment_id',
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true,
        ));

        return parent::_prepareColumns();
    }
/*
    public function getRowUrl($row)
    {
        //azione al click sulla riga
        return $this->getUrl('adminhtml/sales_shipment/view', array(
                    'shipment_id' => $row->getFkParentId(),
                        )
        );
    }
*/

    /**
     * Prepare massaction
     *
     * @return Webgriffe_Tntpro_Block_Adminhtml_Consignmentno_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('consignmentno');
        $this->getMassactionBlock()->setFormFieldName('consignmentno');
        $this->getMassactionBlock()->setUseSelectAll(false);

        $this->getMassactionBlock()->addItem('enable', array(
            'label'         => Mage::helper('wgtntpro')->__('Request Manifest'),
            'url'           => $this->getUrl('*/*/requestManifest'),
            'selected'      => true,
        ));

        return $this;
    }
}