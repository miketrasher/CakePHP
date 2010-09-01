<div class="login">
	<?php
	print $form->create('User', array('url' => array('controller' => 'users', 'action' =>'login')));
	print $form->input('User.username');
	print $form->input('User.password');
	print $form->end(__('Login', true));
	?>
</div>