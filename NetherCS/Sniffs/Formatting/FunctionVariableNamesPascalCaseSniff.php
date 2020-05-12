<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS as NetherCS;

class FunctionVariableNamesPascalCaseSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_FUNCTION ];

	const
	FixReason = 'NN: Method/Function scope vars must be PascalCase (%s)';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$OpenPtr = NULL;
		$ClosePtr = NULL;
		$Seek = NULL;
		$Current = NULL;
		$Expected = NULL;

		while(($Seek = $this->GetTypeFromStack($StackPtr)) && $Seek !== T_OPEN_CURLY_BRACKET && $Seek !== T_SEMICOLON)
		$StackPtr++;

		// we have a function with a full body.

		if($Seek === T_OPEN_CURLY_BRACKET) {
			$OpenPtr = $this->Stack[$StackPtr]['scope_opener'];
			$ClosePtr = $this->Stack[$StackPtr]['scope_closer'];
		}

		// we only have an abstract function.

		else {
			$ClosePtr = $StackPtr;
		}

		// but start from the declaration to hit the arguments too.

		$StackPtr = $this->StackPtr;

		////////

		while($StackPtr < $ClosePtr) {
			$Seek = $this->GetTypeFromStack($StackPtr);

			if($Seek === T_VARIABLE) {

				// don't rewrite static properties.
				if($this->GetTypeFromStack($StackPtr-1) === T_PAAMAYIM_NEKUDOTAYIM) {
					$StackPtr++;
					continue;
				}

				$Current = $this->GetContentFromStack($StackPtr);

				// don't rewrite variable names that are all uppercase to allow you
				// to have things like $SQL if you so wish.
				if(!preg_match('/^\$[^A-Z]+/',$Current)) {
					$StackPtr++;
					continue;
				}

				$Expected = NetherCS\SniffGenericTemplate::ConvertVariableToPascalCase($Current);

				if($Current !== $Expected)
				$this->Fix(
					sprintf(static::FixReason,$Current),
					$Expected, $StackPtr
				);
			}

			$StackPtr++;
		}

		return;
	}

}
