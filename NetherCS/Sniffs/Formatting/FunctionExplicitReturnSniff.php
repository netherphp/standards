<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class FunctionExplicitReturnSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_FUNCTION ];

	const
	FixReason = 'NN: Methods and Functions must explicitly return';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$OpenPtr = NULL;
		$ClosePtr = NULL;
		$Indent = NULL;
		$HasReturn = FALSE;
		$Seek = NULL;


		while(($Seek = $this->GetTypeFromStack($StackPtr)) && $Seek !== T_OPEN_CURLY_BRACKET && $Seek !== T_SEMICOLON)
		$StackPtr++;

		// now we know where this function body starts ane ends.

		$OpenPtr = $this->Stack[$StackPtr]['scope_opener'];
		$ClosePtr = $this->Stack[$StackPtr]['scope_closer'];

		// don't mess around with prototype that have no bodies

		if(!$OpenPtr)
		return;

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

		if(!$HasReturn) {

			// if we found a really empty method.

			if($this->GetLineFromStack($OpenPtr) === $this->GetLineFromStack($ClosePtr)) {
				if($ClosePtr - $OpenPtr === 1) {
					$this->SubmitFix(
						static::FixReason,
						$this->GetContentFromStack($ClosePtr),
						"\n{$Indent}\treturn;\n{$Indent}}",
						$ClosePtr
					);
				}

				elseif($this->GetTypeFromStack($ClosePtr-1) === T_WHITESPACE) {
					$this->SubmitFix(
						static::FixReason,
						$this->GetContentFromStack($ClosePtr),
						"\n{$Indent}\treturn;\n{$Indent}",
						($ClosePtr-1)
					);
				}
			}

			// normal methods.

			else {
				$this->SubmitFix(
					static::FixReason,
					$this->GetContentFromStack($ClosePtr),
					"\treturn;\n{$Indent}}",
					$ClosePtr
				);
			}

		}

		return;
	}

}
