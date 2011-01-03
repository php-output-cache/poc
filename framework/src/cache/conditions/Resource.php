<?php

abstract class Resource {

  var $value;
  var $pattern;
  
  function __construct ($pattern) {
    $this->pattern = $pattern;
    $this->value = $this->setValue();
  }

  function getValue() {
    return $this->value;
  }
  
  abstract function setValue();
  
  function evaluate() {
    return preg_match($this->pattern, $this->value);
  }

}
