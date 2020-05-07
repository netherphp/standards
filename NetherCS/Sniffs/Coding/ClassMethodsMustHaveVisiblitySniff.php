<?php

namespace NetherCS\Sniffs\Coding;

use \NetherCS;
use \PHP_CodeSniffer as PHPCS;

class ClassMethodsMustHaveVisiblitySniff
extends NetherCS\Sniffers\ScopeClassMethod {

	const
	FixReason = 'NN: Class Methods must have a visiblity keyword';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$HasKeyword = FALSE;

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

		if(!$HasKeyword) {
			$this->SubmitFix(
				sprintf('%s (%s)',static::FixReason,$this->File->GetDeclarationName($StackPtr)),
				"function",
				"public function"
			);
		}

		return;
	}

}
