<?php
namespace FlSouto;

abstract class HtField{

	protected $attrs;
	protected $param;
	protected $context = array();
	protected $result = null;


	function __construct($name){
		$this->param = new Param($name);
		$this->attrs = new HtAttrs;
		$this->attrs['id'] = 'field_'.uniqid();
		$this->attrs['name'] = $name;
	}

	function id(){
		return $this->attrs['id'];
	}

	function name(){
		return $this->param->name();
	}

	function context($context){
		$this->context = $context;
		$this->param->context($context);
		$this->result = null;
		return $this;
	}

	function process($force=false){
		
		if(!$this->result||$force){
			if(is_array($force)){
				$this->result = $this->param->process($force);
			} else {
				$this->result = $this->param->process();
			}
		}
		return $this->result;
	}

	function value(){
		return $this->process()->output;
	}

	function validate(){
		return $this->process()->error;
	}

	function attrs(array $attrs){
		$this->attrs->merge($attrs);
		return $this;
	}

	abstract function render();

	function __toString(){
		ob_start();
		$this->render();
		return ob_get_clean();
	}

}