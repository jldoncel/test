<?php
class page_index extends Page {
    function init(){
        parent::init();
        $page=$this;

        // Adding view box with another view object inside with my custom HTML template
        $this->add('View_Info')->add('View',null,null,array('view/myinfobox'));

        // Paste any Agile Toolkit examples BELOW THIS LINE. You can remove what I have here:

        // Adding a View and chaining example
        $this->add('H1')->set('Hello World from your own copy of Agile Toolkit');

        // Assign reference to your object into variable $button
        $button = $page->add('Button')->setLabel('Refresh following text with AJAX');

        // You can store multiple references, different views, will have different methods
        $lorem_ipsum = $this->add('LoremIpsum')->setLength(1,200);
		
		$lorem_ipsum->js(true)->addClass('parrafo');
		$lorem_ipsum->js('ocultar')->hide('slow');
		
        // Bind button click with lorem_ipsum text reload
        $button->js('click',$lorem_ipsum->js()->reload());


		$this->add('Form')->addField('line','foo')->setNotNull();

		$modelo=$this->add('Model_Articulos');
		$modelo->load(3);
		$form=$this->add('Form');
		$form->setModel($modelo);
		
		$form->addSubmit('Enviar');
		
		$form->onSubmit(function($formparam) {
			$formparam->update();
			
			$formparam->js(null,$formparam->js()->hide('slow')->univ()->successMessage('Guardado'))->_selector('.parrafo')->trigger('ocultar')->execute();
		});
    }
}
