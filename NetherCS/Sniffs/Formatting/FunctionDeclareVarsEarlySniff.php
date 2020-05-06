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


		$Indent = NULL;
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
		$InsertPtr  = NULL;

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
		$InsertPtr = $this->File->FindNext([T_WHITESPACE],($OpenPtr+1),$ClosePtr,TRUE);
		$Indent = $this->GetCurrentIndent($InsertPtr);

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

				$VarSeekPtr = $this->File->FindNext(
					[T_VARIABLE],
					$this->StackPtr,
					($VarPtr - 1),
					FALSE,
					$Current
				);

				fwrite(STDERR,"search for {$Current} = {$VarSeekPtr}\n");

				if($VarSeekPtr) {
					$BeginPtr = $VarPtr + 1;
					continue;
				}

				if(!array_key_exists($Current,$VarsToDefine)) {
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

		if(count($VarsToDefine)) {
			if($this->File->AddFixableError(static::FixReason,$this->StackPtr,'UndefVarUseMyDude')) {

				$Code = '';
				foreach($VarsToDefine as $Current => $Default) {
					$Code .= "{$Current} = {$Default};{$this->File->eolChar}{$Indent}";
				}
				$Code .= $this->File->eolChar.$Indent;

				$this->File->fixer->addContentBefore($InsertPtr,$Code);
			}
		}


		return;
	}

}
