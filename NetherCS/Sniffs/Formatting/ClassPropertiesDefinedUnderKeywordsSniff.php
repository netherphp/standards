<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;

class ClassPropertiesDefinedUnderKeywordsSniff
extends NetherCS\Sniffers\ScopeClassProperties {

	const
	FixReason = 'NN: Class Properties must be defined under their keywords (%s)';

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

		// if we found a propety that is on the same line as the keywords.
		// (or with space between before previous comma chained)

		if($this->GetTypeFromStack($this->StackPtr-1) === T_WHITESPACE) {
			if($this->GetLineFromStack($Before) === $this->GetLineFromStack($this->StackPtr)) {
				$Current = $this->GetContentFromStack($this->StackPtr);

				$this->Fix(
					sprintf(static::FixReason,$Current),
					"\n{$Indent}",
					($this->StackPtr-1)
				);
			}
		}

		// if we found a property directly after a comma.

		elseif($this->GetTypeFromStack($this->StackPtr-1) === T_COMMA) {
			$Current = $this->GetContentFromStack($this->StackPtr);

			$this->Fix(
				sprintf(static::FixReason,$Current),
				",\n{$Indent}",
				($this->StackPtr-1)
			);
		}

		return;
	}

}
