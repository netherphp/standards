<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS as NetherCS;

class ClassConstsDefinedUnderKeywordsSniff
extends NetherCS\Sniffers\ScopeClassConsts {

	const
	FixReason = 'NN: Class Consts must be defined under their keywords (%s)';

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

		if($this->GetTypeFromStack($this->StackPtr-1) === T_WHITESPACE) {
			if($this->GetLineFromStack($Before) === $this->GetLineFromStack($this->StackPtr)) {
				$this->Fix(
					sprintf(static::FixReason,$this->GetContentfromStack($this->StackPtr)),
					"\n{$Indent}",
					($this->StackPtr-1)
				);
			}
		}

		elseif($this->GetTypeFromStack($this->StackPtr-1) === T_COMMA) {
			$this->Fix(
				sprintf(static::FixReason,$this->GetContentfromStack($this->StackPtr)),
				",\n{$Indent}",
				($this->StackPtr-1)
			);
		}

		return;
	}

}
