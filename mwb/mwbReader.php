<?php
include_once 'mwbTable.php';

class mwbReader{
	/**
	 * @var string path
	 */
	public $outputFolder = '';
	
	/**
	 * @var string model prefix
	 */
	public $modelPrefix = null;
	
	/**
	 * @var ZipArchive
	 */
	protected $zip = null;
	
	/**
	 * Table- zu Classname wrapper
	 * @param string $tableName Tabellenname aus mwb Datei
	 * @return string
	 */
	public function getClassNameByTableName( $tableName ){
		return ucfirst( $tableName );
	}
	
	/**
	 * öffnet die Zip Datei und erzeugt das Klassenmodell
	 * @param string $fn Pfad zur mwb Datei
	 */
	public function renderFile( $fn ){
		$modelPrefix = $this->modelPrefix;
		$this->zip = new ZipArchive($fn);
		$this->zip->open($fn);
		$model = simplexml_load_string( $d=$this->zip->getFromName('document.mwb.xml') );
		
		$classes = array();
		
		$tableConnections = array();
		foreach( $model->xpath("//value[@struct-name='workbench.physical.Connection']") as $connection ){
			$tableConnection = array();
			foreach($connection->value as $connectionLink){
				$tableConnection[(string)$connectionLink['key']] = (string)$connectionLink;
			}
			foreach($connection->link as $connectionLink){
				$tableConnection[(string)$connectionLink['key']] = (string)$connectionLink;
			}
			$tableConnections[$tableConnection['foreignKey']] = $tableConnection;
		}
	}
}
