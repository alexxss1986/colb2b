<?php

class Webgriffe_Tntpro_Helper_Addresswrapper extends Varien_Object
{
    /**
     * Map short fields names to its full names
     *
     * @var array
    protected $_oldFieldsMap = array(
        'city' => 'town',
        'country_id' => 'country',
        'street' => 'addrline1',
        'region' => 'province',
        'vat_number' => 'vatno',
        'fax' => 'fax2',
    );
     */

    public function getStreet($line=0)
    {
        $street = parent::getData('street');
        if (-1 === $line) {
            return $street;
        } else {
            $arr = is_array($street) ? $street : explode("\n", $street);
            if (0 === $line || $line === null) {
                return $arr;
            } elseif (isset($arr[$line-1])) {
                return $arr[$line-1];
            } else {
                return '';
            }
        }
    }

    public function getTown() {
        return $this->getCity();
    }
    public function getCountry() {
        return $this->getCountryId();
    }
    public function getAddrline1() {
        $arra = $this->getStreet();
        $ret = substr( implode(' ', $arra) ,0 ,35);
        return $ret;
    }
    public function getVatno() {
        return $this->getVatNumber();
    }
    public function getProvince() {
        return $this->getRegion();
    }
    public function getFax2() {
        return $this->getFax();
    }

    public function init($obj) {
        $this->setData($obj->getData());
        $this->setStreetFull($obj->getStreetFull());
        return $this;
    }

    private function __phone1len($telefono) {
        return floor(strlen($telefono)/3);
    }

    public function getPhone1() {
        $telefono = $this->_data['telephone'];
        $my_len = $this->__phone1len($telefono);
        return substr($telefono,0,$my_len);
    }
    public function getPhone2() {
        $telefono = $this->_data['telephone'];
        $not_my_len = $this->__phone1len($telefono);
        return substr($telefono,$not_my_len);
    }

    public function getName() {
        if ( (is_null($this->_data['company'])) || (empty($this->_data['company'])) ) {
            return $this->_data['firstname'].' '.$this->_data['lastname'];
        } else {
            return $this->_data['company'];
        }

    }

    public function getContactname() {
        return $this->_data['firstname'].' '.$this->_data['lastname'];
    }

}