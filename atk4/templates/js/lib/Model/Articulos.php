<?php
class Model_Articulos extends Model_Table  {
	public $table='articulos';
	function init(){
        parent::init();
		
		$this->addField('descripcion')->caption('Descripción')->mandatory('¡Pon una descripción!');
		$this->addField('activo')->type('boolean')->enum(array('S','N'));
		$this->hasOne('Almacenes');
	}    
}