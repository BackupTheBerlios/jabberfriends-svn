<?php
class JForg_App_News extends JForg_App_Base {
    protected $_action_default = 'overview';
    protected $_action_format = array(
        'overview' => array('rss','rss2','atom'),
    );
    
    public function actionOverview($page = 1) {
        
    }
    
    public function actionView($id) {
        
    }
    
    public function actionEdit($id) {
        
    }
}
?>