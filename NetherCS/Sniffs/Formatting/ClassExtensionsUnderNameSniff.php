<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class ClassExtensionsUnderNameSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_CLASS, T_INTERFACE, T_TRAIT ];

	const
	FixReason = 'NN: Class Extends/Implements must be underneath the class (%s)';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$Type = NULL;
		$Next = NULL;
		$Whitespace = NULL;
		$FuncName = $this->GetDeclarationName($this->StackPtr);

		while(($Type = $this->GetTypeFromStack($StackPtr)) && $Type !== T_OPEN_CURLY_BRACKET) {
			if($Type === T_EXTENDS || $Type === T_IMPLEMENTS)
			if($this->GetTypeFromStack($StackPtr - 1) === T_WHITESPACE)
			if(trim($this->GetContentFromStack($StackPtr - 1)," \r") !== "\n")
			$this->Fix(
				sprintf(static::FixReason,$FuncName),
				"\n",
				($StackPtr - 1)
			);

			$StackPtr++;
		}

		return;
	}

}
