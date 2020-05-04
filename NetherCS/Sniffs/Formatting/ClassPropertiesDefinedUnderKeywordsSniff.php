<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;

class ClassPropertiesDefinedUnderKeywordsSniff
extends NetherCS\Sniffers\ScopeClassProperties {

	const
	FixReason = 'NN: Class Properties must be defined under their keywords';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$Indent = $this->GetCurrentIndent();
		$Before = NULL;
		$Current = NULL;

		$Before = $this->File->FindPrevious(
			[T_STATIC,T_VAR,T_PUBLIC,T_PROTECTED,T_PRIVATE,T_COMMA],
			($StackPtr-1),
			NULL
		);

		if($this->GetTypeFromStack($this->StackPtr-1) === T_WHITESPACE) {
			if($this->GetLineFromStack($Before) === $this->GetLineFromStack($this->StackPtr)) {
				$Current = $this->GetContentFromStack($this->StackPtr);

				$this->SubmitFix(
					sprintf('%s (%s)',static::FixReason,$Current),
					$Current,
					"\n{$Indent}",
					($this->StackPtr-1)
				);
			}
		}

		elseif($this->GetTypeFromStack($this->StackPtr-1) === T_COMMA) {
			$Current = $this->GetContentFromStack($this->StackPtr);

			$this->SubmitFix(
				sprintf('%s (%s)',static::FixReason,$Current),
				$Current,
				",\n{$Indent}",
				($this->StackPtr-1)
			);
		}

		return;
	}

}
