<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS as NetherCS;

class NamespaceMultiUseMultiLineSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_USE ];

	const
	FixReason = 'NN: Namespace multi-use must be multi-lined';

	public function
	Execute():
	void {

		$StackPtr = $this->StackPtr;
		$EndPtr = $this->FindNext([T_SEMICOLON,T_OPEN_PARENTHESIS],$StackPtr);
		$Indent = $this->GetCurrentIndent();
		$NextPtr = NULL;
		$Fixed = FALSE;

		// check if we actually found lambda use statement rather than a
		// namespace use alias or class use trait.

		if($this->GetTypeFromStack($EndPtr) === T_OPEN_PARENTHESIS)
		return;

		// loop over the remaining.

		$this->TransactionBegin();

		while($StackPtr <= $EndPtr) {
			if($this->GetTypeFromStack($StackPtr) !== T_COMMA)
			goto TryNextItem;

			if(!($NextPtr = $this->FindNextNot([T_WHITESPACE,T_SEMICOLON],$StackPtr,$EndPtr)))
			goto TryNextItem;

			if($this->GetLineFromStack($StackPtr) !== $this->GetLineFromStack($NextPtr))
			goto TryNextItem;

			if($Fixed = $this->FixBegin(static::FixReason,$StackPtr)) {
				if($this->GetTypeFromStack(($NextPtr-1)) === T_WHITESPACE)
				$this->FixReplace("\n{$Indent}",($NextPtr-1));
				elseif($this->GetTypeFromStack(($NextPtr-1)) === T_COMMA)
				$this->FixReplace(",\n{$Indent}",($NextPtr-1));
			}

			TryNextItem:
			$StackPtr++;
		}

		if($Fixed) {
			$NextPtr = $this->FindNextNot([T_WHITESPACE,T_SEMICOLON],$this->StackPtr,$EndPtr);

			if($this->GetTypeFromStack(($NextPtr-1)) === T_WHITESPACE)
			$this->FixReplace("\n{$Indent}",($NextPtr-1));
			elseif($this->GetTypeFromStack(($NextPtr-1)) === T_COMMA)
			$this->FixReplace(",\n{$Indent}",($NextPtr-1));
		}

		$this->TransactionCommit();

		return;
	}

	protected function
	Allow():
	bool {
	/*//
	we cannot use the scoped template here because the namespace clause without braces
	(e.g. the one you should be using) does not register as a scope. so we only want
	to find use statements that are in the root or verified as a namespace scope to
	not catch class traits here.
	//*/

		$ScopePtr = $this->GetCurrentScope();

		if($ScopePtr !== NULL)
		if($this->GetTypeFromStack($ScopePtr) !== T_NAMESPACE)
		return FALSE;

		return TRUE;
	}

}
