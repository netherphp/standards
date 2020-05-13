<?php

namespace NetherCS\Sniffs\Coding;

use \NetherCS as NetherCS;
use \PHP_CodeSniffer as PHPCS;

class DisallowArrayDerpCommaSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_ARRAY, T_OPEN_SHORT_ARRAY ];

	const
	FixReason = 'NN: Arrays must not contain Derp Comma';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$EndPtr = NULL;
		$DerpPtr = NULL;

		switch($this->GetTypeFromStack()) {
			case T_ARRAY:
				$EndPtr = $this->TokenGetCloseParen($StackPtr);
			break;
			case T_OPEN_SHORT_ARRAY:
				$EndPtr = $this->TokenGetCloseBrack($StackPtr);
			break;
		}

		if(!$EndPtr)
		return;

		// find what the last thing before the closer was.

		$DerpPtr = $this->FindPrevNot([T_WHITESPACE],$EndPtr,$StackPtr);

		// and if it was a comma you should feel bad.

		if($this->GetTypeFromStack($DerpPtr) === T_COMMA)
		$this->Fix(static::FixReason,'',$DerpPtr);

		return;
	}


}
