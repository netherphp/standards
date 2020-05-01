<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class FunctionExplicitReturnSniff
extends NetherCS\SniffTemplate {

	protected
	$TokenTypes = [ T_FUNCTION ];

	const
	FixReason = 'NN: Methods and Functions should explicitly return';

	public function
	Execute():
	Void {

        $StackPtr = $this->StackPtr;
		$OpenPtr = NULL;
		$ClosePtr = NULL;
		$Indent = NULL;
		$HasReturn = FALSE;


        while($this->GetTypeFromStack($StackPtr) !== T_OPEN_CURLY_BRACKET)
		$StackPtr++;
		
		// now we know where this function body starts ane ends.

		$OpenPtr = $this->Stack[$StackPtr]['scope_opener'];
		$ClosePtr = $this->Stack[$StackPtr]['scope_closer'];
		
		// we can determine the indent for this function.

		$Indent = $this->GetCurrentIndent($ClosePtr);

		// now determine if this function ever returned a value.

		$StackPtr = $OpenPtr;
		while($StackPtr < $ClosePtr) {
			if($this->GetTypeFromStack($StackPtr) === T_RETURN) {
				$HasReturn = TRUE;
				break;
			}

			$StackPtr++;
		}

		if(!$HasReturn)
		$this->SubmitFix(
			static::FixReason,
			$this->GetContentFromStack($ClosePtr),
			"\treturn;\n{$Indent}}",
			$ClosePtr
		);
		
		return;
	}

}
