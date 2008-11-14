<?php
abstract class JForg_App_Base extends Solar_Controller_Page  {
     public function __construct($config = null) {
         parent::__construct($config);
         $locale = Solar_Registry::get('locale');
         $locale->setCode('de_DE');
     }
}
?>