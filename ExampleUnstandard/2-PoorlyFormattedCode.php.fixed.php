<?php

class PoorlyFormattedClass
extends StdClass {

	public $uglyProperty = 9;

	public function
	MalformedMethod(string $Input, bool $YeahOk):
	Void {

		$UglyVariable = TRUE;
		$UglyVariable = NULL;

		return;
	}

	public function
	MalformedMethod_ReducedConcern():
	Void {

		$this->GetSomeArray();

		return;
	}

	public function
	GetSomeArray():
	Array {

		$Array = [];

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

function WhyIsThereFunctionOutHere():
String {

	return 'whatever';
}
