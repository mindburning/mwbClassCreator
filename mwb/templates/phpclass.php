<?php echo "<?php\r\n"; ?>
include_once("inc.php");
/**
 * created by mwb class creator https://github.com/mindburning/mwbClassCreator
 **/
class <?php echo $className; ?>{
	const TABLE = '<?php echo $classData['table']; ?>';
<?php foreach($classData['vars'] as $varName=>$varData){ ?>
	const FIELD_<?php echo strtoupper($varName); ?> = '<?php echo $varName; ?>';
<?php } ?>
	
<?php foreach($classData['vars'] as $varName=>$varData){ ?>
	/**
	 * @var mixed
	 */
	protected $<?php echo $varName ?> = null;
	
<?php } ?>

	/**
	 * @param mixed $data
	 * @return \<?php echo $className; ?> 
	 */
	public static function getInstance($data){
		return new <?php echo $className; ?>();
	}
	
<?php foreach($classData['vars'] as $varName=>$varData){ ?>

	/**
	 * @param mixed $<?php echo ucfirst($varName) ?> 
	 * @return \<?php echo $className; ?> 
	 */
	public static function getInstancesBy<?php echo ucfirst($varName) ?>($<?php echo ucfirst($varName) ?>){
		return utils::fetchAll( $this, $<?php echo ucfirst($varName) ?> );
	}
<?php } ?>
	
	/**
	 * @return \<?php echo $className; ?> 
	 */
	public static function getAllInstances(){
		return utils::fetchAll( $this );
	}
	
<?php foreach($classData['vars'] as $varName=>$varData){ ?>
	/**
	 * @return mixed
	 */
	public function get<?php echo ucfirst($varName) ?>(){
		return $this-><?php echo $varName ?>;
	}
	
	/**
	 * @param $<?php echo ucfirst($varName) ?> <?php echo (string)$varData->simpleType; ?> 
	 * @return \<?php echo $className; ?> 
	 */
	public function set<?php echo ucfirst($varName) ?>( $<?php echo ucfirst($varName) ?> ){
		$this-><?php echo $varName ?> = $<?php echo ucfirst($varName) ?>;
		return $this;
	}
	
<?php } ?>
<?php
	foreach($classData['reffrom'] as $refFromClass=>$fkList){
		foreach($fkList as $keyName=>$refData){
			$memberName = array();
			$memberCols = array();
			$memberColVars = array();
			$refDataColumnKeys = array_keys($refData->referencedColumns);
			$i = 0;
			foreach($refData->columns as $thisColName=>$refCol){
				$memberCols[] = '$' . ucfirst($refCol->name) . '';
				$memberColVars[] = '$this->get' . ucfirst($refCol->name) . '()';
			}
			$refMemberName = array();
			foreach($refData->referencedColumns as $thisColName=>$refCol){
				$memberName[] = ucfirst($refCol->name);
				$refMemberName[] = ucfirst($refCol->name);
			}
			if(count($memberCols)>1){
?>
	
	/**
	 * @return \<?php echo $className; ?> 
	 * @TODO need implementation
	 */
	public static function getInstancesBy<?php echo (count($memberCols)>1?ucfirst( substr($refToClass,  strlen($modelPrefix)) ) . "By":'' ) . implode('And', $memberName); ?>(<?php echo implode(', ', $memberCols); ?>) {
		return utils::fetchAll( "<?php echo $className; ?>" );
	}
<?php
			}
?>

	/**
	 * @return \<?php echo $refFromClass; ?> 
	 */
	public function get<?php echo ucfirst($refData->caption) . ucfirst( substr($refFromClass,  strlen($modelPrefix)) ); ?>() {
		return <?php echo $refFromClass ?>::getInstancesBy<?php echo (count($refMemberName)>1?ucfirst( substr($refFromClass,  strlen($modelPrefix)) ) . "By":'' ) . implode('And', $refMemberName); ?>(<?php echo implode(', ', $memberColVars); ?>);
	}
	
	/**
	 * @param $<?php echo $refFromClass; ?> \<?php echo $refFromClass; ?> 
	 * @return \<?php echo $className; ?> 
	 */
	public function set<?php echo ucfirst($refData->caption) . ucfirst( substr($refFromClass,  strlen($modelPrefix)) ); ?>( $<?php echo $refFromClass; ?> ) {
		if( $<?php echo $refFromClass; ?> instanceof <?php echo $refFromClass; ?> ){
<?php	$i = 0; foreach($refData->columns as $thisColName=>$refCol){	?>
			$this->set<?php echo ucfirst($thisColName) ?>($<?php echo $refFromClass; ?>->get<?php echo ucfirst($refDataColumnKeys[$i++]) ?>());
<?php	}	?>
		}
		return $this;
	}
	
<?php
		}
	}
?>
<?php
	foreach($classData['refto'] as $refToClass=>$fkList){
		foreach($fkList as $keyName=>$refData){
			$memberName = array();
			$memberCols = array();
			$inverseColName = array();
			
			$colMappingTo = array();
			
			foreach($refData->columns as $thisColName=>$refCol){
				$colMappingTo[] = $refCol->name;
				$memberName[] = ucfirst($refCol->name);
			}
			
			foreach($refData->referencedColumns as $thisColName=>$refCol){
				$memberCols[] = '$this->get' . ucfirst($thisColName) . '()';
			}
			
			$colMappingToFrom = array_combine($colMappingTo, $memberCols);
			
			$allRefVars = $additionalRefVars = $additionalRefVarsTypes = array();
			foreach($refData->originTable->columns->_items as $refColName=>$refColData){
				if($refColData->isNotNull == 1){
					if(!in_array(ucfirst($refColName),$memberName)){
						$allRefVars[$refColName] = '$' . ucfirst($refColName);
						$additionalRefVars[] = '$' . ucfirst($refColName) . ' = null';
						$additionalRefVarsTypes['$' . ucfirst($refColName)] = $refColData->simpleType;
					}else{
						foreach($memberName as $memberNameIdx=>$memberNameValue){
							if($memberNameValue == ucfirst($refColName)){
								$allRefVars[$refColName] = $memberCols[$memberNameIdx];
							}
						}
					}
				}
			}
?>
	/**
	 * @return \<?php echo $refToClass; ?> 
	 */
	public function get<?php echo ucfirst($refData->caption) . ucfirst( substr($refToClass,  strlen($modelPrefix)) ); ?>( $createNewOnNoResult = false ) {
		$result = <?php echo $refToClass ?>::getInstancesBy<?php echo (count($memberCols)>1?ucfirst( substr($refToClass,  strlen($modelPrefix)) ) . "By":'' ) . implode('And', $memberName); ?>(<?php echo implode(', ', $memberCols); ?>);
		if( $createNewOnNoResult && count( $result ) == 0 ){
			$result = <?php echo $refToClass; ?>::getInstance();
<?php foreach($colMappingToFrom as $colTarget=>$varCurrent){ ?>
			$result->set<?php echo ucfirst($colTarget) ?>(<?php echo $varCurrent ?>);
<?php } ?>
		}
		return $result;
	}
	
	/**
<?php foreach($additionalRefVarsTypes as $additionalRefVarsName => $additionalRefVarsType){ ?>
	 * @param <?php echo $additionalRefVarsName . " " . $additionalRefVarsType ?> 
<?php } ?>
	 * @return \<?php echo $refToClass; ?> 
	 */
	public function new<?php echo ucfirst($refData->caption) . ucfirst( substr($refToClass,  strlen($modelPrefix)) ); ?>(<?php echo implode(', ', $additionalRefVars); ?>){
		$refData = array();
<?php foreach($allRefVars as $allRefVarKey => $allRefVarName){ ?>
		if(<?php echo $allRefVarName ?> !== null ){		$refData['<?php echo $allRefVarKey ?>'] = <?php echo $allRefVarName ?>	;	}
<?php } ?>
		return <?php echo $refToClass ?>::getInstance( $refData );
	}
	
<?php
		}
	}
?>
	/**
	 * @return array
	 */
	public function toArray(AKeyValueFilter $filter = null){
		$result = array();
<?php foreach($classData['vars'] as $varName=>$varData){ ?>
		if( ($filter !== null && $filter->isValid('<?php echo $varName; ?>', $this-><?php echo $varName; ?>)) || ($filter === null<?php if( isset( $classData['indices']->PRIMARY->columns->$varName ) ){ ?> && $this-><?php echo $varName; ?> !== NULL<?php } ?>)) $result['<?php echo $varName; ?>'] = $this-><?php echo $varName; ?>;
<?php } ?>
		return $result;
	}
	
