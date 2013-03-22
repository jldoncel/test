<?php
class Model_Almacenes extends Model_Table  {
	public $table='almacenes';
	function init(){
        parent::init();
		
		$this->addField('name')->caption('Name')->mandatory('¡Pon una descripción!');
	}    
}