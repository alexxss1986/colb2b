<?php

class Webgriffe_Tntpro_Block_Adminhtml_Magazzini_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('MagazziniGrid');
		$this->setDefaultSort('magazzino_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}

	protected function _prepareCollection()
	{
		//$model = Mage::getModel('wgtntpro/magazzini');
		//$collection = $model->getCollection();
		$collection = Mage::getResourceModel('wgtntpro/magazzini_collection');
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	protected function _prepareColumns()
	{
		$this->addColumn('magazzino_id', array(
			'header' => Mage::helper('wgtntpro')->__('ID'),
			'align' =>'left',
			'width' => '1px',
			'index' => 'magazzino_id',
		));

		$this->addColumn('name', array(
			'header' => Mage::helper('wgtntpro')->__('Nome'),
			'align' =>'left',
			'width' => '50px',
			'index' => 'name',
		));

		$this->addColumn('sender_acc_id', array(
			'header' => Mage::helper('wgtntpro')->__('Account'),
			'align' =>'left',
			'width' => '50px',
			'index' => 'sender_acc_id',
		));

		$this->addColumn('vatno', array(
			'header' => Mage::helper('wgtntpro')->__('VAT Number'),
			'align' =>'left',
			'width' => '50px',
			'index' => 'vatno',
		));

		$this->addColumn('addrline1', array(
			'header' => Mage::helper('wgtntpro')->__('Indirizzo'),
			'align' =>'left',
			'width' => '50px',
			'index' => 'addrline1',
		));

		$this->addColumn('town', array(
			'header' => Mage::helper('wgtntpro')->__('Città'),
			'align' =>'left',
			'width' => '50px',
			'index' => 'town',
		));

		$this->addColumn('province', array(
			'header' => Mage::helper('wgtntpro')->__('Provincia'),
			'align' =>'left',
			'width' => '50px',
			'index' => 'province',
		));

		$this->addColumn('postcode', array(
			'header' => Mage::helper('wgtntpro')->__('CAP'),
			'align' =>'left',
			'width' => '50px',
			'index' => 'postcode',
		));

		$this->addColumn('country', array(
			'header' => Mage::helper('wgtntpro')->__('Nazione'),
			'align' =>'left',
			'width' => '50px',
			'index' => 'country',
			'type' => 'country',
		));

		$this->addColumn('contactname', array(
			'header' => Mage::helper('wgtntpro')->__('Contact Name'),
			'align' =>'left',
			'width' => '50px',
			'index' => 'contactname',
		));

		$this->addColumn('phone1', array(
			'header' => Mage::helper('wgtntpro')->__('Pref. Telefono'),
			'align' =>'left',
			'width' => '50px',
			'index' => 'phone1',
		));
		$this->addColumn('phone2', array(
			'header' => Mage::helper('wgtntpro')->__('Num. Telefono'),
			'align' =>'left',
			'width' => '50px',
			'index' => 'phone2',
		));

		$this->addColumn('email', array(
			'header' => Mage::helper('wgtntpro')->__('Email'),
			'align' =>'left',
			'width' => '50px',
			'index' => 'email',
		));

		$this->addColumn('default', array(
			'header' => Mage::helper('wgtntpro')->__('default'),
			'align' =>'right',
			'width' => '50px',
			'index' => 'default',
			'defaul' => 0,
			'renderer' => 'Webgriffe_Tntpro_Block_Adminhtml_Magazzini_Grid_Sino',
		));

		return parent::_prepareColumns();
	}

	public function getRowUrl($row)
	{
		//azione al click sulla riga
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}


}