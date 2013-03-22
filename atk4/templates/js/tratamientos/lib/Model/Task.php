<?php
class Model_Task extends Model_Table {
    public $table='task';
    function init(){
        parent::init();
        $this->addField('user_id')->dataType('int')->refModel('User')->system(true);
        
        $this->addField('name')->mandatory('No has indicado un nombre para la tarea')->group('info');
        $this->addField('description')->dataType('text')->group('info');
        
        $this->addField('state')->dataType('int')->system(true);
        
        $this->addField('interval')->dataType('int')->group('time')->display('Slider');
        $this->addField('every')->dataType('int')->group('time')->display('dropdown')->setValueList(array(1=>'Lunes', 2=>'Martes'));
        
        $this->addHook('beforeSave',$this);
    }
    
    function beforeSave() { 
    	if (!$this['id']) $this['user_id']=$this->api->auth->model['id'];
        return $this;
    }
}