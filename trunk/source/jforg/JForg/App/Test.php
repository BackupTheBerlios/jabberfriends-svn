<?php
class JForg_App_Test extends Solar_Controller_Page {
	
	public function actionIndex() {
		try {
			$document = new JForg_Document_Property();
			$document->setPermissions(764);
			$document->setGroup('users');
			$document->setOwner('daniel');
			$document->setType('buch');
			
			$document->setFoo('bar');
			
			$document->save();
			
			$id = $document->getId();
			
			$redoc = $document->find($id);
			
			echo $redoc->getFoo(); // bar
			
		} catch (Solar_Exception $e) {
			print_r($e);
		}
		die();
	}
}
?>