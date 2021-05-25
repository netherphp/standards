<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS as NetherCS;
use \PHP_CodeSniffer as PHPCS;

class FunctionReturnTypesCasingSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_FUNCTION ];

	const
	FixReason = 'NN: Method/Function returning core types must be lowercased (%s)';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$ReturnPtr = NULL;
		$Seek = NULL;
		$Current = NULL;
		$Expected = NULL;
		$IsDefaultType = NULL;

		// fast foward to the end of the definition.
		while(($Seek = $this->GetTypeFromStack($StackPtr)) && $Seek !== T_OPEN_CURLY_BRACKET) {

			// we found the start of the return type.
			if($Seek === T_COLON)
			$ReturnPtr = $StackPtr;

			// we found the return type word.
			elseif(($Seek === T_STRING || $Seek === T_SELF) && $ReturnPtr !== NULL) {
				$ReturnPtr = $StackPtr;
				break;
			}

			$StackPtr++;
		}

		// bail if there is no return type.

		if(!$ReturnPtr)
		return;

		$Current = trim($this->GetContentFromStack($ReturnPtr));
		$IsDefaultType = static::GetDefaultType($Current);

		if($IsDefaultType)
		$Expected = strtolower($Current);
		else
		$Expected = $this->ConvertToPascalCase($Current);

		// don't attack the self keyword.

		if(strtolower($Current) === 'self')
		return;

		// if it is not one of the default types it is likely a class name
		// and we cannot tell you how to type other people's clases.
		if($IsDefaultType === NULL)
		return;

		if($Current === $Expected)
		return;

		$this->Fix(
			sprintf(static::FixReason,$Current),
			$Expected,
			$ReturnPtr
		);

		return;
	}

}
