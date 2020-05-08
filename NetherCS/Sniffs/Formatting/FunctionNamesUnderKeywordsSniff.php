<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class FunctionNamesUnderKeywordsSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_FUNCTION ];

	const
	FixReason = 'NN: Method/Function names must be defined under their keywords (%s)';

	public function
	Execute():
	Void {

		$Indent = $this->GetCurrentIndent($this->StackPtr);
		$NamePtr = $this->GetDeclarationNamePtr($this->StackPtr);

		// we found an lambda.
		if(!$NamePtr)
		return;

		if($this->GetLineFromStack($this->StackPtr) === $this->GetLineFromStack($NamePtr))
		$this->Fix(
			sprintf(static::FixReason,$this->GetContentFromStack($NamePtr)),
			"{$this->GetEOL()}{$Indent}",
			($this->StackPtr+1)
		);

		return;
	}
}
