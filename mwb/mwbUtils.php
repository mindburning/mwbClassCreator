<?php
include_once 'mwbTable.php';
include_once 'mwbColumn.php';
include_once 'mwbIndexColumn.php';
include_once 'mwbForeignKey.php';

/**
 * Description of mwbUtils
 */
class mwbUtils{
	
	/**
	 * @param class $inst Instanz, welcher Werte zugewiesen werden sollen
	 * @param SimpleXMLElement $data Knoten des mwb XML Knotens
	 */
	public static function assign(&$inst, &$data){
		if( $data instanceof SimpleXMLElement ){
			$inst->id = (string)$data['id'];
			foreach( $data->link as $obj){
				switch((string)$obj['type']){
					case 'object':
						switch((string)$obj['struct-name']){
							case 'db.mysql.Table':
								$inst->$obj['key'] = mwbTable::getInstance( (string)$obj );
								break;
							case 'db.SimpleDatatype':
								$inst->$obj['key'] = str_replace('com.mysql.rdbms.mysql.datatype.','',(string)$obj);
								break;
						}
						break;
				}
			}
			foreach( $data->value as $obj){
				switch((string)$obj['type']){
					case 'list':
						foreach($obj->value as $listItem){
							switch((string)$obj['content-struct-name']){
								case 'db.mysql.IndexColumn':
									foreach( $listItem->link as $linkItem){
										switch($linkItem['key']){
											case 'referencedColumn':
												$col = mwbColumn::getInstance((string)$linkItem);
												$inst->{$obj['key']}->{$col->name} = $col;
												unset($col);
												break;
										}
									}
									break;
								case 'db.mysql.Column':
									$col = mwbColumn::getInstance($listItem);
									$inst->{$obj['key']}->{$col->name} = $col;
									unset($col);
									break;
								case 'db.mysql.Index':
									$col = mwbIndexColumn::getInstance($listItem);
									$inst->{$obj['key']}->{$col->name} = $col;
									unset($col);
									break;
								case 'db.mysql.ForeignKey':
									$col = mwbForeignKey::getInstance($listItem);
									$inst->{$obj['key']}->{$col->name} = $col;
									unset($col);
									break;
							}
						}
						foreach($obj->link as $listItem){
							switch((string)$obj['content-struct-name']){
								case 'db.Column':
									$col = mwbColumn::getInstance((string)$listItem);
									$inst->{$obj['key']}[$col->name] = $col;
									unset($col);
									break;
							}
						}
						break;
					case 'int':
						$inst->$obj['key'] = (int)$obj;
						break;
					case 'string':
						$inst->$obj['key'] = (string)$obj;
						break;
				}
			}
		}
	}
}
