<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class ClassPropertiesPascalCaseSniff
extends NetherCS\SniffTemplate {

	protected
	$TokenTypes = [ T_PROPERTY ];

	const
	FixReason = 'NN: Class Properties should be PascalCased.';

	public function
	Execute():
	Void {

		var_dump($this->GetContentFromStack($this->StackPtr));

		return;
	}

}
