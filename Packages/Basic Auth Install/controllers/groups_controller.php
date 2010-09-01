<?php
class GroupsController extends AppController 
{	
	// Remove on live system!
	function beforeFilter() {
	    parent::beforeFilter(); 
	    $this->Auth->allowedActions = array('index', 'add');
	}
}