<?php
class JQMButton extends View  { 
	function init(){
		parent::init(); 
		return $this; 
	} 
	
	function defaultTemplate(){
        	return array('JQMButton');
    }
    	
    function href($h) {
    	if ($h=='back') $this->template->set(array('back'=>'data-rel="back"'));
    	else $this->template->set(array('href'=>$h));
    	return $this;
    }
    
    function icon($i,$p=null) {
    	$this->template->set(array('icon'=>$i));
    	if (!is_null($p)) {
    		$this->template->set(array('iconpos'=>$p));
    	}
    	return $this;
    }
    
    function position($p) {
    	$this->template->set(array('position'=>$p));
    	return $this;
    }
    
    function mini($m) {
    	if ($m) $this->template->set(array('mini'=>'data-mini="true"'));
    	else $this->template->set(array('mini'=>' '));
    	return $this;
    }
    
    function transition($t) {
    	$this->template->set(array('transition'=>$t));
    	return $this;
    }

}
