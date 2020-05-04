<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;

class ClassConstsDefinedUnderKeywordsSniff
extends NetherCS\Sniffers\ScopeClassConsts {

	const
	FixReason = 'NN: Class Consts must be defined under their keywords';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$Indent = $this->GetCurrentIndent();
		$Before = NULL;
		$ArrayPtr = NULL;

		$Before = $this->File->FindPrevious(
			[T_STATIC,T_PUBLIC,T_PROTECTED,T_PRIVATE,T_COMMA,T_CONST],
			($StackPtr-1),
			NULL
		);

		// but don't catch const arrays.

		$ArrayPtr = $this->File->FindPrevious(
			[ T_OPEN_SQUARE_BRACKET, T_CONST, T_OPEN_SHORT_ARRAY, T_ARRAY ],
			($StackPtr-1),
			NULL
		);

		if($ArrayPtr && $this->GetTypeFromStack($ArrayPtr) !== T_CONST)
		return;

		////////

		if($this->GetTypeFromStack($this->StackPtr-1) === T_WHITESPACE) {
			if($this->GetLineFromStack($Before) === $this->GetLineFromStack($this->StackPtr)) {
				$this->SubmitFix(
					sprintf('%s (%s)',static::FixReason,$this->GetContentfromStack($this->StackPtr)),
					$this->GetContentFromStack($this->StackPtr-1),
					"\n{$Indent}",
					($this->StackPtr-1)
				);
			}
		}

		elseif($this->GetTypeFromStack($this->StackPtr-1) === T_COMMA) {
			$this->SubmitFix(
				sprintf('%s (%s)',static::FixReason,$this->GetContentfromStack($this->StackPtr)),
				$this->GetContentFromStack($this->StackPtr-1),
				",\n{$Indent}",
				($this->StackPtr-1)
			);
		}

		return;
	}

}
