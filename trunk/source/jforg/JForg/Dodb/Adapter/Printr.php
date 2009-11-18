<?php
class JForg_Dodb_Adapter_Printr extends JForg_Dodb_Adapter {
	public function save($id = null, $revision = null, $values) {
		echo 'Save Document: '.$id.'/'.$revision.print_r($values,true);
	}
	
	public function search($keyValuePairs) {
		echo 'Searching for '.print_r($keyValuePairs,true);
	}
	
	public function find($id) {
		
	}
}
?>