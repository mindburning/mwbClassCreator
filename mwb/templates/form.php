<form name="<?php echo $className; ?>" class="<?php echo $className; ?>">
<?php foreach($classData['vars'] as $varName=>$varData){ ?>
	<?php
		$type="text";
		$isFK = false;
		foreach($classData['refto'] as $refToClass=>$fkList){
			foreach($fkList as $keyName=>$refData){
				if( isset( $refData->columns[$varName] ) ){
					$isFK = true;
				}
			}
		}
		if( !$isFK ){
			switch( $type ){
				case 'text':
					echo '<label><span class="lblText">' . htmlentities($varName) . "</span>";
					echo '<input type="text" name="' . $className . '[' . $varName . ']" value="<?php echo $data->get' . ucfirst($varName) . '(); ?>" class="input"/>';
					echo "</label>\n";
					break;
			}
		}else{
			
		}
	?>
<?php } ?>
</form>