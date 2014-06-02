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
	 * 
	 * @param string $fn Pfad zur mwb Datei
	 */
	public function renderFile( $fn ){
	}
}
