<?php

namespace NetherCS\Sniffs\Coding;

use \NetherCS;

class FunctionVariableDeclareSniff
extends NetherCS\SniffGenericTemplate {
/*//
so the goal here is to find all variables which are invented in the
middle of something and demand they be pre-declared. this means like
variables should not be invented as part of a for, foreach, while, or
whatever, they should be delcared before doing them.
//*/

	const
	FixReason = 'NN: Variable used without prior declaration (%s)';

	protected
	$TokenTypes = [ T_FUNCTION ];

	public function
	Execute():
	Void {

		$Insert = $this->SearchForVarsToInsert();
		$Indent = NULL;
		$First = FALSE;
		$Current = NULL;
		$VarPtr = NULL;

		if(!$Insert['Ptr'] || !count($Insert['Vars']))
		return;

		$Indent = $this->GetCurrentIndent($Insert['Ptr']);

		$this->TransactionBegin();
		foreach(array_reverse($Insert['Vars']) as $Current => $VarPtr) {
			if($this->FixBegin(sprintf(static::FixReason,$Current),$VarPtr)) {

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
		goto FindMisusedVariablesEnd;

		$OpenPtr = $this->Stack[$StackPtr]['scope_opener'];
		$ClosePtr = $this->Stack[$StackPtr]['scope_closer'];
		$StructPtr = $this->FindStructsThatMightContainMisuse($OpenPtr,$ClosePtr);
		$InsertPtr = $this->FindInsertionPoint(($OpenPtr+1),$ClosePtr);

		// quit if we found a function that apparently does not do much.

		if(!$StructPtr)
		goto FindMisusedVariablesEnd;

		// now go through the code looking for the vars.

		$StackPtr = $OpenPtr;

		while($StructPtr = $this->FindStructsThatMightContainMisuse($StackPtr, $ClosePtr)) {
			if(!array_key_exists('parenthesis_opener',$this->Stack[$StructPtr]))
			goto FindMisusedVariablesTryNextStruct;

			$BeginPtr = $this->Stack[$StructPtr]['parenthesis_opener'];
			$EndPtr = $this->Stack[$StructPtr]['parenthesis_closer'];

			while($VarPtr = $this->FindVariables($BeginPtr, $EndPtr)) {
				$Current = $this->GetContentFromStack($VarPtr);

				// don't try to create $this
				if($Current === '$this')
				goto FindMisusedVariablesTryNextVar;

				// don't try to create static variables.
				if($this->IsStaticReference($VarPtr))
				goto FindMisusedVariablesTryNextVar;

				// look to see if the var was defined prior to now.
				if($this->FindSpecificVariable($this->StackPtr, ($VarPtr-1), $Current))
				goto FindMisusedVariablesTryNextVar;

				// report this variable as needing defined.
				if(!array_key_exists($Current, $VarsToDefine))
				$VarsToDefine[$Current] = $VarPtr;

				FindMisusedVariablesTryNextVar:
				$BeginPtr = $VarPtr + 1;
			}

			FindMisusedVariablesTryNextStruct:
			$StackPtr = $StructPtr + 1;
		}

		FindMisusedVariablesEnd:
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
