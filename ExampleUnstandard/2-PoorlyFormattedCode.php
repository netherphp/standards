<?php

class poorlyFormattedClass extends StdClass {

	var $superOldProperty = 'old';

	public $uglyProperty = 9;
	
	protected $ProtInlineProp1 = 2, $ProtInlineProp2;

	private $PrivInlineProp1,$ProtIlineProp2 = 7;

	static protected
	$staticProperty,
	$anotherStatic;

	function malformedMethod(string $input, bool $yeah_ok): void {

		$uglyVariable = true;
		$uglyVariable = Null;

		$uglyVariable = str_replace('a','b','c');

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
