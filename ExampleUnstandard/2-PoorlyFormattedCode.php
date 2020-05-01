<?php

class poorlyFormattedClass extends StdClass {

	public $uglyProperty = 9;

	function malformedMethod(string $input, bool $yeah_ok): void {

		$uglyVariable = true;
		$uglyVariable = Null;

	}

	public function
	malformedMethod_reducedConcern():Void {

		$this->GetSomeArray();

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
