<?php
require_once('/usr/home/test.laespanola.es/web/soap/lib/nusoap.php');

class page_tratamiento extends Page {
    function init(){
        parent::init();
        $this->api->template->set('page_title','Tratamiento de fermentadores');
        $h=$this->add('JQMHeader')->set('Tratamiento de fermentadores');
    	if (!empty($_GET['vt']) || !empty ($_GET['ali']) || !empty($_GET['lri']) || !empty($_GET['si'])) {
    		//Cambiamos puntos por comas en decimales
    		foreach ($_GET as $clave=>$valor) $_GET[$clave]=str_replace(',', '.', $valor);
    		$error=array();
    		if (empty($_GET['vt']) || !is_numeric($_GET['vt'])) $error[]='Debes indicar el volumen total';
    		if (empty($_GET['ali']) || !is_numeric($_GET['ali'])) $error[]='Debes indicar la acidez libre inicial';
    		if (empty($_GET['lri']) || !is_numeric($_GET['lri'])) $error[]='Debes indicar la lejia residual inicial';
    		if (empty($_GET['si']) || !is_numeric($_GET['si'])) $error[]='Debes indicar la sal inicial';
    	
    		if (!empty($error)) 
    		{
    			foreach ($error as $err) $this->add('P')->set($err)->setClass('error');
    			$this->api->stickyGET('vt');
	        	$this->api->stickyGET('ali');
	        	$this->api->stickyGET('lri');
	        	$this->api->stickyGET('si');
	        	$this->add('JQMButton')->set('Completar datos')->href($this->api->getDestinationURL('index'));
    		}
    		else {
	    		
		    	//$webservice = new nusoap_client('http://test.laespanola.es/soap/server.php?wdsl');
		    	$webservice = new nusoap_client('http://80.38.86.3:1111/servicios/server.php');
		    	$result = $webservice->call('getTratamiento',
		              array('VT' => $_GET['vt'], 'ALI'=>$_GET['ali'], 'LRI'=>$_GET['lri'], 'SI'=>$_GET['si']));
		        if (empty($result['estado'])) {
			        $this->add('HtmlElement')->setElement('P')->set('ERROR CONECTANDO AL SERVICIO WEB');
			        $this->api->stickyGET('vt');
			        $this->api->stickyGET('ali');
		        	$this->api->stickyGET('lri');
		        	$this->api->stickyGET('si');
		    		$boton=$this->add('JQMButton')->set('Reintentar')->href($this->api->getDestinationURL('index'));
			        return;
		        }
		        
		        //Creamos una lista y añadimos cada propiedad como un elemento de la lista
		        $lista=$this->add('HtmlElement')->setElement('ul')->setAttr('data-role','listview')->setAttr('data-inset','true');
		        //ESTADO    
		        $lista->add('HtmlElement')->setElement('li')->set('ESTADO')->setAttr('data-role','list-divider');
			    $estado=$lista->add('HtmlElement')->setElement('li');
			    $estado->add('H3')->set($result['estado']);
			    $estado->add('P')->set('AL: '.$_GET['ali'].' LR: '.$_GET['lri'].' Sal: '.$_GET['si']);
			    if ($result['estado']!='DISPONIBLE') 
			    {
			        //Tratamiento incompleto
			        if (!empty($result['incompleto'])) {
				        $lista->add('HtmlElement')->setElement('li')->set($result['incompleto'])->setAttr('data-theme','e');
				    	//VOLUMEN
				    	$lista->add('HtmlElement')->setElement('li')->set('VOLUMEN DE SALMUERA A RETIRAR')->setAttr('data-role','list-divider');
				    	if ($result['volumen_retirar']>0) 
			        		$lista->add('HtmlElement')->setElement('li')->set($result['volumen_retirar'].' litros');
			        	//Acido
				        $lista->add('HtmlElement')->setElement('li')->set('ÁCIDO A AÑADIR')->setAttr('data-role','list-divider');
				        if (!empty($result['aviso_acido'])) $lista->add('HtmlElement')->setElement('li')->set($result['aviso_acido']);
				        else 
				        {
				        	$lista->add('HtmlElement')->setElement('li')->set('TOTAL: '.$result['acido_total'].' litros');
				        	//$lista->add('HtmlElement')->setElement('ul');
				        	$lista->add('HtmlElement')->setElement('li')->set('Acido clorhidrico '.$result['acido_clorhidrico'].' litros');
				        	$lista->add('HtmlElement')->setElement('li')->set('Acido citrico '.$result['acido_citrico'].' kilos');
				        	$lista->add('HtmlElement')->setElement('li')->set('Acido lactico '.$result['acido_lactico'].' litros');
				        }	
				    	//QUitar cuando se quite el return al implementar el tratamiento incompleto.    
				    	$this->api->stickyGET('vt');
		    			$boton=$this->add('JQMButton')->set('Otro tratamiento')->href($this->api->getDestinationURL('index'));
				        return;
			        }
			         
			        //Volumen
			        $lista->add('HtmlElement')->setElement('li')->set('VOLUMEN DE SALMUERA A RETIRAR')->setAttr('data-role','list-divider');
			        if ($result['volumen_retirar']>0) 
			        	$lista->add('HtmlElement')->setElement('li')->set($result['volumen_retirar'].' litros');
			        if (!empty($result['aviso_volumen_retirar'])) $lista->add('HtmlElement')->setElement('li')->set($result['aviso_volumen_retirar']);
			        //Aviso salmuera
			        if (!empty($result['aviso_salmuera'])) $lista->add('HtmlElement')->setElement('li')->set($result['aviso_salmuera']);
			        //Aviso sal inicial
			        if (!empty($result['aviso_sal_inicial'])) $lista->add('HtmlElement')->setElement('li')->set($result['aviso_sal_inicial']);
			        //Aviso estandar salmuera blanca
					$lista->add('HtmlElement')->setElement('li')->set($result['aviso_salmuera_blanca']);
			        //Acido
			        $lista->add('HtmlElement')->setElement('li')->set('ÁCIDO A AÑADIR')->setAttr('data-role','list-divider');
			        if (!empty($result['aviso_acido'])) $lista->add('HtmlElement')->setElement('li')->set($result['aviso_acido']);
			        else 
			        {
			        	$lista->add('HtmlElement')->setElement('li')->set('TOTAL: '.$result['acido_total'].' litros');
			        	//$lista->add('HtmlElement')->setElement('ul');
			        	$lista->add('HtmlElement')->setElement('li')->set('Acido clorhidrico '.$result['acido_clorhidrico'].' litros');
			        	$lista->add('HtmlElement')->setElement('li')->set('Acido citrico '.$result['acido_citrico'].' kilos');
			        	$lista->add('HtmlElement')->setElement('li')->set('Acido lactico '.$result['acido_lactico'].' litros');
			        }
			        
			        //Sal
			        if ($result['sal']>0)
			        {
			        	$lista->add('HtmlElement')->setElement('li')->set('SAL')->setAttr('data-role','list-divider');
			        	$lista->add('HtmlElement')->setElement('li')->set('APROXIMADAMENTE '.$result['sal'].' sacos.');
			        }
			  	}
			    $this->api->stickyGET('vt');
		    	$boton=$this->add('JQMButton')->set('Otro tratamiento')->href($this->api->getDestinationURL('index'));
			    //Volcado del array para test
			    //$this->add('P')->set(implode($result));ç
	        }
    	}
    	else $this->add('P')->set('No has pasado parametros para el calculo');
    	
    }
    
}