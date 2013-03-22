<?php
class page_don extends Page  {
	function init(){
        parent::init();
		
		
		
		$tabs=$this->add('Tabs')->addClass('ClassTab');
		
		$tab1=$tabs->addTab('Artículos');
		
		$tab1->add('CRUD')->setModel('Model_Articulos');
		
		$tab2=$tabs->addTab('Almacenes');
		
		$tab2->add('CRUD')->setModel('Model_Almacenes');
		
		
		$modelo=$this->add('Model_Articulos');
		$modelo->load(3);
		$alm=$modelo->ref('almacenes_id')->get('name');
		
		
		$this->add('HtmlElement')->setElement('p')->set($alm);
		
		$this->add('P')->set($alm);
		
		//$boton=$this->add('Button')->set('Ocultar');
		
		//$tabs->js('click')->_selector('#don_project_don_tabs')->hide('slow')->execute();
		
/*	

 		$modelo=$this->add('Model_Articulos');
		$modelo->load(3);
		$form=$this->add('Form');
		$form->setModel($modelo);
		
		$form->addSubmit('Enviar');
		
		$form->onSubmit(function($formparam) {
			$formparam->update();
			
			$formparam->js()->hide('fast')->univ()->successMessage('Actualizado')->execute();
		});*/
		
	}    
}




