<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;

class ClassConstsDefinedUnderKeywordsSniff
extends NetherCS\Sniffers\ScopeClassConsts {

	const
	FixReason = 'NN: Class Constants must be defined under their keywords.';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$Indent = $this->GetCurrentIndent();
		$Before = NULL;

		$Before = $this->File->FindPrevious(
			[T_STATIC,T_PUBLIC,T_PROTECTED,T_PRIVATE,T_COMMA,T_CONST],
			($StackPtr-1),
			NULL
		);

		if($this->GetTypeFromStack($this->StackPtr-1) === T_WHITESPACE) {
			if($this->GetLineFromStack($Before) === $this->GetLineFromStack($this->StackPtr)) {
				$this->SubmitFix(
					static::FixReason,
					$this->GetContentFromStack($this->StackPtr-1),
					"\n{$Indent}",
					($this->StackPtr-1)
				);
			}
		}

		elseif($this->GetTypeFromStack($this->StackPtr-1) === T_COMMA) {
			$this->SubmitFix(
				static::FixReason,
				$this->GetContentFromStack($this->StackPtr-1),
				",\n{$Indent}",
				($this->StackPtr-1)
			);
		}

		return;
	}

}
