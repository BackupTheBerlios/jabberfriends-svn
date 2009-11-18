<?php
abstract class JForg_Dodb_Adapter extends Solar_Base {
	abstract public function find($id);
	
	abstract public function search($keyValuePairs);
	
	abstract public function save($id = null, $revision = null, $values);
}
?>