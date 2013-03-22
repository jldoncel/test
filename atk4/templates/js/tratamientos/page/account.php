<?php
class page_account extends Page {
    function init(){
        parent::init();
		$this->api->template->trySet('back','true');
		$h=$this->add('JQMHeader')->set('Tu cuenta');
		$this->add('JQMButton')->set('Salir')->icon('delete')->href($this->api->url('out'));
        
    }
}
