<?php
class Model_User extends Model_Table {
    public $table='user';
    function init(){
        parent::init();
        $this->addField('nick')->mandatory('No has indicado tu nombre de usuario');
        $this->addField('email')->mandatory('No has indicado tu email');
        $this->addField('password')->mandatory('Indica tu contraseña')->dataType('password');
    }
}