<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class ClassMethodsReturnTypeCasingSniff
extends NetherCS\Sniffers\ScopeClassMethod {

	const
	FixReason       = 'NN: Class Methods returning core types must be Uppercased',
	MetricName      = 'Methods Returning Core Types Casing',
	ResultIncorrect = 'Incorrect',
	ResultProper    = 'Proper';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$ReturnPtr = NULL;
		$Seek = NULL;
		$Current = NULL;
		$Expected = NULL;
		$DefaultTypes = [
			'Void', 'Int', 'Float', 'Double', 'String', 'Bool', 'Boolean', 'Array',
			'Callable', 'self'
		];
		$IsDefaultType = NULL;

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

		$Current = $this->GetContentFromStack($ReturnPtr);
		$IsDefaultType = array_search(
			strtoupper($Current),
			array_map('strtoupper',$DefaultTypes)
		);

		// if it is not one of the default types it is likely a class name
		// and we cannot tell you how to type other people's clases.
		if($IsDefaultType === FALSE)
		return;

		if($DefaultTypes[$IsDefaultType] !== $Current) {
			$this->SubmitFixAndShow(
				static::FixReason,
				$Current,
				$DefaultTypes[$IsDefaultType],
				$ReturnPtr
			);
		}

		$this->BumpMetric(static::MetricName,static::ResultProper);
		return;
	}

}
