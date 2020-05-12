<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS as NetherCS;
use \PHP_CodeSniffer as PHPCS;

class NamespaceNamesPascalCaseSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_NAMESPACE ];

	const
	FixReason = 'NN: Namespace names must be PascalCased (%s)';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$EndPtr = $this->FindNext([T_SEMICOLON,T_OPEN_CURLY_BRACKET],$StackPtr);
		$Current = NULL;
		$Expected = NULL;

		while($StackPtr < $EndPtr) {

			if($this->GetTypeFromStack($StackPtr) !== T_STRING)
			goto TryNextItem;

			$Current = $this->GetContentFromStack($StackPtr);
			$Expected = static::ConvertToPascalCase($Current);

			if($Current === $Expected)
			goto TryNextItem;

			$this->Fix(
				sprintf(static::FixReason,$Current),
				$Expected,
				$StackPtr
			);

			TryNextItem:
			$StackPtr++;
		}

		return;
	}

}
