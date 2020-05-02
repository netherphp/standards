<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class ClassNamesPascalCaseSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_CLASS, T_INTERFACE, T_TRAIT ];

	const
	FixReason       = 'NN: Classes, Interfaces, and Traits, must be PascalCased',
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
		$Expected = NetherCS\SniffGenericTemplate::ConvertToPascalCase($Current);

		if($Current !== $Expected) {
			$this->BumpMetric(static::MetricName,static::ResultIncorrect,$StackPtr);
			$this->SubmitFixAndShow(static::FixReason,$Current,$Expected,$StackPtr);
			return;
		}

		$this->BumpMetric(static::MetricName,static::ResultProper,$StackPtr);
		return;
	}

}
