<?php

namespace NetherCS\Sniffs\Coding;

use \NetherCS;

class NamespaceExplicitUseSniff
extends NetherCS\SniffGenericTemplate {

	protected
	$TokenTypes = [ T_USE ];

	const
	FixReason = 'NN: Namespace USE must have explicit AS (%s)';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$EndPtr = $this->FindNext([T_SEMICOLON],$StackPtr);
		$Seek = NULL;
		$HasAlias = NULL;
		$RefPtr = NULL;
		$RefName = NULL;

		while($StackPtr <= $EndPtr) {
			$Seek = $this->GetTypeFromStack($StackPtr);

			if($Seek === T_AS) {
				$HasAlias = TRUE;
				goto TryNextItem;
			}

			if($Seek === T_COMMA || $Seek === T_SEMICOLON) {
				if(!$HasAlias) {
					$RefPtr = $this->FindPrev([T_STRING],$StackPtr,$this->StackPtr);
					$RefName = $this->GetContentFromStack($RefPtr);

					$this->Fix(
						sprintf(static::FixReason,$RefName),
						sprintf(' as %s%s',$RefName,$this->GetContentFromStack($StackPtr)),
						$StackPtr
					);
				}

				$HasAlias = FALSE;
			}

			TryNextItem:
			$StackPtr++;
		}

		return;
	}

	protected function
	Allow():
	Bool {
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
