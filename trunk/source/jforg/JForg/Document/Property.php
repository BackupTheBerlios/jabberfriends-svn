<?php
class JForg_Document_Property extends JForg_Document {
	protected $_values = array(
	   'permissions' => 700,
	   'owner' => 'admin',
	   'group' => 'admin'
	);
	
	public function getOwnerPermissions() {
		return intval($this->permissions / 100);
	}
	
	public function getGroupPermissions() {
		return (intval($this->permissions / 10) % 10);
	}
	
	public function getOtherPermissions() {
		return ($this->permissions % 10);
	}
	
	public function permissionsToRead($user, $group = array()) {
		return ($this->getPermissionsForUser($user,$group) >= 4);
	}
	
	public function permissionsToWrite($user, $group = array()) {
		$perm = $this->getPermissionsForUser($user,$group);
		return (($perm == 2) OR ($perm == 3) OR ($perm == 6) OR ($perm == 7));
	}
	
	public function permissionsToExecute($user, $group = array()) {
		return (($this->getPermissionsForUser($user,$group) % 2) == 1);
	}
	
	public function getPermissionsForUser($user, $group = array()) {
        if ($user == $this->owner) {
            return $this->getOwnerPermissions();
        } elseif (in_array($this->group,$group)) {
            return $this->getGroupPermissions();
        } else {
            return $this->getOtherPermissions();
        }
	}
}
?>