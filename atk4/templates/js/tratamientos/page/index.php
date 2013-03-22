<?php
require_once('/usr/home/test.laespanola.es/web/soap/lib/nusoap.php');

class page_index extends Page {
    function init(){
        parent::init();
        $this->api->template->set('page_title','Tratamiento de fermentadores');
        $h=$this->add('JQMHeader')->set('Tratamiento de fermentadores');
        //$h->addButtonRight($this->api->auth->model['nick'],'account','gear');
       
        parent::init();
       
        //$c = new nusoap_client('http://test.laespanola.es/soap/server.php?wdsl');
        $c = new nusoap_client('http://80.38.86.3:1111/servicios/server.php');
        $result = $c->call('getValoresFinales',array());

		$vt=(empty($_GET['vt']))?12300:$_GET['vt'];
		$ali=(empty($_GET['ali']))?'':$_GET['ali'];
		$lri=(empty($_GET['lri']))?'':$_GET['lri'];
		$si=(empty($_GET['si']))?'':$_GET['si'];
		if (empty($result['ALFMI'])) {
	        $this->add('HtmlElement')->setElement('P')->set('ERROR CONECTANDO AL SERVICIO WEB');
	        
    		$boton=$this->add('JQMButton')->set('Reintentar')->href($this->api->getDestinationURL('index'));
	        return;
        }
		$f=$this->add('Form');
		$f->addField('line','vt','Volumen total')->set($vt)->setAttr('type','number');
		$f->add('HR');
        $f->addField('line','ali','Acidez libre inicial (AC.L.i)')->set($ali)->setAttr('type','number')->setAttr('step','0.01');
        $f->add('HtmlElement')->setElement('div')->set('Min: '.$result['ALFMI'].' Max: '.$result['ALFMA'].' Obj: '.$result['ALF'])->setClass('comentario');
        $f->add('HR');
		$campolri=$f->addField('line','lri','Lejia Residual inicial (L.R.i)')->set($lri)->setAttr('type','number')->setAttr('step','0.01');
		$f->add('HtmlElement')->setElement('div')->set('Min: '.$result['LRFMI'].' Max: '.$result['LRFMA'].' Obj: '.$result['LRF'])->setClass('comentario');
		$f->add('HR');
		$f->addField('line','si','Sal inicial (ºBE.i)')->set($si)->setAttr('type','number')->setAttr('step','0.01');
		$f->add('HtmlElement')->setElement('div')->set('Min: '.$result['SFMI'].' Max: '.$result['SFMA'].' Obj: '.$result['SF'])->setClass('comentario')->addClass('ultimo');
        $boton=$f->addSubmit()->set('Tratamiento');

        $f->onSubmit(function($form){
        	$p=$form->api->getDestinationURL('./tratamiento',$form->data);
        	$form->js()->univ()->location($p)->execute();
        	$form->api->redirect($p);
        
        
        });
    }
}
