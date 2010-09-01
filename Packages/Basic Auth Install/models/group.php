<?php
class Group extends AppModel 
{
	var $name = 'Group';
	var $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array('User');
	
	var $actsAs = array('Acl' => array('type' => 'requester'));
	
	// ACL
	function parentNode() {
	    return null;
	}

}
?>