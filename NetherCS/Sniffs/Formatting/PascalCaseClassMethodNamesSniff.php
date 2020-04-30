<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class PascalCaseClassMethodNamesSniff
extends NetherCS\SniffClassMethodTemplate {

	const
	FixReason       = 'Class Methods should be PascalCased',
	MetricName      = 'Pascal Cased Methods',
	ResultIncorrect = 'Incorrect',
	ResultProper    = 'Proper';

	public function
	Execute():
	Void {

		// T_FUNCTION T_WHITESPACE T_STRING
		// This       +1          +2

		$StackPtr = $this->StackPtr + 2;
		$Type = $this->GetTypeFromStack($StackPtr);
		$Current = NULL;
		$Expected = NULL;

		if($Type !== T_STRING)
		return;

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
