<?php

namespace NetherCS\Sniffs\Formatting;

use \NetherCS;

class ClassConstsNamesPascalCaseSniff
extends NetherCS\Sniffers\ScopeClassConsts {

	const
	FixReason = 'NN: Class Consts must be PascalCase';

	public function
	Execute():
	Void {

		$StackPtr = $this->StackPtr;
		$Indent = $this->GetCurrentIndent();
		$Current = $this->GetContentFromStack($this->StackPtr);
		$Expected = static::ConvertToPascalCase($Current);

		if($Current !== $Expected) {
			$this->SubmitFixAndShow(
				static::FixReason,
				$Current,
				$Expected,
				$this->StackPtr
			);
		}

		return;
	}

}
