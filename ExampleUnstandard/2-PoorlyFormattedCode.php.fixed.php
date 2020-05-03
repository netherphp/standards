<?php

class PoorlyFormattedClass
extends StdClass {

	var
	$SuperOldProperty = 'old';

	public
	$UglyProperty = 9;
	
	protected
	$ProtInlineProp1 = 2,
	$ProtInlineProp2;

	private
	$PrivInlineProp1,
	$ProtIlineProp2 = 7;

	static protected
	$StaticProperty,
	$AnotherStatic;

	public function
	MalformedMethod(String $Input, Bool $YeahOk):
	Void {

		$UglyVariable = TRUE;
		$UglyVariable = NULL;

		$UglyVariable = str_replace('a','b','c');

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
	MethodUsingVarsWithoutDeclaringThem():
	Void {

		for($Iter = 0; $Iter < 10; $Iter++) {
			
		}

		foreach($this->GetSomeArray() as $Item) {

		}

		while($Row = $this->ProperMethod()) {
			
		}

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
