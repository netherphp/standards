<?php

class poorlyFormattedClass extends StdClass {

	var $superOldProperty = 'old';

	public $uglyProperty = 9;

	static protected
	$staticProperty;

	function malformedMethod(string $input, bool $yeah_ok): void {

		$uglyVariable = true;
		$uglyVariable = Null;

	}

	public function
	malformedMethod_reducedConcern():Void {

		$this->GetSomeArray();
		$this->uglyProperty = 8;

		static::$staticProperty = 7;
		self::$staticProperty = 6;
		$this::$staticProperty = 5;

		return;
	}

	public function
	GetSomeArray():
	Array {

		$Array = array();

		return $Array;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	ProperMethod():
	Void {

		return;
	}

	public function
	ProperMethod_ReducedConcern():
	Void {

		return;
	}

}

function whyIsThereFunctionOutHere(): String {

	return 'whatever';
}
