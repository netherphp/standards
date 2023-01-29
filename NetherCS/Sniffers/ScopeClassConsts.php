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
	bool {
	/*//
	the T_VARIABLE token will also catch function arguments since they are
	technically in the same scope in a way. filter those out.
	//*/

		$StackPtr = $this->StackPtr;
		$Before = NULL;
		$StackAdv = 0;

		// in hindsight this seemed kinda obvious.
		// bob from 2023-01-23

		if($this->GetContentFromStack($StackPtr) !== 'const')
		return FALSE;

		// typed properties in php 8, types apparently are also just T_STRING
		// because making something like T_DATATYPE would be too clever. so we
		// need to scan ahead a little just to make sure this isn't really a
		// typed property.

		for($StackAdv = 1; $StackAdv < 10; $StackAdv++)
		if($this->GetTypeFromStack($StackPtr+$StackAdv) !== T_WHITESPACE)
		break;

		// it looks like we found some function args.

		if(array_key_exists('nested_parenthesis',$this->Stack[$this->StackPtr]))
		if(count($this->Stack[$this->StackPtr]['nested_parenthesis']))
		return FALSE;

		// it looks like we found some array values.

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


