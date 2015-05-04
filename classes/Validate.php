<?php
class Validate {
	private $_passed = false,
			$_errors = array(),
			$_db = null;
	public function __construct() {
		$this->_db = DB::getInstance ();//instantiating a db object
	}
	
	public function check($source, $items = array()) {
		foreach ( $items as $item => $rules ) {
			foreach ( $rules as $rule => $rule_value ) {
				$value = trim($source[$item]);
				$item = escape($item);
				if ($rule=='required' && empty($value)) {
					$this->addError ("{$item} is required.");
				} else if(!empty($value)) {
					/*
					 * This switch is for checking the rule specified in the form and passed as $source and $item
					 */
					switch ($rule){
						case 'min':
							if(strlen($value) < $rule_value){
								$this -> addError("{$item} must be a minium of {$rule_value} cahracter.");
							}
							break;
						case 'max':
							if(strlen($value) > $rule_value){
								$this->addError("{$item} must be less than {$rule_value} characters.");
							}
							break;
						case 'matches':
							if($value != $source[$rule_value]){
								$this->addError("{$rule_value} must match {$item}.");
							}
							break;
						case 'unique':
							//this is for a field that has to be unique in a table
							$check = $this->_db-> get($rule_value, array($item, '=', $value));
							if($check->count() > 0){
								$this->addError("{$item} already exists!!");
							}
							break;
						
					}
				}
			}
		}
		
		if (empty($this->errors())) {
			$this->_passed = true;
		}
		
		return $this;
	}
	
	private function addError($error) {
		$this->_errors[] = $error;
	}
	
	public function errors() {
		return $this->_errors;
	}
	
	public function passed() {
		return $this->_passed;
	}
}
?>