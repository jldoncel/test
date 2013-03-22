<?php
class page_login extends Page {
    function init(){
        parent::init();
        
        $h=$this->add('JQMHeader')->set('Tratamiento de fermentadores - Login');
		
		$this->add('H1')->set('ENTRAR');
        $f=$this->add('Form');
        $f->addField('line','user','Usuario')->validateNotNull();
        $f->addField('password','password','Contraseña')->validateNotNull();
        $f->addSubmit('¡Entrar!');
        
        //Login submit
        $f->onSubmit(function($form){
        	$l=$form->get('user');
            $p=$form->get('password');
		    if ($form->api->auth->verifyCredintials($l,$p)) {
				$form->api->auth->login($l);
				$form->api->auth->memorizeModel();
		    	return $form->js()->univ()->redirect('index')->execute();
		    }
		    else return $form->js()->univ()->successMessage('Error en login');
		});
    }
}
