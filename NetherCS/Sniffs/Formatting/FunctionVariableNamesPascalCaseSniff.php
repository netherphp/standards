<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class FunctionVariableNamesPascalCaseSniff
extends NetherCS\SniffTemplate {

	protected
	$TokenTypes = [ T_FUNCTION ];

	const
	FixReason = 'NN: Methods and Function scope vars should be PascalCase';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$OpenPtr = NULL;
		$ClosePtr = NULL;
		$Seek = NULL;
		$Current = NULL;
		$Expected = NULL;

        while($this->GetTypeFromStack($StackPtr) !== T_OPEN_CURLY_BRACKET)
		$StackPtr++;
		
		$OpenPtr = $this->Stack[$StackPtr]['scope_opener'];
		$ClosePtr = $this->Stack[$StackPtr]['scope_closer'];
		$StackPtr = $this->StackPtr;

		////////

		while($StackPtr < $ClosePtr) {
			$Seek = $this->GetTypeFromStack($StackPtr);

			if($Seek === T_VARIABLE) {
				$Current = $this->GetContentFromStack($StackPtr);
				$Expected = NetherCS\SniffTemplate::ConvertVariableToPascalCase($Current);

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
