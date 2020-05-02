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
		$this->UglyProperty = 8;

		static::$StaticProperty = 7;
		self::$StaticProperty = 6;
		$this::$StaticProperty = 5;

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
