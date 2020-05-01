<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class PascalCaseClassNamesSniff
extends NetherCS\SniffTemplate {

	protected
	$TokenTypes = [ T_CLASS, T_INTERFACE, T_TRAIT ];

	const
	FixReason       = 'Classes, Interfaces, and Traits, should be PascalCased',
	MetricName      = 'Pascal Cased Classes',
	ResultIncorrect = 'Incorrect',
	ResultProper    = 'Proper';

	public function
	Execute():
	Void {

		// T_CLASS T_WHITESPACE T_STRING
		// This    +1          +2

		$StackPtr = $this->StackPtr + 2;
		$Type = $this->GetTypeFromStack($StackPtr);
		$Current = NULL;
		$Expected = NULL;

		if($Type !== T_STRING)
		return;

		$Current = $this->GetContentFromStack($StackPtr);
		$Expected = NetherCS\SniffTemplate::ConvertToPascalCase($Current);

		if($Current !== $Expected) {
			$this->BumpMetric(static::MetricName,static::ResultIncorrect,$StackPtr);
			$this->SubmitFixAndShow(static::FixReason,$Current,$Expected,$StackPtr);
			return;
		}

		$this->BumpMetric(static::MetricName,static::ResultProper,$StackPtr);
		return;
	}

}
