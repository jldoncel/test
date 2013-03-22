<?php
class page_out extends Page {
    function init(){
        parent::init();
        
        $h=$this->add('JQMHeader')->set('Test');
        
        $this->api->auth->logout();
        
        $this->add('H2')->set('Has salido de tu cuenta');
        
        $this->add('JQMButton')->set('Volver al inicio')->href($this->api->url('login'));
        
    }
}
