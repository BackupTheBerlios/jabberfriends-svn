<?php
class JForg_App_Test extends Solar_Controller_Page {
	
	public function actionIndex() {
		try {
			$document = new JForg_Document_Property();
			$document->setPermissions(764);
			$document->setGroup('users');
			$document->setOwner('daniel');
			
			$document->setFoo('bar');
			
			$document->save();
			
			$id = $document->getId();
			
			$documentLookup = new JForg_Document();
			
			//redoc is automaticly of Type JForg_Document_Property
			$redoc = $documentLookup->find($id);
			
			if ($redoc->permissionsToRead('daniel',array('movies','audio','admin'))) {
                echo $redoc->getFoo(); // bar
			}
			
			if ($redoc->permissionsToWrite('daniel',array('users'))) {
				$redoc->setMyProperty('myvalue');
				$redoc->save();
				echo $redoc->getRevision();
			}
			
			if ($redoc->permissionsToWrite('somebody',array('movies','audio'))) {
				die('fail!'); //doesnt happen
			}
			
		} catch (Solar_Exception $e) {
			print_r($e);
		}
		die();
	}
}
?>