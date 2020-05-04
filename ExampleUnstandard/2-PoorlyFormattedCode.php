<?php

class poorlyFormattedClass extends StdClass {

	var $superOldProperty = 'old';

	public $uglyProperty = 9;

	protected $ProtInlineProp1 = 2, $ProtInlineProp2;

	private $PrivInlineProp1,$ProtIlineProp2 = 7;

	static protected
	$staticProperty,
	$anotherStatic;

	const SameLineConst = 'one';

	const SameLineConst2 = 'two', SameLineConst3 = 'three';

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

	function malformedMethod(string $input, bool $yeah_ok): void {

		$uglyVariable = true;
		$uglyVariable = Null;

		$uglyVariable = str_replace('a','b','c');

		$this->malformedMethod_reducedConcern();
		static::malformedStaticMethod();

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
	malformedStaticMethod():
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
	Void { }

	public function
	AnotherEmptyMethod():
	Void {}

	public function
	ThirdEmptyMethod():
	Void {
	}

	public function
	MethodWithMalformedNullableDefaultType(?string $String):
	Void {

		return;
	}

	public function
	MethodWithBracesUnderneath()
	{

		return;
	}

	public function
	MethodWithBracesUnderneathTyped(): Int
	{

		return 1;
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

function whyIsThereFunctionOutHere(): string {

	return 'whatever';
}
