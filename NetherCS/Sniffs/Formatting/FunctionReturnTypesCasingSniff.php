<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class FunctionReturnTypesCasingSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_FUNCTION ];

	const
	FixReason = 'NN: Method/Function returning core Types must be Uppercased (%s)';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$ReturnPtr = NULL;
		$Seek = NULL;
		$Current = NULL;
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

		$Current = $this->GetContentFromStack($ReturnPtr);
		$IsDefaultType = static::GetDefaultType($Current);

		// don't attack the self keyword.

		if(strtolower($Current) === 'self')
		return;

		// if it is not one of the default types it is likely a class name
		// and we cannot tell you how to type other people's clases.
		if($IsDefaultType === FALSE)
		return;

		if(static::$DefaultTypes[$IsDefaultType] !== $Current) {
			$this->Fix(
				sprintf(static::FixReason,$Current),
				static::$DefaultTypes[$IsDefaultType],
				$ReturnPtr
			);
		}

		return;
	}

}
