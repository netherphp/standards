<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;

class ClassTraitsUseMultiLineSniff
extends NetherCS\SniffScopedTemplate {

	protected
	$TokenScopes = [ T_CLASS ];

	protected
	$TokenTypes = [ T_USE ];

	const
	FixReason = 'NN: Class traits must be multi-lined';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$Indent = $this->GetCurrentIndent();
		$EndPtr = $this->FindNext([T_SEMICOLON,T_OPEN_CURLY_BRACKET],$StackPtr);
		$NextPtr = $this->FindNextNot([T_WHITESPACE,T_SEMICOLON,T_OPEN_CURLY_BRACKET],$StackPtr,$EndPtr);
		$Fixed = FALSE;

		$this->TransactionBegin();

		// find out if the use is on the same line as the first thing.

		if($this->GetLineFromStack($NextPtr) === $this->GetLineFromStack($StackPtr)) {
			if($Fixed = $this->FixBegin(static::FixReason,$StackPtr)) {
				if($this->GetTypeFromStack(($NextPtr-1)) === T_WHITESPACE)
				$this->FixReplace("\n{$Indent}",($NextPtr-1));
				elseif($this->GetTypeFromStack(($NextPtr-1)) === T_COMMA)
				$this->FixReplace(",\n{$Indent}",($NextPtr-1));
			}
		}

		// find out if this is multiuse and multiline them.

		while($StackPtr <= $EndPtr) {
			if($this->GetTypeFromStack($StackPtr) !== T_COMMA)
			goto TryNextItem;

			if(!($NextPtr = $this->FindNextNot([T_WHITESPACE,T_SEMICOLON,T_OPEN_CURLY_BRACKET],$StackPtr,$EndPtr)))
			goto TryNextItem;

			if($this->GetLineFromStack($StackPtr) !== $this->GetLineFromStack($NextPtr))
			goto TryNextItem;

			if($Fixed || ($Fixed = $this->FixBegin(static::FixReason,$StackPtr))) {
				if($this->GetTypeFromStack(($NextPtr-1)) === T_WHITESPACE)
				$this->FixReplace("\n{$Indent}",($NextPtr-1));
				elseif($this->GetTypeFromStack(($NextPtr-1)) === T_COMMA)
				$this->FixReplace(",\n{$Indent}",($NextPtr-1));
			}

			TryNextItem:
			$StackPtr++;
		}

		$this->TransactionCommit();

		return;
	}

}
