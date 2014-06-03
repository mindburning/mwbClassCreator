<?php
include_once 'mwbTable.php';

class mwbReader{
	/**
	 * @var string path
	 */
	protected $_outputFolder = '';
	
	/**
	 * @var string model prefix
	 */
	protected $_modelPrefix = null;
	
	/**
	 * @var ZipArchive
	 */
	protected $_zip = null;
	
	/**
	 * Konstruktor
	 * 
	 * @param string|null $outFolder
	 * @param string|null $modelPrefix
	 */
	public function __construct( $outFolder = null, $modelPrefix = null ) {
		$this->setFolder( $outFolder )
				->setModelPrefix( $modelPrefix );
	}
	
	/**
	 * setzt den Ausgabepfad (Ordner)
	 * 
	 * @param string|null $outFolder Pfad zum Ausgabeverzeichnis
	 * @return \mwbReader
	 */
	public function setFolder( $outFolder = null ) {
		$this->_outputFolder = $outFolder;
		return $this;
	}
	
	/**
	 * setzt das verwendete Modelprefix
	 * 
	 * @param string|null $modelPrefix prefix für alle model Klassen, dieser wird aus den Klassennamen entfernt
	 * @return \mwbReader
	 */
	public function setModelPrefix( $modelPrefix = null ) {
		$this->_modelPrefix = $modelPrefix;
		return $this;
	}
	
	/**
	 * Table- zu Classname wrapper
	 * 
	 * @param string $tableName Tabellenname aus mwb Datei
	 * @return string
	 */
	public function getClassNameByTableName( $tableName ){
		return ucfirst( $tableName );
	}
	
	/**
	 * öffnet die Zip Datei und erzeugt das Klassenmodell
	 * 
	 * @param string $fn Pfad zur mwb Datei
	 * @param string $tplFile gibt an, welche Ausgabevorlage genutzt werden soll
	 * @return \mwbReader
	 * @throws Exception
	 */
	public function renderFile( $fn, $tplFile = "phpclass.php" ){
		$modelPrefix = $this->_modelPrefix;
		if( file_exists( $fn ) && is_file( $fn ) ){
			$this->_zip = new ZipArchive($fn);
			$this->_zip->open($fn);
			$model = simplexml_load_string( $d=$this->_zip->getFromName('document.mwb.xml') );

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
			for($i=0;$i<2;$i++){
				foreach( $model->xpath("//value[@content-struct-name='db.mysql.Table']/value[@struct-name='db.mysql.Table']") as $simpleTable ){
					$table = mwbTable::getInstance( $simpleTable );
					if($table->name != ''){
						$tables[$table->id] = $table;

						$className = $this->getClassNameByTableName($table->name);

						$classes[$className]['table']	= $table->name;
						$classes[$className]['indices']	= $table->indices;

						foreach($table->columns->_items as $colname=>$col){
							$classes[$className]['vars'][$colname] = $col;
						}

						foreach($table->foreignKeys->_items as $keyName=>$fk){
							if($fk->referencedTable->name != ''){
								$fk->caption = $tableConnections[$fk->id]['caption'];
								$fk->originTable = $table;
								$refClass = $this->getClassNameByTableName($fk->referencedTable->name);
								$classes[$className]['reffrom'][$refClass][$keyName] = $fk;
								$classes[$refClass]['refto'][$className][$keyName] = $fk;
							}
						}
					}
				}
			}
			foreach( $model->xpath("//value[@content-struct-name='db.mysql.View']/value[@struct-name='db.mysql.View']") as $simpleTable ){
				$view = mwbView::getInstance( $simpleTable );
				preg_match_all('#([^_]*?)_(.*)#msi', $view->name, $res);
				$views[$res[1][0]][$res[2][0]] = $view;
			}
			$classPath = $this->_outputFolder;
			$incFileContent = "<?php\r\n";
			foreach($classes as $className=>$classData){
				ob_start();
				include __DIR__ . "/templates/" . $tplFile;
				$c = ob_get_clean();
				file_put_contents($cPathName = $classPath . $className . ".php", $c);
				chmod($cPathName, 0666);
				$incFileContent.="include_once(__DIR__ . '/" . $className . ".php" . "');\r\n";
			}
			file_put_contents($cPathName = $classPath . "inc.php", $incFileContent);
			chmod($cPathName, 0666);
			return $this;
		}
		
		throw new Exception('input file missing');
	}
}
