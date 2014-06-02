<?php
include_once 'mwbUtils.php';
include_once 'mwbColumns.php';
include_once 'mwbForeignKeys.php';
include_once 'mwbIndexColumns.php';

/**
 * mwb Tabelle
 */
class mwbTable {
	public $id = null;
	
	/**
	 * @var mwbColumns
	 */
	public $columns = null;
	
	/**
	 * @var mwbForeignKeys
	 */
	public $foreignKeys = null;
	
	/**
	 * @var mwbIndexColumns
	 */
	public $indices = null;
	
	protected static $instances = array();
	protected static $instancesByID = array();
	
	/**
	 * Konstruktor
	 */
	public function __construct() {
		$this->columns = new mwbColumns();
		$this->foreignKeys = new mwbForeignKeys();
		$this->indices = new mwbIndexColumns();
	}
	
	/**
	 * erzeugt eine neue Instanz
	 * @param SimpleXMLElement $data
	 * @return \mwbTable
	 */
	public static function getInstance( $data ){
		$inst = new mwbTable();
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