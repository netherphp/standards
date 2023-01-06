<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS as NetherCS;

class FunctionReturnTypesUnderNameSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_FUNCTION ];

	const
	FixReason = 'NN: Method/Function Return Types underneath method name (%s)';

	public function
	Execute():
	void {

		$StackPtr = $this->StackPtr;
		$ReturnPtr = NULL;
		$NamePtr = NULL;
		$Seek = NULL;
		$Indent = $this->GetCurrentIndent();
		$Before = NULL;
		$Whitespace = NULL;
		$FuncName = $this->GetDeclarationName($this->StackPtr);

		// fast foward to the end of the definition.
		while(($Seek = $this->GetTypeFromStack($StackPtr)) && $Seek !== T_OPEN_CURLY_BRACKET && $Seek !== T_SEMICOLON) {

			// we found the start of the return type.
			if($Seek === T_COLON)
			$ReturnPtr = $StackPtr;

			// we found the name of the func.
			elseif($Seek === T_STRING && $ReturnPtr === NULL && $NamePtr === NULL)
			$NamePtr = $StackPtr;

			// we found the return type word.
			elseif(($Seek === T_STRING || $Seek === T_STATIC || $Seek === T_SELF || $Seek === T_NULLABLE) && $ReturnPtr !== NULL) {
				$ReturnPtr = $StackPtr;
				break;
			}

			$StackPtr++;
		}

		// bail if there is no return type.

		if(!$ReturnPtr)
		return;

		// anon func

		if(!$NamePtr)
		$NamePtr = $this->StackPtr;

		$Before = $this->GetTypeFromStack($ReturnPtr-1);

		// if the thing right before the return type is the colon then there is
		// not any whitespace at all.

		if($Before === T_COLON) {
			$this->Fix(
				sprintf(static::FixReason,$FuncName),
				":\n{$Indent}",
				($ReturnPtr-1)
			);
		}

		if($Before === T_WHITESPACE) {
			$Whitespace = $this->GetContentFromStack($ReturnPtr-1);

			// it is on the same line.
			if($this->GetLineFromStack($NamePtr) === $this->GetLineFromStack($ReturnPtr))
			$this->Fix(
				sprintf(static::FixReason,$FuncName),
				"\n{$Indent}",
				($ReturnPtr-1)
			);

			// it is on the next line but the indent looks fucked.
			elseif($Whitespace !== $Indent && $Whitespace !== "\n") {
				$this->Fix(
					sprintf(static::FixReason,$FuncName),
					"{$Indent}",
					($ReturnPtr-1)
				);
			}
		}

		return;
	}

}
