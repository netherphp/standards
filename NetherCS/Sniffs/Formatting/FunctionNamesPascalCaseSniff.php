<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class FunctionNamesPascalCaseSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_FUNCTION ];

	const
	FixReason       = 'NN: Method/Function must be PascalCased';

	public function
	Execute():
	Void {

		// T_FUNCTION T_WHITESPACE T_STRING
		// This       +1          +2

		$StackPtr = $this->StackPtr;
		$Type = T_FUNCTION;
		$Current = NULL;
		$Expected = NULL;

		// in these contexts it seems we can actually have more than one
		// whitespace token in a row due to a newline.

		while($Type && $Type !== T_STRING) {
			$Type = $this->GetTypeFromStack(++$StackPtr);
		}

		$Current = $this->GetContentFromStack($StackPtr);
		$Expected = NetherCS\SniffGenericTemplate::ConvertMethodToPascalCase($Current);

		if($Current !== $Expected)
		$this->SubmitFixAndShow(static::FixReason,$Current,$Expected,$StackPtr);

		return;
	}

}
