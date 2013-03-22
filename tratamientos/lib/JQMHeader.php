<?php
class JQMHeader extends View  { 
	function init(){
		parent::init(); 
	} 
	
	function defaultTemplate(){
        	return array('JQMHeader');
    	}
    	
    function addButtonLeft($caption, $href=null, $icon=null, $iconpos=null, $mini=false) {
    	$b=$this->add('JQMButton','','buttons-left')->set($caption)->href($href)->icon($icon, $iconpos)->position('ui-btn-left')->mini($mini);
    	return $this;
    }
    
    function addButtonRight($caption, $href=null, $icon=null, $iconpos=null, $mini=false) {
    	$b=$this->add('JQMButton','','buttons-right')->set($caption)->href($href)->icon($icon, $iconpos)->position('ui-btn-right')->mini($mini);
    	return $this;
    }
}
