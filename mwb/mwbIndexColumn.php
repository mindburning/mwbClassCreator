<?php

/**
 * Description of mwbIndexColumn
 */
class mwbIndexColumn {
	protected static $instances = array();
	protected static $instancesByID = array();
	
	/**
	 * erzeugt neue Instanz
	 * @param SimpleXMLElement $data
	 * @return \mwbIndexColumn
	 */
	public static function getInstance($data) {
		$inst = new mwbIndexColumn();
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
