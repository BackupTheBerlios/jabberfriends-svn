<?php
abstract class JForg_App_Base extends Solar_Controller_Page  {
    protected $_layout_default = 'main';
    public $title = '';
    public $navi_highlight= '';
    public function __construct($config = null) {
        parent::__construct($config);
        $locale = Solar_Registry::get('locale');
        $locale->setCode('de_DE');
    }
}
?>