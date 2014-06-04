<form name="<?php echo $className; ?>" class="<?php echo $className; ?>">
<?php foreach($classData['vars'] as $varName=>$varData){ ?>
	<?php
		$type="text";
		$isFK = false;
		$isPrimary = false;
		
		if( isset( $classData['indices']->PRIMARY->columns->$varName ) ){
			$isPrimary = true;
		}
		
		foreach($classData['refto'] as $refToClass=>$fkList){
			foreach($fkList as $keyName=>$refData){
				if( isset( $refData->columns[$varName] ) ){
					$isFK = true;
					$fkClassName	= substr($refToClass,  strlen($modelPrefix));
					$fkClassMethod	= ucfirst($refData->caption) . substr($refToClass,  strlen($modelPrefix));
				}
			}
		}
		
		if( !$isFK && !$isPrimary){
			switch( $type ){
				case 'text':
					echo '<label><span class="lblText">' . htmlentities($varName) . "</span>";
					echo '<input type="text" name="' . $className . '[' . $varName . ']" value="<?php echo $data->get' . ucfirst($varName) . '(); ?>" class="input"/>';
					echo "</label>\n";
					break;
			}
		}else{
			if( !$isFK && $isPrimary ){
				echo '<?php if( $data->get' . ucfirst($varName) . '() ){ ?><input type="hidden" name="' . $className . '[' . $varName . ']" value="<?php echo $data->get' . ucfirst($varName) . '(); ?>" class="input"/>' . "<?php } ?>\n";
			}else{
				echo '<?php $dataStack[] = $data;'
				. '$data = $data->get' . $fkClassMethod . '();'
				. 'include(\'' . $fkClassName . ".search.php');"
				. '$data = array_pop($dataStack); ?>' . "\n";
			}
		}
	?>
<?php } ?>
</form>
<?php
	ob_start();
	include __DIR__ . "/search." . $tplFile;
	$searchFormContent = ob_get_clean();
	file_put_contents($cPathName = $classPath . $className . ".search.php", $searchFormContent);
?>