<?php
class Field_Reference extends Field {
    public $model_name=null;
    public $display_field=null;
    public $dereferenced_field=null;

    function setModel($model,$display_field=null){

        $this->model_name=is_string($model)?$model:get_class($model);
        $this->model_name=preg_replace('|^(.*/)?(.*)$|','\1Model_\2',$this->model_name);
        
        if($display_field)$this->display_field=$display_field;

        $this->owner->addExpression($this->getDereferenced())
            ->set(array($this,'calculateSubQuery'))->caption($this->caption());

        $this->visible(false);

        return $this;
    }
    /**
     * ref() will traverse reference and will attempt to load related model's entry. If the entry will fail to load
     * it will return model which would not be loaded. This can be changed by specifying an argument:
     *
     * 'model' - simply create new model and return it without loading anything
     * false or 'ignore' - will not even try to load anything
     * null (default) - will tryLoad()
     * 'load' - will always load the model and if record is not present, will fail
     * 'create' - if record fails to load, will create new record, save, get ID and insert into $this
     * 'link' - if record fails to load, will return new record, with appropriate afterSave hander, which will
     *          update current model also and save it too.
     *
     */
    function getModel(){
        if(!$this->model)$this->model=$this->add($this->model_name);
        return $this->model;
    }
    function ref($mode=null){
        if($mode=='model'){
            return $this->add($this->model_name);
        }

        $this->getModel()->unload();


        if($mode===false || $mode=='ignore'){
            return $this->model;
        }
        if($mode=='load'){
            return $this->model->load($this->get());
        }
        if($mode===null){
            if($this->get())$this->model->tryLoad($this->get());
            return $this->model;
        }
        if($mode=='create'){
            if($this->get())$this->model->tryLoad($this->get());
            if(!$this->model->loaded()){
                $this->model->save();
                $this->set($this->model->id);
                $this->owner->update();
                return $this->model;
            }
        }
        if($mode=='link'){
            $m=$this->add($this->model_name);
            if($this->get())$m->tryLoad($this->get());
            $t=$this;
            $m->addHook('afterSave',function($m)use($t){
                $t->set($m->id);
                $t->owner->save();
            });
            return $m;
        }
    }
    function getDereferenced(){
        if($this->dereferenced_field)return $this->dereferenced_field;
        $f=preg_replace('/_id$/','',$this->short_name);
        if($f!=$this->short_name)return $f;

        $f=$this->_unique($this->owner->elements,$f);
        return $f;
    }
    function destroy(){
        $this->owner->getElement($this->getDereferenced())->destroy();
        return parent::destroy();
    }
    function calculateSubQuery($model,$select){
        if(!$this->model)$this->model=$this->add($this->model_name);

        if($this->display_field){
            $title=$this->model->dsql()->del('fields');
            $this->model->getElement($this->display_field)->updateSelectQuery($title);
        }else{
            $title=$this->model->titleQuery();
        }
        if($this->relation){
            $title->where($this->relation->short_name.'.'.$this->short_name,$title->getField($this->model->id_field));
        }else{
            $title->where($this->owner->_dsql()->getField($this->short_name),$title->getField($this->model->id_field));
        }
        return $title;
    }
}
