<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class PascalCaseClassMethodNamesSniff
extends NetherCS\SniffClassMethodTemplate {

	const
	FixReason       = 'NN: Class Methods should be PascalCased',
	MetricName      = 'Pascal Cased Methods',
	ResultIncorrect = 'Incorrect',
	ResultProper    = 'Proper';

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

		while($Type !== T_STRING && $Type)
		$Type = $this->GetTypeFromStack(++$StackPtr);

		$Current = $this->GetContentFromStack($StackPtr);
		$Expected = NetherCS\SniffTemplate::ConvertMethodToPascalCase($Current);
		
		if($Current !== $Expected) {
			$this->BumpMetric(static::MetricName,static::ResultIncorrect,$StackPtr);
			$this->SubmitFixAndShow(static::FixReason,$Current,$Expected,$StackPtr);
			return;
		}

		$this->BumpMetric(static::MetricName,static::ResultProper,$StackPtr);
		return;
	}

}
