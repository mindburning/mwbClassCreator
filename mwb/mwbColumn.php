<?php

/**
 * Description of mwbColumn
 */
class mwbColumn {
	protected static $instances = array();
	protected static $instancesByID = array();
	
	/**
	 * erzeugt neue Instanz
	 * @param mixed $data
	 * @return \mwbColumn erzeugt eine neue Spalte
	 */
	public static function getInstance($data) {
		$inst = new mwbColumn();
		if( $data instanceof SimpleXMLElement ){
			mwbUtils::assign($inst, $data);
		}else{
			if(isset(self::$instancesByID[$data])){
				return self::$instancesByID[$data];
			}
		}
		self::$instances[] = &$inst;
		if($inst->id != ''){
			self::$instancesByID[$inst->id] = &$inst;
		}
		return $inst;
	}
}
