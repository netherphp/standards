<?php

namespace NetherCS\Sniffs\Coding;

use \NetherCS as NetherCS;

class FunctionVariableDeclareSniff
extends NetherCS\SniffGenericTemplate {
/*//
so the goal here is to find all variables which are invented in the
middle of something and demand they be pre-declared. this means like
variables should not be invented as part of a for, foreach, while, or
whatever, they should be delcared before doing them.
//*/

	const
	FixReason = 'NN: Variable used without prior declaration (%s:%s)';

	protected
	$TokenTypes = [ T_FUNCTION, T_CLOSURE ];

	public function
	Execute():
	Void {

		$Insert = $this->SearchForVarsToInsert();
		$FuncName = $this->GetDeclarationName();
		$Indent = NULL;
		$First = FALSE;
		$Current = NULL;
		$VarPtr = NULL;

		//var_dump($this->Stack[$this->StackPtr]);

		if(!$Insert['Ptr'] || !count($Insert['Vars']))
		return;

		$Indent = $this->GetCurrentIndent($Insert['Ptr']);

		$this->TransactionBegin();
		foreach(array_reverse($Insert['Vars']) as $Current => $VarPtr) {
			if($this->FixBegin(sprintf(static::FixReason,$FuncName,$Current),$VarPtr)) {

				// insert another line before the first one.
				if(!$First && ($First = !$First))
				$this->FixInsertBefore("{$this->File->eolChar}{$Indent}",$Insert['Ptr']);

				// write the variable into the source.
				$this->FixInsertBefore("{$Current} = NULL;{$this->File->eolChar}{$Indent}",$Insert['Ptr']);

			}
		}
		$this->TransactionCommit();

		return;
	}

	protected function
	SearchForVarsToInsert():
	Array {

		$StackPtr = $this->StackPtr;
		$VarsToDefine = [];
		$Current = NULL;

		$OpenPtr    = NULL; // open of the function
		$ClosePtr   = NULL; // end of the function
		$StructPtr  = NULL; // the found control structure.
		$BeginPtr   = NULL; // the start of the control conditions
		$EndPtr     = NULL; // the end of the control conditions
		$VarPtr     = NULL; // the variable in a control conditoin
		$InsertPtr  = NULL; // the location we should insert the variables.

		// quit if we found an abstract method.

		if(!array_key_exists('scope_opener',$this->Stack[$StackPtr]))
		goto TheEnd;

		$OpenPtr = $this->Stack[$StackPtr]['scope_opener'];
		$ClosePtr = $this->Stack[$StackPtr]['scope_closer'];
		$StructPtr = $this->FindStructsThatMightContainMisuse($OpenPtr,$ClosePtr);
		$InsertPtr = $this->FindInsertionPoint(($OpenPtr+1),$ClosePtr);

		// quit if we found a function that apparently does not do much.

		if(!$StructPtr)
		goto TheEnd;

		// now go through the code looking for the vars.

		$StackPtr = $OpenPtr;

		while($StructPtr = $this->FindStructsThatMightContainMisuse($StackPtr, $ClosePtr)) {
			if(!array_key_exists('parenthesis_opener',$this->Stack[$StructPtr]))
			goto TryNextStruct;

			$BeginPtr = $this->Stack[$StructPtr]['parenthesis_opener'];
			$EndPtr = $this->Stack[$StructPtr]['parenthesis_closer'];

			while($VarPtr = $this->FindVariables($BeginPtr, $EndPtr)) {
				$Current = $this->GetContentFromStack($VarPtr);

				// don't try to create $this
				if($Current === '$this')
				goto TryNextVar;

				// don't try to create superglobals.
				if(strpos($Current,'$_') === 0)
				goto TryNextVar;

				// don't try to create static variables.
				if($this->IsStaticReference($VarPtr))
				goto TryNextVar;

				// look to see if the var was defined prior to now.
				if($this->FindSpecificVariable($this->StackPtr, ($VarPtr-1), $Current))
				goto TryNextVar;

				// make sure this variable is actually still in the same scope.
				// we don't want to catch variables in callables to define them outside
				// of the callable.

				if($this->GetCurrentScope($VarPtr) !== $this->StackPtr)
				goto TryNextVar;

				// report this variable as needing defined.
				if(!array_key_exists($Current, $VarsToDefine))
				$VarsToDefine[$Current] = $VarPtr;

				TryNextVar:
				$BeginPtr = $VarPtr + 1;
			}

			TryNextStruct:
			$StackPtr = $StructPtr + 1;
		}

		TheEnd:
		return [ 'Ptr' => $InsertPtr, 'Vars' => $VarsToDefine ];
	}

	protected function
	FindStructsThatMightContainMisuse(Int $Start, Int $Stop):
	?Int {

		return $this->File->FindNext(
			[ T_FOR, T_FOREACH, T_WHILE, T_IF ],
			$Start, $Stop
		) ?: NULL;
	}

	protected function
	FindVariables(Int $Start, Int $Stop):
	?Int {

		return $this->File->FindNext(
			[ T_VARIABLE ],
			$Start ,$Stop
		) ?: NULL;
	}

	protected function
	FindSpecificVariable(Int $Start, Int $Stop,$Which):
	?Int {

		return $this->File->FindNext(
			[ T_VARIABLE ],
			$Start, $Stop, FALSE, $Which
		) ?: NULL;
	}

	protected function
	FindInsertionPoint(Int $Start, Int $Stop):
	?Int {

		$Ptr = $this->File->FindNext(
			[T_WHITESPACE,T_COMMENT],
			$Start, $Stop, TRUE
		);

		return $Ptr ?: NULL;
	}

	protected function
	IsStaticReference(Int $Ptr):
	Bool {

		$PaamNekoPtr = $this->File->FindPrevious(
			[ T_WHITESPACE ],
			($Ptr-1),
			NULL,
			TRUE
		);

		return ($this->GetTypeFromStack($PaamNekoPtr) === T_PAAMAYIM_NEKUDOTAYIM);
	}

}
