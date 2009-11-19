<?php
class JForg_Document extends Solar_Base {
	
	protected $_JForg_Document = array(
	   'dodb' => 'dodb',
	   'basedocument' => 'JForg_Document'
	);
	
	protected $_populated = false;
	protected $_valueWasSet = false;
	
	protected $_id = null;
	protected $_revision = null;
	protected $_type = null;
	
	protected $_values = array();
	
	protected $_dodb;
	
	public function __construct($config = array()) {
		parent::__construct($config);
		$this->_dodb = Solar_Registry::get($this->_config['dodb']);
	}
	
	public function find($id) {
		$values = $this->_dodb->find($id);
		if (isset($values['type'])) {
			$className = $this->_generateClassnameFromType($values['type']);
		} else {
			$className = $this->_config['basedocument'];
		}
		$tmpDocument = new $className();
		return $tmpDocument->populate($values);
	}
	
	protected function _generateClassnameFromType($type) {
		$className = $type;
		//TODO: this seems a little bit like a dirty hack
		try {
			if (class_exists($className)) {
	            return $className;
	        } else {
	            return get_class($this);
	        }
		} catch (Solar_Exception_FileNotReadable $e) {
			return get_class($this);
		}
	}
	
	protected function _generateTypeFromClassname($classname) {
		return $classname;
	}
	
	protected function _injectDefaultValues(&$values , $valueNames = array()) {
		foreach($valueNames as $valueName) {
			if (isset($values[$valueName])) {
				$protectedValueName = '_'.$valueName;
				$this->$protectedValueName = $values[$valueName];
				unset($values[$valueName]);
			}
		}
	}
	
	public function populate(array $values = array()) {
		if (($this->_populated) || ($this->_valueWasSet)) {
			throw new JForg_Document_Exception('you cant populate a document twice and you cant populate a document once you started to set value');
		}
		
		if (!isset($values['id']) || (!isset($values['revision']))) {
			throw new JForg_Document_Exception('document must have an id and a revision');
		}
        
		$this->_injectDefaultValues($values,array('id','revision','type'));
		
        $this->_values = $values;
        $this->_populated = true;
		return $this;
	}
	
	public function save() {
		$keyValuesToSave = $this->_values;
		if ($this->getType() != null) {
			$keyValuesToSave['type'] = $this->getType();
		}
        list($this->_id,$this->_revision) = $this->_dodb->save(
            $this->_id,
            $this->_revision,
            $keyValuesToSave);
		return $this;
	}
	
	public function getType() {
		if ($this->_type == null) {
			return $this->_generateTypeFromClassname(get_class($this));
		} else {
			return $this->_type;
		}
	}
	
	public function __set($valueName , $value) {
		$setterMethod = 'set'.ucfirst($valueName);
		$this->$setterMethod($value);
		return $this;
	}
	
	public function __get($valueName) {
		$getterMethod = 'get'.ucfirst($valueName);
		return $this->$getterMethod();
	}
	
	public function __call($methodName, $methodArguments) {
		$methodPrefix = substr($methodName,0,3);
		if ($methodPrefix === 'get') {
			$valueName = strtolower(substr($methodName,3));
			return $this->_values[$valueName];
		} elseif ($methodPrefix === 'set') {
			$valueName = strtolower(substr($methodName,3));
			$this->_values[$valueName] = $methodArguments[0];
			return $this;
		} else {
			throw new Solar_Exception_MethodNotImplemented();
		}
	}
	
	public final function getId() {
		return $this->_id;
	}
	
	public final function getRevision() {
		return $this->_revision;
	}
	
	private final function setId() {
		throw new JForg_Document_Exception();
	}
	
	private final function setRevision() {
		throw new JForg_Document_Exception();
	}
	
	public final function setType() {
		throw new JForg_Document_Exception(array('message' => 'You cannot set the Type manually'));
	}
	
	public function __toString() {
		return print_r($this->_values,true);
	}
}
?>