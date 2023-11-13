<?php

namespace NetherCS\Sniffs\Coding;

use \NetherCS as NetherCS;
use \PHP_CodeSniffer as PHPCS;

class ClassMethodsMustHaveVisiblitySniff
extends NetherCS\Sniffers\ScopeClassMethod {

	protected mixed
	$File;

	const
	FixReason = 'NN: Class Methods must have a visiblity keyword (%s)';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$HasKeyword = FALSE;
		$FuncName = $this->GetDeclarationName($StackPtr);

		$Find = [ T_STATIC, T_PUBLIC, T_PROTECTED, T_PRIVATE, T_WHITESPACE, T_ABSTRACT, T_FINAL ];
		$Start = $this->File->FindPrevious($Find,($StackPtr-1),NULL,TRUE) + 1;

		while($Start < $StackPtr) {
			switch($this->GetTypeFromStack($Start)) {
				case T_PUBLIC:
				case T_PROTECTED:
				case T_PRIVATE:
					$HasKeyword = TRUE;
				break;
			}
			$Start++;
		}

		if(!$HasKeyword)
		$this->Fix(
			sprintf(static::FixReason,$FuncName),
			"public function",
			$this->StackPtr
		);

		return;
	}

}
