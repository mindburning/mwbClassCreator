<?php
include_once 'mwbColumn.php';

/**
 * Description of mwbIterator
 */
class mwbIterator {
	public $_items = array();
	
	/**
	 * neues element erzeugen
	 * @return \mwbColumn
	 */
	public function getNewItem(){
		return new mwbColumn();
	}
	
	/**
	 * magic get
	 * @param string $name
	 * @return \mwbColumn
	 */
	public function __get($name) {
		if( isset( $this->_items[$name] ) ){
			return $this->_items[$name];
		}
		return $this->getNewItem();
	}
	
	/**
	 * magic set
	 * @param string $name
	 * @param \mwbColumn $value
	 */
	public function __set($name, $value) {
		$this->_items[$name] = $value;
		return $this;
	}
}
