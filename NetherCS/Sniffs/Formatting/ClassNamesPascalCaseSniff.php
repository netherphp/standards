<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class ClassNamesPascalCaseSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_CLASS, T_INTERFACE, T_TRAIT ];

	const
	FixReason = 'NN: Classes/Interfaces/Traits must be PascalCased (%s)';

	public function
	Execute():
	Void {

		$StackPtr = $this->GetDeclarationNamePtr($this->StackPtr);
		$Type = $this->GetTypeFromStack($StackPtr);
		$Current = NULL;
		$Expected = NULL;

		if($Type !== T_STRING)
		return;

		$Current = $this->GetContentFromStack($StackPtr);
		$Expected = NetherCS\SniffGenericTemplate::ConvertToPascalCase($Current);

		if($Current !== $Expected)
		$this->Fix(
			sprintf(static::FixReason,$Current),
			$Expected,
			$StackPtr
		);

		return;
	}

}
