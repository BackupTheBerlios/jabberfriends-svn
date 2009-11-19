<?php
class JForg_Dodb_Adapter_CouchDb extends JForg_Dodb_Adapter {
	
	protected $_JForg_Dodb_Adapter_CouchDb = array(
	   'host' => 'localhost',
	   'port' => '5984',
	   'encrypted' => false
	);
	
	protected $_httpClient = null;
	
    public function __construct($config = array()) {
    	parent::__construct($config);
    	$this->_httpClient = Solar::factory('Solar_Http_Request');
    }
	
	
    public function save($id = null, $revision = null, $values) {
        if ($id != null) {
    		$values['_rev'] = $revision;
    		$result = $this->_query($values,'PUT',$id);
    	} else {
    		$result = $this->_query($values,'POST');
    	}
        if ((isset($result['ok'])) AND ($result['ok'] == 1)) {
        	return array($result['id'],$result['rev']);
        } else {
        	die(print_r($result,true));
        	throw new Exception('connection failed'.print_r($res,true));
        }
    }
    
    public function search($keyValuePairs) {
        echo 'Searching for '.print_r($keyValuePairs,true);
    }
    
    public function find($id) {
        $res = $this->_query('','GET',$id);
        $res['id'] = $res['_id'];
        unset($res['_id']);
        $res['revision'] = $res['_rev'];
        unset($res['_rev']);
        return $res;
    }
    
    protected function _query($content = null, $method = 'GET', $pathApendix = null) {
    	$uri = $this->_generateBaseUri();
    	if ($pathApendix != null) {
    		$uri->path[] = $pathApendix;
    	}
    	if ($content != null) {
    		$content = json_encode($content);
    	} else {
    		$content = '';
    	}
    	$this->_httpClient->setContent($content);
    	$this->_httpClient->setUri($uri->get(true));
    	$this->_httpClient->setMethod($method);
    	$json = $this->_httpClient->fetch()->content;
    	return json_decode($json,true);
    }
    
    protected function _generateBaseUri() {
    	$uri = new Solar_Uri();
    	$uri->setPath($this->_config['dbname']);
    	$uri->host = $this->_config['host'];
    	$uri->port = $this->_config['port'];
    	if ($this->_config['encrypted'] == false) {
    		$uri->scheme = 'http';	
    	} else {
    		$uri->scheme = 'https';
    	}
    	if (isset($this->_config['user']) AND (isset($this->_config['password']))) {
    		$uri->user = $this->_config['user'];
    		$uri->pass = $this->_config['password'];
    	}
    	return $uri;
    }
}
?>