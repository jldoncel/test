<?php
class Frontend extends ApiFrontend {
    function init(){
        parent::init();
        //Si se necesita conexión a bb.dd. permanente descomentar la siguiente línea.
        //Si no es así copiar la siguente lína a las páginas que necesiten conexion a bb.dd.
        //$this->dbConnect();
        
        //incluimos atk e inidicamos qué versión
        $this->requires('atk','4.2.0');

        // Indicamos la localizacion de los add-on.
        $this->addLocation('atk4-addons',array(
                    'php'=>array(
                        'mvc',
                        'misc/lib',
                        )
                    ))
            ->setParent($this->pathfinder->base_location);

        // jUI es necesario para las funciones ajax y javascript
        $this->add('jUI');
        // Inicializamos las librerías javascript que estarán disponible para toda la aplicación
        // Si tienes código javascript propio puedes ponerlo en,
        // templates/js/atk4_univ_ext.js e incluirlo aquí
        $this->js()
            ->_load('atk4_univ')
            ->_load('ui.atk4_notify')
            ->_load('ui.atk4_form')
            ->_load('global');
        $this->jui->addStaticInclude('jqminit');  
        $this->jui->addStaticInclude('http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js');
          


        $this->add('BasicAuth')->allow('testing','asdqwe');
        $this->auth->allowPage('login');            
        if (!$this->auth->isLoggedIn() && !$this->auth->isPageAllowed($this->page)) $this->redirect('login');
    }
    
}
