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
	$PrivInlineProp2 = 7;

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
	MalformedMethod(string $Input, bool $YeahOk):
	void {

		$UglyVariable = TRUE;
		$UglyVariable = NULL;

		$UglyVariable = str_replace('a','b','c');

		$this->MalformedMethod_ReducedConcern();
		static::MalformedStaticMethod();

		echo static::SameLineConsts;

		return;
	}

	public function
	MalformedMethod_ReducedConcern():
	void {

		$this->GetSomeArray();
		$this->UglyProperty = 8;

		static::$StaticProperty = 7;
		self::$StaticProperty = 6;
		$this::$StaticProperty = 5;

		return;
	}

	public function
	MalformedStaticMethod():
	void {

		return;
	}

	public function
	MethodUsingVarsWithoutDeclaringThem(int $NotThisOneTho):
	void {

		$Iter = NULL;
		$Item = NULL;
		$Row = NULL;
		$Cond = NULL;
		
		for($Iter = 0; $Iter < 10; $Iter++) {

		}

		foreach($this->GetSomeArray() as $Item) {

		}

		while($Row = $this->ProperMethod()) {

		}

		if($Cond = $this->ProperMethod()) {

		}

		while($NotThisOneTho > 0)
		$NotThisOneTho--;

		if(count($_SERVER['argv']))
		$NotThisOneTho++;

		return;
	}

	public function
	MethodUsingVarsWithoutDeclaringThemDocblocked():
	void {
	/*//
	this method is totally documented.
	//*/

		$Iter = NULL;
		
		for($Iter = 0; $Iter < 10; $Iter++)
		$Iter++;

		return;
	}

	public function
	MethodReturningSelf():
	self {

		return $this;
	}

	public function
	SomeEmptyMethod():
	void {
		return;
	}

	public function
	AnotherEmptyMethod():
	void {
		return;
	}

	public function
	ThirdEmptyMethod():
	void {
		return;
	}

	public function
	MethodWithMalformedNullableDefaultType(?string $String):
	void {

		return;
	}

	public function
	MethodWithBracesUnderneath() {

		return;
	}

	public function
	MethodWithBracesUnderneathTyped():
	int {

		return 1;
	}

	public function
	GetSomeArray():
	array {

		$Array = [];

		return $Array;
	}

	////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function
	ProperMethod():
	void {

		return;
	}

	public function
	ProperMethod_ReducedConcern():
	void {

		return;
	}

}

function
WhyIsThereFunctionOutHere():
string {

	return 'whatever';
}
