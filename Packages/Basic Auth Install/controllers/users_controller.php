<?php
class UsersController extends AppController {
	
	// Remove on live system!
	function beforeFilter() {
	    parent::beforeFilter(); 
	    $this->Auth->allowedActions = array('login', 'add', 'index');
	}
	
	function login() 
	{
		if ($this->Session->read('Auth.User')) {
			$this->redirect(array('controller' => 'prices'));
		}
	}
	
	function logout() 
	{
		$this->redirect($this->Auth->logout());
	}
}