<?php
class JForg_App_News extends JForg_App_Base {
    protected $_layout_default = 'main';
    protected $_action_default = 'overview';
    protected $_action_format = array(
        'overview' => array('rss','rss2','atom','xml'),
    );
    
    public $title = '';
    
    public function actionOverview($page = 1) {
        $this->title = $this->locale('TEXT_NEWS');
    }
    
    public function actionView($id = null) {
        
    }
    
    public function actionEdit($id = null) {
        
    }
}
?>