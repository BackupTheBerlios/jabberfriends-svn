<?php
class JForg_App_Test extends Solar_Controller_Page {
	
	public function actionIndex() {
		try {
			//you should set basedocument to Vendor_Document and create your own base class
			$document = new JForg_Document_Property(array('basedocument' => 'JForg_Document_Property'));
			$document->setPermissions(764);
			$document->setGroup('users');
			$document->setOwner('daniel');
			$document->setType('buch');
			
			$document->setFoo('bar');
			
			$document->save();
			
			$id = $document->getId();
			
			$redoc = $document->find($id);
			
			if ($redoc->permissionsToRead('daniel',array('movies','audio','admin'))) {
                echo $redoc->getFoo(); // bar
			}
			
		} catch (Solar_Exception $e) {
			print_r($e);
		}
		die();
	}
}
?>