	/**
	 * @return \<?php echo $className; ?> 
	 */
	public function store($insertDelayed = false){
		$storeData=$this->toArray();
		$insertId = utils::insUpd("<?php echo $classData['table'] ?>", $storeData, $insertDelayed);
		if( $insertDelayed == false && $insertId > 0){
<?php foreach($classData['indices']->PRIMARY->columns as $varName => $pkColumn){ if($pkColumn->autoIncrement == 1){ ?>
			$this->set<?php echo ucfirst($varName) ?>($insertId);
<?php } } ?>
		}
		return $this;
	}
	
	/**
	 * @TODO
	 */
	public function delete(){
		$sqlWhere = array();
<?php foreach($classData['indices']->PRIMARY->columns as $varName => $pkColumn){ ?>
		if( $this->get<?php echo ucfirst($varName) ?>() !== null ){  $sqlWhere[] = "<?php echo $varName ?>='" . $this->get<?php echo ucfirst($varName) ?>() . "'";  }
<?php } ?>
		if(isset( $sqlWhere[0] )){
			$sql = "DELETE FROM <?php echo $classData['table']; ?>";
			$sql .= ' WHERE (' . implode(" AND ", $sqlWhere) . ')';
			utils::delete( $sql );
<?php foreach($classData['indices']->PRIMARY->columns as $varName => $pkColumn){ ?>
			$this->set<?php echo ucfirst($varName) ?>( null );
<?php } ?>
		}
		return $this;
	}
}