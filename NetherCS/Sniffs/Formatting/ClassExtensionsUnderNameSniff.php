<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class ClassExtensionsUnderNameSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_CLASS, T_INTERFACE, T_TRAIT ];

	const
	FixReason = 'NN: Class Extends/Implements must be underneath the class';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$Type = NULL;
		$Next = NULL;
		$Whitespace = NULL;

		while(($Type = $this->GetTypeFromStack($StackPtr)) && $Type !== T_OPEN_CURLY_BRACKET) {
			if($Type === T_EXTENDS || $Type === T_IMPLEMENTS)
			if($this->GetTypeFromStack($StackPtr - 1) === T_WHITESPACE)
			if(trim($this->GetContentFromStack($StackPtr - 1)," \r") !== "\n")
			$this->SubmitFix(
				sprintf('%s (%s)',static::FixReason,$this->File->GetDeclarationName($this->StackPtr)),
				$this->GetContentFromStack($StackPtr - 1),
				"\n",
				($StackPtr - 1)
			);

			$StackPtr++;
		}

		return;
	}

}
