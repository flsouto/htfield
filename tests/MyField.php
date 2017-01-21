<?php

#mdx:h HtFieldAlias
use FlSouto\HtField;
require_once('vendor/autoload.php');

#mdx:MyField
class MyField extends HtField{

	// Set field as required
	function required($errmsg){
		$this->param->filters()->required($errmsg);
		return $this;
	}

	// It is mandatory to implement the 'render' method
	function render(){
		echo "<input ".$this->attrs." />";
	}

}
#/mdx