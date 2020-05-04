<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;

class FunctionVariableNamesPascalCaseSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_FUNCTION ];

	const
	FixReason = 'NN: Method/Function scope vars must be PascalCase';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$OpenPtr = NULL;
		$ClosePtr = NULL;
		$Seek = NULL;
		$Current = NULL;
		$Expected = NULL;

		while(($Seek = $this->GetTypeFromStack($StackPtr)) && $Seek !== T_OPEN_CURLY_BRACKET)
		$StackPtr++;

		$OpenPtr = $this->Stack[$StackPtr]['scope_opener'];
		$ClosePtr = $this->Stack[$StackPtr]['scope_closer'];
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
				$Expected = NetherCS\SniffGenericTemplate::ConvertVariableToPascalCase($Current);

				if($Current !== $Expected)
				$this->SubmitFixAndShow(
					static::FixReason,
					$Current,
					$Expected,
					$StackPtr
				);
			}

			$StackPtr++;
		}

		return;
	}

}
