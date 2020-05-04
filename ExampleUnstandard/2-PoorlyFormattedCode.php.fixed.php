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

	const
	SameLineConsts = 'one';

	const
	SameLineConst2 = 'two',
	SameLineConst3 = 'three';

	const
	ArrayConstsTho = [ T_WHITESPACE, T_CONST ];

	const
	LongArrayConstsTho = [
		T_WHITESPACE,
		T_CONST
	];

	private
	$ArrayInstance = [ 'one','two',T_EMPTY ];

	private
	$LongArrayInstance = [
		'one',
		'two',
		T_EMPTY
	];

	static private
	$ArrayStatic = [ 'one','two',T_EMPTY ];

	static private
	$LongArrayStatic = [
		'one',
		'two',
		T_EMPTY
	];

	public function
	MalformedMethod(String $Input, Bool $YeahOk):
	Void {

		$UglyVariable = TRUE;
		$UglyVariable = NULL;

		$UglyVariable = str_replace('a','b','c');

		$this->MalformedMethod_ReducedConcern();
		static::MalformedStaticMethod();

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
	MalformedStaticMethod():
	Void {

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
	SomeEmptyMethod():
	Void {
		return;
	}

	public function
	AnotherEmptyMethod():
	Void {
		return;
	}

	public function
	ThirdEmptyMethod():
	Void {
		return;
	}

	public function
	MethodWithMalformedNullableDefaultType(?String $String):
	Void {

		return;
	}

	public function
	MethodWithBracesUnderneath() {

		return;
	}

	public function
	MethodWithBracesUnderneathTyped():
	Int {

		return 1;
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
