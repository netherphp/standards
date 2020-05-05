<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;

class FunctionDeclareVarsEarlySniff
extends NetherCS\SniffGenericTemplate {

	const
	FixReason = 'NN: Variable used without prior declaration';

	protected
	$TokenTypes = [ T_FUNCTION ];

	public function
	Execute():
	Void {
	/*//
	so the goal here is to find all variables which are invented in the
	middle of something and demand they be pre-declared. this means like
	variables should not be invented as part of a for, foreach, while, or
	whatever, they should be delcared before doing them.
	//*/

		$this->SubmitFix(static::FixReason,'function','function'.chr(10));
		return;

		$Indent = $this->GetCurrentIndent();
		$StackPtr = $this->StackPtr;
		$VarsToDefine = [];
		$Current = NULL;
		$Default = NULL;

		$OpenPtr    = NULL; // open of the function
		$ClosePtr   = NULL; // end of the function
		$StructPtr  = NULL; // the found control structure.
		$BeginPtr   = NULL; // the start of the control conditions
		$EndPtr     = NULL; // the end of the control conditions
		$VarPtr     = NULL; // the variable in a control conditoin
		$VarSeekPtr = NULL; // the variable if defined prior to a control condition

		$Find = function($Start,$Stop) {
			return $this->File->FindNext(
				[T_FOR,T_FOREACH,T_WHILE,T_IF],
				$Start, $Stop
			);
		};

		// quit if we found an abstract method.

		if(!array_key_exists('scope_opener',$this->Stack[$StackPtr]))
		return;

		$OpenPtr = $this->Stack[$StackPtr]['scope_opener'];
		$ClosePtr = $this->Stack[$StackPtr]['scope_closer'];
		$StackPtr = $OpenPtr;
		$StructPtr = $Find($OpenPtr,$ClosePtr);

		// quit if we found a function that apparently does not do much.

		if(!$StructPtr || $StructPtr === $ClosePtr)
		return;

		// now go through the code for real.

		$StackPtr = $OpenPtr;
		while(($StructPtr = $Find($StackPtr,$ClosePtr)) && $StructPtr < $ClosePtr) {
			if(!array_key_exists('parenthesis_opener',$this->Stack[$StructPtr]))
			continue;

			$BeginPtr = $this->Stack[$StructPtr]['parenthesis_opener'];
			$EndPtr = $this->Stack[$StructPtr]['parenthesis_closer'];

			// look for all variables within the control structure call.

			while(($VarPtr = $this->File->FindNext([T_VARIABLE],$BeginPtr,$EndPtr)) && $VarPtr < $EndPtr) {

				$Current = $this->GetContentFromStack($VarPtr);

				// lol m8

				if($Current === '$this') {
					$BeginPtr = $VarPtr + 1;
					continue;
				}

				// find if that variable has been defined prior to now. if not
				// then add it to the list of vars to define after. start from
				// the function pointer to catch any args or use.

				$VarSeekPtr = $this->File->FindPrevious(
					[T_VARIABLE],
					$this->StackPtr,
					($VarPtr - 1)
				);

				if(!$VarSeekPtr && !array_key_exists($Current,$VarsToDefine)) {
					// todo - see how fancy we can get detecting what it was assigned
					// to so that the define define can be the same type. that is why
					// we keyed it and set null here so that it can be a different value
					// later.
					$VarsToDefine[$Current] = 'NULL';
				}

				$BeginPtr = $VarPtr + 1;
			}

			$StackPtr = $StructPtr + 1;
		}

		// write the code for the variables we were missing.

		//foreach($VarsToDefine as $Current => $Default) {


			//break;
		//}

		return;
	}

}
