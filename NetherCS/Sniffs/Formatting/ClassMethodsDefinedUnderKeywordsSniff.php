<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class ClassMethodsDefinedUnderKeywordsSniff
extends NetherCS\Sniffers\ScopeClassMethod {

	const
	FixReason = 'NN: Class Methods must be defined under their keywords (%s)';

	public function
	Execute():
	Void {

		$Indent = $this->GetCurrentIndent($this->StackPtr);
		$NamePtr = $this->GetFunctionNamePtr($this->StackPtr);

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
