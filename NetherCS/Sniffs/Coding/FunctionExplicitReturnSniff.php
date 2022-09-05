<?php

namespace NetherCS\Sniffs\Coding;

use \NetherCS as NetherCS;
use \PHP_CodeSniffer as PHPCS;

class FunctionExplicitReturnSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_FUNCTION, T_CLOSURE ];

	const
	FixReason = 'NN: Method/Function must explicitly return (%s)';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$OpenPtr = NULL;
		$ClosePtr = NULL;
		$Indent = NULL;
		$HasReturn = FALSE;
		$Seek = NULL;
		$FuncName = $this->GetDeclarationName($this->StackPtr);


		while(($Seek = $this->GetTypeFromStack($StackPtr)) && $Seek !== T_OPEN_CURLY_BRACKET && $Seek !== T_SEMICOLON)
		$StackPtr++;

		// now we know where this function body starts ane ends.

		if($this->GetTypeFromStack($StackPtr) === T_SEMICOLON)
		return;

		$OpenPtr = $this->Stack[$StackPtr]['scope_opener'];
		$ClosePtr = $this->Stack[$StackPtr]['scope_closer'];

		// don't mess around with prototype that have no bodies

		if(!$OpenPtr)
		return;

		// don't mess around with never functions.

		$Never = $this->FindNext([ T_COLON ], $this->StackPtr, $ClosePtr);
		if($Never) {
			$NeverEnd = $this->FindNext([ T_OPEN_CURLY_BRACKET, T_SEMICOLON ], $Never) ?? $ClosePtr;

			if($NeverEnd);
			while($Never < $NeverEnd) {
				$Never += 1;

				if($this->GetTypeFromStack($Never) === T_STRING)
				if($this->GetContentFromStack($Never) === 'never')
				return;
			}
		}

		// we can determine the indent for this function.

		$Indent = $this->GetCurrentIndent($this->StackPtr);

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
					$this->Fix(
						sprintf(static::FixReason,$FuncName),
						"\n{$Indent}\treturn;\n{$Indent}}",
						$ClosePtr
					);
				}

				elseif($this->GetTypeFromStack($ClosePtr-1) === T_WHITESPACE) {
					$this->Fix(
						sprintf('%s (%s)',static::FixReason,$FuncName),
						"\n{$Indent}\treturn;\n{$Indent}",
						($ClosePtr-1)
					);
				}
			}

			// normal methods.

			else {
				$this->Fix(
					sprintf(static::FixReason,$FuncName),
					"\treturn;\n{$Indent}}",
					$ClosePtr
				);
			}

		}

		return;
	}

}
