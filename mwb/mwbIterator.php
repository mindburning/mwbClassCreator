<?php
include_once './mwbColumn.php';

/**
 * Description of mwbIterator
 */
class mwbIterator {
	public $_items = array();
	
	public function getNewItem(){
		return new mwbColumn();
	}
	
	public function __get($name) {
		if( isset( $this->_items[$name] ) ){
			return $this->_items[$name];
		}
		return $this->getNewItem();
	}
	
	public function __set($name, $value) {
		$this->_items[$name] = $value;
		return $this;
	}
}
