<?php
/***********************************************************
  ..

  Reference:
  http://agiletoolkit.org/doc/ref

 **ATK4*****************************************************
 This file is part of Agile Toolkit 4 
 http://agiletoolkit.org

 (c) 2008-2011 Agile Technologies Ireland Limited
 Distributed under Affero General Public License v3

 If you are using this file in YOUR web software, you
 must make your make source code for YOUR web software
 public.

 See LICENSE.txt for more information

 You can obtain non-public copy of Agile Toolkit 4 at
 http://agiletoolkit.org/commercial

 *****************************************************ATK4**/
class Form_Field_Slider extends Form_Field {
	public $min=0,$max=10;
	function setRange($min,$max){
		$this->min=$min;
		$this->max=$max;
		return $this;
	}

	function getInput(){
		$this->setAttr('type','range');
		$this->setAttr('min',$this->min);
		$this->setAttr('max',$this->max);
		return parent::getInput();
	}
}
