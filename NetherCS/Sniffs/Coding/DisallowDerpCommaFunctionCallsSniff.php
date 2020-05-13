<?php

namespace NetherCS\Sniffs\Coding;

use \NetherCS as NetherCS;
use \PHP_CodeSniffer as PHPCS;

class DisallowDerpCommaFunctionCallsSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_STRING ];

	const
	FixReason = 'NN: Function calls must not contain Derp Comma (%s)';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$PrevPtr = $this->FindPrevNot([T_WHITESPACE],$StackPtr);
		$NextPtr = $this->FindNextNot([T_WHITESPACE],$StackPtr);
		$EndPtr = NULL;
		$DerpPtr = NULL;
		$Next = NULL;
		$Prev = NULL;
		$Current = NULL;

		if(!$PrevPtr || !$NextPtr)
		return;

		$Prev = $this->GetTypeFromStack($PrevPtr);
		$Next = $this->GetTypeFromStack($NextPtr);

		// does this look like a function call?

		if($Next !== T_OPEN_PARENTHESIS)
		return;

		// but not a function declaration?

		if($Prev === T_FUNCTION)
		return;

		// find its argument list.

		if(!$this->TokenHasCloseParen($NextPtr,$EndPtr))
		return;

		// find what the last thing before the closer is and
		// if it was a comma you should feel bad.

		$DerpPtr = $this->FindPrevNot([T_WHITESPACE],$EndPtr,$StackPtr);
		$Current = $this->GetContentFromStack();

		if($this->GetTypeFromStack($DerpPtr) === T_COMMA)
		$this->Fix(
			sprintf(static::FixReason,$Current),
			'', $DerpPtr
		);

		return;
	}


}
