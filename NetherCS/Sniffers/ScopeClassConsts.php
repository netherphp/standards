<?php

namespace NetherCS\Sniffers;

use \NetherCS as NetherCS;

abstract class ScopeClassConsts
extends NetherCS\SniffScopedTemplate {

	protected
	$TokenScopes = [ T_CLASS, T_ANON_CLASS, T_INTERFACE, T_TRAIT ],
	$TokenTypes  = [ T_STRING ];

	protected function
	Allow():
	Bool {
	/*//
	the T_VARIABLE token will also catch function arguments since they are
	technically in the same scope in a way. filter those out.
	//*/

		$StackPtr = $this->StackPtr;
		$Before = NULL;

		// it looks like we found some arraty values.

		$ArrayPtr = $this->File->FindPrevious(
			[ T_OPEN_SQUARE_BRACKET, T_CONST, T_OPEN_SHORT_ARRAY, T_ARRAY ],
			($StackPtr-1),
			NULL
		);

		if($ArrayPtr && $this->GetTypeFromStack($ArrayPtr) !== T_CONST)
		return FALSE;

		// if the above is a correct assumption about syntax the following
		// checks may not even be needed.

		do $Before = $this->GetTypeFromStack(--$StackPtr);
		while($Before === T_WHITESPACE);

		switch($Before) {
			case T_CONST:
			case T_PUBLIC:
			case T_PROTECTED:
			case T_PRIVATE:
			case T_FINAL:
			case T_STATIC:
			case T_COMMA:
				return TRUE;
			break;
		}

		return FALSE;
	}

}


