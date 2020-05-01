<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class ClassMethodsReturnTypeUnderNameSniff
extends NetherCS\SniffClassMethodTemplate {

	const
	FixReason       = 'NN: Class Methods Return Types underneath method name',
	MetricName      = 'Methods Return Types Positioned Correctly',
	ResultIncorrect = 'Incorrect',
	ResultProper    = 'Proper';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$ReturnPtr = NULL;
		$Seek = NULL;
		$Indent = $this->GetCurrentIndent();
		$Before = NULL;
		$Whitespace = NULL;

		// fast foward to the end of the definition.
		while(($Seek = $this->GetTypeFromStack($StackPtr)) !== T_OPEN_CURLY_BRACKET) {

			// we found the start of the return type.
			if($Seek === T_COLON)
			$ReturnPtr = $StackPtr;

			// we found the return type word.
			elseif($Seek === T_STRING && $ReturnPtr !== NULL) {
				$ReturnPtr = $StackPtr;
				break;
			}

			$StackPtr++;
		}

		// bail if there is no return type.

		if(!$ReturnPtr)
		return;

		$Before = $this->GetTypeFromStack($ReturnPtr-1);
		
		// if the thing right before the return type is the colon then there is
		// not any whitespace at all.

		if($Before === T_COLON) {
			$this->SubmitFix(
				sprintf('%s (%s)',static::FixReason,$this->File->GetDeclarationName($this->StackPtr)),
				':',
				":\n{$Indent}",
				($ReturnPtr-1)
			);
		}

		if($Before === T_WHITESPACE) {
			$Whitespace = $this->GetContentFromStack($ReturnPtr-1);

			// it is on the same line.
			if($this->GetLineFromStack($this->StackPtr) === $this->GetLineFromStack($ReturnPtr))
			$this->SubmitFix(
				sprintf('%s (%s)',static::FixReason,$this->File->GetDeclarationName($this->StackPtr)),
				$Whitespace,
				"\n{$Indent}",
				($ReturnPtr-1)
			);

			// it is on the next line but the indent looks fucked.
			elseif($Whitespace !== $Indent) {
				$this->SubmitFix(
					sprintf('%s (%s)',static::FixReason,$this->File->GetDeclarationName($this->StackPtr)),
					$Whitespace,
					"{$Indent}",
					($ReturnPtr-1)
				);	
			}
		}

		return;
	}

}
