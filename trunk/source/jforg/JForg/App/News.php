<?php
class JForg_App_News extends JForg_App_Base {
    protected $_action_default = 'overview';
    protected $_action_format = array(
        'overview' => array('rss','rss2','atom'),
    );
    
    public $news = null;
    public $page = null;
    public $max_page = null;
    
    public function _preRun() {
        parent::_preRun();
        $this->navi_highlight = 'tuerkis';
    }
    
    public function actionOverview($page = 1) {
        $model_news = new JForg_Model_News();
        if ($this->_format!=null) {
            //requesting an newsfeed (rss,rss2 or atom)
            //disable all pageing stuff
            $this->title = $this->locale('TEXT_NEWS');
            $items_per_page = 15;
            $page = 1;
        } else {
            //requesting an usual (xhtml) webpage.
            //do this pageing stuff
            $page = (int) $page;
            if ($page == 0) {
                $page = 1;
            }
            $items_per_page = 5;
            $pagecount = $model_news->countPages(array(
                'where' => 'language = \'de_DE\'',
                'paging' => $items_per_page,
                'page' => $page
            ));
            if ($page > $pagecount['pages']) {
                $page = $pagecount['pages'];
            }
            $this->title = $this->locale('TEXT_NEWS').' - '.$this->locale('TEXT_PAGE').': '.$page;
            $this->page = $page;
            $this->max_page = $pagecount['pages'];
        }
        $collection = $model_news->fetchAllByLanguage('de_DE',array(
            'page' => $page,
            'paging' => $items_per_page
        ));
        $this->news = $collection;
    }
    
    public function actionView($id = null) {
        
    }
    
    public function actionEdit($id = null) {
        $model_news = new JForg_Model_News();
        $model_news->insert(array(
            'language' => 'de_DE',
            'title' => 'Test News',
            'content' => 'lol',
            'date' => date('Y-m-d H:m:i')
        ));
    }
}
?>