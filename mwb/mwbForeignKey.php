<?php
include_once './mwbUtils.php';

/**
 * Description of mwbForeignKey
 */
class mwbForeignKey {
	
	/**
	 * speichert alle Spalten des Fremdschlüssels
	 * @var array
	 */
	public $columns = array();

	/**
	 * erzeugt eine neue Instanz von mwbForeignKey
	 * @param mixed $data
	 * @return \mwbForeignKey
	 */
	public static function getInstance($data) {
		$inst = new mwbForeignKey();
		mwbUtils::assign($inst, $data);
		return $inst;
	}
}
