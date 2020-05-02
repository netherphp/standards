<?php

namespace NetherCS\Sniffers;

use \NetherCS as NetherCS;

abstract class ScopeClassProperties
extends NetherCS\SniffScopedTemplate {

	protected
	$TokenScopes = [ T_CLASS, T_ANON_CLASS, T_INTERFACE, T_TRAIT ],
	$TokenTypes  = [ T_VARIABLE ];

	protected function
	Allow():
	Bool {
	/*//
	the T_VARIABLE token will also catch function arguments since they are
	technically in the same scope in a way. filter those out.
	//*/

		$StackPtr = $this->StackPtr;
		$Before = NULL;

		do $Before = $this->GetTypeFromStack(--$StackPtr);
		while($Before === T_WHITESPACE);

		switch($Before) {
			case T_VAR:
			case T_PUBLIC:
			case T_PROTECTED:
			case T_PRIVATE:
			case T_FINAL:
			case T_STATIC:
				return TRUE;
			break;
		}
		
		return FALSE;
	}

}


