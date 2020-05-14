<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS as NetherCS;
use \PHP_CodeSniffer as PHPCS;

class ConstantsBoolNullAllCapsSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_TRUE, T_FALSE, T_NULL ];

	const
	FixReason = 'NN: TRUE/FALSE/NULL must be uppercase (%s)';

	public function
	Execute():
	Void {

		$Current = $this->GetContentFromStack();
		$Expected = strtoupper($Current);

		if($Current !== $Expected)
		$this->Fix(sprintf(static::FixReason,$Current),$Expected);

		return;
	}

}
