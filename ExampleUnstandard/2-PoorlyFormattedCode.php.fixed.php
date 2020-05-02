<?php

class PoorlyFormattedClass
extends StdClass {

	var $SuperOldProperty = 'old';

	public $UglyProperty = 9;

	static protected
	$StaticProperty;

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
		$this->uglyProperty = 8;

		static::$staticProperty = 7;
		self::$staticProperty = 6;
		$this::$staticProperty = 5;

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
