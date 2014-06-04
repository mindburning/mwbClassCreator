<div class="list list-<?php echo $className; ?>">
<?php	echo "<?php\n";	?>
foreach(<?php echo $className; ?>::getAllInstances() as $item){
<?php	echo "?>\n";	?>
	<div class="list-item">
<?php
	foreach($classData['vars'] as $varName=>$varData){
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
		if(!$isFK || $isPrimary){
			if( $isPrimary ){
?>
		<div class="item-value-primary item-value-primary-<?php echo $varName ?>"><input type="radio" name="<?php echo $className; ?>" value="<?php echo '<?php echo $data->get' .  ucfirst($varName) . '(); ?>' ?>"/></div>
<?php
					}else{
?>
		<div class="item-value item-value-<?php echo $varName ?>"><?php echo '<?php echo $data->get' .  ucfirst($varName) . '(); ?>' ?></div>
<?php
			}
		}
	}
?>
	</div>
<?php	echo "<?php } ?>\n";	?>
</div>