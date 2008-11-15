<?php
class JForg_App_Startpage extends JForg_App_Base {
    protected $_action_default = 'index';
    
    public function _preRun() {
        parent::_preRun();
        $this->navi_highlight = 'tuerkis';
    }
    
    public function actionIndex() {
        $this->title = $this->locale('TEXT_STARTPAGE');
    }
}
?>