<?php
class JForg_Dodb_Adapter_CouchDb extends JForg_Dodb_Adapter {
	
	protected $_JForg_Dodb_Adapter_CouchDb = array(
	   'host' => 'localhost',
	   'port' => '5984',
	);
	
	protected $_httpClient = null;
	
    public function save($id = null, $revision = null, $values) {
    	$httpClient = $this->_getHttpClient();
    	
    	if ($id != null) {
    		$values['_rev'] = $revision;
    		$httpClient->setUri('http://localhost:5984/gday/'.$id)
    		           ->setMethod('PUT')
    		           ->setContent(json_encode($values));
    	} else {
	        $httpClient->setUri('http://localhost:5984/gday/')
	                   ->setMethod('POST')
	                   ->setContent(json_encode($values));
    	}
        $result = $httpClient->fetch();
        $result = json_decode($result->content,true);
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
        $httpClient = $this->_getHttpClient();
        $res = json_decode($httpClient->setUri('http://localhost:5984/gday/'.$id)
                          ->setMethod('GET')
                          ->setContent('')
                          ->fetch()
                          ->content,true);
        $res['id'] = $res['_id'];
        unset($res['_id']);
        $res['revision'] = $res['_rev'];
        unset($res['_rev']);
        return $res;
    }
    
    protected function _getHttpClient() {
    	if ($this->_httpClient == null) {
    		$this->_httpClient = Solar::factory('Solar_Http_Request');
    	}
    	return $this->_httpClient;
    }
}
?>