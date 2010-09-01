<?php
class Group extends AppModel 
{
	var $hasMany = array('User');
	
	var $actsAs = array('Acl' => array('type' => 'requester'));
	
	// ACL
	function parentNode() {
	    return null;
	}

}
?>