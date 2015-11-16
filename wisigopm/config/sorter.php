<?php
/**
* ArraySorter
*
* Copyright (c) 2011 Przemek Berezowski (przemek@otn.pl)
* All rights reserved.
*
* @category	  Library
* @package  	 ArraySorter
* @copyright     Copyright (c) 2011 Przemek Berezowski (przemek@otn.pl)
* @version       0.9
* @license       New BSD License
*/
class ArraySorter {
	
	const DIRECTION_ASC = 1;
	const DIRECTION_DESC = 2;

	/**
	 * 
	 * @var array - array to sort
	 */
	private $array2sort;
	
	/**
	 * 
	 * @var string - string to describe field position
	 */
	private	$sortField;
	private $direction = 1;
	
	/**
	 * Construct
	 * @param array $array2sort
	 */
	public function __construct($array2sort = array()) {
		$this->setArray($array2sort);
	}
	
	/**
	 * Setter to assign array to sort to a class
	 *  
	 * @param unknown_type $array2sort
	 */
	public function setArray($array2sort) {
		$this->array2sort = $array2sort;
	}
	
	/**
	 * 
	 * Sort method
	 * @param string $field - string to describe field position
	 * @param int $direction - based on class constants
	 * @return array - sorted array:
	 */
	public function sort($field, $direction) {
		$this->sortField = explode('.', $field);
		$this->direction = $direction;
		usort($this->array2sort, array($this, 'doSort'));
		return $this->array2sort;
	}
	
	/**
	 * 
	 * Callback function to compare elements of sorting array
	 * @param mixed $a
	 * @param mixed $b
	 * @return int;
	 */
	protected function doSort($a, $b) {
		
		$cmp1 = $this->getSortField($a);
		$cmp2 = $this->getSortField($b);
		if ($cmp1 == $cmp2) {
			return 0;
		}
		
		if ($this->direction == self::DIRECTION_ASC) {
			return ($cmp1 < $cmp2) ? -1 : 1;
		} else {
			return ($cmp1 < $cmp2) ? 1 : -1;
		}
	}
	
	/**
	 * method to find value to campare
	 * @param mixed $sortElement
	 */
	protected function getSortField($sortElement) {
		
		$val = $sortElement;
		
		foreach($this->sortField as $key) {
			
			if (is_object($val) && isset($val->$key)) {
				$val = $val->$key;
			} elseif (isset($val[$key])) {
				$val = $val[$key];
			} else {
				break;
			}
		}
		return $val;
	}
	
	
}